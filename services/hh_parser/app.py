from flask import Flask, jsonify, request
import requests

app = Flask(__name__)

HH_API_URL = "https://api.hh.ru/vacancies"

@app.route('/search', methods=['GET'])
def search():
    # Get search parameters from request
    query    = request.args.get('query', '')
    area     = request.args.get('area', 113)     # Region (113 = Russia)
    schedule = request.args.get('schedule', '')   # Schedule type (remote/office)

    if not query:
        return jsonify({'error': 'Введите поисковый запрос'}), 400

    headers = {
        'User-Agent': 'bozheslav.ru/1.0 (mfadeev117@gmail.com)',
        'Accept': 'application/json',
    }

    # Build request params
    params = {
        'text':     query,
        'area':     area,
        'per_page': 100,
        'page':     0,
    }

    # Add schedule filter if provided (remote = удалённая работа)
    if schedule:
        params['schedule'] = schedule

    try:
        response = requests.get(HH_API_URL, headers=headers, params=params, timeout=10)
        data = response.json()

        vacancies = []
        for item in data.get('items', []):
            # Salary info
            salary        = item.get('salary')
            salary_from   = salary.get('from') if salary else None
            salary_to     = salary.get('to') if salary else None
            currency      = salary.get('currency') if salary else None

            # Experience (stub values from API)
            experience    = item.get('experience', {}).get('name', 'Не указан')

            # Schedule type (remote/office/hybrid)
            schedule_info = item.get('schedule', {}).get('name', 'Не указан')

            vacancies.append({
                'id':          item.get('id'),
                'name':        item.get('name'),
                'employer':    item.get('employer', {}).get('name'),
                'city':        item.get('area', {}).get('name'),       # Region/City
                'salary_from': salary_from,
                'salary_to':   salary_to,
                'currency':    currency,
                'experience':  experience,   # Experience required
                'schedule':    schedule_info, # Remote or office
                'url':         item.get('alternate_url'),
                'published':   item.get('published_at', '')[:10],
            })

        return jsonify({
            'vacancies': vacancies,
            'total':     data.get('found', 0),
            'shown':     len(vacancies)
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=False)
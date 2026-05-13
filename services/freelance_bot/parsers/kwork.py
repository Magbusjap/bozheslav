import requests
import json

KEYWORDS = [
    "верстка", "вёрстка", "laravel", "php", "html", "css",
    "лендинг", "сайт", "парсер", "python", "бэкенд", "backend",
    "wordpress", "wp", "figma", "адаптив"
]

def fetch_jobs() -> list[dict]:
    url = "https://kwork.ru/projects?c=11&type=1"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"
    }

    try:
        response = requests.get(url, headers=headers, timeout=10)
        text = response.text

        idx_start = text.find('"wants":[')
        if idx_start == -1:
            print("Данные не найдены")
            return []

        idx_start = idx_start + len('"wants":')

        # Находим конец массива по балансу скобок
        depth = 0
        idx_end = idx_start
        for i, ch in enumerate(text[idx_start:], idx_start):
            if ch == '[':
                depth += 1
            elif ch == ']':
                depth -= 1
                if depth == 0:
                    idx_end = i + 1
                    break

        wants = json.loads(text[idx_start:idx_end])
        jobs = []

        for want in wants:
            title = want.get("name", "")
            desc = want.get("description", "")
            budget = str(want.get("priceLimit", "договорная"))
            link = f"https://kwork.ru/projects/{want.get('id', '')}/view"
            proposals = int(want.get("wantGetSumOrderCount", 0))
            hired_rate = 50

            text_check = (title + " " + desc).lower()
            if any(kw in text_check for kw in KEYWORDS):
                jobs.append({
                    "title": title,
                    "description": desc[:500],
                    "budget": budget + " руб.",
                    "link": link,
                    "source": "kwork",   # ← добавить
                    "proposals": proposals,
                    "hired_rate": hired_rate
                })

        return jobs

    except Exception as e:
        print(f"Kwork парсер ошибка: {e}")
        return []
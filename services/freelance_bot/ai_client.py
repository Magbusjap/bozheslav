import os
import requests
import psycopg2
from dotenv import load_dotenv

load_dotenv()

API_KEY = os.getenv("ROUTERAI_API_KEY")
API_URL = "https://routerai.ru/api/v1/chat/completions"
MODEL = "x-ai/grok-4.20"

def get_option(key: str, default: str = "") -> str:
    try:
        conn = psycopg2.connect(os.getenv("DATABASE_URL"))
        cur = conn.cursor()
        cur.execute("SELECT value FROM options WHERE key = %s", (key,))
        result = cur.fetchone()
        cur.close()
        conn.close()
        return result[0] if result else default
    except:
        return default

def get_keywords() -> list[str]:
    keywords_str = get_option("bot_keywords", "верстка,вёрстка,laravel,php,html,css,лендинг,сайт,парсер,python,бэкенд,backend,wordpress,wp,figma,адаптив")
    return [kw.strip().lower() for kw in keywords_str.split(",") if kw.strip()]

def get_override_examples() -> str:
    try:
        conn = psycopg2.connect(os.getenv("DATABASE_URL"))
        cur = conn.cursor()
        cur.execute("""
            SELECT title, budget, verdict, human_override
            FROM bot_jobs
            WHERE human_override = true
            ORDER BY found_at DESC
            LIMIT 20
        """)
        rows = cur.fetchall()
        cur.close()
        conn.close()

        if not rows:
            return ""

        lines = ["Примеры где человек скорректировал решение AI:"]
        for title, budget, verdict, _ in rows:
            action = "ВЗЯЛ" if verdict == "take" else "ПРОПУСТИЛ"
            lines.append(f"- {action}: «{title}» (бюджет: {budget})")

        return "\n".join(lines)
    except:
        return ""

def build_system_prompt() -> str:
    role      = get_option("bot_role", "Ты помощник фрилансера.")
    system    = get_option("bot_system", "Анализируешь задания с бирж фриланса.")
    rules     = get_option("bot_rules", "Пропускай серые темы, нанято < 40%, бюджет < 1000р.")
    about     = get_option("bot_about", "Михаил, веб-разработчик.")
    overrides = get_override_examples()

    prompt = f"""{role}

О фрилансере:
{about}

{system}

Правила:
{rules}
"""

    if overrides:
        prompt += f"""
{overrides}

Учитывай эти примеры при анализе новых заданий.
"""

    return prompt
def analyze_job(title: str, description: str, budget: str, proposals: int, hired_rate: int) -> str:
    prompt = f"""Задание с биржи фриланса:

Название: {title}
Описание: {description}
Бюджет: {budget}
Предложений: {proposals}
Нанято: {hired_rate}% (процент заказов где заказчик нанял исполнителя)

Проанализируй и дай рекомендацию."""

    try:
        response = requests.post(
            API_URL,
            headers={
                "Authorization": f"Bearer {API_KEY}",
                "Content-Type": "application/json"
            },
            json={
                "model": MODEL,
                "messages": [
                    {"role": "system", "content": build_system_prompt()},
                    {"role": "user", "content": prompt}
                ],
                "max_tokens": 500
            },
            timeout=60
        )
        response.raise_for_status()
        data = response.json()
        return data["choices"][0]["message"]["content"]
    except Exception as e:
        return f"ВЕРДИКТ: ПРОПУСТИТЬ\nКАТЕГОРИЯ: ошибка\nКомментарий: AI недоступен ({type(e).__name__}: {str(e)[:100]})"
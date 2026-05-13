import requests
import json
from bs4 import BeautifulSoup

KEYWORDS = []  # берём из БД через ai_client.get_keywords()

def fetch_jobs() -> list[dict]:
    url = "https://www.fl.ru/projects/?kind=1"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"
    }

    try:
        response = requests.get(url, headers=headers, timeout=10)
        soup = BeautifulSoup(response.text, "html.parser")
        jobs = []

        cards = soup.select(".b-post")
        for card in cards:
            title_el = card.select_one(".b-post__title a")
            desc_el = card.select_one(".b-post__body")
            budget_el = card.select_one(".b-post__price")

            if not title_el:
                continue

            title = title_el.get_text(strip=True)
            desc = desc_el.get_text(strip=True) if desc_el else ""
            budget = budget_el.get_text(strip=True) if budget_el else "договорная"
            link = "https://www.fl.ru" + title_el["href"] if title_el.get("href") else ""

            from ai_client import get_keywords
            keywords = get_keywords()

            text = (title + " " + desc).lower()
            if any(kw in text for kw in keywords):
                jobs.append({
                    "title": title,
                    "description": desc[:500],
                    "budget": budget,
                    "link": link,
                    "source": "fl",
                    "proposals": 0,
                    "hired_rate": 50
                })

        return jobs

    except Exception as e:
        print(f"FL.ru парсер ошибка: {e}")
        return []
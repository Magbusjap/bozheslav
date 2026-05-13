from playwright.async_api import async_playwright

async def fetch_jobs() -> list[dict]:
    try:
        from ai_client import get_keywords
        keywords = get_keywords()

        async with async_playwright() as p:
            browser = await p.chromium.launch(headless=True)
            page = await browser.new_page()
            await page.goto("https://freelance.ru/projects/", timeout=30000)
            await page.wait_for_timeout(3000)

            cards = await page.query_selector_all(".project-item-default-card")
            jobs = []

            for card in cards:
                title_el = await card.query_selector("h2 a, h3 a, .project-title a")
                if not title_el:
                    continue

                title = await title_el.inner_text()
                title = title.strip()
                href = await title_el.get_attribute("href")
                link = f"https://freelance.ru{href}" if href and href.startswith("/") else href or ""

                desc_el = await card.query_selector(".project-description, .description, p")
                desc = (await desc_el.inner_text()).strip() if desc_el else ""

                budget_el = await card.query_selector("[class*='price'], [class*='budget']")
                budget = (await budget_el.inner_text()).strip() if budget_el else "договорная"

                text = (title + " " + desc).lower()
                if any(kw in text for kw in keywords):
                    jobs.append({
                        "title": title,
                        "description": desc[:500],
                        "budget": budget,
                        "link": link,
                        "source": "freelance",
                        "proposals": 0,
                        "hired_rate": 50
                    })

            await browser.close()
            return jobs

    except Exception as e:
        print(f"Freelance.ru парсер ошибка: {e}")
        return []
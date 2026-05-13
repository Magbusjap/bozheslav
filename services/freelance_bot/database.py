import os
import psycopg2
from dotenv import load_dotenv

load_dotenv()

def get_connection():
    return psycopg2.connect(os.getenv("DATABASE_URL"))

def is_job_seen(link: str) -> bool:
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("SELECT id FROM bot_jobs WHERE link = %s", (link,))
        result = cur.fetchone()
        cur.close()
        conn.close()
        return result is not None
    except Exception as e:
        print(f"DB ошибка: {e}")
        return False

def save_job(job: dict, analysis: str, verdict: str) -> int:
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO bot_jobs 
            (title, description, budget, link, source, category, verdict, ai_analysis, proposals, hired_rate, sent_to_telegram)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, false)
            ON CONFLICT (link) DO NOTHING
            RETURNING id
        """, (
            job['title'],
            job['description'],
            job['budget'],
            job['link'],
            job.get('source', 'kwork'),
            job.get('category', ''),
            verdict,
            analysis,
            job['proposals'],
            job['hired_rate']
        ))
        result = cur.fetchone()
        conn.commit()
        cur.close()
        conn.close()
        return result[0] if result else 0
    except Exception as e:
        print(f"DB ошибка сохранения: {e}")
        return 0

def save_verdict(job_id: int, verdict: str):
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute(
            "UPDATE bot_jobs SET verdict = %s WHERE id = %s",
            (verdict, job_id)
        )
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB ошибка вердикта: {e}")

def save_verdict(job_id: int, verdict: str):
    try:
        conn = get_connection()
        cur = conn.cursor()
        
        # Проверяем вердикт бота
        cur.execute("SELECT verdict FROM bot_jobs WHERE id = %s", (job_id,))
        result = cur.fetchone()
        ai_verdict = result[0] if result else None
        
        # Если человек выбрал иначе чем AI — это override
        human_override = (
            ai_verdict == 'skip' and verdict == 'take'
        ) or (
            ai_verdict == 'take' and verdict == 'skip'
        )
        
        cur.execute(
            "UPDATE bot_jobs SET verdict = %s, human_override = %s WHERE id = %s",
            (verdict, human_override, job_id)
        )
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB ошибка вердикта: {e}")


def get_bot_state(key: str) -> str:
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("SELECT value FROM bot_state WHERE key = %s", (key,))
        result = cur.fetchone()
        cur.close()
        conn.close()
        return result[0] if result else "off"
    except:
        return "off"

def set_bot_state(key: str, value: str):
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO bot_state (key, value) VALUES (%s, %s)
            ON CONFLICT (key) DO UPDATE SET value = EXCLUDED.value
        """, (key, value))
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB state ошибка: {e}")


def mark_sent(job_id: int):
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("UPDATE bot_jobs SET sent_to_telegram = true WHERE id = %s", (job_id,))
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB ошибка mark_sent: {e}")

def get_unsent_today() -> list[dict]:
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT id, title, description, budget, link, source, category, ai_analysis, verdict
            FROM bot_jobs
            WHERE DATE(found_at) = CURRENT_DATE
            AND sent_to_telegram = false
        """)
        rows = cur.fetchall()
        cur.close()
        conn.close()
        return [
            {
                'id': r[0],
                'title': r[1],
                'description': r[2],
                'budget': r[3],
                'link': r[4],
                'source': r[5],
                'category': r[6],
                'ai_analysis': r[7],
                'verdict': r[8],
                'proposals': 0,
                'hired_rate': 0,
            }
            for r in rows
        ]
    except Exception as e:
        print(f"DB ошибка get_unsent_today: {e}")
        return []

def write_log(event: str, message: str = "", level: str = "info"):
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO bot_logs (level, event, message, created_at, updated_at)
            VALUES (%s, %s, %s, NOW(), NOW())
        """, (level, event, message))
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB лог ошибка: {e}")

def update_last_command():
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO bot_logs (level, event, message, last_command_at, created_at, updated_at)
            VALUES ('info', 'user_command', '', NOW(), NOW(), NOW())
        """)
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB last_command ошибка: {e}")


def get_leads_random(limit: int = 5) -> list[dict]:
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT id, company_name, contact_email, website, niche, source, status, sent_to_telegram
            FROM bot_leads
            ORDER BY RANDOM()
            LIMIT %s
        """, (limit,))
        rows = cur.fetchall()
        cur.close()
        conn.close()
        return [
            {
                'id': r[0],
                'company_name': r[1],
                'contact_email': r[2],
                'website': r[3],
                'niche': r[4],
                'source': r[5],
                'status': r[6],
                'sent_to_telegram': r[7],
            }
            for r in rows
        ]
    except Exception as e:
        print(f"DB get_leads_random ошибка: {e}")
        return []

def get_leads_new(limit: int = 5) -> list[dict]:
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT id, company_name, contact_email, website, niche, source, status
            FROM bot_leads
            WHERE sent_to_telegram = false
            ORDER BY RANDOM()
            LIMIT %s
        """, (limit,))
        rows = cur.fetchall()
        cur.close()
        conn.close()
        return [
            {
                'id': r[0],
                'company_name': r[1],
                'contact_email': r[2],
                'website': r[3],
                'niche': r[4],
                'source': r[5],
                'status': r[6],
            }
            for r in rows
        ]
    except Exception as e:
        print(f"DB get_leads_new ошибка: {e}")
        return []

def mark_lead_sent(lead_id: int):
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("UPDATE bot_leads SET sent_to_telegram = true WHERE id = %s", (lead_id,))
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB mark_lead_sent ошибка: {e}")

def get_jobs_for_reanalysis(hours: int = 6, limit: int = 30) -> list[dict]:
    """Return jobs from last N hours with unclear/empty verdict for re-analysis."""
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT id, title, description, budget, link, source, proposals, hired_rate
            FROM bot_jobs
            WHERE found_at >= NOW() - INTERVAL '%s hours'
              AND (verdict = 'unclear' OR verdict = '' OR verdict IS NULL)
            ORDER BY found_at DESC
            LIMIT %s
        """, (hours, limit))
        rows = cur.fetchall()
        cur.close()
        conn.close()
        return [
            {
                'id': r[0],
                'title': r[1],
                'description': r[2] or '',
                'budget': r[3] or 'договорная',
                'link': r[4],
                'source': r[5] or 'unknown',
                'proposals': r[6] or 0,
                'hired_rate': r[7] or 0,
            }
            for r in rows
        ]
    except Exception as e:
        print(f"DB ошибка get_jobs_for_reanalysis: {e}")
        return []


def update_job_analysis(job_id: int, verdict: str, category: str, analysis: str):
    """Update verdict/category/ai_analysis for existing job and reset sent_to_telegram."""
    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""
            UPDATE bot_jobs
            SET verdict = %s,
                category = %s,
                ai_analysis = %s,
                sent_to_telegram = false
            WHERE id = %s
        """, (verdict, category, analysis, job_id))
        conn.commit()
        cur.close()
        conn.close()
    except Exception as e:
        print(f"DB ошибка update_job_analysis: {e}")
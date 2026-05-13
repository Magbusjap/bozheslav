import os
import asyncio
import subprocess
from telegram import Bot, InlineKeyboardButton, InlineKeyboardMarkup, ReplyKeyboardMarkup, KeyboardButton
from telegram.ext import Application, CallbackQueryHandler, CommandHandler, MessageHandler, filters
from telegram.request import HTTPXRequest
import logging
from dotenv import load_dotenv
from parsers.kwork import fetch_jobs as kwork_jobs
from parsers.fl import fetch_jobs as fl_jobs
from parsers.freelance_ru import fetch_jobs as freelance_jobs
from ai_client import analyze_job
from database import is_job_seen, save_job, save_verdict, get_bot_state, set_bot_state
from database import get_connection, mark_sent, get_unsent_today, write_log, update_last_command
from database import get_leads_random, get_leads_new, mark_lead_sent
from database import get_jobs_for_reanalysis, update_job_analysis

load_dotenv()

logging.basicConfig(
    format="%(asctime)s - %(name)s - %(levelname)s - %(message)s",
    level=logging.INFO,
)
logger = logging.getLogger("freelance_bot")

TOKEN = os.getenv("TELEGRAM_TOKEN")
CHAT_ID = os.getenv("TELEGRAM_CHAT_ID")

MAIN_MENU = ReplyKeyboardMarkup(
    [
        [KeyboardButton("🔍 Анализ площадок"), KeyboardButton("⏹ Завершить анализ")],
        [KeyboardButton("🔥 Горящие задания"), KeyboardButton("⏹ Закончить поиск")],
        [KeyboardButton("📊 Статус"), KeyboardButton("📅 За 7 дней"), KeyboardButton("📅 За 30 дней")],
        [KeyboardButton("🏢 Все компании"), KeyboardButton("🆕 Новые компании")],
        [KeyboardButton("🔄 Повторный анализ"), KeyboardButton("🔄 Перезапустить")]
    ],
    resize_keyboard=True
)


def _clean_line(line: str) -> str:
    """Strip markdown decorations (**, __, #, spaces) from a line."""
    return line.replace("**", "").replace("__", "").replace("#", "").strip()

def extract_category(analysis: str) -> str:
    for raw_line in analysis.splitlines():
        line = _clean_line(raw_line)
        if line.upper().startswith("КАТЕГОРИЯ:"):
            return line.split(":", 1)[1].strip()
    return ""

def extract_verdict(analysis: str) -> str:
    for raw_line in analysis.splitlines():
        line = _clean_line(raw_line).upper()
        if line.startswith("ВЕРДИКТ:"):
            value = line.split(":", 1)[1].strip()
            if "БРАТЬ" in value:
                return "take"
            elif "ПРОПУСТИТЬ" in value:
                return "skip"
    return "unclear"


def get_source_link(source: str) -> str:
    sources = {
        'kwork': '[Kwork](https://kwork.ru)',
        'fl': '[FL.ru](https://fl.ru)',
        'freelance': '[Freelance.ru](https://freelance.ru)',
    }
    return sources.get(source, source)

async def send_job(bot: Bot, job: dict, analysis: str, job_id: int):
    text = (
        f"🔔 *{job['title']}*\n\n"
        f"💰 Бюджет: {job['budget']}\n"
        f"📨 Предложений: {job['proposals']}\n"
        f"🔗 [Открыть задание]({job['link']})\n\n"
        f"🤖 *Анализ:*\n{analysis}\n"
        f"————————————————————\n"
        f"📁 Категория: {job.get('category', '—')}\n"
        f"🏪 Биржа: {get_source_link(job.get('source', ''))}"
    )

    keyboard = InlineKeyboardMarkup([
        [
            InlineKeyboardButton("✅ Откликнусь", callback_data=f"take|{job_id}"),
            InlineKeyboardButton("❌ Пропустить", callback_data=f"skip|{job_id}")
        ]
    ])

    await bot.send_message(
        chat_id=CHAT_ID,
        text=text,
        parse_mode="Markdown",
        reply_markup=keyboard,
        disable_web_page_preview=True
    )

async def send_lead(bot: Bot, lead: dict, is_repeat: bool = False):
    repeat_mark = "🔁 *Повтор*\n" if is_repeat else ""
    text = (
        f"{repeat_mark}🏢 *{lead['company_name']}*\n\n"
        f"🏷 Ниша: {lead.get('niche') or '—'}\n"
        f"🌐 Сайт: {lead.get('website') or '—'}\n"
        f"📧 Email: {lead.get('contact_email') or '—'}\n"
        f"📌 Источник: {lead.get('source') or '—'}\n"
        f"📊 Статус: {lead.get('status') or 'new'}"
    )
    await bot.send_message(
        chat_id=CHAT_ID,
        text=text,
        parse_mode="Markdown",
        disable_web_page_preview=True
    )

async def check_jobs(bot: Bot, hot_only: bool = False):
    """Check all freelance sources, report summary and errors to user."""
    write_log("check_started", f"hot_only={hot_only}")
    errors = []
    source_counts = {"kwork": 0, "fl": 0, "freelance": 0}

    try:
        unsent = get_unsent_today()
        for job in unsent:
            if hot_only and job['verdict'] != 'take':
                continue
            await send_job(bot, job, job['ai_analysis'], job['id'])
            mark_sent(job['id'])
            await asyncio.sleep(2)
    except Exception as e:
        errors.append(f"неотправленные: {type(e).__name__}")
        write_log("error", f"unsent: {e}", level="error")
        unsent = []

    all_jobs = []

    try:
        kw = kwork_jobs()
        source_counts["kwork"] = len(kw)
        all_jobs.extend(kw)
    except Exception as e:
        errors.append(f"Kwork: {type(e).__name__}")
        write_log("error", f"kwork: {e}", level="error")

    try:
        fl = fl_jobs()
        source_counts["fl"] = len(fl)
        all_jobs.extend(fl)
    except Exception as e:
        errors.append(f"FL.ru: {type(e).__name__}")
        write_log("error", f"fl: {e}", level="error")

    try:
        fr = await freelance_jobs()
        source_counts["freelance"] = len(fr)
        all_jobs.extend(fr)
    except Exception as e:
        errors.append(f"Freelance.ru: {type(e).__name__}")
        write_log("error", f"freelance_ru: {e}", level="error")

    new_count = 0
    ai_errors = 0
    for job in all_jobs:
        try:
            if is_job_seen(job['link']):
                continue

            analysis = analyze_job(
                job['title'],
                job['description'],
                job['budget'],
                job['proposals'],
                job['hired_rate']
            )

            verdict = extract_verdict(analysis)
            category = extract_category(analysis)
            job['category'] = category
            job_id = save_job(job, analysis, verdict)

            if hot_only and verdict != 'take':
                continue

            if job_id:
                await send_job(bot, job, analysis, job_id)
                mark_sent(job_id)
                await asyncio.sleep(2)
                new_count += 1
        except Exception as e:
            ai_errors += 1
            write_log("error", f"analyze: {type(e).__name__}: {str(e)[:200]}", level="error")

    summary_lines = [
        f"📊 Проверка завершена",
        f"Kwork: {source_counts['kwork']}, FL.ru: {source_counts['fl']}, Freelance.ru: {source_counts['freelance']}",
        f"Новых опубликовано: {new_count}",
    ]
    if ai_errors:
        summary_lines.append(f"⚠️ Ошибок анализа: {ai_errors}")
    if errors:
        summary_lines.append(f"⚠️ Источники с ошибкой: {', '.join(errors)}")

    summary = "\n".join(summary_lines)
    write_log("check_done", f"new={new_count} errors={len(errors)+ai_errors}")

    try:
        await bot.send_message(chat_id=CHAT_ID, text=summary)
    except Exception as e:
        write_log("error", f"send summary: {e}", level="error")

async def reanalyze_recent(bot: Bot, hours: int):
    """Re-analyze jobs from last N hours that have unclear verdict.
    Called when user presses 'Повторный анализ' in menu."""
    write_log("reanalyze_started", f"hours={hours}")

    jobs = get_jobs_for_reanalysis(hours=hours, limit=30)
    if not jobs:
        await bot.send_message(
            chat_id=CHAT_ID,
            text=f"📭 Нет задач для повторного анализа за последние {hours} ч."
        )
        write_log("reanalyze_done", "no jobs")
        return

    await bot.send_message(
        chat_id=CHAT_ID,
        text=f"🔄 Переанализирую {len(jobs)} задач за {hours} ч... Это займёт ~{len(jobs) * 3} сек."
    )

    take_count = 0
    skip_count = 0
    error_count = 0

    for job in jobs:
        try:
            analysis = analyze_job(
                job['title'],
                job['description'],
                job['budget'],
                job['proposals'],
                job['hired_rate']
            )
            verdict = extract_verdict(analysis)
            category = extract_category(analysis)

            update_job_analysis(job['id'], verdict, category, analysis)

            if verdict == 'take':
                take_count += 1
            elif verdict == 'skip':
                skip_count += 1

            if verdict != 'skip':
                job['category'] = category
                await send_job(bot, job, analysis, job['id'])
                mark_sent(job['id'])
                await asyncio.sleep(2)
        except Exception as e:
            error_count += 1
            write_log("error", f"reanalyze job {job.get('id')}: {type(e).__name__}: {str(e)[:150]}", level="error")

    summary_lines = [
        f"📊 Повторный анализ завершён ({hours} ч)",
        f"Всего проанализировано: {len(jobs)}",
        f"✅ БРАТЬ: {take_count}",
        f"❌ ПРОПУСТИТЬ: {skip_count}",
    ]
    unclear = len(jobs) - take_count - skip_count - error_count
    if unclear:
        summary_lines.append(f"❓ УТОЧНИТЬ: {unclear}")
    if error_count:
        summary_lines.append(f"⚠️ Ошибок: {error_count}")

    write_log("reanalyze_done", f"total={len(jobs)} take={take_count} skip={skip_count} errors={error_count}")

    try:
        await bot.send_message(chat_id=CHAT_ID, text="\n".join(summary_lines))
    except Exception as e:
        write_log("error", f"reanalyze summary: {e}", level="error")


async def main():
    write_log("started", "Бот запущен")
    request = HTTPXRequest(
        connect_timeout=20.0,
        read_timeout=30.0,
        write_timeout=30.0,
        pool_timeout=5.0,
    )
    app = (
        Application.builder()
        .token(TOKEN)
        .request(request)
        .get_updates_request(request)
        .build()
    )

    async def error_handler(update, context):
        err = context.error
        logger.error("Exception in handler: %r", err, exc_info=err)
        try:
            write_log("error", f"handler exception: {err!r}", level="error")
        except Exception:
            pass

    async def initialize_with_retry(app, max_retries=5):
        """Initialize application with retry logic for flaky networks."""
        for attempt in range(1, max_retries + 1):
            try:
                logger.info(f"Initialize attempt {attempt}/{max_retries}")
                await app.initialize()
                await app.start()
                logger.info("Bot initialized successfully")
                return True
            except Exception as e:
                if attempt == max_retries:
                    logger.error(f"Failed to initialize after {max_retries} attempts: {e}")
                    raise
                delay = 2 ** attempt
                logger.warning(f"Initialize failed (attempt {attempt}): {e}. Retrying in {delay}s...")
                await asyncio.sleep(delay)
        return False

    async def send_startup_menu(bot):
        """Send menu to admin on successful bot startup."""
        try:
            await bot.send_message(
                chat_id=CHAT_ID,
                text="✅ Бот запущен. Выбери режим:",
                reply_markup=MAIN_MENU
            )
            logger.info("Startup menu sent")
        except Exception as e:
            logger.warning(f"Could not send startup menu: {e}")

    async def _delayed_restart():
        """Schedule systemctl restart in background after 3 seconds."""
        await asyncio.sleep(3)
        subprocess.Popen(
            ["systemctl", "restart", "freelance_bot"],
            stdout=subprocess.DEVNULL,
            stderr=subprocess.DEVNULL,
        )

    async def callback_handler(update, context):
        if str(update.effective_chat.id) != CHAT_ID:
            return
        query = update.callback_query
        await query.answer()
        action, job_id = query.data.split("|", 1)
        if action in ("take", "skip"):
            save_verdict(int(job_id), action)
            label = "✅ Откликнулся" if action == "take" else "❌ Пропущено"
            await query.edit_message_reply_markup(
                InlineKeyboardMarkup([[InlineKeyboardButton(label, callback_data="done")]])
            )

        elif action == "leads_all":
            limit = int(job_id)
            leads = get_leads_random(limit)
            if not leads:
                await query.edit_message_text("📭 Компаний пока нет.")
                return
            await query.edit_message_text(f"🏢 Показываю {len(leads)} компаний...")
            for lead in leads:
                is_repeat = lead['sent_to_telegram']
                await send_lead(context.bot, lead, is_repeat=is_repeat)
                mark_lead_sent(lead['id'])
                await asyncio.sleep(1)

        elif action == "leads_new":
            limit = int(job_id)
            leads = get_leads_new(limit)
            if not leads:
                await query.edit_message_text("📭 Новых компаний нет.")
                return
            await query.edit_message_text(f"🆕 Показываю {len(leads)} новых компаний...")
            for lead in leads:
                await send_lead(context.bot, lead, is_repeat=False)
                mark_lead_sent(lead['id'])
                await asyncio.sleep(1)

        elif action == "reanalyze":
            hours = int(job_id)
            await query.edit_message_text(f"🔄 Запускаю повторный анализ за {hours} ч...")
            await reanalyze_recent(context.bot, hours)

    async def status_handler(update, context, period: str = "today"):
        if str(update.effective_chat.id) != CHAT_ID:
            return
        conn = get_connection()
        cur = conn.cursor()

        if period == "today":
            where = "DATE(found_at) = CURRENT_DATE"
            label = "Сегодня"
        elif period == "7days":
            where = "found_at >= CURRENT_DATE - INTERVAL '7 days'"
            label = "За 7 дней"
        elif period == "30days":
            where = "found_at >= CURRENT_DATE - INTERVAL '30 days'"
            label = "За 30 дней"
        else:
            where = "DATE(found_at) = CURRENT_DATE"
            label = "Сегодня"

        cur.execute(f"SELECT COUNT(*) FROM bot_jobs WHERE {where}")
        total = cur.fetchone()[0]
        cur.execute(f"SELECT COUNT(*) FROM bot_jobs WHERE sent_to_telegram = true AND {where}")
        sent = cur.fetchone()[0]
        cur.execute(f"SELECT COUNT(*) FROM bot_jobs WHERE verdict = 'take' AND {where}")
        taken = cur.fetchone()[0]
        cur.execute(f"SELECT COUNT(*) FROM bot_jobs WHERE verdict = 'skip' AND {where}")
        skipped = cur.fetchone()[0]
        mode = get_bot_state("mode")
        mode_label = {"analysis": "🔍 Анализ площадок", "hot": "🔥 Горящие задания", "off": "⏹ Выключен"}.get(mode, mode)
        cur.close()
        conn.close()
        await update.message.reply_text(
            f"📊 *Статус бота — {label}*\n\n"
            f"Режим: {mode_label}\n"
            f"Найдено: {total}\n"
            f"📨 Опубликовано в чат: {sent}\n"
            f"✅ Отмечено взять: {taken}\n"
            f"❌ Отмечено пропустить: {skipped}",
            parse_mode="Markdown",
            reply_markup=MAIN_MENU
        )


    async def menu_handler(update, context):
        if str(update.effective_chat.id) != CHAT_ID:
            return
        text = update.message.text
        write_log("user_command", text)
        if str(update.effective_chat.id) != CHAT_ID:
            return
        text = update.message.text

        if text == "🔍 Анализ площадок":
            set_bot_state("mode", "analysis")
            await update.message.reply_text("✅ Анализ площадок запущен. Проверка каждые 30 минут.", reply_markup=MAIN_MENU)
            await check_jobs(context.bot)

        elif text == "⏹ Завершить анализ":
            if get_bot_state("mode") == "analysis":
                set_bot_state("mode", "off")
                await update.message.reply_text("⏹ Анализ остановлен.", reply_markup=MAIN_MENU)
            else:
                await update.message.reply_text("Анализ не был запущен.", reply_markup=MAIN_MENU)

        elif text == "🔥 Горящие задания":
            set_bot_state("mode", "hot")
            await update.message.reply_text("🔥 Поиск горящих заданий запущен. Проверка каждые 10 минут.", reply_markup=MAIN_MENU)
            await check_jobs(context.bot, hot_only=True)

        elif text == "⏹ Закончить поиск":
            if get_bot_state("mode") == "hot":
                set_bot_state("mode", "off")
                await update.message.reply_text("⏹ Поиск горящих заданий остановлен.", reply_markup=MAIN_MENU)
            else:
                await update.message.reply_text("Горящий поиск не был запущен.", reply_markup=MAIN_MENU)

        elif text == "🔄 Повторный анализ":
            update_last_command()
            await update.message.reply_text(
                "За какой период переанализировать задачи с unclear-вердиктом?",
                reply_markup=InlineKeyboardMarkup([
                    [
                        InlineKeyboardButton("1 час", callback_data="reanalyze|1"),
                        InlineKeyboardButton("6 часов", callback_data="reanalyze|6"),
                        InlineKeyboardButton("24 часа", callback_data="reanalyze|24"),
                    ]
                ])
            )

        elif text == "🔄 Перезапустить":
            await update.message.reply_text("🔄 Перезапускаю через 3 секунды...", reply_markup=MAIN_MENU)
            asyncio.create_task(_delayed_restart())

        elif text == "📊 Статус":
            await status_handler(update, context, period="today")
        elif text == "📅 За 7 дней":
            await status_handler(update, context, period="7days")
        elif text == "📅 За 30 дней":
            await status_handler(update, context, period="30days")

        elif text == "🏢 Все компании":
            update_last_command()
            await update.message.reply_text(
                "Сколько компаний показать?",
                reply_markup=InlineKeyboardMarkup([
                    [
                        InlineKeyboardButton("5", callback_data="leads_all|5"),
                        InlineKeyboardButton("15", callback_data="leads_all|15"),
                        InlineKeyboardButton("25", callback_data="leads_all|25"),
                    ]
                ])
            )

        elif text == "🆕 Новые компании":
            update_last_command()
            await update.message.reply_text(
                "Сколько новых компаний показать?",
                reply_markup=InlineKeyboardMarkup([
                    [
                        InlineKeyboardButton("5", callback_data="leads_new|5"),
                        InlineKeyboardButton("15", callback_data="leads_new|15"),
                        InlineKeyboardButton("25", callback_data="leads_new|25"),
                    ]
                ])
            )

    async def start_handler(update, context):
        if str(update.effective_chat.id) != CHAT_ID:
            return
        await update.message.reply_text("👋 Бот запущен. Выбери режим:", reply_markup=MAIN_MENU)

    app.add_error_handler(error_handler)
    app.add_handler(CallbackQueryHandler(callback_handler))
    app.add_handler(CommandHandler("start", start_handler))
    app.add_handler(MessageHandler(filters.TEXT & ~filters.COMMAND, menu_handler))

    await initialize_with_retry(app)
    await app.updater.start_polling()
    await send_startup_menu(app.bot)

    print("Бот запущен. Ожидание команд.")

    mode = get_bot_state("mode")
    if mode == "analysis":
        await check_jobs(app.bot)
    elif mode == "hot":
        await check_jobs(app.bot, hot_only=True)

    while True:
        await asyncio.sleep(3600)
        mode = get_bot_state("mode")
        if mode == "analysis":
            await check_jobs(app.bot)
        elif mode == "hot":
            await check_jobs(app.bot, hot_only=True)
        else:
            write_log("heartbeat", "ожидание команды")

if __name__ == "__main__":
    asyncio.run(main())
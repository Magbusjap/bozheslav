const SKILLS = {
	// ============================================
	// Приоритет 1 — Backend
	// ============================================
	laravel: {
		title: "Laravel 12",
		level: 65,
		desc: "Fullstack-разработка приложений в production: bozheslav.ru и open-source CMS OnFlaude. Маршрутизация, Eloquent ORM, миграции, сервис-провайдеры, middleware, custom artisan-команды.",
	},
	filament: {
		title: "Filament 3 / 4",
		level: 70,
		desc: "Доработки на уровне ядра, а не только CRUD. Кастомные Pages, Widgets, Resources, renderHook'и, viteTheme, ITCSS-рефактор админки (OnFlaude, PR #3 — 24 коммита).",
	},
	php: {
		title: "PHP 8.3",
		level: 65,
		desc: "Современный PHP: typed properties, readonly, enums, match, first-class callable syntax. Laravel-ориентированный подход, PSR-12.",
	},
	postgresql: {
		title: "PostgreSQL 16",
		level: 60,
		desc: "Production-база OnFlaude и bozheslav.ru. Автоматические ежедневные backup через cron, миграции с debugging type mismatch (bigint vs varchar), работа с ILIKE, JSON-полями, foreign keys.",
	},
	livewire: {
		title: "Livewire 3",
		level: 50,
		desc: "Реактивные компоненты в Filament-админке, кастомный MediaPicker. Понимание wire-lifecycle, известные ограничения модалок.",
	},
	pest: {
		title: "Pest",
		level: 40,
		desc: "Test suite в OnFlaude (13 feature-тестов, PostgreSQL test DB). Базовые assertions, фикстуры. Область развития — стабильный green build и purpose-specific тестирование.",
	},

	// ============================================
	// Приоритет 2 — DevOps
	// ============================================
	nginx: {
		title: "Nginx",
		level: 65,
		desc: "Production-конфиг с нуля: server blocks, try_files для Laravel, PHP-FPM проксирование, оптимизация limit_req, static file caching, SSL через Certbot.",
	},
	linux: {
		title: "Linux (Ubuntu Server)",
		level: 70,
		desc: "Администрирование production VPS Ubuntu 24.04 через SSH: systemd-сервисы, cron, права доступа, bash-скриптинг, управление пакетами APT.",
	},
	docker: {
		title: "Docker",
		level: 55,
		desc: "Контейнеризация сервисов: n8n + Telegram bot + OpenAI через Docker Compose. Написание Dockerfile, работа с volumes, networks.",
	},
	"docker-compose": {
		title: "Docker Compose",
		level: 55,
		desc: "Multi-container окружения для приложений и сервисов. Конфигурация services, networks, depends_on. Связка контейнеров через внутренние сети.",
	},
	security: {
		title: "Server Hardening",
		level: 70,
		desc: "Fail2Ban с кастомными jail (SSH + nginx-scan), UFW firewall, SSH key-only auth, non-default порты, server_tokens off. В production: 424 забаненных IP + автоблок сканеров .env/.git.",
	},
	vps: {
		title: "VPS-деплой",
		level: 70,
		desc: "Полный цикл развёртывания production с нуля: Timeweb VPS, установка стека, настройка nginx/SSL, deploy.sh скрипт, автобэкапы. Развёрнуто: bozheslav.ru, dev.onflaude.com.",
	},

	// ============================================
	// Приоритет 3 — Python / Автоматизация
	// ============================================
	python: {
		title: "Python",
		level: 55,
		desc: "Три парсера фриланс-площадок в production: Kwork (JSON из __INITIAL_STATE__), FL.ru (BeautifulSoup), Freelance.ru (Playwright async). Flask-сервис для HH.ru парсера. Работа с venv, pip, systemd.",
	},
	playwright: {
		title: "Playwright",
		level: 60,
		desc: "Headless Chromium для парсинга JS-рендеренных сайтов: обход Freelance.ru, async_playwright внутри asyncio event loop, работа с selectors и waits.",
	},
	beautifulsoup: {
		title: "BeautifulSoup",
		level: 65,
		desc: "Классический HTML-парсинг: извлечение данных с FL.ru, навигация по DOM, CSS-селекторы, работа с requests-session.",
	},
	"telegram-bot": {
		title: "Telegram Bot API",
		level: 65,
		desc: "Два production-бота: freelance-алерты с AI-анализом (DeepSeek) + n8n + OpenAI ассистент. Webhooks, inline keyboards, callback_data, длинные меню через клавиатуры.",
	},
	n8n: {
		title: "n8n / Automation",
		level: 55,
		desc: "Визуальная автоматизация: Telegram → GPT → ответ в бота. Развёрнут в Docker Compose на собственном сервере. Интеграция Telegram Bot API, OpenAI API, webhooks.",
	},

	// ============================================
	// Приоритет 4 — AI / Интеграции
	// ============================================
	"claude-api": {
		title: "Claude API",
		level: 55,
		desc: "Работа с Claude как с инструментом разработки и через MCP-серверы (SSH MCP, Obsidian MCP, agent-recall). Опыт промптинга системных инструкций для Sonnet/Opus.",
	},
	openai: {
		title: "OpenAI API",
		level: 50,
		desc: "Интеграция GPT в n8n-воркфлоу для автоматических ответов в Telegram-боте. Chat Completions API, работа с tokens и rate limits.",
	},
	deepseek: {
		title: "DeepSeek / RouterAI",
		level: 60,
		desc: "Production AI-анализ в freelance-боте через RouterAI proxy (~0.03 RUB за анализ). Фильтрация заказов через системный промпт из БД, human-override learning pipeline.",
	},
	prompt: {
		title: "Prompt Engineering",
		level: 55,
		desc: "Системные промпты хранятся в options table — редактируются из Filament-админки без правок кода. Injection of override-примеров для fine-tuning поведения модели.",
	},

	// ============================================
	// Приоритет 5 — Frontend
	// ============================================
	html: {
		title: "HTML5",
		level: 75,
		desc: "Семантическая разметка, доступность (ARIA), оптимизация ресурсов (WebP, picture/source, fetchpriority). Строгая валидность (без <p> внутри <h1> и подобных).",
	},
	css: {
		title: "CSS3 / BEM",
		level: 75,
		desc: "Без CSS-фреймворков. Grid, Flexbox, CSS custom properties, анимации. Строгое следование BEM. bozheslav.ru: PageSpeed 94/100 Desktop.",
	},
	bem: {
		title: "BEM",
		level: 75,
		desc: "Методология .block__element--modifier строго везде. Плоская структура селекторов без вложенности, без конфликтов специфичности.",
	},
	itcss: {
		title: "ITCSS",
		level: 55,
		desc: "CSS-архитектура Inverted Triangle: settings → base → layout → components → pages → utilities. Применена в open-source OnFlaude (PR #3, 24 коммита), в теме и в админке Filament.",
	},
	javascript: {
		title: "JavaScript (ES6+)",
		level: 60,
		desc: "ES modules, async/await, Fetch API, DOM-манипуляции. Реализация фильтрации, пагинации, модальных окон, карусели на чистом JS без фреймворков.",
	},
	"alpine-js": {
		title: "Alpine.js",
		level: 50,
		desc: "Реактивность в Filament-админке через x-data, x-on, x-show, Alpine.store, Alpine.effect. Интеграция с Livewire-компонентами.",
	},
	tailwind: {
		title: "Tailwind CSS",
		level: 55,
		desc: "Используется в Filament-админке через Filament preset. Утилитарный подход, работа с custom-конфигурацией, @apply директивы.",
	},
	mjml: {
		title: "MJML",
		level: 65,
		desc: "Email-шаблоны для рассылок bozheslav.ru. Кастомный редактор в Filament с preview и test-send через SMTP Yandex. Компоненты mj-section/mj-column/mj-button.",
	},
	vite: {
		title: "Vite",
		level: 55,
		desc: "Два Vite-конфига в OnFlaude: темы и админки Filament (для изоляции билдов). PostCSS pipeline, Laravel Vite plugin, emptyOutDir workaround, hot-reload для разработки.",
	},
	responsive: {
		title: "Responsive Design",
		level: 75,
		desc: "Mobile First подход, адаптив под любые устройства без кросс-браузерных хаков. PageSpeed Mobile: 78/100.",
	},

	// ============================================
	// Второстепенные
	// ============================================
	git: {
		title: "Git / GitHub",
		level: 70,
		desc: "Conventional commits, ветки + cherry-pick, rebase. Использовал filter-branch для массового переписывания истории (OnFlaude: 24 коммита). gh CLI для PR-workflow.",
	},
	composer: {
		title: "Composer",
		level: 65,
		desc: "Управление PHP-зависимостями, autoload/files для helpers.php, composer scripts, semantic versioning в require-блоке.",
	},
	wordpress: {
		title: "WordPress",
		level: 50,
		desc: "Legacy-опыт: построил корпоративный сайт в Витамилк в 2016-2022. Темы, плагины, кастомные типы записей. На текущих проектах не применяю — работа на Laravel/Filament.",
	},
	mysql: {
		title: "MySQL",
		level: 45,
		desc: "Legacy-опыт работы с MySQL. На текущих проектах основная БД — PostgreSQL 16 (production bozheslav.ru и OnFlaude).",
	},
	csharp: {
		title: "C#",
		level: 35,
		desc: "Самообучение 2013-2020: алгоритмы, ООП, базовые приложения. В production не применял — знакомство с языком, не рабочий навык сейчас.",
	},
	figma: {
		title: "Figma",
		level: 40,
		desc: "Чтение макетов от дизайнеров, экспорт ассетов (SVG sprites, иконки), перенос в вёрстку с соблюдением размеров и отступов.",
	}
};



function getLevelLabel(level) {
	if (level >= 80) return "Уверенно";
	if (level >= 60) return "Хорошо";
	if (level >= 40) return "В процессе";
	return "Базово";
}

function openModal(skillKey) {
	const skill = SKILLS[skillKey];
	if (!skill) return;

	document.getElementById("skillModalTitle").textContent = skill.title;
	document.getElementById("skillModalDesc").textContent = skill.desc;
	document.getElementById("skillModalLevel").textContent =
		`${getLevelLabel(skill.level)} — ${skill.level}%`;

	const bar = document.getElementById("skillModalBar");
	bar.style.width = "0%";
	setTimeout(() => {
		bar.style.width = `${skill.level}%`;
	}, 50);

	const modal = document.getElementById("skillModal");
	modal.classList.add("active");
	modal.setAttribute("aria-hidden", "false");
	document.body.style.overflow = "hidden";
}

function closeModal() {
	const modal = document.getElementById("skillModal");
	modal.classList.remove("active");
	modal.setAttribute("aria-hidden", "true");
	document.body.style.overflow = "";
}

export function initSkills() {
	if (!document.querySelector(".skill-tag")) return;

	document.querySelectorAll(".skill-tag").forEach((btn) => {
		btn.addEventListener("click", () => {
			openModal(btn.dataset.skill);
		});
	});

	document
		.getElementById("skillModalClose")
		.addEventListener("click", closeModal);
	document
		.getElementById("skillModalOverlay")
		.addEventListener("click", closeModal);

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape") closeModal();
	});
}

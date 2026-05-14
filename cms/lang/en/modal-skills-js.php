<?php

return array (
  'js' => 
  array (
    'modalSkills' => 
    array (
      'laravel' => 
      array (
        'desc' => 'Full-stack production application development: bozheslav.com and the open-source CMS OnFlaude. Routing, Eloquent ORM, migrations, service providers, middleware, and custom Artisan commands.',
      ),
      'filament' => 
      array (
        'desc' => 'Core-level customization, not just CRUD. Custom Pages, Widgets, Resources, render hooks, viteTheme, and an ITCSS refactor of the admin panel (OnFlaude, PR #3, 24 commits).',
      ),
      'php' => 
      array (
        'desc' => 'Modern PHP: typed properties, readonly, enums, match, and first-class callable syntax. Laravel-oriented development with PSR-12 style.',
      ),
      'postgresql' => 
      array (
        'desc' => 'Production database work for OnFlaude and bozheslav.com. Daily automated backups via cron, migrations, debugging type mismatches (bigint vs varchar), ILIKE, JSON fields, and foreign keys.',
      ),
      'livewire' => 
      array (
        'desc' => 'Reactive components in the Filament admin panel and a custom MediaPicker. Understanding of the wire lifecycle and known modal limitations.',
      ),
      'pest' => 
      array (
        'desc' => 'Test suite in OnFlaude: 13 feature tests and a PostgreSQL test database. Basic assertions and fixtures. Growth area: a stable green build and purpose-specific tests.',
      ),
      'nginx' => 
      array (
        'desc' => 'Production configuration from scratch: server blocks, try_files for Laravel, PHP-FPM proxying, limit_req tuning, static file caching, and SSL via Certbot.',
      ),
      'linux' => 
      array (
        'desc' => 'Production VPS administration on Ubuntu 24.04 over SSH: systemd services, cron, permissions, bash scripting, and APT package management.',
      ),
      'docker' => 
      array (
        'desc' => 'Service containerization: n8n, Telegram bot, and OpenAI through Docker Compose. Dockerfile writing, volumes, and networks.',
      ),
      'docker-compose' => 
      array (
        'desc' => 'Multi-container environments for applications and services. Configuring services, networks, and depends_on. Connecting containers through internal networks.',
      ),
      'security' => 
      array (
        'desc' => 'Fail2Ban with custom jails (SSH and nginx scan), UFW firewall, SSH key-only authentication, non-default ports, and server_tokens off. In production: 424 banned IPs plus automatic blocking of .env/.git scanners.',
      ),
      'vps' => 
      array (
        'desc' => 'Full production deployment cycle from scratch: Timeweb VPS, stack installation, nginx/SSL setup, deploy.sh script, and automatic backups. Deployed: bozheslav.com and dev.onflaude.com.',
      ),
      'python' => 
      array (
        'desc' => 'Three freelance marketplace parsers in production: Kwork (JSON from __INITIAL_STATE__), FL.ru (BeautifulSoup), Freelance.ru (async Playwright). Flask service for an HH.ru parser. Work with venv, pip, and systemd.',
      ),
      'playwright' => 
      array (
        'desc' => 'Headless Chromium for parsing JavaScript-rendered sites: Freelance.ru crawling, async_playwright inside an asyncio event loop, selectors, and waits.',
      ),
      'beautifulsoup' => 
      array (
        'desc' => 'Classic HTML parsing: extracting data from FL.ru, navigating the DOM, CSS selectors, and working with requests sessions.',
      ),
      'telegram-bot' => 
      array (
        'desc' => 'Two production bots: freelance alerts with AI analysis (DeepSeek) plus an n8n + OpenAI assistant. Webhooks, inline keyboards, callback_data, and long menus through keyboards.',
      ),
      'n8n' => 
      array (
        'desc' => 'Visual automation: Telegram -> GPT -> bot reply. Deployed in Docker Compose on a private server. Integrations with Telegram Bot API, OpenAI API, and webhooks.',
      ),
      'claude-api' => 
      array (
        'desc' => 'Using Claude as a development tool and through MCP servers (SSH MCP, Obsidian MCP, agent-recall). Experience with system-instruction prompting for Sonnet and Opus.',
      ),
      'openai' => 
      array (
        'desc' => 'GPT integration into n8n workflows for automatic replies in a Telegram bot. Chat Completions API, tokens, and rate limits.',
      ),
      'deepseek' => 
      array (
        'desc' => 'Production AI analysis in a freelance bot through the RouterAI proxy (~0.03 RUB per analysis). Order filtering through a system prompt from the database and a human-override learning pipeline.',
      ),
      'prompt' => 
      array (
        'desc' => 'System prompts are stored in the options table and edited from the Filament admin panel without code changes. Override examples are injected to fine-tune model behavior.',
      ),
      'html' => 
      array (
        'desc' => 'Semantic markup, accessibility (ARIA), and resource optimization (WebP, picture/source, fetchpriority). Strict validity without invalid nesting.',
      ),
      'css' => 
      array (
        'desc' => 'No CSS frameworks. Grid, Flexbox, CSS custom properties, animations, and strict BEM. bozheslav.com: PageSpeed 94/100 on desktop.',
      ),
      'bem' => 
      array (
        'desc' => 'Strict .block__element--modifier methodology everywhere. Flat selector structure without nesting or specificity conflicts.',
      ),
      'itcss' => 
      array (
        'desc' => 'Inverted Triangle CSS architecture: settings -> base -> layout -> components -> pages -> utilities. Applied in open-source OnFlaude (PR #3, 24 commits), theme, and Filament admin panel.',
      ),
      'javascript' => 
      array (
        'desc' => 'ES modules, async/await, Fetch API, and DOM manipulation. Filtering, pagination, modals, and carousel behavior in vanilla JavaScript.',
      ),
      'alpine-js' => 
      array (
        'desc' => 'Reactivity in the Filament admin panel through x-data, x-on, x-show, Alpine.store, and Alpine.effect. Integration with Livewire components.',
      ),
      'tailwind' => 
      array (
        'desc' => 'Used in the Filament admin panel through the Filament preset. Utility-first workflow, custom configuration, and @apply directives.',
      ),
      'mjml' => 
      array (
        'desc' => 'Email templates for bozheslav.com newsletters. Custom Filament editor with preview and test-send through Yandex SMTP. mj-section, mj-column, and mj-button components.',
      ),
      'vite' => 
      array (
        'desc' => 'Two Vite configs in OnFlaude: theme and Filament admin builds. PostCSS pipeline, Laravel Vite plugin, emptyOutDir workaround, and development hot reload.',
      ),
      'responsive' => 
      array (
        'desc' => 'Mobile First approach and adaptation for different devices without cross-browser hacks. PageSpeed Mobile: 78/100.',
      ),
      'git' => 
      array (
        'desc' => 'Conventional commits, branches, cherry-pick, and rebase. Used filter-branch for bulk history rewriting (OnFlaude: 24 commits). gh CLI for PR workflow.',
      ),
      'composer' => 
      array (
        'desc' => 'PHP dependency management, autoload/files for helpers.php, composer scripts, and semantic versioning in require blocks.',
      ),
      'wordpress' => 
      array (
        'desc' => 'Legacy experience: built a corporate site at Vitamilk in 2016-2022. Themes, plugins, and custom post types. Current projects use Laravel/Filament instead.',
      ),
      'mysql' => 
      array (
        'desc' => 'Legacy MySQL experience. Current projects primarily use PostgreSQL 16 (production bozheslav.com and OnFlaude).',
      ),
      'csharp' => 
      array (
        'desc' => 'Self-study from 2013 to 2020: algorithms, OOP, and basic applications. Not used in production; familiar with the language, but it is not a current working skill.',
      ),
      'figma' => 
      array (
        'desc' => 'Reading designer layouts, exporting assets (SVG sprites and icons), and converting them into markup while preserving sizes and spacing.',
      ),
    ),
  ),
);

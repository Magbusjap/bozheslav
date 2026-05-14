<?php

return array (
  'js' => 
  array (
    'modalSkills' => 
    array (
      'laravel' => 
      array (
        'desc' => 'Full-stack razvoj produkcionih aplikacija: bozheslav.com i open-source CMS OnFlaude. Routing, Eloquent ORM, migracije, service provideri, middleware i custom Artisan komande.',
      ),
      'filament' => 
      array (
        'desc' => 'Dorada na nivou jezgra, ne samo CRUD. Custom Pages, Widgets, Resources, render hook-ovi, viteTheme i ITCSS refaktor admin panela (OnFlaude, PR #3, 24 commita).',
      ),
      'php' => 
      array (
        'desc' => 'Moderan PHP: typed properties, readonly, enums, match i first-class callable syntax. Laravel-orijentisan pristup i PSR-12 stil.',
      ),
      'postgresql' => 
      array (
        'desc' => 'Rad sa produkcionom bazom za OnFlaude i bozheslav.com. Automatski dnevni backup preko cron-a, migracije, debugging type mismatch problema (bigint vs varchar), ILIKE, JSON polja i foreign keys.',
      ),
      'livewire' => 
      array (
        'desc' => 'Reaktivne komponente u Filament admin panelu i custom MediaPicker. Razumevanje wire lifecycle-a i poznatih ograničenja modala.',
      ),
      'pest' => 
      array (
        'desc' => 'Test suite u OnFlaude: 13 feature testova i PostgreSQL test baza. Osnovni assertions i fixture-i. Oblast razvoja: stabilan green build i purpose-specific testovi.',
      ),
      'nginx' => 
      array (
        'desc' => 'Production konfiguracija od nule: server blocks, try_files za Laravel, PHP-FPM proxy, optimizacija limit_req, keširanje statičkih fajlova i SSL preko Certbot-a.',
      ),
      'linux' => 
      array (
        'desc' => 'Administracija production VPS-a na Ubuntu 24.04 preko SSH: systemd servisi, cron, prava pristupa, bash scripting i upravljanje APT paketima.',
      ),
      'docker' => 
      array (
        'desc' => 'Kontejnerizacija servisa: n8n, Telegram bot i OpenAI kroz Docker Compose. Pisanje Dockerfile-a, rad sa volumes i networks.',
      ),
      'docker-compose' => 
      array (
        'desc' => 'Multi-container okruženja za aplikacije i servise. Konfiguracija services, networks i depends_on. Povezivanje kontejnera kroz interne mreže.',
      ),
      'security' => 
      array (
        'desc' => 'Fail2Ban sa custom jail pravilima (SSH i nginx scan), UFW firewall, SSH key-only auth, nestandardni portovi i server_tokens off. U produkciji: 424 banovana IP-a plus automatsko blokiranje .env/.git skenera.',
      ),
      'vps' => 
      array (
        'desc' => 'Pun ciklus produkcionog deploy-a od nule: Timeweb VPS, instalacija stack-a, podešavanje nginx/SSL, deploy.sh skripta i automatski backup. Deploy: bozheslav.com i dev.onflaude.com.',
      ),
      'python' => 
      array (
        'desc' => 'Tri parsera freelance platformi u produkciji: Kwork (JSON iz __INITIAL_STATE__), FL.ru (BeautifulSoup), Freelance.ru (async Playwright). Flask servis za HH.ru parser. Rad sa venv, pip i systemd.',
      ),
      'playwright' => 
      array (
        'desc' => 'Headless Chromium za parsiranje JS-renderovanih sajtova: obilazak Freelance.ru, async_playwright unutar asyncio event loop-a, selectors i waits.',
      ),
      'beautifulsoup' => 
      array (
        'desc' => 'Klasično HTML parsiranje: izvlačenje podataka sa FL.ru, navigacija kroz DOM, CSS selektori i rad sa requests session.',
      ),
      'telegram-bot' => 
      array (
        'desc' => 'Dva production bota: freelance alerti sa AI analizom (DeepSeek) plus n8n + OpenAI asistent. Webhooks, inline keyboards, callback_data i duga menija kroz tastature.',
      ),
      'n8n' => 
      array (
        'desc' => 'Vizuelna automatizacija: Telegram -> GPT -> odgovor u botu. Deploy u Docker Compose na sopstvenom serveru. Integracija Telegram Bot API, OpenAI API i webhooks.',
      ),
      'claude-api' => 
      array (
        'desc' => 'Korišćenje Claude-a kao razvojnog alata i kroz MCP servere (SSH MCP, Obsidian MCP, agent-recall). Iskustvo sa promptingom sistemskih instrukcija za Sonnet i Opus.',
      ),
      'openai' => 
      array (
        'desc' => 'Integracija GPT-a u n8n workflow za automatske odgovore u Telegram botu. Chat Completions API, tokens i rate limits.',
      ),
      'deepseek' => 
      array (
        'desc' => 'Production AI analiza u freelance botu preko RouterAI proxy-ja (~0.03 RUB po analizi). Filtriranje narudžbina kroz sistemski prompt iz baze i human-override learning pipeline.',
      ),
      'prompt' => 
      array (
        'desc' => 'Sistemski promptovi se čuvaju u options tabeli i uređuju iz Filament admin panela bez izmene koda. Override primeri se ubacuju radi podešavanja ponašanja modela.',
      ),
      'html' => 
      array (
        'desc' => 'Semantička struktura, pristupačnost (ARIA) i optimizacija resursa (WebP, picture/source, fetchpriority). Stroga validnost bez neispravnog ugnježđivanja.',
      ),
      'css' => 
      array (
        'desc' => 'Bez CSS framework-a. Grid, Flexbox, CSS custom properties, animacije i strogi BEM. bozheslav.com: PageSpeed 94/100 na desktopu.',
      ),
      'bem' => 
      array (
        'desc' => 'Stroga .block__element--modifier metodologija svuda. Ravna struktura selektora bez ugnježđivanja i konflikata specifičnosti.',
      ),
      'itcss' => 
      array (
        'desc' => 'Inverted Triangle CSS arhitektura: settings -> base -> layout -> components -> pages -> utilities. Primena u open-source OnFlaude (PR #3, 24 commita), temi i Filament admin panelu.',
      ),
      'javascript' => 
      array (
        'desc' => 'ES modules, async/await, Fetch API i DOM manipulacije. Filtriranje, paginacija, modali i carousel na čistom JavaScript-u.',
      ),
      'alpine-js' => 
      array (
        'desc' => 'Reaktivnost u Filament admin panelu kroz x-data, x-on, x-show, Alpine.store i Alpine.effect. Integracija sa Livewire komponentama.',
      ),
      'tailwind' => 
      array (
        'desc' => 'Koristi se u Filament admin panelu kroz Filament preset. Utility-first pristup, custom konfiguracija i @apply direktive.',
      ),
      'mjml' => 
      array (
        'desc' => 'Email šabloni za bozheslav.com newsletter-e. Custom editor u Filament-u sa preview i test-send preko Yandex SMTP. Komponente mj-section, mj-column i mj-button.',
      ),
      'vite' => 
      array (
        'desc' => 'Dva Vite konfiga u OnFlaude: tema i Filament admin build. PostCSS pipeline, Laravel Vite plugin, emptyOutDir workaround i hot reload za razvoj.',
      ),
      'responsive' => 
      array (
        'desc' => 'Mobile First pristup i adaptacija za različite uređaje bez cross-browser hack-ova. PageSpeed Mobile: 78/100.',
      ),
      'git' => 
      array (
        'desc' => 'Conventional commits, grane, cherry-pick i rebase. Korišćen filter-branch za masovno prepisivanje istorije (OnFlaude: 24 commita). gh CLI za PR workflow.',
      ),
      'composer' => 
      array (
        'desc' => 'Upravljanje PHP zavisnostima, autoload/files za helpers.php, composer scripts i semantic versioning u require blokovima.',
      ),
      'wordpress' => 
      array (
        'desc' => 'Legacy iskustvo: korporativni sajt u Vitamilk-u 2016-2022. Teme, pluginovi i custom post types. Trenutni projekti koriste Laravel/Filament.',
      ),
      'mysql' => 
      array (
        'desc' => 'Legacy iskustvo rada sa MySQL. Trenutni projekti primarno koriste PostgreSQL 16 (production bozheslav.com i OnFlaude).',
      ),
      'csharp' => 
      array (
        'desc' => 'Samostalno učenje 2013-2020: algoritmi, OOP i osnovne aplikacije. Nije korišćen u produkciji; poznavanje jezika postoji, ali nije trenutni radni skill.',
      ),
      'figma' => 
      array (
        'desc' => 'Čitanje dizajnerskih layout-a, eksport asset-a (SVG sprites i ikone) i prenos u markup uz poštovanje dimenzija i razmaka.',
      ),
    ),
  ),
);

# bozheslav.com — Personal Portfolio, Blog & CMS

> Live: [bozheslav.com](https://bozheslav.com) · Author: Mikhail Ankudinov

Personal portfolio, blog, CMS, and automation services built from scratch and deployed on a self-configured VPS.  
The project separates the source layout, Laravel CMS, Python services, and infrastructure files into clear system-design layers.

![Python](https://img.shields.io/badge/Python_3.12-3776AB?style=flat-square&logo=python&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.3-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL_16-4169E1?style=flat-square&logo=postgresql&logoColor=white)
![Nginx](https://img.shields.io/badge/Nginx_1.24-009639?style=flat-square&logo=nginx&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker&logoColor=white)
![Ubuntu](https://img.shields.io/badge/Ubuntu_24.04-E95420?style=flat-square&logo=ubuntu&logoColor=white)

---

## Stack

| Layer | Tech |
|---|---|
| CMS / Application | PHP 8.3, Laravel 12, Filament PHP v3 |
| Frontend source | HTML5, CSS3 (BEM), JavaScript ES6+ |
| Frontend rendering | Blade templates, Laravel routes, Vite assets |
| Automation services | Python 3.12, Telegram bot, parsers |
| Database | PostgreSQL 16 |
| Web server | Nginx 1.24, PHP-FPM 8.3 |
| Infrastructure | Ubuntu 24.04 LTS, Docker configs, Certbot |
| Security | SSH keys, fail2ban, restricted admin routes |

---

## Features

### Frontend

- Semantic HTML, BEM methodology, no CSS frameworks
- Dark / Light theme toggle
- Adaptive layout with mobile-first behavior
- Static source layout kept separately from CMS-rendered Blade templates

### CMS

- Blog with Builder blocks: heading, text, code, image, quote, before/after
- Portfolio with demo-project ZIP deployment
- Static pages, media library, SEO fields
- Trash system with restore flow
- Site options: social links, hero buttons, resume upload
- Visit tracking with GeoIP2 / MaxMind analytics
- Server monitoring: RAM, disk, banned IPs, failed login attempts
- Email templates with MJML preview and SMTP test sending
- Separate email media library with folder management and bulk upload

### Services

- Telegram freelance bot
- Project parsers
- PostgreSQL-backed automation data
- Isolated Python virtual environments outside the CMS source tree

### Infrastructure

- Nginx virtual host for `bozheslav.com`
- Let's Encrypt SSL via Certbot
- Laravel Scheduler via cron
- PostgreSQL backups
- Deployment script for syncing static HTML layouts into Blade views

---

## Project Structure

```text
bozheslav/
├── cms/                    # Laravel CMS application root
│   ├── app/                # Laravel PHP namespace: models, HTTP, Filament, providers
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── lang/
│   ├── public/             # Web root for Nginx
│   ├── resources/          # Blade views and frontend source used by Laravel
│   ├── routes/
│   ├── storage/            # Runtime files, media, cache, logs
│   ├── tests/
│   ├── artisan
│   ├── composer.json
│   └── package.json
│
├── bozheslav.com/          # Static HTML/CSS/JS source layout
├── services/               # Python bots and parsers
│   ├── freelance_bot/
│   └── hh_parser/
│
├── infrastructure/
│   ├── nginx/              # Nginx site config copy
│   ├── docker/             # Docker-related configs
│   └── deploy/             # Deployment scripts
│
├── backups/                # Local database backup output, ignored by Git
└── README.md
```

The inner `cms/app` directory is the standard Laravel application namespace and should not be renamed.  
The outer `cms` directory describes the component role in the system: content management, site rendering, admin panel, and application workflows.

---

## Local Setup

```bash
git clone https://github.com/Magbusjap/bozheslav.git
cd bozheslav/cms
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

---

## Deploy

Deployment uses `infrastructure/deploy/deploy.sh`.

The script:

- pulls the latest project state when Git is enabled;
- copies source HTML files from `bozheslav.com/` into Blade templates under `cms/resources/views/`;
- clears Laravel views and refreshes route cache.

Production Nginx serves the Laravel public directory:

```nginx
root /var/www/bozheslav/cms/public;
```

Laravel Scheduler runs from:

```bash
cd /var/www/bozheslav/cms && php artisan schedule:run
```

---

## License

Personal project. Not for commercial reuse.

---

<img src="https://bozheslav.com/storage/media/mikgail-bozheslav-favicon-16x16.png" width="16" height="16" alt="bozheslav.com favicon"> [bozheslav.com](https://bozheslav.com)

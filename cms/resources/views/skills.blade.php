<!doctype html>
<html lang="ru">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="google-site-verification" content="3Ka3wmDabuO6R6kev1UuxPrWy5PKznexHg3VKEZfIGI" />
		<meta name="yandex-verification" content="102f35d468ef751b" />
		<link rel="stylesheet" href="/css/index.css" />
		<link rel="shortcut icon" href="/icons/favicon.ico" type="image/x-icon" />
		<title>Умения — Михаил Божеслав</title>
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-2M9GZV0JW3"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","G-2M9GZV0JW3");</script>
<script type="text/javascript">(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window,document,"script","https://mc.yandex.ru/metrika/tag.js?id=108285091","ym");ym(108285091,"init",{webvisor:true,clickmap:true,accurateTrackBounce:true,trackLinks:true});</script>
<noscript><div><img src="https://mc.yandex.ru/watch/108285091" style="position:absolute;left:-9999px;" alt="" /></div></noscript>
</head>
	<body>
		@auth
		<x-admin-bar 
			:editUrl="'/admin'"
			editLabel="Редактировать страницу"
		/>
		@endauth


		<div id="header"></div>
		<main class="main">
			<!-- Skills page -->
			<section class="section skills-page" id="skills">
				<div class="container skills-page__container">
					<div class="skills-page__header">
						<h1 class="skills-page__title">Навыки</h1>
						<p class="skills-page__desc">
							Технологии и инструменты, которыми я владею. Нажмите на любой
							навык чтобы узнать подробнее.
						</p>
					</div>

					<div class="skills-page__grid">
						<!-- Frontend -->
						<div class="card skill-category">
							<div class="card__header">
								<h2 class="card__title">
									<span
										class="skill-category__icon skill-category__icon--frontend"
									>
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#programming"></use>
										</svg>
									</span>
									Frontend
								</h2>
							</div>
							<div class="card__body">
								<div class="skill-category__list">
									<button class="skill-tag" data-skill="html">
										HTML
										<span class="skill-tag__bar" style="--level: 75%"></span>
									</button>
									<button class="skill-tag" data-skill="css">
										CSS
										<span class="skill-tag__bar" style="--level: 75%"></span>
									</button>
									<button class="skill-tag" data-skill="bem">
										BEM
										<span class="skill-tag__bar" style="--level: 75%"></span>
									</button>
									<button class="skill-tag" data-skill="javascript">
										JavaScript
										<span class="skill-tag__bar" style="--level: 60%"></span>
									</button>
									<button class="skill-tag" data-skill="tailwind">
										Tailwind CSS
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
									<button class="skill-tag" data-skill="responsive">
										Responsive Design
										<span class="skill-tag__bar" style="--level: 75%"></span>
									</button>
									<button class="skill-tag" data-skill="itcss">
										ITCSS
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
									<button class="skill-tag" data-skill="alpine-js">
										Alpine.js
										<span class="skill-tag__bar" style="--level: 50%"></span>
									</button>
									<button class="skill-tag" data-skill="mjml">
										MJML
										<span class="skill-tag__bar" style="--level: 65%"></span>
									</button>
									<button class="skill-tag" data-skill="vite">
										Vite
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
								</div>
							</div>
						</div>

						<!-- Backend -->
						<div class="card skill-category">
							<div class="card__header">
								<h2 class="card__title">
									<span
										class="skill-category__icon skill-category__icon--backend"
									>
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#programming"></use>
										</svg>
									</span>
									Backend
								</h2>
							</div>
							<div class="card__body">
								<div class="skill-category__list">
									<button class="skill-tag" data-skill="laravel">
										Laravel
										<span class="skill-tag__bar" style="--level: 65%"></span>
									</button>
									<button class="skill-tag" data-skill="filament">
										Filament
										<span class="skill-tag__bar" style="--level: 70%"></span>
									</button>
									<button class="skill-tag" data-skill="php">
										PHP
										<span class="skill-tag__bar" style="--level: 65%"></span>
									</button>
									<button class="skill-tag" data-skill="livewire">
										Livewire
										<span class="skill-tag__bar" style="--level: 50%"></span>
									</button>
									<button class="skill-tag" data-skill="pest">
										Pest
										<span class="skill-tag__bar" style="--level: 40%"></span>
									</button>
								</div>
							</div>
						</div>

						<!-- DevOps -->
						<div class="card skill-category">
							<div class="card__header">
								<h2 class="card__title">
									<span
										class="skill-category__icon skill-category__icon--devops"
									>
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#devops"></use>
										</svg>
									</span>
									DevOps & Tools
								</h2>
							</div>
							<div class="card__body">
								<div class="skill-category__list">
									<button class="skill-tag" data-skill="linux">
										Linux (Ubuntu)
										<span class="skill-tag__bar" style="--level: 70%"></span>
									</button>
									<button class="skill-tag" data-skill="nginx">
										Nginx
										<span class="skill-tag__bar" style="--level: 65%"></span>
									</button>
									<button class="skill-tag" data-skill="docker">
										Docker
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
									<button class="skill-tag" data-skill="docker-compose">
										Docker Compose
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
									<button class="skill-tag" data-skill="git">
										Git
										<span class="skill-tag__bar" style="--level: 70%"></span>
									</button>
									<button class="skill-tag" data-skill="figma">
										Figma
										<span class="skill-tag__bar" style="--level: 40%"></span>
									</button>
									<button class="skill-tag" data-skill="security">
										Server Hardening
										<span class="skill-tag__bar" style="--level: 70%"></span>
									</button>
									<button class="skill-tag" data-skill="vps">
										VPS Deploy
										<span class="skill-tag__bar" style="--level: 70%"></span>
									</button>
									<button class="skill-tag" data-skill="composer">
										Composer
										<span class="skill-tag__bar" style="--level: 65%"></span>
									</button>
								</div>
							</div>
						</div>

						<!-- Automation -->
						<div class="card skill-category">
							<div class="card__header">
								<h2 class="card__title">
									<span
										class="skill-category__icon skill-category__icon--automation"
									>
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#frameworks"></use>
										</svg>
									</span>
									Автоматизация
								</h2>
							</div>
							<div class="card__body">
								<div class="skill-category__list">
									<button class="skill-tag" data-skill="n8n">
										n8n
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
									<button class="skill-tag" data-skill="telegram-bot">
										Telegram Bot API
										<span class="skill-tag__bar" style="--level: 65%"></span>
									</button>
									<button class="skill-tag" data-skill="openai">
										OpenAI API
										<span class="skill-tag__bar" style="--level: 50%"></span>
									</button>
									<button class="skill-tag" data-skill="claude-api">
										Claude API
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
									<button class="skill-tag" data-skill="deepseek">
										DeepSeek API
										<span class="skill-tag__bar" style="--level: 60%"></span>
									</button>
									<button class="skill-tag" data-skill="prompt">
										Prompt Engineering
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
									<button class="skill-tag" data-skill="playwright">
										Playwright
										<span class="skill-tag__bar" style="--level: 60%"></span>
									</button>
									<button class="skill-tag" data-skill="beautifulsoup">
										BeautifulSoup
										<span class="skill-tag__bar" style="--level: 65%"></span>
									</button>
								</div>
							</div>
						</div>

						<!-- Base data -->
						<div class="card skill-category">
							<div class="card__header">
								<h2 class="card__title">
									<span
										class="skill-category__icon skill-category__icon--database"
									>
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#base-data"></use>
										</svg>
									</span>
									Базы данных
								</h2>
							</div>
							<div class="card__body">
								<div class="skill-category__list">
									<button class="skill-tag" data-skill="postgresql">
										PostgreSQL
										<span class="skill-tag__bar" style="--level: 60%"></span>
									</button>
									<button class="skill-tag" data-skill="mysql">
										MySQL
										<span class="skill-tag__bar" style="--level: 45%"></span>
									</button>
								</div>
							</div>
						</div>

						<!-- Other -->
						<div class="card skill-category">
							<div class="card__header">
								<h2 class="card__title">
									<span
										class="skill-category__icon skill-category__icon--other"
									>
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#cloud"></use>
										</svg>
									</span>
									Ранее
								</h2>
							</div>
							<div class="card__body">
								<div class="skill-category__list">
									<button class="skill-tag" data-skill="wordpress">
										WordPress
										<span class="skill-tag__bar" style="--level: 50%"></span>
									</button>
									<button class="skill-tag" data-skill="csharp">
										C#
										<span class="skill-tag__bar" style="--level: 35%"></span>
									</button>
									<button class="skill-tag" data-skill="python">
										Python
										<span class="skill-tag__bar" style="--level: 55%"></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>

		<!-- Modale -->
		<div class="skill-modal" id="skillModal" aria-hidden="true">
			<div class="skill-modal__overlay" id="skillModalOverlay"></div>
			<div class="skill-modal__window" role="dialog" aria-modal="true">
				<button
					class="skill-modal__close"
					id="skillModalClose"
					aria-label="Закрыть"
				>
					<svg class="sprites badge__icon" aria-hidden="true">
						<use href="/icons/sprites.svg#cross"></use>
					</svg>
				</button>
				<div class="skill-modal__header">
					<h3 class="skill-modal__title" id="skillModalTitle"></h3>
					<span class="skill-modal__level" id="skillModalLevel"></span>
				</div>
				<div class="skill-modal__progress">
					<div class="skill-modal__progress-bar" id="skillModalBar"></div>
				</div>
				<p class="skill-modal__desc" id="skillModalDesc"></p>
			</div>
		</div>

		<div id="footer"></div>
		<script defer src="/js/index.js" type="module"></script>
	</body>
</html>

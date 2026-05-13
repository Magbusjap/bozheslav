<!doctype html>
<html lang="ru">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="google-site-verification" content="3Ka3wmDabuO6R6kev1UuxPrWy5PKznexHg3VKEZfIGI" />
		<meta name="yandex-verification" content="102f35d468ef751b" />
		<link rel="stylesheet" href="/css/index.css" />
		<link rel="stylesheet" href="/css/vendor/highlight.min.css" />
		<link rel="shortcut icon" href="/icons/favicon.ico" type="image/x-icon" />
		<title>Портфолио — Михаил Божеслав</title>
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
			<!-- Portfolio page -->
			<section class="section portfolio-page" id="portfolio">
				<div class="container portfolio-page__container">
					<div class="portfolio-page__header">
						<h1 class="portfolio-page__title">Портфолио</h1>
						<p class="portfolio-page__desc">
							Проекты, которые я реализовал — от вёрстки до деплоя на
							собственном сервере.
						</p>
					</div>

					<!-- Filters -->
					<div class="portfolio-page__filters" id="portfolioFilters">
						<button class="portfolio-filter active" data-filter="all">Все</button>
						@foreach($categories as $category)
						<button class="portfolio-filter" data-filter="{{ $category->slug }}">
							{{ $category->name }}
						</button>
						@endforeach
					</div>

					<!-- Master-detail -->
					<div class="projects__layout">
						<!-- 1/3 — list -->
						<div class="projects__sidebar">
							<div class="projects__list" id="projectsList">
								@foreach($projects as $index => $project)
								<div class="project-item {{ $index === 0 ? 'active' : '' }}" 
									data-category="{{ $project->category->slug ?? 'other' }}"
									onclick="selectProject({{ $index }})"
									style="{{ $index >= 5 ? 'display:none' : '' }}">
									<h3 class="project-item__name">{{ $project->title }}</h3>
									<div class="project-item__actions">
										@if($project->github_url)
										<a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--outline btn--sm">
											<svg class="btn__icon" width="20" height="20" aria-hidden="true">
												<use href="/icons/sprites.svg#github"></use>
											</svg>
											GitHub
										</a>
										@endif
										@if($project->link_url)
										<a href="{{ $project->link_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--primary btn--sm">
											<svg class="btn__icon" width="20" height="20" aria-hidden="true">
												<use href="/icons/sprites.svg#demo"></use>
											</svg>
											{{ $project->link_label ?? 'Demo' }}
										</a>
										@endif
									</div>
								</div>
								@endforeach
							</div>

							<div class="projects__nav">
								<div class="projects__arrows">
									<button class="projects__arrow" id="prevBtn" onclick="prevProject()" aria-label="Предыдущий проект">
										<svg class="sprites badge__icon projects__arrow-left" aria-hidden="true">
											<use href="/icons/sprites.svg#arrow-left"></use>
										</svg>
									</button>
									<span class="projects__counter" id="projectsCounter">1 / {{ count($projects) }}</span>
									<button class="projects__arrow" id="nextBtn" onclick="nextProject()" aria-label="Следующий проект">
										<svg class="sprites badge__icon projects__arrow-right" aria-hidden="true">
											<use href="/icons/sprites.svg#arrow-right"></use>
										</svg>
									</button>
								</div>
							</div>
						</div>

						<!-- 2/3 — detail -->
						<div class="projects__detail">
							@foreach($projects as $index => $project)
							<div class="project-detail card {{ $index === 0 ? 'active' : '' }}" id="project-{{ $index }}">
								<div class="project-detail__image-wrap">
									<img
										src="{{ $project->cover_url ?? '/images/projects/default.jpg' }}"
										alt="Скриншот проекта {{ $project->title }}"
										class="project-detail__image"
										loading="lazy"
									/>
								</div>
								<div class="card__body">
									<h3 class="project-detail__title">{{ $project->title }}</h3>
									<p class="project-detail__desc">{{ $project->description }}</p>
									<div class="project-detail__tags">
										@foreach($project->stack_tags ?? [] as $tag)
										<span class="badge badge__portfolio portfolio-tag">{{ $tag }}</span>
										@endforeach
									</div>
									<div class="project-detail__actions">
										@if($project->github_url)
										<a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--outline btn--sm">
											<svg class="btn__icon" width="20" height="20" aria-hidden="true">
												<use href="/icons/sprites.svg#github"></use>
											</svg>
											GitHub
										</a>
										@endif
										@if($project->link_url)
										<a href="{{ $project->link_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--primary btn--sm">
											<svg class="btn__icon" width="20" height="20" aria-hidden="true">
												<use href="/icons/sprites.svg#demo"></use>
											</svg>
											{{ $project->link_label ?? 'Demo' }}
										</a>
										@endif
									</div>
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</section>

						<!-- About site -->
			<section class="section portfolio-about" id="about-site">
				<div class="container portfolio-about__container">
					<!-- 1. Заголовок, 2. Подзаголовок, 3. Теги стека -->
					<div class="about-site__header">
						<h2 class="about-site__title">bozheslav.ru — тоже личный проект</h2>
						<p class="about-site__subtitle">
							Построен на Laravel и Filament — от пустого сервера до полноценной
							CMS
						</p>
						<div class="about-site__stack">
							<span class="badge badge__skills about-site__tag">Laravel</span>
							<span class="badge badge__skills about-site__tag">Filament</span>
							<span class="badge badge__skills about-site__tag"
								>PostgreSQL</span
							>
							<span class="badge badge__skills about-site__tag">nginx</span>
							<span class="badge badge__skills about-site__tag">PHP 8.3</span>
							<span class="badge badge__skills about-site__tag"
								>HTML/CSS/JS</span
							>
							<span class="badge badge__skills about-site__tag">BEM</span>
							<span class="badge badge__skills about-site__tag">VPS</span>
						</div>
					</div>

					<!-- 4. Философия: 5. текст слева, 6. скриншот справа -->
					<div class="about-site__philosophy">
						<div class="about-site__philosophy-text">
							<h2 class="about-site__section-label">Философия проекта</h2>
							<p>
								Первый вопрос, который я задал себе — как быть полезным рынку и
								бизнесу? Большинство идут по простому пути: WordPress, готовые
								шаблоны, плагины за $50 в месяц.
							</p>
							<p>
								Я выбрал другой путь. Собственный VPS, Laravel, PostgreSQL — всё
								с нуля. Слишком большое воображение не позволяет работать
								вполсилы.
							</p>
							<h4 class="about-site__subsection-title">
								Почему Laravel, а не WordPress?
							</h4>
							<p>
								Laravel заставляет думать архитектурно. Здесь нет магии плагинов
								— только код, который ты понимаешь и полностью контролируешь.
							</p>
						</div>
						<!-- 6. Заглушка скриншота -->
						<div class="about-site__philosophy-image">
							<div class="about-site__placeholder">
								<img
									class="laravel-image"
									src="/images/Laravel.webp"
									loading="lazy"
									alt="laravel-image-1"
								/>
							</div>
						</div>
					</div>

					<!-- 7. Техническая реализация — три карточки -->
					<div class="about-site__tech">
						<h2 class="about-site__section-title">Техническая реализация</h2>
						<div class="about-site__tech-grid">
							<div class="card">
								<div class="about-site__card-placeholder" aria-hidden="true">
									<img
										class="laravel-image about-site__card-image"
										src="/images/laravel-image-2.png"
										loading="lazy"
										alt="laravel-image-2"
									/>
								</div>

								<div class="card__header">
									<h3 class="card__title">
										<span
											class="about-site__card-icon about-site__card-icon--frontend"
										>
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="./icons/sprites.svg#programming"></use>
											</svg>
										</span>
										Фронтенд с нуля
									</h3>
								</div>
								<div class="card__body">
									<p class="about-site__card-desc">
										HTML/CSS/JS без фреймворков. BEM-методология, адаптивный
										дизайн, тёмная и светлая тема — всё написано руками.
										JS-скрипты структурированы как ES-модули.
									</p>
								</div>
							</div>

							<div class="card">
								<div class="about-site__card-placeholder" aria-hidden="true">
									<img
										class="laravel-image about-site__card-image"
										src="/images/laravel-image-3.png"
										loading="lazy"
										alt="laravel-image-3"
									/>
								</div>
								<div class="card__header">
									<h3 class="card__title">
										<span
											class="about-site__card-icon about-site__card-icon--server"
										>
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="./icons/sprites.svg#devops"></use>
											</svg>
										</span>
										Серверная инфраструктура
									</h3>
								</div>
								<div class="card__body">
									<p class="about-site__card-desc">
										Ubuntu 24.04, nginx с виртуальными хостами, PHP-FPM 8.3,
										PostgreSQL 16, SSL с автопродлением. Деплой одной командой
										через bash-скрипт.
									</p>
								</div>
							</div>

							<div class="card">
								<div class="about-site__card-placeholder" aria-hidden="true">
									<img
										class="laravel-image about-site__card-image"
										src="/images/laravel-image-4.png"
										loading="lazy"
										alt="laravel-image-4"
									/>
								</div>
								<div class="card__header">
									<h3 class="card__title">
										<span
											class="about-site__card-icon about-site__card-icon--security"
										>
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="./icons/sprites.svg#qa"></use>
											</svg>
										</span>
										Безопасность
									</h3>
								</div>
								<div class="card__body">
									<p class="about-site__card-desc">
										fail2ban защищает SSH от брутфорса, honeypot и rate limiting
										на форме обратной связи, HTTP → HTTPS редирект, закрытый
										порт SSH.
									</p>
								</div>
							</div>
						</div>
					</div>

					<!-- 8. Граница секции -->
					<hr class="about-site__divider" />

					<!-- 9–12. Возможности админ-панели -->
					<div class="about-site__admin">
						<div class="about-site__admin-header">
							<h2 class="about-site__section-title">
								Возможности админ-панели
							</h2>
							<p class="about-site__admin-subtitle">
								Filament заменил WordPress полностью — без единого платного
								плагина
							</p>
						</div>
						<!-- 11. Заглушка скриншота — на всю ширину, по центру -->
						<div class="about-site__admin-image">
							
								<div class="before-after js-before-after" >
        
									<!-- After (нижний слой — изображение "после") -->
									<img 
										src="/storage/media/filament-after.webp" 
										class="before-after__img-after" 
    									alt="Filament сейчас"
									/>
									
									<!-- Before (верхний слой — изображение "до") -->
									<div class="before-after__layer js-before-layer">
										<img 
											src="/storage/media/filament-before.webp" 
											class="before-after__img-before js-before-img" 
    										alt="Filament в начале"
										/>
									</div>
									
									<!-- Разделитель -->
									<div class="before-after__handle js-handle">
										<div class="before-after__circle">
											<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M18 8L22 12L18 16M6 8L2 12L6 16"/>
											</svg>
										</div>
									</div>
									
								</div>
							<!-- <div class="about-site__placeholder">
							</div> -->
						</div>

						<!-- 12. Список возможностей — ниже -->
						<div class="card about-site__admin-features">
							<div class="card__body card__body--pt">
								<ul class="about-site__features-list">
									<li>✅ Управление портфолио и проектами</li>
									<li>✅ Медиабиблиотека с загрузкой ZIP</li>
									<li>✅ Блог с категориями и SEO-полями</li>
									<li>✅ Страницы с блочным редактором</li>
									<li>✅ Настройки сайта из одного места</li>
									<li>✅ Логи и управление кэшем в панели</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>


			<!-- Contacts -->
			<section class="section portfolio-contacts" id="contacts">
				<div class="container portfolio-contacts__container">
					<!-- 13–15. CTA-блок -->
					<div class="portfolio-cta card">
						<div class="portfolio-cta__header">
							<div class="portfolio-cta__header-left">
								<!-- 14. Иконка -->
								<svg class="portfolio-cta__icon sprites" aria-hidden="true">
									<use href="/icons/sprites.svg#handshake"></use>
								</svg>
								<!-- 13. Заголовок -->
								<h2 class="portfolio-cta__title">Готов обсудить проект</h2>
							</div>
							<a	href="{{ option('social_telegram', '#') }}" 
								class="portfolio-cta__header-btn" 
								aria-label="Telegram" 
								target="_blank" 
								rel="noopener noreferrer"
							>
								<svg
									class="btn__icon"
									width="18"
									height="18"
									aria-hidden="true"
								>
									<use href="/icons/sprites.svg#telegram"></use>
								</svg>
								Telegram
							</a>
						</div>
						<!-- 15. Текст и кнопки -->
						<div class="portfolio-cta__body">
							<p class="portfolio-cta__text">
								Если вам нужен сайт на Laravel, настройка сервера или
								автоматизация процессов — напишите мне в Telegram.
							</p>
							<div class="portfolio-cta__actions">
								<a	href="{{ option('social_telegram', '#') }}" 
									class="btn btn--outline" 
									aria-label="Telegram" 
									target="_blank" 
									rel="noopener noreferrer"
								>
									<svg
										class="btn__icon"
										width="20"
										height="20"
										aria-hidden="true"
									>
										<use href="/icons/sprites.svg#telegram"></use>
									</svg>
									Написать в Telegram
								</a>
								<a 
									href="{{ option('social_github', '#') }}" 
									class="btn btn--outline" 
									aria-label="GitHub"
									target="_blank"
    								rel="noopener noreferrer"
								>
									<svg
										class="sprites badge__icon contact__info-icon"
										aria-hidden="true"
									>
										<use href="/icons/sprites.svg#github"></use>
									</svg>
									GitHub
								</a>

								<a 
									href="{{ option('social_email', '#') }}" 
									class="btn btn--outline" 
									aria-label="Email"
								>
									<svg
										class="sprites badge__icon contact__info-icon"
										aria-hidden="true"
									>
										<use href="/icons/sprites.svg#email"></use>
									</svg>
									Связаться по email
								</a>
							</div>
						</div>
					</div>
				</div>
			</section>

		</main>

		<div id="footer"></div>
		<script src="/js/vendor/highlight.min.js"></script>
		<script>hljs.highlightAll();</script>
		<script defer src="/js/index.js" type="module"></script>
	</body>
</html>

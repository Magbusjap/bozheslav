<!doctype html>
<html lang="ru">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="google-site-verification" content="3Ka3wmDabuO6R6kev1UuxPrWy5PKznexHg3VKEZfIGI" />
		<meta name="yandex-verification" content="102f35d468ef751b" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="/css/index.css" />
		<link rel="shortcut icon" href="/icons/favicon.ico" type="image/x-icon" />
		<title>Михаил Божеслав — Разработчик</title>
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
			<!-- Hero -->
			<section class="hero section" id="home">
				<!-- Animated background -->
				<div class="hero__bg">
					<div class="hero__blob hero__blob--1"></div>
					<div class="hero__blob hero__blob--2"></div>
					<div class="hero__blob hero__blob--3"></div>
					<div class="hero__grid-bg">
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
						<div class="hero__grid-col"></div>
					</div>
				</div>

				<div class="container hero__container">
					<div class="hero__layout">
						<!-- Left: text content -->
						<div class="hero__content">
							<div class="hero__badge-wrap">
								<span class="badge badge--primary badge__hero">
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#handshake"></use>
									</svg>
									Доступен для сотрудничества
								</span>
							</div>

							<div class="hero__heading-group">
								<h1 class="hero__title">
									<span class="hero__typed">
										<span class="hero__typed-prefix">&gt;</span>
										<span class="hero__typed-text"></span>
										<span class="hero__cursor">|</span>
									</span>
									<span class="hero__title-name"
										>Михаил Анкудинов, Разработчик</span
									>
								</h1>
								<div class="hero__desc-group">
									<p class="hero__lead">
										Backend-разработчик Laravel + DevOps. Автор open-source CMS OnFlaude.
										Открыт к новым проектам.
									</p>
								</div>
							</div>

							<div class="hero__actions">
								 <!-- href="{{ option('hero_resume_url', '#') }}" -->
								<a href="/resume/download" class="btn btn--primary btn--lg">
									<svg
										class="btn__icon"
										width="20"
										height="20"
										aria-hidden="true"
									>
										<use href="/icons/sprites.svg#download"></use>
									</svg>
									Скачать моё резюме
								</a>
								<a href="{{ option('hero_portfolio_url', '#projects') }}" class="btn btn--outline btn--lg">
									<svg
										class="btn__icon"
										width="20"
										height="20"
										aria-hidden="true"
									>
										<use href="/icons/sprites.svg#folder-data-storage"></use>
									</svg>
									Посмотреть мои работы
								</a>
							</div>

							<div class="hero__socials">
								<a 
									href="{{ option('social_github', '#') }}" 
									class="btn btn--ghost btn--icon btn--circle" 
									aria-label="GitHub"
									target="_blank"
    								rel="noopener noreferrer"
								>
									<svg class="sprites" aria-hidden="true">
										<use href="/icons/sprites.svg#github"></use>
									</svg>
								</a>
								<a 
									href="{{ option('social_telegram', '#') }}" 
									class="btn btn--ghost btn--icon btn--circle" 
									aria-label="Telegram"
									target="_blank"
    								rel="noopener noreferrer"
								>
									<svg class="sprites" aria-hidden="true">
										<use href="/icons/sprites.svg#telegram"></use>
									</svg>
								</a>
								<a 
									href="{{ option('social_email', '#') }}" 
									class="btn btn--ghost btn--icon btn--circle" 
									aria-label="Email"
								>
									<svg class="sprites" aria-hidden="true">
										<use href="/icons/sprites.svg#email"></use>
									</svg>
								</a>
							</div>

							<div class="hero__stats">
								<div class="stat">
									<div class="stat__value">5+</div>
									<p class="stat__label">Проектов</p>
								</div>
								<div class="stat">
									<div class="stat__value">1+</div>
									<p class="stat__label">Год в IT (и не только)</p>
								</div>
								<div class="stat">
									<div class="stat__value">✓ отзывы</div>
									<p class="stat__label">На Kwork</p>
								</div>
							</div>
						</div>

						<!-- Right: photo -->
						<div class="hero__image-wrap">
							<div class="hero__image-container">
								<div class="hero__glow hero__glow--top"></div>
								<div class="hero__glow hero__glow--bottom"></div>

								<div class="hero__avatar-group">
									<div class="hero__avatar">
										<picture>
											<source
												srcset="/images/mikhail-ankudinov-developer.webp"
												type="image/webp"
											/>
											<img
												class="hero__photo"
												src="/images/mikhail-ankudinov-developer.jpg"
												fetchpriority="high"
												alt="Михаил Анкудинов — Разработчик"
											/>
										</picture>
										<div class="hero__avatar-overlay"></div>
									</div>

									<div class="hero__status">
										<span class="hero__status-dot"></span>
										<a href="{{ option('hero_hire_url', '#contacts') }}" class="hero__status-link">
											<span class="hero__status-text">Нанять на проект</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Scroll indicator -->
				<a class="hero__scroll" href="#skills">
					<span class="hero__scroll-text">Вниз</span>
					<svg class="sprites badge__icon hero__scroll-icon" aria-hidden="true">
						<use href="/icons/sprites.svg#general-arrow"></use>
					</svg>
				</a>
			</section>

			<!-- Skills -->
			<section class="skills section" id="skills">
				<div class="container skills__container">
					<div class="skills__header">
						<h2 class="skills__title">Мой стек</h2>
						<p class="skills__desc">
							Представляю вам свой стек технологий, которым я овладел за все
							время своего профессионального обучения.
						</p>
					</div>

					<div class="skills__grid">
						<div class="card">
							<div class="card__header">
								<h3 class="card__title">
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#programming"></use>
									</svg>
									Frontend
								</h3>
							</div>
							<div class="card__body">
								<div class="skills__tags">
									<span class="badge badge__skills">HTML</span>
									<span class="badge badge__skills">CSS</span>
									<span class="badge badge__skills">BEM</span>
									<span class="badge badge__skills">JavaScript</span>
									<span class="badge badge__skills">Tailwind CSS</span>
									<span class="badge badge__skills">Responsive Design</span>
									<span class="badge badge__skills">ITCSS</span>
									<span class="badge badge__skills">Alpine.js</span>
									<span class="badge badge__skills">Vite</span>
									<span class="badge badge__skills">MJML</span>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card__header">
								<h3 class="card__title">
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#programming"></use>
									</svg>
									Backend
								</h3>
							</div>
							<div class="card__body">
								<div class="skills__tags">
									<span class="badge badge__skills">Laravel</span>
									<span class="badge badge__skills">PHP</span>
									<span class="badge badge__skills">Filament</span>
									<span class="badge badge__skills">Livewire</span>
									<span class="badge badge__skills">Pest</span>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card__header">
								<h3 class="card__title">
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#devops"></use>
									</svg>
									DevOps & Tools
								</h3>
							</div>
							<div class="card__body">
								<div class="skills__tags">
									<span class="badge badge__skills">Linux (Ubuntu)</span>
									<span class="badge badge__skills">Nginx</span>
									<span class="badge badge__skills">Docker</span>
									<span class="badge badge__skills">Docker Compose</span>
									<span class="badge badge__skills">Git</span>
									<span class="badge badge__skills">VS Code</span>
									<span class="badge badge__skills">Figma</span>
									<span class="badge badge__skills">Fail2Ban</span>
									<span class="badge badge__skills">UFW</span>
									<span class="badge badge__skills">SSL</span>
									<span class="badge badge__skills">Composer</span>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card__header">
								<h3 class="card__title">
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#frameworks"></use>
									</svg>
									Автоматизация
								</h3>
							</div>
							<div class="card__body">
								<div class="skills__tags">
									<span class="badge badge__skills">n8n</span>
									<span class="badge badge__skills">Telegram Bot API</span>
									<span class="badge badge__skills">OpenAI API</span>
									<span class="badge badge__skills">Claude API</span>
									<span class="badge badge__skills">DeepSeek API</span>
									<span class="badge badge__skills">Playwright</span>
									<span class="badge badge__skills">BeautifulSoup</span>
									<span class="badge badge__skills">AI</span>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card__header">
								<h3 class="card__title">
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#base-data"></use>
									</svg>
									Базы данных
								</h3>
							</div>
							<div class="card__body">
								<div class="skills__tags">
									<span class="badge badge__skills">MySQL</span>
									<span class="badge badge__skills">PostgreSQL</span>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card__header">
								<h3 class="card__title">
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#cloud"></use>
									</svg>
									Ранее
								</h3>
							</div>
							<div class="card__body">
								<div class="skills__tags">
									<span class="badge badge__skills">WordPress</span>
									<span class="badge badge__skills">C#</span>
									<span class="badge badge__skills">Python</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Portfolio -->
			<section class="section projects" id="projects">
				<div class="container projects__container">
					<div class="projects__header">
						<h2 class="projects__title">Портфолио</h2>
						<p class="projects__desc">
							Проекты, которые я реализовал — от вёрстки до деплоя на
							собственном сервере.
						</p>
					</div>

					<div class="projects__layout">
						<!-- 1/3 -->
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

							<!-- Nav and links -->
							<div class="projects__nav">
								<div class="projects__arrows">
									<button
										class="projects__arrow"
										id="prevBtn"
										onclick="prevProject()"
										aria-label="Предыдущий проект"
									>
										<svg
											class="sprites badge__icon projects__arrow-left"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#arrow-left"></use>
										</svg>
									</button>
									<span class="projects__counter" id="projectsCounter">1 / {{ count($projects) }}</span>
									<button
										class="projects__arrow"
										id="nextBtn"
										onclick="nextProject()"
										aria-label="Следующий проект"
									>
										<svg
											class="sprites badge__icon projects__arrow-right"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#arrow-right"></use>
										</svg>
									</button>
								</div>
								<a href="/portfolio" class="projects__all">
									Смотреть все проекты
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#general-arrow"></use>
									</svg>
								</a>
							</div>
						</div>

						<!-- 2/3 -->
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
										<span class="badge badge__portfolio">{{ $tag }}</span>
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

			<!-- Experience -->
			<section class="section experience" id="experience">
				<div class="container experience__container">
					<div class="experience__header">
						<h2 class="experience__title">
							Опыт: От доводки металла к чистоте кода <br />
							Путь инженера-самоучки
						</h2>
						<p class="experience__desc">
							Мой путь в IT начался не с теории, а с практики: от первых правок
							в шаблонах WordPress в 2013-м до осознанного изучения алгоритмов
							на C# и Python. Я не просто учу синтаксис — я строю системы. Что я
							принес из «реального сектора» в разработку:
						</p>
					</div>

					<div class="experience__list">
						<div class="card">
							<div class="card__header">
								<div class="experience__card-top">
									<div>
										<h3 class="card__title">
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="/icons/sprites.svg#portfolio"></use>
											</svg>
											Self-Taught Developer / Lab
										</h3>
									</div>
									<div class="experience__meta">
										<div class="experience__meta-item">
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="/icons/sprites.svg#calendar"></use>
											</svg>
											2024 — н.в.
										</div>
									</div>
								</div>
							</div>
							<div class="card__body">
								<p class="experience__text">
									<span class="text-bold">Стек:</span> Python, HTML, CSS,
									Laravel, PostgreSQL, Nginx, Docker, n8n. <br /><span
										class="text-bold"
										>Достижения:</span
									>
									Самостоятельное развертывание и администрирование
									Linux-сервера (Ubuntu). Настройка CI/CD процессов через Docker
									Compose. Разработка личных проектов с интеграцией OpenAI и
									Telegram Bot API.
								</p>
								<div class="experience__tags">
									<span class="badge badge--outline badge__experience"
										>Python</span
									>
									<span class="badge badge--outline badge__experience"
										>HTML</span
									>
									<span class="badge badge--outline badge__experience"
										>CSS</span
									>
									<span class="badge badge--outline badge__experience"
										>Laravel</span
									>
									<span class="badge badge--outline badge__experience"
										>PostgreSQL</span
									>
									<span class="badge badge--outline badge__experience"
										>Nginx</span
									>
									<span class="badge badge--outline badge__experience"
										>Docker</span
									>
									<span class="badge badge--outline badge__experience"
										>n8n</span
									>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card__header">
								<div class="experience__card-top">
									<div>
										<h3 class="card__title">
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="/icons/sprites.svg#portfolio"></use>
											</svg>
											Производственный инженер
										</h3>
									</div>
									<div class="experience__meta">
										<div class="experience__meta-item">
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="/icons/sprites.svg#calendar"></use>
											</svg>
											2022 — 2024
										</div>
									</div>
								</div>
							</div>
							<div class="card__body">
								<p class="experience__text">
									<span class="text-bold">Обязанности:</span> Ремонтировал и
									доводил измерительные инструменты: от штангенциркулей до
									микрометров. <br /><span class="text-bold">IT-связь:</span>
									Вовлеченным и упрямство в работе за 2 года помогло мне развить
									предельную концентрацию и прийти к системному подходу, что
									послужило к будущей диагностике неисправностей. Благодаря
									этому я понял, что монотонный труд в программировании — это
									как глоток свежего воздуха
								</p>
							</div>
						</div>

						<div class="card">
							<div class="card__header">
								<div class="experience__card-top">
									<div>
										<h3 class="card__title">
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="/icons/sprites.svg#portfolio"></use>
											</svg>
											Специалист по системам и данным
										</h3>
										<p class="experience__company">Витамилк / Ростелеком</p>
									</div>
									<div class="experience__meta">
										<div class="experience__meta-item">
											<svg class="sprites badge__icon" aria-hidden="true">
												<use href="/icons/sprites.svg#calendar"></use>
											</svg>
											2016 — 2022
										</div>
									</div>
								</div>
							</div>
							<div class="card__body">
								<p class="experience__text">
									<span class="text-bold">Обязанности:</span> Работал в CRM (1С,
									СБИС, Инфо-Предприятие), обрабатывал претензии, занимался
									логистикой. <br /><span class="text-bold">IT-связь:</span>
									Создавал корпоративный сайт на WordPress. Глубокое понимание
									того, как данные движутся по системе и как конечный
									пользователь взаимодействует с интерфейсом.
								</p>
								<div class="experience__tags">
									<span class="badge badge--outline badge__experience"
										>СБИС</span
									>
									<span class="badge badge--outline badge__experience"
										>1С Предприятие</span
									>
									<span class="badge badge--outline badge__experience"
										>Инфо-Предприятие</span
									>
									<span class="badge badge--outline badge__experience"
										>Wordpress</span
									>
								</div>
							</div>
						</div>

						<h3 class="experience__strong"><strong>Текущий фокус:</strong></h3>
						<p>
							Сейчас я глубоко погружен в экосистему Laravel + PostgreSQL. Моя
							цель — создавать высокопроизводительные веб-сервисы, где каждый
							компонент работает так же четко, как откалиброванный измерительный
							прибор.
						</p>
					</div>
				</div>
			</section>

			<!-- Blog -->
			<section class="section blog" id="blog">
				<div class="container blog__container">
					<div class="blog__header">
						<h2 class="blog__title">Блог</h2>
						<p class="blog__desc">
							Пишу о разработке, инструментах, книгах и профессиональном росте.
						</p>
					</div>

					<div class="blog__grid">
						@forelse($posts as $post)
						<article class="card blog-card">
							<div class="blog-card__image-wrap">
								<img
									src="{{ $post->cover_url ?? '/images/blog/default.jpg' }}"
									alt="{{ $post->title }}"
									class="blog-card__image"
									loading="lazy"
								/>
							</div>
							<div class="card__body">
								<div class="blog-card__meta">
									<a href="/blog?category={{ $post->category->slug ?? '' }}" class="badge badge__blog">
										{{ $post->category->name ?? 'Разное' }}
									</a>
									<span class="blog-card__date-group">
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#calendar"></use>
										</svg>
										<time class="blog-card__date" datetime="{{ $post->created_at->format('Y-m-d') }}">
											{{ $post->created_at->translatedFormat('d F Y') }}
										</time>
									</span>
								</div>
								<a href="/blog/{{ $post->slug }}" class="blog-card__link-a" >
									<h2 class="blog-card__title">{{ $post->title }}</h2>
								</a>
								<p class="blog-card__desc">{{ $post->excerpt }}</p>
								<a href="/blog/{{ $post->slug }}" class="blog-card__link">
									Читать далее
									<svg class="sprites badge__icon" aria-hidden="true">
										<use href="/icons/sprites.svg#general-arrow"></use>
									</svg>
								</a>
							</div>
						</article>
						@empty
						<p>Пока нет публикаций.</p>
						@endforelse
					</div>
				</div>
			</section>

			<!-- Contacts -->
			<section class="section contacts" id="contacts">
				<div class="container contacts__container">
					<div class="contacts__header">
						<h2 class="contacts__title">Есть задача — давай решим</h2>
						<p class="contacts__desc">
							Верстаю, настраиваю серверы, автоматизирую. Если вам нужен человек
							который разберётся сам — напишите.
						</p>
					</div>

					<div class="contacts__grid">
						<!-- Info cards -->
						<div class="contacts__info">
							<div class="card">
								<div class="card__body">
									<div class="contacts__info-row">
										<svg
											class="sprites badge__icon contacts__info-icon"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#email"></use>
										</svg>
										<div>
											<h4 class="contacts__info-label">Почта</h4>
											<p class="contacts__info-value">{{ option('contact_email', 'i@mankudinov.ru') }}</p>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card__body">
									<div class="contacts__info-row">
										<svg
											class="sprites badge__icon contacts__info-icon"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#phone"></use>
										</svg>
										<div>
											<h4 class="contacts__info-label">Телефон</h4>
											<p class="contacts__info-value">{{ option('contact_phone', '+7 (995) 6907083') }}</p>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card__body">
									<div class="contacts__info-row">
										<svg
											class="sprites badge__icon contacts__info-icon"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#location"></use>
										</svg>
										<div>
											<h4 class="contacts__info-label">
												Текущее местоположение
											</h4>
											<p class="contacts__info-value">{{ option('contact_city', 'Пермь, Россия · Удалённая работа') }}</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Form card -->
						<div class="card">
							<div class="card__header">
								<h3 class="card__title">Оставить контакты для связи</h3>
							</div>
							<div class="card__body">
								<form class="contacts__form" id="contactForm">
									@csrf
									<!-- Honeypot — скрытое поле, боты заполняют, люди нет -->
									<input type="text" name="honeypot" style="display:none" tabindex="-1" autocomplete="off">
									
									<div class="contacts__form-row">
										<input
											class="input"
											type="text"
											name="name"
											placeholder="Ваше имя *"
											required
										/>
										<input
											class="input"
											type="email"
											name="email"
											placeholder="Ваша почта *"
											required
										/>
									</div>
									<input class="input" type="text" name="subject" placeholder="Тема письма *" />
									<textarea
										class="input input--textarea"
										name="message"
										placeholder="Ваше сообщение"
										rows="5"
									></textarea>
									<button type="submit" class="btn btn--primary btn--full">
										<svg class="sprites badge__icon" aria-hidden="true">
											<use href="/icons/sprites.svg#telegram"></use>
										</svg>
										Отправить
									</button>
								</form>
							</div>
						</div>
					</div>

					<div class="contacts__socials">
						<strong class="contacts__find">Вы можете найти меня здесь:</strong>
						<div class="hero__socials">
							<a 
								href="{{ option('social_github', '#') }}" 
								class="btn btn--ghost btn--icon btn--circle" 
								aria-label="GitHub"
								target="_blank"
    							rel="noopener noreferrer"
							>
								<svg class="sprites" aria-hidden="true">
									<use href="/icons/sprites.svg#github"></use>
								</svg>
							</a>
							<a 
								href="{{ option('social_telegram', '#') }}" 
								class="btn btn--ghost btn--icon btn--circle" 
								aria-label="Telegram"
								target="_blank"
    							rel="noopener noreferrer"
							>
								<svg class="sprites" aria-hidden="true">
									<use href="/icons/sprites.svg#telegram"></use>
								</svg>
							</a>
							<a 
								href="{{ option('social_email', '#') }}" 
								class="btn btn--ghost btn--icon btn--circle" 
								aria-label="Email"
							>
								<svg class="sprites" aria-hidden="true">
									<use href="/icons/sprites.svg#email"></use>
								</svg>
							</a>
						</div>
					</div>
				</div>
			</section>
		</main>

		<div id="footer"></div>
		<script defer src="/js/index.js" type="module"></script>
	</body>
</html>

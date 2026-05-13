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
		<title>Контакты — Михаил Божеслав</title>
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
			<!-- Contacts page -->
			<section class="section contacts-page" id="contact">
				<div class="container contacts-page__container">
					<div class="contacts-page__header">
						<h1 class="contacts-page__title">Есть задача — давай решим</h1>
						<p class="contacts-page__desc">
							Верстаю, настраиваю серверы, автоматизирую. Если вам нужен человек
							который разберётся сам — напишите.
						</p>
					</div>

					<div class="contacts-page__grid">
						<!-- Info cards -->
						<div class="contacts-page__info">
							<div class="card">
								<div class="card__body">
									<div class="contacts-page__info-row">
										<svg
											class="sprites badge__icon contacts-page__info-icon"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#email"></use>
										</svg>
										<div>
											<h4 class="contacts-page__info-label">Почта</h4>
											<p class="contacts__info-value">{{ option('contact_email', 'i@mankudinov.ru') }}</p>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card__body">
									<div class="contacts-page__info-row">
										<svg
											class="sprites badge__icon contacts-page__info-icon"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#phone"></use>
										</svg>
										<div>
											<h4 class="contacts-page__info-label">Телефон</h4>
											<p class="contacts__info-value">{{ option('contact_phone', '+7 (995) 6907083') }}</p>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card__body">
									<div class="contacts-page__info-row">
										<svg
											class="sprites badge__icon contacts-page__info-icon"
											aria-hidden="true"
										>
											<use href="/icons/sprites.svg#location"></use>
										</svg>
										<div>
											<h4 class="contacts-page__info-label">
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
								<form class="contacts-page__form" id="contactForm">
									@csrf
									<input type="text" name="honeypot" style="display:none" tabindex="-1" autocomplete="off">
									
									<div class="contacts-page__form-row">
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
									<input class="input" type="text" name="subject" placeholder="Тема письма *" required />
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

					<div class="contacts-page__socials">
						<strong class="contacts-page__find">Вы можете найти меня здесь:</strong>
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

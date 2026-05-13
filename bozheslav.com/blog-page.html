<!doctype html>
<html lang="ru">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="google-site-verification" content="3Ka3wmDabuO6R6kev1UuxPrWy5PKznexHg3VKEZfIGI" />
		<meta name="yandex-verification" content="102f35d468ef751b" />
		<link rel="stylesheet" href="/css/index.css" />
		<link rel="shortcut icon" href="/icons/favicon.ico" type="image/x-icon" />
		<title>Блог — Михаил Божеслав</title>
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
			<!-- Blog page -->
			<section class="section blog-page" id="blog">
				<div class="container blog-page__container">
					<div class="blog-page__header">
						<h1 class="blog-page__title">Блог</h1>
						<p class="blog-page__desc">
							Пишу о разработке, инструментах, книгах и профессиональном росте.
						</p>
					</div>

					<!-- Filters -->
					<div class="blog-page__filters" id="blogFilters">
						<button class="blog-filter active" data-filter="all">Все</button>
						@foreach($categories as $category)
						<button class="blog-filter" data-filter="{{ $category->slug }}">{{ $category->name }}</button>
						@endforeach
					</div>

					<!-- Article grid -->
					<div class="blog-page__grid" id="blogGrid">
					@foreach($posts as $post)
					<article class="card blog-card" data-category="{{ $post->category->slug ?? 'other' }}">
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
								<span class="badge badge__blog blog-filter" data-filter="{{ $post->category->slug ?? 'other' }}" style="cursor:pointer">{{ $post->category->name ?? 'Разное' }}</span>
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
					@endforeach
					</div>

					<!-- Pagination -->
					<nav
						class="blog-pagination"
						id="blogPagination"
						aria-label="Страницы блога"
					>
						<button
							class="blog-pagination__btn"
							id="prevPage"
							onclick="prevPage()"
							aria-label="Предыдущая страница"
							disabled
						>
							<svg class="sprites badge__icon" aria-hidden="true">
								<use href="/icons/sprites.svg#arrow-left"></use>
							</svg>
						</button>
						<div class="blog-pagination__pages" id="paginationPages"></div>
						<button
							class="blog-pagination__btn"
							id="nextPage"
							onclick="nextPage()"
							aria-label="Следующая страница"
						>
							<svg class="sprites badge__icon" aria-hidden="true">
								<use href="/icons/sprites.svg#arrow-right"></use>
							</svg>
						</button>
					</nav>
				</div>
			</section>
		</main>

		<div id="footer"></div>
		<script defer src="/js/index.js" type="module"></script>
	</body>
</html>

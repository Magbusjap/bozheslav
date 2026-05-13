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
		<title>Статья — Михаил Божеслав</title>
		<title>{{ $post->seo_title ?? $post->title . ' — Михаил Божеслав' }}</title>
		<meta name="description" content="{{ $post->seo_description ?? $post->excerpt }}">
		<meta property="og:title" content="{{ $post->seo_title ?? $post->title }}">
		<meta property="og:description" content="{{ $post->seo_description ?? $post->excerpt }}">
		<meta property="og:image" content="{{ $post->cover_url ?? '' }}">
		<meta property="og:type" content="article">
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-2M9GZV0JW3"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","G-2M9GZV0JW3");</script>
<script type="text/javascript">(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window,document,"script","https://mc.yandex.ru/metrika/tag.js?id=108285091","ym");ym(108285091,"init",{webvisor:true,clickmap:true,accurateTrackBounce:true,trackLinks:true});</script>
<noscript><div><img src="https://mc.yandex.ru/watch/108285091" style="position:absolute;left:-9999px;" alt="" /></div></noscript>
</head>
	<body>
		@auth
		<x-admin-bar 
			:editUrl="isset($post) ? '/admin/posts/' . $post->id . '/edit' : null"
			:editLabel="isset($post) && $post->status === 'draft' ? 'Редактировать черновик' : 'Редактировать запись'"
		/>
		@endauth

		<div id="header"></div>

		<main class="main">
			<article class="section article-page" id="article">
				<div class="container article-page__container">
					<!-- Breadcrumbs -->
					<nav class="article-page__breadcrumb" aria-label="Навигация">
						<a href="/blog" class="article-page__breadcrumb-link"
							>← Блог</a
						>
					</nav>

					<!-- Meta -->
							@isset($post)
					<div class="article-page__meta">
						<span class="badge badge__blog" data-filter="{{ $post->category->slug ?? 'other' }}">{{ $post->category->name ?? 'Разное' }}</span>
						<!-- <span class="badge badge__blog">{{ $post->category->name ?? 'Разное' }}</span> -->
						<time class="blog-card__date" datetime="{{ $post->created_at->format('Y-m-d') }}">
							{{ $post->created_at->translatedFormat('d F Y') }}
						</time>
					</div>

					<!-- Cover -->
					@if($post->cover_image)
					<div class="article-page__cover">
						<img src="{{ $post->cover_url }}" alt="{{ $post->title }}" />
					</div>
					@endif
					
					<!-- Title -->
					<h1 class="article-page__title">{{ $post->title }}</h1>


					<!-- Content -->
					<div class="article-page__content">
						@foreach($post->content as $block)
							@switch($block['type'])
								@case('heading')
									<{{ $block['data']['level'] }}>{{ $block['data']['text'] }}</{{ $block['data']['level'] }}>
									@break
								@case('text')
									{!! $block['data']['content'] !!}
									@break
								@case('code')
									<div class="article-page__code-wrapper">
										<span class="article-page__code-lang">{{ $block['data']['language'] }}</span>
										<pre><code class="language-{{ $block['data']['language'] }}">{{ $block['data']['code'] }}</code></pre>
									</div>
									@break
								@case('image')
									<figure>
								@php
									$media = \Awcodes\Curator\Models\Media::find($block['data']['url']);
								@endphp
									<img src="{{ \App\Models\Post::getMediaUrl($block['data']['url']) }}" 
										alt="{{ $block['data']['caption'] ?? '' }}"
										style="
											@if(!empty($block['data']['width'])) width: {{ $block['data']['width'] }}px; @endif
											@if(!empty($block['data']['height'])) height: {{ $block['data']['height'] }}px; @endif
										"
									/>
										@if(!empty($block['data']['caption']))
											<figcaption>{{ $block['data']['caption'] }}</figcaption>
										@endif
									</figure>
									@break
								@case('quote')
									<blockquote>
										<p>{{ $block['data']['text'] }}</p>
										@if(!empty($block['data']['author']))
											<cite>— {{ $block['data']['author'] }}</cite>
										@endif
									</blockquote>
									@break
								@case('markdown')
									<div class="md-content">
										@php
											$converter = new \League\CommonMark\GithubFlavoredMarkdownConverter([
												'html_input' => 'strip',
												'allow_unsafe_links' => false,
											]);
										@endphp
										{!! $converter->convert($block['data']['content']) !!}
									</div>
									@break
								@case('image_text')
									<div class="article-page__image-text article-page__image-text--{{ $block['data']['position'] }}">
										<figure class="article-page__image-text-img" style="width: {{ $block['data']['width'] ?? 300 }}px;">
											<img src="{{ \App\Models\Post::getMediaUrl($block['data']['url']) }}" alt="" />
										</figure>
										<div class="article-page__image-text-body">
											{!! $block['data']['text'] !!}
										</div>
									</div>
									@break
							@endswitch
						@endforeach
					</div>
					@else
					<h1 class="article-page__title">Статья не найдена</h1>
					@endisset

					<!-- Navigation -->
					<nav
						class="article-page__nav"
						id="articleNav"
						aria-label="Другие статьи"
					></nav>
				</div>
			</article>
		</main>

		<div id="footer"></div>
		<script src="/js/vendor/highlight.min.js"></script>
		<script>hljs.highlightAll();</script>
		<script defer src="/js/index.js" type="module"></script>
	</body>
</html>

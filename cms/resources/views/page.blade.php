<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="google-site-verification" content="3Ka3wmDabuO6R6kev1UuxPrWy5PKznexHg3VKEZfIGI" />
		<meta name="yandex-verification" content="102f35d468ef751b" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="/css/index.css" />
        <link rel="stylesheet" href="/css/vendor/highlight.min.css" />
        <title>{{ $page->seo_title ?? $page->title . ' — Михаил Божеслав' }}</title>
        <link rel="shortcut icon" href="/icons/favicon.ico" type="image/x-icon" />
        <meta name="description" content="{{ $page->seo_description ?? '' }}">
        <meta property="og:title" content="{{ $page->seo_title ?? $page->title }}">
        <meta property="og:description" content="{{ $page->seo_description ?? '' }}">
        <meta property="og:type" content="website">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-2M9GZV0JW3"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","G-2M9GZV0JW3");</script>
<script type="text/javascript">(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window,document,"script","https://mc.yandex.ru/metrika/tag.js?id=108285091","ym");ym(108285091,"init",{webvisor:true,clickmap:true,accurateTrackBounce:true,trackLinks:true});</script>
<noscript><div><img src="https://mc.yandex.ru/watch/108285091" style="position:absolute;left:-9999px;" alt="" /></div></noscript>

    </head>
    <body>
        @auth
        <x-admin-bar 
            :editUrl="'/admin/site-pages/' . $page->id . '/edit'"
            editLabel="Редактировать страницу"
        />
        @endauth

        <div id="header"></div>

        <main class="main">
            <section class="section" id="page">
                <div class="container page__container">
                    <h1 class="page__title">{{ $page->title }}</h1>
                    @if($page->excerpt)
                        <p>{{ $page->excerpt }}</p>
                    @endif
                    <div class="page__desc">
                        @if($page->content)
                            @foreach($page->content as $block)
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
                                            <pre><code class="language-{{ $block['data']['language'] }}">
                                                {{ $block['data']['code'] }}</code></pre>
                                        </div>
                                        @break
                                    @case('image')
                                        <figure>
                                            @php $media = \Awcodes\Curator\Models\Media::find($block['data']['url']); @endphp
                                            <img src="{{ $media?->url }}" alt="{{ $block['data']['caption'] ?? '' }}"
                                                style="@if(!empty($block['data']['width'])) width: {{ 
                                                    $block['data']['width'] }}px; @endif @if(!empty($block['data']['height'])) 
                                                    height: {{ $block['data']['height'] }}px; @endif" />
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
                                        @case('before_after')
                                        @php
                                            $before = \Awcodes\Curator\Models\Media::find($block['data']['before_url'])?->url;
                                            $after = \Awcodes\Curator\Models\Media::find($block['data']['after_url'])?->url;
                                            $h = $block['data']['height'] ?? 500;
                                            // Добавляем переменную ширины
                                            $w = $block['data']['width'] ?? '100%';
                                        @endphp

                                        @if($before && $after)
                                            <div class="before-after js-before-after" 
                                                style="--ba-height: {{ $h }}px; --ba-width: {{ $w ?? '100%' }};">
                                                
                                                <img src="{{ $after }}" class="before-after__img-after" alt="After">

                                                <div class="before-after__layer js-before-layer">
                                                    <img src="{{ $before }}" class="before-after__img-before js-before-img" alt="Before">
                                                </div>

                                                <div class="before-after__handle js-handle">
                                                    <div class="before-after__circle">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
                                                            <path d="M18 8L22 12L18 16M6 8L2 12L6 16"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @break
                                    @case('parser')
                                        <div class="parser-block"
                                            data-endpoint="{{ $block['data']['endpoint'] }}"
                                            data-param="{{ $block['data']['param'] }}"
                                            data-key="{{ $block['data']['data_key'] }}"
                                        >
                                            <div class="parser-block__form">
                                                <input
                                                    class="input parser-block__input"
                                                    type="text"
                                                    placeholder="{{ $block['data']['placeholder'] ?? 'Введите запрос...' }}"
                                                />
                                                <button class="btn btn--primary parser-block__btn">
                                                    {{ $block['data']['search_label'] ?? 'Найти' }}
                                                </button>
                                            </div>
                                            <div class="parser-block__status" style="display:none"></div>
                                            <div class="parser-block__error" style="display:none"></div>
                                            <div class="parser-block__table-wrap" style="display:none">
                                                <table class="parser-block__table">
                                                    <thead class="parser-block__thead"></thead>
                                                    <tbody class="parser-block__tbody"></tbody>
                                                </table>
                                            </div>
                                            <div class="parser-block__empty" style="display:none">
                                                Ничего не найдено. Попробуйте другой запрос.
                                            </div>
                                            <div class="parser-block__pagination" style="display:none"></div>
                                        </div>
                                        @break
                                

                                    @case('mjml_workspace')
                                        <div style="width: 100%; display: flex; flex-direction: column; align-items: center; padding: 40px 0;">
                                            
                                            @if(!empty($block['data']['project_title']))
                                                <h3 style="margin-bottom: 20px; font-weight: 600;">{{ $block['data']['project_title'] }}</h3>
                                            @endif

                                            <div style="width: 100%; max-width: 700px; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
                                                
                                                <div style="background: #f8fafc; padding: 12px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 6px;">
                                                    <span style="width: 8px; height: 8px; background: #ff5f56; border-radius: 50%;"></span>
                                                    <span style="width: 8px; height: 8px; background: #ffbd2e; border-radius: 50%;"></span>
                                                    <span style="width: 8px; height: 8px; background: #27c93f; border-radius: 50%;"></span>
                                                </div>

                                                {{-- 
                                                    ВАЖНО: Вместо $renderedHtml используем хелпер 
                                                    и данные из блока: $block['data']['html_content'] 
                                                --}}
                                                <iframe 
                                                    srcdoc="{{ compileMjml($block['data']['html_content']) }}" 
                                                    style="width: 100%; border: none; display: block; min-height: 400px;"
                                                    loading="lazy"
                                                    onload="this.style.height = '0px'; this.style.height = (this.contentWindow.document.documentElement.scrollHeight + 20) + 'px';"
                                                ></iframe>
                                            </div>
                                        </div>
                                        @break
                                @endswitch
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>

            @if(isset($related) && $related->count() > 0)
            <section class="section carousel-section">
                <div class="container">
                    <h2 class="carousel-section__title">Другие проекты</h2>
                    <div class="carousel-wrapper">
                        <button class="carousel-arrow carousel-arrow--prev" id="carouselPrev" aria-label="Назад">
                            <svg class="sprites badge__icon" aria-hidden="true">
                                <use href="/icons/sprites.svg#arrow-left"></use>
                            </svg>
                        </button>
                        <div class="carousel-track-wrap">
                            <div class="carousel-track" id="carouselTrack">
                                @foreach($related as $project)
                                <div class="carousel-card card">
                                    <div class="carousel-card__image-wrap">
                                        <img
                                            src="{{ $project->cover_url ?? '/images/projects/default.jpg' }}"
                                            alt="{{ $project->title }}"
                                            class="carousel-card__image"
                                            loading="lazy"
                                        />
                                    </div>
                                    <div class="card__body">
                                        <h3 class="carousel-card__title">{{ $project->title }}</h3>
                                        <div class="carousel-card__tags">
                                            @foreach($project->stack_tags ?? [] as $tag)
                                            <span class="badge badge__portfolio">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                        <div class="carousel-card__actions">
                                            @if($project->github_url)
                                            <a href="{{ $project->github_url }}" class="btn btn--outline btn--sm">
                                                <svg class="btn__icon" width="20" height="20" aria-hidden="true">
                                                    <use href="/icons/sprites.svg#github"></use>
                                                </svg>
                                                GitHub
                                            </a>
                                            @endif
                                            @if($project->link_url)
                                            <a href="{{ $project->link_url }}" class="btn btn--primary btn--sm">
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
                        <button class="carousel-arrow carousel-arrow--next" id="carouselNext" aria-label="Вперёд">
                            <svg class="sprites badge__icon" aria-hidden="true">
                                <use href="/icons/sprites.svg#arrow-right"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </section>
            @endif

        </main>

        <div id="footer"></div>
		<script src="/js/vendor/highlight.min.js"></script>
		<script>hljs.highlightAll();</script>
		<script defer src="/js/index.js" type="module"></script>
    </body>
</html>

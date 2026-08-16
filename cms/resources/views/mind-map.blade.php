<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $mindMap->title }} — Mind Map</title>
        <meta name="description" content="{{ $mindMap->excerpt }}">
        <link rel="stylesheet" href="/css/index.css" />
        @vite('resources/js/mind-maps.js')
    </head>
    <body>
        <div id="header"></div>

        <main class="main">
            <section class="section">
                <div class="container" style="max-width: 1200px;">
                    <nav class="article-page__breadcrumb" aria-label="Навигация">
                        <a href="{{ url('/' . app()->getLocale() . '/blog') }}" class="article-page__breadcrumb-link">← Блог</a>
                    </nav>

                    <h1 class="article-page__title">{{ $mindMap->title }}</h1>

                    @if($mindMap->excerpt)
                        <p class="of-post-mind-map__excerpt">{{ $mindMap->excerpt }}</p>
                    @endif

                    <div
                        class="of-post-mind-map__viewer"
                        data-mind-map-viewer
                        data-mind-map='@json($mindMap->decodedData())'
                    >
                        <div class="of-post-mind-map__viewer-toolbar">
                            <button type="button" class="of-post-mind-map__viewer-button" data-mind-map-viewer-action="toggle-theme">
                                Dark
                            </button>
                            <button type="button" class="of-post-mind-map__viewer-button" data-mind-map-viewer-action="scale-fit">
                                Fit
                            </button>
                            <button type="button" class="of-post-mind-map__viewer-button" data-mind-map-viewer-action="zoom-in">
                                +
                            </button>
                            <button type="button" class="of-post-mind-map__viewer-button" data-mind-map-viewer-action="zoom-out">
                                -
                            </button>
                            <button type="button" class="of-post-mind-map__viewer-button" data-mind-map-viewer-action="toggle-fullscreen">
                                Fullscreen
                            </button>
                        </div>

                        <div class="of-post-mind-map__canvas" data-mind-map-canvas style="height: 720px;"></div>
                    </div>
                </div>
            </section>
        </main>

        <div id="footer"></div>
        <script defer src="/js/index.js" type="module"></script>
    </body>
</html>

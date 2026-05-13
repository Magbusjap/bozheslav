<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/index.css" />
    <link rel="shortcut icon" href="/icons/favicon.ico" type="image/x-icon" />
    <title>404 — Страница не найдена</title>
</head>
<body>
    <div id="header"></div>

    <main class="main">
        <section class="error-page" id="error-page" data-path="{{ request()->path() }}">
            <div class="terminal">
                <div class="terminal__titlebar">
                    <div class="terminal__dot terminal__dot--red"></div>
                    <div class="terminal__dot terminal__dot--yellow"></div>
                    <div class="terminal__dot terminal__dot--green"></div>
                    <span class="terminal__title">bash — bozheslav.com</span>
                </div>
                <div class="terminal__body" id="terminal-output">
                    <span class="terminal__cursor" id="cursor"></span>
                </div>
                <div class="terminal__footer">
                    <a href="/" class="terminal__back" id="back-btn" style="display:none;">
                        $ cd /На главную →
                    </a>
                </div>
            </div>
        </section>
    </main>

    <div id="footer"></div>
    <script src="/js/index.js" type="module"></script>
</body>
</html>


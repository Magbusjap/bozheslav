export function init404() {
    if (!document.getElementById('terminal-output')) return;

    const path = document.getElementById('error-page')?.dataset.path || '';
    const locale = window.SITE_I18N?.locale || window.location.pathname.match(/^\/(ru|en|sr)(?=\/|$)/)?.[1] || 'ru';
    const notFound = window.SITE_I18N?.js?.notFound || fallbackNotFound(locale);

    const lines = [
        { text: '$ curl -I ' + window.location.origin + '/' + path, cls: '' },
        { text: '', cls: 'terminal__line--empty' },
        { text: 'HTTP/2 404 Not Found', cls: 'terminal__line--error' },
        { text: notFound.error, cls: 'terminal__line--warning' },
        { text: '', cls: 'terminal__line--empty' },
        { text: notFound.message, cls: 'terminal__line--comment' },
        { text: notFound.back, cls: 'terminal__line--comment' },
    ];

    const output = document.getElementById('terminal-output');
    const cursor = document.getElementById('cursor');
    const backBtn = document.getElementById('back-btn');

    let lineIndex = 0;
    let charIndex = 0;
    let currentDiv = null;

    function type() {
        if (lineIndex >= lines.length) {
            output.appendChild(cursor);
            backBtn.style.display = 'inline-flex';
            return;
        }

        const line = lines[lineIndex];

        if (charIndex === 0) {
            currentDiv = document.createElement('div');
            currentDiv.className = 'terminal__line ' + (line.cls || '');
            output.insertBefore(currentDiv, cursor);
        }

        if (line.cls === 'terminal__line--empty') {
            lineIndex++;
            charIndex = 0;
            setTimeout(type, 100);
            return;
        }

        if (charIndex < line.text.length) {
            currentDiv.textContent = line.text.substring(0, charIndex + 1);
            charIndex++;
            setTimeout(type, 28);
        } else {
            lineIndex++;
            charIndex = 0;
            setTimeout(type, 220);
        }
    }

    setTimeout(type, 600);
}

function fallbackNotFound(locale) {
    return {
        en: {
            error: 'X-Error: Route not resolved',
            message: '# Page not found or not published yet.',
            back: '# Try going back to the home page.',
        },
        sr: {
            error: 'X-Error: Ruta nije pronađena',
            message: '# Stranica nije pronađena ili još nije objavljena.',
            back: '# Predlažem povratak na početnu.',
        },
        ru: {
            error: 'X-Error: Route not resolved',
            message: '# Страница не найдена или ещё не опубликована.',
            back: '# Предлагаю вернуться на главную.',
        },
    }[locale] || fallbackNotFound('ru');
}

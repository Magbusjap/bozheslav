export function init404() {
    if (!document.getElementById('terminal-output')) return;

    const path = document.getElementById('error-page')?.dataset.path || '';

    const lines = [
        { text: '$ curl -I https://bozheslav.ru/' + path, cls: '' },
        { text: '', cls: 'terminal__line--empty' },
        { text: 'HTTP/2 404 Not Found', cls: 'terminal__line--error' },
        { text: 'X-Error: Route not resolved', cls: 'terminal__line--warning' },
        { text: '', cls: 'terminal__line--empty' },
        { text: '# Страница не найдена или ещё не опубликована.', cls: 'terminal__line--comment' },
        { text: '# Предлагаю вернуться на главную.', cls: 'terminal__line--comment' },
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
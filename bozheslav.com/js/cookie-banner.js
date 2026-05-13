export function initCookieBanner() {
    if (localStorage.getItem('cookies_accepted')) return;

    const banner = document.createElement('div');
    banner.id = 'cookie-banner';
    banner.innerHTML = `
        <div class="cookie-banner__text">
            Для улучшения работы сайта используются cookie файлы. Пожалуйста, прочитте: 
            <a href="/privacy" class="cookie-banner__link">Политика конфиденциальности</a>
        </div>
        <button class="cookie-banner__btn btn btn--primary btn--sm" id="cookieAccept">
            Принять
        </button>
    `;
    document.body.appendChild(banner);

    document.getElementById('cookieAccept').addEventListener('click', () => {
        localStorage.setItem('cookies_accepted', '1');
        banner.remove();
    });
}
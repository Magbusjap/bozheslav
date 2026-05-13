export function initCookieBanner() {
    if (localStorage.getItem('cookies_accepted')) return;

    const cookie = window.SITE_I18N?.client?.cookie || {};
    const text = cookie.text || 'Для улучшения работы сайта используются cookie файлы. Пожалуйста, прочитте:';
    const link = cookie.link || 'Политика конфиденциальности';
    const accept = cookie.accept || 'Принять';
    const privacyUrl = getLocalizedPath('/privacy');

    const banner = document.createElement('div');
    banner.id = 'cookie-banner';
    banner.innerHTML = `
        <div class="cookie-banner__text">
            ${text}
            <a href="${privacyUrl}" class="cookie-banner__link">${link}</a>
        </div>
        <button class="cookie-banner__btn btn btn--primary btn--sm" id="cookieAccept">
            ${accept}
        </button>
    `;
    document.body.appendChild(banner);

    document.getElementById('cookieAccept').addEventListener('click', () => {
        localStorage.setItem('cookies_accepted', '1');
        banner.remove();
    });
}

function getLocalizedPath(path) {
    const locale = window.SITE_I18N?.locale;
    return ['ru', 'en', 'sr'].includes(locale) ? `/${locale}${path}` : path;
}

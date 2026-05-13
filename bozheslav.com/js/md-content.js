export function initMdCodeLang() {
    document.querySelectorAll('.md-content pre code').forEach(code => {
        const lang = [...code.classList]
            .find(c => c.startsWith('language-'))
            ?.replace('language-', '');

        if (!lang) return;

        const pre = code.parentElement;
        const wrapper = document.createElement('div');
        wrapper.className = 'article-page__code-wrapper';
        pre.parentNode.insertBefore(wrapper, pre);
        wrapper.appendChild(pre);

        const span = document.createElement('span');
        span.className = 'article-page__code-lang';
        span.textContent = lang;
        wrapper.appendChild(span);
    });
}
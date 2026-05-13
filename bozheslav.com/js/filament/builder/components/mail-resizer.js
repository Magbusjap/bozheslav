export function initMailResizer() {
    const iframes = document.querySelectorAll('iframe[srcdoc]');
    
    iframes.forEach(iframe => {
        const updateHeight = () => {
            if (iframe.contentWindow && iframe.contentWindow.document.body) {
                // Измеряем реальный контент внутри
                const height = iframe.contentWindow.document.body.scrollHeight;
                iframe.style.height = height + 'px';
            }
        };

        // Подписываемся на загрузку каждого iframe
        iframe.onload = () => {
            setTimeout(updateHeight, 150); // Даем время на рендер стилей
        };

        // На случай, если он уже загрузился
        updateHeight();
    });
}
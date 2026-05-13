export function initArticleTags() {
    if (document.getElementById("blogGrid")) return;
    document.querySelectorAll(".badge__blog").forEach((tag) => {
        tag.addEventListener("click", () => {
            const filter = tag.dataset.filter;
            if (filter) {
                window.location.href = `/blog?category=${filter}`;
            }
        });
    });
}

export function initArticle() {
    // Контент статьи рендерится через Laravel/Blade
}
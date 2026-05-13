export function initCarousel() {
    const track = document.getElementById("carouselTrack");
    if (!track) return;

    const prevBtn = document.getElementById("carouselPrev");
    const nextBtn = document.getElementById("carouselNext");
    const cards = Array.from(track.querySelectorAll(".carousel-card"));
    const total = cards.length;
    let current = 0;

    const visibleCount = () => {
        if (window.innerWidth <= 480) return 1;
        if (window.innerWidth <= 768) return 2;
        return 3;
    };

    function getCardWidth() {
        const wrap = track.parentElement;
        const visible = visibleCount();
        const gap = 24; // 1.5rem
        return (wrap.offsetWidth - gap * (visible - 1)) / visible;
    }

    function update() {
        const visible = visibleCount();
        const maxIndex = Math.max(0, total - visible);
        if (current > maxIndex) current = 0;
        if (current < 0) current = maxIndex;

        const cardWidth = getCardWidth();
        const gap = 24;
        track.style.transform = `translateX(-${current * (cardWidth + gap)}px)`;
    }

    prevBtn.addEventListener("click", () => {
        const visible = visibleCount();
        const maxIndex = Math.max(0, total - visible);
        current = current <= 0 ? maxIndex : current - 1;
        update();
    });

    nextBtn.addEventListener("click", () => {
        const visible = visibleCount();
        const maxIndex = Math.max(0, total - visible);
        current = current >= maxIndex ? 0 : current + 1;
        update();
    });

    window.addEventListener("resize", update);
    update();
}
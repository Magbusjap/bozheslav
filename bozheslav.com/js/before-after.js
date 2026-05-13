export function initBeforeAfter() {
    const containers = document.querySelectorAll('.js-before-after');

    containers.forEach(container => {
        const beforeLayer = container.querySelector('.js-before-layer');
        const beforeImg = container.querySelector('.js-before-img');
        const handle = container.querySelector('.js-handle');

        if (!beforeLayer || !beforeImg || !handle) return;

        const updatePosition = (e) => {
            const rect = container.getBoundingClientRect();
            const x = (e.pageX || (e.touches ? e.touches[0].pageX : 0)) - rect.left;
            let percent = (x / rect.width) * 100;

            if (percent < 0) percent = 0;
            if (percent > 100) percent = 100;

            beforeLayer.style.width = `${percent}%`;
            handle.style.left = `${percent}%`;
            beforeImg.style.width = `${rect.width}px`;
        };

        let isMoving = false;

        container.addEventListener('mousedown', () => isMoving = true);
        window.addEventListener('mouseup', () => isMoving = false);
        container.addEventListener('mousemove', (e) => isMoving && updatePosition(e));

        container.addEventListener('touchstart', () => isMoving = true);
        window.addEventListener('touchend', () => isMoving = false);
        container.addEventListener('touchmove', (e) => isMoving && updatePosition(e));

        const setImgWidth = () => {
            beforeImg.style.width = `${container.offsetWidth}px`;
        };
        window.addEventListener('resize', setImgWidth);
        setImgWidth();
    });
}
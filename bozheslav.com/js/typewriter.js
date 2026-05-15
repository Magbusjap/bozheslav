export function initTyped() {
	const element = document.querySelector(".hero__typed");

	if (!element) return;

	const translatedPhrases = window.SITE_I18N?.js?.typewriter?.phrases;
	const phrases = Array.isArray(translatedPhrases) && translatedPhrases.length
		? translatedPhrases
		: [
			"Привет...",
			"Ищешь разработчика?",
			"Нужен сайт под ключ?",
			"Автоматизировать процессы?",
			"Тогда ты по адресу.",
		];

	const typed = {
		phrases,
		element,
		index: 0,
		charIndex: 0,
		isDeleting: false,

		type() {
			const current = this.phrases[this.index];

			if (this.isDeleting) {
				this.element.textContent = current.slice(0, this.charIndex--);
			} else {
				this.element.textContent = current.slice(0, this.charIndex++);
			}

			let speed = this.isDeleting ? 50 : 100;

			if (!this.isDeleting && this.charIndex === current.length + 1) {
				speed = 1500; // pause after typing
				this.isDeleting = true;
			} else if (this.isDeleting && this.charIndex === 0) {
				this.isDeleting = false;
				this.index = (this.index + 1) % this.phrases.length;
				speed = 400; // pause before next phrase
			}

			setTimeout(() => this.type(), speed);
		},
	};

	typed.type();
}

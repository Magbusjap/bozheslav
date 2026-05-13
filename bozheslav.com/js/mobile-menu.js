const BREAKPOINT = 768;

export function initMenu(headerFixedInstance = null) {
	const mobileMenuBtn = document.getElementById("mobileMenuBtn");
	if (!mobileMenuBtn) return;
	const mobileMenu = document.getElementById("mobileMenu");
	const menuIcon = document.getElementById("menuIcon");
	const closeIcon = document.getElementById("closeIcon");
	const main = document.querySelector("main");

	let isMobile = window.innerWidth < BREAKPOINT;
	let touchStartX = 0;

	/* ---- open / close ---- */

	function openMenu() {
		mobileMenu.hidden = false;
		menuIcon.style.display = "none";
		closeIcon.style.display = "block";

		mobileMenuBtn.setAttribute("aria-expanded", "true");
		mobileMenuBtn.setAttribute("aria-label", "Закрыть меню");

		if (main) main.style.pointerEvents = "none";

		if (headerFixedInstance) headerFixedInstance.removeFixedClass();
	}

	function closeMenu() {
		mobileMenu.hidden = true;
		menuIcon.style.display = "block";
		closeIcon.style.display = "none";

		mobileMenuBtn.setAttribute("aria-expanded", "false");
		mobileMenuBtn.setAttribute("aria-label", "Открыть меню");

		if (main) main.style.pointerEvents = "";

		if (headerFixedInstance) headerFixedInstance.updateFixedClass();
	}

	function isOpen() {
		return !mobileMenu.hidden;
	}

	/* ---- event handlers ---- */

	function onBurgerClick() {
		isOpen() ? closeMenu() : openMenu();
	}

	function onBodyClick(e) {
		if (!isOpen()) return;
		const outsideMenu = !e.target.closest("#mobileMenu");
		const outsideBtn = !e.target.closest("#mobileMenuBtn");
		if (outsideMenu && outsideBtn) closeMenu();
	}

	function onTouchStart(e) {
		if (!isOpen()) return;
		touchStartX = e.changedTouches[0].screenX;
	}

	function onTouchEnd(e) {
		if (!isOpen()) return;
		const swipe = e.changedTouches[0].screenX - touchStartX;
		if (swipe < -70) closeMenu(); // swipe left to close
	}

	/* ---- add / remove events ---- */

	function addEvents() {
		mobileMenuBtn.addEventListener("click", onBurgerClick);
		document.addEventListener("click", onBodyClick);
		document.addEventListener("touchstart", onTouchStart);
		document.addEventListener("touchend", onTouchEnd);
		mobileMenu.querySelectorAll(".nav__link").forEach((link) => {
			link.addEventListener("click", closeMenu);
		});
	}

	function removeEvents() {
		mobileMenuBtn.removeEventListener("click", onBurgerClick);
		document.removeEventListener("click", onBodyClick);
		document.removeEventListener("touchstart", onTouchStart);
		document.removeEventListener("touchend", onTouchEnd);
		closeMenu();
	}

	/* ---- resize ---- */

	function onResize() {
		const nowMobile = window.innerWidth < BREAKPOINT;
		if (isMobile === nowMobile) return;
		isMobile = nowMobile;
		isMobile ? addEvents() : removeEvents();
	}

	/* ---- init ---- */

	mobileMenuBtn.setAttribute("aria-expanded", "false");
	mobileMenuBtn.setAttribute("aria-label", "Открыть меню");

	if (isMobile) addEvents();
	window.addEventListener("resize", onResize);
}

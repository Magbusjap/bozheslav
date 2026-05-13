const THEME_KEY = "portfolio-theme";
const html = document.documentElement;

function getPreferredTheme() {
	const saved = localStorage.getItem(THEME_KEY);
	if (saved) return saved;
	return window.matchMedia("(prefers-color-scheme: dark)").matches
		? "dark"
		: "light";
}

function applyTheme(theme) {
	if (theme === "dark") {
		html.classList.add("dark");
	} else {
		html.classList.remove("dark");
	}
	localStorage.setItem(THEME_KEY, theme);
}

function toggleTheme() {
	const isDark = html.classList.contains("dark");
	applyTheme(isDark ? "light" : "dark");
}

export function initTheme() {
	applyTheme(getPreferredTheme());
	document.querySelectorAll(".theme-toggle").forEach((btn) => {
		btn.addEventListener("click", toggleTheme);
	});
}

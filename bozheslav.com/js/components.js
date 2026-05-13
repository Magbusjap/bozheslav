let languageSwitcherListenersBound = false;

export async function loadComponent(id, path) {
	try {
		const res = await fetch(path);
		if (!res.ok) throw new Error(`Failed to load ${path}: ${res.status}`);
		const html = translateHtml(await res.text());
		const el = document.getElementById(id);
		if (!el) throw new Error(`Element #${id} not found`);
		el.innerHTML = html;
		localizeLinks(el);
		initLanguageSwitchers(el);
	} catch (err) {
		console.error("[loadComponent]", err);
	}
}

export function localizeLinks(root = document) {
	const locale = window.SITE_I18N?.locale;
	if (!["ru", "en", "sr"].includes(locale)) return;

	root.querySelectorAll?.("a[href]").forEach((link) => {
		if (link.matches("[data-locale-option]")) return;

		const href = link.getAttribute("href");
		if (!shouldLocalizeHref(href)) return;

		const url = new URL(href, window.location.origin);
		const cleanPath = url.pathname.replace(/^\/(ru|en|sr)(?=\/|$)/, "");
		url.pathname = `/${locale}${cleanPath === "/" ? "" : cleanPath}`;
		link.setAttribute("href", `${url.pathname}${url.search}${url.hash}`);
	});
}

export function initLanguageSwitchers(root = document) {
	const currentLocale = getCurrentLocale();

	root.querySelectorAll?.("[data-language-switcher]").forEach((switcher) => {
		const toggle = switcher.querySelector("[data-language-toggle]");
		const menu = switcher.querySelector("[data-language-menu]");
		const currentLabel = switcher.querySelector("[data-current-locale]");
		if (!toggle || !menu) return;

		if (currentLabel) {
			currentLabel.textContent = currentLocale.toUpperCase();
		}

		switcher.querySelectorAll("[data-locale-option]").forEach((option) => {
			const locale = option.dataset.localeOption;
			option.href = buildLocaleUrl(locale);
			option.toggleAttribute("aria-current", locale === currentLocale);
			option.hidden = locale === currentLocale;
		});

		toggle.addEventListener("click", (event) => {
			event.stopPropagation();
			const isOpen = !menu.hidden;
			closeLanguageMenus();
			menu.hidden = isOpen;
			toggle.setAttribute("aria-expanded", String(!isOpen));
		});
	});

	if (!languageSwitcherListenersBound) {
		document.addEventListener("click", closeLanguageMenus);
		document.addEventListener("keydown", closeLanguageMenusOnEscape);
		languageSwitcherListenersBound = true;
	}
}

function translateHtml(html) {
	const replacements = window.SITE_I18N?.replacements;
	if (!replacements || typeof replacements !== "object") return html;

	return Object.entries(replacements).reduce((result, [source, target]) => {
		return result.split(source).join(target);
	}, html);
}

function shouldLocalizeHref(href) {
	if (!href || href.startsWith("#")) return false;
	if (/^(https?:)?\/\//.test(href)) return false;
	if (/^(mailto|tel):/.test(href)) return false;

	const url = new URL(href, window.location.origin);
	if (url.origin !== window.location.origin) return false;

	return ![
		"/admin",
		"/api",
		"/build",
		"/css",
		"/icons",
		"/images",
		"/js",
		"/livewire",
		"/storage",
		"/vendor",
	].some((prefix) => url.pathname === prefix || url.pathname.startsWith(`${prefix}/`));
}

function closeLanguageMenus() {
	document.querySelectorAll("[data-language-switcher]").forEach((switcher) => {
		switcher.querySelector("[data-language-menu]")?.setAttribute("hidden", "");
		switcher
			.querySelector("[data-language-toggle]")
			?.setAttribute("aria-expanded", "false");
	});
}

function closeLanguageMenusOnEscape(event) {
	if (event.key === "Escape") {
		closeLanguageMenus();
	}
}

function getCurrentLocale() {
	const configuredLocale = window.SITE_I18N?.locale;
	if (["ru", "en", "sr"].includes(configuredLocale)) return configuredLocale;

	const [, localeSegment] = window.location.pathname.match(/^\/(ru|en|sr)(?=\/|$)/) || [];
	return localeSegment || "ru";
}

function buildLocaleUrl(locale) {
	if (!["ru", "en", "sr"].includes(locale)) return window.location.pathname;

	const url = new URL(window.location.href);
	const cleanPath = url.pathname.replace(/^\/(ru|en|sr)(?=\/|$)/, "") || "/";
	url.pathname = `/${locale}${cleanPath === "/" ? "" : cleanPath}`;

	return `${url.pathname}${url.search}${url.hash}`;
}

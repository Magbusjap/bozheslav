export async function loadComponent(id, path) {
	try {
		const res = await fetch(path);
		if (!res.ok) throw new Error(`Failed to load ${path}: ${res.status}`);
		const html = translateHtml(await res.text());
		const el = document.getElementById(id);
		if (!el) throw new Error(`Element #${id} not found`);
		el.innerHTML = html;
		localizeLinks(el);
	} catch (err) {
		console.error("[loadComponent]", err);
	}
}

export function localizeLinks(root = document) {
	const locale = window.SITE_I18N?.locale;
	if (!["ru", "en", "sr"].includes(locale)) return;

	root.querySelectorAll?.("a[href]").forEach((link) => {
		const href = link.getAttribute("href");
		if (!shouldLocalizeHref(href)) return;

		const url = new URL(href, window.location.origin);
		const cleanPath = url.pathname.replace(/^\/(ru|en|sr)(?=\/|$)/, "");
		url.pathname = `/${locale}${cleanPath === "/" ? "" : cleanPath}`;
		link.setAttribute("href", `${url.pathname}${url.search}${url.hash}`);
	});
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

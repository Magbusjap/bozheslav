export async function loadComponent(id, path) {
	try {
		const res = await fetch(path);
		if (!res.ok) throw new Error(`Failed to load ${path}: ${res.status}`);
		const html = await res.text();
		const el = document.getElementById(id);
		if (!el) throw new Error(`Element #${id} not found`);
		el.innerHTML = html;
	} catch (err) {
		console.error("[loadComponent]", err);
	}
}

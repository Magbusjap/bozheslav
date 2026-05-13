const INITIAL_COUNT = 5;
const LOAD_MORE_COUNT = 3;

function getEntries() {
	return Array.from(document.querySelectorAll(".exp-entry"));
}

function renderEntries(visibleCount) {
	const entries = getEntries();

	entries.forEach((entry, i) => {
		if (i < visibleCount) {
			entry.classList.remove("exp-entry--hidden");
		} else {
			entry.classList.add("exp-entry--hidden");
		}
	});

	const btn = document.getElementById("expLoadMore");
	if (!btn) return;

	const remaining = entries.length - visibleCount;

	if (remaining <= 0) {
		btn.style.display = "none";
	} else {
		btn.style.display = "inline-flex";
		btn.textContent = `Показать ещё...`;
	}
}

export function initExperience() {
	if (!document.querySelector(".exp-entry")) return;

	let visibleCount = INITIAL_COUNT;
	renderEntries(visibleCount);

	const btn = document.getElementById("expLoadMore");
	if (!btn) return;

	btn.addEventListener("click", () => {
		visibleCount += LOAD_MORE_COUNT;
		renderEntries(visibleCount);
	});
}

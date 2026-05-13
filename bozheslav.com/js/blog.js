const POSTS_PER_PAGE = 6;
let currentPage = 1;
let currentFilter = "all";

function getAllCards() {
	return Array.from(document.querySelectorAll(".blog-card"));
}

function getFilteredCards() {
	return getAllCards().filter((card) => {
		if (currentFilter === "all") return true;
		return card.dataset.category === currentFilter;
	});
}

function renderPage() {
	const filtered = getFilteredCards();
	const total = Math.ceil(filtered.length / POSTS_PER_PAGE);
	const start = (currentPage - 1) * POSTS_PER_PAGE;
	const end = start + POSTS_PER_PAGE;

	getAllCards().forEach((card) => card.classList.add("blog-card--hidden"));
	filtered
		.slice(start, end)
		.forEach((card) => card.classList.remove("blog-card--hidden"));

	renderPagination(total);

	document.getElementById("prevPage").disabled = currentPage === 1;
	document.getElementById("nextPage").disabled =
		currentPage === total || total === 0;
}

function renderPagination(total) {
	const container = document.getElementById("paginationPages");
	container.innerHTML = "";

	for (let i = 1; i <= total; i++) {
		const btn = document.createElement("button");
		btn.className = `blog-pagination__page${i === currentPage ? " active" : ""}`;
		btn.textContent = i;
		btn.onclick = () => goToPage(i);
		container.appendChild(btn);
	}
}

function goToPage(page) {
	currentPage = page;
	renderPage();
	window.scrollTo({ top: 0, behavior: "smooth" });
}

export function prevPage() {
	if (currentPage > 1) goToPage(currentPage - 1);
}

export function nextPage() {
	const total = Math.ceil(getFilteredCards().length / POSTS_PER_PAGE);
	if (currentPage < total) goToPage(currentPage + 1);
}

function initFilters() {
	document.querySelectorAll(".blog-filter").forEach((btn) => {
		btn.addEventListener("click", () => {
			document
				.querySelectorAll(".blog-filter")
				.forEach((b) => b.classList.remove("active"));
			btn.classList.add("active");
			currentFilter = btn.dataset.filter;
			currentPage = 1;
			renderPage();
		});
	});
}

function initTags() {
	document.querySelectorAll(".badge__blog").forEach((tag) => {
		tag.addEventListener("click", () => {
			const filter = tag.dataset.filter; // or tag.textContent.trim()

			// Remove active class from filter buttons
			document
				.querySelectorAll(".blog-filter")
				.forEach((b) => b.classList.remove("active"));

			// Set active class on selected button
			const matchBtn = [...document.querySelectorAll(".blog-filter")].find(
				(b) => b.dataset.filter === filter,
			);
			if (matchBtn) matchBtn.classList.add("active");

			currentFilter = filter;
			currentPage = 1;
			renderPage();
		});
	});
}

export function initBlog() {
    if (!document.getElementById("blogGrid")) return;
    
    const params = new URLSearchParams(window.location.search);
    const categoryParam = params.get('category');
    if (categoryParam) {
        currentFilter = categoryParam;
    }
    
    initFilters();
    initTags();
    renderPage();

	// Enable the filter button 
    if (categoryParam) {
        const matchBtn = [...document.querySelectorAll(".blog-filter")].find(
            (b) => b.dataset.filter === categoryParam
        );
        if (matchBtn) {
            document.querySelectorAll(".blog-filter").forEach((b) => b.classList.remove("active"));
            matchBtn.classList.add("active");
        }
    }
}


window.prevPage = prevPage;
window.nextPage = nextPage;

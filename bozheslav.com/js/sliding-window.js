const VISIBLE = 5;
let currentProject = 0;
let windowStart = 0;

function getTotalProjects() {
	return document.querySelectorAll(".project-item").length;
}

function updateList() {
	const total = getTotalProjects();
	document.querySelectorAll(".project-item").forEach((item, i) => {
		const inWindow = i >= windowStart && i < windowStart + VISIBLE;
		item.style.display = inWindow ? "block" : "none";
		item.classList.toggle("active", i === currentProject);
	});
}

export function selectProject(index) {
    const total = getTotalProjects();
    currentProject = ((index % total) + total) % total; // цикличность

    // Shift window if needed
    if (currentProject >= windowStart + VISIBLE) {
        windowStart = currentProject - VISIBLE + 1;
    } else if (currentProject < windowStart) {
        windowStart = currentProject;
    }

    // Wrap window
    if (windowStart < 0) windowStart = 0;
    if (windowStart + VISIBLE > total) windowStart = Math.max(0, total - VISIBLE);

    updateList();

    document.querySelectorAll(".project-detail").forEach((detail, i) => {
        detail.classList.toggle("active", i === currentProject);
    });

    document.getElementById("projectsCounter").textContent =
        `${currentProject + 1} / ${total}`;

    // The buttons are always active
    document.getElementById("prevBtn").disabled = false;
    document.getElementById("nextBtn").disabled = false;
}

export function prevProject() {
    const total = getTotalProjects();
    selectProject((currentProject - 1 + total) % total);
}

export function nextProject() {
    const total = getTotalProjects();
    selectProject((currentProject + 1) % total);
}

export function initProjects() {
    if (!document.getElementById("projectsCounter")) return;
    if (document.querySelector(".portfolio-filter")) return; // не главная
    selectProject(0);
}

if (!document.querySelector(".portfolio-filter")) {
    window.selectProject = selectProject;
    window.prevProject = prevProject;
    window.nextProject = nextProject;
}
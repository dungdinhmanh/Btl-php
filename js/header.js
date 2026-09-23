document.addEventListener("DOMContentLoaded", () => {
	const header = document.querySelector(".header");
	if (!header) return;

	const syncHeaderState = () => {
		const stuckAt = parseFloat(getComputedStyle(header).top) || 0;
		header.classList.toggle("header-fixed", header.getBoundingClientRect().top <= stuckAt + 0.5);
	};

	window.addEventListener("scroll", syncHeaderState, { passive: true });
	window.addEventListener("resize", syncHeaderState, { passive: true });
	syncHeaderState();
});
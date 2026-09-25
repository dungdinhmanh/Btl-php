document.addEventListener("DOMContentLoaded", () => {
	const header = document.querySelector(".header");
	if (!header) return;
	const initialOffsetTop = header.getBoundingClientRect().top + window.scrollY;

	const syncHeaderState = () => {
		header.classList.toggle("header-fixed", window.scrollY > 700);
	};

	window.addEventListener("scroll", syncHeaderState, { passive: true });
	window.addEventListener("resize", syncHeaderState, { passive: true });
	syncHeaderState();
});
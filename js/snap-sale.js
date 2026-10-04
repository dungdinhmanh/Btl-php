(() => {
	const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

	document.querySelectorAll("[data-snap-carousel]").forEach((root) => {
		const track = root.querySelector("[data-snap-track]");
		if (!track || track.children.length === 0) return;

		const step = () =>
			track.children[0].getBoundingClientRect().width + (parseFloat(getComputedStyle(track).columnGap) || 0);
		const atStart = () => track.scrollLeft <= 2;
		const atEnd = () => track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;

		const go = (direction) => {
			if (direction > 0) {
				if (atEnd()) track.scrollTo({ left: 0 });
				else track.scrollBy({ left: step() });
			} else if (atStart()) {
				track.scrollTo({ left: track.scrollWidth });
			} else {
				track.scrollBy({ left: -step() });
			}
		};

		root.querySelectorAll("[data-snap-nav]").forEach((button) => {
			button.addEventListener("click", () => go(button.dataset.snapNav === "next" ? 1 : -1));
		});

		const syncStatic = () => root.classList.toggle("is-static", track.scrollWidth <= track.clientWidth + 1);
		if ("ResizeObserver" in window) new ResizeObserver(syncStatic).observe(track);
		window.addEventListener("resize", syncStatic);
		syncStatic();

        const delay = Number(root.dataset.autoplay) || 0;
		if (delay > 0 && !reduceMotion) {
			let timer = null;
			const stop = () => clearInterval(timer);
			const start = () => {
				stop();
				timer = setInterval(() => {
					if (!document.hidden && !root.classList.contains("is-static")) go(1);
				}, delay);
			};
			["mouseenter", "focusin", "touchstart"].forEach((name) => root.addEventListener(name, stop, { passive: true }));
			["mouseleave", "focusout"].forEach((name) => root.addEventListener(name, start));
			start();
		}
	});
})();
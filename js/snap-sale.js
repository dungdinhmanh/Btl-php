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

		let timer = null;
		const delay = Number(root.dataset.autoplay) || 0;
		const stopAutoplay = () => clearInterval(timer);
		const startAutoplay = () => {
			if (delay <= 0 || reduceMotion) return;
			stopAutoplay();
			timer = setInterval(() => {
				if (!document.hidden && !root.classList.contains("is-static")) go(1);
			}, delay);
		};

		let pointerId = null;
		let startX = 0;
		let startScrollLeft = 0;
		let dragged = false;

		track.addEventListener("pointerdown", (event) => {
			if (event.pointerType === "mouse" && event.button !== 0) return;
			pointerId = event.pointerId;
			startX = event.clientX;
			startScrollLeft = track.scrollLeft;
			dragged = false;
			track.setPointerCapture?.(pointerId);
			track.classList.add("is-dragging");
			stopAutoplay();
		});

		track.addEventListener("pointermove", (event) => {
			if (event.pointerId !== pointerId) return;
			const delta = event.clientX - startX;
			if (Math.abs(delta) > 6) dragged = true;
			if (dragged) {
				track.scrollLeft = startScrollLeft - delta;
				event.preventDefault();
			}
		});

		const endDrag = (event) => {
			if (event.pointerId !== pointerId) return;
			const delta = event.clientX - startX;
			track.classList.remove("is-dragging");
			track.releasePointerCapture?.(pointerId);
			pointerId = null;
			if (Math.abs(delta) >= 24) {
				track.scrollTo({ left: startScrollLeft - delta, behavior: reduceMotion ? "auto" : "smooth" });
			}
			startAutoplay();
		};

		track.addEventListener("pointerup", endDrag);
		track.addEventListener("pointercancel", endDrag);
		track.addEventListener("click", (event) => {
			if (!dragged) return;
			event.preventDefault();
			event.stopPropagation();
			dragged = false;
		}, true);

		root.querySelectorAll("[data-snap-nav]").forEach((button) => {
			button.addEventListener("click", () => go(button.dataset.snapNav === "next" ? 1 : -1));
		});

		const syncStatic = () => root.classList.toggle("is-static", track.scrollWidth <= track.clientWidth + 1);
		if ("ResizeObserver" in window) new ResizeObserver(syncStatic).observe(track);
		window.addEventListener("resize", syncStatic);
		syncStatic();

		root.addEventListener("mouseenter", stopAutoplay);
		root.addEventListener("focusin", stopAutoplay);
		root.addEventListener("touchstart", stopAutoplay, { passive: true });
		root.addEventListener("mouseleave", startAutoplay);
		root.addEventListener("focusout", startAutoplay);
		root.addEventListener("dragstart", (event) => event.preventDefault());
		startAutoplay();
	});
})();
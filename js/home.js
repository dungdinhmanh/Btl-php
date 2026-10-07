document.addEventListener("DOMContentLoaded", async () => {
	const container = document.querySelector("#featured-products-list");
	if (!container) return;

	TNC.showLoading(container, "Đang tải sản phẩm ...");

	try {
		const products = await TNC.api.featured(4);
		container.innerHTML = products.length
			? products
					.map((product) =>
						TNC.productCard(product, {
							columnClass: "col-sm-6 col-lg-3",
							tag: "Bán chạy",
							imageHeight: 160,
						}),
					)
					.join("")
			: TNC.emptyState({ icon: "bi-box-seam", title: "Chưa có sản phẩm nổi bật" });
	} catch (error) {
		TNC.renderError(container, error);
	}
});

document.addEventListener("DOMContentLoaded", () => {
	if (typeof bootstrap === "undefined") return;

	const dragThreshold = 40;
	const carouselSelector = '.carousel[data-bs-ride="carousel"]';
	let startX = null;
	let dragged = false;

	document.addEventListener("pointerdown", (event) => {
		if (event.pointerType === "mouse" && event.button !== 0) return;
		if (!event.target.closest(carouselSelector)) return;
		startX = event.clientX;
		dragged = false;
	});

	document.addEventListener("pointermove", (event) => {
		if (startX === null) return;
		if (Math.abs(event.clientX - startX) > dragThreshold) dragged = true;
	});

	document.addEventListener("pointerup", (event) => {
		if (startX === null) return;
		const carousel = event.target.closest(carouselSelector);
		const direction = event.clientX - startX;
		const moved = dragged;
		startX = null;
		if (!carousel || !moved) return;
		const instance = bootstrap.Carousel.getOrCreateInstance(carousel);
		if (direction < 0) {
			instance.next();
		} else {
			instance.prev();
		}
	});

	document.addEventListener(
		"click",
		(event) => {
			if (!dragged) return;
			event.preventDefault();
			event.stopPropagation();
		},
		true,
	);
});

document.addEventListener("DOMContentLoaded", async () => {
	const lists = [...document.querySelectorAll("[data-product-list]")];
	if (!lists.length) return;

	await Promise.all(
		lists.map(async (list) => {
			const row = list.closest("[data-group-folders]");
			const slugs = (row?.dataset.groupFolders || "")
				.split(",")
				.map((slug) => slug.trim())
				.filter(Boolean);
			if (!slugs.length) return;

			TNC.showLoading(list, "Đang tải sản phẩm...");

			try {
				const groups = await TNC.api.productGroups(slugs, 4);
				const items = groups.flatMap((group) => group.items || []).slice(0, 4);

				list.innerHTML = items.length
					? items
							.map((product) =>
								TNC.productCard(product, {
									columnClass: "col-sm-6 col-lg-3",
									tag: "",
									imageHeight: 130,
								}),
							)
							.join("")
					: TNC.emptyState({ icon: "bi-box-seam", title: "Chưa có sản phẩm" });
			} catch (error) {
				TNC.renderError(list, error);
			}
		}),
	);
});

document.addEventListener("DOMContentLoaded", () => {
	const root = document.querySelector("[data-feedback-carousel]");
	if (!root) return;

	const viewport = root.querySelector(".feedback-viewport");
	const track = root.querySelector(".feedback-track");
	const items = [...track.querySelectorAll(".item")];
	const prev = root.querySelector(".feedback-prev");
	const next = root.querySelector(".feedback-next");
	if (!viewport || !track || items.length < 2) return;

	const getVisible = () => {
		if (window.innerWidth <= 575.98) return 1;
		if (window.innerWidth <= 991.98) return 2;
		return 3;
	};

	let visible = getVisible();
	let current = visible;
	let startX = 0;
	let startTranslate = 0;
	let currentTranslate = 0;
	let dragging = false;
	let moved = false;
	let pointerId = null;
	let autoplayId = null;

	items.forEach((item) => track.appendChild(item.cloneNode(true)));
	items.forEach((item) => track.insertBefore(item.cloneNode(true), track.firstChild));

	const allItems = () => [...track.querySelectorAll(".item")];
	const itemWidth = () => allItems()[0]?.getBoundingClientRect().width || 285;

	const setTransition = (enabled) => {
		track.classList.toggle("is-animating", enabled);
	};

	const render = (translate, animate = true) => {
		setTransition(animate);
		currentTranslate = translate;
		track.style.transform = `translate3d(${translate}px, 0, 0)`;
	};

	const baseOffset = () => -(items.length * itemWidth());
	const snap = (index, animate = true) => {
		current = index;
		render(-(current * itemWidth()), animate);
	};

	const normalize = () => {
		const count = items.length;
		if (current >= count * 2) {
			current -= count;
			render(-(current * itemWidth()), false);
		} else if (current < count) {
			current += count;
			render(-(current * itemWidth()), false);
		}
	};

	const resetAutoplay = () => {
		clearInterval(autoplayId);
		autoplayId = setInterval(() => {
			snap(current + 1);
		}, 5000);
	};

	const rebuild = () => {
		const nextVisible = getVisible();
		if (nextVisible === visible) return;
		visible = nextVisible;
		current = items.length;
		render(-(current * itemWidth()), false);
	};

	const startDrag = (event) => {
		if (event.pointerType === "mouse" && event.button !== 0) return;
		dragging = true;
		moved = false;
		pointerId = event.pointerId;
		startX = event.clientX;
		startTranslate = currentTranslate;
		viewport.classList.add("is-dragging");
		viewport.setPointerCapture?.(pointerId);
		setTransition(false);
		clearInterval(autoplayId);
	};

	const moveDrag = (event) => {
		if (!dragging || event.pointerId !== pointerId) return;
		const delta = event.clientX - startX;
		if (Math.abs(delta) > 6) moved = true;
		const width = itemWidth();
		const min = -((items.length * 2 - visible) * width);
		const max = -((items.length - 1) * width);
		let nextTranslate = startTranslate + delta;

		if (nextTranslate > max) nextTranslate = max + (nextTranslate - max) * 0.25;
		if (nextTranslate < min) nextTranslate = min + (nextTranslate - min) * 0.25;

		render(nextTranslate, false);
	};

	const endDrag = (event) => {
		if (!dragging || event.pointerId !== pointerId) return;
		dragging = false;
		viewport.classList.remove("is-dragging");
		viewport.releasePointerCapture?.(pointerId);
		pointerId = null;

		const delta = event.clientX - startX;
		const threshold = Math.min(100, itemWidth() * 0.18);
		if (Math.abs(delta) >= threshold) {
			snap(current + (delta < 0 ? 1 : -1));
		} else {
			snap(current);
		}
		resetAutoplay();
	};

	prev.addEventListener("click", () => {
		if (moved) {
			moved = false;
			return;
		}
		snap(current - 1);
		resetAutoplay();
	});

	next.addEventListener("click", () => {
		if (moved) {
			moved = false;
			return;
		}
		snap(current + 1);
		resetAutoplay();
	});

	viewport.addEventListener("pointerdown", startDrag);
	viewport.addEventListener("pointermove", moveDrag);
	viewport.addEventListener("pointerup", endDrag);
	viewport.addEventListener("pointercancel", endDrag);
	viewport.addEventListener("click", (event) => {
		if (!moved) return;
		event.preventDefault();
		event.stopPropagation();
		moved = false;
	}, true);

	track.addEventListener("transitionend", () => {
		if (!dragging) normalize();
	});

	window.addEventListener("resize", rebuild);

	visible = getVisible();
	current = items.length;
	render(-(current * itemWidth()), false);
	resetAutoplay();
});

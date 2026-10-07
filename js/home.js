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
							columnClass: "col-sm-4 col-lg-3",
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
	const directionThreshold = 8;

	document.querySelectorAll(".promo-carousel-section .carousel").forEach((carousel) => {
		const inner = carousel.querySelector(".carousel-inner");
		const items = [...carousel.querySelectorAll(".carousel-item")];
		if (!inner || items.length < 2) return;

		carousel.classList.add("owl-drag", "owl-grab");
		carousel.style.cursor = "grab";
		carousel.style.userSelect = "none";
		carousel.style.webkitUserSelect = "none";

		let pointerId = null;
		let startX = 0;
		let startY = 0;
		let currentX = 0;
		let dragging = false;
		let cancelled = false;
		let suppressClick = false;
		let activeItem = null;
		let targetItem = null;
		let targetIndex = -1;
		let direction = 0;
		let width = 0;

		const clearDragStyles = () => {
			items.forEach((item) => {
				item.classList.remove("promo-drag-target");
				item.style.transition = "";
				item.style.transform = "";
				item.style.position = "";
				item.style.inset = "";
				item.style.zIndex = "";
				item.style.display = "";
			});
			inner.style.height = "";
			carousel.classList.remove("is-dragging");
			carousel.style.cursor = "grab";
		};

		const getActiveIndex = () => {
			const active = items.findIndex((item) => item.classList.contains("active"));
			return active >= 0 ? active : 0;
		};

		const prepareTarget = (nextDirection) => {
			const activeIndex = getActiveIndex();
			targetIndex = (activeIndex + (nextDirection < 0 ? 1 : -1) + items.length) % items.length;
			direction = nextDirection;
			activeItem = items[activeIndex];
			targetItem = items[targetIndex];
			width = inner.clientWidth;

			inner.style.height = `${inner.clientHeight}px`;

			targetItem.classList.add("promo-drag-target");
			targetItem.style.position = "absolute";
			targetItem.style.inset = "0";
			targetItem.style.zIndex = "1";
			targetItem.style.display = "block";
			targetItem.style.transition = "none";
			targetItem.style.transform = `translate3d(${direction * -width}px, 0, 0)`;

			activeItem.style.position = "relative";
			activeItem.style.zIndex = "2";
			activeItem.style.transition = "none";
		};

		const updatePosition = (deltaX) => {
			activeItem.style.transform = `translate3d(${deltaX}px, 0, 0)`;
			targetItem.style.transform = `translate3d(${direction * -width + deltaX}px, 0, 0)`;
		};

		const finish = (commit) => {
			if (!activeItem || !targetItem) {
				clearDragStyles();
				return;
			}

			const finalActiveX = commit ? direction * width : 0;
			const finalTargetX = commit ? 0 : direction * -width;

			activeItem.style.transition = "transform 260ms ease-out";
			targetItem.style.transition = "transform 260ms ease-out";
			activeItem.style.transform = `translate3d(${finalActiveX}px, 0, 0)`;
			targetItem.style.transform = `translate3d(${finalTargetX}px, 0, 0)`;

			window.setTimeout(() => {
				if (commit) {
					items.forEach((item, index) => {
						item.classList.toggle("active", index === targetIndex);
					});
					carousel.querySelectorAll(".carousel-indicators [data-bs-slide-to]").forEach((indicator) => {
						const isActive = Number(indicator.dataset.bsSlideTo) === targetIndex;
						indicator.classList.toggle("active", isActive);
						indicator.setAttribute("aria-current", isActive ? "true" : "false");
					});
				}

				clearDragStyles();
				const instance = bootstrap.Carousel.getOrCreateInstance(carousel);
				instance.cycle();
			}, 270);
		};

		const cancel = () => {
			if (pointerId === null) return;
			cancelled = true;
			if (dragging) finish(false);
			else {
				clearDragStyles();
				bootstrap.Carousel.getOrCreateInstance(carousel).cycle();
			}
			pointerId = null;
			dragging = false;
		};

		carousel.addEventListener("pointerdown", (event) => {
			if (event.pointerType === "mouse" && event.button !== 0) return;
			if (event.target.closest(".carousel-control-prev, .carousel-control-next, .carousel-indicators")) return;

			pointerId = event.pointerId;
			startX = currentX = event.clientX;
			startY = event.clientY;
			cancelled = false;
			dragging = false;
			suppressClick = false;

			carousel.setPointerCapture?.(pointerId);
			bootstrap.Carousel.getOrCreateInstance(carousel).pause();
		});

		carousel.addEventListener("pointermove", (event) => {
			if (event.pointerId !== pointerId || cancelled) return;

			const deltaX = event.clientX - startX;
			const deltaY = event.clientY - startY;

			if (!dragging) {
				if (Math.abs(deltaX) < directionThreshold && Math.abs(deltaY) < directionThreshold) return;

				if (Math.abs(deltaY) > Math.abs(deltaX)) {
					cancel();
					return;
				}

				dragging = true;
				suppressClick = true;
				carousel.classList.add("is-dragging");
				carousel.style.cursor = "grabbing";
				prepareTarget(deltaX < 0 ? -1 : 1);
			}

			event.preventDefault();
			currentX = event.clientX;
			let dragX = currentX - startX;

			if (Math.abs(dragX) > width && width > 0) {
				dragX = Math.sign(dragX) * (width + (Math.abs(dragX) - width) / 5);
			}

			updatePosition(dragX);
		});

		const release = (event) => {
			if (event.pointerId !== pointerId) return;

			const wasDragging = dragging;
			const deltaX = currentX - startX;
			const commit = wasDragging && Math.abs(deltaX) >= dragThreshold;

			if (wasDragging) finish(commit);
			else {
				clearDragStyles();
				bootstrap.Carousel.getOrCreateInstance(carousel).cycle();
			}

			if (carousel.hasPointerCapture?.(pointerId)) {
				carousel.releasePointerCapture(pointerId);
			}

			pointerId = null;
			dragging = false;
		};

		carousel.addEventListener("pointerup", release);
		carousel.addEventListener("pointercancel", cancel);
		carousel.addEventListener("lostpointercapture", () => {
			if (pointerId !== null) cancel();
		});

		carousel.addEventListener("click", (event) => {
			if (!suppressClick) return;
			event.preventDefault();
			event.stopPropagation();
			suppressClick = false;
		}, true);
	});
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
									columnClass: "col-sm-4 col-lg-3",
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

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
	const updateCarouselControls = (carousel) => {
		const list = carousel.querySelector("[data-product-list]");
		const previous = carousel.querySelector(".product-carousel-prev");
		const next = carousel.querySelector(".product-carousel-next");
		if (!list || !previous || !next) return;

		const maxScroll = list.scrollWidth - list.clientWidth;
		const canScroll = maxScroll > 1;
		previous.hidden = !canScroll;
		next.hidden = !canScroll;
		previous.disabled = list.scrollLeft <= 1;
		next.disabled = list.scrollLeft >= maxScroll - 1;
	};

	document.querySelectorAll("[data-product-carousel]").forEach((carousel) => {
		const list = carousel.querySelector("[data-product-list]");
		list?.addEventListener("scroll", () => updateCarouselControls(carousel), { passive: true });
		new ResizeObserver(() => updateCarouselControls(carousel)).observe(carousel);
	});

	document.addEventListener("click", (event) => {
		const button = event.target.closest("[data-product-scroll]");
		if (!button) return;
		const carousel = button.closest("[data-product-carousel]");
		const list = carousel?.querySelector("[data-product-list]");
		const item = list?.querySelector(".home-category-product");
		if (!list || !item) return;
		list.scrollBy({
			left: Number(button.dataset.productScroll) * item.getBoundingClientRect().width,
			behavior: "smooth",
		});
	});

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
				const groups = await TNC.api.productGroups(slugs, 8);
				const productGroups = groups.map((group) => group.items || []);
				const items = [];
				for (let index = 0; index < 8 && items.length < 8; index += 1) {
					let foundItem = false;
					for (const products of productGroups) {
						if (items.length === 8) break;
						if (products[index]) {
							items.push(products[index]);
							foundItem = true;
						}
						if (items.length === 8) break;
					}
					if (!foundItem) break;
				}

				list.innerHTML = items.length
					? items
							.map((product) =>
								TNC.productCard(product, {
									columnClass: "home-category-product",
									tag: "",
									imageHeight: 205,
								}),
							)
							.join("")
					: TNC.emptyState({ icon: "bi-box-seam", title: "Chưa có sản phẩm" });
				updateCarouselControls(list.closest("[data-product-carousel]"));
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
	if (!viewport || !track || !prev || !next || items.length < 2) return;

	const itemWidth = () => track.querySelector(".item")?.getBoundingClientRect().width || 285;
	const getVisible = () => Math.max(1, Math.min(items.length, Math.floor(viewport.clientWidth / itemWidth())));
	const prefersReducedMotion = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;

	let visible = getVisible();
	let current = items.length;
	let startX = 0;
	let startTranslate = 0;
	let currentTranslate = 0;
	let dragging = false;
	let moved = false;
	let pointerId = null;
	let autoplayId = null;
	let transitionActive = false;

	const cloneItem = (item) => {
		const clone = item.cloneNode(true);
		clone.inert = true;
		clone.setAttribute("aria-hidden", "true");
		clone.querySelectorAll("a, button, input, select, textarea, [tabindex]").forEach((element) => {
			element.tabIndex = -1;
		});
		return clone;
	};

	items.forEach((item) => track.appendChild(cloneItem(item)));
	[...items].reverse().forEach((item) => track.insertBefore(cloneItem(item), track.firstChild));

	const setTransition = (enabled) => {
		track.classList.toggle("is-animating", enabled && !prefersReducedMotion());
	};

	const updateFocusability = () => {
		const start = current % items.length;
		items.forEach((item, index) => {
			const relative = (index - start + items.length) % items.length;
			const visibleItem = relative < visible;
			item.tabIndex = visibleItem ? 0 : -1;
			item.setAttribute("aria-hidden", visibleItem ? "false" : "true");
		});
	};

	const render = (translate, animate = true) => {
		setTransition(animate);
		currentTranslate = translate;
		track.style.transform = `translate3d(${translate}px, 0, 0)`;
		updateFocusability();
	};

	const normalize = () => {
		const count = items.length;
		if (current >= count * 2) current -= count;
		if (current < count) current += count;
		render(-(current * itemWidth()), false);
		transitionActive = false;
	};

	const snap = (index, animate = true) => {
		if (transitionActive && animate) return false;
		current = index;
		transitionActive = animate && !prefersReducedMotion();
		render(-(current * itemWidth()), animate);
		if (!transitionActive) normalize();
		return true;
	};

	const stopAutoplay = () => {
		clearInterval(autoplayId);
		autoplayId = null;
	};

	const resetAutoplay = () => {
		stopAutoplay();
		if (prefersReducedMotion() || root.matches(":hover") || root.contains(document.activeElement)) return;
		autoplayId = setInterval(() => {
			snap(current + 1);
		}, 5000);
	};

	root.addEventListener("pointerenter", stopAutoplay);
	root.addEventListener("pointerleave", resetAutoplay);
	root.addEventListener("focusin", stopAutoplay);
	root.addEventListener("focusout", (event) => {
		if (!root.contains(event.relatedTarget)) resetAutoplay();
	});

	const rebuild = () => {
		const nextVisible = getVisible();
		if (nextVisible === visible) {
			updateFocusability();
			return;
		}
		visible = nextVisible;
		current = items.length;
		transitionActive = false;
		render(-(current * itemWidth()), false);
	};

	const confirmDrag = () => {
		dragging = true;
		moved = true;
		viewport.classList.add("is-dragging");
		viewport.setPointerCapture?.(pointerId);
		setTransition(false);
		stopAutoplay();
	};

	const startDrag = (event) => {
		if (event.pointerType === "mouse" && event.button !== 0) return;
		if (transitionActive) return;
		pointerId = event.pointerId;
		startX = event.clientX;
		startTranslate = currentTranslate;
		moved = false;
		dragging = false;
	};

	const moveDrag = (event) => {
		if (event.pointerId !== pointerId) return;
		const delta = event.clientX - startX;
		if (!dragging && Math.abs(delta) < 6) return;
		if (!dragging) confirmDrag();

		const width = itemWidth();
		const count = items.length;
		const min = -((count * 2 - 1) * width);
		const max = -(count * width);
		let nextTranslate = startTranslate + delta;

		if (nextTranslate > max) nextTranslate = max + (nextTranslate - max) * 0.25;
		if (nextTranslate < min) nextTranslate = min + (nextTranslate - min) * 0.25;
		render(nextTranslate, false);
	};

	const endDrag = (event) => {
		if (event.pointerId !== pointerId) return;
		const wasDragging = dragging;
		const delta = event.clientX - startX;

		if (wasDragging) {
			viewport.classList.remove("is-dragging");
			if (viewport.hasPointerCapture?.(pointerId)) viewport.releasePointerCapture(pointerId);
			dragging = false;
			pointerId = null;
			const threshold = Math.min(100, itemWidth() * 0.18);
			if (Math.abs(delta) >= threshold) snap(current + (delta < 0 ? 1 : -1));
			else snap(current);
			resetAutoplay();
			return;
		}

		pointerId = null;
	};

	prev.addEventListener("click", () => {
		if (moved) {
			moved = false;
			return;
		}
		if (snap(current - 1)) resetAutoplay();
	});

	next.addEventListener("click", () => {
		if (moved) {
			moved = false;
			return;
		}
		if (snap(current + 1)) resetAutoplay();
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

	track.addEventListener("transitionend", (event) => {
		if (event.propertyName === "transform" && !dragging) normalize();
	});

	const resizeObserver = typeof ResizeObserver === "undefined"
		? null
		: new ResizeObserver(rebuild);
	resizeObserver?.observe(viewport);
	window.addEventListener("resize", rebuild);

	window.matchMedia("(prefers-reduced-motion: reduce)").addEventListener?.("change", (event) => {
		if (event.matches) {
			stopAutoplay();
		} else {
			resetAutoplay();
		}
	});

	current = items.length;
	visible = getVisible();
	render(-(current * itemWidth()), false);
	resetAutoplay();
});

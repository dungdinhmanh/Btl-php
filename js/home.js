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
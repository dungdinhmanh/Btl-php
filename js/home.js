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
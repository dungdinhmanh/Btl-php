
document.addEventListener("DOMContentLoaded", async () => {
	const gridContainer = document.querySelector("#product-grid");
	const countEl = document.querySelector("#product-count");
	const sortSelect = document.querySelector("#sort-price");
	const categoryInputs = [...document.querySelectorAll("[data-category-filter]")];

	if (!gridContainer) return;

	const params = new URLSearchParams(window.location.search);
	const querySearch = (params.get("q") || "").trim().toLowerCase();
	// Header, footer, and category-tile links use short slugs; "components" means everything.
	const categoryAliases = {
		pc: "components",
		"pc-gaming": "components",
		components: "components",
		cpu: "cpu",
		mainboard: "mainboard",
		vga: "vga",
	};
	const activeCategory = categoryAliases[(params.get("category") || "").trim().toLowerCase()] || "";

	// Keep the sidebar checkboxes in step with a ?category= link.
	categoryInputs.forEach((input) => {
		if (input.value === activeCategory) input.checked = true;
	});

	const catalog = await loadCatalog();

	if (catalog.length === 0) {
		if (countEl) countEl.textContent = "0 sản phẩm";
		gridContainer.innerHTML = `
			<div class="col-12 py-5 text-center text-muted">
				<i class="bi bi-exclamation-circle fs-1 d-block mb-3"></i>
				<h5>Chưa tải được dữ liệu sản phẩm</h5>
				<p>Kiểm tra lại các tệp CSV trong thư mục assets/products.</p>
			</div>
		`;
		return;
	}

	// Category counts shown next to each filter checkbox.
	categoryInputs.forEach((input) => {
		const count =
			input.value === "components"
				? catalog.length
				: catalog.filter((product) => product.type === input.value).length;
		const badge = document.querySelector(`[data-category-count="${input.value}"]`);
		if (badge) badge.textContent = count;
		input.addEventListener("change", applyFiltersAndSort);
	});

	/** Matches the free-text query against the fields a shopper would search on. */
	function matchesQuery(product) {
		if (!querySearch) return true;
		return [product.name, product.brand, product.model, product.spec]
			.filter(Boolean)
			.some((value) => value.toLowerCase().includes(querySearch));
	}

	function applyFiltersAndSort() {
		const selected = categoryInputs.filter((input) => input.checked).map((input) => input.value);
		const wantsEverything = selected.length === 0 || selected.includes("components");

		const visible = catalog.filter((product) => {
			if (!matchesQuery(product)) return false;
			return wantsEverything || selected.includes(product.type);
		});

		const sort = sortSelect?.value || "featured";
		if (sort === "price-asc") visible.sort((a, b) => a.priceNum - b.priceNum);
		else if (sort === "price-desc") visible.sort((a, b) => b.priceNum - a.priceNum);

		render(visible);
	}

	function render(list) {
		if (countEl) {
			countEl.textContent = querySearch
				? `${list.length} sản phẩm cho "${querySearch}"`
				: `${list.length} sản phẩm`;
		}

		if (list.length === 0) {
			gridContainer.innerHTML = `
				<div class="col-12 py-5 text-center text-muted">
					<i class="bi bi-search fs-1 d-block mb-3"></i>
					<h5>Không tìm thấy sản phẩm phù hợp</h5>
					<p>Thử tìm kiếm với từ khóa khác hoặc bỏ các bộ lọc.</p>
				</div>
			`;
			return;
		}

		gridContainer.innerHTML = productGridMarkup(list, "col-sm-6 col-xl-4");
	}

	sortSelect?.addEventListener("change", applyFiltersAndSort);
	applyFiltersAndSort();
});

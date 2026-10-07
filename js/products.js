/**
 * Product catalog: server-side filtering, search and sorting against the
 * MySQL-backed products endpoint.
 */

document.addEventListener("DOMContentLoaded", async () => {
	const grid = document.querySelector("#product-grid");
	if (!grid) return;

	const countEl = document.querySelector("#product-count");
	const sortSelect = document.querySelector("#sort-price");
	const filterHolder = document.querySelector("#category-filters");
	const brandHolder = document.querySelector("#brand-filters");
	const searchParams = new URLSearchParams(window.location.search);
	const activeFilterHolder = document.querySelector("#active-filters");

	const state = {
		q: (searchParams.get("q") || "").trim(),
		categories: (searchParams.get("category") || "")
			.split(",")
			.map((slug) => slug.trim())
			.filter(Boolean),
    	brand: searchParams.get("brand") || "",
		sort: searchParams.get("sort") || "newest",
	};
let brands = [];

	if (sortSelect && state.sort) sortSelect.value = state.sort;

	function syncUrl() {
		const params = new URLSearchParams();
		if (state.q) params.set("q", state.q);
		if (state.categories.length) params.set("category", state.categories.join(","));
		if (state.brand) params.set("brand", state.brand);
		if (state.sort !== "newest") params.set("sort", state.sort);
		const query = params.toString();
		window.history.replaceState({}, "", query ? `?${query}` : window.location.pathname);
	}

	function renderFilters(categories) {
	if (!filterHolder) return;

	filterHolder.innerHTML = `
		<div class="category-filter-title">Danh mục</div>
		<div class="category-filter-list">
			${categories
				.map(
					(category) => `
						<a href="#"
							class="category-filter-item"
							data-category-filter="${TNC.escapeHtml(category.slug)}">
							» ${TNC.escapeHtml(category.name)}
						</a>
					`,
				)
				.join("")}
		</div>
	`;

	filterHolder
		.querySelectorAll("[data-category-filter]")
		.forEach((item) => {
			item.addEventListener("click", (event) => {
				event.preventDefault();

				const slug = item.dataset.categoryFilter;

				state.categories =
					state.categories.includes(slug)
						? []
						: [slug];

				loadProducts();
			});
		});
}
	function renderBrandFilters(brands) {
	if (!brandHolder) return;

	const visibleBrands = state.brand
    ? brands.filter((brand) => brand.slug === state.brand)
    : brands;

	brandHolder.innerHTML = visibleBrands
		.map(
			(brand) => `
				<label class="brand-filter-item">
					<span class="brand-filter-left">
						<input
							type="checkbox"
							value="${TNC.escapeHtml(brand.slug)}"
							data-brand-filter
							${state.brand === brand.slug ? "checked" : ""}
						/>
						<span>${TNC.escapeHtml(brand.name)}</span>
					</span>

					<span class="brand-product-count">
						[${brand.productCount}]
					</span>
				</label>
			`,
		)
		.join("");

	brandHolder
    .querySelectorAll("[data-brand-filter]")
    .forEach((input) => {
        input.addEventListener("click", () => {
            if (state.brand === input.value) {
                state.brand = "";
                input.checked = false;
            } else {
                state.brand = input.value;
                input.checked = true;
            }
            renderBrandFilters(brands);
            loadProducts();
        });
      });
	}

	async function loadProducts() {
		syncUrl();
		TNC.showLoading(grid, "Đang tải sản phẩm...");

		try {
			const payload = await TNC.api.products({
				q: state.q,
				category: state.categories.join(","),
				brand: state.brand,
				sort: state.sort,
				limit: 60,
			});

			const items = payload.data || [];
			if (countEl) countEl.textContent = `${payload.total ?? items.length} sản phẩm`;
			const activeFilterHolder = document.querySelector("#active-filters");

			if (activeFilterHolder) {
			if (state.brand) {
			const selectedBrand = brands.find(
			(brand) => brand.slug === state.brand
		);

		activeFilterHolder.innerHTML = `
			<span class="active-filter-label">Lọc theo:</span>

			<button type="button" class="active-filter-item" id="remove-brand-filter">
    			${TNC.escapeHtml(selectedBrand?.name || state.brand)}
    			<span class="active-filter-x">×</span>
			</button>

			<button type="button" class="clear-all-filters" id="clear-all-filters">
				Xóa tất cả
			</button>
		`;
	} else {
		activeFilterHolder.innerHTML = "";
	}
}

if (activeFilterHolder) {
	document
		.querySelector("#remove-brand-filter")
		?.addEventListener("click", () => {
			state.brand = "";
			renderBrandFilters(brands);
			loadProducts();
		});

	document
		.querySelector("#clear-all-filters")
		?.addEventListener("click", () => {
			state.brand = "";
			state.categories = [];
			state.q = "";
			renderBrandFilters(brands);
			loadProducts();
		});
}
			grid.innerHTML = items.length
				? items.map((product) => TNC.productCard(product)).join("")
				: TNC.emptyState({
						icon: "bi-search",
						title: "Không tìm thấy sản phẩm phù hợp",
						text: "Thử tìm kiếm với từ khóa khác hoặc bỏ các bộ lọc.",
					});
		} catch (error) {
			if (countEl) countEl.textContent = "0 sản phẩm";
			TNC.renderError(grid, error);
		}
	}

	sortSelect?.addEventListener("change", () => {
		state.sort = sortSelect.value || "newest";
		loadProducts();
	});

	TNC.showLoading(filterHolder, "Đang tải bộ lọc...", "text-muted small py-3");

	try {
		const meta = await TNC.api.meta();
		brands = meta.brands || [];
		renderFilters(meta.categories || []);
		renderBrandFilters(meta.brands || []);
	} catch {
		if (filterHolder) {
			filterHolder.innerHTML =
				'<p class="text-muted small mb-0">Không tải được bộ lọc.</p>';
		}
	}

	await loadProducts();
});

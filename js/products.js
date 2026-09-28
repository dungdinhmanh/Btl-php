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
	const searchParams = new URLSearchParams(window.location.search);

	const state = {
		q: (searchParams.get("q") || "").trim(),
		categories: (searchParams.get("category") || "")
			.split(",")
			.map((slug) => slug.trim())
			.filter(Boolean),
		sort: searchParams.get("sort") || "newest",
	};

	if (sortSelect && state.sort) sortSelect.value = state.sort;

	function syncUrl() {
		const params = new URLSearchParams();
		if (state.q) params.set("q", state.q);
		if (state.categories.length) params.set("category", state.categories.join(","));
		if (state.sort !== "newest") params.set("sort", state.sort);
		const query = params.toString();
		window.history.replaceState({}, "", query ? `?${query}` : window.location.pathname);
	}

	function renderFilters(categories) {
		if (!filterHolder) return;

		filterHolder.innerHTML = categories
			.map(
				(category) => `
					<label class="filter-check">
						<input type="checkbox" value="${TNC.escapeHtml(category.slug)}" data-category-filter
							${state.categories.includes(category.slug) ? "checked" : ""} />
						${TNC.escapeHtml(category.name)}
						<span data-category-count="${TNC.escapeHtml(category.slug)}">${category.productCount}</span>
					</label>
				`,
			)
			.join("");

		filterHolder.querySelectorAll("[data-category-filter]").forEach((input) => {
			input.addEventListener("change", () => {
				state.categories = [...filterHolder.querySelectorAll("[data-category-filter]")]
					.filter((box) => box.checked)
					.map((box) => box.value);
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
				sort: state.sort,
				limit: 60,
			});

			const items = payload.data || [];
			if (countEl) countEl.textContent = `${payload.total ?? items.length} sản phẩm`;

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
		renderFilters(meta.categories || []);
	} catch {
		if (filterHolder) {
			filterHolder.innerHTML =
				'<p class="text-muted small mb-0">Không tải được bộ lọc.</p>';
		}
	}

	await loadProducts();
});

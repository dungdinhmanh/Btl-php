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
	const breadcrumbHolder = document.querySelector("#products-breadcrumb");
	const productTitle = document.querySelector("#product-page-title");
	const categoryFeatured = document.querySelector("#category-featured");
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

	function renderBreadcrumb(categories) {
    if (!breadcrumbHolder) return;

    if (!state.categories.length) {
        breadcrumbHolder.innerHTML = `
            <a href="index.php">Trang chủ</a>
            <span>›</span>
            <a href="products.php" class="current">Linh kiện máy tính</a>
        `;
        return;
    }

    const category = categories.find(
        (item) => item.slug === state.categories[0]
    );

    if (productTitle) {
    productTitle.textContent = category
        ? category.name.toUpperCase()
        : "LINH KIỆN MÁY TÍNH";
}

    if (!category) return;

    breadcrumbHolder.innerHTML = `
        <a href="index.php" class="breadcrumb-home">Trang chủ</a>
        <span>›</span>
        <a href="products.php">Linh kiện máy tính</a>
        <span>›</span>
        <a
            href="products.php?category=${encodeURIComponent(category.slug)}"
            class="current"
        >
            ${TNC.escapeHtml(category.name)}
        </a>
    `;
}

	function renderFilters(categories) {
    if (!filterHolder) return;

    const order = [
        "cpu",
        "mainboard",
        "ram",
        "hdd",
        "case",
        "psu",
        "ssd"
    ];

    const sortedCategories = [...categories].sort((a, b) => {
        const indexA = order.indexOf(a.slug.toLowerCase());
        const indexB = order.indexOf(b.slug.toLowerCase());

        const positionA = indexA === -1 ? order.length : indexA;
        const positionB = indexB === -1 ? order.length : indexB;

        return positionA - positionB;
    });

    filterHolder.innerHTML = `
        <div class="category-filter-title">Danh mục</div>
        <div class="category-filter-list">
            ${sortedCategories
                .map(
                    (category) => `
                        <a href="#"
                            class="category-filter-item"
                            data-category-filter="${TNC.escapeHtml(category.slug)}">
                            » ${TNC.escapeHtml(category.name)}
                        </a>
                    `
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

                window.location.href =
                    `products.php?category=${encodeURIComponent(slug)}`;
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


async function loadCategoryFeatured() {
    const holder = document.querySelector("#category-featured");

    if (!holder) return;

    const category = state.categories[0];

    if (category !== "cpu") {
        holder.style.display = "none";
        holder.innerHTML = "";
        return;
    }

    try {
        const payload = await TNC.api.products({
            category: "cpu",
            sort: "newest",
            limit: 4,
        });

        const items = payload.data || [];

        if (!items.length) {
            holder.style.display = "none";
            return;
        }

        holder.innerHTML = `
            <section class="category-featured-section">
                <h2>CPU BÁN CHẠY</h2>
                <div class="category-featured-grid">
                    ${items.map((product) => TNC.productCard(product)).join("")}
                </div>
            </section>
        `;

        holder.style.display = "block";
    } catch (error) {
        console.error("Không tải được CPU nổi bật:", error);
        holder.style.display = "none";
    }
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
		const categories = meta.categories || [];
		renderFilters(categories);
		renderBrandFilters(brands);
		renderBreadcrumb(categories);
	} catch {
		if (filterHolder) {
			filterHolder.innerHTML =
				'<p class="text-muted small mb-0">Không tải được bộ lọc.</p>';
		}
	}

	await loadProducts();
	await loadCategoryFeatured();
});

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
	const socketHolder = document.querySelector("#cpu-socket-filters");
	const socketSection = document.querySelector("#cpu-socket-section");
	const breadcrumbHolder = document.querySelector("#products-breadcrumb");
	const productTitle = document.querySelector("#product-page-title");
	const categoryFeatured = document.querySelector("#category-featured");
    const searchParams = new URLSearchParams(window.location.search);
    const currentCategory = (searchParams.get("category") || "")
        .split(",")[0]
        .trim()
        .toLowerCase();

    function parsePriceParam(value) {
        if (value === null || value === undefined || value === "") return null;

        const digits = String(value).replace(/[^\d]/g, "");

        return digits === "" ? null : Number(digits);
    }

function updateProductBanner(categories) {
    const carousel = document.querySelector("#productBanner");
    if (!carousel) return;

    // Trang không chọn danh mục giữ carousel 3 ảnh giống trang gốc.
    if (!currentCategory) return;

    const category = categories.find((item) => item.slug === currentCategory);
    const imagePath = category?.banner;
    const bannerSection = document.querySelector("#products-page-banner");

    // Danh mục chưa khai báo banner thì ẩn hẳn, không lùi về ảnh chung.
    if (!imagePath) {
        if (bannerSection) bannerSection.hidden = true;
        return;
    }

    if (bannerSection) bannerSection.hidden = false;

    carousel.innerHTML = `
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img
                    src="${TNC.escapeHtml(imagePath)}"
                    class="d-block w-100"
                    alt="${TNC.escapeHtml(category.bannerAlt || category.name)}"
                />
            </div>
        </div>
    `;

    // Tắt tự động chuyển slide vì danh mục này chỉ có một ảnh.
    carousel.removeAttribute("data-bs-ride");
}

		const state = {
    q: (searchParams.get("q") || "").trim(),
    categories: (searchParams.get("category") || "")
        .split(",")
        .map((slug) => slug.trim())
        .filter(Boolean),
    brand: searchParams.get("brand") || "",
    sort: searchParams.get("sort") || "newest",
    priceMin: parsePriceParam(searchParams.get("min")),
    priceMax: parsePriceParam(searchParams.get("max")),
    socket: searchParams.get("socket") || "",
};
let brands = [];
let sockets = [];
let categoriesMeta = [];
let priceFilterConfig = null;
const priceFacetsCache = new Map();
let productsController = null;
let productsRequestId = 0;

if (state.priceMin !== null && state.priceMax !== null && state.priceMin > state.priceMax) {
    [state.priceMin, state.priceMax] = [state.priceMax, state.priceMin];
}

	if (sortSelect && state.sort) sortSelect.value = state.sort;

	function syncUrl() {
		const params = new URLSearchParams();
		if (state.q) params.set("q", state.q);
		if (state.categories.length) params.set("category", state.categories.join(","));
		if (state.brand) params.set("brand", state.brand);
		if (state.priceMin !== null) params.set("min", String(state.priceMin));
		if (state.priceMax !== null) params.set("max", String(state.priceMax));
		if (state.socket) params.set("socket", state.socket);
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

    // Thứ tự danh mục đã được meta API sắp theo config.
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
						${brand.productCount}
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

/**
 * Socket chỉ chọn một giá trị tại một thời điểm, giống trang gốc TNC.
 * Danh mục không có sản phẩm dùng socket (RAM, SSD, PSU...) thì ẩn hẳn
 * khối lọc để tránh chọn xong lại ra 0 sản phẩm.
 */
function renderSocketFilters(items) {
    if (!socketHolder || !socketSection) return;

    if (!items.length) {
        socketSection.hidden = true;
        socketHolder.innerHTML = "";
        return;
    }

    socketSection.hidden = false;
    socketHolder.innerHTML = items
        .map(
            (item) => `
                <label class="cpu-filter-item">
                    <input
                        type="checkbox"
                        value="${TNC.escapeHtml(item.socket)}"
                        data-socket-filter
                        ${state.socket && state.socket === item.socket ? "checked" : ""}
                    />
                    <span>${TNC.escapeHtml(item.socket)}</span>
                    <span class="socket-product-count">${item.productCount}</span>
                </label>
            `,
        )
        .join("");

    socketHolder
        .querySelectorAll("[data-socket-filter]")
        .forEach((input) => {
            input.addEventListener("change", () => {
                if (input.checked) {
                    state.socket = input.value;
                    socketHolder
                        .querySelectorAll("[data-socket-filter]")
                        .forEach((other) => {
                            if (other !== input) other.checked = false;
                        });
                } else {
                    state.socket = "";
                }

                loadProducts();
            });
        });
}


function formatPriceInput(value) {
    if (value === null || value === undefined || Number.isNaN(Number(value))) return "";

    return new Intl.NumberFormat("vi-VN").format(Number(value));
}

function parsePriceInput(value) {
    const digits = String(value ?? "").replace(/[^\d]/g, "");

    return digits === "" ? null : Number(digits);
}

function getSelectedPriceBand() {
    if (!priceFilterConfig) return null;

    return priceFilterConfig.bands.find(
        (band) => band.min === state.priceMin && band.max === state.priceMax
    ) || null;
}

function getPriceFilterLabel() {
    const band = getSelectedPriceBand();
    if (band) return band.label;

    const hasMin = state.priceMin !== null;
    const hasMax = state.priceMax !== null;

    if (hasMin && hasMax) {
        return `${formatPriceInput(state.priceMin)} đ - ${formatPriceInput(state.priceMax)} đ`;
    }
    if (hasMin) return `Trên ${formatPriceInput(state.priceMin)} đ`;
    if (hasMax) return `Dưới ${formatPriceInput(state.priceMax)} đ`;

    return "";
}

function updatePriceSliderFill() {
    const fill = document.querySelector("#price-slider-fill");
    const minSlider = document.querySelector("#price-slider-min");
    const maxSlider = document.querySelector("#price-slider-max");
    if (!fill || !minSlider || !maxSlider) return;

    const min = Number(minSlider.min);
    const max = Number(maxSlider.max);
    const span = max - min;

    if (span <= 0) {
        fill.style.left = "0%";
        fill.style.width = "100%";
        return;
    }

    const from = ((Number(minSlider.value) - min) / span) * 100;
    const to = ((Number(maxSlider.value) - min) / span) * 100;

    fill.style.left = `${Math.max(0, Math.min(100, from))}%`;
    fill.style.width = `${Math.max(0, Math.min(100, to - from))}%`;
}

function syncPriceControls() {
    if (!priceFilterConfig) return;

    const minValue = state.priceMin ?? priceFilterConfig.min;
    const maxValue = state.priceMax ?? priceFilterConfig.max;
    const minInput = document.querySelector("#price-min-input");
    const maxInput = document.querySelector("#price-max-input");
    const minSlider = document.querySelector("#price-slider-min");
    const maxSlider = document.querySelector("#price-slider-max");

    if (minInput) minInput.value = formatPriceInput(minValue);
    if (maxInput) maxInput.value = formatPriceInput(maxValue);
    if (minSlider) minSlider.value = String(minValue);
    if (maxSlider) maxSlider.value = String(maxValue);

    updatePriceSliderFill();

    document.querySelectorAll("[data-price-band]").forEach((input) => {
        const band = priceFilterConfig.bands[Number(input.dataset.priceBand)];
        input.checked = Boolean(
            band && band.min === state.priceMin && band.max === state.priceMax
        );
    });
}

function renderPriceFacetCounts(facets) {
    facets.forEach((facet, index) => {
        const node = document.querySelector(`[data-price-count="${index}"]`);
        if (node) node.textContent = `(${facet.count})`;
    });
}

function applyPriceRange(min, max, { reload = true } = {}) {
    if (!priceFilterConfig) return;

    min = min === null
        ? null
        : Math.max(priceFilterConfig.min, Math.min(priceFilterConfig.max, min));
    max = max === null
        ? null
        : Math.max(priceFilterConfig.min, Math.min(priceFilterConfig.max, max));

    if (min !== null && max !== null && min > max) {
        [min, max] = [max, min];
    }

    state.priceMin = min;
    state.priceMax = max;
    syncPriceControls();

    if (reload) loadProducts();
}

function renderPriceFilter(config) {
    const section = document.querySelector("#price-filter-section");
    const body = document.querySelector("#price-filter-body");
    if (!section || !body) return;

    if (!config || !Array.isArray(config.bands) || config.bands.length === 0) {
        section.hidden = true;
        priceFilterConfig = null;
        state.priceMin = null;
        state.priceMax = null;
        return;
    }

    priceFilterConfig = {
        min: Number(config.min ?? 0),
        max: Number(config.max ?? 0),
        bands: config.bands.map((band) => ({
            label: String(band.label ?? ""),
            min: band.min === null || band.min === undefined ? null : Number(band.min),
            max: band.max === null || band.max === undefined ? null : Number(band.max),
        })),
    };

    if (priceFilterConfig.max < priceFilterConfig.min) {
        [priceFilterConfig.min, priceFilterConfig.max] = [
            priceFilterConfig.max,
            priceFilterConfig.min,
        ];
    }

    if (state.priceMin !== null) {
        state.priceMin = Math.max(
            priceFilterConfig.min,
            Math.min(priceFilterConfig.max, state.priceMin)
        );
    }
    if (state.priceMax !== null) {
        state.priceMax = Math.max(
            priceFilterConfig.min,
            Math.min(priceFilterConfig.max, state.priceMax)
        );
    }
    if (state.priceMin !== null && state.priceMax !== null && state.priceMin > state.priceMax) {
        [state.priceMin, state.priceMax] = [state.priceMax, state.priceMin];
    }

    const hasSlider = priceFilterConfig.max > priceFilterConfig.min;
    const step = 50000;

    body.innerHTML = `
        <div class="price-filter-options">
            ${priceFilterConfig.bands
                .map(
                    (band, index) => `
                        <label class="price-filter-item">
                            <input type="checkbox" data-price-band="${index}">
                            <span class="price-filter-name">${TNC.escapeHtml(band.label)}</span>
                            <span class="price-filter-count" data-price-count="${index}"></span>
                        </label>
                    `
                )
                .join("")}
        </div>
        <div class="price-filter-form">
            <div class="price-filter-inputs">
                <label class="price-filter-input">
                    <input type="text" inputmode="numeric" id="price-min-input" aria-label="Giá thấp nhất">
                    <span>đ</span>
                </label>
                <span class="price-separator">-</span>
                <label class="price-filter-input">
                    <input type="text" inputmode="numeric" id="price-max-input" aria-label="Giá cao nhất">
                    <span>đ</span>
                </label>
            </div>
            ${hasSlider
                ? `
                    <div class="price-slider" id="price-slider">
                        <div class="price-slider-track"></div>
                        <div class="price-slider-fill" id="price-slider-fill"></div>
                        <input
                            type="range"
                            id="price-slider-min"
                            min="${priceFilterConfig.min}"
                            max="${priceFilterConfig.max}"
                            step="${step}"
                            aria-label="Giá thấp nhất"
                        >
                        <input
                            type="range"
                            id="price-slider-max"
                            min="${priceFilterConfig.min}"
                            max="${priceFilterConfig.max}"
                            step="${step}"
                            aria-label="Giá cao nhất"
                        >
                    </div>
                `
                : ""}
        </div>
    `;

    section.hidden = false;
    syncPriceControls();

    body.querySelectorAll("[data-price-band]").forEach((input) => {
        input.addEventListener("change", () => {
            const band = priceFilterConfig.bands[Number(input.dataset.priceBand)];
            if (!band) return;

            if (input.checked) {
                applyPriceRange(band.min, band.max);
            } else {
                applyPriceRange(null, null);
            }
        });
    });

    const minInput = body.querySelector("#price-min-input");
    const maxInput = body.querySelector("#price-max-input");
    const minSlider = body.querySelector("#price-slider-min");
    const maxSlider = body.querySelector("#price-slider-max");

    const commitInputs = () => {
        applyPriceRange(
            parsePriceInput(minInput?.value),
            parsePriceInput(maxInput?.value)
        );
    };

    [minInput, maxInput].forEach((input) => {
        if (!input) return;

        input.addEventListener("focus", () => {
            input.dataset.previousValue = input.value;
            input.value = String(parsePriceInput(input.value) ?? "");
        });

        input.addEventListener("blur", () => {
            if (input.value.trim() === "") {
                const fallback = input.id === "price-min-input"
                    ? (state.priceMin ?? priceFilterConfig.min)
                    : (state.priceMax ?? priceFilterConfig.max);
                input.value = formatPriceInput(fallback);
                delete input.dataset.previousValue;
                return;
            }

            // Không gửi lại filter khi người dùng chỉ bấm vào rồi rời ô,
            // tránh biến khoảng mở ("Trên 11 triệu") thành khoảng rỗng.
            const next = parsePriceInput(input.value);
            const previous = parsePriceInput(input.dataset.previousValue ?? "");
            delete input.dataset.previousValue;

            if (next === previous) {
                input.value = formatPriceInput(next);
                return;
            }

            commitInputs();
        });

        input.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                input.blur();
            }
        });
    });

    if (minSlider && maxSlider) {
        minSlider.addEventListener("input", () => {
            if (Number(minSlider.value) > Number(maxSlider.value)) {
                minSlider.value = maxSlider.value;
            }

            if (minInput) minInput.value = formatPriceInput(Number(minSlider.value));
            if (maxInput) maxInput.value = formatPriceInput(Number(maxSlider.value));
            updatePriceSliderFill();
        });

        maxSlider.addEventListener("input", () => {
            if (Number(maxSlider.value) < Number(minSlider.value)) {
                maxSlider.value = minSlider.value;
            }

            if (minInput) minInput.value = formatPriceInput(Number(minSlider.value));
            if (maxInput) maxInput.value = formatPriceInput(Number(maxSlider.value));
            updatePriceSliderFill();
        });

        const commitSlider = () => {
            applyPriceRange(Number(minSlider.value), Number(maxSlider.value));
        };

        minSlider.addEventListener("change", commitSlider);
        maxSlider.addEventListener("change", commitSlider);
    }

    const title = section.querySelector("#price-filter-toggle");
    title?.addEventListener("click", () => {
        const collapsed = section.classList.toggle("is-collapsed");
        title.setAttribute("aria-expanded", String(!collapsed));
    });
}

function initPriceFilter() {
    // Bộ lọc giá chỉ có nghĩa khi xem đúng một danh mục.
    if (state.categories.length !== 1) {
        renderPriceFilter(null);
        return;
    }

    const category = categoriesMeta.find((item) => item.slug === currentCategory);

    renderPriceFilter(category?.priceFilter || null);
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
            limit: 10,
        });

        const items = payload.data || [];

        if (!items.length) {
            holder.style.display = "none";
            return;
        }

        holder.innerHTML = `
            <section class="category-featured-section" aria-labelledby="category-featured-title">
                <div class="category-featured-tabs">
                    <span class="category-featured-tab" id="category-featured-title">CPU Bán Chạy</span>
                </div>

                <div class="category-featured-content">
                    <button type="button" class="category-carousel-btn prev"
                            id="cpu-carousel-prev" aria-label="Sản phẩm trước">
                        &#10094;
                    </button>

                    <div class="category-featured-grid" id="cpu-carousel-track">
                        ${items.map((product) => TNC.productCard(product)).join("")}
                    </div>

                    <button type="button" class="category-carousel-btn next"
                            id="cpu-carousel-next" aria-label="Sản phẩm tiếp theo">
                        &#10095;
                    </button>
                </div>
            </section>
        `;

        holder.style.display = "block";
        const track = holder.querySelector("#cpu-carousel-track");
const prevBtn = holder.querySelector("#cpu-carousel-prev");
const nextBtn = holder.querySelector("#cpu-carousel-next");

prevBtn?.addEventListener("click", () => {
    track?.scrollBy({
        left: -track.clientWidth,
        behavior: "smooth"
    });
});

nextBtn?.addEventListener("click", () => {
    track?.scrollBy({
        left: track.clientWidth,
        behavior: "smooth"
    });
});
// Chỉ cho phép di chuyển bằng hai nút mũi tên

    } catch (error) {
        console.error("Không tải được CPU nổi bật:", error);
        holder.style.display = "none";
    }
}
function renderActiveFilters() {
    const holder = document.querySelector("#active-filters");
    if (!holder) return;

    const chips = [];

    if (state.brand) {
        const selectedBrand = brands.find((brand) => brand.slug === state.brand);
        chips.push({ type: "brand", label: selectedBrand?.name || state.brand });
    }

    if (state.priceMin !== null || state.priceMax !== null) {
        chips.push({ type: "price", label: getPriceFilterLabel() });
    }

    if (state.socket) {
        chips.push({ type: "socket", label: state.socket });
    }

    if (!chips.length) {
        holder.innerHTML = "";
        return;
    }

    holder.innerHTML = `
        <span class="active-filter-label">Lọc theo:</span>
        ${chips
            .map(
                (chip) => `
                    <button type="button" class="active-filter-item" data-remove-filter="${chip.type}">
                        ${TNC.escapeHtml(chip.label)}
                        <span class="active-filter-x">×</span>
                    </button>
                `
            )
            .join("")}
        <button type="button" class="clear-all-filters" id="clear-all-filters">
            Xóa tất cả
        </button>
    `;

    holder.querySelectorAll("[data-remove-filter]").forEach((button) => {
        button.addEventListener("click", () => {
            const type = button.dataset.removeFilter;

            if (type === "brand") {
                state.brand = "";
                renderBrandFilters(brands);
            } else if (type === "price") {
                state.priceMin = null;
                state.priceMax = null;
                syncPriceControls();
            } else if (type === "socket") {
                state.socket = "";
                document
                    .querySelectorAll("#cpu-socket-filters input[type='checkbox']")
                    .forEach((input) => {
                        input.checked = false;
                    });
            }

            loadProducts();
        });
    });

    holder.querySelector("#clear-all-filters")?.addEventListener("click", () => {
        state.brand = "";
        state.q = "";
        state.socket = "";
        state.priceMin = null;
        state.priceMax = null;
        renderBrandFilters(brands);
        syncPriceControls();
        document
            .querySelectorAll("#cpu-socket-filters input[type='checkbox']")
            .forEach((input) => {
                input.checked = false;
            });

        loadProducts();
    });
}

	async function loadProducts() {
		syncUrl();
		renderActiveFilters();

		// Hủy request cũ khi người dùng bấm lọc liên tiếp để tránh kết quả
		// của lần trước ghi đè lên lần sau.
		productsController?.abort();
		const controller = new AbortController();
		productsController = controller;
		const requestId = ++productsRequestId;

		const categoryKey = state.categories.join(",");
		const cachedFacets = priceFacetsCache.get(categoryKey);

		TNC.showLoading(grid, "Đang tải sản phẩm...");

		try {
			const payload = await TNC.api.products(
				{
					q: state.q,
					category: categoryKey,
					min: state.priceMin,
					max: state.priceMax,
					socket: state.socket,
					brand: state.brand,
					sort: state.sort,
					limit: 60,
					// Facet giá chỉ phụ thuộc danh mục; đã có thì khỏi tính lại.
					facets: cachedFacets ? 0 : 1,
				},
				{ signal: controller.signal },
			);

			if (requestId !== productsRequestId) return;

			const items = payload.data || [];
			if (countEl) countEl.textContent = `${payload.total ?? items.length} sản phẩm`;

			const facets = cachedFacets || payload.priceFacets;
			if (Array.isArray(facets) && facets.length) {
				priceFacetsCache.set(categoryKey, facets);
				renderPriceFacetCounts(facets);
			}

			grid.innerHTML = items.length
				? items.map((product) => TNC.productCard(product)).join("")
				: TNC.emptyState({
						icon: "bi-search",
						title: "Không tìm thấy sản phẩm phù hợp",
						text: "Thử tìm kiếm với từ khóa khác hoặc bỏ các bộ lọc.",
					});
		} catch (error) {
			if (controller.signal.aborted) return;
			if (countEl) countEl.textContent = "0 sản phẩm";
			TNC.renderError(grid, error);
		}
	}

	sortSelect?.addEventListener("change", () => {
		state.sort = sortSelect.value || "newest";
		loadProducts();
	});

	function bindCollapsibleFilter(sectionId, toggleId) {
		const section = document.querySelector(`#${sectionId}`);
		const toggle = document.querySelector(`#${toggleId}`);
		if (!section || !toggle) return;

		toggle.addEventListener("click", () => {
			const collapsed = section.classList.toggle("is-collapsed");
			toggle.setAttribute("aria-expanded", String(!collapsed));
		});
	}

	bindCollapsibleFilter("brand-filter-section", "brand-filter-toggle");
	bindCollapsibleFilter("cpu-socket-section", "cpu-socket-toggle");

	TNC.showLoading(filterHolder, "Đang tải bộ lọc...", "text-muted small py-3");

	try {
		const meta = await TNC.api.meta({
			category: state.categories.join(","),
		});
		brands = meta.brands || [];
		sockets = meta.sockets || [];
		categoriesMeta = meta.categories || [];

		// URL có thể mang socket không còn thuộc danh mục hiện tại; bỏ đi
		// để bộ lọc và địa chỉ luôn khớp nhau.
		if (state.socket && !sockets.some((item) => item.socket === state.socket)) {
			state.socket = "";
		}

		renderFilters(categoriesMeta);
		renderBrandFilters(brands);
		renderSocketFilters(sockets);
		renderBreadcrumb(categoriesMeta);
		initPriceFilter();
	} catch {
		if (filterHolder) {
			filterHolder.innerHTML =
				'<p class="text-muted small mb-0">Không tải được bộ lọc.</p>';
		}
	}
	updateProductBanner(categoriesMeta);
	await loadProducts();
	await loadCategoryFeatured();
});

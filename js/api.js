/**
 * Storefront API client for the MySQL-backed catalog and news endpoints.
 * Exposes a `TNC` global consumed by the page-specific scripts.
 */
const TNC = (() => {
	const base = (window.TNC_API_BASE || "backend/api").replace(/\/$/, "");

	const escapeHtml = (value) =>
		String(value ?? "").replace(
			/[&<>'"]/g,
			(character) =>
				({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[
					character
				],
		);

	async function request(path, params = {}) {
		const url = new URL(`${base}/${path}`, window.location.href);
		Object.entries(params).forEach(([key, value]) => {
			if (value !== undefined && value !== null && value !== "") {
				url.searchParams.set(key, value);
			}
		});

		const response = await fetch(url, { headers: { Accept: "application/json" } });
		let payload = null;
		try {
			payload = await response.json();
		} catch {
			payload = null;
		}

		if (!response.ok || !payload || payload.ok !== true) {
			const error = new Error(
				payload?.message || `Không tải được dữ liệu (HTTP ${response.status}).`,
			);
			error.status = response.status;
			throw error;
		}

		return payload;
	}

	const api = {
		meta: () => request("meta.php").then((payload) => payload.data),
		featured: (limit = 4) =>
			request("products.php", { featured: 1, limit }).then((payload) => payload.data),
		product: (slug) =>
			request("products.php", { slug }).then((payload) => payload.data),
		products: (params = {}) => request("products.php", params),
		productGroups: (slugs, perCategory = 8) =>
			request("products.php", { categories: slugs.join(","), perCategory }).then(
				(payload) => payload.data,
			),
		news: (params = {}) => request("news.php", params).then((payload) => payload.data),
		newsCategories: () =>
			request("news.php", { categories: 1 }).then((payload) => payload.data),
		newsPost: (slug) => request("news.php", { slug }).then((payload) => payload.data),
		dashboard: () => request("dashboard.php").then((payload) => payload.data),
	};

	const formatPrice = (value) => `${Number(value || 0).toLocaleString("vi-VN")}đ`;

	const detailUrl = (product) => `product-detail.php?slug=${encodeURIComponent(product.slug)}`;

	/**
	 * Product card markup. `.product-card`, `h3`, `.product-brand` and
	 * `.product-price` are relied on by cart.js, so keep those hooks intact.
	 */
	function productCard(product, options = {}) {
		const {
			columnClass = "col-sm-6 col-xl-4",
			tag = "Chính hãng",
			imageHeight = 150,
		} = options;

		const name = escapeHtml(product.name);
		const url = detailUrl(product);
		const meta = [product.brand, product.socket ? `Socket ${product.socket}` : null]
			.filter(Boolean)
			.join(" · ");

		return `
			<div class="${columnClass} d-flex">
				<article class="product-card d-flex w-100 flex-column">
					<a href="${url}" class="product-image p-3 text-center bg-white d-block text-decoration-none">
						<img src="${escapeHtml(product.image)}" alt="${name}" class="img-fluid" style="height: ${imageHeight}px; object-fit: contain;" loading="lazy">
						${tag ? `<span class="product-tag">${escapeHtml(tag)}</span>` : ""}
					</a>
					<p class="product-brand">${escapeHtml(meta)}</p>
					<h3>
						<a href="${url}" class="text-decoration-none text-dark">${name}</a>
					</h3>
					<strong class="product-price">${escapeHtml(product.priceText)}</strong>
					<div class="mt-auto pt-2">
						<button class="btn btn-outline-primary w-100" type="button">
							<i class="bi bi-cart-plus me-2"></i>
							Thêm vào giỏ
						</button>
					</div>
				</article>
			</div>
		`;
	}

	function emptyState({ icon = "bi-inbox", title = "Chưa có dữ liệu", text = "" }) {
		return `
			<div class="col-12 py-5 text-center text-muted">
				<i class="bi ${icon} fs-1 d-block mb-3"></i>
				<h5>${escapeHtml(title)}</h5>
				${text ? `<p class="mb-0">${escapeHtml(text)}</p>` : ""}
			</div>
		`;
	}

	/** Centered spinner so containers are never left blank while data loads. */
	function loadingState(text = "Đang tải dữ liệu...", wrapperClass = "col-12 text-center text-muted py-5") {
		return `
			<div class="${wrapperClass}">
				<div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>
				<span>${escapeHtml(text)}</span>
			</div>
		`;
	}

	function showLoading(container, text, wrapperClass) {
		if (!container) return;
		container.innerHTML = loadingState(text, wrapperClass);
	}

	/** Spinner inside a table body, where a div would be invalid markup. */
	function loadingRow(columns, text = "Đang tải dữ liệu...") {
		return `
			<tr>
				<td colspan="${columns}" class="text-center text-muted py-4">
					<div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>
					<span>${escapeHtml(text)}</span>
				</td>
			</tr>
		`;
	}

	/** Renders a load failure in place of content, distinguishing a down database. */
	function renderError(container, error) {
		if (!container) return;
		const offline = error?.status === 503;
		container.innerHTML = emptyState({
			icon: offline ? "bi-database-exclamation" : "bi-exclamation-triangle",
			title: offline ? "Chưa kết nối được cơ sở dữ liệu" : "Không tải được dữ liệu",
			text: offline
				? "Kiểm tra cấu hình DB_* trong backend/config/.env rồi tải lại trang."
				: error?.message || "",
		});
	}

	return {
		api,
		escapeHtml,
		formatPrice,
		productCard,
		emptyState,
		showLoading,
		loadingRow,
		renderError,
	};
})();

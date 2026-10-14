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

	/**
	 * Ghi dữ liệu (POST/PUT/DELETE) với JSON body.
	 * `request` chỉ dùng GET nên các API ghi dùng đường này.
	 */
	async function send(path, method, payload = {}) {
		const url = new URL(`${base}/${path}`, window.location.href);
		const response = await fetch(url, {
			method,
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
			},
			body: JSON.stringify(payload),
		});

		let data = null;
		try {
			data = await response.json();
		} catch {
			data = null;
		}

		if (!response.ok || !data || data.ok !== true) {
			const error = new Error(data?.message || `Không lưu được dữ liệu (HTTP ${response.status}).`);
			error.status = response.status;
			throw error;
		}

		return data;
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
		reviews: (product) => request("reviews.php", { product }).then((payload) => payload.data),
		createReview: (payload) => send("reviews.php", "POST", payload),
		replyReview: (payload) => send("reviews.php", "POST", payload),
		updateReview: (payload) => send("reviews.php", "PUT", payload),
		deleteReview: (payload) => send("reviews.php", "DELETE", payload),
		news: (params = {}) => request("news.php", params).then((payload) => payload.data),
		newsCategories: () =>
			request("news.php", { categories: 1 }).then((payload) => payload.data),
		newsPost: (slug) => request("news.php", { slug }).then((payload) => payload.data),
		dashboard: () => request("dashboard.php").then((payload) => payload.data),
	};

	const formatPrice = (value) => `${Number(value || 0).toLocaleString("vi-VN")}đ`;

	const detailUrl = (product) => `product-detail.php?slug=${encodeURIComponent(product.slug)}`;

	const starClass = (rating) =>
		`star${Math.max(0, Math.min(5, Math.round(Number(rating) || 0)))}`;

	/**
 * Thẻ sản phẩm — template DUY NHẤT, mọi thẻ trên mọi trang đều từ đây.
 * DOM sao chép từ tncstore.vn. Hai điểm phải giữ nguyên, đã đo trên trang gốc:
 * - `<del class="old-price">` LUÔN được render, kể cả rỗng. Trang gốc reserve cứng
 *   `height: 20px` cho hàng này nên thiếu nó làm card thấp hơn 20px và lệch nhau.
 * - `.product-name` có `height: 38px` cố định (2 dòng) nên tên 1 hay 2 dòng đều bằng nhau.
 * Thẻ không có nút thêm vào giỏ — trang gốc cũng không có.
 */
function productCard(product, options = {}) {
		const { tag = "Chính hãng" } = options;

		const name = escapeHtml(product.name);
		const url = detailUrl(product);
		const price = Number(product.price || 0);
		const priceLabel = price > 0 ? formatPrice(price) : "Liên hệ";
		const discountPercent = Number(product.discountPercent || 0);
		const hasDiscount = Boolean(product.hasDiscount) && discountPercent > 0;
		const reviewCount = Number(product.reviewCount || 0);
		const brand = product.brand ? escapeHtml(product.brand) : "TNC STORE";
		const oldPriceNumber = String(product.listPriceText || "")
			.replace(/\s*đ\s*$/u, "")
			.trim();

		return `
			<article
				class="product-item"
				data-id="${escapeHtml(product.id)}"
				data-brand="${brand}"
				data-slug="${escapeHtml(product.slug)}"
			>
				<a href="${url}" class="product-image">
					<img class="lazy" src="${escapeHtml(product.image)}" alt="${name}" loading="lazy">
					${tag ? `<span class="box-sale">${escapeHtml(tag)}</span>` : ""}
					<span class="p-icon-holder"></span>
				</a>

				<div class="product-info">
					<a href="${url}" class="product-name line-clamp-2">${name}</a>

					${
						reviewCount > 0
							? `<div class="info-review d-flex align-items">
									<i class="icon-star ${starClass(product.rating)}"></i>
									<span>${reviewCount} đánh giá</span>
								</div>`
							: ""
					}

					<div class="main-price">
						<del class="old-price">${hasDiscount ? `${escapeHtml(oldPriceNumber)}<u>đ</u>` : ""}</del>
						<div class="d-flex align-items">
							<b class="price">${priceLabel}</b>
							${
								hasDiscount
									? `<div class="price-saleoff">-${discountPercent}%</div>`
									: ""
							}
						</div>
					</div>
				</div>

				<span class="boder-top"></span>
				<span class="boder-right"></span>
				<span class="boder-bottom"></span>
				<span class="boder-left"></span>
			</article>
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
				? "Kiểm tra lại cấu hình database hoặc internet"
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

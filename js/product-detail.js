/**
 * Product detail page: renders one product fetched from the products endpoint.
 */

const TNC_CATEGORY_ICONS = {
	cpu: "bi-cpu",
	mainboard: "bi-motherboard",
	vga: "bi-gpu-card",
	ram: "bi-memory",
	ssd: "bi-device-ssd",
	case: "bi-pc",
	psu: "bi-lightning-charge",
	display: "bi-display",
	"phu-kien": "bi-plug",
};

document.addEventListener("DOMContentLoaded", async () => {
	const params = new URLSearchParams(window.location.search);
	const identifier = (params.get("slug") || params.get("model") || params.get("id") || "").trim();

	if (identifier === "") {
		showProductError("Đường dẫn thiếu tham số sản phẩm.");
		return;
	}

	const specBody = document.querySelector("#spec-table tbody");
	if (specBody) specBody.innerHTML = TNC.loadingRow(2, "Đang tải thông số...");

	try {
		renderProductDetail(await TNC.api.product(identifier));
	} catch (error) {
		showProductError(error?.message || "Không tải được sản phẩm.");
	}
});

function showProductError(message) {
	const nameEl = document.querySelector("#product-name");
	if (nameEl) nameEl.textContent = "Không tìm thấy sản phẩm";

	const priceEl = document.querySelector("#product-price");
	if (priceEl) priceEl.textContent = "---";

	const specBody = document.querySelector("#spec-table tbody");
	if (specBody) {
		specBody.innerHTML = `<tr><td colspan="2">${TNC.escapeHtml(message)}</td></tr>`;
	}
}

function renderProductDetail(product) {
	const title = document.querySelector("#page-title");
	if (title) title.textContent = `${product.name} | TNC Store`;

	const breadcrumb = document.querySelector("#breadcrumb-product-name");
	if (breadcrumb) breadcrumb.textContent = product.name;

	const brandEl = document.querySelector("#product-brand");
	if (brandEl) {
		brandEl.textContent = product.brand || product.categoryName;
	}

	const skuEl = document.querySelector("#product-sku");
	if (skuEl) skuEl.textContent = product.slug;

	const nameEl = document.querySelector("#product-name");
	if (nameEl) nameEl.textContent = product.name;

	const modelEl = document.querySelector("#product-model");
	if (modelEl) modelEl.textContent = buildModelLine(product);

	const priceEl = document.querySelector("#product-price");
	if (priceEl) priceEl.textContent = product.priceText;

	// Giá gốc + % giảm: chỉ hiện khi backend trả thêm trường oldPrice (lớn hơn price).
	const oldPriceEl = document.querySelector("#product-old-price");
	const discountEl = document.querySelector("#product-discount");
	const oldPrice = Number(product.oldPrice) || 0;
	const price = Number(product.price) || 0;
	if (oldPriceEl && discountEl && oldPrice > price && price > 0) {
		oldPriceEl.textContent = TNC.formatPrice(oldPrice);
		discountEl.textContent = `-${Math.round((1 - price / oldPrice) * 100)}%`;
		oldPriceEl.hidden = false;
		discountEl.hidden = false;
	}

	renderStock(product);
	renderHighlights(product);
	renderGallery(product);
	renderSpecs(product);
	wireAddToCart(product);
	renderDescription(product);
	renderSimilar(product);
	renderRelatedNews();
}

function buildModelLine(product) {
	const parts = [];
	if (product.socket) parts.push(`Socket: ${product.socket}`);
	product.specs.slice(0, 2).forEach((spec) => parts.push(`${spec.label}: ${spec.value}`));

	return parts.join(" · ");
}

function renderStock(product) {
	const holder = document.querySelector("#product-stock");
	if (!holder) return;

	const stock = Number(product.stock) || 0;
	holder.className = `product-stock ${stock > 0 ? "is-in" : "is-out"}`;
	holder.textContent = stock > 0 ? `Còn hàng (${stock})` : "Hết hàng";
}

/** Danh sách cấu hình dạng gạch đầu dòng: hiện 5 dòng đầu, bấm "Xem thêm" để mở hết. */
function renderHighlights(product) {
	const box = document.querySelector("#product-highlights");
	const list = document.querySelector("#highlight-list");
	const toggle = document.querySelector("#highlight-toggle");
	if (!box || !list) return;

	const specs = product.specs || [];
	if (specs.length === 0) {
		box.hidden = true;
		return;
	}

	const VISIBLE = 5;
	list.innerHTML = specs
		.map((spec, index) => {
			const value = spec.unit ? `${spec.value} ${spec.unit}` : spec.value;
			const extra = index >= VISIBLE ? " is-extra" : "";
			return `<li class="${extra.trim()}"><span class="lbl">${TNC.escapeHtml(spec.label)}</span> ${TNC.escapeHtml(value)}</li>`;
		})
		.join("");
	box.hidden = false;

	if (!toggle) return;
	const hasMore = specs.length > VISIBLE;
	toggle.hidden = !hasMore;
	list.classList.toggle("is-collapsed", hasMore);
	toggle.textContent = "Xem thêm";
	toggle.onclick = () => {
		const collapsed = list.classList.toggle("is-collapsed");
		toggle.textContent = collapsed ? "Xem thêm" : "Thu gọn";
	};
}

function renderGallery(product) {
	const mainImg = document.querySelector("#main-product-img");
	const thumbsContainer = document.querySelector("#gallery-thumbs");
	const images = product.images?.length ? product.images : [product.image];

	if (mainImg) {
		mainImg.src = images[0];
		mainImg.alt = product.name;
	}

	thumbsContainer?.querySelectorAll(".product-thumb").forEach((thumb) => {
		thumb.replaceWith(thumb.cloneNode(true));
	});

	if (thumbsContainer) {
		thumbsContainer.innerHTML = images
			.map(
				(imgSrc, index) => `
					<img
						src="${TNC.escapeHtml(imgSrc)}"
						alt="${TNC.escapeHtml(product.name)} ${index + 1}"
						class="product-thumb ${index === 0 ? "active" : ""}"
						data-img-src="${TNC.escapeHtml(imgSrc)}"
					/>
				`,
			)
			.join("");

		thumbsContainer.querySelectorAll(".product-thumb").forEach((thumb) => {
			const switchImg = () => {
				thumbsContainer
					.querySelectorAll(".product-thumb")
					.forEach((other) => other.classList.remove("active"));
				thumb.classList.add("active");
				if (mainImg) mainImg.src = thumb.dataset.imgSrc;
			};
			thumb.addEventListener("click", switchImg);
			thumb.addEventListener("mouseenter", switchImg);
		});
	}

	const zoomContainer = mainImg?.closest(".product-image-zoom");
	if (mainImg && zoomContainer && !zoomContainer.dataset.zoomReady) {
		zoomContainer.dataset.zoomReady = "true";
		zoomContainer.addEventListener("mouseenter", () => {
			mainImg.style.transform = "scale(2)";
		});
		zoomContainer.addEventListener("mousemove", (event) => {
			const bounds = zoomContainer.getBoundingClientRect();
			const x = ((event.clientX - bounds.left) / bounds.width) * 100;
			const y = ((event.clientY - bounds.top) / bounds.height) * 100;
			mainImg.style.transformOrigin = `${x}% ${y}%`;
		});
		zoomContainer.addEventListener("mouseleave", () => {
			mainImg.style.transform = "";
			mainImg.style.transformOrigin = "center center";
		});
	}
}

function renderSpecs(product) {
	const specBody = document.querySelector("#spec-table tbody");
	if (!specBody) return;

	const rows = [
		{ label: "Hãng sản xuất", value: product.brand || "Đang cập nhật" },
		{ label: "Danh mục", value: product.categoryName },
	];
	if (product.socket) rows.push({ label: "Chuẩn Socket", value: product.socket });
	product.specs.forEach((spec) =>
		rows.push({ label: spec.label, value: spec.unit ? `${spec.value} ${spec.unit}` : spec.value }),
	);
	rows.push({ label: "Bảo hành", value: "36 Tháng" });

	specBody.innerHTML = rows
		.map(
			(row) => `
				<tr>
					<th>${TNC.escapeHtml(row.label)}</th>
					<td>${TNC.escapeHtml(row.value)}</td>
				</tr>
			`,
		)
		.join("");

	setupSpecToggle(rows.length);
}

/** Thu gọn bảng thông số khi dài hơn 8 dòng, bấm nút để xem đầy đủ. */
function setupSpecToggle(rowCount) {
	const wrap = document.querySelector("#spec-wrap");
	const button = document.querySelector("#spec-toggle");
	if (!wrap || !button) return;

	const collapsible = rowCount > 8;
	wrap.classList.toggle("is-collapsed", collapsible);
	button.hidden = !collapsible;
	button.textContent = "Xem thêm thông số";

	button.onclick = () => {
		const collapsed = wrap.classList.toggle("is-collapsed");
		button.textContent = collapsed ? "Xem thêm thông số" : "Thu gọn";
	};
}

/**
 * Mô tả dựng từ dữ liệu có sẵn (tên, hãng, danh mục, thông số, tồn kho),
 * vì bảng products chưa có cột mô tả riêng.
 */
function renderDescription(product) {
	const holder = document.querySelector("#product-description");
	if (!holder) return;

	const esc = TNC.escapeHtml;
	const brand = product.brand ? ` của thương hiệu ${esc(product.brand)}` : "";
	const stock = Number(product.stock) || 0;
	const highlights = (product.specs || [])
		.slice(0, 5)
		.map((spec) => `${esc(spec.label)}: ${esc(spec.unit ? `${spec.value} ${spec.unit}` : spec.value)}`)
		.join("; ");

	holder.innerHTML = `
		<p><strong>${esc(product.name)}</strong> là sản phẩm thuộc danh mục ${esc(product.categoryName)}${brand}, được TNC Store phân phối chính hãng.</p>
		${highlights ? `<p>Thông số nổi bật: ${highlights}.</p>` : ""}
		<p>${stock > 0 ? `Hiện còn ${stock} sản phẩm trong kho, sẵn sàng giao hàng.` : "Sản phẩm tạm hết hàng, vui lòng liên hệ để được tư vấn."}</p>
		<ul class="pd-desc-list">
			<li>Bảo hành chính hãng theo linh kiện.</li>
			<li>Miễn phí vận chuyển toàn quốc.</li>
			<li>Hỗ trợ 1 đổi 1 khi có lỗi từ nhà sản xuất.</li>
			<li>Hỗ trợ kỹ thuật trọn đời.</li>
		</ul>
	`;
}

/** Sản phẩm cùng danh mục; nếu không có thì lấy sản phẩm nổi bật để mục không bị trống. */
async function renderSimilar(product) {
	const section = document.querySelector("#similar-section");
	const holder = document.querySelector("#similar-products");
	const title = document.querySelector("#similar-title");
	if (!section || !holder) return;

	const others = (list) => (list || []).filter((item) => item.slug !== product.slug).slice(0, 4);

	try {
		let items = [];
		if (product.category) {
			const payload = await TNC.api.products({ category: product.category, limit: 5 });
			items = others(payload.data);
		}
		if (items.length === 0) {
			items = others(await TNC.api.featured(5));
			if (title) title.textContent = "Sản phẩm nổi bật";
		}
		if (items.length === 0) return;

		holder.innerHTML = items
			.map((item) => TNC.productCard(item, { columnClass: "col-6 col-lg-3", imageHeight: 140 }))
			.join("");
		section.hidden = false;
	} catch {
		// Lỗi tải sản phẩm tương tự thì bỏ qua, không ảnh hưởng trang chính.
	}
}

/** 4 bài viết mới nhất (CSDL chưa có liên kết sản phẩm - tin tức). */
async function renderRelatedNews() {
	const section = document.querySelector("#news-section");
	const holder = document.querySelector("#related-news");
	if (!section || !holder) return;

	try {
		const posts = (await TNC.api.news({ limit: 4 })) || [];
		if (posts.length === 0) return;

		const esc = TNC.escapeHtml;
		holder.innerHTML = posts
			.map((post) => {
				const url = `news-post.php?slug=${encodeURIComponent(post.slug)}`;
				const cover = post.coverImage || "assets/img/branding/tnc.png";
				return `
					<div class="col-6 col-lg-3 d-flex">
						<article class="pd-news-card w-100">
							<a href="${url}" class="pd-news-img"><img src="${esc(cover)}" alt="${esc(post.title)}" loading="lazy" /></a>
							<div class="pd-news-body">
								<span class="pd-news-cat">${esc(post.category)}</span>
								<h3><a href="${url}">${esc(post.title)}</a></h3>
								<small>${esc(post.date)}</small>
							</div>
						</article>
					</div>
				`;
			})
			.join("");
		section.hidden = false;
	} catch {
		// Lỗi tải tin tức thì bỏ qua.
	}
}

function wireAddToCart(product) {
	const button = document.querySelector("#btn-add-to-cart");
	if (!button) return;

	button.onclick = () => {
		const qtyInput = document.querySelector("#product-qty");
		const quantity = Math.max(1, parseInt(qtyInput?.value, 10) || 1);

		if (typeof addToCart === "function") {
			addToCart({
				id: product.slug,
				name: product.name,
				brand: product.brand || "TNC STORE",
				price: product.price,
				image: product.image,
				icon: `bi ${TNC_CATEGORY_ICONS[product.category] || "bi-box-seam"}`,
				quantity,
			});
		}

		const originalHtml = button.innerHTML;
		button.innerHTML = '<i class="bi bi-check2"></i> Đã thêm vào giỏ';
		button.classList.add("is-added");
		setTimeout(() => {
			button.innerHTML = originalHtml;
			button.classList.remove("is-added");
		}, 1500);
	};
}

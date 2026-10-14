/**
 * Trang chi tiết sản phẩm: dựng khối trên (ảnh · thông tin · cột phải) và
 * khối dưới (trái = đánh giá do js/reviews.js lo, phải = thông số).
 *
 * DOM và class bám theo tncstore.vn — đừng đổi tên id/class ở đây.
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

	const specBody = document.querySelector("#content-specifications");
	if (specBody) {
		specBody.innerHTML = `<table><tbody><tr><td class="att-name">Lỗi</td><td class="att-value">${TNC.escapeHtml(message)}</td></tr></tbody></table>`;
	}
}

/**
 * Breadcrumb 3 cấp như tncstore.vn: Trang chủ › danh mục cha › danh mục lá,
 * không có tên sản phẩm. Danh mục cha chỉ có với 8 danh mục lá thuộc 2 nhóm,
 * `vga` và `display` không có cha nên breadcrumb rút còn 2 cấp — đúng như trang gốc.
 * Bằng chứng và đối chiếu CSS: PLAN.md mục 5.10.
 */
function renderBreadcrumb(product) {
	const list = document.querySelector("#breadcrumb-list");
	if (!list) return;

	const home = `
		<li class="home">
			<a href="index.php" class="nopad-l">
				<span><i class="bi bi-house" aria-hidden="true"></i> Trang chủ</span>
			</a>
			<i class="bi bi-chevron-right" aria-hidden="true"></i>
		</li>`;

	const crumb = (name, slug) => `
		<li>
			<a href="products.php?category=${encodeURIComponent(slug)}"><span>${TNC.escapeHtml(name)}</span></a>
			<i class="bi bi-chevron-right" aria-hidden="true"></i>
		</li>`;

	const parent = product.parentCategory
		? crumb(product.parentCategoryName, product.parentCategory)
		: "";
	const leaf = product.category ? crumb(product.categoryName, product.category) : "";

	list.innerHTML = home + parent + leaf;
}

function renderProductDetail(product) {
	// js/reviews.js cần id sản phẩm để gọi API đánh giá.
	window.__tncProduct = product;
	document.dispatchEvent(new CustomEvent("tnc:product-loaded", { detail: { product } }));

	const title = document.querySelector("#page-title");
	if (title) title.textContent = `${product.name} | TNC Store`;

	renderBreadcrumb(product);

	const skuEl = document.querySelector("#product-sku");
	if (skuEl) skuEl.textContent = product.sku || product.slug;

	const nameEl = document.querySelector("#product-name");
	if (nameEl) nameEl.textContent = product.name;

	renderBrand(product);
	renderStock(product);
	renderGallery(product);
	renderProductSummary(product);
	renderDescription(product);
	renderPrice(product);
	renderSpecs(product);
	wireQuantity();
	wireBuyButtons(product);
	renderSimilar(product);
}

function renderBrand(product) {
	const link = document.querySelector("#product-brand-link");
	const logo = document.querySelector("#product-brand-logo");
	const name = document.querySelector("#product-brand-name");

	// Ưu tiên logo trong bảng brand_images; thiếu thì lùi về tên brand.
	if (product.brandImage && logo && link) {
		logo.src = product.brandImage;
		logo.alt = product.brand || "";
		link.href = `products.php?brand=${encodeURIComponent(product.brand || "")}`;
		link.hidden = false;
		if (name) name.hidden = true;
		return;
	}

	if (name && product.brand) {
		name.textContent = product.brand;
		name.hidden = false;
	}
	if (link) link.hidden = true;
}

/** DB chưa có cột mô tả nên luôn rơi vào trạng thái rỗng, markup lấy từ tncstore.vn. */
function renderDescription(product) {
	const content = document.querySelector("#content-desciption");
	const more = document.querySelector("#desc-more");
	const html = typeof product.description === "string" ? product.description.trim() : "";

	if (html !== "") {
		content.innerHTML = html;
		content.hidden = false;
		more.hidden = false;
		more.onclick = () => content.classList.toggle("active");
		return;
	}

	content.hidden = true;
	content.innerHTML = "";
	if (more) {
		more.hidden = true;
		more.onclick = null;
	}

	const existing = document.querySelector(".box-desciption .alert-mess");
	if (existing) existing.remove();
	const notice = document.createElement("h2");
	notice.className = "alert-mess";
	notice.textContent = "Mô tả sản phẩm đang được cập nhật";
	document.querySelector(".box-desciption")?.appendChild(notice);
}

function renderStock(product) {
	const holder = document.querySelector("#product-stock");
	if (!holder) return;

	const stock = Number(product.stock) || 0;
	holder.textContent = stock > 0 ? `Còn hàng (${stock})` : "Hết hàng";
	holder.style.color = stock > 0 ? "#28a745" : "var(--color-red)";
}

function renderGallery(product) {
	const mainImg = document.querySelector("#main-product-img");
	const thumbs = document.querySelector("#sync2");
	const images = product.images?.length ? product.images : [product.image];

	if (mainImg) {
		mainImg.src = images[0];
		mainImg.alt = product.name;
	}

	if (!thumbs) return;

	thumbs.innerHTML = images
		.map(
			(src, index) => `
				<a href="${TNC.escapeHtml(src)}" class="item${index === 0 ? " active" : ""}" data-img="${TNC.escapeHtml(src)}">
					<img src="${TNC.escapeHtml(src)}" alt="${TNC.escapeHtml(product.name)} ${index + 1}" />
				</a>
			`,
		)
		.join("");

	thumbs.querySelectorAll(".item").forEach((thumb) => {
		const switchImage = () => {
			thumbs.querySelectorAll(".item").forEach((other) => other.classList.remove("active"));
			thumb.classList.add("active");
			if (mainImg) mainImg.src = thumb.dataset.img;
		};
		thumb.addEventListener("click", (event) => {
			event.preventDefault();
			switchImage();
		});
		thumb.addEventListener("mouseenter", switchImage);
	});
}

/** Tóm tắt thông số dạng gạch đầu dòng: 6 dòng đầu hiện, còn lại ẩn sau "Xem thêm".
 *  Tên có tiền tố "Product" để không đụng `renderSummary` trong js/reviews.js. */
function renderProductSummary(product) {
	const list = document.querySelector("#show_summary");
	const toggle = document.querySelector("#summary-toggle");
	if (!list) return;

	const items = [];
	if (product.brand) items.push(`Hãng sản xuất: ${product.brand}`);
	if (product.model) items.push(`Model: ${product.model}`);
	if (product.socket) items.push(`Socket: ${product.socket}`);
	(product.specs || []).forEach((spec) => {
		items.push(`${spec.label}: ${spec.unit ? `${spec.value} ${spec.unit}` : spec.value}`);
	});

	if (items.length === 0) {
		list.innerHTML = "";
		if (toggle) toggle.hidden = true;
		return;
	}

	const VISIBLE = 6;
	list.innerHTML = items
		.map((text, index) => `<li class="item${index >= VISIBLE ? " hide" : ""}"><p>${TNC.escapeHtml(text)}</p></li>`)
		.join("");

	if (!toggle) return;
	toggle.hidden = items.length <= VISIBLE;
	toggle.textContent = "Xem thêm";
	toggle.onclick = () => {
		const collapsed = list.querySelectorAll("li.hide").length > 0;
		list.querySelectorAll("li.hide").forEach((li) => li.classList.toggle("hide", !collapsed));
		toggle.textContent = collapsed ? "Thu gọn" : "Xem thêm";
	};
}

function renderPrice(product) {
	const priceEl = document.querySelector("#product-price");
	if (priceEl) priceEl.textContent = product.priceText;

	const oldPriceEl = document.querySelector("#product-old-price");
	const discountEl = document.querySelector("#product-discount");
	const oldPrice = Number(product.listPrice) || 0;
	const price = Number(product.price) || 0;

	if (oldPriceEl && discountEl && oldPrice > price && price > 0) {
		oldPriceEl.textContent = TNC.formatPrice(oldPrice);
		discountEl.textContent = `-${Math.round((1 - price / oldPrice) * 100)}%`;
		oldPriceEl.hidden = false;
		discountEl.hidden = false;
	}
}

/** Bảng thông số: Hãng → Model → Socket → specs → Bảo hành (giữ thứ tự như cũ). */
function renderSpecs(product) {
	const holder = document.querySelector("#content-specifications");
	const toggle = document.querySelector("#spec-toggle");
	if (!holder) return;

	const rows = [{ label: "Hãng sản xuất", value: product.brand || "Đang cập nhật" }];
	if (product.model) rows.push({ label: "Model", value: product.model });
	if (product.socket) rows.push({ label: "Chuẩn Socket", value: product.socket });
	(product.specs || []).forEach((spec) =>
		rows.push({ label: spec.label, value: spec.unit ? `${spec.value} ${spec.unit}` : spec.value }),
	);
	rows.push({ label: "Bảo hành", value: "36 Tháng" });

	holder.innerHTML = `
		<table>
			<tbody>
				${rows
					.map(
						(row) => `
						<tr>
							<th class="att-name">${TNC.escapeHtml(row.label)}</th>
							<td class="att-value">${TNC.escapeHtml(row.value)}</td>
						</tr>
					`,
					)
					.join("")}
			</tbody>
		</table>
	`;

	if (!toggle) return;
	toggle.hidden = rows.length <= 8;
	toggle.onclick = () => {
		const collapsed = !holder.classList.contains("active");
		holder.classList.toggle("active", collapsed);
	};
}

function wireQuantity() {
	const input = document.querySelector("#js-buy-quantity");
	if (!input) return;

	document.querySelectorAll(".unit-detail-amount-control a").forEach((button) => {
		button.addEventListener("click", (event) => {
			event.preventDefault();
			const step = Number(button.dataset.value);
			const next = (parseInt(input.value, 10) || 1) + step;
			input.value = String(Math.max(1, next));
		});
	});

	input.addEventListener("change", () => {
		input.value = String(Math.max(1, parseInt(input.value, 10) || 1));
	});
}

function quantity() {
	return Math.max(1, parseInt(document.querySelector("#js-buy-quantity")?.value, 10) || 1);
}

function cartPayload(product) {
	return {
		productId: Number(product.id),
		id: product.slug,
		name: product.name,
		brand: product.brand || "TNC STORE",
		price: product.price,
		image: product.image,
		icon: `bi ${TNC_CATEGORY_ICONS[product.category] || "bi-box-seam"}`,
		quantity: quantity(),
	};
}

/** Trang gốc không có nút "Thêm vào giỏ" ở trang chi tiết, chỉ "Mua Ngay" + "Nhận tư vấn". */
function wireBuyButtons(product) {
	const buyBtn = document.querySelector("#js-buyNow");
	if (!buyBtn) return;

	buyBtn.addEventListener("click", (event) => {
		event.preventDefault();
		if (typeof addToCart === "function") {
			addToCart(cartPayload(product));
		}
		window.location.href = "checkout.php";
	});
}

async function renderSimilar(product) {
	const section = document.querySelector("#similar-section");
	const holder = document.querySelector("#js-product-similar");
	if (!section || !holder || !product.category) return;

	try {
		const payload = await TNC.api.products({ category: product.category, limit: 5 });
		const items = (payload.data || []).filter((item) => item.slug !== product.slug).slice(0, 4);
		if (items.length === 0) return;

		holder.innerHTML = items
			.map(
				(item) => `
					<div class="col-6 col-md-3">
						${TNC.productCard(item)}
					</div>
				`,
			)
			.join("");
		section.hidden = false;
	} catch {
		// Không có sản phẩm tương tự thì bỏ qua, không ảnh hưởng phần chính.
	}
}

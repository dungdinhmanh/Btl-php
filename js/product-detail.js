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
		brandEl.className = "badge bg-dark text-uppercase mb-2 align-self-start px-2 py-1";
	}

	const nameEl = document.querySelector("#product-name");
	if (nameEl) nameEl.textContent = product.name;

	const modelEl = document.querySelector("#product-model");
	if (modelEl) modelEl.textContent = buildModelLine(product);

	const priceEl = document.querySelector("#product-price");
	if (priceEl) priceEl.textContent = product.priceText;

	renderStock(product);
	renderGallery(product);
	renderSpecs(product);
	wireAddToCart(product);
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

	holder.className = "product-stock";
	holder.textContent = `Tồn kho: ${Number(product.stock) || 0}`;
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
}

function wireAddToCart(product) {
	const button = document.querySelector("#btn-add-to-cart");
	if (!button) return;

	button.onclick = () => {
		const qtyInput = document.querySelector("#product-qty");
		const quantity = Math.max(1, parseInt(qtyInput?.value, 10) || 1);

		if (typeof addToCart === "function") {
			addToCart({
				productId: Number(product.id),
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
		button.innerHTML = '<i class="bi bi-check2 me-2"></i>Đã thêm vào giỏ';
		button.classList.replace("btn-primary", "btn-success");
		setTimeout(() => {
			button.innerHTML = originalHtml;
			button.classList.replace("btn-success", "btn-primary");
		}, 1500);
	};
}

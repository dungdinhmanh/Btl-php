/**
 * Product detail page renderer.
 *
 * The source file, the model column, and the specification rows differ per product type,
 * so they are described in PRODUCT_SOURCES instead of branching on the type everywhere.
 *
 * Depends on: js/csv-parser.js.
 */

const PRODUCT_SOURCES = {
	cpu: {
		path: "assets/products/cpu/sources.csv",
		folder: "cpu",
		modelField: "Model",
		brandField: "Hãng",
		label: "CPU",
		icon: "bi bi-cpu",
	},
	mainboard: {
		path: "assets/products/mainboard/sources.csv",
		folder: "mainboard",
		modelField: "Tên sản phẩm",
		brandField: "Hãng",
		label: "Mainboard",
		icon: "bi bi-motherboard",
	},
	vga: {
		path: "assets/products/vga/products.csv",
		folder: "vga",
		modelField: "Dòng sản phẩm",
		brandField: "Hãng sản xuất",
		label: "VGA",
		icon: "bi bi-gpu-card",
	},
};

document.addEventListener("DOMContentLoaded", async () => {
	const params = new URLSearchParams(window.location.search);
	const targetParam = (params.get("model") || params.get("id") || params.get("slug") || "")
		.trim()
		.toLowerCase();

	const requestedType = (params.get("type") || "cpu").toLowerCase();
	const productType = PRODUCT_SOURCES[requestedType] ? requestedType : "cpu";
	const source = PRODUCT_SOURCES[productType];

	const productList = await fetchAndParseCSV(source.path);
	if (!productList || productList.length === 0) {
		console.warn(`Không tải được dữ liệu ${productType} từ ${source.path}`);
		renderMissingProduct();
		return;
	}

	// Match on the URL slug or on a partial model name. There is deliberately no
	// "first product" fallback: an unknown link must not show an unrelated product.
	const product = targetParam
		? productList.find((row) => {
				const model = row[source.modelField] || "";
				return (
					toSlug(model) === targetParam ||
					model.toLowerCase() === targetParam ||
					toSlug(model).includes(targetParam) ||
					model.toLowerCase().includes(targetParam)
				);
			})
		: null;

	if (!product) {
		renderMissingProduct();
		return;
	}

	renderProductDetail(product, productType);
});

/**
 * Renders an explicit empty state when the requested model is not in the catalog.
 */
function renderMissingProduct() {
	document.title = "Không tìm thấy sản phẩm | TNC Store";

	const setText = (selector, value) => {
		const element = document.querySelector(selector);
		if (element) element.textContent = value;
	};

	setText("#breadcrumb-product-name", "Không tìm thấy");
	setText("#product-name", "Không tìm thấy sản phẩm");
	setText("#product-model", "");
	setText("#product-price", "---");
	setText(
		"#product-desc",
		"Sản phẩm bạn đang tìm không tồn tại hoặc đã ngừng kinh doanh. Hãy quay lại danh mục sản phẩm để chọn sản phẩm khác.",
	);

	document.querySelector("#product-brand")?.remove();
	document.querySelector("#gallery-thumbs")?.replaceChildren();

	const specBody = document.querySelector("#spec-table tbody");
	if (specBody) {
		specBody.innerHTML =
			'<tr><td colspan="2" class="text-muted">Không có thông số để hiển thị.</td></tr>';
	}

	const addButton = document.querySelector("#btn-add-to-cart");
	if (addButton) addButton.disabled = true;

	document.querySelector("#product-qty")?.setAttribute("disabled", "disabled");
}

/** One-line summary shown under the product name. */
function productSummary(product, productType) {
	if (productType === "mainboard") {
		return `Socket: ${product.Socket} · ${product["Chuẩn RAM (DDR4/DDR5)"]} · ${product["Form factor (ATX/mATX/ITX)"]}`;
	}
	if (productType === "vga") {
		return `${product["Chipset (NVIDIA/AMD)"]} · ${product["VRAM (GB)"]}GB VRAM`;
	}
	return `Socket: ${product.Socket} · ${product["Số nhân/luồng"] || ""} · ${product["Xung nhịp"] || ""}`;
}

/** Marketing paragraph for the detail page. */
function productDescription(product, productType, model) {
	if (productType === "mainboard") {
		return `Bo mạch chủ ${product.Hãng} ${model}, chuẩn Socket ${product.Socket}, hỗ trợ ${product["Chuẩn RAM (DDR4/DDR5)"]} và kích thước ${product["Form factor (ATX/mATX/ITX)"]}.`;
	}

	if (productType === "vga") {
		return `Card màn hình ${product["Hãng sản xuất"]} ${model}, chip đồ họa ${product["Chipset (NVIDIA/AMD)"]}, dung lượng bộ nhớ ${product["VRAM (GB)"]}GB. Hàng chính hãng, bảo hành theo tiêu chuẩn của hãng.`;
	}

	const igpuText =
		product.iGPU && product.iGPU !== "Không có"
			? `Tích hợp nhân đồ họa ${product.iGPU}.`
			: "Sản phẩm không tích hợp sẵn nhân đồ họa, cần sử dụng kèm card màn hình rời (VGA).";

	return `Bộ vi xử lý ${product.Hãng} ${model} chuẩn Socket ${product.Socket}, cấu hình ${product["Số nhân/luồng"] || "đa nhân"}, tốc độ tối đa ${product["Xung nhịp"] || ""}, công suất tiêu thụ cơ bản ${product.TDP || "65W"}. ${igpuText}`;
}

/** Rows for the specification table. */
function productSpecs(product, productType, model) {
	if (productType === "mainboard") {
		return [
			{ label: "Hãng sản xuất", value: product.Hãng },
			{ label: "Model", value: model },
			{ label: "Chuẩn Socket", value: product.Socket },
			{ label: "Chuẩn RAM", value: product["Chuẩn RAM (DDR4/DDR5)"] },
			{ label: "Form factor", value: product["Form factor (ATX/mATX/ITX)"] },
			{ label: "Tình trạng", value: "Mới 100% - Chính hãng" },
			{ label: "Bảo hành", value: "36 Tháng" },
		];
	}

	if (productType === "vga") {
		return [
			{ label: "Hãng sản xuất", value: product["Hãng sản xuất"] },
			{ label: "Model", value: model },
			{ label: "Chipset đồ họa", value: product["Chipset (NVIDIA/AMD)"] },
			{ label: "Dung lượng VRAM", value: `${product["VRAM (GB)"]} GB` },
			{ label: "Tình trạng", value: "Mới 100% - Chính hãng" },
			{ label: "Bảo hành", value: "36 Tháng" },
		];
	}

	return [
		{ label: "Hãng sản xuất", value: product.Hãng },
		{ label: "Model", value: model },
		{ label: "Chuẩn Socket", value: product.Socket },
		{ label: "Số nhân / Số luồng", value: product["Số nhân/luồng"] || "---" },
		{ label: "Xung nhịp tối đa", value: product["Xung nhịp"] || "---" },
		{ label: "Điện năng tiêu thụ (TDP)", value: product.TDP || "---" },
		{ label: "Đồ họa tích hợp (iGPU)", value: product.iGPU || "Không có" },
		{ label: "Tình trạng", value: "Mới 100% - Chính hãng" },
		{ label: "Bảo hành", value: "36 Tháng" },
	];
}

function renderProductDetail(product, productType) {
	const source = PRODUCT_SOURCES[productType];
	const model = (product[source.modelField] || "").trim();
	const brand = (product[source.brandField] || "TNC Store").trim();
	const fullName = `${source.label} ${brand} ${model}`.replace(/\s+/g, " ").trim();
	const priceNum = Number((product["Giá TB (VNĐ)"] || "0").replace(/[^\d]/g, ""));
	const images = (product["Ảnh"] || "")
		.split("|")
		.map((image) => image.trim())
		.filter(Boolean)
		.map((image) => `assets/products/${source.folder}/${image}`);

	// 1. Tiêu đề trang & Breadcrumb
	const pageTitle = document.querySelector("#page-title");
	if (pageTitle) pageTitle.textContent = `${fullName} | TNC Store`;

	const breadcrumbName = document.querySelector("#breadcrumb-product-name");
	if (breadcrumbName) breadcrumbName.textContent = fullName;

	// 2. Thông tin chính
	const brandEl = document.querySelector("#product-brand");
	if (brandEl) {
		brandEl.textContent = brand;
		brandEl.className = "badge bg-dark text-uppercase mb-2 align-self-start px-2 py-1";
	}

	const nameEl = document.querySelector("#product-name");
	if (nameEl) nameEl.textContent = fullName;

	const modelEl = document.querySelector("#product-model");
	if (modelEl) modelEl.textContent = productSummary(product, productType);

	const priceEl = document.querySelector("#product-price");
	if (priceEl) priceEl.textContent = product["Giá TB (VNĐ)"] || "Liên hệ";

	// 3. Mô tả ngắn
	const descEl = document.querySelector("#product-desc");
	if (descEl) descEl.textContent = productDescription(product, productType, model);

	// 4. Gallery ảnh tương tác
	const mainImg = document.querySelector("#main-product-img");
	const thumbsContainer = document.querySelector("#gallery-thumbs");
	const zoomContainer = mainImg?.closest(".product-image-zoom");

	if (mainImg && zoomContainer) {
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

	if (images.length > 0) {
		if (mainImg) {
			mainImg.src = images[0];
			mainImg.alt = fullName;
		}

		if (thumbsContainer) {
			thumbsContainer.innerHTML = images
				.map(
					(imgSrc, index) => `
						<img
							src="${imgSrc}"
							alt="${fullName} ${index + 1}"
							class="product-thumb ${index === 0 ? "active" : ""}"
							data-img-src="${imgSrc}"
						/>
					`,
				)
				.join("");

			thumbsContainer.querySelectorAll(".product-thumb").forEach((thumb) => {
				const switchImg = () => {
					thumbsContainer
						.querySelectorAll(".product-thumb")
						.forEach((item) => item.classList.remove("active"));
					thumb.classList.add("active");
					if (mainImg) mainImg.src = thumb.dataset.imgSrc;
				};
				thumb.addEventListener("click", switchImg);
				thumb.addEventListener("mouseenter", switchImg);
			});
		}
	} else {
		if (mainImg) mainImg.src = "assets/img/branding/tnc.png";
		if (thumbsContainer) thumbsContainer.innerHTML = "";
	}

	// 5. Bảng thông số kỹ thuật
	const specTable = document.querySelector("#spec-table tbody");
	if (specTable) {
		specTable.innerHTML = productSpecs(product, productType, model)
			.map(
				(spec) => `
					<tr>
						<th>${spec.label}</th>
						<td>${spec.value ?? "---"}</td>
					</tr>
				`,
			)
			.join("");
	}

	// 6. Nút thêm vào giỏ hàng
	const addToCartBtn = document.querySelector("#btn-add-to-cart");
	if (addToCartBtn) {
		addToCartBtn.onclick = () => {
			const qtyInput = document.querySelector("#product-qty");
			const qty = Math.max(1, parseInt(qtyInput?.value, 10) || 1);

			if (typeof addToCart === "function") {
				addToCart({
					id: toSlug(`${productType}-${model}`) || Date.now().toString(),
					name: fullName,
					brand,
					price: priceNum,
					icon: source.icon,
					imageClass: "product-image",
					quantity: qty,
				});
			}

			const originalHtml = addToCartBtn.innerHTML;
			addToCartBtn.innerHTML = '<i class="bi bi-check2 me-2"></i>Đã thêm vào giỏ';
			addToCartBtn.classList.replace("btn-primary", "btn-success");
			setTimeout(() => {
				addToCartBtn.innerHTML = originalHtml;
				addToCartBtn.classList.replace("btn-success", "btn-primary");
			}, 1500);
		};
	}
}

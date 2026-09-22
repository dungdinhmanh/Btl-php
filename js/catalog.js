/**
 * Shared catalog module.
 *
 * Loads every product CSV, normalizes the rows into a single shape, and renders the
 * TNC-style product card used by the home page and the catalog page.
 *
 * Depends on: js/csv-parser.js (fetchAndParseCSV, toSlug).
 */

const PRODUCT_TYPES = {
	cpu: {
		file: "assets/products/cpu/sources.csv",
		folder: "cpu",
		icon: "bi bi-cpu",
		label: "CPU - Bộ xử lý",
	},
	mainboard: {
		file: "assets/products/mainboard/sources.csv",
		folder: "mainboard",
		icon: "bi bi-motherboard",
		label: "Mainboard - Bo mạch chủ",
	},
	vga: {
		file: "assets/products/vga/products.csv",
		folder: "vga",
		icon: "bi bi-gpu-card",
		label: "VGA - Card màn hình",
	},
};

/** CSV prices look like "3.200.000 đ"; the VGA file sometimes stores a bare number. */
function parseVnd(value) {
	return Number(String(value ?? "").replace(/[^\d]/g, "")) || 0;
}

function formatVnd(value) {
	return `${new Intl.NumberFormat("vi-VN").format(value)}đ`;
}

function escapeHtml(value) {
	return String(value ?? "").replace(
		/[&<>'"]/g,
		(character) =>
			({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[character],
	);
}

/** Stable 0..1 value derived from a string so demo badges never change on reload. */
function stableRatio(seed) {
	let hash = 7;
	for (let index = 0; index < seed.length; index += 1) {
		hash = (hash * 31 + seed.charCodeAt(index)) % 99991;
	}
	return hash / 99991;
}

/** Apache serves the PHP entry points, the static preview keeps the .html files. */
function storefrontPage(name) {
	const extension = window.location.pathname.toLowerCase().endsWith(".php") ? "php" : "html";
	return `${name}.${extension}`;
}

/** The Ảnh column stores "first.jpg|second.jpg". */
function firstImage(value) {
	return String(value ?? "").split("|")[0].trim();
}

/**
 * Adds the marketing fields the TNC card layout shows. The values are derived from
 * the model name instead of Math.random() so the catalog is stable between reloads.
 */
function decorateProduct(product) {
	const discounts = [5, 8, 10, 12, 15, 18, 20];
	const discount = discounts[Math.floor(stableRatio(`${product.type}-${product.model}`) * discounts.length)] || 10;

	return {
		...product,
		discount,
		oldPriceNum: Math.round(product.priceNum / (1 - discount / 100) / 1000) * 1000,
		oldPriceStr: formatVnd(Math.round(product.priceNum / (1 - discount / 100) / 1000) * 1000),
		stock: 1 + Math.floor(stableRatio(`${product.model}#stock`) * 23),
		rating: (4.5 + stableRatio(`${product.model}#rating`) * 0.5).toFixed(1),
		reviews: 3 + Math.floor(stableRatio(`${product.model}#reviews`) * 120),
		priceStr: formatVnd(product.priceNum),
		detailUrl: `${storefrontPage("product-detail")}?type=${product.type}&model=${encodeURIComponent(product.model)}`,
	};
}

function normalizeCpu(row) {
	const model = (row.Model || "").trim();
	return {
		type: "cpu",
		id: `cpu-${toSlug(model)}`,
		brand: (row.Hãng || "TNC Store").trim(),
		model,
		name: `CPU ${row.Hãng || ""} ${model}`.replace(/\s+/g, " ").trim(),
		spec: `Socket ${row.Socket || "---"}`,
		highlights: [row["Số nhân/luồng"], row["Xung nhịp"], row.TDP].filter(Boolean),
		image: firstImage(row.Ảnh) ? `assets/products/cpu/${firstImage(row.Ảnh)}` : "",
		priceNum: parseVnd(row["Giá TB (VNĐ)"]),
	};
}

function normalizeMainboard(row) {
	const model = (row["Tên sản phẩm"] || "").trim();
	return {
		type: "mainboard",
		id: `mainboard-${toSlug(model)}`,
		brand: (row.Hãng || "TNC Store").trim(),
		model,
		name: `Mainboard ${row.Hãng || ""} ${model}`.replace(/\s+/g, " ").trim(),
		spec: [row.Socket, row["Chuẩn RAM (DDR4/DDR5)"], row["Form factor (ATX/mATX/ITX)"]]
			.filter(Boolean)
			.join(" · "),
		highlights: [row["Chuẩn RAM (DDR4/DDR5)"], row["Form factor (ATX/mATX/ITX)"]].filter(Boolean),
		image: firstImage(row.Ảnh) ? `assets/products/mainboard/${firstImage(row.Ảnh)}` : "",
		priceNum: parseVnd(row["Giá TB (VNĐ)"]),
	};
}

function normalizeVga(row) {
	const model = (row["Dòng sản phẩm"] || "").trim();
	return {
		type: "vga",
		id: `vga-${toSlug(model)}`,
		brand: (row["Hãng sản xuất"] || "TNC Store").trim(),
		model,
		name: `VGA ${row["Hãng sản xuất"] || ""} ${model}`.replace(/\s+/g, " ").trim(),
		spec: [row["Chipset (NVIDIA/AMD)"], row["VRAM (GB)"] ? `${row["VRAM (GB)"]}GB VRAM` : ""]
			.filter(Boolean)
			.join(" · "),
		highlights: [row["Chipset (NVIDIA/AMD)"], row["VRAM (GB)"] ? `${row["VRAM (GB)"]}GB` : ""].filter(
			Boolean,
		),
		image: firstImage(row.Ảnh) ? `assets/products/vga/${firstImage(row.Ảnh)}` : "",
		priceNum: parseVnd(row["Giá TB (VNĐ)"]),
	};
}

const PRODUCT_NORMALIZERS = {
	cpu: normalizeCpu,
	mainboard: normalizeMainboard,
	vga: normalizeVga,
};

/** Loads and normalizes every product in the CSV catalog. */
async function loadCatalog() {
	const types = Object.keys(PRODUCT_TYPES);
	const rowsByType = await Promise.all(
		types.map((type) => fetchAndParseCSV(PRODUCT_TYPES[type].file, [])),
	);

	const products = [];
	types.forEach((type, index) => {
		(rowsByType[index] || []).forEach((row) => {
			const product = PRODUCT_NORMALIZERS[type](row);
			// Rows without a model or a price cannot be sold, so they are skipped.
			if (product.model && product.priceNum > 0) products.push(decorateProduct(product));
		});
	});

	return products;
}

/** One TNC-style product card. Keeps the hooks js/cart.js reads from the DOM. */
function productCardMarkup(product) {
	const meta = PRODUCT_TYPES[product.type] || PRODUCT_TYPES.cpu;
	// A few CSV rows have no image yet; fall back to a type icon instead of a logo.
	const media = product.image
		? `<img src="${product.image}" alt="${escapeHtml(product.name)}" loading="lazy" />`
		: `<i class="bi ${meta.icon} product-image-placeholder" aria-hidden="true"></i>`;
	const highlights = (product.highlights || [])
		.filter(Boolean)
		.slice(0, 2)
		.map((item) => `<span>${escapeHtml(item)}</span>`)
		.join("");

	return `
		<article
			class="product-card d-flex w-100 flex-column"
			data-product-id="${escapeHtml(product.id)}"
			data-product-name="${escapeHtml(product.name)}"
			data-product-brand="${escapeHtml(product.brand)}"
			data-product-price="${product.priceNum}"
			data-product-icon="${meta.icon}"
		>
			<a href="${product.detailUrl}" class="product-image" aria-label="${escapeHtml(product.name)}">
				${media}
				<span class="product-tag product-tag-sale">-${product.discount}%</span>
			</a>
			<p class="product-brand">${escapeHtml(product.brand)} · ${escapeHtml(product.spec)}</p>
			<h3><a href="${product.detailUrl}">${escapeHtml(product.name)}</a></h3>
			${highlights ? `<div class="product-highlights">${highlights}</div>` : ""}
			<div class="product-rating">
				${'<i class="bi bi-star-fill"></i>'.repeat(5)}
				<span>${product.rating} (${product.reviews})</span>
			</div>
			<div class="product-pricing">
				<strong class="product-price">${product.priceStr}</strong>
				<s class="product-old-price">${product.oldPriceStr}</s>
			</div>
			<p class="product-stock">Còn lại: <b>${product.stock}</b></p>
			<button class="btn btn-outline-primary w-100 mt-auto" type="button">
				<i class="bi bi-cart-plus me-2"></i>
				Thêm vào giỏ
			</button>
		</article>`;
}

/** Grid columns for the catalog page. */
function productGridMarkup(products, columns = "col-6 col-lg-4") {
	return products
		.map((product) => `<div class="${columns} d-flex">${productCardMarkup(product)}</div>`)
		.join("");
}

/** Horizontal scroller used by the home page rows, mirroring TNC's carousels. */
function productRailMarkup(products) {
	return products
		.map((product) => `<div class="rail-item">${productCardMarkup(product)}</div>`)
		.join("");
}

/** Deterministic "best pick" ordering so every row shows a spread of brands/prices. */
function pickProducts(products, { type, limit = 8, sort = "featured" } = {}) {
	const list = type ? products.filter((product) => product.type === type) : [...products];

	if (sort === "price-asc") list.sort((a, b) => a.priceNum - b.priceNum);
	else if (sort === "price-desc") list.sort((a, b) => b.priceNum - a.priceNum);
	else if (sort === "discount") list.sort((a, b) => b.discount - a.discount || a.priceNum - b.priceNum);
	else if (sort === "deal") {
		// A stable shuffle of the discounted products, so the deal row does not show the
		// same badge ten times in a row.
		list
			.filter((product) => product.discount >= 8)
			.sort((a, b) => stableRatio(`deal-${a.id}`) - stableRatio(`deal-${b.id}`));
		return list.slice(0, limit);
	}

	return list.slice(0, limit);
}

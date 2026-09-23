document.addEventListener("DOMContentLoaded", async () => {
	const container = document.querySelector("#featured-products-list");
	if (!container) return;
	const escapeHtml = (value) =>
		String(value ?? "").replace(/[&<>'"]/g, (character) =>
			({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[character],
		);
	try {
		const apiBase = window.TNC_API_BASE || "backend/api";
		const response = await fetch(`${apiBase}/products.php?featured=1&limit=4`);
		const payload = await response.json();
		if (response.ok && payload.ok && Array.isArray(payload.data) && payload.data.length) {
			container.innerHTML = payload.data
				.map((product) => {
					const name = escapeHtml(product.name);
					const brand = escapeHtml(product.brand || "TNC Store");
					const socket = escapeHtml(product.socket || "Liên hệ");
					const image = escapeHtml(product.image || "assets/img/branding/tnc.png");
					const url = `product-detail.php?slug=${encodeURIComponent(product.slug)}`;
					const price = new Intl.NumberFormat("vi-VN").format(Number(product.price) || 0);
					return `<div class="col-sm-6 col-lg-3 d-flex"><article class="product-card d-flex w-100 flex-column"><a href="${url}" class="product-image p-3 text-center bg-white d-block text-decoration-none"><img src="${image}" alt="${name}" class="img-fluid" style="height: 160px; object-fit: contain;"><span class="product-tag">Bán chạy</span></a><p class="product-brand">${brand} · ${socket}</p><h3><a href="${url}" class="text-decoration-none text-dark">${name}</a></h3><strong class="product-price">${price} đ</strong><button class="btn btn-outline-primary w-100 mt-auto" type="button"><i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ</button></article></div>`;
				})
				.join("");
			return;
		}
	} catch {
		// The API is intentionally optional until MySQL and its schema are available.
	}

	const cpuList = await fetchAndParseCSV("assets/products/cpu/sources.csv");
	if (!cpuList || cpuList.length === 0) return;

	const featured = cpuList.filter((p) => p.Ảnh && p.Ảnh.trim().length > 0).slice(0, 4);

	container.innerHTML = featured
		.map((p) => {
			const firstImg = p.Ảnh.split("|")[0].trim();
			const imgSrc = `assets/products/cpu/${firstImg}`;
			const detailUrl = `product-detail.php?model=${encodeURIComponent(p.Model)}`;
			const fullName = `CPU ${p.Hãng} ${p.Model}`;
			const price = p["Giá TB (VNĐ)"] || "Liên hệ";

			return `
				<div class="col-sm-6 col-lg-3 d-flex">
					<article class="product-card d-flex w-100 flex-column">
						<a href="${detailUrl}" class="product-image p-3 text-center bg-white d-block text-decoration-none">
							<img src="${imgSrc}" alt="${fullName}" class="img-fluid" style="height: 160px; object-fit: contain;">
							<span class="product-tag">Bán chạy</span>
						</a>
						<p class="product-brand">${p.Hãng} · Socket ${p.Socket}</p>
						<h3>
							<a href="${detailUrl}" class="text-decoration-none text-dark">${fullName}</a>
						</h3>								
						<strong class="product-price">${price}</strong>
						<button class="btn btn-outline-primary w-100 mt-auto" type="button">
							<i class="bi bi-cart-plus me-2"></i>
							Thêm vào giỏ
						</button>
					</article>
				</div>
			`;
		})
		.join("");
});

document.addEventListener("DOMContentLoaded", () => {
	if (typeof bootstrap === "undefined") return;

	const dragThreshold = 40;
	const carouselSelector = '.carousel[data-bs-ride="carousel"]';
	let startX = null;
	let dragged = false;

	document.addEventListener("pointerdown", (event) => {
		if (event.pointerType === "mouse" && event.button !== 0) return;
		if (!event.target.closest(carouselSelector)) return;
		startX = event.clientX;
		dragged = false;
	});

	document.addEventListener("pointermove", (event) => {
		if (startX === null) return;
		if (Math.abs(event.clientX - startX) > dragThreshold) dragged = true;
	});

	document.addEventListener("pointerup", (event) => {
		if (startX === null) return;
		const carousel = event.target.closest(carouselSelector);
		const direction = event.clientX - startX;
		const moved = dragged;
		startX = null;
		if (!carousel || !moved) return;
		const instance = bootstrap.Carousel.getOrCreateInstance(carousel);
		if (direction < 0) {
			instance.next();
		} else {
			instance.prev();
		}
	});

	document.addEventListener(
		"click",
		(event) => {
			if (!dragged) return;
			event.preventDefault();
			event.stopPropagation();
		},
		true,
	);
});

const PRODUCT_SOURCES = {
	cpu: { csv: "assets/products/cpu/sources.csv", dir: "cpu", fields: ["Hãng", "Model"], prefix: "CPU" },
	mainboard: {
		csv: "assets/products/mainboard/sources.csv",
		dir: "mainboard",
		fields: ["Hãng", "Tên sản phẩm"],
		prefix: "Mainboard",
	},
	vga: {
		csv: "assets/products/vga/products.csv",
		dir: "vga",
		fields: ["Hãng sản xuất", "Dòng sản phẩm"],
		prefix: "VGA",
	},
	case: { csv: "assets/products/case/products.csv", dir: "case", fields: ["Hãng", "Tên sản phẩm"], prefix: "" },
	display: {
		csv: "assets/products/display/products.csv",
		dir: "display",
		fields: ["Hãng", "Tên sản phẩm"],
		prefix: "",
	},
	psu: { csv: "assets/products/psu/products.csv", dir: "psu", fields: ["Hãng", "Tên sản phẩm"], prefix: "" },
	ram: { csv: "assets/products/ram/products.csv", dir: "ram", fields: ["Hãng", "Tên sản phẩm"], prefix: "" },
	SSD: { csv: "assets/products/SSD/products.csv", dir: "SSD", fields: ["Hãng", "Tên sản phẩm"], prefix: "" },
	"phụ kiện": {
		csv: "assets/products/phụ kiện/products.csv",
		dir: "phụ kiện",
		fields: ["Hãng", "Tên sản phẩm"],
		prefix: "",
	},
};

const escapeHtmlText = (value) =>
	String(value ?? "").replace(
		/[&<>'"]/g,
		(character) =>
			({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[character],
	);

document.addEventListener("DOMContentLoaded", async () => {
	const lists = [...document.querySelectorAll("[data-product-list]")];
	if (!lists.length) return;

	const cache = new Map();
	const loadFolder = (folder) => {
		const source = PRODUCT_SOURCES[folder];
		if (!source) return Promise.resolve([]);
		if (!cache.has(folder)) cache.set(folder, fetchAndParseCSV(source.csv, []));
		return cache.get(folder);
	};

	await Promise.all(
		lists.map(async (list) => {
			const row = list.closest("[data-group-folders]");
			const folders = (row ? row.dataset.groupFolders : "").split(",").filter(Boolean);
			if (!folders.length) return;
			const groups = await Promise.all(folders.map(loadFolder));
			const items = [];

			groups.forEach((rows, index) => {
				const source = PRODUCT_SOURCES[folders[index]];
				rows.slice(0, 8).forEach((row) => {
					const firstImage = (row["Ảnh"] || "").split("|")[0].trim();
					items.push({
						brand: row[source.fields[0]] || "",
						name: [source.prefix, row[source.fields[0]], row[source.fields[1]]]
							.filter(Boolean)
							.join(" "),
						image: firstImage
							? `assets/products/${source.dir}/${firstImage}`
							: "assets/img/branding/tnc.png",
						price: row["Giá TB (VNĐ)"] || "Liên hệ",
					});
				});
			});

			list.innerHTML = items
				.slice(0, 4)
				.map((item) => {
					const name = escapeHtmlText(item.name);
					const image = escapeHtmlText(item.image);
					const url = `product-detail.php?model=${encodeURIComponent(item.name)}`;
					return `<div class="col-sm-6 col-lg-3 d-flex"><article class="product-card d-flex w-100 flex-column"><a href="${url}" class="product-image p-3 text-center bg-white d-block text-decoration-none"><img src="${image}" alt="${name}" class="img-fluid" style="height: 130px; object-fit: contain;"></a><p class="product-brand">${escapeHtmlText(item.brand)}</p><h3><a href="${url}" class="text-decoration-none text-dark">${name}</a></h3><strong class="product-price">${escapeHtmlText(item.price)}</strong><button class="btn btn-outline-primary w-100 mt-auto" type="button"><i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ</button></article></div>`;
				})
				.join("");
		}),
	);
});
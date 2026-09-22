/**
 * Home page renderer.
 *
 * Renders the "DEAL GIỜ VÀNG" countdown and the category product rails that mirror the
 * rows on tncstore.vn. All card markup comes from js/catalog.js so the home page and the
 * catalog page stay in sync.
 *
 * Depends on: js/csv-parser.js, js/catalog.js.
 */

document.addEventListener("DOMContentLoaded", async () => {
	const rails = [...document.querySelectorAll("[data-rail]")];
	startDealCountdown(document.querySelector("[data-deal-timer]"));

	if (rails.length === 0) return;

	// MySQL-backed products take priority once the database is configured.
	// The CSV catalog below stays as the local-development fallback.
	const apiProducts = await fetchFeaturedFromApi();
	const products = await loadCatalog();

	if (products.length === 0 && apiProducts.length === 0) {
		rails.forEach((rail) => {
			rail.innerHTML = '<p class="rail-empty">Chưa tải được dữ liệu sản phẩm.</p>';
		});
		return;
	}

	const picks = {
		deal: () => pickProducts(products, { sort: "deal", limit: 10 }),
		vga: () => pickProducts(products, { type: "vga", limit: 10 }),
		cpu: () => pickProducts(products, { type: "cpu", limit: 10 }),
		mainboard: () => pickProducts(products, { type: "mainboard", limit: 10 }),
		featured: () => (apiProducts.length ? apiProducts : pickProducts(products, { limit: 10 })),
	};

	rails.forEach((rail) => {
		const pick = picks[rail.dataset.rail] || picks.featured;
		const items = pick();

		rail.innerHTML = items.length
			? productRailMarkup(items)
			: '<p class="rail-empty">Danh mục này đang được cập nhật.</p>';
		rail.removeAttribute("aria-busy");
	});
});

/**
 * Reads the featured products from the PHP/MySQL API. Returns an empty array when the
 * database is not configured yet, which is the normal state for local development.
 */
async function fetchFeaturedFromApi() {
	try {
		const apiBase = window.TNC_API_BASE || "backend/api";
		const response = await fetch(`${apiBase}/products.php?featured=1&limit=8`);
		const payload = await response.json();

		if (!response.ok || !payload.ok || !Array.isArray(payload.data)) return [];

		return payload.data.map((row) => {
			const product = decorateProduct({
				type: "api",
				id: `api-${row.slug || row.id}`,
				brand: row.brand || "TNC Store",
				model: row.name,
				name: row.name,
				spec: row.socket ? `Socket ${row.socket}` : "Chính hãng",
				highlights: [],
				image: row.image || "",
				priceNum: Number(row.price) || 0,
			});
			product.detailUrl = `${storefrontPage("product-detail")}?slug=${encodeURIComponent(row.slug || "")}`;
			return product;
		});
	} catch {
		// The API is intentionally optional until MySQL and its schema are available.
		return [];
	}
}

/**
 * Counts down to the end of the current three-hour window, matching the deal clock on the
 * original storefront (the window resets at 00:00, 03:00, 06:00 and so on).
 */
function startDealCountdown(timer) {
	if (!timer) return;

	const units = {
		hours: timer.querySelector('[data-deal-unit="hours"]'),
		minutes: timer.querySelector('[data-deal-unit="minutes"]'),
		seconds: timer.querySelector('[data-deal-unit="seconds"]'),
	};
	if (!units.hours || !units.minutes || !units.seconds) return;

	const pad = (value) => String(value).padStart(2, "0");

	const tick = () => {
		const now = new Date();
		const windowEnd = new Date(now);
		windowEnd.setHours(Math.floor(now.getHours() / 3) * 3 + 3, 0, 0, 0);

		const remaining = Math.max(0, Math.floor((windowEnd - now) / 1000));
		units.hours.textContent = pad(Math.floor(remaining / 3600));
		units.minutes.textContent = pad(Math.floor((remaining % 3600) / 60));
		units.seconds.textContent = pad(remaining % 60);
	};

	tick();
	setInterval(tick, 1000);
}

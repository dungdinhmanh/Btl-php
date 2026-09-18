/**
 * Home page dynamic renderer
 */

document.addEventListener("DOMContentLoaded", async () => {
	const container = document.querySelector("#featured-products-list");
	if (!container) return;

	const cpuList = await fetchAndParseCSV("assets/products/cpu/sources.csv");
	if (!cpuList || cpuList.length === 0) return;

	// Lấy 4 sản phẩm CPU tiêu biểu có ảnh
	const featured = cpuList.filter((p) => p.Ảnh && p.Ảnh.trim().length > 0).slice(0, 4);

	container.innerHTML = featured
		.map((p) => {
			const firstImg = p.Ảnh.split("|")[0].trim();
			const imgSrc = `assets/products/cpu/${firstImg}`;
			const detailUrl = `product-detail.html?model=${encodeURIComponent(p.Model)}`;
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

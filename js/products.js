/**
 * Product catalog dynamic renderer with filter, search, and sort
 */

document.addEventListener('DOMContentLoaded', async () => {
	const gridContainer = document.querySelector('#product-grid');
	const countEl = document.querySelector('#product-count');
	const sortSelect = document.querySelector('#sort-price');
	const searchParams = new URLSearchParams(window.location.search);
	const querySearch = (searchParams.get('q') || '').trim().toLowerCase();

	if (!gridContainer) return;

	// Nạp dữ liệu từ sources.csv
	const cpuList = await fetchAndParseCSV('assets/products/cpu/sources.csv');
	if (!cpuList || cpuList.length === 0) return;

	let products = cpuList.map((p) => {
		const firstImg = (p.Ảnh || '').split('|')[0].trim();
		const imgSrc = firstImg ? `assets/products/cpu/${firstImg}` : 'assets/img/branding/tnc.png';
		const fullName = `CPU ${p.Hãng} ${p.Model}`;
		const priceStr = p['Giá TB (VNĐ)'] || '0 đ';
		const priceNum = Number(priceStr.replace(/[^\d]/g, '')) || 0;

		return {
			...p,
			fullName,
			imgSrc,
			priceStr,
			priceNum,
			detailUrl: `product-detail.html?model=${encodeURIComponent(p.Model)}`,
		};
	});

	// Lọc theo từ khóa tìm kiếm nếu có
	if (querySearch) {
		products = products.filter(
			(p) =>
				p.fullName.toLowerCase().includes(querySearch) ||
				(p.Socket && p.Socket.toLowerCase().includes(querySearch)) ||
				(p.Hãng && p.Hãng.toLowerCase().includes(querySearch)),
		);
	}

	function render(list) {
		if (countEl) countEl.textContent = `${list.length} sản phẩm`;

		if (list.length === 0) {
			gridContainer.innerHTML = `
				<div class="col-12 py-5 text-center text-muted">
					<i class="bi bi-search fs-1 d-block mb-3"></i>
					<h5>Không tìm thấy sản phẩm phù hợp</h5>
					<p>Thử tìm kiếm với từ khóa khác hoặc bỏ các bộ lọc.</p>
				</div>
			`;
			return;
		}

		gridContainer.innerHTML = list
			.map(
				(p) => `
					<div class="col-sm-6 col-xl-4 d-flex">
						<article class="product-card d-flex w-100 flex-column">
							<a href="${p.detailUrl}" class="product-image p-3 text-center bg-white d-block text-decoration-none">
								<img src="${p.imgSrc}" alt="${p.fullName}" class="img-fluid" style="height: 150px; object-fit: contain;">
								${p.Ảnh ? '<span class="product-tag">Chính hãng</span>' : ''}
							</a>
							<p class="product-brand">${p.Hãng} · Socket ${p.Socket}</p>
							<h3>
								<a href="${p.detailUrl}" class="text-decoration-none text-dark">${p.fullName}</a>
							</h3>
							<strong class="product-price">${p.priceStr}</strong>
							<div class="mt-auto pt-2">
								<button class="btn btn-outline-primary w-100" type="button">
									<i class="bi bi-cart-plus me-2"></i>
									Thêm vào giỏ
								</button>
							</div>
						</article>
					</div>
				`,
			)
			.join('');
	}

	// Sắp xếp
	if (sortSelect) {
		sortSelect.addEventListener('change', () => {
			let sorted = [...products];
			if (sortSelect.value.includes('thấp đến cao')) {
				sorted.sort((a, b) => a.priceNum - b.priceNum);
			} else if (sortSelect.value.includes('cao đến thấp')) {
				sorted.sort((a, b) => b.priceNum - a.priceNum);
			}
			render(sorted);
		});
	}

	render(products);
});

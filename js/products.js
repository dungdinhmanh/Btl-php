/**
 * Product catalog dynamic renderer with filter, search, and sort
 */

document.addEventListener('DOMContentLoaded', async () => {
	const gridContainer = document.querySelector('#product-grid');
	const countEl = document.querySelector('#product-count');
	const sortSelect = document.querySelector('#sort-price');
	const categoryInputs = [...document.querySelectorAll('[data-category-filter]')];
	const searchParams = new URLSearchParams(window.location.search);
	const querySearch = (searchParams.get('q') || '').trim().toLowerCase();

	if (!gridContainer) return;

	const [cpuList, mainboardList] = await Promise.all([
		fetchAndParseCSV('assets/products/cpu/sources.csv'),
		fetchAndParseCSV('assets/products/mainboard/sources.csv', []),
	]);

	let products = cpuList.map((p) => {
		const firstImg = (p.Ảnh || '').split('|')[0].trim();
		const imgSrc = firstImg ? `assets/products/cpu/${firstImg}` : 'assets/img/branding/tnc.png';
		const fullName = `CPU ${p.Hãng} ${p.Model}`;
		const priceStr = p['Giá TB (VNĐ)'] || '0 đ';
		const priceNum = Number(priceStr.replace(/[^\d]/g, '')) || 0;

		return {
			...p,
			type: 'cpu',
			category: 'components',
			fullName,
			imgSrc,
			priceStr,
			priceNum,
			detailUrl: `product-detail.html?type=cpu&model=${encodeURIComponent(p.Model)}`,
		};
	});

	products = products.concat(
		mainboardList.map((p) => {
			const model = p['Tên sản phẩm'];
			const firstImg = (p.Ảnh || '').split('|')[0].trim();
			const imgSrc = firstImg
				? `assets/products/mainboard/${firstImg}`
				: 'assets/img/branding/tnc.png';
			const fullName = `Mainboard ${p.Hãng} ${model}`;
			const priceStr = p['Giá TB (VNĐ)'] || '0 đ';
			const priceNum = Number(priceStr.replace(/[^\d]/g, '')) || 0;

			return {
				...p,
				type: 'mainboard',
				category: 'components',
				model,
				fullName,
				imgSrc,
				priceStr,
				priceNum,
				detailUrl: `product-detail.html?type=mainboard&model=${encodeURIComponent(model)}`,
			};
		}),
	);

	if (products.length === 0) return;

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

	function applyFiltersAndSort() {
		const selectedCategories = categoryInputs.filter((input) => input.checked).map((input) => input.value);
		let visibleProducts = selectedCategories.length
			? products.filter((product) => selectedCategories.includes(product.category))
			: [...products];

		if (sortSelect?.value.includes('thấp đến cao')) {
			visibleProducts.sort((a, b) => a.priceNum - b.priceNum);
		} else if (sortSelect?.value.includes('cao đến thấp')) {
			visibleProducts.sort((a, b) => b.priceNum - a.priceNum);
		}

		render(visibleProducts);
	}

	categoryInputs.forEach((input) => {
		const count = products.filter((product) => product.category === input.value).length;
		const countEl = document.querySelector(`[data-category-count="${input.value}"]`);
		if (countEl) countEl.textContent = count;
		input.addEventListener('change', applyFiltersAndSort);
	});

	sortSelect?.addEventListener('change', applyFiltersAndSort);
	applyFiltersAndSort();
});

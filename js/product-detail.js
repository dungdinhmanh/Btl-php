/**
 * Dynamic product detail page renderer
 */

document.addEventListener('DOMContentLoaded', async () => {
	const params = new URLSearchParams(window.location.search);
	const targetParam = (params.get('model') || params.get('id') || params.get('slug') || '').trim().toLowerCase();

	const cpuList = await fetchAndParseCSV('assets/products/cpu/sources.csv');
	if (!cpuList || cpuList.length === 0) {
		console.warn('Không tải được dữ liệu CPU từ sources.csv');
		return;
	}

	// Tìm sản phẩm khớp hoặc mặc định lấy sản phẩm đầu tiên có ảnh
	let product = null;
	if (targetParam) {
		product = cpuList.find((p) => {
			const modelSlug = toSlug(p.Model);
			const modelLower = (p.Model || '').toLowerCase();
			return modelSlug === targetParam || modelLower === targetParam || modelSlug.includes(targetParam) || modelLower.includes(targetParam);
		});
	}

	if (!product) {
		// Mặc định chọn Core i5-12400F hoặc sản phẩm đầu tiên
		product = cpuList.find((p) => (p.Model || '').includes('12400F')) || cpuList[0];
	}

	renderProductDetail(product);
});

function renderProductDetail(product) {
	const fullName = `CPU ${product.Hãng} ${product.Model}`;
	const priceNum = Number((product['Giá TB (VNĐ)'] || '0').replace(/[^\d]/g, ''));
	const images = (product['Ảnh'] || '')
		.split('|')
		.map((img) => img.trim())
		.filter(Boolean)
		.map((img) => `assets/products/cpu/${img}`);

	// 1. Tiêu đề trang & Breadcrumb
	const pageTitle = document.querySelector('#page-title');
	if (pageTitle) pageTitle.textContent = `${fullName} | TNC Store`;

	const breadcrumbName = document.querySelector('#breadcrumb-product-name');
	if (breadcrumbName) breadcrumbName.textContent = fullName;

	// 2. Thông tin chính
	const brandEl = document.querySelector('#product-brand');
	if (brandEl) {
		brandEl.textContent = product.Hãng;
		brandEl.className = 'badge bg-dark text-uppercase mb-2 align-self-start px-2 py-1';
	}

	const nameEl = document.querySelector('#product-name');
	if (nameEl) nameEl.textContent = fullName;

	const modelEl = document.querySelector('#product-model');
	if (modelEl) {
		modelEl.textContent = `Socket: ${product.Socket} · ${product['Số nhân/luồng'] || ''} · ${product['Xung nhịp'] || ''}`;
	}

	const priceEl = document.querySelector('#product-price');
	if (priceEl) {
		priceEl.textContent = product['Giá TB (VNĐ)'] || 'Liên hệ';
	}

	// 3. Mô tả ngắn
	const descEl = document.querySelector('#product-desc');
	if (descEl) {
		const igpuText =
			product.iGPU && product.iGPU !== 'Không có'
				? `Tích hợp nhân đồ họa ${product.iGPU}.`
				: 'Sản phẩm không tích hợp sẵn nhân đồ họa, cần sử dụng kèm card màn hình rời (VGA).';

		descEl.textContent = `Bộ vi xử lý ${product.Hãng} ${product.Model} chuẩn Socket ${product.Socket}, cấu hình ${product['Số nhân/luồng'] || 'đa nhân'}, tốc độ tối đa ${product['Xung nhịp'] || ''}, công suất tiêu thụ cơ bản ${product.TDP || '65W'}. ${igpuText}`;
	}

	// 4. Gallery ảnh tương tác
	const mainImg = document.querySelector('#main-product-img');
	const thumbsContainer = document.querySelector('#gallery-thumbs');

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
							class="product-thumb ${index === 0 ? 'active' : ''}"
							data-img-src="${imgSrc}"
						/>
					`,
				)
				.join('');

			thumbsContainer.querySelectorAll('.product-thumb').forEach((thumb) => {
				const switchImg = () => {
					thumbsContainer.querySelectorAll('.product-thumb').forEach((t) => t.classList.remove('active'));
					thumb.classList.add('active');
					if (mainImg) mainImg.src = thumb.dataset.imgSrc;
				};
				thumb.addEventListener('click', switchImg);
				thumb.addEventListener('mouseenter', switchImg);
			});
		}
	} else {
		if (mainImg) mainImg.src = 'assets/img/branding/tnc.png';
		if (thumbsContainer) thumbsContainer.innerHTML = '';
	}

	// 5. Bảng thông số kỹ thuật (Specs table)
	const specTable = document.querySelector('#spec-table tbody');
	if (specTable) {
		const specs = [
			{ label: 'Hãng sản xuất', value: product.Hãng },
			{ label: 'Model', value: product.Model },
			{ label: 'Chuẩn Socket', value: product.Socket },
			{ label: 'Số nhân / Số luồng', value: product['Số nhân/luồng'] || '---' },
			{ label: 'Xung nhịp tối đa', value: product['Xung nhịp'] || '---' },
			{ label: 'Điện năng tiêu thụ (TDP)', value: product.TDP || '---' },
			{ label: 'Đồ họa tích hợp (iGPU)', value: product.iGPU || 'Không có' },
			{ label: 'Tình trạng', value: 'Mới 100% - Chính hãng' },
			{ label: 'Bảo hành', value: '36 Tháng' },
		];

		specTable.innerHTML = specs
			.map(
				(s) => `
					<tr>
						<th>${s.label}</th>
						<td>${s.value}</td>
					</tr>
				`,
			)
			.join('');
	}

	// 6. Xử lý nút Thêm vào giỏ hàng
	const addToCartBtn = document.querySelector('#btn-add-to-cart');
	if (addToCartBtn) {
		addToCartBtn.onclick = () => {
			const qtyInput = document.querySelector('#product-qty');
			const qty = Math.max(1, parseInt(qtyInput?.value, 10) || 1);

			const cartItem = {
				id: toSlug(product.Model) || Date.now().toString(),
				name: fullName,
				brand: product.Hãng || 'TNC STORE',
				price: priceNum,
				icon: 'bi bi-cpu',
				imageClass: 'product-image',
				quantity: qty,
			};

			// Gọi addToCart hoặc tự lưu nếu đã có cart.js
			if (typeof addToCart === 'function') {
				for (let i = 0; i < qty; i++) {
					addToCart({ ...cartItem, quantity: 1 });
				}
			}

			const originalHtml = addToCartBtn.innerHTML;
			addToCartBtn.innerHTML = '<i class="bi bi-check2 me-2"></i>Đã thêm vào giỏ';
			addToCartBtn.classList.replace('btn-primary', 'btn-success');
			setTimeout(() => {
				addToCartBtn.innerHTML = originalHtml;
				addToCartBtn.classList.replace('btn-success', 'btn-primary');
			}, 1500);
		};
	}
}

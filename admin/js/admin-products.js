const productTableBody = document.querySelector("#admin-product-table-body");
const productForm = document.querySelector("#product-form");
const productModalElement = document.querySelector("#productModal");
const productModal = productModalElement ? new bootstrap.Modal(productModalElement) : null;
const createButton = document.querySelector("[data-product-create]");

const getField = (name) => productForm?.elements.namedItem(name);

async function loadProducts() {
	if (!productTableBody) return;
	productTableBody.innerHTML = TNC.loadingRow(8, "Đang tải sản phẩm...");

	try {
		const response = await fetch("../backend/api/admin/products.php", {
			headers: { Accept: "application/json" },
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) {
			throw new Error(payload.message || "Không thể tải danh sách sản phẩm.");
		}
		renderProducts(payload.data);
	} catch (error) {
		productTableBody.innerHTML = `
			<tr>
				<td colspan="8" class="text-center text-danger py-4">${TNC.escapeHtml(error.message || "Không tải được dữ liệu.")}</td>
			</tr>
		`;
	}
}

function renderProducts(products) {
	if (!products || !products.length) {
		productTableBody.innerHTML = `
			<tr>
				<td colspan="8" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td>
			</tr>
		`;
		return;
	}

	productTableBody.innerHTML = products
		.map(
			(product) => `
				<tr>
					<td>#${product.id}</td>
					<td>
						<strong>${TNC.escapeHtml(product.name)}</strong><br />
						<small class="text-muted">${TNC.escapeHtml(product.slug)}</small>
					</td>
					<td>${TNC.escapeHtml(product.category)}</td>
					<td>${TNC.escapeHtml(product.brand)}</td>
					<td>${TNC.escapeHtml(product.priceText)}</td>
					<td>${TNC.escapeHtml(String(product.stock))}</td>
					<td>
						<span class="badge text-bg-${product.status === "active" ? "success" : product.status === "draft" ? "secondary" : "warning"}">
							${product.status === "active" ? "Đang bán" : product.status === "draft" ? "Bản nháp" : "Lưu trữ"}
						</span>
					</td>
					<td>
						<div class="d-flex gap-2">
							<button class="btn btn-sm btn-outline-primary" type="button" data-action="edit" data-id="${product.id}">Sửa</button>
							<button class="btn btn-sm btn-outline-danger" type="button" data-action="delete" data-id="${product.id}">Xoá</button>
						</div>
					</td>
				</tr>
			`,
		)
		.join("");
}

function resetForm() {
	if (!productForm) return;
	productForm.reset();
	productForm.dataset.mode = "create";
	productForm.dataset.id = "";
	productForm.querySelector('[name="product_status"]').value = "active";
	productForm.querySelector('[name="stock"]').value = 0;
	const title = document.querySelector("#productModalTitle");
	if (title) title.textContent = "Thêm sản phẩm";
}

function fillForm(product) {
	if (!productForm) return;
	productForm.dataset.mode = "edit";
	productForm.dataset.id = String(product.id);
	getField("name").value = product.name || "";
	getField("sku").value = product.sku || "";
	getField("slug").value = product.slug || "";
	getField("socket").value = product.socket || "";
	getField("category_id").value = product.categoryId || "";
	getField("brand_id").value = product.brandId || "";
	getField("unit_price").value = product.price || 0;
	getField("stock").value = product.stock || 0;
	getField("product_status").value = product.status || "draft";
	getField("is_featured").checked = Boolean(product.isFeatured);
	const title = document.querySelector("#productModalTitle");
	if (title) title.textContent = "Sửa sản phẩm";
}

function serializeForm() {
	if (!productForm) return {};
	const formData = new FormData(productForm);
	return {
		id: productForm.dataset.id ? Number(productForm.dataset.id) : undefined,
		name: String(formData.get("name") || "").trim(),
		sku: String(formData.get("sku") || "").trim(),
		slug: String(formData.get("slug") || "").trim(),
		socket: String(formData.get("socket") || "").trim(),
		category_id: Number(formData.get("category_id") || 0),
		brand_id: Number(formData.get("brand_id") || 0),
		unit_price: Number(formData.get("unit_price") || 0),
		stock: Number(formData.get("stock") || 0),
		product_status: String(formData.get("product_status") || "draft"),
		is_featured: formData.get("is_featured") === "1",
	};
}

async function submitProduct(event) {
	event.preventDefault();
	if (!productForm) return;
	const payload = serializeForm();
	if (!payload.name) {
		alert("Tên sản phẩm không được để trống.");
		return;
	}

	const method = productForm.dataset.mode === "edit" ? "PUT" : "POST";
	const requestBody = method === "PUT" ? JSON.stringify(payload) : JSON.stringify(payload);

	try {
		const response = await fetch("../backend/api/admin/products.php", {
			method,
			headers: { "Content-Type": "application/json", Accept: "application/json" },
			body: requestBody,
		});
		const result = await response.json();
		if (!response.ok || !result.ok) {
			throw new Error(result.message || "Không thể lưu sản phẩm.");
		}
		if (productModal) productModal.hide();
		resetForm();
		await loadProducts();
	} catch (error) {
		alert(error.message || "Có lỗi xảy ra khi lưu sản phẩm.");
	}
}

async function deleteProductById(productId) {
	if (!window.confirm("Bạn có chắc chắn muốn xoá sản phẩm này?")) {
		return;
	}

	try {
		const response = await fetch(`../backend/api/admin/products.php?id=${productId}`, {
			method: "DELETE",
			headers: { Accept: "application/json" },
		});
		const result = await response.json();
		if (!response.ok || !result.ok) {
			throw new Error(result.message || "Không thể xoá sản phẩm.");
		}
		await loadProducts();
	} catch (error) {
		alert(error.message || "Có lỗi xảy ra khi xoá sản phẩm.");
	}
}

createButton?.addEventListener("click", () => {
	resetForm();
	if (productModal) productModal.show();
});

productForm?.addEventListener("submit", submitProduct);

document.addEventListener("click", async (event) => {
	const target = event.target.closest("[data-action]");
	if (!target) return;
	const action = target.dataset.action;
	const productId = Number(target.dataset.id || 0);
	if (!productId) return;

	if (action === "edit") {
		try {
			const response = await fetch(`../backend/api/admin/products.php`, {
				headers: { Accept: "application/json" },
			});
			const payload = await response.json();
			const product = (payload.data || []).find((item) => Number(item.id) === productId);
			if (!product) {
				throw new Error("Không tìm thấy sản phẩm.");
			}
			fillForm(product);
			if (productModal) productModal.show();
		} catch (error) {
			alert(error.message || "Không thể tải thông tin sản phẩm.");
		}
	}

	if (action === "delete") {
		await deleteProductById(productId);
	}
});

resetForm();
loadProducts();

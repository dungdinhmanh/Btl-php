const customerTableBody = document.querySelector("#customer-table-body");
const customerStats = document.querySelector("#customer-stats");
const customerFilters = document.querySelector("#customer-filters");
const customerPageLabel = document.querySelector("#customer-page-label");
const customerModalElement = document.querySelector("#customerDetailModal");
const customerModal = customerModalElement ? new bootstrap.Modal(customerModalElement) : null;
let customerPage = 1;
let customerPageCount = 1;

function renderCustomerStats(stats) {
	const cards = [
		{ icon: "bi-people", label: "Tổng khách hàng", value: stats.total },
		{ icon: "bi-person-check", label: "Đang hoạt động", value: stats.active },
		{ icon: "bi-person-x", label: "Đã vô hiệu hóa", value: stats.disabled },
		{ icon: "bi-person-dash", label: "Khách tự xóa", value: stats.deleted },
		{ icon: "bi-person-plus", label: "Đăng ký tháng này", value: stats.newThisMonth },
	];

	customerStats.innerHTML = cards
		.map(
			(card) => `
				<div>
					<span class="admin-stat-icon"><i class="bi ${card.icon}"></i></span>
					<p>${TNC.escapeHtml(card.label)}</p>
					<strong>${Number(card.value || 0).toLocaleString("vi-VN")}</strong>
				</div>
			`,
		)
		.join("");
}

function renderCustomers(result) {
	const { items, total, page, perPage } = result;
	customerPage = page;
	customerPageCount = Math.max(1, Math.ceil(total / perPage));
	customerPageLabel.textContent = total
		? `Hiển thị ${(page - 1) * perPage + 1}-${Math.min(page * perPage, total)} trong ${total} khách hàng`
		: "Không có khách hàng phù hợp.";
	document.querySelectorAll("[data-page-change]").forEach((button) => {
		button.disabled = page + Number(button.dataset.pageChange) < 1 || page + Number(button.dataset.pageChange) > customerPageCount;
	});

	if (!items.length) {
		customerTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Chưa có khách hàng nào.</td></tr>';
		return;
	}

	customerTableBody.innerHTML = items
		.map((customer) => {
			const active = customer.status === "active";
			const statusLabel = active ? "Hoạt động" : customer.status === "deleted" ? "Khách tự xóa" : "Đã khóa";
			const nextStatus = active ? "disabled" : "active";
			const statusAction = customer.status === "deleted" ? "Khôi phục" : active ? "Khóa" : "Mở khóa";
			return `
				<tr>
					<td>#${customer.id}</td>
					<td><strong>${TNC.escapeHtml(customer.name)}</strong><br><small class="text-muted">${TNC.escapeHtml(customer.email)}</small></td>
					<td>${TNC.escapeHtml(customer.phone || "Chưa cập nhật")}</td>
					<td>${TNC.escapeHtml(customer.joinedAt)}</td>
					<td>${Number(customer.orderCount).toLocaleString("vi-VN")}</td>
					<td><span class="badge text-bg-${active ? "success" : customer.status === "deleted" ? "danger" : "secondary"}">${statusLabel}</span></td>
					<td><div class="d-flex flex-wrap gap-2">
						<button class="btn btn-sm btn-outline-primary" type="button" data-customer-action="detail" data-id="${customer.id}" aria-label="Xem khách hàng ${TNC.escapeHtml(customer.name)}"><i class="bi bi-eye"></i></button>
						<button class="btn btn-sm btn-outline-secondary" type="button" data-customer-action="status" data-id="${customer.id}" data-status="${nextStatus}">${statusAction}</button>
						<button class="btn btn-sm btn-outline-danger" type="button" data-customer-action="delete" data-id="${customer.id}" aria-label="Xóa vĩnh viễn ${TNC.escapeHtml(customer.name)}"><i class="bi bi-trash3"></i></button>
					</div></td>
				</tr>
			`;
		})
		.join("");
}

async function loadCustomers() {
	customerTableBody.innerHTML = TNC.loadingRow(7, "Đang tải khách hàng...");
	const formData = new FormData(customerFilters);
	const params = new URLSearchParams({
		search: String(formData.get("search") || "").trim(),
		status: String(formData.get("status") || "all"),
		registeredFrom: String(formData.get("registeredFrom") || ""),
		registeredTo: String(formData.get("registeredTo") || ""),
		page: String(customerPage),
	});

	try {
		const response = await fetch(`../backend/api/admin/customers.php?${params}`, {
			headers: { Accept: "application/json" },
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) throw new Error(payload.message || "Không thể tải khách hàng.");
		renderCustomerStats(payload.data.stats);
		renderCustomers(payload.data.customers);
	} catch (error) {
		customerTableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">${TNC.escapeHtml(error.message || "Không tải được dữ liệu.")}</td></tr>`;
		customerPageLabel.textContent = "";
	}
}

async function showCustomerDetail(customerId) {
	const body = document.querySelector("#customer-detail-body");
	body.innerHTML = '<p class="text-muted mb-0">Đang tải thông tin...</p>';
	customerModal.show();
	try {
		const response = await fetch(`../backend/api/admin/customers.php?id=${customerId}`, {
			headers: { Accept: "application/json" },
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) throw new Error(payload.message || "Không thể tải khách hàng.");
		const customer = payload.data;
		const addressText = customer.address
			? [customer.address.recipientName, customer.address.recipientPhone, customer.address.text]
				.map(TNC.escapeHtml)
				.join(", ")
			: "Chưa có địa chỉ lưu";
		document.querySelector("#customer-detail-title").textContent = customer.name;
		body.innerHTML = `
			<dl class="row mb-4">
				<dt class="col-sm-3">Email</dt><dd class="col-sm-9">${TNC.escapeHtml(customer.email)}</dd>
				<dt class="col-sm-3">Điện thoại</dt><dd class="col-sm-9">${TNC.escapeHtml(customer.phone || "Chưa cập nhật")}</dd>
				<dt class="col-sm-3">Ngày tham gia</dt><dd class="col-sm-9">${TNC.escapeHtml(customer.joinedAt)}</dd>
				<dt class="col-sm-3">Trạng thái</dt><dd class="col-sm-9">${customer.status === "active" ? "Đang hoạt động" : customer.status === "deleted" ? "Khách đã tự xóa" : "Đã vô hiệu hóa"}</dd>
				<dt class="col-sm-3">Địa chỉ</dt><dd class="col-sm-9">${addressText}</dd>
			</dl>
			<h3 class="h6">Đơn hàng gần đây</h3>
			<div class="table-responsive"><table class="table table-sm align-middle mb-0">
				<thead><tr><th>Mã đơn</th><th>Ngày đặt</th><th>Trạng thái</th><th class="text-end">Tổng tiền</th></tr></thead>
				<tbody>${customer.orders.length ? customer.orders.map((order) => `
					<tr><td>${TNC.escapeHtml(order.number)}</td><td>${TNC.escapeHtml(order.placedAt)}</td><td>${TNC.escapeHtml(order.status)}</td><td class="text-end">${Number(order.total).toLocaleString("vi-VN")}đ</td></tr>
				`).join("") : '<tr><td colspan="4" class="text-center text-muted py-3">Chưa có đơn hàng.</td></tr>'}</tbody>
			</table></div>
		`;
	} catch (error) {
		body.innerHTML = `<p class="text-danger mb-0">${TNC.escapeHtml(error.message || "Không thể tải thông tin.")}</p>`;
	}
}

async function updateCustomer(customerId, action, status = "") {
	if (action === "delete" && !window.confirm("Xóa vĩnh viễn tài khoản này? Đơn hàng sẽ được giữ lại nhưng không thể khôi phục tài khoản.")) return;
	const method = action === "delete" ? "DELETE" : "PUT";
	try {
		const response = await fetch(`../backend/api/admin/customers.php?id=${customerId}`, {
			method,
			headers: { Accept: "application/json", "Content-Type": "application/json" },
			...(method === "PUT" ? { body: JSON.stringify({ id: customerId, status }) } : {}),
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) throw new Error(payload.message || "Không thể cập nhật khách hàng.");
		await loadCustomers();
	} catch (error) {
		window.alert(error.message || "Có lỗi xảy ra.");
	}
}

customerFilters.addEventListener("submit", (event) => {
	event.preventDefault();
	customerPage = 1;
	loadCustomers();
});

document.addEventListener("click", (event) => {
	const actionButton = event.target.closest("[data-customer-action]");
	if (actionButton) {
		const { customerAction, id, status } = actionButton.dataset;
		if (customerAction === "detail") showCustomerDetail(Number(id));
		if (customerAction === "status" || customerAction === "delete") updateCustomer(Number(id), customerAction, status);
		return;
	}

	const pageButton = event.target.closest("[data-page-change]");
	if (pageButton) {
		customerPage += Number(pageButton.dataset.pageChange);
		loadCustomers();
	}
});

loadCustomers();
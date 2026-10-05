const orderTableBody = document.querySelector("#admin-order-table-body");
const orderForm = document.querySelector("#order-form");
const orderModalElement = document.querySelector("#orderModal");
const orderModal = orderModalElement ? new bootstrap.Modal(orderModalElement) : null;
const statusSelect = orderForm?.querySelector('[name="status_code"]');

const STATUS_TEXT = {
	pending: "Chờ xác nhận",
	confirmed: "Đã xác nhận",
	shipping: "Đang giao",
	completed: "Hoàn tất",
	cancelled: "Đã huỷ",
};

async function loadOrders() {
	if (!orderTableBody) return;
	orderTableBody.innerHTML = TNC.loadingRow(6, "Đang tải đơn hàng...");

	try {
		const response = await fetch("../backend/api/admin/orders.php", {
			headers: { Accept: "application/json" },
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) {
			throw new Error(payload.message || "Không thể tải danh sách đơn hàng.");
		}
		renderStatuses(payload.data.statuses);
		renderOrders(payload.data.orders);
	} catch (error) {
		orderTableBody.innerHTML = `
			<tr>
				<td colspan="6" class="text-center text-danger py-4">${TNC.escapeHtml(error.message || "Không tải được dữ liệu.")}</td>
			</tr>
		`;
	}
}

function renderStatuses(statuses) {
	if (!statusSelect) return;
	statusSelect.innerHTML = (statuses || [])
		.map((status) => `<option value="${TNC.escapeHtml(status.code)}">${TNC.escapeHtml(status.name)}</option>`)
		.join("");
}

function renderOrders(orders) {
	if (!orders || !orders.length) {
		orderTableBody.innerHTML = `
			<tr>
				<td colspan="6" class="text-center text-muted py-4">Chưa có đơn hàng nào.</td>
			</tr>
		`;
		return;
	}

	orderTableBody.innerHTML = orders
		.map(
			(order) => `
				<tr>
					<td>#${TNC.escapeHtml(order.number)}</td>
					<td>
						<strong>${TNC.escapeHtml(order.customerName)}</strong><br />
						<small class="text-muted">${TNC.escapeHtml(order.customerEmail || "Chưa có email")}</small>
					</td>
					<td>${TNC.escapeHtml(order.date)}</td>
					<td>${TNC.escapeHtml(order.totalText)}</td>
					<td>
						<span class="badge text-bg-${order.statusCode === "completed" ? "success" : order.statusCode === "shipping" ? "warning" : order.statusCode === "cancelled" ? "danger" : "secondary"}">
							${TNC.escapeHtml(STATUS_TEXT[order.statusCode] || order.statusName)}
						</span>
					</td>
					<td>
						<button class="btn btn-sm btn-outline-primary" type="button" data-order-action="edit" data-order-id="${order.id}">Cập nhật</button>
					</td>
				</tr>
			`,
		)
		.join("");
}

function openStatusModal(orderId, statusCode) {
	if (!orderForm || !statusSelect || !orderModal) return;
	orderForm.dataset.orderId = String(orderId);
	statusSelect.value = statusCode || "pending";
	orderModal.show();
}

async function saveOrderStatus(event) {
	event.preventDefault();
	if (!orderForm) return;

	const orderId = Number(orderForm.dataset.orderId || 0);
	const statusCode = statusSelect.value;

	try {
		const response = await fetch("../backend/api/admin/orders.php", {
			method: "PUT",
			headers: { "Content-Type": "application/json", Accept: "application/json" },
			body: JSON.stringify({ id: orderId, status_code: statusCode }),
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) {
			throw new Error(payload.message || "Không thể cập nhật trạng thái.");
		}
		if (orderModal) orderModal.hide();
		await loadOrders();
	} catch (error) {
		alert(error.message || "Có lỗi xảy ra khi cập nhật trạng thái.");
	}
}

document.addEventListener("click", async (event) => {
	const target = event.target.closest("[data-order-action]");
	if (!target) return;
	const orderId = Number(target.dataset.orderId || 0);
	if (!orderId) return;

	try {
		const response = await fetch("../backend/api/admin/orders.php", {
			headers: { Accept: "application/json" },
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) {
			throw new Error(payload.message || "Không thể tải thông tin đơn hàng.");
		}
		const order = (payload.data.orders || []).find((item) => Number(item.id) === orderId);
		if (!order) {
			throw new Error("Không tìm thấy đơn hàng.");
		}
		openStatusModal(orderId, order.statusCode);
	} catch (error) {
		alert(error.message || "Không thể mở đơn hàng.");
	}
});

orderForm?.addEventListener("submit", saveOrderStatus);
loadOrders();

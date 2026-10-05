const adminBody = document.querySelector(".admin-body");
const sidebarToggle = document.querySelector(".admin-sidebar-toggle");
const sidebarStorageKey = "tnc-admin-sidebar-collapsed";

function setSidebarCollapsed(collapsed) {
	adminBody.classList.toggle("sidebar-collapsed", collapsed);
	sidebarToggle.setAttribute("aria-expanded", String(!collapsed));
	sidebarToggle.setAttribute("aria-label", collapsed ? "Mở rộng thanh bên" : "Thu gọn thanh bên");
	sidebarToggle.querySelector("span").textContent = collapsed ? "Mở rộng" : "Thu gọn";
	sidebarToggle.querySelector("i").className = collapsed
		? "bi bi-layout-sidebar"
		: "bi bi-layout-sidebar-inset";
}

setSidebarCollapsed(localStorage.getItem(sidebarStorageKey) === "true");

sidebarToggle.addEventListener("click", () => {
	const collapsed = !adminBody.classList.contains("sidebar-collapsed");
	setSidebarCollapsed(collapsed);
	localStorage.setItem(sidebarStorageKey, String(collapsed));
});

const ORDER_STATUS_CLASSES = {
	completed: "status-success",
	shipping: "status-shipping",
	pending: "status-pending",
	confirmed: "status-pending",
	cancelled: "status-pending",
};

const formatCount = (value) => Number(value || 0).toLocaleString("vi-VN");

async function loadDashboard() {
	TNC.showLoading(document.querySelector("#admin-stats"), "Đang tải số liệu...", "admin-loading");
	TNC.showLoading(
		document.querySelector("#admin-low-stock"),
		"Đang tải kho hàng...",
		"text-muted small py-3",
	);
	TNC.showLoading(
		document.querySelector("#admin-product-status"),
		"Đang tải trạng thái sản phẩm...",
		"text-muted small py-3",
	);

	const ordersBody = document.querySelector("#admin-recent-orders");
	if (ordersBody) ordersBody.innerHTML = TNC.loadingRow(5, "Đang tải đơn hàng...");

	try {
		const data = await TNC.api.dashboard();
		renderStats(data.stats);
		renderProductStatus(data.productStatus);
		renderLowStock(data.lowStock);
		renderOrders(data.recentOrders);
	} catch (error) {
		const holder = document.querySelector("#admin-stats");
		if (holder) {
			holder.innerHTML = `<div class="summary-empty">${TNC.escapeHtml(
				error?.message || "Không tải được dữ liệu.",
			)}</div>`;
		}
	}
}

function renderStats(stats) {
	const holder = document.querySelector("#admin-stats");
	if (!holder || !stats) return;

	const cards = [
		{
			icon: "bi-currency-dollar",
			label: "Doanh thu tháng này",
			value: stats.revenueThisMonthText,
			note: `${formatCount(stats.ordersTotal)} đơn hàng`,
		},
		{
			icon: "bi-bag-check",
			label: "Đơn hàng mới",
			value: formatCount(stats.ordersThisMonth),
			note: "Trong tháng này",
		},
		{
			icon: "bi-people",
			label: "Khách hàng mới",
			value: formatCount(stats.customersThisMonth),
			note: `${formatCount(stats.customersTotal)} khách hàng`,
		},
		{
			icon: "bi-box",
			label: "Sản phẩm đang bán",
			value: formatCount(stats.activeProducts),
			note: `${formatCount(stats.lowStockCount)} sản phẩm sắp hết`,
		},
	];

	holder.innerHTML = cards
		.map(
			(card) => `
				<div>
					<span class="admin-stat-icon"><i class="bi ${card.icon}"></i></span>
					<p>${TNC.escapeHtml(card.label)}</p>
					<strong>${TNC.escapeHtml(card.value)}</strong>
					<small class="neutral">${TNC.escapeHtml(card.note)}</small>
				</div>
			`,
		)
		.join("");
}

function renderLowStock(items) {
	const holder = document.querySelector("#admin-low-stock");
	if (!holder) return;

	if (!items || !items.length) {
		holder.innerHTML = '<p class="summary-empty">Không có sản phẩm nào sắp hết hàng.</p>';
		return;
	}

	holder.innerHTML = items
		.map(
			(item) => `
				<div class="stock-row">
					<span class="stock-product">
						<i class="bi bi-box-seam"></i>
						<span>
							${TNC.escapeHtml(item.name)}
							<small>${TNC.escapeHtml(item.brand || "TNC Store")}</small>
						</span>
					</span>
					<strong>${formatCount(item.available)}</strong>
				</div>
			`,
		)
		.join("");
}

function renderProductStatus(statusMap) {
	const holder = document.querySelector("#admin-product-status");
	if (!holder) return;

	const items = [
		{ key: "active", label: "Đang bán", icon: "bi-check-circle" },
		{ key: "draft", label: "Bản nháp", icon: "bi-pencil-square" },
		{ key: "archived", label: "Lưu trữ", icon: "bi-archive" },
	];

	const safeMap = statusMap || {};

	holder.innerHTML = items
		.map(
			(item) => `
				<div class="stock-row">
					<span class="stock-product">
						<i class="bi ${item.icon}"></i>
						<span>
							${TNC.escapeHtml(item.label)}
						</span>
					</span>
					<strong>${formatCount(safeMap[item.key] ?? 0)}</strong>
				</div>
			`,
		)
		.join("");
}

function renderOrders(orders) {
	const body = document.querySelector("#admin-recent-orders");
	if (!body) return;

	if (!orders || !orders.length) {
		body.innerHTML =
			'<tr><td colspan="5" class="text-center text-muted py-4">Chưa có đơn hàng nào.</td></tr>';
		return;
	}

	body.innerHTML = orders
		.map(
			(order) => `
				<tr>
					<td><strong>#${TNC.escapeHtml(order.number)}</strong></td>
					<td>${TNC.escapeHtml(order.customer)}</td>
					<td>${TNC.escapeHtml(order.date)}</td>
					<td>${TNC.escapeHtml(order.totalText)}</td>
					<td>
						<span class="status-pill ${ORDER_STATUS_CLASSES[order.statusCode] || "status-pending"}">${TNC.escapeHtml(
							order.statusName,
						)}</span>
					</td>
				</tr>
			`,
		)
		.join("");
}

document.addEventListener("DOMContentLoaded", loadDashboard);

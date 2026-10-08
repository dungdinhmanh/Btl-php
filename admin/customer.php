<?php
require_once __DIR__ . '/../backend/bootstrap.php';

if (($_SESSION['user']['role'] ?? '') !== 'admin') {
	header('Location: ../index.php');
	exit;
}

$adminName = $_SESSION['user']['name'] ?? 'Administrator';
$adminInitials = strtoupper(substr(preg_replace('/\s+/', '', (string) $adminName), 0, 2));
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Quản lý khách hàng | TNC Store</title>
		<?php require __DIR__ . '/../partial/link.php'; ?>
	</head>
	<body class="admin-body">
		<aside id="adminSidebar" class="admin-sidebar">
			<a class="logo" href="../index.php"><img src="../assets/img/branding/tnc.png" alt="TNC Store" /></a>
			<p class="admin-label">Quản lý cửa hàng</p>
			<nav>
				<a href="../admin.php"><i class="bi bi-grid-1x2"></i><span>Tổng quan</span></a>
				<a href="products.php"><i class="bi bi-box-seam"></i><span>Sản phẩm</span></a>
				<a href="orders.php"><i class="bi bi-receipt"></i><span>Đơn hàng</span></a>
				<a class="active" href="customer.php"><i class="bi bi-people"></i><span>Khách hàng</span></a>
			</nav>
			<button class="admin-sidebar-toggle" type="button" aria-controls="adminSidebar" aria-expanded="true" aria-label="Thu gọn thanh bên">
				<i class="bi bi-layout-sidebar-inset" aria-hidden="true"></i><span>Thu gọn</span>
			</button>
			<a class="admin-back" href="../index.php"><i class="bi bi-arrow-left"></i><span>Về cửa hàng</span></a>
		</aside>

		<main class="admin-main">
			<header class="admin-topbar">
				<div>
					<p class="eyebrow">Hôm nay: <?= htmlspecialchars(date('d/m/Y'), ENT_QUOTES) ?></p>
					<h1>Quản lý khách hàng</h1>
				</div>
				<div class="admin-user">
					<span class="admin-avatar"><?= htmlspecialchars($adminInitials, ENT_QUOTES) ?></span>
					<span><strong><?= htmlspecialchars($adminName, ENT_QUOTES) ?></strong><small>Quản trị viên</small></span>
					<i class="bi bi-chevron-down"></i>
				</div>
			</header>

			<section class="admin-stats" id="customer-stats" aria-live="polite"></section>
			<section class="admin-panel">
				<div class="admin-panel-head">
					<div><p class="eyebrow">Danh sách tài khoản</p><h2>Khách hàng</h2></div>
				</div>
				<form class="row g-2 align-items-end mb-4" id="customer-filters">
					<div class="col-md-6 col-xl-4">
						<label class="form-label" for="customer-search">Tìm khách hàng</label>
						<input class="form-control" id="customer-search" name="search" type="search" maxlength="100" placeholder="Tên, email hoặc số điện thoại">
					</div>
					<div class="col-md-3 col-xl-2">
						<label class="form-label" for="customer-status">Trạng thái</label>
						<select class="form-select" id="customer-status" name="status">
							<option value="all">Tất cả</option>
							<option value="active">Đang hoạt động</option>
							<option value="disabled">Đã vô hiệu hóa</option>
							<option value="deleted">Khách tự xóa</option>
						</select>
					</div>
					<div class="col-md-3 col-xl-2">
						<label class="form-label" for="customer-registered-from">Từ ngày</label>
						<input class="form-control" id="customer-registered-from" name="registeredFrom" type="date">
					</div>
					<div class="col-md-3 col-xl-2">
						<label class="form-label" for="customer-registered-to">Đến ngày</label>
						<input class="form-control" id="customer-registered-to" name="registeredTo" type="date">
					</div>
					<div class="col-md-3 col-xl-2">
						<button class="btn btn-primary w-100" type="submit"><i class="bi bi-search me-2"></i>Lọc</button>
					</div>
				</form>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead><tr><th>Mã</th><th>Khách hàng</th><th>Điện thoại</th><th>Ngày tham gia</th><th>Đơn hàng</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
						<tbody id="customer-table-body"></tbody>
					</table>
				</div>
				<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3">
					<small class="text-muted" id="customer-page-label" aria-live="polite"></small>
					<div class="btn-group" role="group" aria-label="Phân trang khách hàng">
						<button class="btn btn-outline-secondary" type="button" data-page-change="-1" aria-label="Trang trước"><i class="bi bi-chevron-left"></i></button>
						<button class="btn btn-outline-secondary" type="button" data-page-change="1" aria-label="Trang sau"><i class="bi bi-chevron-right"></i></button>
					</div>
				</div>
			</section>
		</main>

		<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h2 class="modal-title fs-5" id="customer-detail-title">Thông tin khách hàng</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
					</div>
					<div class="modal-body" id="customer-detail-body"></div>
				</div>
			</div>
		</div>

		<script src="../js/api.js"></script>
		<script src="js/admin.js"></script>
		<script src="js/admin-customers.js"></script>
	</body>
</html>

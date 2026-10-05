<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$adminName = $_SESSION['user']['name'] ?? 'Administrator';
$adminInitials = strtoupper(substr(preg_replace('/\s+/', '', (string) $adminName), 0, 2));
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Quản lý đơn hàng | TNC Store</title>
		<?php require __DIR__ . '/../partial/link.php'; ?>
	</head>
	<body class="admin-body">
		<aside id="adminSidebar" class="admin-sidebar">
			<a class="logo" href="../index.php">
				<img src="../assets/img/branding/tnc.png" alt="TNC Store" />
			</a>
			<p class="admin-label">Quản lý cửa hàng</p>
			<nav>
				<a href="../admin.php">
					<i class="bi bi-grid-1x2"></i>
					<span>Tổng quan</span>
				</a>
				<a href="products.php">
					<i class="bi bi-box-seam"></i>
					<span>Sản phẩm</span>
				</a>
				<a class="active" href="orders.php">
					<i class="bi bi-receipt"></i>
					<span>Đơn hàng</span>
				</a>
				<a href="../contact.php">
					<i class="bi bi-chat-left-text"></i>
					<span>Khách hàng</span>
				</a>
			</nav>
			<button
				class="admin-sidebar-toggle"
				type="button"
				aria-controls="adminSidebar"
				aria-expanded="true"
				aria-label="Thu gọn thanh bên"
			>
				<i class="bi bi-layout-sidebar-inset" aria-hidden="true"></i>
				<span>Thu gọn</span>
			</button>
			<a class="admin-back" href="../index.php">
				<i class="bi bi-arrow-left"></i>
				<span>Về cửa hàng</span>
			</a>
		</aside>

		<main class="admin-main">
			<header class="admin-topbar">
				<div>
					<p class="eyebrow">Hôm nay: <?= htmlspecialchars(date('d/m/Y')) ?></p>
					<h1>Quản lý đơn hàng</h1>
				</div>
				<div class="admin-user">
					<span class="admin-avatar"><?= htmlspecialchars($adminInitials) ?></span>
					<span>
						<strong><?= htmlspecialchars($adminName) ?></strong>
						<small>Quản trị viên</small>
					</span>
					<i class="bi bi-chevron-down"></i>
				</div>
			</header>

			<section class="admin-panel">
				<div class="admin-panel-head">
					<div>
						<p class="eyebrow">Theo dõi</p>
						<h2>Đơn hàng mới</h2>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th>Mã đơn</th>
								<th>Khách hàng</th>
								<th>Ngày đặt</th>
								<th>Tổng tiền</th>
								<th>Trạng thái</th>
								<th>Hành động</th>
							</tr>
						</thead>
						<tbody id="admin-order-table-body"></tbody>
					</table>
				</div>
			</section>
		</main>

		<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<form id="order-form">
						<div class="modal-header">
							<h5 class="modal-title" id="orderModalTitle">Cập nhật trạng thái</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<label class="form-label">Trạng thái đơn hàng</label>
							<select class="form-select" name="status_code" required></select>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
							<button type="submit" class="btn btn-primary">Lưu</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script src="../js/api.js"></script>
		<script src="js/admin-orders.js"></script>
	</body>
</html>

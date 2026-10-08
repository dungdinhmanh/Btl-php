<?php
require_once __DIR__ . '/backend/bootstrap.php';

$adminName = $_SESSION['user']['name'] ?? 'Administrator';
$adminInitials = strtoupper(substr(preg_replace('/\s+/', '', (string) $adminName), 0, 2));
$todayLabel = date('d/m/Y');
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Quản trị | TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body class="admin-body">
		<aside id="adminSidebar" class="admin-sidebar">
			<a class="logo" href="index.php">
				<img src="assets/img/branding/tnc.png" alt="TNC Store" />
			</a>
			<p class="admin-label">Quản lý cửa hàng</p>
			<nav>
				<a class="active" href="admin.php">
					<i class="bi bi-grid-1x2"></i>
					<span>Tổng quan</span>
				</a>
				<a href="admin/products.php">
					<i class="bi bi-box-seam"></i>
					<span>Sản phẩm</span>
				</a>
				<a href="admin/orders.php">
					<i class="bi bi-receipt"></i>
					<span>Đơn hàng</span>
				</a>
				<a href="admin/customer.php">
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
			<a class="admin-back" href="index.php">
				<i class="bi bi-arrow-left"></i>
				<span>Về cửa hàng</span>
			</a>
		</aside>
		<main class="admin-main">
			<header class="admin-topbar">
				<div>
					<p class="eyebrow">Hôm nay: <?= htmlspecialchars($todayLabel) ?></p>
					<h1>Bảng điều khiển</h1>
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
			<section class="admin-stats" id="admin-stats"></section>
			<section class="admin-grid">
				<div class="admin-panel">
					<div class="admin-panel-head">
						<div>
							<p class="eyebrow">Sản phẩm</p>
							<h2>Trạng thái sản phẩm</h2>
						</div>
					</div>
					<div id="admin-product-status"></div>
				</div>
				<div class="admin-panel">
					<div class="admin-panel-head">
						<div>
							<p class="eyebrow">Kho hàng</p>
							<h2>Sắp hết hàng</h2>
						</div>
						<a class="text-link" href="admin/products.php">Xem tất cả</a>
					</div>
					<div id="admin-low-stock"></div>
				</div>
			</section>
			<section class="admin-panel orders-panel">
				<div class="admin-panel-head">
					<div>
						<p class="eyebrow">Mới nhất</p>
						<h2>Đơn hàng gần đây</h2>
					</div>
					<a class="text-link" href="admin/orders.php">Xem tất cả</a>
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
							</tr>
						</thead>
						<tbody id="admin-recent-orders"></tbody>
					</table>
				</div>
			</section>
		</main>
		<script src="js/api.js"></script>
		<script src="admin/js/admin.js"></script>
	</body>
</html>

<?php
require_once __DIR__ . '/backend/bootstrap.php';
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
				<a href="products.php">
					<i class="bi bi-box-seam"></i>
					<span>Sản phẩm</span>
				</a>
				<a href="cart.php">
					<i class="bi bi-receipt"></i>
					<span>Đơn hàng</span>
				</a>
				<a href="contact.php">
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
					<p class="eyebrow">Thứ Hai, 14 tháng 9, 2026</p>
					<h1>Tổng quan</h1>
				</div>
				<div class="admin-user">
					<span class="admin-avatar">TN</span>
					<span>
						<strong>Trần Nam</strong>
						<small>Quản trị viên</small>
					</span>
					<i class="bi bi-chevron-down"></i>
				</div>
			</header>
			<section class="admin-stats" id="admin-stats"></section>
			<section class="admin-grid">
				<div class="admin-panel chart-panel">
					<div class="admin-panel-head">
						<div>
							<p class="eyebrow">Hiệu suất</p>
							<h2>Doanh thu theo tháng</h2>
						</div>
						<select class="form-select form-select-sm">
							<option>6 tháng gần đây</option>
						</select>
					</div>
					<div class="chart">
						<div class="chart-y">
							<span>80m</span>
							<span>60m</span>
							<span>40m</span>
							<span>20m</span>
							<span>0</span>
						</div>
						<div class="chart-area">
							<div class="chart-grid-lines"></div>
							<svg
								viewBox="0 0 700 230"
								preserveAspectRatio="none"
								aria-label="Revenue chart"
							>
								<path
									d="M0,178 C70,150 90,180 145,140 S240,125 290,150 S370,82 420,110 S500,52 555,90 S640,35 700,55"
									fill="none"
									stroke="#29334f"
									stroke-width="4"
								></path>
								<path
									d="M0,178 C70,150 90,180 145,140 S240,125 290,150 S370,82 420,110 S500,52 555,90 S640,35 700,55 V230 H0 Z"
									fill="#29334f"
									opacity=".12"
								></path>
							</svg>
							<div class="chart-x">
								<span>Tháng 4</span>
								<span>Tháng 5</span>
								<span>Tháng 6</span>
								<span>Tháng 7</span>
								<span>Tháng 8</span>
								<span>Tháng 9</span>
							</div>
						</div>
					</div>
				</div>
				<div class="admin-panel">
					<div class="admin-panel-head">
						<div>
							<p class="eyebrow">Kho hàng</p>
							<h2>Sắp hết hàng</h2>
						</div>
						<a class="text-link" href="products.php">Xem tất cả</a>
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
					<a class="text-link" href="cart.php">Xem tất cả</a>
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
		<script src="js/admin.js"></script>
	</body>
</html>

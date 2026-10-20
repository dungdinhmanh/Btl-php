<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$adminName = requirePageRole(STAFF_ROLES, '../login.php')['name'];
$adminInitials = strtoupper(substr(preg_replace('/\s+/', '', (string) $adminName), 0, 2));
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Quản lý đánh giá | TNC Store</title>
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
				<a href="orders.php">
					<i class="bi bi-receipt"></i>
					<span>Đơn hàng</span>
				</a>
				<a href="reviews.php" class="active">
					<i class="bi bi-star"></i>
					<span>Đánh giá</span>
				</a>
				<a href="news.php">
					<i class="bi bi-newspaper"></i>
					<span>Tin tức</span>
				</a>
				<a href="customer.php">
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
					<h1>Quản lý đánh giá</h1>
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
						<p class="eyebrow">Phản hồi khách hàng</p>
						<h2>Danh sách đánh giá</h2>
					</div>
					<div class="d-flex gap-2 flex-wrap">
						<input
							class="form-control form-control-sm"
							id="review-search"
							type="search"
							placeholder="Tên, SĐT, nội dung hoặc sản phẩm"
							style="min-width: 260px"
						/>
						<select class="form-select form-select-sm" id="review-status-filter" style="width: auto">
							<option value="all">Tất cả</option>
							<option value="published">Đang hiện</option>
							<option value="hidden">Đang ẩn</option>
						</select>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th>Sản phẩm</th>
								<th>Người viết</th>
								<th>Điểm</th>
								<th>Nội dung</th>
								<th>Thời gian</th>
								<th>Trạng thái</th>
								<th>Hành động</th>
							</tr>
						</thead>
						<tbody id="admin-review-table-body"></tbody>
					</table>
				</div>

				<div class="d-flex justify-content-between align-items-center mt-3" id="review-pagination"></div>
			</section>
		</main>

		<script src="../js/api.js"></script>
		<script src="js/admin-reviews.js"></script>
	</body>
</html>

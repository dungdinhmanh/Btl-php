<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Tài khoản | TNC Store</title>
		<link rel="stylesheet" href="css/style.css" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
			rel="stylesheet"
		/>
		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
		/>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main>
			<section class="page-intro compact">
				<div class="container">
					<p class="eyebrow">TNC Store / Tài khoản</p>
					<h1>Tài khoản của bạn</h1>
				</div>
			</section>
			<section class="section-space">
				<div class="container">
					<div class="row g-4">
						<aside class="col-lg-3">
							<div class="filter-panel">
								<div class="d-flex align-items-center gap-3 mb-4">
									<i class="bi bi-person-circle fs-1 text-primary"></i>
									<div>
										<strong>Nguyễn Văn A</strong>
										<small class="d-block text-muted">
											Khách hàng thân thiết
										</small>
									</div>
								</div>
								<nav class="nav flex-column gap-2">
									<a class="nav-link active" href="#overview">Tổng quan</a>
									<a class="nav-link" href="#profile-info">Thông tin cá nhân</a>
									<a class="nav-link" href="#orders">Đơn hàng của tôi</a>
									<a class="nav-link" href="#address">Địa chỉ giao hàng</a>
									<a class="nav-link text-danger" href="index.html">Đăng xuất</a>
								</nav>
							</div>
						</aside>
						<div class="col-lg-9">
							<div id="overview" class="row g-3 mb-4">
								<div class="col-md-4">
									<div class="summary-card h-100">
										<small class="text-muted">Tổng đơn hàng</small>
										<h2 class="mt-2 mb-0">12</h2>
									</div>
								</div>
								<div class="col-md-4">
									<div class="summary-card h-100">
										<small class="text-muted">Đang xử lý</small>
										<h2 class="mt-2 mb-0">2</h2>
									</div>
								</div>
								<div class="col-md-4">
									<div class="summary-card h-100">
										<small class="text-muted">Điểm thành viên</small>
										<h2 class="mt-2 mb-0">860</h2>
									</div>
								</div>
							</div>
							<div id="profile-info" class="summary-card mb-4">
								<div class="d-flex justify-content-between align-items-center mb-3">
									<h2>Thông tin cá nhân</h2>
									<button class="btn btn-outline-primary btn-sm" type="button">
										Chỉnh sửa
									</button>
								</div>
								<div class="row g-3">
									<div class="col-md-6">
										<small class="text-muted d-block">Họ và tên</small>
										<strong>Nguyễn Văn A</strong>
									</div>
									<div class="col-md-6">
										<small class="text-muted d-block">Email</small>
										<strong>nguyenvana@example.com</strong>
									</div>
									<div class="col-md-6">
										<small class="text-muted d-block">Số điện thoại</small>
										<strong>090 123 4567</strong>
									</div>
									<div class="col-md-6">
										<small class="text-muted d-block">Ngày tham gia</small>
										<strong>12/03/2025</strong>
									</div>
								</div>
							</div>
							<div id="orders" class="summary-card mb-4">
								<h2 class="mb-3">Đơn hàng gần đây</h2>
								<div class="table-responsive">
									<table class="table align-middle mb-0">
										<thead>
											<tr>
												<th>Mã đơn</th>
												<th>Ngày đặt</th>
												<th>Trạng thái</th>
												<th class="text-end">Tổng tiền</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>#TNC-1024</td>
												<td>15/09/2026</td>
												<td>
													<span class="badge text-bg-warning">
														Đang giao
													</span>
												</td>
												<td class="text-end">12.490.000đ</td>
											</tr>
											<tr>
												<td>#TNC-1018</td>
												<td>02/09/2026</td>
												<td>
													<span class="badge text-bg-success">
														Hoàn tất
													</span>
												</td>
												<td class="text-end">4.500.000đ</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="address" class="summary-card">
								<h2 class="mb-3">Địa chỉ mặc định</h2>
								<strong>Nguyễn Văn A</strong>
								<p class="text-muted mb-0">
									090 123 4567
									<br />
									12 Nguyễn Huệ, phường Bến Nghé, Quận 1, TP. Hồ Chí Minh
								</p>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
		<script src="js/cart.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

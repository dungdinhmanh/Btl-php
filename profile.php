<?php
require_once __DIR__ . '/backend/bootstrap.php';

// Trang này chỉ dành cho người đã đăng nhập.
if (empty($_SESSION['user'])) {
	header('Location: login.php');
	exit;
}
$sessionName = htmlspecialchars((string) $_SESSION['user']['name'], ENT_QUOTES);
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Tài khoản | TNC Store</title>
		<?php require 'partial/link.php' ?>
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
				<div class="container" id="profile-root">
					<div class="row g-4">
						<aside class="col-lg-3">
							<div class="filter-panel">
								<div class="d-flex align-items-center gap-3 mb-4">
									<i class="bi bi-person-circle fs-1 text-primary"></i>
									<div class="min-w-0">
										<strong data-profile="side-name"><?= $sessionName ?></strong>
										<small class="d-block text-muted text-break" data-profile="side-email"></small>
									</div>
								</div>
								<nav class="nav flex-column gap-2">
									<a class="nav-link active" href="#overview">Tổng quan</a>
									<a class="nav-link" href="#profile-info">Thông tin cá nhân</a>
									<a class="nav-link" href="#orders">Đơn hàng của tôi</a>
									<a class="nav-link" href="#address">Địa chỉ giao hàng</a>
									<form method="post" action="backend/auth/logout.php">
										<button type="submit" class="nav-link text-danger border-0 bg-transparent text-start w-100">
											Đăng xuất
										</button>
									</form>
								</nav>
							</div>
						</aside>
						<div class="col-lg-9">
							<div class="alert alert-danger d-none" role="alert" data-profile-error></div>
							<div class="alert alert-success d-none" role="status" data-profile-success></div>
							<div id="overview" class="row g-3 mb-4">
								<div class="col-md-4">
									<div class="summary-card h-100">
										<small class="text-muted">Tổng đơn hàng</small>
										<p class="fs-3 fw-bold mt-2 mb-0" data-profile="stat-total">…</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="summary-card h-100">
										<small class="text-muted">Đang xử lý</small>
										<p class="fs-3 fw-bold mt-2 mb-0" data-profile="stat-processing">…</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="summary-card h-100">
										<small class="text-muted">Tổng chi tiêu</small>
										<p class="fs-3 fw-bold mt-2 mb-0" data-profile="stat-spent">…</p>
									</div>
								</div>
							</div>
							<div id="profile-info" class="summary-card mb-4">
								<h2 class="mb-3">Thông tin cá nhân</h2>
								<form class="row g-3" data-profile-form="details">
									<div class="col-md-6">
										<label class="form-label" for="profile-name">Họ và tên</label>
										<input class="form-control" id="profile-name" name="name" type="text" maxlength="150" autocomplete="name" required>
									</div>
									<div class="col-md-6">
										<label class="form-label" for="profile-email">Email</label>
										<input class="form-control" id="profile-email" name="email" type="email" maxlength="254" autocomplete="email" required>
									</div>
									<div class="col-md-6">
										<label class="form-label" for="profile-phone">Số điện thoại</label>
										<input class="form-control" id="profile-phone" name="phone" type="tel" maxlength="25" autocomplete="tel">
									</div>
									<div class="col-md-6">
										<label class="form-label" for="profile-current-password">Mật khẩu hiện tại</label>
										<input class="form-control" id="profile-current-password" name="currentPassword" type="password" autocomplete="current-password" required>
									</div>
									<div class="col-12">
										<button class="btn btn-primary" type="submit">Lưu thông tin</button>
									</div>
								</form>
								<hr class="my-4">
								<h3 class="h5 mb-3">Đổi mật khẩu</h3>
								<form class="row g-3" data-profile-form="password">
									<div class="col-md-4">
										<label class="form-label" for="password-current">Mật khẩu hiện tại</label>
										<input class="form-control" id="password-current" name="currentPassword" type="password" autocomplete="current-password" required>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="password-new">Mật khẩu mới</label>
										<input class="form-control" id="password-new" name="newPassword" type="password" minlength="8" autocomplete="new-password" required>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="password-confirm">Xác nhận mật khẩu mới</label>
										<input class="form-control" id="password-confirm" name="confirmPassword" type="password" minlength="8" autocomplete="new-password" required>
									</div>
									<div class="col-12">
										<button class="btn btn-outline-primary" type="submit">Đổi mật khẩu</button>
									</div>
								</form>
								<hr class="my-4">
								<div class="row g-3">
									<div class="col-md-6">
										<small class="text-muted d-block">Ngày tham gia</small>
										<strong data-profile="joined">…</strong>
									</div>
								</div>
								<hr class="my-4">
								<div class="row g-3 align-items-end">
									<div class="col-lg-7">
										<h3 class="h5 text-danger mb-2">Xóa tài khoản</h3>
										<p class="text-muted mb-0">Tài khoản sẽ được xóa mềm và bạn sẽ được đăng xuất. Dữ liệu đơn hàng được giữ lại.</p>
									</div>
									<div class="col-lg-5">
										<form class="d-flex flex-column gap-2" data-profile-form="delete">
											<label class="form-label mb-0" for="delete-current-password">Nhập mật khẩu để xác nhận</label>
											<input class="form-control" id="delete-current-password" name="currentPassword" type="password" autocomplete="current-password" required>
											<button class="btn btn-outline-danger align-self-start" type="submit">Vô hiệu hóa tài khoản</button>
										</form>
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
										<tbody id="orders-body">
											<tr>
												<td colspan="4" class="text-center text-muted py-4">Đang tải...</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="address" class="summary-card">
								<h2 class="mb-3">Địa chỉ mặc định</h2>
								<div data-profile="address">Đang tải...</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<?php require 'partial/footer.php' ?>
		<script src="js/profile.js"></script>
	</body>
</html>

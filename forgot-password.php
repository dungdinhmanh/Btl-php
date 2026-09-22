<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Quên mật khẩu | TNC Store</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" href="assets/favicon.png" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
			rel="stylesheet"
		/>
		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
		/>
	</head>
	<body style="background-color: #f5f7fa">
		<?php require 'partial/header.php' ?>
		<main>
			<section class="auth-section">
				<div class="container">
					<div class="auth-card account-modal">
						<h1 class="modal-title mb-2">Quên mật khẩu</h1>
						<p class="text-muted small mb-4">
							Nhập email của bạn để nhận mã xác nhận đặt lại mật khẩu.
						</p>
						<form>
							<label for="forgot-email">Email đăng ký</label>
							<input
								id="forgot-email"
								class="form-control mb-3"
								type="email"
								placeholder="name@example.com"
								required
							/>
							<button class="btn btn-primary w-100 mb-3" type="submit">
								Gửi yêu cầu
							</button>
							<a href="index.html#accountModal" class="btn btn-link w-100">
								Quay lại đăng nhập
							</a>
						</form>
					</div>
				</div>
			</section>
		</main>
		<footer class="site-footer">
			<div class="container">
				<div class="row g-4">
					<div class="col-lg-5">
						<a class="logo" href="index.html">
							<img src="assets/img/branding/tnc.png" alt="TNC Store" />
						</a>
						<p>Thiết bị công nghệ chọn lọc cho những người luôn muốn làm tốt hơn.</p>
					</div>
					<div class="col-6 col-lg-2">
						<h3>Khám phá</h3>
						<a href="products.html">Sản phẩm</a>
						<a href="about.html">Về TNC</a>
						<a href="contact.html">Liên hệ</a>
					</div>
					<div class="col-6 col-lg-2">
						<h3>Hỗ trợ</h3>
						<a href="buildpc.html">Build PC</a>
						<a href="cart.html">Giỏ hàng</a>
						<a href="index.html#accountModal">Tài khoản</a>
					</div>
					<div class="col-lg-3">
						<h3>Kết nối</h3>
						<div class="footer-social">
							<a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
							<a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
							<a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
						</div>
					</div>
				</div>
				<div class="footer-bottom">
					© 2026 TNC Store
					<a href="admin.html">Quản trị viên</a>
				</div>
			</div>
		</footer>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
		<script src="js/cart.js"></script>
	</body>
</html>

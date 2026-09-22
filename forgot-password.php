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
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
		<script src="js/cart.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Đăng nhập | TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main>
			<section class="auth-section">
				<div class="container">
					<div class="auth-card account-modal">
						<h1 class="modal-title mb-4">Đăng nhập</h1>
						<form action="backend/auth/login.php">
							<label for="login-email">Email</label>
							<input
								id="login-email"
								class="form-control mb-3"
								type="email"
								placeholder="you@example.com"
								required
							/>
							<label for="login-password">Mật khẩu</label>
							<input
								id="login-password"
								class="form-control mb-3"
								type="password"
								placeholder="••••••••"
								required
							/>
							<button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
						</form>
						<a href="forgot-password.php" class="auth-link">Quên mật khẩu?</a>
						<a href="index.php#accountModal-register" class="btn btn-link w-100 mt-2">
							Tạo tài khoản mới
						</a>
					</div>
				</div>
			</section>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

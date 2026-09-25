<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Đăng ký | TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main>
			<section class="auth-section">
				<div class="container">
					<div class="auth-card account-modal">
						<h1 class="modal-title mb-4">Tạo tài khoản</h1>
						<form action="backend/auth/register.php">
							<label for="register-name">Họ và tên</label>
							<input
								id="register-name"
								class="form-control mb-3"
								type="text"
								placeholder="Nguyễn Văn A"
								required
							/>
							<label for="register-email">Email</label>
							<input
								id="register-email"
								class="form-control mb-3"
								type="email"
								placeholder="you@example.com"
								required
							/>
							<label for="register-password">Mật khẩu</label>
							<input
								id="register-password"
								class="form-control mb-3"
								type="password"
								placeholder="Tối thiểu 8 ký tự"
								required
							/>
							<button class="btn btn-primary w-100" type="submit">
								Tạo tài khoản
							</button>
						</form>
						<a href="index.php#accountModal" class="btn btn-link w-100 mt-2">
							Đã có tài khoản? Đăng nhập
						</a>
					</div>
				</div>
			</section>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

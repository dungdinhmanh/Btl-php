<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Không tìm thấy | TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main class="error-page">
			<div class="container">
				<div class="error-number">404</div>
				<p class="eyebrow">Có vẻ bạn đã đi hơi xa</p>
				<h1>
					Trang này không
					<br />
					ở đây nữa.
				</h1>
				<p>Đường dẫn có thể đã thay đổi. Hãy quay về cửa hàng để tiếp tục khám phá.</p>
				<a class="btn btn-primary" href="index.php">
					Về trang chủ
					<i class="bi bi-arrow-right ms-2"></i>
				</a>
			</div>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

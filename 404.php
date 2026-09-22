<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Không tìm thấy | TNC Store</title>
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
				<a class="btn btn-primary" href="index.html">
					Về trang chủ
					<i class="bi bi-arrow-right ms-2"></i>
				</a>
			</div>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

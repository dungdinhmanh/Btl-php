<?php
require_once __DIR__ . '/backend/bootstrap.php';
$orderNumber = $_SESSION['last_order_number'] ?? null;
unset($_SESSION['last_order_number']);
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Đặt hàng thành công - TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body class="bg-light">
		<?php require 'partial/header.php' ?>

		<!-- Phần nội dung thông báo đặt hàng thành công được căn giữa riêng biệt -->
		<main class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
			<div class="card p-5 shadow-sm text-center my-5" style="max-width: 500px; width: 100%">
				<div class="mb-4">
					<i class="bi <?= is_string($orderNumber) && $orderNumber !== '' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-circle text-warning' ?>" style="font-size: 4rem"></i>
				</div>
				<h3 class="fw-bold mb-2"><?= is_string($orderNumber) && $orderNumber !== '' ? 'Đặt hàng thành công!' : 'Chưa xác nhận đơn hàng' ?></h3>
				<p class="text-muted mb-4">
					<?php if (is_string($orderNumber) && $orderNumber !== ''): ?>
						Cảm ơn bạn đã mua hàng. Mã đơn hàng của bạn là:
						<strong class="text-dark">#<?= htmlspecialchars($orderNumber, ENT_QUOTES, 'UTF-8') ?></strong>.
						Chúng tôi sẽ liên hệ xác nhận giao hàng trong thời gian sớm nhất.
					<?php else: ?>
						Chưa có đơn hàng mới được xác nhận trong phiên này. Vui lòng quay lại giỏ hàng để hoàn tất thanh toán.
					<?php endif; ?>
				</p>
				<div class="d-grid gap-2">
					<a href="index.php" class="btn btn-primary">Về trang chủ</a>
					<a href="products.php" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
				</div>
			</div>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

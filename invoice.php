<?php
require_once __DIR__ . '/backend/bootstrap.php';

$user = $_SESSION['user'] ?? null;
if (!$user) {
	header('Location: login.php');
	exit;
}

$orderNumber = trim((string) ($_GET['order'] ?? ''));
$invoice = null;
if (preg_match('/^[A-Za-z0-9-]{1,32}$/', $orderNumber)) {
	try {
		$invoice = (new InvoiceRepository(database()))->forOrder($orderNumber);
	} catch (Throwable $exception) {
		http_response_code(503);
		exit('Chưa kết nối được cơ sở dữ liệu.');
	}
}

// Chủ đơn hoặc nhân viên/quản trị mới được xem. Người khác nhận 404 như khi mã đơn không tồn tại,
// để không lộ việc mã đơn đó có thật hay không.
$canView = $invoice !== null
	&& ($invoice['ownerId'] === (int) $user['id'] || in_array($user['role'] ?? '', ['admin', 'staff'], true));
if (!$canView) {
	http_response_code(404);
	require __DIR__ . '/404.php';
	exit;
}

$statusLabels = [
	'pending' => 'Chờ xác nhận',
	'confirmed' => 'Đã xác nhận',
	'shipping' => 'Đang giao',
	'completed' => 'Hoàn tất',
	'cancelled' => 'Đã hủy',
];
$statusLabel = $statusLabels[$invoice['statusCode']] ?? $invoice['statusCode'];
$money = static fn (int $value): string => number_format($value, 0, ',', '.') . 'đ';
$e = static fn (?string $value): string => htmlspecialchars((string) $value, ENT_QUOTES);
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Hóa đơn <?= $e($invoice['invoiceNumber']) ?> | TNC Store</title>
		<?php require 'partial/link.php' ?>
		<link rel="stylesheet" href="css/invoice.css" />
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main>
			<section class="section-space">
				<div class="container">
					<article class="invoice" id="invoice">
						<div class="invoice-head">
							<div>
								<img class="invoice-logo" src="assets/img/branding/tnc.png" alt="TNC Store" />
								<p class="mb-0 mt-2"><strong>TNC Store</strong></p>
								<small class="text-muted d-block">Hotline: 0868 302 123</small>
								<small class="text-muted d-block">Email: cskh@tncstore.vn</small>
							</div>
							<div class="text-end">
								<h1 class="invoice-title">HÓA ĐƠN</h1>
								<div>Số: <strong><?= $e($invoice['invoiceNumber']) ?></strong></div>
								<div>Ngày lập: <?= $e($invoice['issuedAt']) ?></div>
								<div>Đơn hàng: <?= $e($invoice['orderNumber']) ?></div>
								<div>Trạng thái: <strong><?= $e($statusLabel) ?></strong></div>
							</div>
						</div>

						<?php if ($invoice['statusCode'] === 'cancelled'): ?>
							<div class="alert alert-danger mt-3 mb-0" role="alert">
								Đơn hàng này đã hủy, hóa đơn không có giá trị thanh toán.
							</div>
						<?php endif; ?>

						<div class="row g-4 mt-1">
							<div class="col-md-6">
								<p class="invoice-label">Khách hàng</p>
								<strong><?= $e($invoice['customer']['name']) ?></strong>
								<?php if ($invoice['customer']['email']): ?>
									<div class="text-break"><?= $e($invoice['customer']['email']) ?></div>
								<?php endif; ?>
								<?php if ($invoice['customer']['phone']): ?>
									<div><?= $e($invoice['customer']['phone']) ?></div>
								<?php endif; ?>
							</div>
							<div class="col-md-6">
								<p class="invoice-label">Giao tới</p>
								<?php if ($invoice['address']): ?>
									<strong><?= $e($invoice['address']['recipientName']) ?></strong>
									<div><?= $e($invoice['address']['recipientPhone']) ?></div>
									<div><?= $e($invoice['address']['text']) ?></div>
								<?php else: ?>
									<span class="text-muted">Chưa có địa chỉ giao hàng.</span>
								<?php endif; ?>
							</div>
						</div>

						<div class="table-responsive mt-4">
							<table class="table table-sm align-middle invoice-table">
								<thead>
									<tr>
										<th>#</th>
										<th>Sản phẩm</th>
										<th class="text-end">SL</th>
										<th class="text-end">Đơn giá</th>
										<th class="text-end">Thành tiền</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($invoice['items'] as $item): ?>
										<tr>
											<td><?= $item['line'] ?></td>
											<td>
												<?= $e($item['name']) ?>
												<small class="text-muted d-block">SKU: <?= $e($item['sku']) ?></small>
											</td>
											<td class="text-end"><?= $item['quantity'] ?></td>
											<td class="text-end"><?= $money($item['unitPrice']) ?></td>
											<td class="text-end"><?= $money($item['lineTotal']) ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>

						<dl class="invoice-totals">
							<div><dt>Tạm tính</dt><dd><?= $money($invoice['subtotal']) ?></dd></div>
							<div><dt>Phí vận chuyển</dt><dd><?= $money($invoice['shipping']) ?></dd></div>
							<?php if ($invoice['discount'] > 0): ?>
								<div><dt>Giảm giá</dt><dd>-<?= $money($invoice['discount']) ?></dd></div>
							<?php endif; ?>
							<div class="invoice-grand"><dt>Tổng thanh toán</dt><dd><?= $money($invoice['total']) ?></dd></div>
						</dl>

						<p class="text-center text-muted mt-4 mb-0">Cảm ơn bạn đã mua sắm tại TNC Store.</p>
					</article>

					<div class="invoice-actions no-print">
						<button type="button" class="btn btn-primary" data-print>
							<i class="bi bi-printer"></i> In hóa đơn
						</button>
						<a class="btn btn-outline-secondary" href="profile.php#orders">Quay lại đơn hàng</a>
					</div>
				</div>
			</section>
		</main>
		<?php require 'partial/footer.php' ?>
		<script>
			document.querySelector("[data-print]").addEventListener("click", () => window.print());
		</script>
	</body>
</html>

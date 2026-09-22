<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Giỏ hàng | TNC Store</title>
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
		<main>
			<section class="page-intro compact">
				<div class="container">
					<p class="eyebrow">TNC Store / Checkout</p>
					<h1>Giỏ hàng của bạn</h1>
				</div>
			</section>
			<section class="section-space">
				<div class="container">
					<div class="alert alert-light border d-none" data-cart-empty>
						Giỏ hàng đang trống.
						<a href="products.html">Tiếp tục mua sắm</a>
					</div>
					<div class="row g-5" data-cart-content>
						<div class="col-lg-8">
							<div data-cart-list></div>
							<div class="promo-box">
								<i class="bi bi-ticket-perforated"></i>
								<div>
									<strong>Bạn có mã giảm giá?</strong>
									<p>Nhập mã ở bước thanh toán để nhận ưu đãi.</p>
								</div>
								<a href="checkout.html">
									Thanh toán
									<i class="bi bi-arrow-right"></i>
								</a>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="summary-card">
								<h2>Tóm tắt đơn hàng</h2>
								<div>
									<span>Tạm tính</span>
									<strong data-cart-subtotal>0đ</strong>
								</div>
								<div>
									<span>Phí vận chuyển</span>
									<span>Miễn phí</span>
								</div>
								<hr />
								<div class="summary-total">
									<span>Tổng cộng</span>
									<strong data-cart-total>0đ</strong>
								</div>
								<a class="btn btn-primary w-100 mt-4" href="checkout.html">
									Tiến hành thanh toán
									<i class="bi bi-arrow-right ms-2"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
		<script src="js/cart.js"></script>
	    <?php require 'partial/header.php' ?>
	</body>
</html>

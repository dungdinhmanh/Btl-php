<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title id="page-title">Chi tiết sản phẩm | TNC Store</title>
		<?php require 'partial/link.php' ?>
		<link rel="stylesheet" href="css/product-detail.css" />
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main>
			<div class="container pd-page">
				<!-- Breadcrumb -->
				<nav aria-label="breadcrumb" class="mb-4">
					<ol class="breadcrumb mb-0">
						<li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
						<li class="breadcrumb-item">
							<a href="products.php">Sản phẩm</a>
						</li>
						<li
							class="breadcrumb-item active text-truncate"
							id="breadcrumb-product-name"
							aria-current="page"
						>
							Đang tải...
						</li>
					</ol>
				</nav>

				<!-- Nội dung chi tiết sản phẩm -->
				<section class="pd-wrapper">
					<!-- Cột trái: gallery ảnh -->
					<div class="pd-gallery">
						<div class="pd-main-img product-image-zoom">
							<img
								id="main-product-img"
								src="assets/img/branding/tnc.png"
								alt="Sản phẩm"
								class="product-gallery-main"
							/>
						</div>
						<div class="pd-thumb-row" id="gallery-thumbs"></div>
					</div>

					<!-- Cột phải: thông tin chính -->
					<div class="pd-info">
						<h1 class="pd-name" id="product-name">Đang tải sản phẩm...</h1>
						<p class="pd-model" id="product-model"></p>

						<div class="pd-meta-row">
							<span>Mã SP: <strong id="product-sku">---</strong></span>
							<span>Tình trạng: <span id="product-stock" class="product-stock"></span></span>
							<span>Thương hiệu: <strong class="pd-brand" id="product-brand">---</strong></span>
						</div>

						<!-- Giá -->
						<div class="pd-price-box">
							<span class="pd-price" id="product-price">---</span>
						</div>

						<!-- Cấu hình nổi bật (JS đổ từ thông số sản phẩm) -->
						<div class="pd-highlight-box" id="product-highlights" hidden>
							<div class="pd-box-title">
								<i class="bi bi-cpu"></i> Cấu hình nổi bật
							</div>
							<div id="highlight-list"></div>
						</div>

						<!-- Khuyến mãi -->
						<div class="pd-promo">
							<div class="pd-promo-header">
								<i class="bi bi-gift"></i> Quà tặng &amp; ưu đãi kèm theo
							</div>
							<ul class="pd-promo-body">
								<li>Tặng ngay Bàn phím cơ Gaming + Chuột Gaming RGB.</li>
								<li>Tặng Lót chuột kích thước lớn TNC E-sports.</li>
								<li>Giảm thêm 200.000đ khi mua kèm Màn hình Gaming từ 24 inch.</li>
							</ul>
						</div>

						<!-- Số lượng -->
						<div class="pd-qty">
							<label for="product-qty">Số lượng:</label>
							<input type="number" id="product-qty" value="1" min="1" />
						</div>

						<!-- Nút hành động -->
						<div class="pd-btn-row">
							<button class="pd-btn pd-btn-cart" id="btn-add-to-cart" type="button">
								<i class="bi bi-cart-plus"></i> Thêm vào giỏ
							</button>
							<a href="checkout.php" class="pd-btn pd-btn-buy">Mua ngay</a>
						</div>

						<!-- Cam kết dịch vụ -->
						<div class="pd-commit">
							<div><i class="bi bi-shield-check"></i> Bảo hành chính hãng 36 tháng</div>
							<div><i class="bi bi-truck"></i> Giao hàng miễn phí toàn quốc</div>
							<div><i class="bi bi-arrow-repeat"></i> Lỗi 1 đổi 1 trong 30 ngày</div>
							<div><i class="bi bi-headset"></i> Hỗ trợ kỹ thuật trọn đời</div>
						</div>
					</div>
				</section>

				<!-- Tabs: thông số / chính sách -->
				<section class="pd-tabs-card">
					<div class="pd-tab-headers" role="tablist">
						<button
							class="pd-tab-btn active"
							data-bs-toggle="tab"
							data-bs-target="#tab-specs"
							type="button"
							role="tab"
						>
							Thông số chi tiết
						</button>
						<button
							class="pd-tab-btn"
							data-bs-toggle="tab"
							data-bs-target="#tab-policy"
							type="button"
							role="tab"
						>
							Chính sách bán hàng
						</button>
					</div>

					<div class="tab-content">
						<div class="tab-pane fade show active" id="tab-specs" role="tabpanel">
							<table class="table table-bordered mb-0 product-spec-table" id="spec-table">
								<tbody>
									<tr>
										<td>Đang tải...</td>
										<td>---</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="tab-pane fade pd-policy" id="tab-policy" role="tabpanel">
							<p>Sản phẩm được bảo hành chính hãng 36 tháng tại TNC Store.</p>
							<p>Miễn phí giao hàng toàn quốc. Lỗi do nhà sản xuất được đổi mới 1 đổi 1 trong 30 ngày.</p>
							<p>Đội ngũ kỹ thuật hỗ trợ cài đặt và xử lý sự cố trọn đời cho khách hàng.</p>
						</div>
					</div>
				</section>
			</div>
		</main>
		<script src="js/product-detail.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

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

					<!-- Cột giữa: thông tin chính -->
					<div class="pd-info">
						<div class="pd-brand-line">
							<strong class="pd-brand" id="product-brand">---</strong>
						</div>
						<h1 class="pd-name" id="product-name">Đang tải sản phẩm...</h1>
						<p class="pd-model" id="product-model"></p>

						<div class="pd-meta-row">
							<span>MSP: <strong id="product-sku">---</strong></span>
							<span>Tình trạng: <span id="product-stock" class="product-stock"></span></span>
						</div>

						<!-- Cấu hình nổi bật dạng gạch đầu dòng (JS đổ từ thông số) -->
						<div class="pd-highlights" id="product-highlights" hidden>
							<ul class="pd-bullets" id="highlight-list"></ul>
							<button class="pd-link-btn" id="highlight-toggle" type="button" hidden>
								Xem thêm
							</button>
						</div>

						<!-- Giá -->
						<div class="pd-price-row">
							<span class="pd-price" id="product-price">---</span>
							<span class="pd-price-old" id="product-old-price" hidden></span>
							<span class="pd-discount" id="product-discount" hidden></span>
						</div>
						<span class="pd-warranty-tag">Bảo hành chính hãng theo linh kiện</span>

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
							<a href="contact.php" class="pd-btn pd-btn-consult">
								<i class="bi bi-chat-dots"></i> Nhận tư vấn ngay
								<small>Nhận giá tốt nhất, không chờ đợi</small>
							</a>
						</div>
					</div>

					<!-- Cột phải: khuyến mãi + cam kết -->
					<aside class="pd-aside">
						<div class="pd-promo">
							<div class="pd-promo-header">
								<i class="bi bi-gift-fill"></i> Khuyến mãi khi mua sản phẩm
							</div>
							<ul class="pd-promo-body">
								<li><i class="bi bi-piggy-bank"></i> Giảm thêm 4% cho toàn bộ linh kiện.</li>
								<li><i class="bi bi-display"></i> Giảm thêm 2% khi mua kèm Màn hình.</li>
								<li><i class="bi bi-keyboard"></i> Giảm thêm 3% khi mua kèm Gaming Gear.</li>
								<li><i class="bi bi-mouse"></i> Tặng 01 bàn di chuột.</li>
								<li><i class="bi bi-truck"></i> Miễn phí vận chuyển PC toàn quốc.</li>
								<li><i class="bi bi-arrow-repeat"></i> Hỗ trợ 1 đổi 1.</li>
								<li><i class="bi bi-headset"></i> Hỗ trợ kỹ thuật trọn đời.</li>
							</ul>
						</div>
					</aside>
				</section>

				<!-- Mô tả sản phẩm -->
				<section class="pd-section" id="pd-description">
					<h2 class="pd-section-title">Mô tả sản phẩm</h2>
					<div class="pd-desc" id="product-description">Đang tải...</div>
				</section>

				<!-- Thông số kỹ thuật -->
				<section class="pd-section" id="pd-specs">
					<h2 class="pd-section-title">Thông số kỹ thuật</h2>
					<div class="pd-spec-wrap" id="spec-wrap">
						<table class="table table-bordered mb-0 product-spec-table" id="spec-table">
							<tbody>
								<tr>
									<td>Đang tải...</td>
									<td>---</td>
								</tr>
							</tbody>
						</table>
					</div>
					<button class="pd-more-btn" id="spec-toggle" type="button" hidden>
						Xem thêm thông số
					</button>
				</section>

				<!-- Sản phẩm tương tự (JS đổ cùng danh mục) -->
				<section class="pd-similar" id="similar-section" hidden>
					<h2 class="pd-section-title" id="similar-title">Sản phẩm tương tự</h2>
					<div class="row g-3" id="similar-products"></div>
				</section>

				<!-- Tin tức liên quan (JS đổ bài mới nhất) -->
				<section class="pd-similar" id="news-section" hidden>
					<h2 class="pd-section-title">Tin tức liên quan</h2>
					<div class="row g-3" id="related-news"></div>
				</section>
			</div>
		</main>
		<script src="js/product-detail.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

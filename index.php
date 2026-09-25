<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>TNC Store</title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<?php require 'partial/link.php'?>
	</head>
	<body>
		<?php require 'partial/header.php'?>
		<main>
			<section class="promo-carousel-section">
				<div id="storePromoCarousel" class="carousel slide" data-bs-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-indicators">
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="0"
								class="active"
								aria-current="true"
								aria-label="Back To School"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="1"
								aria-label="Build PC Asus Rinh quà hết nấc"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="2"
								aria-label="Build PC Gigabyte Intel"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="3"
								aria-label="Đồng hành trở về căn cứ Asus"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="4"
								aria-label="PC AI Gigabyte Web"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="5"
								aria-label="Razer len deal gear len doi"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="6"
								aria-label="Asus Miku Gear"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="7"
								aria-label="MSI Frieren"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="8"
								aria-label="Trang chu Asus T1 PC"
							></button>
						</div>
						<div class="carousel-item active promo-slide">
							<img
								src="assets/img/banner/banner-back-to-school-pc-1.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-build-pc-asus-rinh-qua-het-nac.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-build-pc-gigabyte-intel-pc.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-dong-hanh-tro-ve-can-cu-asus-pc.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-pc-ai-gigabyte-web-7.jpg"
								width="90%"
								height="80%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-razer-len-deal-gear-len-doi.png"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-trang-chu-asus-hiku.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-trang-chu-msi-frieren-mobile-1.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-trang-chu-asus-t1-pc-2.jpg"
								width="100%"
							/>
						</div>
					</div>
					<div class="container">
						<button
							class="carousel-control-prev"
							type="button"
							data-bs-target="#storePromoCarousel"
							data-bs-slide="prev"
						>
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</button>
						<button
							class="carousel-control-next"
							type="button"
							data-bs-target="#storePromoCarousel"
							data-bs-slide="next"
						>
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</button>
					</div>
				</div>
			</section>
			<section class="section-space">
				<div class="container">
					<h2 class="category-featured-title">Danh mục nổi bật</h2>
					<div class="category-grid">
						<a href="products.php?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>PC GAMING</strong>
								<span>Mua ngay - Giá đang rẻ</span>
							</div>
							<img src="assets/img/category/cat-pc-gaming-miku.jpg" alt="PC gaming" />
						</a>
						<a href="products.php?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>PC ĐỒ HỌA AI</strong>
								<span>Tối ưu công việc - Tối thiểu giá thành</span>
							</div>
							<img
								src="assets/img/category/cat-pc-do-hoa-asus.jpg"
								alt="PC đồ họa AI"
							/>
						</a>
						<a href="products.php?category=monitor" class="category-tile">
							<div class="category-tile-copy">
								<strong>MÀN HÌNH MÁY TÍNH</strong>
								<span>Thế giới màn hình giá rẻ</span>
							</div>
							<img
								src="assets/img/category/cat-man-hinh-may-tinh-1.png"
								alt="Màn hình máy tính"
							/>
						</a>
						<a href="products.php?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>VGA - CARD MÀN HÌNH</strong>
								<span>Tổng kho VGA rẻ nhất Hà Nội</span>
							</div>
							<img
								src="assets/img/category/cat-vga-card-man-hinh-2.png"
								alt="VGA card màn hình"
							/>
						</a>
						<a href="products.php?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>LAPTOP GAMING</strong>
								<span>Giá rẻ - Cấu hình khủng</span>
							</div>
							<img
								src="assets/img/category/cat-laptop-gaming-1.png"
								alt="Laptop gaming"
							/>
						</a>
						<a href="products.php?category=gaming-gear" class="category-tile">
							<div class="category-tile-copy">
								<strong>MÁY CHƠI GAME PS5</strong>
								<span>Chính hãng - Giá rẻ - Bảo hành 1 đổi 1</span>
							</div>
							<img
								src="assets/img/category/9223-ps5-slim.jpg"
								alt="Máy chơi game PS5"
							/>
						</a>
						<a href="products.php?category=gaming-gear" class="category-tile">
							<div class="category-tile-copy">
								<strong>NINTENDO SWITCH</strong>
								<span>Giá rẻ - Chơi game tuyệt đỉnh</span>
							</div>
							<img
								src="assets/img/category/cat-pc-handheld-1.png"
								alt="Nintendo Switch"
							/>
						</a>
						<a href="products.php?category=gaming-gear" class="category-tile">
							<div class="category-tile-copy">
								<strong>GHẾ GAMING</strong>
								<span>Rẻ, hiện đại, tối ưu công năng</span>
							</div>
							<img src="assets/img/category/cat-ghe-gaming-1.png" alt="Ghế gaming" />
						</a>
					</div>
				</div>
			</section>
			<?php
			$productGroups = [
				[
					'title' => 'PC Gaming nổi bật',
					'banner' => 'cat_big_82_1764436058.jpg',
					'folders' => ['case', 'psu', 'vga', 'cpu'],
				],
				[
					'title' => 'PC Đồ Họa AI nổi bật',
					'banner' => 'cat_big_210_1764436013.jpg',
					'folders' => ['vga', 'ram', 'cpu', 'mainboard'],
				],
				[
					'title' => 'Laptop - Máy Tính Xách Tay nổi bật',
					'banner' => 'cat_big_79_1764436023.jpg',
					'folders' => ['ram', 'SSD'],
				],
				[
					'title' => 'Màn Hình Máy Tính nổi bật',
					'banner' => 'cat_big_68_1764436032.jpg',
					'folders' => ['display'],
				],
				[
					'title' => 'Máy chơi game - Console nổi bật',
					'banner' => 'cat_big_217_1764436040.jpg',
					'folders' => ['phụ kiện'],
				],
				[
					'title' => 'Gaming Gears nổi bật',
					'banner' => 'cat_big_78_1764436048.jpg',
					'folders' => ['phụ kiện', 'case'],
				],
			];
			?>
			<div class="box-group-category">
				<div class="container">
					<?php foreach ($productGroups as $group): ?>
					<div
						class="group-category-home background-white"
						data-group-folders="<?= implode(',', $group['folders']) ?>"
					>
						<div class="group-title d-flex align-items space-between">
							<h2 class="title-left"><?= $group['title'] ?></h2>
							<a href="products.php" class="more-all">
								<span class="hover-txt">Xem tất cả</span>
								<i class="bi bi-arrow-right" aria-hidden="true"></i>
							</a>
						</div>
						<div class="content-product-category d-flex">
							<a href="products.php" class="banner-sale-cate">
								<img
									src="assets/img/category/<?= $group['banner'] ?>"
									width="100%"
									height="100%"
									alt="<?= $group['title'] ?>"
									loading="lazy"
								/>
							</a>
							<div class="product-list row g-4" data-product-list></div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<section class="section-space section-muted">
				<div class="container">
					<div class="section-heading">
						<div>
							<h2>Sản phẩm nổi bật</h2>
						</div>
						<a href="products.php" class="text-link">
							Xem cửa hàng
							<i class="bi bi-arrow-up-right"></i>
						</a>
					</div>
					<div class="row g-4" id="featured-products-list">
						<div class="col-sm-6 col-lg-3 d-flex">
							<article class="product-card d-flex w-100 flex-column">
								<div class="product-image">
									<i class="bi bi-cpu"></i>
									<span class="product-tag">Bán chạy</span>
								</div>
								<p class="product-brand">Intel · Socket LGA1700</p>
								<h3>
									<a
										href="product-detail.php?model=Core%20i5-12400F"
										class="text-decoration-none text-dark"
									>
										CPU Intel Core i5-12400F
									</a>
								</h3>
								<strong class="product-price">3.200.000 đ</strong>
								<button class="btn btn-outline-primary w-100 mt-3">
									<i class="bi bi-cart-plus me-2"></i>
									Thêm vào giỏ
								</button>
							</article>
						</div>
					</div>
				</div>
			</section>
			<div class="container my-5">
				<div class="d-flex align-items-center mb-4">
					<h2>
						Chuyên trang khuyến mãi
					</h2>
				</div>
				<div class="row g-4 mb-4">
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden ratio ratio-21x9">
							<img
								src="assets/img/Banner KM/anh1.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 1"
							/>
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden ratio ratio-21x9">
							<img
								src="assets/img/Banner KM/anh2.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 2"
							/>
						</div>
					</div>
				</div>
				<div class="row g-4">
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden shadow-sm ratio ratio-21x9 hover-zoom">
							<img
								src="assets/img/Banner KM/anh3.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 3"
							/>
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden shadow-sm ratio ratio-21x9 hover-zoom">
							<img
								src="assets/img/Banner KM/anh4.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 4"
							/>
						</div>
					</div>
				</div>
			</div>
			<section
				class="section-space section-muted home-news-section"
				aria-labelledby="home-news-title"
			>
				<div class="container">
					<div class="section-heading">
						<div>
							<h2 id="home-news-title">Tin tức mới nhất</h2>
						</div>
						<a href="news.php" class="text-link">
							Xem tất cả
							<i class="bi bi-arrow-up-right"></i>
						</a>
					</div>
					<div class="row g-4">
						<div class="col-lg-6">
							<article class="news-feature-card h-100">
								<a href="news-post.php?post=pc-gaming" class="news-image-wrap">
									<img
										src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?auto=format&fit=crop&w=1200&q=85"
										alt="Bộ máy tính gaming với màn hình hiển thị"
									/>
								</a>
								<div class="news-card-body">
									<div class="news-meta">
										<span>Hướng dẫn</span>
										<time datetime="2026-09-16">16.09.2026</time>
									</div>
									<h3>
										<a href="news-post.php?post=pc-gaming">
											Hướng dẫn chọn cấu hình PC Gaming phù hợp từng nhu cầu
										</a>
									</h3>
									<p>
										Từ CPU, card đồ họa đến ngân sách: các điểm cần cân nhắc
										trước khi bắt đầu build PC.
									</p>
									<a class="news-read-link" href="news-post.php?post=pc-gaming">
										Đọc bài viết
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>
							</article>
						</div>
						<div class="col-lg-6">
							<div class="news-list-card">
								<article class="news-list-item">
									<a href="news-post.php?post=monitor" class="news-thumb">
										<img
											src="https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=600&q=80"
											alt="Không gian làm việc với nhiều màn hình"
										/>
									</a>
									<div>
										<div class="news-meta">
											<span>Thủ thuật</span>
											<time datetime="2026-09-14">14.09.2026</time>
										</div>
										<h3>
											<a href="news-post.php?post=monitor">
												Chọn màn hình cho công việc và giải trí: đừng bỏ qua
												4 thông số này
											</a>
										</h3>
									</div>
								</article>
								<article class="news-list-item">
									<a href="news-post.php?post=setup" class="news-thumb">
										<img
											src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80"
											alt="Góc máy tính với phụ kiện gaming"
										/>
									</a>
									<div>
										<div class="news-meta">
											<span>Gaming gear</span>
											<time datetime="2026-09-12">12.09.2026</time>
										</div>
										<h3>
											<a href="news-post.php?post=setup">
												5 nâng cấp nhỏ giúp góc máy gọn gàng và hiệu quả hơn
											</a>
										</h3>
									</div>
								</article>
								<article class="news-list-item">
									<a href="news-post.php?post=laptop" class="news-thumb">
										<img
											src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=600&q=80"
											alt="Laptop trên bàn làm việc"
										/>
									</a>
									<div>
										<div class="news-meta">
											<span>Tư vấn mua hàng</span>
											<time datetime="2026-09-10">10.09.2026</time>
										</div>
										<h3>
											<a href="news-post.php?post=laptop">
												Laptop cho sinh viên: ưu tiên hiệu năng, pin hay
												tính cơ động?
											</a>
										</h3>
									</div>
								</article>
							</div>
						</div>
					</div>
				</div>
			</section>
			<div class="feedback-customer">
				<div class="container">
					<div class="content-feedback d-flex">
						<div class="left-content-feedback">
							<b>Cảm ơn</b>
							<b class="red">1.000.000+</b>
							<b>KHÁCH HÀNG ĐÃ VÀ ĐANG CHỌN</b>0
							<div class="list-star d-flex align-items">
								<i class="bi bi-star-fill" aria-hidden="true"></i>
								<i class="bi bi-star-fill" aria-hidden="true"></i>
								<i class="bi bi-star-fill" aria-hidden="true"></i>
								<i class="bi bi-star-fill" aria-hidden="true"></i>
								<i class="bi bi-star-fill" aria-hidden="true"></i>
							</div>
							<img
								src="assets/img/feedback/logo-feedback.png"
								width="126"
								height="65"
								alt="TNC Store"
							/>
						</div>
						<div
							class="list-feedback carousel slide"
							id="js-slider-feedback"
							data-bs-ride="carousel"
						>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<a href="products.php" class="item">
										<img
											src="assets/img/feedback/anh-khach-hang-18-12.jpg"
											width="2047"
											height="1358"
											alt="Khách hàng TNC Store"
										/>
									</a>
									<a href="products.php" class="item">
										<img
											src="assets/img/feedback/anh-khach-hang-19-12-2.jpg"
											width="2047"
											height="1358"
											alt="Khách hàng TNC Store"
										/>
									</a>
								</div>
								<div class="carousel-item">
									<a href="products.php" class="item">
										<img
											src="assets/img/feedback/anh-khach-hang-19-12-2.jpg"
											width="2047"
											height="1358"
											alt="Khách hàng TNC Store"
										/>
									</a>
									<a href="products.php" class="item">
										<img
											src="assets/img/feedback/11_01-cead646c48b7b3e9c83eeb98bdb47e101.jpg"
											width="2047"
											height="1358"
											alt="Khách hàng TNC Store"
										/>
									</a>
								</div>
								<div class="carousel-item">
									<a href="products.php" class="item">
										<img
											src="assets/img/feedback/11_01-cead646c48b7b3e9c83eeb98bdb47e101.jpg"
											width="2047"
											height="1358"
											alt="Khách hàng TNC Store"
										/>
									</a>
									<a href="products.php" class="item">
										<img
											src="assets/img/feedback/anh-khach-hang-18-12.jpg"
											width="2047"
											height="1358"
											alt="Khách hàng TNC Store"
										/>
									</a>
								</div>
							</div>
							<button
								class="carousel-control-prev"
								type="button"
								data-bs-target="#js-slider-feedback"
								data-bs-slide="prev"
							>
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Trước</span>
							</button>
							<button
								class="carousel-control-next"
								type="button"
								data-bs-target="#js-slider-feedback"
								data-bs-slide="next"
							>
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Sau</span>
							</button>
							<div class="carousel-indicators">
								<button
									type="button"
									data-bs-target="#js-slider-feedback"
									data-bs-slide-to="0"
									class="active"
									aria-current="true"
									aria-label="Nhóm ảnh 1"
								></button>
								<button
									type="button"
									data-bs-target="#js-slider-feedback"
									data-bs-slide-to="1"
									aria-label="Nhóm ảnh 2"
								></button>
								<button
									type="button"
									data-bs-target="#js-slider-feedback"
									data-bs-slide-to="2"
									aria-label="Nhóm ảnh 3"
								></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<div class="brand-slider-container">
			<div class="brand-slider-title">Thương hiệu đồng hành</div>
			<div class="brand-slider">
				<div class="brand-track">
					<div class="brand-item">
						<img src="assets/img/branding/intel.jpg" alt="Intel" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/amd.jpg" alt="AMD" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/gigabyte.png" alt="Gigabyte" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/lenovo.png" alt="Lenovo" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/asus.png" alt="Asus" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/acer.png" alt="Acer" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/msi.png" alt="MSI" />
					</div>
					<div class="brand-item"><img src="assets/img/branding/lg.png" alt="LG" /></div>
					<div class="brand-item">
						<img src="assets/img/branding/sony.png" alt="Sony" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/razer.png" alt="Razer" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/intel.jpg" alt="Intel" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/amd.jpg" alt="AMD" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/gigabyte.png" alt="Gigabyte" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/lenovo.png" alt="Lenovo" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/asus.png" alt="Asus" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/acer.png" alt="Acer" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/msi.png" alt="MSI" />
					</div>
					<div class="brand-item"><img src="assets/img/branding/lg.png" alt="LG" /></div>
					<div class="brand-item">
						<img src="assets/img/branding/sony.png" alt="Sony" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/razer.png" alt="Razer" />
					</div>
				</div>
			</div>
		</div>
		<?php require 'partial/footer.php'?>
		<script src="js/csv-parser.js"></script>
		<script src="js/home.js"></script>
		<script src="js/header.js"></script>
	</body>
</html>

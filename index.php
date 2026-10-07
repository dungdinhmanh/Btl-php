<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/partial/news-cards.php';

$homeColumns = [
	['heading' => 'TNC Channel', 'more' => 'news.php?category=tnc-channel', 'play' => true, 'category' => 'tnc-channel', 'posts' => []],
	['heading' => 'Tin tức', 'more' => 'news.php', 'play' => false, 'category' => null, 'posts' => []],
];

try {
	$newsRepository = new NewsRepository(database());
	foreach ($homeColumns as &$column) {
		$column['posts'] = $newsRepository->all(4, 0, $column['category']);
	}
	unset($column);
} catch (Throwable $exception) {
	error_log($exception->getMessage());
}

$homeNewsAvailable = array_reduce(
	$homeColumns,
	static fn (bool $available, array $column): bool => $available || $column['posts'] !== [],
	false,
);
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
					'folders' => ['ram', 'ssd'],
				],
				[
					'title' => 'Màn Hình Máy Tính nổi bật',
					'banner' => 'cat_big_68_1764436032.jpg',
					'folders' => ['display'],
				],
				[
					'title' => 'Máy chơi game - Console nổi bật',
					'banner' => 'cat_big_217_1764436040.jpg',
					'folders' => ['phu-kien'],
				],
				[
					'title' => 'Gaming Gears nổi bật',
					'banner' => 'cat_big_78_1764436048.jpg',
					'folders' => ['phu-kien', 'case'],
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
			<?php if ($homeNewsAvailable): ?>
			<section
				class="section-space section-muted home-news-section"
				aria-labelledby="home-news-title"
			>
				<div class="container my-4">
			    
			<link rel="stylesheet" href="css/home-news.css">
			<div class="row g-5">
			    <?php foreach ($homeColumns as $column): ?>
			        <div class="col-lg-6">
			            <div class="home-news-col">
			                <div class="home-news-head">
			                    <h2><span><?= e($column['heading']) ?></span></h2>
			                    <a href="<?= e($column['more']) ?>" class="home-news-more">Xem tất cả <i class="bi bi-arrow-right"></i></a>
			                </div>
			                <div class="home-news-list">
			                    <?php foreach ($column['posts'] as $post): ?>
			                        <article class="home-news-item">
			                            <a href="<?= e(newsPostUrl($post)) ?>" class="home-news-thumb">
			                                <img src="<?= e(newsCover($post)) ?>" alt="<?= e($post['title']) ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/img/branding/tnc.png'">
			                                <?php if ($column['play']): ?>
			                                    <span class="home-news-play"><i class="bi bi-play-fill"></i></span>
			                                <?php endif; ?>
			                            </a>
			                            <div class="home-news-body">
			                                <h3><a href="<?= e(newsPostUrl($post)) ?>"><?= e($post['title']) ?></a></h3>
			                                <?php if (!empty($post['excerpt'])): ?>
			                                    <p><?= e($post['excerpt']) ?></p>
			                                <?php endif; ?>
			                                <div class="home-news-meta">
			                                    <span><i class="bi bi-clock"></i><?= e($post['dateTime']) ?></span>
			                                    <span><i class="bi bi-eye"></i><?= number_format($post['views'], 0, ',', '.') ?></span>
			                                </div>
			                            </div>
			                        </article>
			                    <?php endforeach; ?>
			                </div>
			            </div>
			        </div>
			    <?php endforeach; ?>
			</div>
			</section>
			<?php endif; ?>
			<div class="feedback-customer">
				<div class="container">
					<div class="content-feedback d-flex">
						<div class="left-content-feedback">
							<b>Cảm ơn</b>
							<b class="red">1.000.000+</b>
							<b>KHÁCH HÀNG ĐÃ VÀ ĐANG CHỌN</b>
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
						<div class="list-feedback" id="js-slider-feedback" data-feedback-carousel>
							<div class="feedback-viewport">
								<div class="feedback-track">
									<a href="products.php" class="item">
										<img src="assets/img/feedback/11_01-cead646c48b7b3e9c83eeb98bdb47e101.jpg" width="2047" height="1358" alt="Khách hàng TNC Store" loading="lazy" />
									</a>
									<a href="products.php" class="item">
										<img src="assets/img/feedback/anh-khach-hang-19-12-2.jpg" width="2047" height="1358" alt="Khách hàng TNC Store" loading="lazy" />
									</a>
									<a href="products.php" class="item">
										<img src="assets/img/feedback/anh-khach-hang-18-12.jpg" width="2047" height="1358" alt="Khách hàng TNC Store" loading="lazy" />
									</a>
									<a href="products.php" class="item">
										<img src="assets/img/feedback/anh-khach-hang-19-12.jpg" width="2047" height="1358" alt="Khách hàng TNC Store" loading="lazy" />
									</a>
									<a href="products.php" class="item">
										<img src="assets/img/feedback/anh-khach-hang-18-12-1.jpg" width="2047" height="1358" alt="Khách hàng TNC Store" loading="lazy" />
									</a>
								</div>
							</div>
							<button type="button" class="feedback-nav feedback-prev" aria-label="Ảnh trước">
								<i class="bi bi-chevron-left" aria-hidden="true"></i>
							</button>
							<button type="button" class="feedback-nav feedback-next" aria-label="Ảnh tiếp theo">
								<i class="bi bi-chevron-right" aria-hidden="true"></i>
							</button>
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
		<script src="js/home.js"></script>
		<script src="js/header.js"></script>
	</body>
</html>

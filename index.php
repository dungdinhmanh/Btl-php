<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/partial/news-cards.php';

$brandingImages = array_values(array_filter(
	glob(__DIR__ . '/assets/img/branding/*') ?: [],
	static fn (string $path): bool => is_file($path)
		&& in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'], true),
));
sort($brandingImages, SORT_NATURAL | SORT_FLAG_CASE);
$brandScrollDuration = max(30, count($brandingImages) * 3);

$homeColumns = [
	[
		'heading' => 'TNC Channel',
		'more' => 'news.php?category=tin-tuc-video',
		'video' => true,
		'category' => 'tin-tuc-video',
		'exclude' => [],
		'posts' => [],
	],
	[
		'heading' => 'Tin tức',
		'more' => 'news.php',
		'video' => false,
		'category' => null,
		'exclude' => ['tin-tuc-video', 'chuyen-trang-khuyen-mai'],
		'posts' => [],
	],
];

// Bốn banner khuyến mãi đang được ghim trên trang chủ gốc, giữ đúng thứ tự.
$promoPostSlugs = [
	'chon-amd-so-huu-onimusha-way-of-the-sword',
	'san-qua-asus-rog-edition-20',
	'build-pc-asus-rinh-qua-het-nac-tnc-store',
	'build-pc-gigabyte-intel-nhan-voucher',
];

$newsRepository = null;
try {
	$newsRepository = new NewsRepository(database());
	foreach ($homeColumns as &$column) {
		$column['posts'] = $column['exclude'] === []
			? $newsRepository->all(4, 0, $column['category'])
			: $newsRepository->latestExcludingCategories($column['exclude'], 4);
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

$promoPosts = [];
try {
	$newsRepository ??= new NewsRepository(database());
	$promoPosts = $newsRepository->bySlugs($promoPostSlugs);

	// Nếu thiếu bài ghim, bổ sung bằng các bài khuyến mãi mới nhất để block vẫn đủ 4 banner.
	if (count($promoPosts) < 4) {
		$seenIds = array_column($promoPosts, 'id');
		foreach ($newsRepository->all(4, 0, 'chuyen-trang-khuyen-mai') as $post) {
			if (in_array($post['id'], $seenIds, true)) {
				continue;
			}
			$promoPosts[] = $post;
			$seenIds[] = $post['id'];
			if (count($promoPosts) >= 4) {
				break;
			}
		}
	}
} catch (Throwable $exception) {
	error_log($exception->getMessage());
}
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
		<link rel="stylesheet" href="css/home-news.css?v=<?= (int) filemtime(__DIR__ . '/css/home-news.css') ?>" />
	</head>
	<body>
		<?php require 'partial/header.php'?>
		<main>
			<?php require 'partial/promo.php'; ?>
			<?php require 'partial/sale.php'; ?>
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
							<div class="product-carousel" data-product-carousel>
								<button class="product-carousel-nav product-carousel-prev" type="button" data-product-scroll="-1" aria-label="Sản phẩm trước" hidden>
									<i class="bi bi-chevron-left" aria-hidden="true"></i>
								</button>
								<div class="product-list" data-product-list role="region" aria-label="Danh sách sản phẩm" tabindex="0"></div>
								<button class="product-carousel-nav product-carousel-next" type="button" data-product-scroll="1" aria-label="Sản phẩm tiếp theo" hidden>
									<i class="bi bi-chevron-right" aria-hidden="true"></i>
								</button>
							</div>
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
					<div class="row g-4" id="featured-products-list"></div>
				</div>
			</section>
			<?php if ($promoPosts !== []): ?>
			<div class="home-promo-section">
				<h2 class="home-promo-title">Chuyên trang khuyến mãi</h2>
				<div class="home-promo-list">
					<?php foreach (array_slice($promoPosts, 0, 4) as $promo): ?>
						<article class="home-promo-item">
							<a href="<?= e(newsPostUrl($promo)) ?>" aria-label="<?= e($promo['title']) ?>">
								<img
									src="<?= e(newsCover($promo)) ?>"
									alt="<?= e($promo['title']) ?>"
									loading="lazy"
									onerror="this.onerror=null;this.src='assets/img/branding/tnc.png'"
								/>
							</a>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($homeNewsAvailable): ?>
			<section
				class="section-space section-muted home-news-section"
				aria-label="Tin tức và TNC Channel"
			>
				<div class="container home-news-container">
					<div class="home-news-grid">
						<?php foreach ($homeColumns as $column): ?>
							<div class="home-news-col">
								<div class="home-news-head">
									<h2><?= e($column['heading']) ?></h2>
									<a href="<?= e($column['more']) ?>" class="home-news-more">Xem tất cả <i class="bi bi-arrow-right"></i></a>
								</div>
								<div class="home-news-list">
									<?php foreach ($column['posts'] as $post): ?>
										<article class="home-news-item<?= $column['video'] ? ' home-news-item--video' : '' ?>">
											<a href="<?= e(newsPostUrl($post)) ?>" class="home-news-thumb">
												<img src="<?= e(newsCover($post)) ?>" alt="<?= e($post['title']) ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/img/branding/tnc.png'">
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
						<?php endforeach; ?>
					</div>
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
							<div class="feedback-carousel">
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
			</div>
		</main>
		<div class="list-brand">
			<div class="container background-white">
				<h2 class="title">Thương hiệu đồng hành</h2>
				<div class="brand-slider">
					<div class="brand-track" style="--brand-scroll-duration: <?= $brandScrollDuration ?>s">
						<?php foreach ([false, true] as $isClone): ?>
							<?php foreach ($brandingImages as $imagePath): ?>
								<?php
								$fileName = basename($imagePath);
								?>
								<div class="brand-item"<?= $isClone ? ' aria-hidden="true"' : '' ?>>
									<img
										src="<?= htmlspecialchars('assets/img/branding/' . $fileName, ENT_QUOTES, 'UTF-8') ?>"
										loading="lazy"
									/>
								</div>
							<?php endforeach; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php require 'partial/footer.php'?>
		<script src="js/home.js"></script>
		<script src="js/header.js"></script>
	</body>
</html>

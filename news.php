<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/partial/news-cards.php';

$activeCategory = trim((string) ($_GET['category'] ?? ''));
$posts = [];
$categories = [];
$loadError = false;

try {
    $repository = new NewsRepository(database());
    $categories = $repository->categories();
    $posts = $repository->all(12, 0, $activeCategory !== '' ? $activeCategory : null);
} catch (Throwable $exception) {
    $loadError = true;
}

// With a category filter, show a plain grid; otherwise feature + list + grid.
$filtered = $activeCategory !== '';
$feature = $filtered ? null : ($posts[0] ?? null);
$sideList = $filtered ? [] : array_slice($posts, 1, 3);
$gridPosts = $filtered ? $posts : array_slice($posts, 4);
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Tin tức công nghệ | TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main class="news-page">
			<div class="container" id="news-list-root">
				<header class="news-page-header">
					<p class="eyebrow">TNC Store / News</p>
					<h1>Tin tức công nghệ</h1>
					<p>
						Thông tin sản phẩm, hướng dẫn build PC và những gợi ý thiết thực để bạn chọn
						đúng thiết bị.
					</p>
				</header>

				<?php if ($loadError): ?>
					<p class="text-center text-muted py-5">Không tải được bài viết. Vui lòng thử lại sau.</p>
				<?php else: ?>
					<nav class="news-category-nav" aria-label="Chuyên mục">
						<a class="<?= $filtered ? '' : 'active' ?>" href="news.php">Mới nhất</a>
						<?php foreach ($categories as $category): ?>
							<a
								class="<?= $activeCategory === $category['slug'] ? 'active' : '' ?>"
								href="news.php?category=<?= e(rawurlencode($category['slug'])) ?>"
							>
								<?= e($category['name']) ?>
							</a>
						<?php endforeach; ?>
					</nav>

					<?php if (!$posts): ?>
						<p class="text-center text-muted py-5">Chưa có bài viết nào.</p>
					<?php else: ?>
						<?php if ($feature): ?>
							<section id="latest" aria-labelledby="latest-title">
								<div class="section-heading"><h2 id="latest-title">Bài viết mới nhất</h2></div>
								<div class="row g-4 mb-5">
									<div class="col-lg-7"><?= newsFeatureCard($feature) ?></div>
									<div class="col-lg-5">
										<div class="news-list-card">
											<?php foreach ($sideList as $post): ?>
												<?= newsListItem($post) ?>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
							</section>
						<?php endif; ?>

						<?php if ($gridPosts): ?>
							<section id="more" aria-labelledby="more-title">
								<div class="section-heading">
									<div>
										<p class="eyebrow">Chọn đúng, dùng tốt</p>
										<h2 id="more-title"><?= $filtered ? 'Bài viết trong chuyên mục' : 'Bài viết khác' ?></h2>
									</div>
								</div>
								<div class="row g-4">
									<?php foreach ($gridPosts as $post): ?>
										<div class="col-md-4"><?= newsGridCard($post) ?></div>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

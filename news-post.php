<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/partial/news-cards.php';

// 1) Which article? `post` is kept so old links (?post=pc-gaming) still work.
$slug = trim((string) ($_GET['slug'] ?? $_GET['post'] ?? ''));
$post = null;
$related = [];
$loadError = false;

// 2) Ask MySQL (through the repository) for that one row.
if ($slug !== '') {
    try {
        $repository = new NewsRepository(database());
        $post = $repository->findBySlug($slug);
        if ($post !== null) {
            $related = $repository->related($post['id'], 6);
        }
    } catch (Throwable $exception) {
        $loadError = true;
    }
}

// 3) No such row -> real 404 (search engines need the status code).
if ($post === null && !$loadError) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// 4) Everything the <head> needs comes from the same row.
$canonical = $post ? absoluteUrl(newsPostUrl($post)) : '';
$cover = $post && $post['coverImage'] ? absoluteUrl($post['coverImage']) : '';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?= $post ? e($post['title']) : 'Bài viết' ?> | TNC Store</title>
		<?php if ($post): ?>
			<meta name="description" content="<?= e($post['excerpt']) ?>" />
			<meta name="robots" content="index,follow" />
			<link rel="canonical" href="<?= e($canonical) ?>" />
			<meta property="og:type" content="article" />
			<meta property="og:title" content="<?= e($post['title']) ?>" />
			<meta property="og:description" content="<?= e($post['excerpt']) ?>" />
			<meta property="og:url" content="<?= e($canonical) ?>" />
			<?php if ($cover): ?><meta property="og:image" content="<?= e($cover) ?>" /><?php endif; ?>
		<?php endif; ?>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		
		<main class="article-page py-4 bg-light">
			<div class="container">
				<!-- Thanh điều hướng breadcrumb -->
				<div class="article-breadcrumb mb-3 text-muted small">
					<a href="index.php" class="text-decoration-none text-muted">Trang chủ</a>
					<span class="mx-1">›</span>
					<a href="news.php" class="text-decoration-none text-muted">Tin tức</a>
					<span class="mx-1">›</span>
					<span class="text-muted">Bài viết mới nhất</span>
					<span class="mx-1">›</span>
					<span class="text-dark"><?= e($post['category']) ?></span>
				</div>

				<!-- Bố cục 2 cột chính -->
				<div class="row g-4">
					<!-- CỘT TRÁI: NỘI DUNG CHI TIẾT BÀI VIẾT -->
					<div class="col-lg-8">
						<article class="article-shell bg-white p-4 rounded shadow-sm" id="news-article">
							<?php if ($loadError): ?>
								<p class="text-center text-muted py-5">Không tải được bài viết. Vui lòng thử lại sau.</p>
							<?php else: ?>
								<!-- Tiêu đề bài viết -->
								<h1 class="article-title fw-bold mb-3 text-dark" style="font-size: 1.75rem; line-height: 1.4;"><?= e($post['title']) ?></h1>
								
								<!-- Thông tin meta (ngày đăng, lượt xem) -->
								<div class="mb-3 text-muted small d-flex align-items-center gap-3">
									<?= newsMeta($post) ?>
								</div>

								<!-- Đoạn mở đầu (Lead) -->
								<?php if ($post['excerpt']): ?>
									<p class="article-lead text-secondary mb-4" style="font-size: 1.05rem; line-height: 1.6;"><?= e($post['excerpt']) ?></p>
								<?php endif; ?>

								<!-- Ảnh bìa hoặc Video minh họa -->
								<?php if ($post['coverImage']): ?>
									<figure class="article-hero mb-4 text-center">
										<img src="<?= e($post['coverImage']) ?>" alt="<?= e($post['title']) ?>" class="img-fluid rounded w-100" style="max-height: 450px; object-fit: cover;" />
									</figure>
								<?php endif; ?>

								<!-- Nội dung chi tiết bài viết -->
								<div class="article-content lh-lg text-dark"><?= $post['content'] /* trusted HTML authored by admins */ ?></div>
							<?php endif; ?>
						</article>
					</div>

					<!-- CỘT PHẢI: TIN LIÊN QUAN (SIDEBAR) -->
					<div class="col-lg-4">
						<?php if ($related): ?>
							<aside class="related-sidebar bg-white p-3 rounded shadow-sm" aria-labelledby="related-title">
								<!-- Tiêu đề sidebar viết hoa giống mẫu gốc -->
								<div class="section-heading mb-3 border-bottom pb-2">
									<h2 id="related-title" class="h6 fw-bold m-0 text-uppercase text-dark">Tin liên quan</h2>
								</div>
								
								<!-- Danh sách các bài viết liên quan dạng dọc -->
								<div class="d-flex flex-column gap-3">
									<?php foreach ($related as $item): ?>
										<div class="related-item-wrapper pb-2 border-bottom border-light">
											<?= newsGridCard($item) ?>
										</div>
									<?php endforeach; ?>
								</div>
							</aside>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>
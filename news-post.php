<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/partial/news-cards.php';

// 1) Which article? `post` is kept so old links (?post=pc-gaming) still work.
$slug = trim((string) ($_GET['slug'] ?? $_GET['post'] ?? ''));
$post = null;
$related = [];
$loadError = false;

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
if ($post === null) {
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
		
				<main class="article-page">
			<link rel="stylesheet" href="css/article-layout.css">
			<div class="np-layout">
				<article class="article-shell" id="news-article">
					<?php if ($loadError): ?>
						<p class="text-center text-muted py-5">Không tải được bài viết. Vui lòng thử lại sau.</p>
					<?php else: ?>
						<div class="article-breadcrumb">
							<a href="index.php">Trang chủ</a>
							<i class="bi bi-chevron-right mx-1"></i>
							<a href="news.php">Tin tức</a>
							<i class="bi bi-chevron-right mx-1"></i>
							<?= e($post['category']) ?>
						</div>
						<h1 class="article-title"><?= e($post['title']) ?></h1>
						<div class="article-info">
							<span><i class="bi bi-clock"></i><?= e($post['dateTime']) ?></span>
							<span><i class="bi bi-eye"></i><?= number_format($post['views'], 0, ',', '.') ?></span>
						</div>
						<?php if ($post['excerpt']): ?>
							<p class="article-lead"><?= e($post['excerpt']) ?></p>
						<?php endif; ?>
						<?php if ($post['coverImage']): ?>
							<figure class="article-hero">
								<img src="<?= e($post['coverImage']) ?>" alt="<?= e($post['title']) ?>" />
							</figure>
						<?php endif; ?>
						<div class="article-content"><?= $post['content'] /* trusted HTML authored by admins */ ?></div>
					<?php endif; ?>
				</article>

				<?php if ($related): ?>
					<aside class="np-related">
						<h2 class="np-related-title"><span>Tin liên quan</span></h2>
						<div class="list-article">
							<?php foreach ($related as $item): ?>
								<?= newsRelatedItem($item) ?>
							<?php endforeach; ?>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>
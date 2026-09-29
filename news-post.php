<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/partial/news-cards.php';

// `post` is kept so old links (news-post.php?post=pc-gaming) still work.
$slug = trim((string) ($_GET['slug'] ?? $_GET['post'] ?? ''));
$post = null;
$loadError = false;

if ($slug !== '') {
    try {
        $post = (new NewsRepository(database()))->findBySlug($slug);
    } catch (Throwable $exception) {
        $loadError = true;
    }
}

if ($post === null && !$loadError) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?= $post ? e($post['title']) : 'Bài viết' ?> | TNC Store</title>
		<?php if ($post && $post['excerpt']): ?>
			<meta name="description" content="<?= e($post['excerpt']) ?>" />
		<?php endif; ?>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main class="article-page">
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
					<?= newsMeta($post) ?>
					<h1 class="article-title"><?= e($post['title']) ?></h1>
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
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

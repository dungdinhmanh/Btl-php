<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new NewsRepository(database());

    $slug = trim((string) ($_GET['slug'] ?? ''));
    if ($slug !== '') {
        $post = $repository->findBySlug($slug);
        if ($post === null) {
            jsonResponse(['ok' => false, 'message' => 'Không tìm thấy bài viết.'], 404);
        }
        jsonResponse(['ok' => true, 'data' => $post]);
    }

    if (filter_var($_GET['categories'] ?? false, FILTER_VALIDATE_BOOL)) {
        jsonResponse(['ok' => true, 'data' => $repository->categories()]);
    }

    $limit = (int) ($_GET['limit'] ?? 8);
    $offset = (int) ($_GET['offset'] ?? 0);
    $category = trim((string) ($_GET['category'] ?? ''));

    jsonResponse([
        'ok' => true,
        'data' => $repository->all($limit, $offset, $category !== '' ? $category : null),
    ]);
} catch (Throwable $exception) {
    apiError($exception);
}

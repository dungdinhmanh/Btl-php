<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new ProductRepository(database());

    $identifier = trim((string) ($_GET['slug'] ?? $_GET['model'] ?? $_GET['id'] ?? ''));
    if ($identifier !== '' && !isset($_GET['featured']) && !isset($_GET['categories'])) {
        $product = $repository->findByIdentifier($identifier);
        if ($product === null) {
            jsonResponse(['ok' => false, 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }
        jsonResponse(['ok' => true, 'data' => $product]);
    }

    $limit = (int) ($_GET['limit'] ?? 24);

    if (filter_var($_GET['featured'] ?? false, FILTER_VALIDATE_BOOL)) {
        jsonResponse(['ok' => true, 'data' => $repository->featured($limit)]);
    }

    $categories = trim((string) ($_GET['categories'] ?? ''));
    if ($categories !== '') {
        $slugs = array_values(array_filter(array_map('trim', explode(',', $categories))));
        $groups = [];
        foreach ($repository->byCategories($slugs, (int) ($_GET['perCategory'] ?? 8)) as $slug => $items) {
            $groups[] = ['category' => $slug, 'items' => $items];
        }

        jsonResponse(['ok' => true, 'data' => $groups]);
    }

    $query = trim((string) ($_GET['q'] ?? ''));
    $categoryList = array_values(array_filter(array_map('trim', explode(',', (string) ($_GET['category'] ?? '')))));
    $brand = trim((string) ($_GET['brand'] ?? ''));
    $offset = (int) ($_GET['offset'] ?? 0);

    $products = $repository->search(
        $query,
        count($categoryList) === 1 ? $categoryList[0] : null,
        $limit,
        $offset,
        $brand !== '' ? $brand : null,
        trim((string) ($_GET['sort'] ?? 'newest')),
        count($categoryList) > 1 ? $categoryList : [],
    );

    jsonResponse([
        'ok' => true,
        'data' => $products,
        'total' => $repository->count($query, null, $brand !== '' ? $brand : null, $categoryList),
        'limit' => max(1, min($limit, 100)),
        'offset' => max(0, $offset),
    ]);
} catch (Throwable $exception) {
    apiError($exception);
}

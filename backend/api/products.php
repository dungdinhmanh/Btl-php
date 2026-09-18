<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new ProductRepository(database());
    $query = trim((string) ($_GET['q'] ?? ''));
    $category = trim((string) ($_GET['category'] ?? ''));
    $featured = filter_var($_GET['featured'] ?? false, FILTER_VALIDATE_BOOL);
    $products = $featured
        ? $repository->featured((int) ($_GET['limit'] ?? 4))
        : $repository->search($query, $category ?: null, (int) ($_GET['limit'] ?? 24));

    jsonResponse(['ok' => true, 'data' => $products]);
} catch (Throwable $exception) {
    apiError($exception);
}

<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new NewsRepository(database());
    $slug = trim((string) ($_GET['slug'] ?? ''));
    $data = $slug === '' ? $repository->latest((int) ($_GET['limit'] ?? 8)) : $repository->findBySlug($slug);
    jsonResponse(['ok' => true, 'data' => $data]);
} catch (Throwable $exception) {
    apiError($exception);
}

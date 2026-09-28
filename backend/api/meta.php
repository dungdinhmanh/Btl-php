<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new ProductRepository(database());

    jsonResponse([
        'ok' => true,
        'data' => [
            'categories' => $repository->categories(),
            'brands' => $repository->brands(),
        ],
    ]);
} catch (Throwable $exception) {
    apiError($exception);
}

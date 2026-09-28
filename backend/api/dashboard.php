<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new DashboardRepository(database());

    jsonResponse([
        'ok' => true,
        'data' => [
            'stats' => $repository->stats(),
            'lowStock' => $repository->lowStock((int) ($_GET['threshold'] ?? 10), (int) ($_GET['limit'] ?? 5)),
            'recentOrders' => $repository->recentOrders((int) ($_GET['orderLimit'] ?? 5)),
            'productStatus' => $repository->productStatusBreakdown(),
        ],
    ]);
} catch (Throwable $exception) {
    apiError($exception);
}

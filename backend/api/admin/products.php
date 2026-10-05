<?php

declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../src/Repositories/AdminProductRepository.php';

try {
    $repository = new AdminProductRepository(database());
    $requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    $rawBody = file_get_contents('php://input');
    $payload = $rawBody !== false && trim($rawBody) !== '' ? json_decode($rawBody, true) : [];
    if (!is_array($payload)) {
        $payload = [];
    }

    switch ($requestMethod) {
        case 'GET':
            jsonResponse([
                'ok' => true,
                'data' => $repository->list(),
            ]);

        case 'POST':
            $productId = $repository->create($payload);
            jsonResponse([
                'ok' => true,
                'message' => 'Sản phẩm đã được tạo.',
                'data' => ['id' => $productId],
            ]);

        case 'PUT':
            $productId = (int) ($payload['id'] ?? 0);
            if ($productId <= 0) {
                throw new InvalidArgumentException('Thiếu ID sản phẩm để cập nhật.');
            }

            $repository->update($productId, $payload);
            jsonResponse([
                'ok' => true,
                'message' => 'Sản phẩm đã được cập nhật.',
                'data' => ['id' => $productId],
            ]);

        case 'DELETE':
            $productId = (int) ($_GET['id'] ?? $payload['id'] ?? 0);
            if ($productId <= 0) {
                throw new InvalidArgumentException('Thiếu ID sản phẩm để xoá.');
            }

            $repository->delete($productId);
            jsonResponse([
                'ok' => true,
                'message' => 'Sản phẩm đã được xoá.',
                'data' => ['id' => $productId],
            ]);

        default:
            http_response_code(405);
            jsonResponse([
                'ok' => false,
                'message' => 'Phương thức không được hỗ trợ.',
            ]);
    }
} catch (Throwable $exception) {
    apiError($exception);
}

<?php

declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../src/Repositories/AdminOrderRepository.php';

try {
    $repository = new AdminOrderRepository(database());
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
                'data' => [
                    'orders' => $repository->list(),
                    'statuses' => $repository->statuses(),
                ],
            ]);

        case 'PUT':
            $orderId = (int) ($payload['id'] ?? 0);
            $statusCode = (string) ($payload['status_code'] ?? $payload['statusCode'] ?? '');
            if ($orderId <= 0) {
                throw new InvalidArgumentException('Thiếu ID đơn hàng.');
            }

            $repository->updateStatus($orderId, $statusCode);
            jsonResponse([
                'ok' => true,
                'message' => 'Trạng thái đơn hàng đã được cập nhật.',
                'data' => ['id' => $orderId],
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

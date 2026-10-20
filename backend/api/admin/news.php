<?php

declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../src/Repositories/AdminNewsRepository.php';

try {
    $requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    $user = requireRole($requestMethod === 'DELETE' ? ADMIN_ROLES : STAFF_ROLES);
    $repository = new AdminNewsRepository(database());
    $rawBody = file_get_contents('php://input');
    $payload = $rawBody !== false && trim($rawBody) !== '' ? json_decode($rawBody, true) : [];
    if (!is_array($payload)) {
        $payload = [];
    }

    switch ($requestMethod) {
        case 'GET':
            $postId = (int) ($_GET['id'] ?? 0);
            if ($postId > 0) {
                $post = $repository->find($postId);
                if ($post === null) {
                    jsonResponse(['ok' => false, 'message' => 'Không tìm thấy bài viết.'], 404);
                }
                jsonResponse(['ok' => true, 'data' => $post]);
            }

            jsonResponse([
                'ok' => true,
                'data' => $repository->list(),
            ]);

        case 'POST':
            $postId = $repository->create($payload, $user['id']);
            $created = $repository->find($postId);
            jsonResponse([
                'ok' => true,
                'message' => 'Bài viết đã được tạo.',
                'data' => ['id' => $postId, 'slug' => $created['slug'] ?? ''],
            ]);

        case 'PUT':
            $postId = (int) ($payload['id'] ?? 0);
            if ($postId <= 0) {
                throw new InvalidArgumentException('Thiếu ID bài viết để cập nhật.');
            }
            if ($repository->find($postId) === null) {
                throw new InvalidArgumentException('Không tìm thấy bài viết để cập nhật.');
            }

            $repository->update($postId, $payload);
            $updated = $repository->find($postId);
            jsonResponse([
                'ok' => true,
                'message' => 'Bài viết đã được cập nhật.',
                'data' => ['id' => $postId, 'slug' => $updated['slug'] ?? ''],
            ]);

        case 'DELETE':
            $postId = (int) ($_GET['id'] ?? $payload['id'] ?? 0);
            if ($postId <= 0) {
                throw new InvalidArgumentException('Thiếu ID bài viết để xoá.');
            }

            $repository->delete($postId);
            jsonResponse([
                'ok' => true,
                'message' => 'Bài viết đã được xoá.',
                'data' => ['id' => $postId],
            ]);

        default:
            jsonResponse([
                'ok' => false,
                'message' => 'Phương thức không được hỗ trợ.',
            ], 405);
    }
} catch (Throwable $exception) {
    apiError($exception);
}

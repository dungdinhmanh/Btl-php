<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new ReviewRepository(database());
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    if ($method === 'GET') {
        // product chấp nhận cả id lẫn slug để tiện cho cả hai phía gọi.
        $product = trim((string) ($_GET['product'] ?? ''));
        $productId = (int) ($_GET['product_id'] ?? 0);
        if ($productId <= 0 && ctype_digit($product)) {
            $productId = (int) $product;
        }
        if ($productId <= 0 && $product !== '') {
            $statement = database()->prepare(
                "SELECT product_id FROM products WHERE product_slug = :slug AND product_status = 'active'",
            );
            $statement->execute([':slug' => $product]);
            $productId = (int) $statement->fetchColumn();
        }
        if ($productId <= 0) {
            jsonResponse(['ok' => false, 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        jsonResponse([
            'ok' => true,
            'data' => $repository->forProduct($productId, (int) ($_GET['limit'] ?? 50)),
        ]);
    }

    $user = currentUser();
    $isStaff = $user !== null && in_array($user['role'], STAFF_ROLES, true);
    $input = requestInput();

    if ($method === 'POST') {
        $name = (string) ($input['name'] ?? '');
        $phone = (string) ($input['phone'] ?? '');
        $content = (string) ($input['content'] ?? '');
        $parentId = (int) ($input['parent_id'] ?? 0);

        if ($parentId > 0) {
            $reviewId = $repository->reply(
                $parentId,
                $name,
                $phone,
                $content,
                $user['id'] ?? null,
                $isStaff,
            );
            jsonResponse([
                'ok' => true,
                'message' => 'Đã gửi trả lời.',
                'data' => ['id' => $reviewId],
            ]);
        }

        $productId = (int) ($input['product_id'] ?? 0);
        $slug = trim((string) ($input['slug'] ?? ''));
        if ($productId <= 0 && $slug !== '') {
            $statement = database()->prepare(
                "SELECT product_id FROM products WHERE product_slug = :slug AND product_status = 'active'",
            );
            $statement->execute([':slug' => $slug]);
            $productId = (int) $statement->fetchColumn();
        }

        $reviewId = $repository->create(
            $productId,
            (int) ($input['rating'] ?? 0),
            $name,
            $phone,
            $content,
            $user['id'] ?? null,
        );
        jsonResponse([
            'ok' => true,
            'message' => 'Cảm ơn bạn đã đánh giá sản phẩm.',
            'data' => ['id' => $reviewId],
        ]);
    }

    if ($method === 'PUT' || $method === 'DELETE') {
        $reviewId = (int) ($input['id'] ?? $_GET['id'] ?? 0);
        if ($reviewId <= 0) {
            throw new InvalidArgumentException('Thiếu ID đánh giá.');
        }

        if ($method === 'PUT') {
            $repository->updateContent($reviewId, (string) ($input['content'] ?? ''), $user['id'] ?? null, $isStaff);
            jsonResponse(['ok' => true, 'message' => 'Đã cập nhật đánh giá.']);
        }

        $repository->delete($reviewId, $user['id'] ?? null, $isStaff);
        jsonResponse(['ok' => true, 'message' => 'Đã xoá đánh giá.']);
    }

    jsonResponse(['ok' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
} catch (Throwable $exception) {
    apiError($exception);
}

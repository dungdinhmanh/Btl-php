<?php
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';

try {
    $repository = new ReviewRepository(database());
    $database = database();
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    if ($method === 'GET') {
        $status = (string) ($_GET['status'] ?? 'all');
        if (!in_array($status, ['all', 'published', 'hidden'], true)) {
            throw new InvalidArgumentException('Trạng thái đánh giá không hợp lệ.');
        }

        $search = trim((string) ($_GET['search'] ?? ''));
        $productId = (int) ($_GET['product_id'] ?? 0);
        $page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);
        if ($page === false || $page < 1) {
            throw new InvalidArgumentException('Số trang không hợp lệ.');
        }
        $limit = 20;

        $where = ['1 = 1'];
        $params = [];
        if ($status !== 'all') {
            $where[] = 'r.review_status = :status';
            $params[':status'] = $status;
        }
        if ($productId > 0) {
            $where[] = 'r.product_id = :product_id';
            $params[':product_id'] = $productId;
        }
        if (mb_strlen($search) > 100) {
            throw new InvalidArgumentException('Từ khoá tìm kiếm quá dài.');
        }
        if ($search !== '') {
            // MySQL native prepare (ATTR_EMULATE_PREPARES = false) không cho phép
            // dùng lại cùng một placeholder nhiều lần, nên mỗi vị trí một tên riêng.
            $where[] = '(r.reviewer_name LIKE :termName OR r.reviewer_phone LIKE :termPhone
                OR r.content LIKE :termContent OR p.product_name LIKE :termProduct)';
            $term = '%' . $search . '%';
            $params[':termName'] = $term;
            $params[':termPhone'] = $term;
            $params[':termContent'] = $term;
            $params[':termProduct'] = $term;
        }
        $whereSql = implode(' AND ', $where);

        $countStatement = $database->prepare(
            "SELECT COUNT(*) FROM product_reviews r
             INNER JOIN products p ON p.product_id = r.product_id
             WHERE {$whereSql}",
        );
        $countStatement->execute($params);
        $total = (int) $countStatement->fetchColumn();

        $statement = $database->prepare(
            "SELECT r.review_id AS id, r.product_id AS productId, p.product_name AS productName,
                    p.product_slug AS productSlug,
                    r.user_id AS userId, r.parent_review_id AS parentId, r.rating,
                    r.reviewer_name AS name, r.reviewer_phone AS phone, r.content,
                    r.is_staff AS isStaff, r.review_status AS status, r.created_at AS createdAt
             FROM product_reviews r
             INNER JOIN products p ON p.product_id = r.product_id
             WHERE {$whereSql}
             ORDER BY r.created_at DESC, r.review_id DESC
             LIMIT :limit OFFSET :offset",
        );
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', ($page - 1) * $limit, PDO::PARAM_INT);
        $statement->execute();

        $reviews = array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'productId' => (int) $row['productId'],
            'productName' => (string) $row['productName'],
            'productSlug' => (string) $row['productSlug'],
            'isReply' => $row['parentId'] !== null,
            'rating' => $row['rating'] !== null ? (int) $row['rating'] : null,
            'name' => (string) $row['name'],
            'phone' => (string) $row['phone'],
            'content' => (string) $row['content'],
            'isStaff' => (bool) $row['isStaff'],
            'isLoggedIn' => $row['userId'] !== null,
            'status' => (string) $row['status'],
            'createdAtText' => (new DateTimeImmutable((string) $row['createdAt']))->format('d/m/Y H:i'),
        ], $statement->fetchAll());

        jsonResponse([
            'ok' => true,
            'data' => [
                'reviews' => $reviews,
                'total' => $total,
                'page' => $page,
                'pages' => (int) ceil($total / $limit),
            ],
        ]);
    }

    $input = requestInput();
    $reviewId = (int) ($input['id'] ?? $_GET['id'] ?? 0);
    if ($reviewId <= 0) {
        throw new InvalidArgumentException('Thiếu ID đánh giá.');
    }

    if ($method === 'PUT') {
        $repository->setStatus($reviewId, (string) ($input['status'] ?? ''));
        jsonResponse(['ok' => true, 'message' => 'Đã cập nhật trạng thái đánh giá.']);
    }

    if ($method === 'DELETE') {
        $repository->delete($reviewId, null, true);
        jsonResponse(['ok' => true, 'message' => 'Đã xoá đánh giá.']);
    }

    jsonResponse(['ok' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
} catch (Throwable $exception) {
    apiError($exception);
}

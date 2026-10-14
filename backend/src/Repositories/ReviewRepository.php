<?php
declare(strict_types=1);

/** Đánh giá gốc (có rating) và trả lời (không rating) dựng thành cây. */
final class ReviewRepository
{
    private const MAX_NAME = 150;
    private const MAX_PHONE = 25;
    private const MAX_CONTENT = 2000;
    private const MAX_DEPTH = 12;

    public function __construct(private readonly PDO $db) {}

    /**
     * Danh sách đánh giá của một sản phẩm, đã dựng thành cây.
     *
     * @return array{summary: array<string, mixed>, items: array<int, array<string, mixed>>}
     */
    public function forProduct(int $productId, int $limit = 50): array
    {
        $limit = max(1, min($limit, 200));

        $statement = $this->db->prepare(
            "SELECT review_id AS id, product_id AS productId, user_id AS userId,
                    parent_review_id AS parentId, rating, reviewer_name AS name,
                    reviewer_phone AS phone, content, is_staff AS isStaff, created_at AS createdAt
             FROM product_reviews
             WHERE product_id = :id AND review_status = 'published'
             ORDER BY created_at ASC, review_id ASC",
        );
        $statement->execute([':id' => $productId]);
        $rows = $statement->fetchAll();

        $summary = $this->summary($productId);

        return ['summary' => $summary, 'items' => $this->buildTree($rows, $limit)];
    }

    /** Điểm trung bình + phân bố sao, chỉ tính đánh giá gốc đang hiển thị. */
    public function summary(int $productId): array
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*) AS total,
                    COALESCE(AVG(rating), 0) AS average,
                    COALESCE(SUM(rating = 5), 0) AS five,
                    COALESCE(SUM(rating = 4), 0) AS four,
                    COALESCE(SUM(rating = 3), 0) AS three,
                    COALESCE(SUM(rating = 2), 0) AS two,
                    COALESCE(SUM(rating = 1), 0) AS one
             FROM product_reviews
             WHERE product_id = :id AND parent_review_id IS NULL AND review_status = 'published'",
        );
        $statement->execute([':id' => $productId]);
        $row = $statement->fetch();
        $total = (int) ($row['total'] ?? 0);

        return [
            'productId' => $productId,
            'total' => $total,
            'average' => round((float) ($row['average'] ?? 0), 1),
            'breakdown' => [
                5 => (int) ($row['five'] ?? 0),
                4 => (int) ($row['four'] ?? 0),
                3 => (int) ($row['three'] ?? 0),
                2 => (int) ($row['two'] ?? 0),
                1 => (int) ($row['one'] ?? 0),
            ],
        ];
    }

    /**
     * @throws InvalidArgumentException khi dữ liệu sai hoặc tài khoản đã đánh giá sản phẩm này
     */
    public function create(
        int $productId,
        int $rating,
        string $name,
        string $phone,
        string $content,
        ?int $userId = null,
    ): int {
        $this->assertProductExists($productId);
        $rating = $this->checkRating($rating);
        [$name, $phone, $content] = $this->checkFields($name, $phone, $content);

        $this->db->beginTransaction();
        try {
            if ($userId !== null && $this->hasRootReview($productId, $userId)) {
                throw new InvalidArgumentException('Bạn đã đánh giá sản phẩm này rồi.');
            }

            $statement = $this->db->prepare(
                'INSERT INTO product_reviews
                    (product_id, user_id, parent_review_id, root_user_id, rating, reviewer_name, reviewer_phone, content)
                 VALUES (:product_id, :user_id, NULL, :root_user_id, :rating, :name, :phone, :content)',
            );
            $statement->execute([
                ':product_id' => $productId,
                ':user_id' => $userId,
                ':root_user_id' => $userId,
                ':rating' => $rating,
                ':name' => $name,
                ':phone' => $phone,
                ':content' => $content,
            ]);

            $reviewId = (int) $this->db->lastInsertId();
            $this->refreshProductRating($productId);
            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            $this->rethrowDuplicate($exception);
        }

        return $reviewId;
    }

    /**
     * @param bool $isStaff nhân viên/quản trị được trả lời mọi nhánh
     * @throws InvalidArgumentException khi không có quyền trả lời nhánh đó
     */
    public function reply(
        int $parentReviewId,
        string $name,
        string $phone,
        string $content,
        ?int $userId = null,
        bool $isStaff = false,
    ): int {
        [$name, $phone, $content] = $this->checkFields($name, $phone, $content);

        $parent = $this->findNode($parentReviewId);
        if ($parent === null) {
            throw new InvalidArgumentException('Không tìm thấy bình luận cần trả lời.');
        }

        if (!$isStaff && !$this->ownsThread($parent['rootId'], $name, $phone, $userId)) {
            throw new InvalidArgumentException('Bạn chỉ có thể trả lời trong bình luận của mình.');
        }

        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare(
                'INSERT INTO product_reviews
                    (product_id, user_id, parent_review_id, root_user_id, rating, reviewer_name, reviewer_phone, content, is_staff)
                 VALUES (:product_id, :user_id, :parent_review_id, NULL, NULL, :name, :phone, :content, :is_staff)',
            );
            $statement->execute([
                ':product_id' => (int) $parent['productId'],
                ':user_id' => $userId,
                ':parent_review_id' => $parentReviewId,
                ':name' => $name,
                ':phone' => $phone,
                ':content' => $content,
                ':is_staff' => $isStaff ? 1 : 0,
            ]);

            $reviewId = (int) $this->db->lastInsertId();
            $this->db->commit(); // trả lời không đổi điểm trung bình nên không cần tính lại
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }

        return $reviewId;
    }

    public function updateContent(int $reviewId, string $content, ?int $userId, bool $isStaff): void
    {
        $content = trim($content);
        if ($content === '' || mb_strlen($content) > self::MAX_CONTENT) {
            throw new InvalidArgumentException('Nội dung đánh giá không hợp lệ.');
        }

        $node = $this->findNode($reviewId);
        if ($node === null) {
            throw new InvalidArgumentException('Không tìm thấy đánh giá cần sửa.');
        }
        if (!$isStaff && ($userId === null || (int) $node['userId'] !== $userId)) {
            throw new InvalidArgumentException('Bạn chỉ có thể sửa đánh giá của mình.');
        }

        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare(
                'UPDATE product_reviews SET content = :content WHERE review_id = :id',
            );
            $statement->execute([':content' => $content, ':id' => $reviewId]);
            $this->refreshProductRating((int) $node['productId']);
            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function delete(int $reviewId, ?int $userId, bool $isStaff): void
    {
        $node = $this->findNode($reviewId);
        if ($node === null) {
            throw new InvalidArgumentException('Không tìm thấy đánh giá cần xoá.');
        }
        if (!$isStaff && ($userId === null || (int) $node['userId'] !== $userId)) {
            throw new InvalidArgumentException('Bạn chỉ có thể xoá đánh giá của mình.');
        }

        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare('DELETE FROM product_reviews WHERE review_id = :id');
            $statement->execute([':id' => $reviewId]);
            $this->refreshProductRating((int) $node['productId']);
            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function setStatus(int $reviewId, string $status): void
    {
        if (!in_array($status, ['published', 'hidden'], true)) {
            throw new InvalidArgumentException('Trạng thái đánh giá không hợp lệ.');
        }

        $node = $this->findNode($reviewId);
        if ($node === null) {
            throw new InvalidArgumentException('Không tìm thấy đánh giá.');
        }

        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare('UPDATE product_reviews SET review_status = :status WHERE review_id = :id');
            $statement->execute([':status' => $status, ':id' => $reviewId]);
            $this->refreshProductRating((int) $node['productId']);
            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    /** Phải nằm trong transaction của thao tác ghi đang chạy. */
    public function refreshProductRating(int $productId): void
    {
        $statement = $this->db->prepare(
            "UPDATE products p
             SET p.rating_avg = COALESCE((
                     SELECT AVG(r.rating) FROM product_reviews r
                     WHERE r.product_id = p.product_id
                       AND r.parent_review_id IS NULL
                       AND r.review_status = 'published'
                 ), 0),
                 p.review_count = (
                     SELECT COUNT(*) FROM product_reviews r
                     WHERE r.product_id = p.product_id
                       AND r.parent_review_id IS NULL
                       AND r.review_status = 'published'
                 )
             WHERE p.product_id = :id",
        );
        $statement->execute([':id' => $productId]);
    }

    public function refreshAllProducts(): int
    {
        $statement = $this->db->prepare(
            "UPDATE products p
             SET p.rating_avg = COALESCE((
                     SELECT AVG(r.rating) FROM product_reviews r
                     WHERE r.product_id = p.product_id
                       AND r.parent_review_id IS NULL
                       AND r.review_status = 'published'
                 ), 0),
                 p.review_count = (
                     SELECT COUNT(*) FROM product_reviews r
                     WHERE r.product_id = p.product_id
                       AND r.parent_review_id IS NULL
                       AND r.review_status = 'published'
                 )",
        );

        return $statement->execute() ? $statement->rowCount() : 0;
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    private function buildTree(array $rows, int $limit): array
    {
        $nodes = [];
        $order = [];
        foreach ($rows as $row) {
            $id = (int) $row['id'];
            $nodes[$id] = [
                'id' => $id,
                'productId' => (int) $row['productId'],
                'userId' => $row['userId'] !== null ? (int) $row['userId'] : null,
                'parentId' => $row['parentId'] !== null ? (int) $row['parentId'] : null,
                'rating' => $row['rating'] !== null ? (int) $row['rating'] : null,
                'name' => (string) $row['name'],
                'phone' => (string) $row['phone'],
                'content' => (string) $row['content'],
                'isStaff' => (bool) $row['isStaff'],
                'createdAt' => (string) $row['createdAt'],
                'createdAtText' => $this->formatDate((string) $row['createdAt']),
                'date' => (new DateTimeImmutable((string) $row['createdAt']))->format('j/n/Y'),
                'children' => [],
            ];
            $order[] = $id;
        }

        $roots = [];
        foreach ($order as $id) {
            $parentId = $nodes[$id]['parentId'];
            if ($parentId !== null && isset($nodes[$parentId])) {
                $nodes[$parentId]['children'][] = $id;
            } else {
                $roots[] = $id;
            }
        }

        // Đánh giá gốc mới nhất lên đầu; nhánh trả lời giữ thứ tự cũ -> mới.
        rsort($roots);

        $result = [];
        $emitted = 0;
        foreach ($roots as $rootId) {
            if ($emitted >= $limit) {
                break;
            }
            $result[] = $this->materialize($nodes, $rootId, 0, $emitted, $limit);
            $emitted += count($nodes[$rootId]['children']) + 1;
        }

        return $result;
    }

    /**
     * @param array<int, array<string, mixed>> $nodes
     * @return array<string, mixed>
     */
    private function materialize(array $nodes, int $id, int $depth, int &$emitted, int $limit): array
    {
        $node = $nodes[$id];
        $children = [];
        if ($depth < self::MAX_DEPTH) {
            foreach ($node['children'] as $childId) {
                if ($emitted >= $limit) {
                    break;
                }
                $children[] = $this->materialize($nodes, $childId, $depth + 1, $emitted, $limit);
                ++$emitted;
            }
        }
        $node['children'] = $children;

        return $node;
    }

    /** Kèm review_id gốc của nhánh, dùng kiểm tra quyền trả lời. */
    private function findNode(int $reviewId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT review_id AS id, product_id AS productId, user_id AS userId,
                    parent_review_id AS parentId, root_user_id AS rootUserId, reviewer_name AS name,
                    reviewer_phone AS phone
             FROM product_reviews WHERE review_id = :id',
        );
        $statement->execute([':id' => $reviewId]);
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }

        $rootId = $row['rootUserId'] !== null
            ? $this->rootReviewIdForUser((int) $row['rootUserId'])
            : $this->rootReviewIdForPhone($row['name'], $row['phone']);

        return [
            'id' => (int) $row['id'],
            'productId' => (int) $row['productId'],
            'userId' => $row['userId'] !== null ? (int) $row['userId'] : null,
            'rootUserId' => $row['rootUserId'] !== null ? (int) $row['rootUserId'] : null,
            'name' => (string) $row['name'],
            'phone' => (string) $row['phone'],
            'rootId' => $rootId,
        ];
    }

    private function rootReviewIdForUser(int $userId): ?int
    {
        $statement = $this->db->prepare(
            'SELECT review_id FROM product_reviews WHERE root_user_id = :id ORDER BY review_id ASC LIMIT 1',
        );
        $statement->execute([':id' => $userId]);
        $id = $statement->fetchColumn();

        return $id === false ? null : (int) $id;
    }

    private function rootReviewIdForPhone(string $name, string $phone): ?int
    {
        $statement = $this->db->prepare(
            'SELECT review_id FROM product_reviews
             WHERE parent_review_id IS NULL AND user_id IS NULL
               AND reviewer_name = :name AND reviewer_phone = :phone
             ORDER BY review_id ASC LIMIT 1',
        );
        $statement->execute([':name' => $name, ':phone' => $phone]);
        $id = $statement->fetchColumn();

        return $id === false ? null : (int) $id;
    }

    private function ownsThread(?int $rootId, string $name, string $phone, ?int $userId): bool
    {
        if ($rootId === null) {
            return false;
        }

        $statement = $this->db->prepare(
            'SELECT user_id AS userId, reviewer_name AS name, reviewer_phone AS phone
             FROM product_reviews WHERE review_id = :id',
        );
        $statement->execute([':id' => $rootId]);
        $root = $statement->fetch();
        if ($root === false) {
            return false;
        }

        if ($userId !== null && $root['userId'] !== null && (int) $root['userId'] === $userId) {
            return true;
        }

        return (string) $root['name'] === $name && (string) $root['phone'] === $phone;
    }

    private function hasRootReview(int $productId, int $userId): bool
    {
        $statement = $this->db->prepare(
            'SELECT COUNT(*) FROM product_reviews WHERE product_id = :product AND root_user_id = :user',
        );
        $statement->execute([':product' => $productId, ':user' => $userId]);

        return (int) $statement->fetchColumn() > 0;
    }

    private function assertProductExists(int $productId): void
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*) FROM products WHERE product_id = :id AND product_status = 'active'",
        );
        $statement->execute([':id' => $productId]);
        if ((int) $statement->fetchColumn() === 0) {
            throw new InvalidArgumentException('Không tìm thấy sản phẩm để đánh giá.');
        }
    }

    private function checkRating(int $rating): int
    {
        if ($rating < 1 || $rating > 5) {
            throw new InvalidArgumentException('Vui lòng chọn số sao từ 1 đến 5.');
        }

        return $rating;
    }

    /** @return array{0: string, 1: string, 2: string} */
    private function checkFields(string $name, string $phone, string $content): array
    {
        $name = trim($name);
        $phone = trim($phone);
        $content = trim($content);

        if ($name === '' || mb_strlen($name) > self::MAX_NAME) {
            throw new InvalidArgumentException('Vui lòng nhập họ tên.');
        }
        if ($phone === '' || mb_strlen($phone) > self::MAX_PHONE) {
            throw new InvalidArgumentException('Vui lòng nhập số điện thoại.');
        }
        if ($content === '' || mb_strlen($content) > self::MAX_CONTENT) {
            throw new InvalidArgumentException('Vui lòng nhập nội dung đánh giá.');
        }

        return [$name, $phone, $content];
    }

    /** Ràng buộc UNIQUE có thể đã chặn trước khi hasRootReview() kịp thấy. */
    private function rethrowDuplicate(Throwable $exception): never
    {
        if ($exception instanceof PDOException && $exception->getCode() === '23000') {
            throw new InvalidArgumentException('Bạn đã đánh giá sản phẩm này rồi.');
        }

        throw $exception;
    }

    private function formatDate(string $value): string
    {
        try {
            return (new DateTimeImmutable($value))->format('d/m/Y H:i');
        } catch (Throwable) {
            return $value;
        }
    }
}

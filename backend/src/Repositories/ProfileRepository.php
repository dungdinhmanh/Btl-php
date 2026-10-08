<?php
declare(strict_types=1);

/**
 * Dữ liệu trang "Tài khoản của tôi" (khớp với những gì js/profile.js đọc):
 * thông tin người dùng, thống kê đơn hàng, danh sách đơn gần đây, địa chỉ mặc định.
 */
final class ProfileRepository
{
    /** Trạng thái được tính là "đang xử lý" trên thẻ thống kê. */
    private const PROCESSING = ['pending', 'confirmed', 'shipping'];

    public function __construct(private readonly PDO $db) {}

    public function user(int $userId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT full_name, email, phone, created_at FROM users WHERE user_id = :id AND account_status = :status',
        );
        $statement->execute([':id' => $userId, ':status' => 'active']);
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }

        return [
            'name' => $row['full_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'joinedAt' => (new DateTimeImmutable($row['created_at']))->format('d/m/Y'),
        ];
    }

    public function updateDetails(int $userId, string $name, string $email, string $phone, string $currentPassword): bool
    {
        $statement = $this->db->prepare(
            'SELECT password_hash FROM users WHERE user_id = :id AND account_status = :status',
        );
        $statement->execute([':id' => $userId, ':status' => 'active']);
        $passwordHash = $statement->fetchColumn();
        if (!is_string($passwordHash) || !password_verify($currentPassword, $passwordHash)) {
            return false;
        }

        $statement = $this->db->prepare(
            'UPDATE users SET full_name = :name, email = :email, phone = :phone
             WHERE user_id = :id AND account_status = :status',
        );
        $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone !== '' ? $phone : null,
            ':id' => $userId,
            ':status' => 'active',
        ]);

        return true;
    }

    public function updatePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $statement = $this->db->prepare(
            'SELECT password_hash FROM users WHERE user_id = :id AND account_status = :status',
        );
        $statement->execute([':id' => $userId, ':status' => 'active']);
        $passwordHash = $statement->fetchColumn();
        if (!is_string($passwordHash) || !password_verify($currentPassword, $passwordHash)) {
            return false;
        }

        $statement = $this->db->prepare(
            'UPDATE users SET password_hash = :password_hash
             WHERE user_id = :id AND account_status = :status',
        );
        $statement->execute([
            ':password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $userId,
            ':status' => 'active',
        ]);

        return true;
    }

    public function deleteAccount(int $userId, string $currentPassword): bool
    {
        $statement = $this->db->prepare(
            'SELECT password_hash FROM users WHERE user_id = :id AND account_status = :status',
        );
        $statement->execute([':id' => $userId, ':status' => 'active']);
        $passwordHash = $statement->fetchColumn();
        if (!is_string($passwordHash) || !password_verify($currentPassword, $passwordHash)) {
            return false;
        }

        $statement = $this->db->prepare(
            'UPDATE users SET account_status = :new_status
             WHERE user_id = :id AND account_status = :current_status',
        );
        $statement->execute([
            ':new_status' => 'deleted',
            ':id' => $userId,
            ':current_status' => 'active',
        ]);

        return $statement->rowCount() > 0;
    }

    /** @return array{total: int, processing: int, spent: float} */
    public function stats(int $userId): array
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*) AS total,
                    COALESCE(SUM(t.status_code IN ('pending', 'confirmed', 'shipping')), 0) AS processing,
                    COALESCE(SUM(CASE WHEN t.status_code <> 'cancelled' THEN t.total END), 0) AS spent
             FROM (
                 SELECT o.order_id, s.status_code,
                        COALESCE(SUM(i.quantity * i.unit_price), 0) + o.shipping_amount - o.discount_amount AS total
                 FROM orders o
                 INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
                 LEFT JOIN order_items i ON i.order_id = o.order_id
                 WHERE o.user_id = :id
                 GROUP BY o.order_id, s.status_code, o.shipping_amount, o.discount_amount
             ) t",
        );
        $statement->execute([':id' => $userId]);
        $row = $statement->fetch();

        return [
            'total' => (int) $row['total'],
            'processing' => (int) $row['processing'],
            'spent' => (float) $row['spent'],
        ];
    }

    public function orders(int $userId, int $limit = 10): array
    {
        $statement = $this->db->prepare(
            'SELECT o.order_number, o.placed_at, s.status_code, s.status_name,
                    COALESCE(SUM(i.quantity * i.unit_price), 0) + o.shipping_amount - o.discount_amount AS total
             FROM orders o
             INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
             LEFT JOIN order_items i ON i.order_id = o.order_id
             WHERE o.user_id = :id
             GROUP BY o.order_id, o.order_number, o.placed_at, s.status_code, s.status_name, o.shipping_amount, o.discount_amount
             ORDER BY o.placed_at DESC, o.order_id DESC
             LIMIT :limit',
        );
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':limit', max(1, min($limit, 50)), PDO::PARAM_INT);
        $statement->execute();

        return array_map(static fn (array $row): array => [
            'number' => $row['order_number'],
            'date' => (new DateTimeImmutable($row['placed_at']))->format('d/m/Y'),
            'statusCode' => $row['status_code'],
            'statusName' => $row['status_name'],
            'total' => (float) $row['total'],
        ], $statement->fetchAll());
    }

    public function defaultAddress(int $userId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT recipient_name, recipient_phone, line_1, line_2, ward, district, city
             FROM addresses WHERE user_id = :id AND is_default = 1 LIMIT 1',
        );
        $statement->execute([':id' => $userId]);
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }

        $parts = array_filter(
            [$row['line_1'], $row['line_2'], $row['ward'], $row['district'], $row['city']],
            static fn (?string $part): bool => $part !== null && trim($part) !== '',
        );

        return [
            'recipientName' => $row['recipient_name'],
            'recipientPhone' => $row['recipient_phone'],
            'text' => implode(', ', $parts),
        ];
    }
}

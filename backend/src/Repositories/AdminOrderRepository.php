<?php

declare(strict_types=1);

final class AdminOrderRepository
{
    public function __construct(private readonly PDO $db) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        $statement = $this->db->query(
            'SELECT o.order_id AS id, o.order_number AS number, o.placed_at AS placedAt,
                    COALESCE(addr.recipient_name, u.full_name) AS customerName, u.email AS customerEmail,
                    s.status_code AS statusCode, s.status_name AS statusName,
                    (COALESCE(oi.total, 0) + o.shipping_amount - o.discount_amount) AS total
             FROM orders o
             INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
             LEFT JOIN users u ON u.user_id = o.user_id
             LEFT JOIN order_addresses addr ON addr.order_id = o.order_id
             LEFT JOIN (
                 SELECT order_id, SUM(quantity * unit_price) AS total
                 FROM order_items
                 GROUP BY order_id
             ) oi ON oi.order_id = o.order_id
             ORDER BY o.placed_at DESC, o.order_id DESC',
        );

        return array_map(static function (array $row): array {
            $placedAt = $row['placedAt'] !== null ? strtotime((string) $row['placedAt']) : false;

            return [
                'id' => (int) $row['id'],
                'number' => (string) $row['number'],
                'customerName' => $row['customerName'] !== null ? (string) $row['customerName'] : 'Khách vãng lai',
                'customerEmail' => $row['customerEmail'] !== null ? (string) $row['customerEmail'] : '',
                'statusCode' => (string) $row['statusCode'],
                'statusName' => (string) $row['statusName'],
                'total' => (float) $row['total'],
                'totalText' => number_format((float) $row['total'], 0, ',', '.') . ' đ',
                'placedAt' => $row['placedAt'] !== null ? (string) $row['placedAt'] : null,
                'date' => $placedAt !== false ? date('d/m/Y', $placedAt) : '',
            ];
        }, $statement->fetchAll());
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function statuses(): array
    {
        $statement = $this->db->query(
            'SELECT status_code AS code, status_name AS name
             FROM order_statuses
             ORDER BY status_name ASC',
        );

        return array_map(static fn (array $row): array => [
            'code' => (string) $row['code'],
            'name' => (string) $row['name'],
        ], $statement->fetchAll());
    }

    public function updateStatus(int $orderId, string $statusCode): void
    {
        $statusCode = strtolower(trim($statusCode));
        if ($statusCode === '') {
            throw new InvalidArgumentException('Trạng thái đơn hàng không hợp lệ.');
        }

        $statement = $this->db->prepare(
            'UPDATE orders o
             INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
             SET o.order_status_id = (
                 SELECT os.order_status_id
                 FROM order_statuses os
                 WHERE os.status_code = :status_code
                 LIMIT 1
             )
             WHERE o.order_id = :order_id',
        );

        $statement->execute([
            ':status_code' => $statusCode,
            ':order_id' => $orderId,
        ]);
    }
}

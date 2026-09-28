<?php
declare(strict_types=1);

final class DashboardRepository
{
    public function __construct(private readonly PDO $db) {}

    /** @return array{revenueThisMonth: float, ordersThisMonth: int, ordersTotal: int, customersThisMonth: int, customersTotal: int, activeProducts: int, lowStockCount: int} */
    public function stats(): array
    {
        $revenue = (float) $this->db->query(
            "SELECT COALESCE(SUM(line.quantity * line.unit_price), 0)
             FROM order_items line
             INNER JOIN orders o ON o.order_id = line.order_id
             INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
             WHERE s.status_code <> 'cancelled'
               AND o.placed_at >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')",
        )->fetchColumn();

        $ordersThisMonth = (int) $this->db->query(
            "SELECT COUNT(*) FROM orders WHERE placed_at >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')",
        )->fetchColumn();

        $ordersTotal = (int) $this->db->query('SELECT COUNT(*) FROM orders')->fetchColumn();

        $customersThisMonth = (int) $this->db->query(
            "SELECT COUNT(*) FROM users WHERE created_at >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')",
        )->fetchColumn();

        $customersTotal = (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();

        $activeProducts = (int) $this->db->query(
            "SELECT COUNT(*) FROM products WHERE product_status = 'active'",
        )->fetchColumn();

        $lowStockCount = (int) $this->db->query(
            'SELECT COUNT(*) FROM (
                SELECT i.product_id
                FROM inventory i
                GROUP BY i.product_id
                HAVING SUM(i.quantity_on_hand - i.quantity_reserved) <= 10
             ) low',
        )->fetchColumn();

        return [
            'revenueThisMonth' => $revenue,
            'revenueThisMonthText' => number_format($revenue, 0, ',', '.') . ' đ',
            'ordersThisMonth' => $ordersThisMonth,
            'ordersTotal' => $ordersTotal,
            'customersThisMonth' => $customersThisMonth,
            'customersTotal' => $customersTotal,
            'activeProducts' => $activeProducts,
            'lowStockCount' => $lowStockCount,
        ];
    }

    public function lowStock(int $threshold = 10, int $limit = 5): array
    {
        $statement = $this->db->prepare(
            'SELECT p.product_id AS id, p.product_name AS name, p.product_slug AS slug,
                    b.brand_name AS brand, SUM(i.quantity_on_hand - i.quantity_reserved) AS available
             FROM inventory i
             INNER JOIN products p ON p.product_id = i.product_id
             LEFT JOIN brands b ON b.brand_id = p.brand_id
             WHERE p.product_status = :status
             GROUP BY p.product_id, p.product_name, p.product_slug, b.brand_name
             HAVING available <= :threshold
             ORDER BY available ASC, p.product_name ASC
             LIMIT :limit',
        );
        $statement->bindValue(':status', 'active');
        $statement->bindValue(':threshold', $threshold, PDO::PARAM_INT);
        $statement->bindValue(':limit', max(1, min($limit, 20)), PDO::PARAM_INT);
        $statement->execute();

        return array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'slug' => (string) $row['slug'],
            'brand' => $row['brand'] !== null ? (string) $row['brand'] : null,
            'available' => (int) $row['available'],
        ], $statement->fetchAll());
    }

    public function recentOrders(int $limit = 5): array
    {
        $statement = $this->db->prepare(
            "SELECT o.order_id AS id, o.order_number AS number, o.placed_at AS placedAt,
                    s.status_code AS statusCode, s.status_name AS statusName,
                    COALESCE(addr.recipient_name, u.full_name, 'Khách vãng lai') AS customer,
                    COALESCE(line.total, 0) + o.shipping_amount - o.discount_amount AS total
             FROM orders o
             INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
             LEFT JOIN users u ON u.user_id = o.user_id
             LEFT JOIN order_addresses addr ON addr.order_id = o.order_id
             LEFT JOIN (
                SELECT order_id, SUM(quantity * unit_price) AS total
                FROM order_items GROUP BY order_id
             ) line ON line.order_id = o.order_id
             ORDER BY o.placed_at DESC, o.order_id DESC
             LIMIT :limit",
        );
        $statement->bindValue(':limit', max(1, min($limit, 20)), PDO::PARAM_INT);
        $statement->execute();

        return array_map(static function (array $row): array {
            $total = (float) $row['total'];
            $placedAt = $row['placedAt'] !== null ? strtotime((string) $row['placedAt']) : false;

            return [
                'id' => (int) $row['id'],
                'number' => (string) $row['number'],
                'customer' => (string) $row['customer'],
                'placedAt' => $row['placedAt'] !== null ? (string) $row['placedAt'] : null,
                'date' => $placedAt !== false ? date('d/m/Y', $placedAt) : '',
                'total' => $total,
                'totalText' => number_format($total, 0, ',', '.') . ' đ',
                'statusCode' => (string) $row['statusCode'],
                'statusName' => (string) $row['statusName'],
            ];
        }, $statement->fetchAll());
    }

    public function productStatusBreakdown(): array
    {
        $statement = $this->db->query(
            'SELECT product_status AS status, COUNT(*) AS total FROM products GROUP BY product_status',
        );

        $breakdown = ['draft' => 0, 'active' => 0, 'archived' => 0];
        foreach ($statement->fetchAll() as $row) {
            $breakdown[(string) $row['status']] = (int) $row['total'];
        }

        return $breakdown;
    }
}

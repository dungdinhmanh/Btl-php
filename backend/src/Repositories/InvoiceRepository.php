<?php
declare(strict_types=1);

/**
 * Hóa đơn được dựng trực tiếp từ đơn hàng (orders + order_items + order_addresses),
 * không có bảng invoices riêng nên không thể lệch với đơn hàng.
 *
 * Đơn giá lấy từ order_items.unit_price (đã chụp lại lúc đặt hàng) nên đổi giá sản phẩm
 * sau này không làm đổi hóa đơn cũ. Tên và SKU sản phẩm thì lấy từ bảng products hiện tại.
 */
final class InvoiceRepository
{
    public function __construct(private readonly PDO $db) {}

    public function forOrder(string $orderNumber): ?array
    {
        $statement = $this->db->prepare(
            'SELECT o.order_id, o.order_number, o.user_id, o.placed_at, o.shipping_amount, o.discount_amount,
                    s.status_code, u.full_name, u.email, u.phone
             FROM orders o
             INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
             LEFT JOIN users u ON u.user_id = o.user_id
             WHERE o.order_number = :number',
        );
        $statement->execute([':number' => $orderNumber]);
        $order = $statement->fetch();
        if ($order === false) {
            return null;
        }
        $orderId = (int) $order['order_id'];

        $statement = $this->db->prepare(
            'SELECT i.line_number, p.product_name, p.sku, i.quantity, i.unit_price
             FROM order_items i
             INNER JOIN products p ON p.product_id = i.product_id
             WHERE i.order_id = :id
             ORDER BY i.line_number',
        );
        $statement->execute([':id' => $orderId]);

        $items = [];
        $subtotal = 0;
        foreach ($statement->fetchAll() as $row) {
            $unitPrice = (int) round((float) $row['unit_price']); // VND không có phần lẻ
            $lineTotal = (int) $row['quantity'] * $unitPrice;
            $subtotal += $lineTotal;
            $items[] = [
                'line' => (int) $row['line_number'],
                'name' => $row['product_name'],
                'sku' => $row['sku'],
                'quantity' => (int) $row['quantity'],
                'unitPrice' => $unitPrice,
                'lineTotal' => $lineTotal,
            ];
        }

        $shipping = (int) round((float) $order['shipping_amount']);
        $discount = (int) round((float) $order['discount_amount']);

        return [
            'ownerId' => $order['user_id'] === null ? null : (int) $order['user_id'],
            'orderNumber' => $order['order_number'],
            'invoiceNumber' => 'HD-' . $order['order_number'],
            'issuedAt' => (new DateTimeImmutable($order['placed_at']))->format('d/m/Y H:i'),
            'statusCode' => $order['status_code'],
            'customer' => [
                'name' => $order['full_name'] ?? 'Khách vãng lai',
                'email' => $order['email'],
                'phone' => $order['phone'],
            ],
            'address' => $this->address($orderId),
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => $subtotal + $shipping - $discount,
        ];
    }

    private function address(int $orderId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT recipient_name, recipient_phone, line_1, line_2, ward, district, city
             FROM order_addresses WHERE order_id = :id',
        );
        $statement->execute([':id' => $orderId]);
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

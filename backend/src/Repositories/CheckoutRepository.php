<?php
declare(strict_types=1);

final class CheckoutRepository
{
    public function __construct(private readonly PDO $db) {}

    /**
     * @param array{name: string, phone: string, address: string, city: string} $customer
     * @param array<int, array{product_id: int, quantity: int}> $items
     */
    public function createOrder(array $customer, array $items, ?int $userId): string
    {
        $quantities = [];
        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            if ($productId <= 0 || $quantity <= 0 || $quantity > 99) {
                throw new InvalidArgumentException('Thông tin sản phẩm trong giỏ hàng không hợp lệ.');
            }
            $quantities[$productId] = ($quantities[$productId] ?? 0) + $quantity;
            if ($quantities[$productId] > 99) {
                throw new InvalidArgumentException('Số lượng mỗi sản phẩm tối đa là 99.');
            }
        }
        if ($quantities === []) {
            throw new InvalidArgumentException('Giỏ hàng đang trống.');
        }

        $this->db->beginTransaction();
        try {
            $productStatement = $this->db->prepare(
                'SELECT unit_price FROM products
                 WHERE product_id = :id AND product_status = :status',
            );
            $lines = [];
            foreach ($quantities as $productId => $quantity) {
                $productStatement->execute([':id' => $productId, ':status' => 'active']);
                $price = $productStatement->fetchColumn();
                if ($price === false) {
                    throw new InvalidArgumentException('Một sản phẩm trong giỏ hàng hiện không còn được bán.');
                }
                $lines[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => (float) $price,
                ];
            }

            $statusId = $this->db->query(
                "SELECT order_status_id FROM order_statuses WHERE status_code = 'pending' LIMIT 1",
            )->fetchColumn();
            if ($statusId === false) {
                throw new RuntimeException('Pending order status is not configured.');
            }

            $orderNumber = 'TNC' . date('ymd') . strtoupper(bin2hex(random_bytes(6)));
            $statement = $this->db->prepare(
                'INSERT INTO orders (order_number, user_id, order_status_id, shipping_amount, discount_amount)
                 VALUES (:number, :user_id, :status_id, 0, 0)',
            );
            $statement->execute([
                ':number' => $orderNumber,
                ':user_id' => $userId,
                ':status_id' => (int) $statusId,
            ]);
            $orderId = (int) $this->db->lastInsertId();

            $itemStatement = $this->db->prepare(
                'INSERT INTO order_items (order_id, line_number, product_id, quantity, unit_price)
                 VALUES (:order_id, :line_number, :product_id, :quantity, :unit_price)',
            );
            foreach ($lines as $index => $line) {
                $itemStatement->execute([
                    ':order_id' => $orderId,
                    ':line_number' => $index + 1,
                    ':product_id' => $line['product_id'],
                    ':quantity' => $line['quantity'],
                    ':unit_price' => $line['unit_price'],
                ]);
            }

            $addressStatement = $this->db->prepare(
                'INSERT INTO order_addresses (order_id, recipient_name, recipient_phone, line_1, city)
                 VALUES (:order_id, :name, :phone, :address, :city)',
            );
            $addressStatement->execute([
                ':order_id' => $orderId,
                ':name' => $customer['name'],
                ':phone' => $customer['phone'],
                ':address' => $customer['address'],
                ':city' => $customer['city'],
            ]);

            $this->db->commit();
            return $orderNumber;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }
}
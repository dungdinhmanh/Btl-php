<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../src/Repositories/CheckoutRepository.php';

try {
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        jsonResponse(['ok' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
    }

    $input = requestInput();
    $customerInput = is_array($input['customer'] ?? null) ? $input['customer'] : [];
    $customer = [
        'name' => trim((string) ($customerInput['name'] ?? '')),
        'phone' => trim((string) ($customerInput['phone'] ?? '')),
        'address' => trim((string) ($customerInput['address'] ?? '')),
        'city' => trim((string) ($customerInput['city'] ?? '')),
    ];
    foreach ($customer as $value) {
        if ($value === '') {
            throw new InvalidArgumentException('Vui lòng nhập đầy đủ họ tên, số điện thoại, địa chỉ và tỉnh/thành phố.');
        }
    }
    if (
        strlen($customer['name']) > 600
        || strlen($customer['phone']) > 100
        || strlen($customer['address']) > 1020
        || strlen($customer['city']) > 480
    ) {
        throw new InvalidArgumentException('Thông tin giao hàng vượt quá độ dài cho phép.');
    }

    $items = $input['items'] ?? null;
    if (!is_array($items) || count($items) > 100) {
        throw new InvalidArgumentException('Giỏ hàng không hợp lệ.');
    }

    $orderNumber = (new CheckoutRepository(database()))->createOrder(
        $customer,
        $items,
        isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null,
    );
    $_SESSION['last_order_number'] = $orderNumber;

    jsonResponse([
        'ok' => true,
        'message' => 'Đơn hàng đã được tạo.',
        'data' => ['orderNumber' => $orderNumber],
    ], 201);
} catch (Throwable $exception) {
    apiError($exception);
}
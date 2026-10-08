<?php
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../src/Repositories/AdminCustomer.php';

try {
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        jsonResponse(['ok' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
    }

    $repository = new AdminCustomerRepository(database());
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    if ($method === 'GET') {
        $userId = (int) ($_GET['id'] ?? 0);
        if ($userId > 0) {
            $customer = $repository->detail($userId);
            if ($customer === null) {
                jsonResponse(['ok' => false, 'message' => 'Không tìm thấy khách hàng.'], 404);
            }
            jsonResponse(['ok' => true, 'data' => $customer]);
        }

        $search = trim((string) ($_GET['search'] ?? ''));
        $status = (string) ($_GET['status'] ?? 'all');
        $registeredFrom = (string) ($_GET['registeredFrom'] ?? '');
        $registeredTo = (string) ($_GET['registeredTo'] ?? '');
        $page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);
        $isValidDate = static function (string $date): bool {
            if ($date === '') {
                return true;
            }
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $matches) !== 1) {
                return false;
            }

            return checkdate((int) $matches[2], (int) $matches[3], (int) $matches[1]);
        };
        if (
            mb_strlen($search) > 100
            || !in_array($status, ['all', 'active', 'disabled', 'deleted'], true)
            || $page === false
            || $page < 1
            || !$isValidDate($registeredFrom)
            || !$isValidDate($registeredTo)
            || ($registeredFrom !== '' && $registeredTo !== '' && $registeredFrom > $registeredTo)
        ) {
            throw new InvalidArgumentException('Điều kiện lọc khách hàng không hợp lệ.');
        }

        jsonResponse([
            'ok' => true,
            'data' => [
                'stats' => $repository->stats(),
                'customers' => $repository->list($search, $status, $page, 20, $registeredFrom, $registeredTo),
            ],
        ]);
    }

    $input = requestInput();
    $userId = (int) ($input['id'] ?? $_GET['id'] ?? 0);
    if ($userId < 1) {
        throw new InvalidArgumentException('ID khách hàng không hợp lệ.');
    }

    if ($method === 'PUT') {
        $status = (string) ($input['status'] ?? '');
        if (!$repository->updateStatus($userId, $status)) {
            jsonResponse(['ok' => false, 'message' => 'Không tìm thấy khách hàng để cập nhật.'], 404);
        }
        jsonResponse(['ok' => true, 'message' => 'Đã cập nhật trạng thái tài khoản.']);
    }

    if ($method === 'DELETE') {
        if (!$repository->delete($userId)) {
            jsonResponse(['ok' => false, 'message' => 'Không tìm thấy khách hàng để xóa.'], 404);
        }
        jsonResponse(['ok' => true, 'message' => 'Tài khoản khách hàng đã được xóa vĩnh viễn.']);
    }

    jsonResponse(['ok' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
} catch (Throwable $exception) {
    apiError($exception);
}
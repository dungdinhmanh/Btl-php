<?php
require_once __DIR__ . '/../backend/bootstrap.php';
require_once __DIR__ . '/../backend/src/Repositories/AdminProductRepository.php';

$repo = new AdminProductRepository(database());
$categories = $repo->categories();
$brands = $repo->brands();
$adminName = $_SESSION['user']['name'] ?? 'Administrator';
$adminInitials = strtoupper(substr(preg_replace('/\s+/', '', (string) $adminName), 0, 2));
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Quản lý sản phẩm | TNC Store</title>
		<?php require __DIR__ . '/../partial/link.php'; ?>
	</head>
	<body class="admin-body">
		<aside id="adminSidebar" class="admin-sidebar">
			<a class="logo" href="../index.php">
				<img src="../assets/img/branding/tnc.png" alt="TNC Store" />
			</a>
			<p class="admin-label">Quản lý cửa hàng</p>
			<nav>
				<a href="../admin.php">
					<i class="bi bi-grid-1x2"></i>
					<span>Tổng quan</span>
				</a>
				<a class="active" href="products.php">
					<i class="bi bi-box-seam"></i>
					<span>Sản phẩm</span>
				</a>
				<a href="orders.php">
					<i class="bi bi-receipt"></i>
					<span>Đơn hàng</span>
				</a>
				<a href="customer.php">
					<i class="bi bi-chat-left-text"></i>
					<span>Khách hàng</span>
				</a>
			</nav>
			<button
				class="admin-sidebar-toggle"
				type="button"
				aria-controls="adminSidebar"
				aria-expanded="true"
				aria-label="Thu gọn thanh bên"
			>
				<i class="bi bi-layout-sidebar-inset" aria-hidden="true"></i>
				<span>Thu gọn</span>
			</button>
			<a class="admin-back" href="../index.php">
				<i class="bi bi-arrow-left"></i>
				<span>Về cửa hàng</span>
			</a>
		</aside>

		<main class="admin-main">
			<header class="admin-topbar">
				<div>
					<p class="eyebrow">Hôm nay: <?= htmlspecialchars(date('d/m/Y')) ?></p>
					<h1>Quản lý sản phẩm</h1>
				</div>
				<div class="admin-user">
					<span class="admin-avatar"><?= htmlspecialchars($adminInitials) ?></span>
					<span>
						<strong><?= htmlspecialchars($adminName) ?></strong>
						<small>Quản trị viên</small>
					</span>
					<i class="bi bi-chevron-down"></i>
				</div>
			</header>

			<section class="admin-panel">
				<div class="admin-panel-head">
					<div>
						<p class="eyebrow">Danh sách</p>
						<h2>Sản phẩm</h2>
					</div>
					<button class="btn btn-primary" type="button" data-product-create>
						<i class="bi bi-plus-lg me-2"></i>Thêm sản phẩm
					</button>
				</div>

				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th>ID</th>
								<th>Tên sản phẩm</th>
								<th>Danh mục</th>
								<th>Thương hiệu</th>
								<th>Giá</th>
								<th>Tồn kho</th>
								<th>Trạng thái</th>
								<th>Hành động</th>
							</tr>
						</thead>
						<tbody id="admin-product-table-body"></tbody>
					</table>
				</div>
			</section>
		</main>

		<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<form id="product-form">
						<div class="modal-header">
							<h5 class="modal-title" id="productModalTitle">Thêm sản phẩm</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body row g-3">
							<div class="col-md-6">
								<label class="form-label">Tên sản phẩm</label>
								<input type="text" class="form-control" name="name" required />
							</div>
							<div class="col-md-6">
								<label class="form-label">SKU</label>
								<input type="text" class="form-control" name="sku" />
							</div>
							<div class="col-md-6">
								<label class="form-label">Slug</label>
								<input type="text" class="form-control" name="slug" />
							</div>
							<div class="col-md-6">
								<label class="form-label">Socket</label>
								<input type="text" class="form-control" name="socket" placeholder="LGA1700, AM5..." />
							</div>
							<div class="col-md-6">
								<label class="form-label">Danh mục</label>
								<select class="form-select" name="category_id" required>
									<option value="">-- Chọn danh mục --</option>
									<?php foreach ($categories as $category): ?>
										<option value="<?= (int) $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label">Thương hiệu</label>
								<select class="form-select" name="brand_id">
									<option value="">-- Chọn thương hiệu --</option>
									<?php foreach ($brands as $brand): ?>
										<option value="<?= (int) $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label">Giá (đ)</label>
								<input type="number" min="0" step="1000" class="form-control" name="unit_price" required />
							</div>
							<div class="col-md-4">
								<label class="form-label">Tồn kho</label>
								<input type="number" min="0" step="1" class="form-control" name="stock" value="0" required />
							</div>
							<div class="col-md-4">
								<label class="form-label">Trạng thái</label>
								<select class="form-select" name="product_status">
									<option value="draft">Bản nháp</option>
									<option value="active" selected>Đang bán</option>
									<option value="archived">Lưu trữ</option>
								</select>
							</div>
							<div class="col-12">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="1" name="is_featured" id="product-featured" />
									<label class="form-check-label" for="product-featured">Nổi bật</label>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
							<button type="submit" class="btn btn-primary">Lưu</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script src="../js/api.js"></script>
		<script src="js/admin-products.js"></script>
	</body>
</html>

<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Sản phẩm | TNC Store</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" href="assets/favicon.png" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
			rel="stylesheet"
		/>
		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
		/>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main>
			<section class="section-space">
				<div class="container">
					<div class="row g-4">
						<aside class="col-lg-3">
							<div class="filter-panel">
								<h2>Bộ lọc</h2>
								<label class="filter-check">
									<input
										type="checkbox"
										value="components"
										data-category-filter
									/>
									PC & Linh kiện
									<span data-category-count="components">0</span>
								</label>
								<label class="filter-check">
									<input type="checkbox" value="keyboard" data-category-filter />
									Bàn phím
									<span data-category-count="keyboard">0</span>
								</label>
								<label class="filter-check">
									<input type="checkbox" value="monitor" data-category-filter />
									Màn hình
									<span data-category-count="monitor">0</span>
								</label>
								<label class="filter-check">
									<input type="checkbox" value="gaming" data-category-filter />
									Gaming gear
									<span data-category-count="gaming">0</span>
								</label>
								<hr />
								<label for="sort-price" class="filter-label">Sắp xếp theo</label>
								<select id="sort-price" class="form-select">
									<option>Nổi bật nhất</option>
									<option>Giá thấp đến cao</option>
									<option>Giá cao đến thấp</option>
								</select>
							</div>
						</aside>
						<div class="col-lg-9">
							<div class="row g-4" id="product-grid">
								<!-- Rendered dynamically from CSV -->
								<div class="col-12 py-5 text-center text-muted">
									<div
										class="spinner-border spinner-border-sm me-2"
										role="status"
									></div>
									Đang nạp danh sách sản phẩm...
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
		<script src="js/csv-parser.js"></script>
		<script src="js/products.js"></script>
		<script src="js/cart.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Sản phẩm | TNC Store</title>
		<?php require 'partial/link.php' ?>
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
    						<div id="category-filters"></div>
   							 <hr />
   							 <div class="brand-filter-section">
     					   <div class="brand-filter-title">
          					  <span>HÃNG SẢN XUẤT</span>
          					  <span class="brand-filter-toggle">⌄</span>
       						 </div>
        					<div id="brand-filters" class="brand-filter-list"></div>
  						  </div>
					</div>
						</aside>
						<div class="col-lg-9">
							<div class="row g-4" id="product-grid">
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
		<script src="js/products.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

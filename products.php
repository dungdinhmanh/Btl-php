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
              <div class="products-breadcrumb" id="products-breadcrumb">
                <a href="index.php" class="breadcrumb-home">Trang chủ</a>
             <span>›</span>
              <a href="products.php" class="current">Linh kiện máy tính</a>
                  </div>
                  <!-- Khu vực sản phẩm nổi bật theo danh mục -->
                  <div id="category-featured" style="display: none;"></div>
			<section class="products-page-banner">
				<div class="container">
					<div id="productBanner" class="carousel slide product-banner" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img
                src="assets/img/banned product/banner-products.png"
                class="d-block w-100"
                alt="Banner linh kiện máy tính"
            />
        </div>
        <div class="carousel-item">
            <img
                src="assets/img/banned product/banner-products2.png"
                class="d-block w-100"
                alt="Banner linh kiện máy tính"
            />
        </div>

        <div class="carousel-item">
            <img
                src="assets/img/banned product/banner-products3.png"
                class="d-block w-100"
                alt="Banner linh kiện máy tính"
            />
        </div>

    </div>

    <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#productBanner"
        data-bs-slide="prev"
    >
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#productBanner"
        data-bs-slide="next"
    >
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
					<div class="row g-4">
						<aside class="col-lg-3">
							<div class="filter-panel">
    						<h2>Bộ lọc sản phẩm</h2>
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
         				<div class="col-lg-9 product-list-panel">
       					 <div class="product-list-header">
           			 <h2 id="product-page-title">LINH KIỆN MÁY TÍNH</h2>
          		  <div class="product-list-info">
                <span id="product-count">0 sản phẩm</span>
                <span class="product-list-divider">|</span>
                <span>Hiển thị theo:</span>
                <select id="sort-price">
                    <option value="newest">Sắp xếp sản phẩm</option>
                    <option value="price-asc">Giá thấp đến cao</option>
                    <option value="price-desc">Giá cao đến thấp</option>
                    <option value="name-asc">Tên A-Z</option>
                </select>
            </div>
        </div>
        <div id="active-filters"></div>
    <div id="product-grid">
								<div class="col-12 py-5 text-center text-muted">
									<div
										class="spinner-border spinner-border-sm me-2"
										role="status"
									></div>
								</div>
									 <div class="row g-4" id="product-grid">Đang nạp danh sách sản phẩm...
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

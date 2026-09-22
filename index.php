<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>TNC Store</title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" href="assets/favicon.png" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
			crossorigin="anonymous"
		/>
		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
		/>
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link
			href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
			rel="stylesheet"
		/>
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
			crossorigin="anonymous"
		></script>
	</head>
	<body>
		<div class="banner-top">
			<a href=""></a>
			<img src="assets/img/banner/banner-ad.png" alt="Banner top" />
		</div>
		<?php require 'partial/header.php'?>
		<main>
			<section class="promo-carousel-section">
				<div id="storePromoCarousel" class="carousel slide" data-bs-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-indicators">
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="0"
								class="active"
								aria-current="true"
								aria-label="Back To School"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="1"
								aria-label="Build PC Asus Rinh quà hết nấc"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="2"
								aria-label="Build PC Gigabyte Intel"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="3"
								aria-label="Đồng hành trở về căn cứ Asus"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="4"
								aria-label="PC AI Gigabyte Web"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="5"
								aria-label="Razer len deal gear len doi"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="6"
								aria-label="Asus Miku Gear"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="7"
								aria-label="MSI Frieren"
							></button>
							<button
								type="button"
								data-bs-target="#storePromoCarousel"
								data-bs-slide-to="8"
								aria-label="Trang chu Asus T1 PC"
							></button>
						</div>
						<div class="carousel-item active promo-slide">
							<img
								src="assets/img/banner/banner-back-to-school-pc-1.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-build-pc-asus-rinh-qua-het-nac.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-build-pc-gigabyte-intel-pc.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-dong-hanh-tro-ve-can-cu-asus-pc.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-pc-ai-gigabyte-web-7.jpg"
								width="90%"
								height="80%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-razer-len-deal-gear-len-doi.png"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-trang-chu-asus-hiku.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-trang-chu-msi-frieren-mobile-1.jpg"
								width="100%"
							/>
						</div>
						<div class="carousel-item promo-slide">
							<img
								src="assets/img/banner/banner-trang-chu-asus-t1-pc-2.jpg"
								width="100%"
							/>
						</div>
					</div>
					<div class="container">
						<button
							class="carousel-control-prev"
							type="button"
							data-bs-target="#storePromoCarousel"
							data-bs-slide="prev"
						>
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</button>
						<button
							class="carousel-control-next"
							type="button"
							data-bs-target="#storePromoCarousel"
							data-bs-slide="next"
						>
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</button>
					</div>
				</div>
			</section>
			<section class="section-space">
				<div class="container">
					<h2 class="category-featured-title">Danh mục nổi bật</h2>
					<div class="category-grid">
						<a href="products.html?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>PC GAMING</strong>
								<span>Mua ngay - Giá đang rẻ</span>
							</div>
							<img src="assets/img/category/cat-pc-gaming-miku.jpg" alt="PC gaming" />
						</a>
						<a href="products.html?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>PC ĐỒ HỌA AI</strong>
								<span>Tối ưu công việc - Tối thiểu giá thành</span>
							</div>
							<img
								src="assets/img/category/cat-pc-do-hoa-asus.jpg"
								alt="PC đồ họa AI"
							/>
						</a>
						<a href="products.html?category=monitor" class="category-tile">
							<div class="category-tile-copy">
								<strong>MÀN HÌNH MÁY TÍNH</strong>
								<span>Thế giới màn hình giá rẻ</span>
							</div>
							<img
								src="assets/img/category/cat-man-hinh-may-tinh-1.png"
								alt="Màn hình máy tính"
							/>
						</a>
						<a href="products.html?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>VGA - CARD MÀN HÌNH</strong>
								<span>Tổng kho VGA rẻ nhất Hà Nội</span>
							</div>
							<img
								src="assets/img/category/cat-vga-card-man-hinh-2.png"
								alt="VGA card màn hình"
							/>
						</a>
						<a href="products.html?category=pc" class="category-tile">
							<div class="category-tile-copy">
								<strong>LAPTOP GAMING</strong>
								<span>Giá rẻ - Cấu hình khủng</span>
							</div>
							<img
								src="assets/img/category/cat-laptop-gaming-1.png"
								alt="Laptop gaming"
							/>
						</a>
						<a href="products.html?category=gaming-gear" class="category-tile">
							<div class="category-tile-copy">
								<strong>MÁY CHƠI GAME PS5</strong>
								<span>Chính hãng - Giá rẻ - Bảo hành 1 đổi 1</span>
							</div>
							<img
								src="assets/img/category/9223-ps5-slim.jpg"
								alt="Máy chơi game PS5"
							/>
						</a>
						<a href="products.html?category=gaming-gear" class="category-tile">
							<div class="category-tile-copy">
								<strong>NINTENDO SWITCH</strong>
								<span>Giá rẻ - Chơi game tuyệt đỉnh</span>
							</div>
							<img
								src="assets/img/category/cat-pc-handheld-1.png"
								alt="Nintendo Switch"
							/>
						</a>
						<a href="products.html?category=gaming-gear" class="category-tile">
							<div class="category-tile-copy">
								<strong>GHẾ GAMING</strong>
								<span>Rẻ, hiện đại, tối ưu công năng</span>
							</div>
							<img src="assets/img/category/cat-ghe-gaming-1.png" alt="Ghế gaming" />
						</a>
					</div>
				</div>
			</section>
			<section class="section-space section-muted">
				<div class="container">
					<div class="section-heading">
						<div>
							<h2>Sản phẩm nổi bật</h2>
						</div>
						<a href="products.html" class="text-link">
							Xem cửa hàng
							<i class="bi bi-arrow-up-right"></i>
						</a>
					</div>
					<div class="row g-4" id="featured-products-list">
						<div class="col-sm-6 col-lg-3 d-flex">
							<article class="product-card d-flex w-100 flex-column">
								<div class="product-image">
									<i class="bi bi-cpu"></i>
									<span class="product-tag">Bán chạy</span>
								</div>
								<p class="product-brand">Intel · Socket LGA1700</p>
								<h3>
									<a
										href="product-detail.html?model=Core%20i5-12400F"
										class="text-decoration-none text-dark"
									>
										CPU Intel Core i5-12400F
									</a>
								</h3>
								<strong class="product-price">3.200.000 đ</strong>
								<button class="btn btn-outline-primary w-100 mt-3">
									<i class="bi bi-cart-plus me-2"></i>
									Thêm vào giỏ
								</button>
							</article>
						</div>
					</div>
				</div>
			</section>
			<div class="container my-5">
				<div class="d-flex align-items-center mb-4">
					<h2>
						Chuyên trang khuyến mãi
					</h2>
				</div>
				<div class="row g-4 mb-4">
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden h-100 ">
							<img
								src="assets/img/Banner KM/anh1.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 1"
							/>
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden h-100 ">
							<img
								src="assets/img/Banner KM/anh2.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 2"
							/>
						</div>
					</div>
				</div>
				<div class="row g-4">
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden shadow-sm h-100 hover-zoom">
							<img
								src="assets/img/Banner KM/anh3.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 3"
							/>
						</div>
					</div>
					<!-- Banner 4 -->
					<div class="col-lg-6 col-md-12">
						<div class="overflow-hidden shadow-sm h-100 hover-zoom">
							<img
								src="assets/img/Banner KM/anh4.jpg"
								class="card-img object-fit-cover"
								alt="Khuyến mãi 4"
							/>
						</div>
					</div>
				</div>
			</div>
			<section
				class="section-space section-muted home-news-section"
				aria-labelledby="home-news-title"
			>
				<div class="container">
					<div class="section-heading">
						<div>
							<p class="eyebrow">Góc công nghệ TNC</p>
							<h2 id="home-news-title">Tin tức mới nhất</h2>
						</div>
						<a href="news.html" class="text-link">
							Xem tất cả
							<i class="bi bi-arrow-up-right"></i>
						</a>
					</div>
					<div class="row g-4">
						<div class="col-lg-6">
							<article class="news-feature-card h-100">
								<a href="news-post.html?post=pc-gaming" class="news-image-wrap">
									<img
										src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?auto=format&fit=crop&w=1200&q=85"
										alt="Bộ máy tính gaming với màn hình hiển thị"
									/>
								</a>
								<div class="news-card-body">
									<div class="news-meta">
										<span>Hướng dẫn</span>
										<time datetime="2026-09-16">16.09.2026</time>
									</div>
									<h3>
										<a href="news-post.html?post=pc-gaming">
											Hướng dẫn chọn cấu hình PC Gaming phù hợp từng nhu cầu
										</a>
									</h3>
									<p>
										Từ CPU, card đồ họa đến ngân sách: các điểm cần cân nhắc
										trước khi bắt đầu build PC.
									</p>
									<a class="news-read-link" href="news-post.html?post=pc-gaming">
										Đọc bài viết
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>
							</article>
						</div>
						<div class="col-lg-6">
							<div class="news-list-card">
								<article class="news-list-item">
									<a href="news-post.html?post=monitor" class="news-thumb">
										<img
											src="https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=600&q=80"
											alt="Không gian làm việc với nhiều màn hình"
										/>
									</a>
									<div>
										<div class="news-meta">
											<span>Thủ thuật</span>
											<time datetime="2026-09-14">14.09.2026</time>
										</div>
										<h3>
											<a href="news-post.html?post=monitor">
												Chọn màn hình cho công việc và giải trí: đừng bỏ qua
												4 thông số này
											</a>
										</h3>
									</div>
								</article>
								<article class="news-list-item">
									<a href="news-post.html?post=setup" class="news-thumb">
										<img
											src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80"
											alt="Góc máy tính với phụ kiện gaming"
										/>
									</a>
									<div>
										<div class="news-meta">
											<span>Gaming gear</span>
											<time datetime="2026-09-12">12.09.2026</time>
										</div>
										<h3>
											<a href="news-post.html?post=setup">
												5 nâng cấp nhỏ giúp góc máy gọn gàng và hiệu quả hơn
											</a>
										</h3>
									</div>
								</article>
								<article class="news-list-item">
									<a href="news-post.html?post=laptop" class="news-thumb">
										<img
											src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=600&q=80"
											alt="Laptop trên bàn làm việc"
										/>
									</a>
									<div>
										<div class="news-meta">
											<span>Tư vấn mua hàng</span>
											<time datetime="2026-09-10">10.09.2026</time>
										</div>
										<h3>
											<a href="news-post.html?post=laptop">
												Laptop cho sinh viên: ưu tiên hiệu năng, pin hay
												tính cơ động?
											</a>
										</h3>
									</div>
								</article>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="section-space">
				<div class="container">
					<div class="trust-strip">
						<div>
							<i class="bi bi-shield-check"></i>
							<span>
								<strong>Hàng chính hãng</strong>
								Đổi trả minh bạch
							</span>
						</div>
						<div>
							<i class="bi bi-truck"></i>
							<span>
								<strong>Giao hàng toàn quốc</strong>
								Đóng gói cẩn thận
							</span>
						</div>
						<div>
							<i class="bi bi-headset"></i>
							<span>
								<strong>Tư vấn tận tâm</strong>
								Hỗ trợ trước và sau mua
							</span>
						</div>
					</div>
				</div>
			</section>
		</main>
		<div
			class="modal fade"
			id="accountModal"
			tabindex="-1"
			aria-labelledby="accountModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content account-modal">
					<div class="modal-header border-0 pb-0">
						<h2 class="modal-title" id="accountModalLabel">Tài khoản</h2>
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="modal"
							aria-label="Đóng"
						></button>
					</div>
					<div class="modal-body pt-2">
						<ul class="nav nav-tabs account-tabs mb-4" role="tablist">
							<li class="nav-item" role="presentation">
								<button
									class="nav-link active"
									data-bs-toggle="tab"
									data-bs-target="#account-login-pane"
									type="button"
									role="tab"
								>
									Đăng nhập
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button
									class="nav-link"
									data-bs-toggle="tab"
									data-bs-target="#account-register-pane"
									type="button"
									role="tab"
								>
									Đăng ký
								</button>
							</li>
						</ul>
						<div class="tab-content">
							<div
								class="tab-pane fade show active"
								id="account-login-pane"
								role="tabpanel"
							>
								<form>
									<label for="modal-login-email">Email</label>
									<input
										id="modal-login-email"
										class="form-control mb-3"
										type="email"
										placeholder="you@example.com"
										required
									/>
									<label for="modal-login-password">Mật khẩu</label>
									<input
										id="modal-login-password"
										class="form-control mb-3"
										type="password"
										placeholder="••••••••"
										required
									/>
									<button class="btn btn-primary w-100" type="submit">
										Đăng nhập
									</button>
								</form>
								<a href="forgot-password.html" class="auth-link">Quên mật khẩu?</a>
							</div>
							<div class="tab-pane fade" id="account-register-pane" role="tabpanel">
								<form>
									<label for="modal-register-name">Họ và tên</label>
									<input
										id="modal-register-name"
										class="form-control mb-3"
										type="text"
										placeholder="Nguyễn Văn A"
										required
									/>
									<label for="modal-register-email">Email</label>
									<input
										id="modal-register-email"
										class="form-control mb-3"
										type="email"
										placeholder="you@example.com"
										required
									/>
									<label for="modal-register-password">Mật khẩu</label>
									<input
										id="modal-register-password"
										class="form-control mb-3"
										type="password"
										placeholder="Tối thiểu 8 ký tự"
										required
									/>
									<button class="btn btn-primary w-100" type="submit">
										Tạo tài khoản
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="brand-slider-container">
			<div class="brand-slider-title">Thương hiệu đồng hành</div>
			<div class="brand-slider">
				<div class="brand-track">
					<div class="brand-item">
						<img src="assets/img/branding/intel.jpg" alt="Intel" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/amd.jpg" alt="AMD" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/gigabyte.png" alt="Gigabyte" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/lenovo.png" alt="Lenovo" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/asus.png" alt="Asus" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/acer.png" alt="Acer" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/msi.png" alt="MSI" />
					</div>
					<div class="brand-item"><img src="assets/img/branding/lg.png" alt="LG" /></div>
					<div class="brand-item">
						<img src="assets/img/branding/sony.png" alt="Sony" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/razer.png" alt="Razer" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/intel.jpg" alt="Intel" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/amd.jpg" alt="AMD" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/gigabyte.png" alt="Gigabyte" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/lenovo.png" alt="Lenovo" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/asus.png" alt="Asus" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/acer.png" alt="Acer" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/msi.png" alt="MSI" />
					</div>
					<div class="brand-item"><img src="assets/img/branding/lg.png" alt="LG" /></div>
					<div class="brand-item">
						<img src="assets/img/branding/sony.png" alt="Sony" />
					</div>
					<div class="brand-item">
						<img src="assets/img/branding/razer.png" alt="Razer" />
					</div>
				</div>
			</div>
		</div>
		<?php require 'partial/footer.php'?>
		<script src="js/csv-parser.js"></script>
		<script src="js/home.js"></script>
		<script src="js/cart.js"></script>
		<script src="js/account.js"></script>
		<script src="js/header.js"></script>
	</body>
</html>

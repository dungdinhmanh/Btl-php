<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title id="page-title">Chi tiết sản phẩm | TNC Store</title>
		<?php require 'partial/link.php' ?>
		<link rel="stylesheet" href="css/product-detail.css" />
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<div class="product-detail">
			<div class="container">
				<!-- Trang chủ › danh mục CHA › danh mục LÁ, không có tên sản phẩm.
			     Nội dung danh mục do js/product-detail.js điền vào #breadcrumb-list. -->
				<div id="breadcrumb" class="breadcrumb">
					<ol id="breadcrumb-list">
						<li class="home">
							<a href="index.php" class="nopad-l">
								<span><i class="bi bi-house" aria-hidden="true"></i> Trang chủ</span>
							</a>
							<i class="bi bi-chevron-right" aria-hidden="true"></i>
						</li>
					</ol>
				</div>

				<div class="clearfix">
					<!-- ── Khối trên: ảnh · thông tin · cột phải ── -->
					<div class="main-product-detail background-white">
						<div class="content-main-product d-flex">
							<!-- Ảnh chính + thumbnail -->
							<div class="product-images-detail">
								<div id="sync1" class="image-big">
									<a href="javascript:void(0)">
										<img
											id="main-product-img"
											src="assets/img/branding/tnc.png"
											alt="Sản phẩm"
										/>
									</a>
								</div>
								<div id="sync2" class="image-thumb d-flex flex-wrap"></div>
							</div>

							<!-- Tên, MSP, đánh giá, tóm tắt thông số -->
							<div class="product-main-detail">
								<div class="list-brand d-flex flex-wrap align-items" id="product-brand-list">
									<a href="#" class="item" id="product-brand-link" hidden>
										<img
											id="product-brand-logo"
											src=""
											alt=""
											width="50"
											height="32"
										/>
									</a>
									<span class="item text-uppercase fw-semibold" id="product-brand-name" hidden></span>
								</div>
								<h1 class="name" id="product-name">Đang tải sản phẩm...</h1>

								<div class="info-sku d-flex align-items">
									<span class="sku">MSP: <span id="product-sku">---</span></span>
									<a
										class="review d-flex align-items text-decoration-none"
										id="js-scroll-review"
										href="#review"
									>
										Đánh giá: <i class="icon-star star0"></i>
									</a>
									<span class="view" id="product-stock"></span>
								</div>

								<div class="summary-info">
									<ul class="content" id="show_summary"></ul>
									<a href="javascript:void(0)" class="btn_show_summary" id="summary-toggle" hidden>
										Xem thêm
									</a>
								</div>
							</div>

							<!-- Khuyến mãi, giá, số lượng, nút mua, cam kết -->
							<div class="product-main-right">
								<div class="info-specialOffer">
									<div class="title">
										<i class="bi bi-gift-fill" aria-hidden="true"></i>
										Khuyến mãi khi mua sản phẩm
									</div>
									<div class="content">
										<p>Xả hàng VGA - RAM, giảm tới 45% khi Build kèm PC.</p>
										<p>Giảm thêm 4% cho toàn bộ linh kiện.</p>
										<p>Giảm thêm 2% khi mua kèm Màn Hình.</p>
										<p>Giảm thêm 3% khi mua kèm Gaming Gear.</p>
									</div>
								</div>

								<div class="info-main-price">
									<div class="d-flex align-items">
										<div class="price" id="product-price">---</div>
										<del class="old-price" id="product-old-price" hidden></del>
										<div class="saleoff" id="product-discount" hidden></div>
									</div>
									<div class="info-vat d-flex align-items">
										<div class="item">Bảo hành chính hãng 36 tháng</div>
									</div>
								</div>

								<div class="group-add-product">
									<div class="choose-quantity d-flex align-items">
										<span>Số lượng</span>
										<div class="unit-detail-amount-control d-flex">
											<a href="javascript:;" data-value="-1" aria-label="Giảm số lượng">
												<i class="bi bi-dash-lg" aria-hidden="true"></i>
											</a>
											<input
												type="text"
												size="3"
												value="1"
												id="js-buy-quantity"
												aria-label="Số lượng"
											/>
											<a href="javascript:;" data-value="1" aria-label="Tăng số lượng">
												<i class="bi bi-plus-lg" aria-hidden="true"></i>
											</a>
										</div>
									</div>

									<div class="all-btn-add-product d-flex align-items">
										<div class="btn-buy buyNow">
											<a href="javascript:void(0)" class="buy-go-cart btn-buyNow" id="js-buyNow">
												Mua Ngay
												<span>Hiện tại giá đang rẻ</span>
											</a>
										</div>
										<div class="btn-buy">
											<a href="contact.php" class="buy-go-cart btn-buyNow">
												<div class="d-flex align-items gap-2">
													<i class="bi bi-chat-dots" aria-hidden="true"></i>
													<p>Nhận tư vấn ngay</p>
												</div>
												<span>Nhận giá tốt nhất, không chờ đợi</span>
											</a>
										</div>
									</div>
								</div>

								<div class="info-policy">
									<div class="item">
										<i class="bi bi-credit-card" aria-hidden="true"></i>
										<span>Hỗ trợ trả góp 0%, trả trước 0 đ</span>
									</div>
									<div class="item">
										<i class="bi bi-cash-stack" aria-hidden="true"></i>
										<span>Hoàn tiền 200% nếu có hàng giả</span>
									</div>
									<div class="item">
										<i class="bi bi-lightning-charge" aria-hidden="true"></i>
										<span>Giao hàng nhanh trên toàn quốc</span>
									</div>
									<div class="item">
										<i class="bi bi-headset" aria-hidden="true"></i>
										<span>Hỗ trợ kĩ thuật online 24/7</span>
									</div>
									<div class="item">
										<i class="bi bi-wrench" aria-hidden="true"></i>
										<span>Vệ sinh miễn phí PC, Laptop trọn đời</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- ── Khối dưới: trái = đánh giá, phải = thông số ── -->
					<div class="content-product-detail d-flex">
						<div class="content-left">
							<!-- Mô tả sản phẩm. Khi rỗng, tncstore hiện .alert-mess. -->
							<div class="box-desciption background-white">
								<h2 class="title">Mô tả sản phẩm</h2>
								<div class="content nd" id="content-desciption" hidden></div>
								<a href="javascript:void(0)" class="more-all" id="desc-more" hidden>
									Xem thêm <i class="bi bi-chevron-down" aria-hidden="true"></i>
								</a>
							</div>

							<!-- Đánh giá, DOM sao chép từ #review của tncstore.vn -->
							<div id="review" class="box-review background-white">
								<h2 class="title">Đánh giá của khách hàng</h2>

								<div class="content-form-review">
									<div class="review-info d-flex align-items">
										<div class="box-avgRate">
											<div class="avgRate">
												<span id="review-average">0</span><i class="bi bi-star-fill"></i>
											</div>
										</div>
										<div class="box-avg-rate-count" id="review-breakdown"></div>
										<div class="button-review">
											<a href="javascript:;" id="js-show-review" class="btn-review">
												<span class="hover-txt">Viết đánh giá</span>
											</a>
										</div>
									</div>

									<div class="list-review" id="js-holder-review"></div>
									<a class="more-all" id="show_review" href="javascript:void(0)" style="display: none;">
										Xem thêm <i class="bi bi-chevron-down" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="content-right">
							<!-- Thông số kỹ thuật -->
							<div class="box-specifications background-white">
								<h2 class="title">Thông số kỹ thuật</h2>
								<div class="content" id="content-specifications"></div>
								<a href="javascript:void(0)" class="more-specifications" id="spec-toggle" hidden>
									Xem thêm <i class="bi bi-arrow-right" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>

					<!-- Sản phẩm tương tự (JS đổ cùng danh mục) -->
					<div class="product-similar background-white" id="similar-section" hidden>
						<h2 class="title">Sản phẩm tương tự</h2>
						<div class="row" id="js-product-similar"></div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal viết đánh giá, DOM sao chép từ #show-form của tncstore.vn -->
		<div class="box-form-review" id="show-form">
			<div class="title">
				<p>Đánh giá sản phẩm</p>
				<a href="javascript:void(0)" class="close-review" data-close-review>
					<i class="bi bi-x-lg" aria-hidden="true"></i>
				</a>
			</div>
			<div class="info-pro">
				<img id="review-modal-image" src="assets/img/branding/tnc.png" alt="" />
				<a href="javascript:void(0)" class="name line-clamp-2" id="review-modal-name"></a>
			</div>
			<form class="form-post" id="review-form">
				<div class="rating">
					<div class="rating-selection" id="rating-review0"></div>
				</div>

				<div class="form-input content">
					<div class="label">Chia sẻ đánh giá <span style="color: red">*</span></div>
					<textarea
						class="form-control"
						id="review-content"
						rows="3"
						maxlength="2000"
						placeholder="Viết đánh giá của bạn"
					></textarea>
					<div class="note-error"></div>
				</div>
				<div class="d-flex align-items">
					<div class="form-input w-5">
						<div class="label">Họ và tên <span style="color: red">*</span></div>
						<input
							type="text"
							id="review-name"
							maxlength="150"
							autocomplete="name"
							placeholder="Nhập họ và tên"
						/>
						<div class="note-error"></div>
					</div>
					<div class="form-input w-5">
						<div class="label">Nhập số điện thoại <span style="color: red">*</span></div>
						<input
							type="text"
							id="review-phone"
							maxlength="25"
							autocomplete="tel"
							placeholder="Nhập số điện thoại của bạn"
						/>
						<div class="note-error"></div>
					</div>
				</div>
				<div class="note-error form-error" id="review-error" hidden></div>
				<a href="javascript:;" class="send_form btn-submit" id="review-submit">
					<span class="txt">Gửi đánh giá</span>
				</a>
			</form>
		</div>

		<!-- Modal trả lời, DOM sao chép từ .box-form-review-reply của tncstore.vn -->
		<div class="box-form-review-reply" id="show-reply">
			<div class="title">
				<p>Trả lời đánh giá sản phẩm</p>
				<a href="javascript:void(0)" class="close-review" data-close-review>
					<i class="bi bi-x-lg" aria-hidden="true"></i>
				</a>
			</div>
			<form class="form-post" id="reply-form">
				<div class="form-input content">
					<div class="label">Trả lời đánh giá sản phẩm <span style="color: red">*</span></div>
					<textarea
						class="form-control"
						id="reply-content"
						rows="3"
						maxlength="2000"
						placeholder="Viết đánh giá của bạn"
					></textarea>
					<div class="note-error"></div>
				</div>
				<div class="d-flex align-items">
					<div class="form-input w-5">
						<div class="label">Họ và tên <span style="color: red">*</span></div>
						<input
							type="text"
							id="reply-name"
							maxlength="150"
							autocomplete="name"
							placeholder="Nhập họ và tên"
						/>
						<div class="note-error"></div>
					</div>
					<div class="form-input w-5">
						<div class="label">Nhập số điện thoại <span style="color: red">*</span></div>
						<input
							type="text"
							id="reply-phone"
							maxlength="25"
							autocomplete="tel"
							placeholder="Nhập số điện thoại của bạn"
						/>
						<div class="note-error"></div>
					</div>
				</div>
				<div class="note-error form-error" id="reply-error" hidden></div>
				<a href="javascript:;" class="send_form btn-submit" id="reply-submit">
					<span class="txt">Gửi đánh giá</span>
				</a>
			</form>
		</div>

		<script src="js/product-detail.js"></script>
		<script src="js/reviews.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

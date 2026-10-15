<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/partial/news-cards.php';

/** Tin tức cho khối "Tin tức liên quan" (như TNC lấy 4 bài mới nhất). */
try {
	$newsRepository = new NewsRepository(database());
	$relatedPosts = $newsRepository->latest(4);
} catch (Throwable $exception) {
	$relatedPosts = [];
}
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta
			name="description"
			content="Tự chọn linh kiện và xây dựng cấu hình PC phù hợp tại TNC Store."
		/>
		<title>Build PC | TNC Store</title>
		<?php require 'partial/link.php' ?>
		<link rel="stylesheet" href="css/buildpc.css" />
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main class="page-buildpc">
			<div class="container">
				<div class="breadcrumb">
					<ol class="ul clearfix">
						<li>
							<a href="index.php"><span>Trang chủ</span></a>
							<i class="bi bi-chevron-right" aria-hidden="true"></i>
						</li>
						<li>
							<a href="buildpc.php"><h1>Build PC - Xây dựng cấu hình PC</h1></a>
						</li>
					</ol>
				</div>

				<div class="product-list-buildpc background-white">
					<h2 class="title">Mẫu PC nổi bật</h2>
					<div class="list-bst" id="js-list-bst">
						<p style="font-size: 30px; text-align: center; display: flex; align-items: center; justify-content: center; line-height: 300px; font-weight: bold;">Đang cập nhật sản phẩm.....!</p>
					</div>
				</div>

				<div class="banner-buildpc">
					<a href="products.php" class="item">
						<img src="assets/img/banner/promo/banner-build-pc-gigabyte-intel-pc.jpg" alt="Khuyến mại build PC" />
					</a>
				</div>

				<div class="content-buildpc d-flex">
					<div class="left-buildpc">
						<div class="list-btn-action d-flex align-items">
							<a href="javascript:void(0)" class="item active" data-slot="1">Cấu hình 1</a>
							<a href="javascript:void(0)" class="item" data-slot="2">Cấu hình 2</a>
							<a href="javascript:void(0)" class="item" data-slot="3">Cấu hình 3</a>
							<a href="javascript:void(0)" class="item" data-slot="4">Cấu hình 4</a>
							<a href="javascript:void(0)" class="item" data-slot="5">Cấu hình 5</a>
						</div>
						<div class="background-white">
							<h3 class="title">Chọn linh kiện pc</h3>
							<div class="list-action d-flex align-items space-between" id="js-buildpc-action">
								<a href="javascript:void(0)" class="item">
									<i class="bi bi-floppy" aria-hidden="true"></i>
									<span class="hover-txt" data-action="save">Lưu cấu hình</span>
								</a>
								<a href="javascript:void(0)" class="item">
									<i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
									<span class="hover-txt" data-action="download-excel">Tải file excel cấu hình</span>
								</a>
								<a href="javascript:void(0)" class="item">
									<i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
									<span class="hover-txt" data-action="create-image">Tải ảnh cấu hình</span>
								</a>
								<a href="javascript:void(0)" class="item">
									<i class="bi bi-printer" aria-hidden="true"></i>
									<span class="hover-txt" data-action="view">In báo giá</span>
								</a>
								<a href="javascript:void(0)" class="item">
									<i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
									<span class="hover-txt">Làm mới cấu hình</span>
								</a>
							</div>
							<div class="list-drive" id="js-buildpc-layout"></div>
						</div>
					</div>

					<div class="right-buildpc">
						<div class="box-promotion background-white">
							<div class="box-info-promotion js-buildpc-promotion-content">
								<b style="color: red; font-weight: 600;">Không tìm thấy khuyến mãi phù hợp!</b>
							</div>
							<p class="note-user">Mã giảm giá và phiếu mua hàng sẽ khổng thể dùng lại sau khi đã đặt mua hàng</p>
							<div class="box-note-promotion">
								<p>Quý khách hàng lưu ý: Khuyến mãi áp dụng cho 01 bộ PC, tính trên tổng linh kiện bắt buộc và phụ kiện tùy chọn trong cùng đơn hàng.</p>
								<p>Trong đó những <strong>sản phẩm bắt buộc</strong> bao gồm: VGA, Main, CPU, RAM, SSD, Nguồn, Vỏ Case (Chiết khấu chỉ áp dụng số lượng 01 cho mỗi linh kiện).</p>
								<p>Những <strong>sản phẩm tùy chọn</strong> bao gồm: Màn hình, Tản Nhiệt, Quạt, Bàn Phím, Chuột,...</p>
								<p>Đối với <strong>PC không có VGA</strong> chỉ giảm 3% cho bộ PC</p>
								<p><strong>Ưu đãi mua kèm:</strong> Màn hình -2% | Gaming gear -3% (Chỉ áp dụng giảm số lượng 01 cho mỗi nhóm sản phẩm)</p>
								<p>Chương trình khuyến mãi không áp dụng với PC build Card màn hình chạy Flash Sale, ưu đãi không cộng dồn với các khuyến mãi khác. Giá hiển thị chỉ mang tính tham khảo, vui lòng liên hệ NVKD để biết thông tin chi tiết.</p>
							</div>
						</div>

						<div class="box-add-buildpc background-white">
							<div class="box-config-summary">
								<h4 class="text-center">Mời bạn chọn linh kiện ở bảng tên</h4>
								<a href="javascript:void(0)" class="add-buildpc error">
									<span class="txt">Xác nhận mua hàng</span>
								</a>
								<div class="show-action-buy">
									<h4>Thông tin PC</h4>
									<div class="content-total-buildpc">
										<div class="item d-flex align-items space-between">
											<b>Chi phí dự tính</b>
											<b class="price js-config-summary"></b>
										</div>
										<div class="item d-flex align-items space-between" id="price-sale-promotion">
											<b>Khuyến mãi</b>
											<b class="price js-title-promotion">0đ</b>
										</div>
										<div class="item d-flex align-items space-between" id="total-sale-promotion">
											<b>Giảm tiền mặt</b>
											<b class="price">0đ</b>
										</div>
										<div class="item total d-flex align-items space-between">
											<b>Tổng chi phí</b>
											<b class="total-price-config js-config-summary js-total-price-final"></b>
										</div>
										<div class="item">
											<span class="vat">Đã bao gồm VAT [nếu có]</span>
										</div>
									</div>
									<a href="javascript:void(0)" class="add-buildpc active" id="js-add-to-cart">
										<span class="txt">Tiến hành thanh toán</span>
									</a>
								</div>
							</div>
							<div class="icon-pay">
								<img src="assets/img/pay_cart.png" alt="pay" />
							</div>
							<div class="info-policy">
								<div class="item">
									<i class="bi bi-credit-card" aria-hidden="true"></i>
									<span>Hỗ trợ trả góp 0%, trả trước 0 đ</span>
								</div>
								<div class="item">
									<i class="bi bi-cash-coin" aria-hidden="true"></i>
									<span>Hoàn tiền 200% nếu có hàng giả</span>
								</div>
								<div class="item">
									<i class="bi bi-lightning-charge-fill" aria-hidden="true"></i>
									<span>Giao hàng nhanh trên toàn quốc</span>
								</div>
								<div class="item">
									<i class="bi bi-headset" aria-hidden="true"></i>
									<span>Hỗ trợ kĩ thuật online 24/7</span>
								</div>
								<div class="item">
									<i class="bi bi-wrench-adjustable" aria-hidden="true"></i>
									<span>Vệ sinh miễn phí PC, Laptop trọn đời</span>
								</div>
							</div>
						</div>

						<div class="box-related-news background-white">
							<div class="d-flex align-items space-between">
								<h4 class="title">Tin tức liên quan</h4>
							</div>
							<div class="list-related" id="js-list-related">
								<?php if ($relatedPosts !== []): ?>
									<?php foreach ($relatedPosts as $post): ?>
										<div class="item-article d-flex">
											<div class="info">
												<h3 class="name line-clamp-2"><a href="<?= e(newsPostUrl($post)) ?>"><?= e($post['title']) ?></a></h3>
												<div class="summary line-clamp-2"><?= e($post['excerpt']) ?></div>
												<div class="time d-flex align-items">
													<div class="datetime">
														<i class="bi bi-clock" aria-hidden="true"></i> <?= e($post['dateTime']) ?>
													</div>
													<div class="view">
														<i class="bi bi-eye" aria-hidden="true"></i> <?= number_format((float) $post['views'], 0, ',', '.') ?>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<i style="padding: 10px; display: block;">Bài viết đang được cập nhật.....!</i>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="js-modal-popup"><!-- popup select --></div>

			<div class="mask-popup" id="mask-rebuild">
				<div id="popup-rebuild_config">
					<div class="d-flex align-items space-center">
						<h2>Chọn linh kiện mới</h2>
					</div>
					<p>Cảnh báo: Toàn bộ linh kiện của bộ PC hiện tại sẽ bị xóa đi</p>
					<button type="button" class="btn btn-red cancel" id="rebuild-cancel">HỦY</button>
					<button type="button" class="btn btn-red confirm" id="rebuild-confirm">XÁC NHẬN</button>
				</div>
			</div>
		</main>
		<script src="js/buildpc.js"></script>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

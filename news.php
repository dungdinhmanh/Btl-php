<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Tin tức công nghệ | TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main class="news-page">
			<div class="container">
				<header class="news-page-header">
					<p class="eyebrow">TNC Store / News</p>
					<h1>Tin tức công nghệ</h1>
					<p>
						Thông tin sản phẩm, hướng dẫn build PC và những gợi ý thiết thực để bạn chọn
						đúng thiết bị.
					</p>
				</header>
				<nav class="news-category-nav" aria-label="Chuyên mục">
					<a class="active" href="#latest">Mới nhất</a>
					<a href="#guides">Hướng dẫn</a>
					<a href="#hardware">Phần cứng</a>
					<a href="#gaming">Gaming</a>
					<a href="#reviews">Review</a>
				</nav>
				<section id="latest" aria-labelledby="latest-title">
					<div class="section-heading"><h2 id="latest-title">Bài viết mới nhất</h2></div>
					<div class="row g-4 mb-5">
						<div class="col-lg-7">
							<article class="news-feature-card h-100">
								<a class="news-image-wrap" href="news-post.php?post=pc-gaming">
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
										<a href="news-post.php?post=pc-gaming">
											Hướng dẫn chọn cấu hình PC Gaming phù hợp từng nhu cầu
										</a>
									</h3>
									<p>
										Đặt mục tiêu sử dụng và ngân sách trước, sau đó cân bằng
										CPU, VGA và khả năng nâng cấp cho bộ máy.
									</p>
									<a class="news-read-link" href="news-post.php?post=pc-gaming">
										Đọc bài viết
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>
							</article>
						</div>
						<div class="col-lg-5">
							<div class="news-list-card">
								<article class="news-list-item">
									<a class="news-thumb" href="news-post.php?post=monitor">
										<img
											src="https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=600&q=80"
											alt="Góc làm việc với nhiều màn hình"
										/>
									</a>
									<div>
										<div class="news-meta">
											<span>Thủ thuật</span>
											<time datetime="2026-09-14">14.09.2026</time>
										</div>
										<h3>
											<a href="news-post.php?post=monitor">
												4 thông số cần biết trước khi mua màn hình mới
											</a>
										</h3>
									</div>
								</article>
								<article class="news-list-item">
									<a class="news-thumb" href="news-post.php?post=setup">
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
											<a href="news-post.php?post=setup">
												5 nâng cấp nhỏ giúp góc máy gọn hơn
											</a>
										</h3>
									</div>
								</article>
								<article class="news-list-item">
									<a class="news-thumb" href="news-post.php?post=laptop">
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
											<a href="news-post.php?post=laptop">
												Laptop cho sinh viên: chọn gì là đủ?
											</a>
										</h3>
									</div>
								</article>
							</div>
						</div>
					</div>
				</section>
				<section id="guides" aria-labelledby="guides-title">
					<div class="section-heading">
						<div>
							<p class="eyebrow">Chọn đúng, dùng tốt</p>
							<h2 id="guides-title">Hướng dẫn &amp; tư vấn</h2>
						</div>
					</div>
					<div class="row g-4">
						<div class="col-md-4">
							<article class="news-grid-card">
								<a href="news-post.php?post=pc-gaming">
									<img
										src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=700&q=80"
										alt="Linh kiện máy tính"
									/>
								</a>
								<div class="news-card-body">
									<div class="news-meta">
										<span>Phần cứng</span>
										<time datetime="2026-09-08">08.09.2026</time>
									</div>
									<h3>
										<a href="news-post.php?post=pc-gaming">
											CPU và VGA: phân bổ ngân sách sao cho hợp lý?
										</a>
									</h3>
									<p>Điểm xuất phát đơn giản để tránh cấu hình mất cân bằng.</p>
								</div>
							</article>
						</div>
						<div class="col-md-4">
							<article class="news-grid-card">
								<a href="news-post.php?post=monitor">
									<img
										src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=700&q=80"
										alt="Laptop và màn hình trên bàn"
									/>
								</a>
								<div class="news-card-body">
									<div class="news-meta">
										<span>Thủ thuật</span>
										<time datetime="2026-09-06">06.09.2026</time>
									</div>
									<h3>
										<a href="news-post.php?post=monitor">
											Tần số quét, độ phân giải và màu sắc: hiểu nhanh
										</a>
									</h3>
									<p>Ba tiêu chí quan trọng khi chọn màn hình.</p>
								</div>
							</article>
						</div>
						<div class="col-md-4">
							<article class="news-grid-card">
								<a href="news-post.php?post=setup">
									<img
										src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=700&q=80"
										alt="Bàn phím và thiết bị gaming"
									/>
								</a>
								<div class="news-card-body">
									<div class="news-meta">
										<span>Gaming</span>
										<time datetime="2026-09-04">04.09.2026</time>
									</div>
									<h3>
										<a href="news-post.php?post=setup">
											Hoàn thiện gaming setup không cần mua mọi thứ cùng lúc
										</a>
									</h3>
									<p>Ưu tiên các món nâng trải nghiệm sử dụng mỗi ngày.</p>
								</div>
							</article>
						</div>
					</div>
				</section>
			</div>
		</main>
		<?php require 'partial/footer.php' ?>	
	</body>
</html>

<?php
require_once __DIR__ . '/backend/bootstrap.php';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Hướng dẫn chọn cấu hình PC Gaming | TNC Store</title>
		<?php require 'partial/link.php' ?>
	</head>
	<body>
		<?php require 'partial/header.php' ?>
		<main class="article-page">
			<article class="article-shell">
				<div class="article-breadcrumb">
					<a href="index.php">Trang chủ</a>
					<i class="bi bi-chevron-right mx-1"></i>
					<a href="news.php">Tin tức</a>
					<i class="bi bi-chevron-right mx-1"></i>
					Hướng dẫn
				</div>
				<div class="news-meta">
					<span>Hướng dẫn build PC</span>
					<time datetime="2026-09-16">16.09.2026</time>
				</div>
				<h1 class="article-title">
					Hướng dẫn chọn cấu hình PC Gaming phù hợp từng nhu cầu
				</h1>
				<p class="article-lead">
					Một bộ PC đáng tiền không nhất thiết phải chứa linh kiện đắt nhất. Hãy bắt đầu
					từ tựa game, độ phân giải màn hình và ngân sách bạn thực sự muốn đầu tư.
				</p>
				<figure class="article-hero">
					<img
						src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?auto=format&fit=crop&w=1400&q=85"
						alt="Bộ máy tính gaming với màn hình hiển thị"
					/>
				</figure>
				<div class="article-content">
					<p>
						Khi chọn cấu hình, điều quan trọng là sự cân bằng. CPU xử lý các tác vụ nền
						và khung hình trong nhiều tựa game, trong khi VGA quyết định phần lớn trải
						nghiệm ở độ phân giải cao hoặc khi bật thiết lập hình ảnh nặng.
					</p>
					<h2>1. Xác định màn hình và thể loại game</h2>
					<p>
						Game eSports ở Full HD thường ưu tiên khung hình cao, vì vậy CPU, RAM và màn
						hình tần số quét cao là những điểm đáng đầu tư. Với game AAA ở 2K hoặc 4K,
						ngân sách nên được dồn nhiều hơn cho card đồ họa.
					</p>
					<h2>2. Phân bổ ngân sách hợp lý</h2>
					<ul>
						<li>
							Dành phần lớn ngân sách cho VGA nếu mục tiêu là chơi game đồ họa nặng.
						</li>
						<li>Chọn CPU tương xứng để tránh giới hạn hiệu năng của VGA.</li>
						<li>
							Đừng quên nguồn điện chất lượng, bộ nhớ RAM và dung lượng SSD đủ dùng.
						</li>
					</ul>
					<h2>3. Chừa đường cho nâng cấp</h2>
					<p>
						Mainboard, nguồn và case thường được giữ lại qua nhiều lần nâng cấp. Chọn
						đúng ngay từ đầu giúp bạn dễ bổ sung ổ cứng, RAM hoặc thay card đồ họa trong
						tương lai mà không phải làm lại toàn bộ cấu hình.
					</p>
					<p>
						Bạn có thể bắt đầu bằng danh sách linh kiện mình cần, sau đó dùng công cụ
						Build PC để kiểm tra sự tương thích trước khi đặt hàng.
					</p>
					<a href="buildpc.php" class="btn btn-primary mt-2">
						Bắt đầu Build PC
						<i class="bi bi-arrow-right ms-1"></i>
					</a>
				</div>
			</article>
		</main>
		<?php require 'partial/footer.php' ?>
	</body>
</html>

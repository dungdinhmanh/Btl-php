<?php
require_once __DIR__ . '/../backend/bootstrap.php';
require_once __DIR__ . '/../backend/src/Repositories/AdminNewsRepository.php';

$admin = requirePageRole(STAFF_ROLES, '../login.php');
$adminName = $admin['name'];
$adminInitials = strtoupper(substr(preg_replace('/\s+/', '', (string) $adminName), 0, 2));

$repo = new AdminNewsRepository(database());
$categories = $repo->categories();

$postId = (int) ($_GET['id'] ?? 0);
$post = $postId > 0 ? $repo->find($postId) : null;
$notFound = $postId > 0 && $post === null;
$saved = isset($_GET['saved']);

$initial = [
    'id' => $post['id'] ?? 0,
    'title' => $post['title'] ?? '',
    'slug' => $post['slug'] ?? '',
    'categoryId' => $post['categoryId'] ?? 0,
    'status' => $post['status'] ?? 'draft',
    'publishedInput' => $post['publishedInput'] ?? '',
    'excerpt' => $post['excerpt'] ?? '',
    'coverImage' => $post['coverImage'] ?? '',
    'content' => $post['content'] ?? '',
    'views' => $post['views'] ?? 0,
];
$pageTitle = $post ? 'Sửa bài viết' : 'Viết bài mới';
?>
<!doctype html>
<html lang="vi">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?= htmlspecialchars($pageTitle) ?> | TNC Store</title>
		<?php require __DIR__ . '/../partial/link.php'; ?>
		<link rel="stylesheet" href="../css/article-layout.css" />
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
				<a href="products.php">
					<i class="bi bi-box-seam"></i>
					<span>Sản phẩm</span>
				</a>
				<a href="orders.php">
					<i class="bi bi-receipt"></i>
					<span>Đơn hàng</span>
				</a>
				<a href="reviews.php">
					<i class="bi bi-star"></i>
					<span>Đánh giá</span>
				</a>
				<a class="active" href="news.php">
					<i class="bi bi-newspaper"></i>
					<span>Tin tức</span>
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
					<h1><?= htmlspecialchars($pageTitle) ?></h1>
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

			<?php if ($saved): ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<i class="bi bi-check-lg me-2"></i>Bài viết đã được lưu.
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
				</div>
			<?php endif; ?>

			<?php if ($notFound): ?>
				<div class="alert alert-warning" role="alert">
					Không tìm thấy bài viết cần sửa. <a href="news.php" class="alert-link">Quay lại danh sách</a>.
				</div>
			<?php else: ?>
				<form id="news-form" novalidate>
					<section class="admin-panel">
						<div class="admin-panel-head">
							<div>
								<p class="eyebrow">Thông tin bài viết</p>
								<h2>Nội dung chính</h2>
							</div>
							<div class="d-flex align-items-center gap-2">
								<a class="btn btn-outline-secondary" href="news.php">Huỷ</a>
								<button class="btn btn-primary" type="submit" id="news-save-btn">
									<i class="bi bi-floppy me-2"></i>Lưu bài viết
								</button>
							</div>
						</div>

						<div class="row g-3">
							<div class="col-12">
								<label class="form-label" for="news-title">Tiêu đề</label>
								<input
									class="form-control form-control-lg"
									id="news-title"
									name="title"
									maxlength="255"
									placeholder="Nhập tiêu đề bài viết..."
									autocomplete="off"
									required
								/>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="news-slug">Đường dẫn (slug)</label>
								<input
									class="form-control"
									id="news-slug"
									name="slug"
									placeholder="Để trống để tự tạo từ tiêu đề"
									autocomplete="off"
								/>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="news-category">Danh mục</label>
								<select class="form-select" id="news-category" name="category_id" required>
									<option value="">-- Chọn danh mục --</option>
									<?php foreach ($categories as $category): ?>
										<option value="<?= (int) $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-12">
								<label class="form-label" for="news-excerpt">Mô tả ngắn</label>
								<textarea
									class="form-control"
									id="news-excerpt"
									name="excerpt"
									rows="2"
									placeholder="Hiển thị dưới tiêu đề bài viết và ở danh sách tin"
								></textarea>
							</div>
							<div class="col-md-4">
								<label class="form-label" for="news-status">Trạng thái</label>
								<select class="form-select" id="news-status" name="post_status">
									<option value="draft">Bản nháp</option>
									<option value="published">Đăng bài</option>
									<option value="archived">Lưu trữ</option>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label" for="news-published-at">Thời gian đăng</label>
								<input type="datetime-local" class="form-control" id="news-published-at" name="published_at" />
								<div class="form-text">Bỏ trống nếu muốn đăng ngay khi lưu.</div>
							</div>
							<div class="col-md-4">
								<label class="form-label" for="news-cover">Ảnh đại diện</label>
								<div class="input-group">
									<input
										class="form-control"
										id="news-cover"
										name="cover_image_path"
										placeholder="https://... hoặc tải ảnh lên"
										autocomplete="off"
									/>
									<button class="btn btn-outline-secondary" type="button" id="news-cover-upload-btn" title="Tải ảnh lên">
										<i class="bi bi-upload"></i>
									</button>
								</div>
								<input type="file" id="news-cover-file" accept="image/*" hidden />
								<div class="news-cover-preview mt-2" id="news-cover-preview" hidden>
									<img alt="Ảnh đại diện" id="news-cover-thumb" />
									<button class="btn btn-sm btn-outline-danger" type="button" id="news-cover-remove">
										<i class="bi bi-x-lg me-1"></i>Xoá ảnh
									</button>
								</div>
							</div>
						</div>
					</section>

					<section class="news-workspace">
						<div class="admin-panel">
							<div class="admin-panel-head">
								<div>
									<p class="eyebrow">Nội dung bài viết</p>
									<h2>Soạn thảo</h2>
								</div>
								<p class="news-editor-hint mb-0">
									Bôi đen chữ rồi chọn định dạng, không cần viết HTML.
								</p>
							</div>

							<div class="news-toolbar" id="news-toolbar" role="toolbar" aria-label="Định dạng nội dung">
								<button class="btn btn-sm news-tool" type="button" data-cmd="undo" title="Hoàn tác (Ctrl+Z)">
									<i class="bi bi-arrow-counterclockwise"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="redo" title="Làm lại (Ctrl+Y)">
									<i class="bi bi-arrow-clockwise"></i>
								</button>
								<span class="news-toolbar-sep"></span>
								<select class="form-select form-select-sm news-block" id="news-block" title="Kiểu đoạn" aria-label="Kiểu đoạn">
									<option value="p">Đoạn văn</option>
									<option value="h2">Tiêu đề 2</option>
									<option value="h3">Tiêu đề 3</option>
									<option value="h4">Tiêu đề 4</option>
									<option value="blockquote">Trích dẫn</option>
								</select>
								<span class="news-toolbar-sep"></span>
								<button class="btn btn-sm news-tool" type="button" data-cmd="bold" title="Đậm (Ctrl+B)">
									<i class="bi bi-type-bold"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="italic" title="Nghiêng (Ctrl+I)">
									<i class="bi bi-type-italic"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="underline" title="Gạch chân (Ctrl+U)">
									<i class="bi bi-type-underline"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="strikeThrough" title="Gạch ngang">
									<i class="bi bi-type-strikethrough"></i>
								</button>
								<span class="news-toolbar-sep"></span>
								<button class="btn btn-sm news-tool" type="button" data-cmd="justifyLeft" title="Căn trái">
									<i class="bi bi-text-left"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="justifyCenter" title="Căn giữa">
									<i class="bi bi-text-center"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="justifyRight" title="Căn phải">
									<i class="bi bi-text-right"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="justifyFull" title="Căn đều hai bên">
									<i class="bi bi-justify"></i>
								</button>
								<span class="news-toolbar-sep"></span>
								<button class="btn btn-sm news-tool" type="button" data-cmd="insertUnorderedList" title="Danh sách gạch đầu dòng">
									<i class="bi bi-list-ul"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="insertOrderedList" title="Danh sách đánh số">
									<i class="bi bi-list-ol"></i>
								</button>
								<span class="news-toolbar-sep"></span>
								<button class="btn btn-sm news-tool" type="button" data-action="link" title="Chèn liên kết (Ctrl+K)">
									<i class="bi bi-link-45deg"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-cmd="unlink" title="Bỏ liên kết">
									<i class="bi bi-link"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-action="image" title="Chèn ảnh">
									<i class="bi bi-image"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-action="video" title="Chèn video YouTube">
									<i class="bi bi-camera-video"></i>
								</button>
								<span class="news-toolbar-sep"></span>
								<button class="btn btn-sm news-tool" type="button" data-cmd="insertHorizontalRule" title="Chèn dòng kẻ">
									<i class="bi bi-dash-lg"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-action="clear-format" title="Xoá định dạng">
									<i class="bi bi-eraser"></i>
								</button>
								<button class="btn btn-sm news-tool" type="button" data-action="source" title="Xem/sửa mã HTML">
									<i class="bi bi-code-slash"></i>
								</button>
							</div>

							<div
								class="news-editor"
								id="news-editor"
								contenteditable="true"
								spellcheck="true"
								role="textbox"
								aria-multiline="true"
								aria-label="Nội dung bài viết"
							></div>
							<textarea class="news-source" id="news-source" spellcheck="false" hidden aria-label="Mã HTML nội dung"></textarea>
						</div>

						<div class="admin-panel news-preview-panel">
							<div class="admin-panel-head">
								<div>
									<p class="eyebrow">Hiển thị trên trang tin</p>
									<h2>Xem trước</h2>
								</div>
								<a class="text-link" id="news-view-live" href="#" target="_blank" rel="noopener" hidden>
									Mở bài viết <i class="bi bi-arrow-up-right"></i>
								</a>
							</div>
							<div class="news-preview" id="news-preview">
								<div class="np-layout news-preview-layout">
									<article class="article-shell">
										<h1 class="article-title" id="preview-title">Tiêu đề bài viết</h1>
										<div class="article-info">
											<span><i class="bi bi-clock"></i><span id="preview-date"></span></span>
											<span><i class="bi bi-eye"></i><span id="preview-views">0</span></span>
										</div>
										<p class="article-lead" id="preview-excerpt" hidden></p>
										<figure class="article-hero" id="preview-hero" hidden>
											<img id="preview-cover" alt="" />
										</figure>
										<div class="article-content" id="preview-content"></div>
										<p class="news-preview-empty" id="news-preview-empty">Chưa có nội dung để xem trước.</p>
									</article>
								</div>
							</div>
						</div>
					</section>
				</form>

				<div class="modal fade" id="newsLinkModal" tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="newsLinkModalTitle">Chèn liên kết</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
							</div>
							<div class="modal-body">
								<div class="mb-3">
									<label class="form-label" for="news-link-text">Chữ hiển thị</label>
									<input type="text" class="form-control" id="news-link-text" placeholder="Ví dụ: Xem chi tiết tại đây" />
								</div>
								<div class="mb-3">
									<label class="form-label" for="news-link-url">Đường dẫn</label>
									<input type="text" class="form-control" id="news-link-url" placeholder="https://..." />
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="news-link-blank" checked />
									<label class="form-check-label" for="news-link-blank">Mở ở tab mới</label>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
								<button type="button" class="btn btn-primary" id="news-link-confirm">Chèn liên kết</button>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="newsImageModal" tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Chèn ảnh</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
							</div>
							<div class="modal-body">
								<label class="form-label" for="news-image-src">Đường dẫn ảnh</label>
								<div class="input-group mb-3">
									<input type="text" class="form-control" id="news-image-src" placeholder="https://... hoặc tải ảnh lên" />
									<button class="btn btn-outline-secondary" type="button" id="news-image-upload-btn">
										<i class="bi bi-upload me-1"></i>Tải lên
									</button>
								</div>
								<input type="file" id="news-image-file" accept="image/*" hidden />
								<label class="form-label" for="news-image-alt">Mô tả ảnh (alt)</label>
								<input type="text" class="form-control" id="news-image-alt" placeholder="Mô tả ngắn nội dung ảnh" />
								<div class="news-image-preview" id="news-image-preview" hidden>
									<img alt="" />
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
								<button type="button" class="btn btn-primary" id="news-image-confirm">Chèn ảnh</button>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="newsVideoModal" tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Chèn video YouTube</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
							</div>
							<div class="modal-body">
								<label class="form-label" for="news-video-url">Đường dẫn video</label>
								<input type="text" class="form-control" id="news-video-url" placeholder="https://www.youtube.com/watch?v=..." />
								<div class="form-text">Hỗ trợ youtube.com/watch, youtu.be, /shorts và /embed.</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
								<button type="button" class="btn btn-primary" id="news-video-confirm">Chèn video</button>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</main>

		<script>window.NEWS_POST = <?= json_encode($initial, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
		<script src="../js/api.js"></script>
		<script src="js/admin-news-editor.js"></script>
	</body>
</html>

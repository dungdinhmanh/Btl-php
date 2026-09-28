/**
 * News listing (news.php) and single article (news-post.php), served from the
 * MySQL-backed news endpoint. Both pages keep their original markup as a no-JS
 * fallback; this script replaces it with live data.
 */

document.addEventListener("DOMContentLoaded", () => {
	if (document.querySelector("#news-list-root")) renderNewsList();
	if (document.querySelector("#news-article")) renderNewsArticle();
});

const postUrl = (post) => `news-post.php?slug=${encodeURIComponent(post.slug)}`;

const postCover = (post) => post.coverImage || "assets/img/branding/tnc.png";

const postMeta = (post) => `
	<div class="news-meta">
		<span>${TNC.escapeHtml(post.category)}</span>
		<time datetime="${TNC.escapeHtml(post.publishedAtIso)}">${TNC.escapeHtml(post.date)}</time>
	</div>
`;

async function renderNewsList() {
	const root = document.querySelector("#news-list-root");
	if (!root) return;

	TNC.showLoading(root, "Đang tải bài viết...", "text-center text-muted py-5");

	try {
		const posts = await TNC.api.news({ limit: 12 });
		if (!posts.length) {
			root.innerHTML = '<p class="text-center text-muted py-5">Chưa có bài viết nào.</p>';
			return;
		}

		const [feature, ...others] = posts;

		root.innerHTML = `
			<header class="news-page-header">
				<p class="eyebrow">TNC Store / News</p>
				<h1>Tin tức công nghệ</h1>
				<p>
					Thông tin sản phẩm, hướng dẫn build PC và những gợi ý thiết thực để bạn chọn
					đúng thiết bị.
				</p>
			</header>
			<section aria-labelledby="latest-title">
				<div class="section-heading"><h2 id="latest-title">Bài viết mới nhất</h2></div>
				<div class="row g-4 mb-5">
					<div class="col-lg-7">
						<article class="news-feature-card h-100">
							<a class="news-image-wrap" href="${postUrl(feature)}">
								<img src="${TNC.escapeHtml(postCover(feature))}" alt="${TNC.escapeHtml(feature.title)}" />
							</a>
							<div class="news-card-body">
								${postMeta(feature)}
								<h3><a href="${postUrl(feature)}">${TNC.escapeHtml(feature.title)}</a></h3>
								<p>${TNC.escapeHtml(feature.excerpt || "")}</p>
								<a class="news-read-link" href="${postUrl(feature)}">
									Đọc bài viết
									<i class="bi bi-arrow-right"></i>
								</a>
							</div>
						</article>
					</div>
					<div class="col-lg-5">
						<div class="news-list-card">
							${others
								.slice(0, 4)
								.map(
									(post) => `
										<article class="news-list-item">
											<a class="news-thumb" href="${postUrl(post)}">
												<img src="${TNC.escapeHtml(postCover(post))}" alt="${TNC.escapeHtml(post.title)}" />
											</a>
											<div>
												${postMeta(post)}
												<h3><a href="${postUrl(post)}">${TNC.escapeHtml(post.title)}</a></h3>
											</div>
										</article>
									`,
								)
								.join("")}
						</div>
					</div>
				</div>
			</section>
			<section aria-labelledby="guides-title">
				<div class="section-heading">
					<div>
						<p class="eyebrow">Chọn đúng, dùng tốt</p>
						<h2 id="guides-title">Hướng dẫn &amp; tư vấn</h2>
					</div>
				</div>
				<div class="row g-4">
					${posts
						.slice(0, 3)
						.map(
							(post) => `
								<div class="col-md-4">
									<article class="news-grid-card">
										<a href="${postUrl(post)}">
											<img src="${TNC.escapeHtml(postCover(post))}" alt="${TNC.escapeHtml(post.title)}" />
										</a>
										<div class="news-card-body">
											${postMeta(post)}
											<h3><a href="${postUrl(post)}">${TNC.escapeHtml(post.title)}</a></h3>
											<p>${TNC.escapeHtml(post.excerpt || "")}</p>
										</div>
									</article>
								</div>
							`,
						)
						.join("")}
				</div>
			</section>
		`;
	} catch (error) {
		TNC.renderError(root, error);
	}
}

async function renderNewsArticle() {
	const root = document.querySelector("#news-article");
	if (!root) return;

	const params = new URLSearchParams(window.location.search);
	const slug = (params.get("slug") || params.get("post") || "").trim();

	if (!slug) {
		root.innerHTML = '<p class="text-center text-muted py-5">Thiếu tham số bài viết.</p>';
		return;
	}

	TNC.showLoading(root, "Đang tải bài viết...", "text-center text-muted py-5");

	try {
		const post = await TNC.api.newsPost(slug);
		document.title = `${post.title} | TNC Store`;

		root.innerHTML = `
			<div class="article-breadcrumb">
				<a href="index.php">Trang chủ</a>
				<i class="bi bi-chevron-right mx-1"></i>
				<a href="news.php">Tin tức</a>
				<i class="bi bi-chevron-right mx-1"></i>
				${TNC.escapeHtml(post.category)}
			</div>
			${postMeta(post)}
			<h1 class="article-title">${TNC.escapeHtml(post.title)}</h1>
			${post.excerpt ? `<p class="article-lead">${TNC.escapeHtml(post.excerpt)}</p>` : ""}
			${
				post.coverImage
					? `<figure class="article-hero"><img src="${TNC.escapeHtml(post.coverImage)}" alt="${TNC.escapeHtml(post.title)}" /></figure>`
					: ""
			}
			<div class="article-content">${post.content}</div>
		`;
	} catch (error) {
		root.innerHTML = `<p class="text-center text-muted py-5">${TNC.escapeHtml(
			error?.message || "Không tải được bài viết.",
		)}</p>`;
	}
}

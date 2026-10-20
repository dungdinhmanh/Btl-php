const newsTableBody = document.querySelector("#admin-news-table-body");
const newsSearch = document.querySelector("#news-search");
const newsStatusFilter = document.querySelector("#news-status-filter");

const NEWS_STATUS_META = {
	published: { label: "Đã đăng", className: "success" },
	draft: { label: "Bản nháp", className: "secondary" },
	archived: { label: "Lưu trữ", className: "warning" },
};

let newsPosts = [];

/** Ảnh lưu dạng tương đối từ gốc site (assets/news/...) nên trang trong /admin/ phải thêm "../". */
const newsAssetUrl = (path) => {
	const value = String(path || "");
	if (!value) return "";
	return /^(https?:|data:|\/)/i.test(value) ? value : `../${value}`;
};

async function loadNews() {
	if (!newsTableBody) return;
	newsTableBody.innerHTML = TNC.loadingRow(7, "Đang tải bài viết...");

	try {
		const response = await fetch("../backend/api/admin/news.php", {
			headers: { Accept: "application/json" },
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) {
			throw new Error(payload.message || "Không thể tải danh sách bài viết.");
		}
		newsPosts = payload.data || [];
		renderNews();
	} catch (error) {
		newsTableBody.innerHTML = `
			<tr>
				<td colspan="7" class="text-center text-danger py-4">${TNC.escapeHtml(error.message || "Không tải được dữ liệu.")}</td>
			</tr>
		`;
	}
}

function renderNews() {
	if (!newsTableBody) return;

	const keyword = (newsSearch?.value || "").trim().toLowerCase();
	const status = newsStatusFilter?.value || "all";
	const items = newsPosts.filter((post) => {
		if (status !== "all" && post.status !== status) {
			return false;
		}
		if (keyword !== "") {
			const haystack = `${post.title} ${post.slug}`.toLowerCase();
			if (!haystack.includes(keyword)) {
				return false;
			}
		}
		return true;
	});

	if (!items.length) {
		newsTableBody.innerHTML = `
			<tr>
				<td colspan="7" class="text-center text-muted py-4">Không có bài viết nào phù hợp.</td>
			</tr>
		`;
		return;
	}

	newsTableBody.innerHTML = items
		.map((post) => {
			const statusMeta = NEWS_STATUS_META[post.status] || NEWS_STATUS_META.draft;
			const thumb = post.coverImage
				? `<img class="news-thumb me-2" src="${TNC.escapeHtml(newsAssetUrl(post.coverImage))}" alt="" loading="lazy" />`
				: "";

			return `
				<tr>
					<td>#${post.id}</td>
					<td>
						<div class="d-flex align-items-center">
							${thumb}
							<div>
								<strong>${TNC.escapeHtml(post.title)}</strong><br />
								<small class="text-muted">/${TNC.escapeHtml(post.slug)}</small>
							</div>
						</div>
					</td>
					<td>${TNC.escapeHtml(post.category)}</td>
					<td><span class="badge text-bg-${statusMeta.className}">${statusMeta.label}</span></td>
					<td>${TNC.escapeHtml(post.dateLabel)}</td>
					<td>${Number(post.views || 0).toLocaleString("vi-VN")}</td>
					<td>
						<div class="d-flex gap-2">
							${
								post.status === "published"
									? `<a class="btn btn-sm btn-outline-secondary" href="../news-post.php?slug=${encodeURIComponent(post.slug)}" target="_blank" rel="noopener">Xem</a>`
									: ""
							}
							<a class="btn btn-sm btn-outline-primary" href="news-edit.php?id=${post.id}">Sửa</a>
							<button class="btn btn-sm btn-outline-danger" type="button" data-action="delete" data-id="${post.id}">Xoá</button>
						</div>
					</td>
				</tr>
			`;
		})
		.join("");
}

async function deleteNewsPost(postId) {
	if (!window.confirm("Bạn có chắc chắn muốn xoá bài viết này?")) {
		return;
	}

	try {
		const response = await fetch(`../backend/api/admin/news.php?id=${postId}`, {
			method: "DELETE",
			headers: { Accept: "application/json" },
		});
		const result = await response.json();
		if (!response.ok || !result.ok) {
			throw new Error(result.message || "Không thể xoá bài viết.");
		}
		await loadNews();
	} catch (error) {
		alert(error.message || "Có lỗi xảy ra khi xoá bài viết.");
	}
}

newsSearch?.addEventListener("input", renderNews);
newsStatusFilter?.addEventListener("change", renderNews);

document.addEventListener("click", async (event) => {
	const target = event.target.closest("[data-action]");
	if (!target || target.dataset.action !== "delete") return;
	const postId = Number(target.dataset.id || 0);
	if (!postId) return;

	await deleteNewsPost(postId);
});

loadNews();

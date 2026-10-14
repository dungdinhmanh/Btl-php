/** Trang quản lý đánh giá: lọc, ẩn/hiện, xoá. */

const ADMIN_REVIEW_PAGE_SIZE = 20;

const adminReviewState = {
	page: 1,
	pages: 1,
	search: "",
	status: "all",
};

const adminReviewBody = document.querySelector("#admin-review-table-body");
const adminReviewSearch = document.querySelector("#review-search");
const adminReviewFilter = document.querySelector("#review-status-filter");
const adminReviewPagination = document.querySelector("#review-pagination");

async function loadAdminReviews() {
	if (!adminReviewBody) return;

	adminReviewBody.innerHTML = TNC.loadingRow(7, "Đang tải đánh giá...");

	try {
		const response = await fetch("../backend/api/admin/reviews.php", {
			headers: { Accept: "application/json" },
		});
		const payload = await response.json();
		if (!response.ok || !payload.ok) {
			throw new Error(payload.message || "Không thể tải danh sách đánh giá.");
		}

		renderAdminReviews(payload.data);
	} catch (error) {
		adminReviewBody.innerHTML = `
			<tr>
				<td colspan="7" class="text-center text-danger py-4">${TNC.escapeHtml(error.message)}</td>
			</tr>
		`;
	}
}

function adminReviewQuery() {
	const params = new URLSearchParams({
		page: String(adminReviewState.page),
		status: adminReviewState.status,
	});
	if (adminReviewState.search) params.set("search", adminReviewState.search);
	return params;
}

async function fetchAdminReviews() {
	const response = await fetch(`../backend/api/admin/reviews.php?${adminReviewQuery()}`, {
		headers: { Accept: "application/json" },
	});
	const payload = await response.json();
	if (!response.ok || !payload.ok) {
		throw new Error(payload.message || "Không thể tải danh sách đánh giá.");
	}
	return payload.data;
}

function renderAdminReviews(data) {
	adminReviewState.pages = Math.max(1, Number(data.pages || 1));

	if (!data.reviews.length) {
		adminReviewBody.innerHTML = `
			<tr>
				<td colspan="7" class="text-center text-muted py-4">Chưa có đánh giá nào.</td>
			</tr>
		`;
	} else {
		adminReviewBody.innerHTML = data.reviews.map(renderAdminReviewRow).join("");
	}

	renderAdminReviewPagination(data.total);
}

function renderAdminReviewRow(review) {
	const stars = review.rating
		? Array.from(
				{ length: 5 },
				(_, index) => `<i class="icon-star star${index < review.rating ? index + 1 : 0}"></i>`,
			).join("")
		: `<span class="badge text-bg-light">${review.isStaff ? "Trả lời của TNC" : "Trả lời"}</span>`;

	const origin = review.isReply
		? ""
		: review.isLoggedIn
			? '<small class="text-muted"> · đã đăng nhập</small>'
			: '<small class="text-muted"> · khách vãng lai</small>';

	return `
		<tr data-review-id="${review.id}">
			<td style="max-width: 220px">
				<a href="../product-detail.php?slug=${encodeURIComponent(review.productSlug)}">${TNC.escapeHtml(review.productName)}</a>
			</td>
			<td>
				<strong>${TNC.escapeHtml(review.name)}</strong>${origin}<br />
				<small class="text-muted">${TNC.escapeHtml(review.phone)}</small>
			</td>
			<td style="white-space: nowrap">${stars}</td>
			<td style="max-width: 320px">${TNC.escapeHtml(review.content)}</td>
			<td style="white-space: nowrap">${TNC.escapeHtml(review.createdAtText)}</td>
			<td>
				<span class="badge text-bg-${review.status === "published" ? "success" : "secondary"}">
					${review.status === "published" ? "Đang hiện" : "Đang ẩn"}
				</span>
			</td>
			<td style="white-space: nowrap">
				<button class="btn btn-sm btn-outline-secondary" type="button"
					data-review-action="toggle" data-review-id="${review.id}"
					data-review-status="${review.status}">
					${review.status === "published" ? "Ẩn" : "Hiện"}
				</button>
				<button class="btn btn-sm btn-outline-danger" type="button"
					data-review-action="delete" data-review-id="${review.id}">
					Xoá
				</button>
			</td>
		</tr>
	`;
}

function renderAdminReviewPagination(total) {
	if (!adminReviewPagination) return;

	if (adminReviewState.pages <= 1) {
		adminReviewPagination.innerHTML = `<small class="text-muted">${total} đánh giá</small>`;
		return;
	}

	adminReviewPagination.innerHTML = `
		<small class="text-muted">${total} đánh giá · trang ${adminReviewState.page}/${adminReviewState.pages}</small>
		<div class="btn-group btn-group-sm">
			<button class="btn btn-outline-secondary" type="button" data-review-page="prev"
				${adminReviewState.page <= 1 ? "disabled" : ""}>Trước</button>
			<button class="btn btn-outline-secondary" type="button" data-review-page="next"
				${adminReviewState.page >= adminReviewState.pages ? "disabled" : ""}>Sau</button>
		</div>
	`;
}

async function toggleAdminReview(reviewId, status) {
	const next = status === "published" ? "hidden" : "published";
	const response = await fetch("../backend/api/admin/reviews.php", {
		method: "PUT",
		headers: { "Content-Type": "application/json", Accept: "application/json" },
		body: JSON.stringify({ id: reviewId, status: next }),
	});
	const payload = await response.json();
	if (!response.ok || !payload.ok) {
		throw new Error(payload.message || "Không thể cập nhật đánh giá.");
	}
}

async function deleteAdminReview(reviewId) {
	const response = await fetch("../backend/api/admin/reviews.php", {
		method: "DELETE",
		headers: { "Content-Type": "application/json", Accept: "application/json" },
		body: JSON.stringify({ id: reviewId }),
	});
	const payload = await response.json();
	if (!response.ok || !payload.ok) {
		throw new Error(payload.message || "Không thể xoá đánh giá.");
	}
}

adminReviewBody?.addEventListener("click", async (event) => {
	const button = event.target.closest("[data-review-action]");
	if (!button) return;

	const reviewId = Number(button.dataset.reviewId);
	if (!reviewId) return;

	button.disabled = true;
	try {
		if (button.dataset.reviewAction === "toggle") {
			await toggleAdminReview(reviewId, button.dataset.reviewStatus);
		} else if (confirm("Xoá đánh giá này? Các trả lời của nó cũng bị xoá theo.")) {
			await deleteAdminReview(reviewId);
		}
		await reloadAdminReviews();
	} catch (error) {
		alert(error.message || "Có lỗi xảy ra.");
	} finally {
		button.disabled = false;
	}
});

adminReviewPagination?.addEventListener("click", (event) => {
	const button = event.target.closest("[data-review-page]");
	if (!button || button.disabled) return;

	adminReviewState.page =
		button.dataset.reviewPage === "next"
			? Math.min(adminReviewState.pages, adminReviewState.page + 1)
			: Math.max(1, adminReviewState.page - 1);
	loadAdminReviews();
});

let adminReviewSearchTimer = null;
adminReviewSearch?.addEventListener("input", () => {
	clearTimeout(adminReviewSearchTimer);
	adminReviewSearchTimer = setTimeout(() => {
		adminReviewState.search = adminReviewSearch.value.trim();
		adminReviewState.page = 1;
		loadAdminReviews();
	}, 350);
});

adminReviewFilter?.addEventListener("change", () => {
	adminReviewState.status = adminReviewFilter.value;
	adminReviewState.page = 1;
	loadAdminReviews();
});

async function reloadAdminReviews() {
	adminReviewBody.innerHTML = TNC.loadingRow(7, "Đang tải đánh giá...");
	try {
		const data = await fetchAdminReviews();
		renderAdminReviews(data);
	} catch (error) {
		adminReviewBody.innerHTML = `
			<tr>
				<td colspan="7" class="text-center text-danger py-4">${TNC.escapeHtml(error.message)}</td>
			</tr>
		`;
	}
}

loadAdminReviews();

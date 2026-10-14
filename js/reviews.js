/**
 * Đánh giá sản phẩm trên trang chi tiết.
 *
 * DOM và class sao chép nguyên văn từ tncstore.vn (#review, #show-form,
 * .box-form-review-reply, .item-user-review, .box-list-reply) — đừng đổi tên class.
 * Quy tắc nghiệp vụ và cách chống trùng đánh giá: backend/README.md mục 4.4.
 */

const REVIEW_STARS = [1, 2, 3, 4, 5];
/** Nhãn hiện dưới ô chọn sao, lấy từ data-title của tncstore.vn. */
const REVIEW_STAR_TITLES = ["Rất tệ", "Tệ", "Tạm ổn", "Ổn", "Rất Tốt"];
const REVIEW_PAGE_SIZE = 5;

const reviewsState = {
	productId: 0,
	product: null,
	slug: "",
	rate: 5,
	replyTo: 0,
	shown: REVIEW_PAGE_SIZE,
};

document.addEventListener("DOMContentLoaded", () => {
	if (!document.querySelector("#js-holder-review")) return;

	const params = new URLSearchParams(window.location.search);
	reviewsState.slug = params.get("slug") || params.get("model") || params.get("id") || "";

	wireReviewModal();
	wireReplyModal();

	// product-detail.js đã tải xong sản phẩm thì dùng luôn id; chưa thì chờ sự kiện.
	if (window.__tncProduct) {
		onProductLoaded(window.__tncProduct);
		return;
	}
	document.addEventListener("tnc:product-loaded", (event) => {
		onProductLoaded(event.detail?.product);
	});
});

function onProductLoaded(product) {
	if (!product) return;
	reviewsState.product = product;
	reviewsState.productId = Number(product.id || 0);

	const image = document.querySelector("#review-modal-image");
	if (image) {
		image.src = product.image || "assets/img/branding/tnc.png";
		image.alt = product.name || "";
	}
	const name = document.querySelector("#review-modal-name");
	if (name) name.textContent = product.name || "";

	loadReviews();
}

/* ── Tải và vẽ danh sách ── */

async function loadReviews() {
	const holder = document.querySelector("#js-holder-review");
	if (!holder) return;

	holder.innerHTML = TNC.loadingRow(1, "Đang tải đánh giá...");

	try {
		const data = await TNC.api.reviews(reviewsState.productId || reviewsState.slug);
		reviewsState.productId = Number(data.summary.productId || reviewsState.productId);
		renderSummary(data.summary);
		renderReviews(data.items);
	} catch (error) {
		TNC.renderError(holder, error);
	}
}

function renderSummary(summary) {
	const total = Number(summary.total || 0);
	const average = Number(summary.average || 0);
	const breakdown = summary.breakdown || {};

	const averageEl = document.querySelector("#review-average");
	if (averageEl) averageEl.textContent = String(average);

	// Dòng "Đánh giá: ★" nằm trong .info-sku, đúng như trang gốc.
	const anchor = document.querySelector("#js-scroll-review");
	if (anchor) {
		if (total > 0) {
			anchor.innerHTML = `Đánh giá: <i class="icon-star star${Math.round(average)}"></i>
				<span style="margin-left: 5px;">${average.toFixed(1)} (${total})</span>`;
		} else {
			anchor.innerHTML = 'Đánh giá: <i class="icon-star star0"></i>';
		}
	}

	const bars = document.querySelector("#review-breakdown");
	if (!bars) return;

	bars.innerHTML = REVIEW_STARS.slice()
		.reverse()
		.map((star) => {
			const count = Number(breakdown[star] || 0);
			const percent = total > 0 ? Math.round((count / total) * 100) : 0;
			return `
				<div class="avg-rate-item">
					<span class="rate-number">${star} <i class="bi bi-star-fill"></i></span>
					<div class="box-percent">
						<div class="percent" style="width:${percent}%"></div>
					</div>
					<span class="total-avg-rate" title="Xem các đánh giá này">${percent}%</span>
				</div>
			`;
		})
		.join("");
}

function renderReviews(items) {
	const holder = document.querySelector("#js-holder-review");
	const moreBtn = document.querySelector("#show_review");
	if (!holder) return;

	const visible = items.slice(0, reviewsState.shown);

	if (items.length === 0) {
		holder.innerHTML = TNC.emptyState({
			icon: "bi-star",
			title: "Chưa có đánh giá nào",
			text: "Hãy là người đầu tiên đánh giá sản phẩm này.",
		});
		if (moreBtn) moreBtn.style.display = "none";
		return;
	}

	holder.innerHTML = visible.map((review) => renderItem(review)).join("");

	if (moreBtn) {
		moreBtn.style.display = items.length > reviewsState.shown ? "" : "none";
		moreBtn.onclick = () => {
			reviewsState.shown += REVIEW_PAGE_SIZE;
			renderReviews(items);
		};
	}
}

function renderItem(review) {
	return `
		<div class="item-user-review item-review" data-review-id="${review.id}">
			<div class="name-info d-flex align-items">
				<b>${TNC.escapeHtml(review.name)}</b>
				${review.rating !== null ? `<i class="icon-star star${review.rating}"></i>` : ""}
			</div>
			<div class="content-info">
				<p>${TNC.escapeHtml(review.content)}</p>
				<div class="action-reply">
					<a href="javascript:void(0)" class="click-reply" data-reply-to="${review.id}"
						data-reply-name="${TNC.escapeHtml(review.name)}"
						data-reply-phone="${TNC.escapeHtml(review.phone)}">Phản hồi</a>
					<span class="time">${TNC.escapeHtml(review.date)}</span>
				</div>
			</div>
			${renderReplies(review.children || [], 1)}
		</div>
	`;
}

function renderReplies(replies, depth) {
	if (replies.length === 0) return "";

	return `
		<div class="box-list-reply" data-depth="${depth}">
			<div class="list-reply">
				${replies
					.map(
						(reply) => `
						<div class="item-reply" data-review-id="${reply.id}">
							<div class="info d-flex align-items">
								<b>${TNC.escapeHtml(reply.name)}</b>
								${reply.isStaff ? '<span class="badge text-bg-primary">TNC Store</span>' : ""}
							</div>
							<div class="cnt">${TNC.escapeHtml(reply.content)}</div>
							<div class="action-reply">
								<a href="javascript:void(0)" class="click-reply" data-reply-to="${reply.id}"
									data-reply-name="${TNC.escapeHtml(reply.name)}"
									data-reply-phone="${TNC.escapeHtml(reply.phone)}">Phản hồi</a>
								<span class="time">${TNC.escapeHtml(reply.date)}</span>
							</div>
							${renderReplies(reply.children || [], depth + 1)}
						</div>
					`,
					)
					.join("")}
			</div>
		</div>
	`;
}

/* ── Modal viết đánh giá ── */

function buildStarSelection() {
	const holder = document.querySelector("#rating-review0");
	if (!holder || holder.dataset.ready === "1") return;
	holder.dataset.ready = "1";

	// Thứ tự ngược (5 → 1) để CSS float: right dựng hình ngôi sao từ phải sang trái,
	// giống hệt tncstore.vn.
	holder.innerHTML = REVIEW_STARS.slice()
		.reverse()
		.map(
			(star) => `
			<input type="radio" class="rating-input" id="rating-input-review-0-${star}" value="${star}"
				name="user_post[rate]" ${star === reviewsState.rate ? "checked" : ""} />
			<label for="rating-input-review-0-${star}" class="sprite-star rating-star"
				data-title="${REVIEW_STAR_TITLES[star - 1]}"></label>
		`,
		)
		.join("");

	holder.addEventListener("change", (event) => {
		if (event.target.classList.contains("rating-input")) {
			reviewsState.rate = Number(event.target.value);
		}
	});
}

function wireReviewModal() {
	const modal = document.querySelector("#show-form");
	const openBtn = document.querySelector("#js-show-review");
	const form = document.querySelector("#review-form");
	if (!modal || !openBtn || !form) return;

	buildStarSelection();

	openBtn.addEventListener("click", () => {
		openModal(modal);
		document.querySelector("#review-content")?.focus();
	});

	document.querySelectorAll("[data-close-review]").forEach((button) => {
		button.addEventListener("click", () => closeModal(button.closest(".box-form-review, .box-form-review-reply")));
	});

	form.addEventListener("submit", (event) => {
		event.preventDefault();
		submitReview(form, modal);
	});
}

async function submitReview(form, modal) {
	const errorEl = document.querySelector("#review-error");
	const submit = document.querySelector("#review-submit");

	clearFormError(form, errorEl);
	submit?.classList.add("is-loading");

	try {
		await TNC.api.createReview({
			product_id: reviewsState.productId,
			rating: reviewsState.rate,
			name: document.querySelector("#review-name").value,
			phone: document.querySelector("#review-phone").value,
			content: document.querySelector("#review-content").value,
		});

		form.reset();
		showFormError(form, null, "Cảm ơn bạn đã đánh giá sản phẩm.");
		closeModal(modal);
		reviewsState.shown = REVIEW_PAGE_SIZE;
		await loadReviews();
	} catch (error) {
		showFormError(form, errorEl, error?.message || "Không gửi được đánh giá.");
	} finally {
		submit?.classList.remove("is-loading");
	}
}

/* ── Modal trả lời ── */

function wireReplyModal() {
	const modal = document.querySelector("#show-reply");
	const form = document.querySelector("#reply-form");
	if (!modal || !form) return;

	document.addEventListener("click", (event) => {
		const trigger = event.target.closest("[data-reply-to]");
		if (!trigger) return;

		reviewsState.replyTo = Number(trigger.dataset.replyTo);
		document.querySelector("#reply-name").value = trigger.dataset.replyName || "";
		document.querySelector("#reply-phone").value = trigger.dataset.replyPhone || "";

		openModal(modal);
		document.querySelector("#reply-content").focus();
	});

	form.addEventListener("submit", (event) => {
		event.preventDefault();
		submitReply(form, modal);
	});
}

async function submitReply(form, modal) {
	const errorEl = document.querySelector("#reply-error");
	const submit = document.querySelector("#reply-submit");

	clearFormError(form, errorEl);
	submit?.classList.add("is-loading");

	try {
		await TNC.api.replyReview({
			parent_id: reviewsState.replyTo,
			name: document.querySelector("#reply-name").value,
			phone: document.querySelector("#reply-phone").value,
			content: document.querySelector("#reply-content").value,
		});

		form.reset();
		closeModal(modal);
		await loadReviews();
	} catch (error) {
		showFormError(form, errorEl, error?.message || "Không gửi được trả lời.");
	} finally {
		submit?.classList.remove("is-loading");
	}
}

/* ── Modal helpers ── */

function openModal(modal) {
	modal.classList.add("active");
	document.body.style.overflow = "hidden";
}

function closeModal(modal) {
	if (!modal) return;
	modal.classList.remove("active");
	if (!document.querySelector(".box-form-review.active, .box-form-review-reply.active")) {
		document.body.style.overflow = "";
	}
}

document.addEventListener("keydown", (event) => {
	if (event.key !== "Escape") return;
	document.querySelectorAll(".box-form-review.active, .box-form-review-reply.active").forEach(closeModal);
});

/** Báo lỗi ngay dưới ô nhập sai, giống .form-input.error của tncstore.vn. */
function showFormError(form, errorEl, message) {
	form.querySelectorAll(".form-input.error").forEach((node) => node.classList.remove("error"));

	if (errorEl) {
		errorEl.textContent = message;
		errorEl.hidden = false;
	}
}

function clearFormError(form, errorEl) {
	form.querySelectorAll(".form-input.error").forEach((node) => node.classList.remove("error"));
	if (errorEl) {
		errorEl.textContent = "";
		errorEl.hidden = true;
	}
}

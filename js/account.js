/**
 * Tài khoản: đăng nhập / đăng ký / quên mật khẩu (jQuery 4).
 *
 * Một logic dùng cho hai nơi:
 *   1. Panel thả xuống ở header:   [data-account-panel] > [data-account-form="login|register|forgot-password"]
 *   2. Trang riêng:                form[data-auth-form="login|register|forgot-password"]
 *
 * Mỗi "root" chứa các input có thuộc tính name và một phần tử .auth-note để hiện thông báo.
 * Cần nạp jQuery (partial/link.php) trước file này.
 */
(function ($) {
	"use strict";

	const ENDPOINT = {
		login: "backend/auth/login.php",
		register: "backend/auth/register.php",
	};

	const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	const emailRule = (v) => !EMAIL_RE.test(v) && "Vui lòng nhập email hợp lệ.";

	// Mỗi rule trả về chuỗi lỗi, hoặc false nếu hợp lệ.
	const RULES = {
		login: {
			email: emailRule,
			password: (v) => !v && "Vui lòng nhập mật khẩu.",
		},
		register: {
			name: (v) => !v && "Vui lòng nhập họ và tên.",
			email: emailRule,
			password: (v) => v.length < 8 && "Mật khẩu tối thiểu 8 ký tự.", // khớp backend/auth/register.php
		},
		"forgot-password": {
			email: emailRule,
		},
	};

	const ERROR_TEXT = {
		0: "Không kết nối được máy chủ. Vui lòng thử lại.",
		401: "Email hoặc mật khẩu không đúng.",
		409: "Email này đã được đăng ký.",
		422: "Thông tin chưa hợp lệ, vui lòng kiểm tra lại.",
		503: "Chưa kết nối được cơ sở dữ liệu.",
	};
	const errorMessage = (xhr) => ERROR_TEXT[xhr.status] || "Có lỗi xảy ra, vui lòng thử lại.";

	function setNote($root, text, kind) {
		$root
			.find(".auth-note")
			.text(text)
			.toggleClass("text-danger", kind === "error")
			.toggleClass("text-success", kind === "success");
	}

	function setFieldError($input, message) {
		const $wrap = $input.closest(".form-input"); // panel header
		if ($wrap.length) {
			$wrap.addClass("error").find(".note-error").text(message);
			return;
		}
		// trang riêng (Bootstrap)
		$input.addClass("is-invalid").next(".invalid-feedback").remove();
		$("<div>").addClass("invalid-feedback").text(message).insertAfter($input);
	}

	function clearFieldError($input) {
		$input.closest(".form-input").removeClass("error").find(".note-error").text("");
		$input.removeClass("is-invalid").next(".invalid-feedback").remove();
	}

	function clearErrors($root) {
		$root.find(":input[name]").each(function () {
			clearFieldError($(this));
		});
		setNote($root, "", null);
	}

	function readValues($root) {
		const values = {};
		$root.find(":input[name]").each(function () {
			const raw = $(this).val();
			values[this.name] = this.type === "password" ? raw : raw.trim();
		});
		return values;
	}

	function validate($root, type, values) {
		let $first = null;
		$.each(RULES[type], (name, rule) => {
			const message = rule(values[name] ?? "");
			if (!message) return;
			const $input = $root.find(`[name="${name}"]`);
			setFieldError($input, message);
			$first ??= $input;
		});
		if ($first) $first.trigger("focus");
		return !$first;
	}

	function setBusy($root, busy) {
		const $btn = $root.find(".btn-submit, [type=submit]").first();
		const $label = $btn.find(".txt").length ? $btn.find(".txt") : $btn;
		$root.data("busy", busy);
		if (busy) {
			$label.data("text", $label.text()).text("Đang xử lý...");
		} else if ($label.data("text") !== undefined) {
			$label.text($label.data("text"));
		}
		$btn.css("opacity", busy ? 0.6 : "").prop("disabled", busy);
	}

	const isPanelForm = ($root) => $root.is("[data-account-form]");
	const formType = ($root) => $root.data("accountForm") || $root.data("authForm");

	function onSuccess($root, type, res) {
		const name = res.data.name;
		setNote(
			$root,
			type === "login" ? `Đăng nhập thành công. Xin chào ${name}!` : "Tạo tài khoản thành công!",
			"success",
		);

		setTimeout(() => {
			if (isPanelForm($root)) {
				// Header được PHP dựng theo phiên đăng nhập, nên tải lại để "Tài khoản" đổi thành tên người dùng.
				window.location.reload();
			} else {
				window.location.href = "index.php";
			}
		}, 900);
	}

	function submit($root) {
		const type = formType($root);
		if (!RULES[type] || $root.data("busy")) return;

		clearErrors($root);
		const values = readValues($root);
		if (!validate($root, type, values)) return;

		if (type === "forgot-password") {
			// TODO: chưa có backend/auth/forgot-password.php (cần bảng token + gửi mail).
			// Hiện chỉ kiểm tra email ở phía trình duyệt rồi hiện thông báo chung.
			setNote(
				$root,
				"Nếu email đã đăng ký, hướng dẫn đặt lại mật khẩu sẽ được gửi tới bạn.",
				"success",
			);
			return;
		}

		setBusy($root, true);
		$.ajax({
			url: ENDPOINT[type],
			method: "POST",
			contentType: "application/json",
			data: JSON.stringify(values),
			dataType: "json",
		})
			.done((res) => {
				if (res && res.ok === true && res.data) return onSuccess($root, type, res);
				setNote($root, "Có lỗi xảy ra, vui lòng thử lại.", "error");
				setBusy($root, false);
			})
			.fail((xhr) => {
				setNote($root, errorMessage(xhr), "error");
				setBusy($root, false);
			});
	}

	/* ---------- Panel thả xuống ở header ---------- */

	const $panel = $("[data-account-panel]");

	function showForm(name) {
		const $target = $panel.find(`[data-account-form="${name}"]`);
		if (!$target.length) return;

		const wasHidden = !$panel.is(":visible");
		clearErrors($target);
		$panel.find(".content-login").removeClass("active");
		$target.addClass("active");

		if (wasHidden) {
			$panel.stop(true, true).fadeIn(120);
		} else {
			$target.hide().fadeIn(150);
		}
	}

	function closePanel() {
		$panel.stop(true, true).fadeOut(120);
	}

	$(function () {
		$(document)
			// mở / đóng panel
			.on("click", "[data-account-toggle]", function (event) {
				event.preventDefault();
				if ($panel.is(":visible")) return closePanel();
				showForm($panel.find(".content-login.active").data("accountForm") || "login");
			})
			// chuyển giữa đăng nhập / đăng ký / quên mật khẩu
			.on("click", "[data-account-form-link]", function (event) {
				event.preventDefault();
				showForm($(this).data("accountFormLink"));
			})
			.on("click", "[data-account-close]", function (event) {
				event.preventDefault();
				closePanel();
			})
			// bấm ra ngoài thì đóng
			.on("click", function (event) {
				if (!$(event.target).closest("[data-account-panel], [data-account-toggle]").length) {
					closePanel();
				}
			})
			.on("keydown", function (event) {
				if (event.key === "Escape") closePanel();
			})
			// gửi form trong panel
			.on("click", "[data-account-panel] .btn-submit", function (event) {
				event.preventDefault();
				submit($(this).closest("[data-account-form]"));
			})
			.on("keydown", "[data-account-panel] :input[name]", function (event) {
				if (event.key !== "Enter") return;
				event.preventDefault();
				submit($(this).closest("[data-account-form]"));
			})
			// gửi form ở trang riêng
			.on("submit", "form[data-auth-form]", function (event) {
				event.preventDefault();
				submit($(this));
			})
			// gõ lại thì xóa lỗi của ô đó
			.on("input", "[data-account-form] :input[name], form[data-auth-form] :input[name]", function () {
				clearFieldError($(this));
			});

		if ($panel.length) {
			if (window.location.hash === "#accountModal-register") showForm("register");
			else if (window.location.hash === "#accountModal") showForm("login");
		}
	});
})(jQuery);

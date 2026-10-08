(function ($) {
	"use strict";

	const STATUS = {
		pending: ["Chờ xác nhận", "secondary"],
		confirmed: ["Đã xác nhận", "info"],
		shipping: ["Đang giao", "warning"],
		completed: ["Hoàn tất", "success"],
		cancelled: ["Đã hủy", "danger"],
	};

	const money = (value) => `${Number(value).toLocaleString("vi-VN")}đ`;
	const setText = (key, text) => $(`[data-profile="${key}"]`).text(text);

	function renderUser(user) {
		setText("side-name", user.name);
		setText("side-email", user.email);
		setText("joined", user.joinedAt);
		$('[data-profile-form="details"] [name="name"]').val(user.name);
		$('[data-profile-form="details"] [name="email"]').val(user.email);
		$('[data-profile-form="details"] [name="phone"]').val(user.phone || "");
	}

	function renderStats(stats) {
		setText("stat-total", stats.total);
		setText("stat-processing", stats.processing);
		setText("stat-spent", money(stats.spent));
	}

	function renderOrders(orders) {
		const $body = $("#orders-body").empty();

		if (!orders.length) {
			const $empty = $("<td>").attr("colspan", 4).addClass("text-center text-muted py-4");
			$empty.append("Bạn chưa có đơn hàng nào. ", $("<a>").attr("href", "products.php").text("Mua sắm ngay"));
			$body.append($("<tr>").append($empty));
			return;
		}

		const $rows = $.map(orders, (order) => {
			const [label, color] = STATUS[order.statusCode] || [order.statusName, "secondary"];
			const $badge = $("<span>").addClass(`badge text-bg-${color}`).text(label);

			return $("<tr>").append(
				$("<td>").append(
					$("<a>")
						.attr({ href: `invoice.php?order=${encodeURIComponent(order.number)}`, title: "Xem hóa đơn" })
						.text(order.number),
				),
				$("<td>").text(order.date),
				$("<td>").append($badge),
				$("<td>").addClass("text-end").text(money(order.total)),
			);
		});
		$body.append($rows);
	}

	function renderAddress(address) {
		const $box = $('[data-profile="address"]').empty();

		if (!address) {
			$box.append($("<span>").addClass("text-muted").text("Chưa có địa chỉ mặc định."));
			return;
		}

		$box.append(
			$("<strong>").text(address.recipientName),
			$("<p>")
				.addClass("text-muted mb-0")
				.append(document.createTextNode(address.recipientPhone), $("<br>"), document.createTextNode(address.text)),
		);
	}

	function showError(message) {
		$("[data-profile-error]").text(message).removeClass("d-none");
		$("#orders-body td").text("Không tải được dữ liệu.");
		$('[data-profile="address"]').text("Không tải được dữ liệu.");
	}

	function submitForm($form, data) {
		$("[data-profile-error], [data-profile-success]").addClass("d-none");
		const $button = $form.find('button[type="submit"]').prop("disabled", true);
		$.ajax({ url: "backend/api/profile.php", method: "POST", data, dataType: "json" })
			.done((response) => {
				$form[0].reset();
				if (data.action === "delete-account") {
					window.location.assign("index.php");
					return;
				}
				if (data.action === "update-details") {
					renderUser({
						name: data.name,
						email: data.email,
						phone: data.phone,
						joinedAt: $('[data-profile="joined"]').text(),
					});
				}
				$('[data-profile-success]').text(response.message).removeClass("d-none");
			})
			.fail((xhr) => {
				const message = xhr.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại.";
				$('[data-profile-error]').text(message).removeClass("d-none");
			})
			.always(() => $button.prop("disabled", false));
	}

	$(function () {
		if (!$("#profile-root").length) return;

		$('[data-profile-form="details"]').on("submit", function (event) {
			event.preventDefault();
			submitForm($(this), { action: "update-details", ...Object.fromEntries(new FormData(this)) });
		});
		$('[data-profile-form="password"]').on("submit", function (event) {
			event.preventDefault();
			submitForm($(this), { action: "update-password", ...Object.fromEntries(new FormData(this)) });
		});
		$('[data-profile-form="delete"]').on("submit", function (event) {
			event.preventDefault();
			if (!window.confirm("Tài khoản sẽ bị vô hiệu hóa. Bạn có chắc chắn muốn tiếp tục?")) return;
			submitForm($(this), { action: "delete-account", ...Object.fromEntries(new FormData(this)) });
		});

		$.ajax({ url: "backend/api/profile.php", dataType: "json" })
			.done((res) => {
				const data = res.data;
				renderUser(data.user);
				renderStats(data.stats);
				renderOrders(data.orders);
				renderAddress(data.address);
			})
			.fail((xhr) => {
				if (xhr.status === 401) {
					window.location.href = "login.php";
					return;
				}
				showError(xhr.status === 503 ? "Chưa kết nối được cơ sở dữ liệu." : "Có lỗi xảy ra, vui lòng thử lại.");
			});
	});
})(jQuery);
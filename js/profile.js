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
		setText("name", user.name);
		setText("email", user.email);
		setText("phone", user.phone || "Chưa cập nhật");
		setText("joined", user.joinedAt);
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
				$("<td>").text(order.number),
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

	$(function () {
		if (!$("#profile-root").length) return;

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
					window.location.href = "index.php#accountModal";
					return;
				}
				showError(xhr.status === 503 ? "Chưa kết nối được cơ sở dữ liệu." : "Có lỗi xảy ra, vui lòng thử lại.");
			});
	});
})(jQuery);
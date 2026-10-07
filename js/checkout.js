const checkoutForm = document.getElementById("checkoutForm");
const checkoutSubmitButton = document.querySelector("[data-checkout-submit]");
const voucherInput = document.getElementById("voucher-code");
const voucherButton = document.querySelector("[data-voucher-apply]");
const voucherMessage = document.querySelector("[data-voucher-message]");
const checkoutError = document.querySelector("[data-checkout-error]");
const requiredCheckoutFields = ["customer-name", "customer-phone", "customer-address"];

function markInvalid(field) {
	field.classList.add("is-invalid");
	field.classList.remove("shake");
	void field.offsetWidth;
	field.classList.add("shake");
}

function clearInvalid(field) {
	field.classList.remove("is-invalid", "shake");
}

if (checkoutForm && checkoutSubmitButton) {
	checkoutForm.addEventListener("submit", async (event) => {
		event.preventDefault();
		checkoutError?.classList.add("d-none");
		let firstInvalid;

		requiredCheckoutFields.forEach((id) => {
			const field = document.getElementById(id);
			if (!field) return;
			if (!field.value.trim()) {
				markInvalid(field);
				firstInvalid ||= field;
			} else {
				clearInvalid(field);
			}
		});

		if (firstInvalid) {
			firstInvalid.focus();
			return;
		}

		let cart;
		try {
			cart = JSON.parse(localStorage.getItem("tnc-cart") || "[]");
		} catch {
			cart = [];
		}
		if (!Array.isArray(cart) || cart.length === 0 || cart.some((item) => !Number(item.productId))) {
			if (checkoutError) {
				checkoutError.textContent = "Giỏ hàng trống hoặc sản phẩm đã cũ. Vui lòng quay lại thêm sản phẩm một lần nữa.";
				checkoutError.classList.remove("d-none");
			}
			return;
		}

		checkoutSubmitButton.disabled = true;
		try {
			const response = await fetch("backend/api/checkout.php", {
				method: "POST",
				headers: { "Content-Type": "application/json", Accept: "application/json" },
				body: JSON.stringify({
					customer: {
						name: document.getElementById("customer-name").value.trim(),
						phone: document.getElementById("customer-phone").value.trim(),
						address: document.getElementById("customer-address").value.trim(),
						city: document.getElementById("customer-city").value.trim(),
					},
					items: cart.map((item) => ({ product_id: Number(item.productId), quantity: Number(item.quantity) })),
				}),
			});
			const result = await response.json();
			if (!response.ok || !result.ok) {
				throw new Error(result.message || "Không thể tạo đơn hàng.");
			}
			localStorage.removeItem("tnc-cart");
			window.location.assign("success.php");
		} catch (error) {
			if (checkoutError) {
				checkoutError.textContent = error.message || "Có lỗi xảy ra, vui lòng thử lại.";
				checkoutError.classList.remove("d-none");
			}
			checkoutSubmitButton.disabled = false;
		}
	});

	requiredCheckoutFields.forEach((id) => {
		const field = document.getElementById(id);
		field?.addEventListener("input", () => {
			if (field.value.trim()) clearInvalid(field);
		});
	});
}

voucherButton?.addEventListener("click", () => {
	const voucher = voucherInput?.value.trim();
	if (!voucher) {
		voucherMessage.textContent = "Vui lòng nhập mã ưu đãi.";
		voucherInput?.focus();
		return;
	}

	voucherMessage.textContent = "Mã đã được ghi nhận và sẽ được xác minh khi đặt hàng.";
});

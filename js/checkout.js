const checkoutForm = document.getElementById('checkoutForm');
const checkoutSubmitButton = document.querySelector('[data-checkout-submit]');
const voucherInput = document.getElementById('voucher-code');
const voucherButton = document.querySelector('[data-voucher-apply]');
const voucherMessage = document.querySelector('[data-voucher-message]');
const requiredCheckoutFields = ['customer-name', 'customer-phone', 'customer-address'];

function markInvalid(field) {
	field.classList.add('is-invalid');
	field.classList.remove('shake');
	void field.offsetWidth;
	field.classList.add('shake');
}

function clearInvalid(field) {
	field.classList.remove('is-invalid', 'shake');
}

if (checkoutForm && checkoutSubmitButton) {
	checkoutSubmitButton.addEventListener('click', (event) => {
		event.preventDefault();
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

		checkoutForm.submit();
	});

	requiredCheckoutFields.forEach((id) => {
		const field = document.getElementById(id);
		field?.addEventListener('input', () => {
			if (field.value.trim()) clearInvalid(field);
		});
	});
}

voucherButton?.addEventListener('click', () => {
	const voucher = voucherInput?.value.trim();
	if (!voucher) {
		voucherMessage.textContent = 'Vui lòng nhập mã ưu đãi.';
		voucherInput?.focus();
		return;
	}

	voucherMessage.textContent = 'Mã đã được ghi nhận và sẽ được xác minh khi đặt hàng.';
});

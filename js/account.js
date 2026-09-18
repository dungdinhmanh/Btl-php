document.addEventListener("DOMContentLoaded", () => {
	const modalElement = document.querySelector("#accountModal");
	if (!modalElement || typeof bootstrap === "undefined") return;

	if (
		window.location.hash === "#accountModal" ||
		window.location.hash === "#accountModal-register"
	) {
		if (window.location.hash === "#accountModal-register") {
			const registerTab = modalElement.querySelector(
				'[data-bs-target="#account-register-pane"]',
			);
			registerTab && bootstrap.Tab.getOrCreateInstance(registerTab).show();
		}
		bootstrap.Modal.getOrCreateInstance(modalElement).show();
	}
});

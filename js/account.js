document.addEventListener("DOMContentLoaded", () => {
	const panel = document.querySelector("[data-account-panel]");
	if (!panel) return;

	const showForm = (name) => {
		panel.querySelectorAll(".content-login").forEach((form) => {
			form.classList.toggle("active", form.dataset.accountForm === name);
		});
		panel.style.display = "block";
	};

	const closePanel = () => {
		panel.style.display = "none";
	};

	document.querySelectorAll("[data-account-toggle]").forEach((trigger) => {
		trigger.addEventListener("click", (event) => {
			event.preventDefault();
			if (panel.style.display === "block") {
				closePanel();
				return;
			}
			const active = panel.querySelector(".content-login.active");
			showForm(active ? active.dataset.accountForm : "login");
		});
	});

	panel.querySelectorAll("[data-account-form-link]").forEach((link) => {
		link.addEventListener("click", (event) => {
			event.preventDefault();
			showForm(link.dataset.accountFormLink);
		});
	});

	panel.querySelector("[data-account-close]").addEventListener("click", closePanel);

	document.addEventListener("click", (event) => {
		if (
			!panel.contains(event.target) &&
			!event.target.closest("[data-account-toggle]")
		) {
			closePanel();
		}
	});

	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape") closePanel();
	});

	if (window.location.hash === "#accountModal-register") showForm("register");
	if (window.location.hash === "#accountModal") showForm("login");
});

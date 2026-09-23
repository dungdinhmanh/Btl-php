/**
 * Header search form: category dropdown
 * - toggles the option list (`.show`, Bootstrap's dropdown-menu convention)
 * - swaps the label shown on the toggle
 * - marks the selected option
 */
(function () {
	const dropdown = document.querySelector(".search-form .category-dropdown");
	if (!dropdown) return;

	const toggle = dropdown.querySelector("#js-search-title");
	const menu = dropdown.querySelector("#js-list-option-search");
	const label = toggle ? toggle.querySelector("span") : null;
	const items = Array.from(dropdown.querySelectorAll(".dropdown-item"));

	if (!toggle || !menu || !items.length) return;

	const isOpen = () => menu.classList.contains("show");

	function close() {
		menu.classList.remove("show");
		toggle.setAttribute("aria-expanded", "false");
	}

	function open() {
		menu.classList.add("show");
		toggle.setAttribute("aria-expanded", "true");
	}

	toggle.addEventListener("click", (event) => {
		event.stopPropagation();
		isOpen() ? close() : open();
	});

	items.forEach((item) => {
		item.addEventListener("click", (event) => {
			event.stopPropagation();

			if (label) label.textContent = item.textContent.trim();
			items.forEach((other) => other.classList.toggle("active", other === item));

			close();
		});
	});

	document.addEventListener("click", (event) => {
		if (isOpen() && !dropdown.contains(event.target)) close();
	});

	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape" && isOpen()) {
			close();
			toggle.focus();
		}
	});
})();

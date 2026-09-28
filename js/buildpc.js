const componentTypes = [
	{
		key: "cpu",
		label: "Bộ vi xử lý",
		icon: "bi-cpu",
		category: "cpu",
	},
	{
		key: "motherboard",
		label: "Bo mạch chủ",
		icon: "bi-motherboard",
		category: "mainboard",
	},
	{
		key: "gpu",
		label: "Card đồ họa",
		icon: "bi-gpu-card",
		category: "vga",
	},
	{
		key: "ram",
		label: "RAM",
		icon: "bi-memory",
		category: "ram",
	},
	{
		key: "storage",
		label: "Ổ lưu trữ",
		icon: "bi-device-ssd",
		category: "ssd",
	},
	{
		key: "psu",
		label: "Nguồn máy tính",
		icon: "bi-lightning-charge",
		category: "psu",
	},
	{
		key: "case",
		label: "Vỏ máy",
		icon: "bi-pc",
		category: "case",
	},
	{
		key: "cooler",
		label: "Tản nhiệt CPU",
		icon: "bi-fan",
		category: "cooler",
	},
];

// Selections may hold strings written by the pre-MySQL version of this page.
const storedSelections = JSON.parse(localStorage.getItem("tnc-buildpc") || "{}");
const selections = {};
Object.entries(storedSelections).forEach(([key, value]) => {
	selections[key] = typeof value === "string" ? { name: value, price: 0 } : value;
});

const componentList = document.querySelector("#component-list");
const selectedCount = document.querySelector("#selected-count");
const summaryTitle = document.querySelector("#summary-title");
const summaryList = document.querySelector("#summary-list");
const partPickerModal = document.querySelector("#partPickerModal");
const partPickerTitle = document.querySelector("#part-picker-title");
const partSearch = document.querySelector("#part-search");
const partBrandFilter = document.querySelector("#part-brand-filter");
const partPickerResults = document.querySelector("#part-picker-results");
let activeComponentKey = "";

function renderComponents() {
	componentList.innerHTML = componentTypes
		.map(
			(component, index) => `
				<section class="component-card ${selections[component.key] ? "is-selected" : ""}">
					<div class="component-card-head">
						<div class="component-icon"><i class="bi ${component.icon}"></i></div>
						<div>
							<p class="component-step">0${index + 1}</p>
							<h3>${component.label}</h3>						</div>
						<span class="component-status">${selections[component.key] ? "Đã chọn" : "Chưa chọn"}</span>
					</div>
					<button class="component-add ${selections[component.key] ? "has-selection" : ""}" type="button" data-component-key="${component.key}">
						<span class="component-add-icon"><i class="bi ${selections[component.key] ? "bi-pencil-square" : "bi-plus-lg"}"></i></span>
						<span><strong>${selections[component.key]?.name || "Thêm " + component.label.toLowerCase()}</strong><small>${selections[component.key] ? "Nhấn để thay đổi linh kiện" : "Mở kho sản phẩm để chọn"}</small></span>
						<i class="bi bi-arrow-right-short" aria-hidden="true"></i>
					</button>
				</section>
			`,
		)
		.join("");

	componentList.querySelectorAll("[data-component-key]").forEach((button) => {
		button.addEventListener("click", () => openPartPicker(button.dataset.componentKey));
	});
}

function renderSummary() {
	const chosen = componentTypes.filter((component) => selections[component.key]);
	selectedCount.textContent = `${chosen.length}/8`;
	summaryTitle.textContent = chosen.length === 8 ? "Sẵn sàng để kiểm tra" : "Chưa hoàn thiện";

	summaryList.innerHTML = chosen.length
		? chosen
				.map(
					(component) => `
						<div>
							<span>${TNC.escapeHtml(component.label)}</span>
							<strong>${TNC.escapeHtml(selections[component.key].name)}</strong>
						</div>
					`,
				)
				.join("")
		: '<p class="summary-empty">Các linh kiện bạn chọn sẽ xuất hiện ở đây.</p>';

	const total = chosen.reduce(
		(sum, component) => sum + (Number(selections[component.key].price) || 0),
		0,
	);
	const totalEl = document.querySelector("#total-price");
	if (totalEl) totalEl.textContent = total > 0 ? TNC.formatPrice(total) : "Chưa có dữ liệu";

	renderCompatibility(chosen.length);
}

function renderCompatibility(chosenCount) {
	const holder = document.querySelector("#compatibility-message");
	if (!holder) return;

	const cpu = selections.cpu;
	const board = selections.motherboard;
	let icon = "bi-info-circle";
	let text = "Chọn CPU và bo mạch chủ để bắt đầu kiểm tra.";

	if (cpu && board) {
		if (cpu.socket && board.socket && cpu.socket === board.socket) {
			icon = "bi-check-circle";
			text = `CPU và bo mạch chủ cùng chuẩn Socket ${cpu.socket}.`;
		} else if (cpu.socket && board.socket) {
			icon = "bi-exclamation-triangle";
			text = `Socket không khớp: CPU dùng ${cpu.socket}, bo mạch chủ dùng ${board.socket}.`;
		}
	} else if (chosenCount > 0) {
		text = "Chọn thêm linh kiện để TNC kiểm tra tương thích.";
	}

	holder.innerHTML = `<i class="bi ${icon}"></i><span>${TNC.escapeHtml(text)}</span>`;
}

function openPartPicker(componentKey) {
	activeComponentKey = componentKey;
	const component = componentTypes.find((item) => item.key === componentKey);
	partPickerTitle.textContent = `Chọn ${component.label.toLowerCase()}`;
	partSearch.value = "";
	partBrandFilter.value = "";
	bootstrap.Modal.getOrCreateInstance(partPickerModal).show();
	loadParts();
}

function activeComponent() {
	return componentTypes.find((item) => item.key === activeComponentKey);
}

async function loadParts() {
	const component = activeComponent();
	if (!component) return;

	partPickerResults.innerHTML = `
		<div class="part-picker-empty">
			<div class="spinner-border spinner-border-sm mb-3" role="status"></div>
			<strong>Đang tải sản phẩm...</strong>
		</div>
	`;

	try {
		const payload = await TNC.api.products({
			category: component.category,
			q: partSearch.value.trim(),
			brand: partBrandFilter.value,
			limit: 24,
		});
		renderParts(component, payload.data || []);
	} catch (error) {
		partPickerResults.innerHTML = `
			<div class="part-picker-empty">
				<i class="bi bi-database-exclamation"></i>
				<strong>Không tải được sản phẩm</strong>
				<p>${TNC.escapeHtml(error?.message || "")}</p>
			</div>
		`;
	}
}

function renderParts(component, items) {
	if (!items.length) {
		partPickerResults.innerHTML = `
			<div class="part-picker-empty">
				<i class="bi bi-search"></i>
				<strong>Không có linh kiện phù hợp</strong>
				<p>Chưa có sản phẩm nào cho <b>${TNC.escapeHtml(component.label.toLowerCase())}</b> khớp bộ lọc hiện tại.</p>
			</div>
		`;
		return;
	}

	partPickerResults.innerHTML = items
		.map(
			(product) => `
				<button class="part-picker-item" type="button" data-part-slug="${TNC.escapeHtml(product.slug)}">
					<img src="${TNC.escapeHtml(product.image)}" alt="${TNC.escapeHtml(product.name)}" loading="lazy" />
					<span class="part-picker-info">
						<strong>${TNC.escapeHtml(product.name)}</strong>
						<small>${TNC.escapeHtml(
							[product.brand, product.socket ? `Socket ${product.socket}` : null]
								.filter(Boolean)
								.join(" · "),
						)}</small>
					</span>
					<span class="part-picker-price">${TNC.escapeHtml(product.priceText)}</span>
				</button>
			`,
		)
		.join("");

	partPickerResults.querySelectorAll("[data-part-slug]").forEach((button) => {
		button.addEventListener("click", () => {
			const product = items.find((item) => item.slug === button.dataset.partSlug);
			if (product) choosePart(product);
		});
	});
}

function choosePart(product) {
	selections[activeComponentKey] = {
		slug: product.slug,
		name: product.name,
		brand: product.brand,
		socket: product.socket,
		price: product.price,
		priceText: product.priceText,
	};
	localStorage.setItem("tnc-buildpc", JSON.stringify(selections));
	bootstrap.Modal.getOrCreateInstance(partPickerModal).hide();
	renderComponents();
	renderSummary();
}

async function loadBrandOptions() {
	try {
		const meta = await TNC.api.meta();
		partBrandFilter.innerHTML =
			'<option value="">Tất cả thương hiệu</option>' +
			(meta.brands || [])
				.map(
					(brand) =>
						`<option value="${TNC.escapeHtml(brand.slug)}">${TNC.escapeHtml(brand.name)}</option>`,
				)
				.join("");
	} catch {
		partBrandFilter.innerHTML = '<option value="">Tất cả thương hiệu</option>';
	}
}

let partSearchTimer = null;
partSearch.addEventListener("input", () => {
	window.clearTimeout(partSearchTimer);
	partSearchTimer = window.setTimeout(loadParts, 250);
});
partBrandFilter.addEventListener("change", loadParts);
document.addEventListener("DOMContentLoaded", loadBrandOptions);
document.querySelector("#reset-build").addEventListener("click", () => {
	Object.keys(selections).forEach((key) => delete selections[key]);
	localStorage.removeItem("tnc-buildpc");
	renderComponents();
	renderSummary();
});
document.querySelector("#consult-build").addEventListener("click", () => {
	bootstrap.Modal.getOrCreateInstance(document.querySelector("#consultModal")).show();
});
document.querySelector("#consult-form").addEventListener("submit", (event) => {
	event.preventDefault();
	event.currentTarget.hidden = true;
	document.querySelector("#consult-success").hidden = false;
});

renderComponents();
renderSummary();

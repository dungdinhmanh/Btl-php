const componentTypes = [
	{
		key: "cpu",
		label: "Bộ vi xử lý",
		icon: "bi-cpu",
	},
	{
		key: "motherboard",
		label: "Bo mạch chủ",
		icon: "bi-motherboard",
	},
	{
		key: "gpu",
		label: "Card đồ họa",
		icon: "bi-gpu-card",
	},
	{
		key: "ram",
		label: "RAM",
		icon: "bi-memory",
	},
	{
		key: "storage",
		label: "Ổ lưu trữ",
		icon: "bi-device-ssd",
	},
	{
		key: "psu",
		label: "Nguồn máy tính",
		icon: "bi-lightning-charge",
	},
	{
		key: "case",
		label: "Vỏ máy",
		icon: "bi-pc",
	},
	{
		key: "cooler",
		label: "Tản nhiệt CPU",
		icon: "bi-fan",
	},
];

const selections = JSON.parse(localStorage.getItem("tnc-buildpc") || "{}");
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
						<span><strong>${selections[component.key] || "Thêm " + component.label.toLowerCase()}</strong><small>${selections[component.key] ? "Nhấn để thay đổi linh kiện" : "Mở kho sản phẩm để chọn"}</small></span>
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
	const selectedComponents = componentTypes.filter((component) => selections[component.key]);
	selectedCount.textContent = `${selectedComponents.length}/8`;
	summaryTitle.textContent =
		selectedComponents.length === 8 ? "Sẵn sàng để kiểm tra" : "Chưa hoàn thiện";
	summaryList.innerHTML = selectedComponents.length
		? selectedComponents
				.map(
					(component) =>
						`<div><span>${component.label}</span><strong>Đã chọn</strong></div>`,
				)
				.join("")
		: '<p class="summary-empty">Các linh kiện bạn chọn sẽ xuất hiện ở đây.</p>';
}

function openPartPicker(componentKey) {
	activeComponentKey = componentKey;
	const component = componentTypes.find((item) => item.key === componentKey);
	partPickerTitle.textContent = `Chọn ${component.label.toLowerCase()}`;
	partSearch.value = "";
	partBrandFilter.value = "";
	renderEmptyProducts();
	bootstrap.Modal.getOrCreateInstance(partPickerModal).show();
}

function renderEmptyProducts() {
	partPickerResults.innerHTML = `
		<div class="part-picker-empty">
			<i class="bi bi-database-exclamation"></i>
			<strong>Chưa có dữ liệu sản phẩm</strong>
			<p>Search và filter đã sẵn sàng. Kết quả database cho <b>${partPickerTitle.textContent}</b> sẽ được render ở đây.</p>
			<span class="part-picker-api-note">API placeholder · category: ${activeComponentKey}</span>
		</div>
	`;
}

partSearch.addEventListener("input", renderEmptyProducts);
partBrandFilter.addEventListener("change", renderEmptyProducts);
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

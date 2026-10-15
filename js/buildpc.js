/**
 * Build PC — dựng lại luồng của tncstore.vn/buildpc.
 *
 * Bám theo script gốc (BuildPC_V2 + BuildPCVisualNew):
 * - 5 tab "Cấu hình 1..5", mỗi tab một bộ cấu hình lưu trong localStorage.
 * - Bấm dấu "+" để mở modal chọn linh kiện (mask-popup > popup-select).
 * - Thanh tác vụ chỉ phản hồi khi bấm vào <span class="hover-txt">:
 *   lưu -> alert("Lưu thành công !"); làm mới -> popup xác nhận;
 *   cấu hình rỗng thì bấm không có tác dụng (giống gốc).
 * - "Tiến hành thanh toán": thêm linh kiện vào giỏ hàng rồi tải lại trang
 *   (giống addConfigToCart_Hura8 của gốc).
 *
 * Dữ liệu sản phẩm lấy từ backend/api/products.php.
 */
(() => {
	"use strict";

	const SLOT_COUNT = 5;
	const STORAGE_PREFIX = "tnc-buildpc-";
	const PICKER_PAGE_SIZE = 12;
	const MAX_QUANTITY = 99;
	const PRODUCT_FALLBACK_IMAGE = "assets/img/branding/tnc.png";

	/** 17 dòng linh kiện theo đúng thứ tự / tên / id của TNC. */
	const componentTypes = [
		{ key: "cpu", id: 62, label: "Bộ vi xử lý", category: "cpu" },
		{ key: "motherboard", id: 61, label: "Bo mạch chủ", category: "mainboard" },
		{ key: "ram", id: 63, label: "RAM", category: "ram" },
		{ key: "ssd", id: 69, label: "Ổ Cứng SSD", category: "ssd" },
		{ key: "hdd", id: 64, label: "Ổ Cứng HDD", category: "hdd" },
		{ key: "gpu", id: 65, label: "VGA", category: "vga" },
		{ key: "psu", id: 67, label: "Nguồn", category: "psu" },
		{ key: "case", id: 66, label: "Vỏ Case", category: "case" },
		{ key: "fan", id: 109, label: "Quạt Tản Nhiệt", category: null },
		{ key: "cpu-cooler", id: 105, label: "Tản Nhiệt CPU", category: null },
		{ key: "aio-cooler", id: 107, label: "Tản Nhiệt Nước AIO", category: null },
		{ key: "monitor", id: 68, label: "Màn Hình", category: null },
		{ key: "keyboard", id: 110, label: "Bàn phím", category: null },
		{ key: "mouse", id: 84, label: "Chuột Gaming", category: null },
		{ key: "headphone", id: 91, label: "Tai Nghe", category: null },
		{ key: "desk", id: 179, label: "Bàn Gaming", category: null },
		{ key: "chair", id: 92, label: "Ghế Gaming", category: null },
	];

	/** Khoảng giá trong modal (nhãn theo cách viết của gốc). */
	const priceFilters = [
		{ key: "duoi-2-trieu", label: "Dưới 2 triệu", min: 0, max: 2000000 },
		{ key: "2-5-trieu", label: "2 triệu - 5 triệu", min: 2000000, max: 5000000 },
		{ key: "5-10-trieu", label: "5 triệu - 10 triệu", min: 5000000, max: 10000000 },
		{ key: "tren-10-trieu", label: "Trên 10 triệu", min: 10000000, max: Number.POSITIVE_INFINITY },
	];

	const state = {
		slot: 1,
		selections: {},
		picker: null,
		actionsBound: false,
	};

	const els = {};

	/* ---------------------------------------------------------------------
	   Tiện ích
	   --------------------------------------------------------------------- */
	const escapeHtml = (value) =>
		String(value ?? "").replace(
			/[&<>'"]/g,
			(character) =>
				({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[character],
		);

	const formatNumber = (value) => (Number(value) || 0).toLocaleString("vi-VN");

	const formatPrice = (value) => `${formatNumber(value)}đ`;

	const componentByKey = (key) => componentTypes.find((component) => component.key === key);

	const componentById = (id) => componentTypes.find((component) => component.id === Number(id));

	const productUrl = (item) => `product-detail.php?slug=${encodeURIComponent(item.slug || "")}`;

	const storageKey = (slot) => `${STORAGE_PREFIX}${slot}`;

	/* ---------------------------------------------------------------------
	   Lưu / đọc cấu hình theo tab
	   --------------------------------------------------------------------- */
	function loadSlot(slot) {
		try {
			const saved = JSON.parse(window.localStorage.getItem(storageKey(slot)) || "{}") || {};
			const selections = {};
			componentTypes.forEach((component) => {
				const item = saved[component.key];
				if (item && typeof item === "object") {
					selections[component.key] = item;
				}
			});
			return selections;
		} catch (error) {
			return {};
		}
	}

	function persistSlot() {
		window.localStorage.setItem(storageKey(state.slot), JSON.stringify(state.selections));
	}

	const chosenComponents = () => componentTypes.filter((component) => state.selections[component.key]);

	const configTotal = () =>
		chosenComponents().reduce((sum, component) => {
			const item = state.selections[component.key];
			return sum + (Number(item.price) || 0) * (Number(item.quantity) || 1);
		}, 0);

	/* ---------------------------------------------------------------------
	   Dựng bảng linh kiện
	   --------------------------------------------------------------------- */
	function renderLayout() {
		els.layout.innerHTML = componentTypes.map(rowTemplate).join("");
	}

	function rowTemplate(component) {
		const item = state.selections[component.key];

		return `<div class="item-drive">
				<span class="name">${escapeHtml(component.label)}</span>
				<div class="drive-checked${item ? " active" : ""}" id="checked-${component.id}">
					<span class="show-popup_select span-last open-selection box-add" id="js-category-info-${component.id}" data-info='${JSON.stringify({ id: component.id, name: component.label })}'>
						<i class="bi bi-plus" aria-hidden="true"></i>
					</span>
					<div class="show-selected js-item-row" id="js-selected-item-${component.id}" data-id="${component.id}">
						${item ? itemRowTemplate(component, item) : ""}
					</div>
				</div>
			</div>`;
	}

	function itemRowTemplate(component, item) {
		const url = productUrl(item);
		const name = escapeHtml(item.name);
		const quantity = Math.max(1, Number(item.quantity) || 1);
		const price = Number(item.price) || 0;

		return `<div class="contain-item-drive" data-category_id="${component.id}" data-product_id="${escapeHtml(item.id)}" data-variant_id="" data-current_stock="20">
				<a target="_blank" href="${url}" class="d-img">
					<img src="${escapeHtml(item.image || PRODUCT_FALLBACK_IMAGE)}" alt="${name}" />
				</a>
				<div class="d-info">
					<div class="info-name-edit-remove d-flex space-between">
						<a target="_blank" href="${url}" class="product-name">${name}</a>
						<div class="btn-edit d-flex" data-category_id="${component.id}" data-product_id="${escapeHtml(item.id)}">
							<span class="btn-action_seclect show-popup_select edit-item">Sửa</span>
							<span class="btn-action_seclect delete_select remove-item">Xóa</span>
						</div>
					</div>
					<div class="info-main-price d-flex align-items space-between">
						<div class="ground-price d-flex align-items">
							<div class="unit-detail-amount-control d-flex">
								<input type="number" size="3" data-total="1" value="${quantity}" id="js-buy-quantity-${escapeHtml(item.id)}" class="count-p" />
								<div class="btn-click d-flex flex-direction">
									<a href="javascript:;" data-value="1" data-change="1" class="change-quantity"><i class="bi bi-plus"></i></a>
									<a href="javascript:;" data-value="-1" data-change="-1" class="change-quantity"><i class="bi bi-dash"></i></a>
								</div>
							</div>
							<span class="icon-time">x</span>
							<span class="d-price">${formatNumber(price)}</span>
						</div>
						<span class="sum_price">${formatPrice(price * quantity)}</span>
					</div>
				</div>
			</div>`;
	}

	function renderRowItem(component) {
		const item = state.selections[component.key];
		const holder = document.getElementById(`js-selected-item-${component.id}`);
		if (holder) {
			holder.innerHTML = item ? itemRowTemplate(component, item) : "";
		}
		document.getElementById(`checked-${component.id}`)?.classList.toggle("active", Boolean(item));
	}

	/* ---------------------------------------------------------------------
	   Tổng chi phí (giống displaySelectedConfigSummary + addActiveClassToDriveChecked)
	   --------------------------------------------------------------------- */
	function updateSummary() {
		const total = configTotal();
		document.querySelectorAll(".js-config-summary").forEach((element) => {
			element.textContent = `${formatNumber(total)} đ`;
		});

		if (chosenComponents().length > 0) {
			document.querySelectorAll(".add-buildpc.error").forEach((element) => {
				element.style.display = "none";
			});
			document.querySelector(".show-action-buy")?.classList.add("active");
		}
	}

	/* ---------------------------------------------------------------------
	   Tab cấu hình
	   --------------------------------------------------------------------- */
	function selectSlot(slot) {
		state.slot = slot;
		state.selections = loadSlot(slot);
		document.querySelectorAll(".list-btn-action .item").forEach((tab) => {
			tab.classList.toggle("active", Number(tab.dataset.slot) === slot);
		});
		renderLayout();
		updateSummary();
	}

	/* ---------------------------------------------------------------------
	   Số lượng
	   --------------------------------------------------------------------- */
	function changeQuantity(component, change) {
		const item = state.selections[component.key];
		if (!item) return;

		const input = document.getElementById(`js-buy-quantity-${item.id}`);
		if (!input) return;

		let quantity = parseInt(input.value, 10);
		if (Number.isNaN(quantity)) quantity = 1;
		quantity += change;
		if (quantity < 1) quantity = 1;
		if (quantity > MAX_QUANTITY) quantity = MAX_QUANTITY;
		input.value = quantity;
	}

	/** Gốc: click trong dòng sẽ chốt số lượng đang nhập rồi lưu (changeQNT). */
	function commitRowQuantity(row) {
		const component = componentById(row?.dataset.category_id);
		if (!component) return;

		const item = state.selections[component.key];
		const input = row.querySelector(".count-p");
		if (!item || !input) return;

		let quantity = parseInt(input.value, 10);
		if (Number.isNaN(quantity) || quantity < 1) quantity = 1;
		if (quantity > MAX_QUANTITY) quantity = MAX_QUANTITY;
		input.value = quantity;
		item.quantity = quantity;

		const sumPrice = row.querySelector(".sum_price");
		if (sumPrice) {
			sumPrice.textContent = formatPrice((Number(item.price) || 0) * quantity);
		}

		persistSlot();
		updateSummary();
	}

	/* ---------------------------------------------------------------------
	   Xóa linh kiện (giống removeProductToList của gốc)
	   --------------------------------------------------------------------- */
	function removeItem(component) {
		if (!window.confirm("Bạn muốn bỏ sản phẩm này ?")) return;

		document.getElementById(`checked-${component.id}`)?.classList.remove("active");
		const holder = document.getElementById(`js-selected-item-${component.id}`);
		if (holder) holder.innerHTML = "";

		delete state.selections[component.key];
		persistSlot();
		updateSummary();
	}

	/* ---------------------------------------------------------------------
	   Modal chọn linh kiện
	   --------------------------------------------------------------------- */
	function ensurePickerModal() {
		if (els.mask) return;

		els.modalHolder.innerHTML = `
			<div class="mask-popup">
				<div class="popup-select popup-animation">
					<div class="header d-flex align-items">
						<h4 id="name-select"></h4>
						<form action="" class="form-input-buildpc">
							<input type="text" value="" id="buildpc-search-keyword" class="input-search" placeholder="Nhập sản phẩm cần tìm..." />
							<span class="btn-search"><i class="bi bi-search" id="js-buildpc-search-btn"></i></span>
						</form>
						<a href="javascript:void(0)" class="close-popup"><i class="bi bi-x-lg"></i></a>
					</div>
					<div class="content-main d-flex">
						<div class="popup-main-filter">
							<div class="box-filter">
								<div class="item-filter">
									<div class="title-filter d-flex align-items space-between">
										<span>Hãng sản xuất</span>
										<i class="bi bi-chevron-down"></i>
									</div>
									<div class="content-filter" id="js-brand-filter"></div>
								</div>
								<div class="item-filter">
									<div class="title-filter d-flex align-items space-between">
										<span>Khoảng giá (VNĐ)</span>
										<i class="bi bi-chevron-down"></i>
									</div>
									<div class="content-filter" id="js-price-filter"></div>
								</div>
							</div>
						</div>
						<div class="popup-content-right">
							<div class="sort-paging d-flex align-items space-between">
								<div class="content-sort d-flex align-items">
									<span>Hiển thị theo:</span>
									<select id="js-sort-holder">
										<option value="">Tùy chọn</option>
										<option value="new">Mới nhất</option>
										<option value="price-asc">Giá tăng dần</option>
										<option value="price-desc">Giá giảm dần</option>
										<option value="view">Lượt xem</option>
										<option value="comment">Trao đổi</option>
										<option value="rating">Đánh giá</option>
										<option value="name">Tên A-&gt;Z</option>
									</select>
								</div>
								<div class="content-paging d-flex js-paging"></div>
							</div>
							<div class="scroll-list">
								<div class="list-product-select" id="js-holder-p_item"></div>
							</div>
						</div>
					</div>
				</div>
			</div>`;

		els.mask = els.modalHolder.querySelector(".mask-popup");
		els.modalTitle = els.mask.querySelector("#name-select");
		els.searchForm = els.mask.querySelector(".form-input-buildpc");
		els.searchInput = els.mask.querySelector("#buildpc-search-keyword");
		els.searchBtn = els.mask.querySelector("#js-buildpc-search-btn");
		els.brandFilter = els.mask.querySelector("#js-brand-filter");
		els.priceFilter = els.mask.querySelector("#js-price-filter");
		els.filterPanel = els.mask.querySelector(".box-filter");
		els.results = els.mask.querySelector("#js-holder-p_item");
		els.paging = els.mask.querySelector(".js-paging");
		els.sortSelect = els.mask.querySelector("#js-sort-holder");

		els.mask.querySelector(".close-popup").addEventListener("click", closePicker);

		els.searchForm.addEventListener("submit", (event) => {
			event.preventDefault();
			searchKeyword(els.searchInput.value);
		});

		els.searchInput.addEventListener("keypress", (event) => {
			if (event.which === 13) {
				event.preventDefault();
				searchKeyword(els.searchInput.value);
			}
		});

		els.searchBtn.addEventListener("click", () => searchKeyword(els.searchInput.value));

		els.sortSelect.addEventListener("change", () => {
			if (!state.picker) return;
			state.picker.sort = els.sortSelect.value;
			state.picker.page = 1;
			renderPicker();
		});

		els.filterPanel.addEventListener("click", (event) => {
			const target = event.target.closest(".item-attr");
			if (!target || !state.picker) return;

			const group = target.dataset.group;
			const value = target.dataset.value;
			// Gốc: mỗi nhóm chọn 1 giá trị, bấm lại chính nó để bỏ lọc
			state.picker[group] = state.picker[group] === value ? "" : value;
			state.picker.page = 1;
			renderPicker();
		});

		els.paging.addEventListener("click", (event) => {
			const link = event.target.closest(".item");
			if (!link || !state.picker) return;
			state.picker.page = Number(link.dataset.page) || 1;
			renderPicker();
		});

		els.results.addEventListener("click", (event) => {
			const button = event.target.closest(".js-select-product");
			if (!button || !state.picker) return;
			chooseProduct(button.dataset.id);
		});
	}

	function openPicker(component) {
		ensurePickerModal();

		const picker = {
			key: component.key,
			items: [],
			query: "",
			brand: "",
			price: "",
			socket: "",
			sort: "",
			page: 1,
			pageCount: 1,
			filtered: [],
		};
		state.picker = picker;

		els.modalTitle.textContent = component.label;
		els.searchInput.value = "";
		els.sortSelect.value = "";
		els.brandFilter.innerHTML = "";
		els.priceFilter.innerHTML = "";
		els.filterPanel.querySelectorAll("[data-attr-group]").forEach((element) => element.remove());
		els.results.innerHTML = "";
		els.paging.innerHTML = "";

		els.mask.classList.add("active");
		document.body.style.overflow = "hidden";

		loadPickerProducts(picker, component);
	}

	async function loadPickerProducts(picker, component) {
		// `TNC` là global lexical trong js/api.js (không nằm trên window)
		const api = typeof TNC !== "undefined" ? TNC.api : null;

		if (!component.category || !api?.products) {
			renderPicker();
			return;
		}

		try {
			const payload = await api.products({ category: component.category, limit: 100 });
			const items = Array.isArray(payload?.data) ? payload.data : [];
			picker.items = items.map((item) => ({
				id: item.id,
				slug: item.slug,
				name: item.name,
				brand: item.brand || "",
				price: Number(item.price) || 0,
				image: item.image || "",
				socket: item.socket || "",
				rating: Number(item.rating ?? item.rating_avg) || 0,
				reviewCount: Number(item.reviewCount ?? item.review_count) || 0,
			}));
		} catch (error) {
			console.error("Không tải được danh sách sản phẩm:", error);
			picker.items = [];
		}

		if (state.picker !== picker) return;
		renderPicker();
	}

	function closePicker() {
		state.picker = null;
		if (els.mask) els.mask.classList.remove("active");
		document.body.style.overflow = "auto";
	}

	function renderPicker() {
		const picker = state.picker;
		if (!picker) return;

		applyPickerFilters();
		renderPickerFilters();
		renderPickerResults();
		renderPickerPaging();
	}

	function applyPickerFilters() {
		const picker = state.picker;
		const query = picker.query.trim().toLowerCase();

		let list = picker.items.filter(
			(item) => !query || `${item.name} ${item.brand}`.toLowerCase().includes(query),
		);

		if (picker.brand) {
			list = list.filter((item) => item.brand === picker.brand);
		}

		if (picker.price) {
			const bucket = priceFilters.find((entry) => entry.key === picker.price);
			if (bucket) {
				list = list.filter((item) => item.price >= bucket.min && item.price < bucket.max);
			}
		}

		if (picker.socket) {
			list = list.filter((item) => item.socket === picker.socket);
		}

		picker.filtered = sortPickerItems(list, picker.sort);
		picker.pageCount = Math.max(1, Math.ceil(picker.filtered.length / PICKER_PAGE_SIZE));
		if (picker.page > picker.pageCount) picker.page = picker.pageCount;
	}

	function sortPickerItems(list, sort) {
		const items = [...list];

		if (sort === "price-asc") items.sort((a, b) => a.price - b.price);
		if (sort === "price-desc") items.sort((a, b) => b.price - a.price);
		if (sort === "rating") {
			items.sort((a, b) => b.rating - a.rating || b.reviewCount - a.reviewCount);
		}
		if (sort === "comment") items.sort((a, b) => b.reviewCount - a.reviewCount);
		if (sort === "name") items.sort((a, b) => a.name.localeCompare(b.name, "vi"));

		return items;
	}

	function filterItemTemplate({ group, value, label, count, checked }) {
		return `<div class="item-attr d-flex align-items" data-group="${group}" data-value="${escapeHtml(value)}">
				<div class="icon-check${checked ? " checkered" : ""}"></div>
				<a href="javascript:void(0)" class="filter-right d-flex align-items space-between">
					<span>${escapeHtml(label)}</span>
					<span class="total-attr">(${count})</span>
				</a>
			</div>`;
	}

	function renderPickerFilters() {
		const picker = state.picker;

		// Gốc: danh sách bộ lọc được dựng theo kết quả đang lọc
		const brands = new Map();
		picker.filtered.forEach((item) => {
			if (item.brand) brands.set(item.brand, (brands.get(item.brand) || 0) + 1);
		});
		els.brandFilter.innerHTML = [...brands.entries()]
			.sort((a, b) => a[0].localeCompare(b[0], "vi"))
			.map(([brand, count]) =>
				filterItemTemplate({
					group: "brand",
					value: brand,
					label: brand,
					count,
					checked: picker.brand === brand,
				}),
			)
			.join("");

		const prices = new Map(priceFilters.map((bucket) => [bucket.key, 0]));
		picker.filtered.forEach((item) => {
			const bucket = priceFilters.find((entry) => item.price >= entry.min && item.price < entry.max);
			if (bucket) prices.set(bucket.key, (prices.get(bucket.key) || 0) + 1);
		});
		els.priceFilter.innerHTML = priceFilters
			.map((bucket) =>
				filterItemTemplate({
					group: "price",
					value: bucket.key,
					label: bucket.label,
					count: prices.get(bucket.key) || 0,
					checked: picker.price === bucket.key,
				}),
			)
			.join("");

		// Gốc: nhóm thuộc tính nằm trực tiếp trong .box-filter, ngay sau nhóm giá
		els.filterPanel.querySelectorAll("[data-attr-group]").forEach((element) => element.remove());

		const sockets = new Map();
		picker.filtered.forEach((item) => {
			if (item.socket) sockets.set(item.socket, (sockets.get(item.socket) || 0) + 1);
		});

		if (sockets.size > 0) {
			// Gốc ghi "Hỗ trợ Socket" ở bo mạch chủ, "Socket" ở bộ vi xử lý
			const label = picker.key === "motherboard" ? "Hỗ trợ Socket" : "Socket";
			els.filterPanel.insertAdjacentHTML(
				"beforeend",
				`<div class="item-filter" data-attr-group="socket">
					<div class="title-filter d-flex align-items space-between">
						<span>${label}</span>
						<i class="bi bi-chevron-down"></i>
					</div>
					<div class="content-filter">
						${[...sockets.entries()]
							.sort((a, b) => a[0].localeCompare(b[0], "vi"))
							.map(([socket, count]) =>
								filterItemTemplate({
									group: "socket",
									value: socket,
									label: socket,
									count,
									checked: picker.socket === socket,
								}),
							)
							.join("")}
					</div>
				</div>`,
			);
		}
	}

	function productItemTemplate(item) {
		const url = productUrl(item);
		const name = escapeHtml(item.name);
		const stars = Math.max(0, Math.min(5, Math.round(item.rating || 0)));
		const priceLabel = item.price > 0 ? formatPrice(item.price) : "Liên hệ";

		return `<div class="pro-item d-flex">
				<a href="${url}" class="pro-image">
					<img src="${escapeHtml(item.image || PRODUCT_FALLBACK_IMAGE)}" alt="${name}" />
				</a>
				<div class="pro-info">
					<a href="${url}" class="pro-name">${name}</a>
					<div class="info-review d-flex align-items">
						<b>Đánh giá</b>
						<i class="icon-star star${stars}"></i>
					</div>
					<div class="pro-status">
						<b>Tình trạng</b>
						<span> Còn hàng </span>
					</div>
					<div class="price">${priceLabel}</div>
					<a href="javascript:void(0)" class="btn-buy js-select-product" data-id="${escapeHtml(item.id)}">Thêm vào cấu hình <i class="bi bi-plus" aria-hidden="true"></i></a>
				</div>
			</div>`;
	}

	function renderPickerResults() {
		const picker = state.picker;

		if (picker.items.length === 0) {
			els.results.innerHTML = '<p style="padding: 24px;">Đang cập nhật sản phẩm.....!</p>';
			return;
		}

		const start = (picker.page - 1) * PICKER_PAGE_SIZE;
		els.results.innerHTML = picker.filtered
			.slice(start, start + PICKER_PAGE_SIZE)
			.map(productItemTemplate)
			.join("");
		els.results.scrollTop = 0;
	}

	function renderPickerPaging() {
		const picker = state.picker;

		if (picker.pageCount <= 1) {
			els.paging.innerHTML = "";
			return;
		}

		let html = "";
		for (let page = 1; page <= picker.pageCount; page += 1) {
			html += `<a href="javascript:void(0);" class="item${page === picker.page ? " active" : ""}" data-page="${page}">${page}</a>`;
		}
		els.paging.innerHTML = html;
	}

	/** Gốc: từ khóa tìm kiếm phải từ 2 ký tự. */
	function searchKeyword(keyword) {
		if (!state.picker) return;
		if (String(keyword).length < 2) return;
		state.picker.query = String(keyword);
		state.picker.page = 1;
		renderPicker();
	}

	function chooseProduct(productId) {
		const picker = state.picker;
		const component = picker ? componentByKey(picker.key) : null;
		if (!picker || !component) return;

		const product = picker.items.find((item) => String(item.id) === String(productId));
		if (!product) return;

		state.selections[component.key] = { ...product, quantity: 1 };
		closePicker();
		renderRowItem(component);
		persistSlot();
		updateSummary();
	}

	/* ---------------------------------------------------------------------
	   Thanh tác vụ (giống _listener của gốc)
	   --------------------------------------------------------------------- */
	function handleActionBarClick(event) {
		const item = event.target.closest(".item");
		const isRebuild = item && !item.querySelector("span.hover-txt[data-action]");

		// Gốc: chỉ nhận khi bấm trực tiếp vào <span class="hover-txt">,
		// riêng "Làm mới cấu hình" gắn onclick trên cả thẻ <a>.
		if (event.target.nodeName !== "SPAN") {
			if (isRebuild) openRebuildPopup();
			return;
		}

		const action = event.target.dataset.action;
		if (!action) {
			openRebuildPopup();
			return;
		}

		runAction(action);
	}

	function runAction(action) {
		// Gốc: cấu hình rỗng thì bấm không có tác dụng (fancybox "#opps" không tồn tại)
		if (chosenComponents().length === 0) return;

		if (action === "save") {
			persistSlot();
			window.alert("Lưu thành công !");
			return;
		}

		if (action === "download-excel") {
			downloadExcel();
			return;
		}

		if (action === "create-image") {
			downloadImage();
			return;
		}

		if (action === "view") {
			openPrintView();
		}
	}

	function downloadExcel() {
		const rows = [
			["STT", "Nhóm linh kiện", "Tên sản phẩm", "Số lượng", "Đơn giá", "Thành tiền"].join(";"),
		];

		chosenComponents().forEach((component, index) => {
			const item = state.selections[component.key];
			const quantity = Number(item.quantity) || 1;
			const price = Number(item.price) || 0;
			rows.push([index + 1, component.label, item.name, quantity, price, price * quantity].join(";"));
		});

		rows.push(["", "", "", "", "Tổng cộng", configTotal()].join(";"));

		const blob = new Blob(["\uFEFF" + rows.join("\r\n")], { type: "text/csv;charset=utf-8;" });
		const url = URL.createObjectURL(blob);
		const link = document.createElement("a");
		link.href = url;
		link.download = `cau-hinh-pc-${state.slot}.csv`;
		link.click();
		URL.revokeObjectURL(url);
	}

	function truncateCanvasText(context, text, maxWidth) {
		if (context.measureText(text).width <= maxWidth) return text;

		let output = text;
		while (output.length > 1 && context.measureText(`${output}…`).width > maxWidth) {
			output = output.slice(0, -1);
		}

		return `${output}…`;
	}

	/** "Tải ảnh cấu hình" — gốc dùng dịch vụ chụp ảnh ngoài, ở đây vẽ PNG bằng canvas. */
	function downloadImage() {
		const chosen = chosenComponents();
		const width = 1000;
		const padding = 40;
		const rowHeight = 46;
		const headerHeight = 130;
		const height = headerHeight + chosen.length * rowHeight + 90;
		const scale = 2;

		const canvas = document.createElement("canvas");
		canvas.width = width * scale;
		canvas.height = height * scale;

		const context = canvas.getContext("2d");
		context.scale(scale, scale);
		context.fillStyle = "#ffffff";
		context.fillRect(0, 0, width, height);

		context.fillStyle = "#29324e";
		context.font = "bold 26px 'Chakra Petch', sans-serif";
		context.fillText("CẤU HÌNH PC - TNC STORE", padding, 60);

		context.fillStyle = "#8d94ac";
		context.font = "14px 'Chakra Petch', sans-serif";
		context.fillText(
			`Cấu hình ${state.slot} — Ngày ${new Date().toLocaleDateString("vi-VN")}`,
			padding,
			88,
		);

		context.fillStyle = "#29324e";
		context.font = "bold 15px 'Chakra Petch', sans-serif";
		context.fillText("Linh kiện", padding, 122);
		context.textAlign = "right";
		context.fillText("Thành tiền", width - padding, 122);
		context.textAlign = "left";

		let y = headerHeight + 24;
		chosen.forEach((component) => {
			const item = state.selections[component.key];
			const quantity = Number(item.quantity) || 1;
			const price = Number(item.price) || 0;

			context.fillStyle = "#8d94ac";
			context.font = "12px 'Chakra Petch', sans-serif";
			context.fillText(component.label.toUpperCase(), padding, y - 14);

			context.fillStyle = "#29324e";
			context.font = "600 15px 'Chakra Petch', sans-serif";
			const label = truncateCanvasText(context, item.name, width - padding * 2 - 160);
			context.fillText(quantity > 1 ? `${label} x${quantity}` : label, padding, y + 6);

			context.fillStyle = "#fb4e4e";
			context.font = "bold 15px 'Chakra Petch', sans-serif";
			context.textAlign = "right";
			context.fillText(formatPrice(price * quantity), width - padding, y + 6);
			context.textAlign = "left";

			y += rowHeight;
		});

		context.strokeStyle = "#ededed";
		context.beginPath();
		context.moveTo(padding, y);
		context.lineTo(width - padding, y);
		context.stroke();

		context.fillStyle = "#29324e";
		context.font = "bold 20px 'Chakra Petch', sans-serif";
		context.fillText("Tổng chi phí", padding, y + 40);

		context.fillStyle = "#fb4e4e";
		context.textAlign = "right";
		context.fillText(`${formatNumber(configTotal())} đ`, width - padding, y + 40);
		context.textAlign = "left";

		canvas.toBlob((blob) => {
			if (!blob) return;
			const url = URL.createObjectURL(blob);
			const link = document.createElement("a");
			link.href = url;
			link.download = `cau-hinh-pc-${state.slot}.png`;
			link.click();
			URL.revokeObjectURL(url);
		}, "image/png");
	}

	/** "In báo giá" — gốc mở trang export_file.php, ở đây mở cửa sổ in riêng. */
	function openPrintView() {
		const rows = chosenComponents()
			.map((component, index) => {
				const item = state.selections[component.key];
				const quantity = Number(item.quantity) || 1;
				const price = Number(item.price) || 0;

				return `<tr>
						<td>${index + 1}</td>
						<td>${escapeHtml(component.label)}</td>
						<td>${escapeHtml(item.name)}</td>
						<td class="num">${quantity}</td>
						<td class="num">${formatNumber(price)}</td>
						<td class="num">${formatNumber(price * quantity)}</td>
					</tr>`;
			})
			.join("");

		const html = `<!doctype html>
<html lang="vi">
<head>
	<meta charset="utf-8" />
	<title>Báo giá cấu hình PC</title>
	<style>
		body { font-family: "Chakra Petch", Arial, sans-serif; color: #29324e; padding: 24px; }
		h1 { font-size: 20px; margin: 0 0 4px; }
		p { margin: 0 0 4px; font-size: 13px; }
		table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 14px; }
		th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
		th { background: #f5f5f5; }
		.num { text-align: right; white-space: nowrap; }
		tfoot td { font-weight: 700; }
	</style>
</head>
<body>
	<h1>Công ty TNHH Thương mại &amp; Tin học Tú Nguyệt (TNC Store)</h1>
	<p>Showroom: 172 Lê Thanh Nghị, Phường Bạch Mai, Hà Nội — Hotline: 086 830 2123</p>
	<p>Báo giá cấu hình PC — Cấu hình ${state.slot} — Ngày ${new Date().toLocaleDateString("vi-VN")}</p>
	<table>
		<thead>
			<tr><th>STT</th><th>Nhóm linh kiện</th><th>Tên sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>
		</thead>
		<tbody>${rows}</tbody>
		<tfoot>
			<tr><td colspan="5">Tổng cộng</td><td class="num">${formatNumber(configTotal())} đ</td></tr>
		</tfoot>
	</table>
</body>
</html>`;

		const printWindow = window.open("", "_blank");
		if (!printWindow) return;
		printWindow.document.write(html);
		printWindow.document.close();
		printWindow.focus();
		printWindow.print();
	}

	/* ---------------------------------------------------------------------
	   Popup "Làm mới cấu hình" (giống openPopupRebuild + reBuild của gốc)
	   --------------------------------------------------------------------- */
	function openRebuildPopup() {
		els.rebuildMask?.classList.add("active");
	}

	function closeRebuildPopup() {
		els.rebuildMask?.classList.remove("active");
	}

	function confirmRebuild() {
		window.localStorage.removeItem(storageKey(state.slot));
		window.alert("Lưu thành công !");
		window.location.href = "buildpc.php";
	}

	/* ---------------------------------------------------------------------
	   Thêm cấu hình vào giỏ hàng (giống addConfigToCart_Hura8)
	   --------------------------------------------------------------------- */
	function handleAddToCart() {
		persistSlot();

		if (typeof getCart === "function" && typeof saveCart === "function") {
			const cart = getCart();

			chosenComponents().forEach((component) => {
				const item = state.selections[component.key];
				const quantity = Number(item.quantity) || 1;
				const id = String(item.slug || item.id);
				const existing = cart.find((entry) => String(entry.id) === id);

				if (existing) {
					existing.quantity += quantity;
				} else {
					cart.push({
						productId: Number(item.id) || 0,
						id,
						name: item.name,
						brand: item.brand || "TNC STORE",
						price: Number(item.price) || 0,
						image: item.image || "",
						icon: "bi bi-box",
						quantity,
					});
				}
			});

			saveCart(cart);
			if (typeof updateCartCount === "function") updateCartCount();
		}

		// Gốc: hiển thị thông báo (không có trong DOM) rồi tải lại trang sau 1s
		window.setTimeout(() => window.location.reload(), 1000);
	}

	/* ---------------------------------------------------------------------
	   Khởi tạo
	   --------------------------------------------------------------------- */
	function init() {
		els.layout = document.getElementById("js-buildpc-layout");
		els.actionBar = document.getElementById("js-buildpc-action");
		els.modalHolder = document.getElementById("js-modal-popup");
		els.rebuildMask = document.getElementById("mask-rebuild");

		if (!els.layout || !els.actionBar || !els.modalHolder) return;

		document.querySelectorAll(".list-btn-action .item").forEach((tab) => {
			tab.addEventListener("click", () => selectSlot(Number(tab.dataset.slot) || 1));
		});

		if (!state.actionsBound) {
			state.actionsBound = true;

			els.layout.addEventListener("click", (event) => {
				const openEl = event.target.closest(".open-selection");
				if (openEl) {
					const component = componentById(openEl.id.replace("js-category-info-", ""));
					if (component) openPicker(component);
					return;
				}

				const editEl = event.target.closest(".edit-item");
				if (editEl) {
					const component = componentById(editEl.closest(".btn-edit")?.dataset.category_id);
					if (component) openPicker(component);
					return;
				}

				const removeEl = event.target.closest(".remove-item");
				if (removeEl) {
					const component = componentById(removeEl.closest(".btn-edit")?.dataset.category_id);
					if (component) removeItem(component);
					return;
				}

				const changeEl = event.target.closest("[data-change]");
				if (changeEl) {
					const component = componentById(changeEl.closest(".contain-item-drive")?.dataset.category_id);
					if (component) changeQuantity(component, Number(changeEl.dataset.change));
				}

				const row = event.target.closest(".contain-item-drive");
				if (row) commitRowQuantity(row);
			});

			els.actionBar.addEventListener("click", handleActionBarClick);

			document.getElementById("rebuild-cancel")?.addEventListener("click", closeRebuildPopup);
			document.getElementById("rebuild-confirm")?.addEventListener("click", confirmRebuild);
			document.getElementById("js-add-to-cart")?.addEventListener("click", handleAddToCart);
		}

		// Gốc: showBuildId(1) ngay khi tải trang
		selectSlot(Math.min(Math.max(state.slot || 1, 1), SLOT_COUNT));
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})();

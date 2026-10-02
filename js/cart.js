const cartStorageKey = "tnc-cart";

function getCart() {
	try {
		return JSON.parse(localStorage.getItem(cartStorageKey)) || [];
	} catch {
		return [];
	}
}

function saveCart(cart) {
	localStorage.setItem(cartStorageKey, JSON.stringify(cart));
}

function formatPrice(value) {
	return `${value.toLocaleString("vi-VN")}đ`;
}

const escapeAttr = (value) =>
	String(value ?? "").replace(
		/[&<>'"]/g,
		(character) =>
			({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[character],
	);

/** Product thumbnail: the real photo when known, otherwise the legacy icon. */
function cartThumb(item) {
	if (item.image) {
		return `<div class="cart-product-image has-image">
			<img src="${escapeAttr(item.image)}" alt="${escapeAttr(item.name)}" loading="lazy" />
		</div>`;
	}

	return `<div class="cart-product-image"><i class="${escapeAttr(item.icon || "bi bi-box")}"></i></div>`;
}

function getProductFromCard(card) {
	const priceText = card.querySelector(".product-price")?.textContent || "0";

	return {
		id:
			card.querySelector("h3")?.textContent.trim().toLowerCase().replace(/\s+/g, "-") ||
			Date.now().toString(),
		name: card.querySelector("h3")?.textContent.trim() || "Sản phẩm",
		brand: card.querySelector(".product-brand")?.textContent.trim() || "TNC STORE",
		price: Number(priceText.replace(/[^\d]/g, "")),
		image: card.querySelector(".product-image img")?.getAttribute("src") || "",
		icon: card.querySelector(".product-image i")?.className || "bi bi-box",
		quantity: 1,
	};
}

function updateCartCount() {
	const count = getCart().reduce((total, item) => total + item.quantity, 0);
	document.querySelectorAll(".cart-counter").forEach((counter) => {
		counter.textContent = count;
	});
	renderCartHover();
}

function renderCartHover() {
	const holder = document.querySelector("[data-cart-hover]");
	if (!holder) return;

	const cart = getCart();

	if (cart.length === 0) {
		holder.innerHTML =
			holder.innerHTML =
				'<div class="cart-items-holder"><b class="d-block text-center p-4">Có 0 sản phẩm trong giỏ hàng</b></div>';
		return;
	}

	const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
	holder.innerHTML = `<div class="cart-items-holder">${
		cart
			.map(
				(item) => `
					<div class="cart-item js-item-row" data-cart-id="${escapeAttr(item.id)}">
						<div class="cart-img">${cartThumb(item)}</div>
						<div class="cart-mid">
							<p class="cart-meta">${escapeAttr(item.brand)}</p>
							<a href="cart.php" class="cart-name">${escapeAttr(item.name)}</a>
							<div class="main-price"><b class="price">${formatPrice(item.price * item.quantity)}</b></div>
						</div>
						<div class="cart-right">
							<div class="quantity-control">
								<button type="button" data-cart-action="decrease" aria-label="Giảm số lượng">−</button>
								<span>${item.quantity}</span>
								<button type="button" data-cart-action="increase" aria-label="Tăng số lượng">+</button>
							</div>
							<button type="button" class="remove-item" data-cart-action="remove">Xóa</button>
						</div>
					</div>
				`,
			)
			.join("")
	}</div><div class="cart-price-hover"><div class="cart-total-row"><p>Tổng chi phí</p><b>${formatPrice(total)}</b></div>
	<div class="cart-hover-actions"><a href="cart.php" class="btn-goCart">Xem giỏ hàng</a><a href="checkout.php" class="btn-goCart cart-2">Mua hàng</a></div></div>`;
}

function addToCart(product) {
	const cart = getCart();
	const existingProduct = cart.find((item) => item.id === product.id);

	if (existingProduct) {
		existingProduct.quantity += 1;
	} else {
		cart.push(product);
	}

	saveCart(cart);
	updateCartCount();
}

function renderCartPage() {
	const cartList = document.querySelector("[data-cart-list]");
	if (!cartList) return;

	const cart = getCart();
	const emptyMessage = document.querySelector("[data-cart-empty]");
	const cartContent = document.querySelector("[data-cart-content]");

	if (cart.length === 0) {
		cartList.innerHTML = "";
		emptyMessage?.classList.remove("d-none");
		cartContent?.classList.add("d-none");
		updateCartSummary(0);
		return;
	}

	emptyMessage?.classList.add("d-none");
	cartContent?.classList.remove("d-none");
	cartList.innerHTML = cart
		.map(
			(item) => `
				<div class="cart-item" data-cart-id="${escapeAttr(item.id)}">
					${cartThumb(item)}
					<div class="flex-grow-1">
						<p class="product-brand">${escapeAttr(item.brand)}</p>
						<p class="cart-name">${escapeAttr(item.name)}</p>
						<p class="cart-meta">Sản phẩm chính hãng</p>
					</div>
					<div class="quantity-control">
						<button type="button" data-cart-action="decrease" aria-label="Giảm số lượng">−</button>
						<span>${item.quantity}</span>
						<button type="button" data-cart-action="increase" aria-label="Tăng số lượng">+</button>
					</div>
					<button type="button" class="remove-item" data-cart-action="remove" aria-label="Xóa sản phẩm">
						<i class="bi bi-x-lg"></i>
					</button>
					<strong>${formatPrice(item.price * item.quantity)}</strong>
				</div>
			`,
		)
		.join("");

	updateCartSummary(cart.reduce((total, item) => total + item.price * item.quantity, 0));
}

function updateCartSummary(total) {
	document.querySelectorAll("[data-cart-subtotal], [data-cart-total]").forEach((element) => {
		element.textContent = formatPrice(total);
	});
}

function renderCheckoutPage() {
	const itemsContainer = document.querySelector("[data-checkout-items]");
	if (!itemsContainer) return;

	const cart = getCart();
	const submitButton = document.querySelector("[data-checkout-submit]");
	const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

	if (cart.length === 0) {
		itemsContainer.innerHTML = '<p class="cart-meta">Giỏ hàng đang trống.</p>';
		if (submitButton) submitButton.disabled = true;
	} else {
		itemsContainer.innerHTML = cart
			.map(
				(item) => `
					<div class="mini-product">
						<span>
							${
								item.image
									? `<img class="mini-product-thumb" src="${escapeAttr(item.image)}" alt="${escapeAttr(item.name)}" loading="lazy" />`
									: `<i class="${escapeAttr(item.icon || "bi bi-box")}"></i>`
							}
							${item.name}
							<b>× ${item.quantity}</b>
						</span>
						<strong>${formatPrice(item.price * item.quantity)}</strong>
					</div>
				`,
			)
			.join("");
	}

	if (submitButton && cart.length > 0) submitButton.disabled = false;
	updateCartSummary(total);
}

document.addEventListener("click", (event) => {
	const addButton = event.target.closest(".product-card button, .idea-product button");
	if (addButton) {
		addToCart(getProductFromCard(addButton.closest(".product-card")));
		const originalText = addButton.innerHTML;
		addButton.innerHTML = '<i class="bi bi-check2 me-2"></i>Đã thêm';
		setTimeout(() => {
			addButton.innerHTML = originalText;
		}, 1200);
		return;
	}

	const actionButton = event.target.closest("[data-cart-action]");
	if (!actionButton) return;

	const cartItem = actionButton.closest("[data-cart-id]");
	const productId = cartItem?.dataset.cartId;
	const cart = getCart();
	const product = cart.find((item) => item.id === productId);
	if (!product) return;

	if (actionButton.dataset.cartAction === "increase") product.quantity += 1;
	if (actionButton.dataset.cartAction === "decrease") product.quantity -= 1;
	if (actionButton.dataset.cartAction === "remove" || product.quantity <= 0) {
		cart.splice(cart.indexOf(product), 1);
	}

	saveCart(cart);
	updateCartCount();
	renderCartPage();
	renderCheckoutPage();
});

document.addEventListener("DOMContentLoaded", () => {
	updateCartCount();
	renderCartPage();
	renderCheckoutPage();
});
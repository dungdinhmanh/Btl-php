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

function getProductFromCard(card) {
	const priceText = card.querySelector(".product-price")?.textContent || "0";
	const icon = card.querySelector(".product-image i")?.className || "bi bi-box";
	const imageClass = card.querySelector(".product-image")?.className || "product-image";

	return {
		id:
			card.querySelector("h3")?.textContent.trim().toLowerCase().replace(/\s+/g, "-") ||
			Date.now().toString(),
		name: card.querySelector("h3")?.textContent.trim() || "Sản phẩm",
		brand: card.querySelector(".product-brand")?.textContent.trim() || "TNC STORE",
		price: Number(priceText.replace(/[^\d]/g, "")),
		icon,
		imageClass,
		quantity: 1,
	};
}

function updateCartCount() {
	const count = getCart().reduce((total, item) => total + item.quantity, 0);
	document.querySelectorAll(".cart-icon b").forEach((counter) => {
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
			'<b class="d-block text-center p-4">Có 0 sản phẩm trong giỏ hàng</b>';
		return;
	}

	holder.innerHTML =
		cart
			.map(
				(item) => `
					<div class="cart-item">
						<div class="cart-product-image ${item.imageClass.replace("product-image", "")}">
							<i class="${item.icon}"></i>
						</div>
						<div class="flex-grow-1">
							<p class="cart-meta">${item.brand}</p>
							<h2>${item.name}</h2>
							<p class="cart-meta">Số lượng: ${item.quantity}</p>
						</div>
						<strong>${formatPrice(item.price * item.quantity)}</strong>
					</div>
				`,
			)
			.join("") +
		'<a href="cart.php" class="btn-submit"><span class="txt">Xem giỏ hàng</span></a>';
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
				<div class="cart-item" data-cart-id="${item.id}">
					<div class="cart-product-image ${item.imageClass.replace("product-image", "")}">
						<i class="${item.icon}"></i>
					</div>
					<div class="flex-grow-1">
						<p class="product-brand">${item.brand}</p>
						<h2>${item.name}</h2>
						<p class="cart-meta">Sản phẩm chính hãng</p>
					</div>
					<div class="quantity-control">
						<button type="button" data-cart-action="decrease" aria-label="Giảm số lượng">−</button>
						<span>${item.quantity}</span>
						<button type="button" data-cart-action="increase" aria-label="Tăng số lượng">+</button>
					</div>
					<strong>${formatPrice(item.price * item.quantity)}</strong>
					<button type="button" class="remove-item" data-cart-action="remove" aria-label="Xóa sản phẩm">
						<i class="bi bi-x-lg"></i>
					</button>
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
							<i class="${item.icon}"></i>
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
});

document.addEventListener("DOMContentLoaded", () => {
	updateCartCount();
	renderCartPage();
	renderCheckoutPage();
});

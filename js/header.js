/** Shared three-tier header for all legacy storefront pages. */
(() => {
	const headerMarkup = `
		<header class="store-header">
			<div class="header-top"><div class="container header-top-inner">
				<a href="products.html" class="top-catalog-link"><i class="bi bi-grid-3x3-gap"></i>Tất cả sản phẩm</a>
				<div class="header-contact"><a href="tel:0868302123"><i class="bi bi-telephone"></i>0868 302 123</a><a href="mailto:cskh@tncstore.vn"><i class="bi bi-envelope"></i>cskh@tncstore.vn</a></div>
			</div></div>
			<div class="header-mid"><div class="container header-mid-inner">
				<a class="navbar-brand logo" href="index.html" aria-label="TNC Store home"><img src="assets/img/branding/tnc.png" alt="TNC Store"></a>
				<div class="dropdown category-dropdown"><button class="category-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-list"></i>Danh mục</button><ul class="dropdown-menu"><li><a class="dropdown-item" href="buildpc.html">Xây dựng cấu hình PC</a></li><li><a class="dropdown-item" href="products.html?category=components">PC &amp; linh kiện</a></li><li><a class="dropdown-item" href="products.html?category=monitor">Màn hình máy tính</a></li><li><a class="dropdown-item" href="products.html?category=gaming">Gaming gear</a></li></ul></div>
				<form class="search-form" role="search" action="products.html" method="get"><label class="visually-hidden" for="site-search">Tìm kiếm sản phẩm</label><input id="site-search" class="form-control" type="search" name="q" placeholder="Nhập sản phẩm cần tìm..." autocomplete="off"><button class="search-button" type="submit" aria-label="Tìm kiếm"><i class="bi bi-search"></i></button></form>
				<div class="header-actions"><a class="header-action" href="contact.html"><i class="bi bi-headset"></i><span><small>Hỗ trợ khách hàng</small>Liên hệ</span></a><a class="header-action" href="login.html"><i class="bi bi-person-circle"></i><span><small>Tài khoản</small>Đăng nhập</span></a><a class="header-action cart-action" href="cart.html"><span class="cart-icon"><i class="bi bi-cart3"></i><b>0</b></span><span><small>Giỏ hàng</small>Thanh toán</span></a></div>
			</div></div>
			<nav class="header-bottom" aria-label="Danh mục nổi bật"><div class="container header-bottom-inner"><a class="all-categories" href="products.html"><i class="bi bi-list"></i>Danh mục sản phẩm</a><div class="header-menu"><a href="buildpc.html">Build PC</a><a href="products.html?category=components">PC Gaming</a><a href="products.html?category=components">PC Đồ họa AI</a><a href="products.html?category=monitor">Màn hình</a><a href="products.html?category=components">Linh kiện</a><a href="products.html?category=gaming">Gaming gear</a><a href="news.html">Khuyến mãi</a></div></div></nav>
		</header>`;

	function mountHeader() {
		const existingHeader = document.querySelector(".store-header, .header, .site-header");
		if (!existingHeader || existingHeader.classList.contains("store-header")) return;
		existingHeader.outerHTML = headerMarkup;
		window.updateCartCount?.();
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", mountHeader, { once: true });
	} else {
		mountHeader();
	}
})();

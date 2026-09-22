/** Shared three-tier header for all legacy storefront pages. */
(() => {
	// Apache/PHP serves these pages as .php, while a static preview stays on .html.
	const extension = window.location.pathname.toLowerCase().endsWith(".php") ? "php" : "html";
	const page = (name) => `${name}.${extension}`;
	const category = (slug) => `${page("products")}?category=${slug}`;

	const headerMarkup = `
		<header class="store-header">
			<div class="header-top">
				<div class="container header-top-inner">
					<a href="${category("components")}" class="item">
						<i class="bi bi-grid-3x3-gap"></i>
						Tất cả sản phẩm
					</a>
					<a href="tel:0868302123" class="item">
						<i class="bi bi-telephone"></i>
						086 830 2123
					</a>
					<a href="mailto:cskh@tncstore.vn" class="item">
						<i class="bi bi-envelope"></i>
						cskh@tncstore.vn
					</a>
				</div>
			</div>
			<div class="header-mid">
				<div class="container header-mid-inner">
					<a class="navbar-brand logo" href="${page("index")}" aria-label="TNC Store home">
						<img src="assets/img/branding/tnc.png" alt="TNC Store" />
					</a>
					<div class="dropdown category-dropdown">
						<button
							class="category-toggle dropdown-toggle"
							type="button"
							data-bs-toggle="dropdown"
							aria-expanded="false"
						>
							<i class="bi bi-list"></i>
							Tất cả danh mục
						</button>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="${page("buildpc")}">Build PC theo yêu cầu</a></li>
							<li><a class="dropdown-item" href="${category("components")}">PC &amp; linh kiện</a></li>
							<li><a class="dropdown-item" href="${category("cpu")}">CPU - Bộ xử lý</a></li>
							<li>
								<a class="dropdown-item" href="${category("mainboard")}">
									Mainboard - Bo mạch chủ
								</a>
							</li>
							<li><a class="dropdown-item" href="${category("vga")}">VGA - Card màn hình</a></li>
						</ul>
					</div>
					<form class="search-form" role="search" action="${page("products")}" method="get">
						<label class="visually-hidden" for="site-search">Tìm kiếm sản phẩm</label>
						<input
							id="site-search"
							class="form-control"
							type="search"
							name="q"
							placeholder="Nhập tên sản phẩm, hãng cần tìm..."
							autocomplete="off"
						/>
						<button class="search-button" type="submit" aria-label="Tìm kiếm">
							<i class="bi bi-search"></i>
						</button>
					</form>
					<div class="header-actions">
						<a class="header-action" href="${page("contact")}">
							<i class="bi bi-headset"></i>
							<span><small>Hỗ trợ khách hàng</small>Liên hệ</span>
						</a>
						<a class="header-action" href="${page("login")}">
							<i class="bi bi-person-circle"></i>
							<span><small>Tài khoản</small>Đăng nhập</span>
						</a>
						<a class="header-action cart-action" href="${page("cart")}">
							<span class="cart-icon">
								<i class="bi bi-cart3"></i>
								<b>0</b>
							</span>
							<span><small>Giỏ hàng</small>Thanh toán</span>
						</a>
					</div>
				</div>
			</div>
			<nav class="header-bottom" aria-label="Danh mục sản phẩm">
				<div class="container header-bottom-inner">
					<a class="all-categories" href="${category("components")}">
						<i class="bi bi-list"></i>
						DANH MỤC SẢN PHẨM
					</a>
					<div class="header-menu">
						<a href="${page("buildpc")}">Build PC</a>
						<a href="${category("components")}">PC &amp; Linh kiện</a>
						<a href="${category("vga")}">VGA - Card màn hình</a>
						<a href="${category("cpu")}">CPU</a>
						<a href="${category("mainboard")}">Mainboard</a>
						<a href="${page("news")}">Khuyến mãi</a>
						<a href="${page("contact")}">Liên hệ</a>
					</div>
				</div>
			</nav>
		</header>`;

	const footerMarkup = `
		<footer class="site-footer">
			<div class="footer-main">
				<div class="container">
					<div class="row g-4">
						<div class="col-lg-4">
							<a class="logo footer-logo" href="${page("index")}">
								<img src="assets/img/branding/tnc.png" alt="TNC Store" />
							</a>
							<p class="footer-about">
								TNC Store - Hệ thống bán lẻ PC Gaming, linh kiện máy tính và thiết bị
								công nghệ chính hãng. Hàng CHẤT trên từng cảm nhận.
							</p>
							<ul class="footer-contact">
								<li>
									<i class="bi bi-geo-alt-fill"></i>
									172 Lê Thanh Nghị, Phường Bạch Mai, Hà Nội
								</li>
								<li>
									<i class="bi bi-telephone-fill"></i>
									<a href="tel:0868302123">086 830 2123</a>
								</li>
								<li>
									<i class="bi bi-envelope-fill"></i>
									<a href="mailto:cskh@tncstore.vn">cskh@tncstore.vn</a>
								</li>
							</ul>
							<div class="footer-social">
								<a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
								<a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
								<a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
								<a href="#" aria-label="Shopee"><i class="bi bi-bag-heart"></i></a>
							</div>
						</div>
						<div class="col-6 col-lg-2 footer-col">
							<h3>Danh mục sản phẩm</h3>
							<a href="${category("components")}">PC &amp; Linh kiện</a>
							<a href="${category("cpu")}">CPU - Bộ xử lý</a>
							<a href="${category("mainboard")}">Mainboard</a>
							<a href="${category("vga")}">VGA - Card màn hình</a>
							<a href="${page("buildpc")}">Build PC</a>
						</div>
						<div class="col-6 col-lg-2 footer-col">
							<h3>Thông tin chung</h3>
							<a href="${page("about")}">Giới thiệu</a>
							<a href="${page("news")}">Tin tức</a>
							<a href="${page("contact")}">Liên hệ hợp tác</a>
							<a href="${page("cart")}">Giỏ hàng</a>
							<a href="${page("checkout")}">Thanh toán</a>
						</div>
						<div class="col-6 col-lg-2 footer-col">
							<h3>Chính sách</h3>
							<a href="${page("about")}">Quy định chung</a>
							<a href="${page("about")}">Chính sách vận chuyển</a>
							<a href="${page("about")}">Chính sách bảo hành</a>
							<a href="${page("about")}">Chính sách đổi trả</a>
							<a href="${page("about")}">Chính sách doanh nghiệp</a>
						</div>
						<div class="col-6 col-lg-2 footer-col">
							<h3>Thông tin hữu ích</h3>
							<a href="${page("buildpc")}">Build PC là TNC</a>
							<a href="${page("news-post")}?post=pc-gaming">Hướng dẫn Build PC</a>
							<a href="${page("news-post")}?post=monitor">Cách chọn màn hình</a>
							<a href="${page("profile")}">Tra cứu đơn hàng</a>
							<a href="${page("contact")}">Trung tâm bảo hành</a>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-company">
				<div class="container footer-company-inner">
					<div class="footer-company-info">
						<strong>Công ty TNHH Thương mại &amp; Tin học Tú Nguyệt</strong>
						<span>Showroom: 172 Lê Thanh Nghị, Phường Bạch Mai, Hà Nội</span>
						<span>Trung tâm bảo hành: 172 Lê Thanh Nghị, Phường Bạch Mai, Hà Nội</span>
						<span>Trụ sở (không bán hàng): 11 Vũ Thạnh, Phường Ô Chợ Dừa, Hà Nội</span>
					</div>
					<div class="footer-newsletter">
						<strong>Đăng ký email để nhận tin khuyến mãi</strong>
						<form class="newsletter">
							<input
								type="email"
								placeholder="Email của bạn"
								aria-label="Email của bạn"
								required
							/>
							<button type="submit" aria-label="Đăng ký">
								<i class="bi bi-send"></i>
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="footer-bottom-bar">
				<div class="container footer-bottom-inner">
					<p class="footer-copy">
						© 2020 - Bản quyền của Công ty TNHH Thương mại &amp; Tin học Tú Nguyệt
					</p>
					<div class="footer-payments">
						<span>VISA</span>
						<span>Mastercard</span>
						<span>JCB</span>
						<span>Momo</span>
						<span>VNPAY</span>
						<span>COD</span>
					</div>
				</div>
			</div>
		</footer>`;

	function mountHeader() {
		if (document.querySelector(".store-header")) return;

		const legacyHeader = document.querySelector(".header, .site-header");
		if (legacyHeader) {
			// Upgrade the legacy one-row header in place so the layout does not shift.
			legacyHeader.outerHTML = headerMarkup;
		} else {
			// Pages without a header (for example the checkout flow) get one before <main>.
			const anchor = document.querySelector("main");
			if (anchor) {
				anchor.insertAdjacentHTML("beforebegin", headerMarkup);
			} else if (document.body) {
				document.body.insertAdjacentHTML("afterbegin", headerMarkup);
			}
		}

		window.updateCartCount?.();
	}

	function mountFooter() {
		const existingFooter = document.querySelector(".site-footer");
		if (existingFooter) {
			// Every page ships the same footer, so the legacy copy is replaced in place.
			existingFooter.outerHTML = footerMarkup;
			return;
		}

		// Pages without a footer (for example the profile page) get one after the content.
		const main = document.querySelector("main");
		if (main) {
			main.insertAdjacentHTML("afterend", footerMarkup);
		} else if (document.body) {
			document.body.insertAdjacentHTML("beforeend", footerMarkup);
		}
	}

	function mountChrome() {
		mountHeader();
		mountFooter();
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", mountChrome, { once: true });
	} else {
		mountChrome();
	}
})();

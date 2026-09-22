<?php
declare(strict_types=1);
?>
<div class="header">
	<div class="header-top">
		<div class="container right">
			<a href="tel:0868302123" class="item">
				<i class="bi bi-telephone"></i>
				<span>0868 302 123</span>
			</a>
			<a href="mailto:cskh@tncstore.vn" class="item">
				<i class="bi bi-envelope"></i>
				<span>cskh@tncstore.vn</span>
			</a>
		</div>
	</div>
	<div class="header-mid container-fluid">
		<nav class="navbar navbar-expand-lg py-3" aria-label="Main navigation">
			<a class="navbar-brand logo" href="index.html" aria-label="TNC Store home">
				<img src="assets/img/branding/tnc.png" alt="TNC Store" />
			</a>
			<button
				class="navbar-toggler"
				type="button"
				data-bs-toggle="collapse"
				data-bs-target="#storeNavigation"
				aria-controls="storeNavigation"
				aria-expanded="false"
				aria-label="Toggle navigation"
			>
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="storeNavigation">
				<div class="dropdown category-dropdown my-3 my-lg-0">
					<button
						class="category-toggle dropdown-toggle"
						type="button"
						data-bs-toggle="dropdown"
						aria-expanded="false"
					>
						<i class="bi bi-grid-3x3-gap me-2"></i>
						Danh mục
					</button>
					<ul class="dropdown-menu">
						<li>
							<a class="dropdown-item" href="buildpc.html">Build PC</a>
						</li>
						<li>
							<a class="dropdown-item" href="products.html?category=pc">
								PC & Linh kiện
							</a>
						</li>
						<li>
							<a class="dropdown-item" href="products.html?category=keyboard">
								Bàn phím
							</a>
						</li>
						<li>
							<a class="dropdown-item" href="products.html?category=monitor">
								Màn hình
							</a>
						</li>
						<li>
							<a class="dropdown-item" href="products.html?category=gaming-gear">
								Gaming gear
							</a>
						</li>
					</ul>
				</div>
				<form
					class="search-form mx-lg-4 my-3 my-lg-0"
					role="search"
					action="products.html"
					method="get"
				>
					<label class="visually-hidden" for="site-search">Search products</label>
					<input
						id="site-search"
						class="form-control"
						type="search"
						name="q"
						placeholder="Bạn đang tìm gì?"
						autocomplete="off"
					/>
					<button class="search-button" type="submit" aria-label="Search">
						<i class="bi bi-search" aria-hidden="true"></i>
					</button>
				</form>
				<div class="header-actions ms-lg-auto">
					<a
						class="header-action header-button"
						href="#accountModal"
						data-bs-toggle="modal"
						data-bs-target="#accountModal"
					>
						<i class="bi bi-person-circle" aria-hidden="true"></i>
						<span>Tài khoản</span>
					</a>
					<a class="header-action cart-action" href="cart.html">
						<span class="cart-icon">
							<i class="bi bi-cart3" aria-hidden="true"></i>
							<b>0</b>
						</span>
						<span>Giỏ hàng</span>
					</a>
				</div>
			</div>
		</nav>
	</div>
	<div class="header-bottom">
		<div class="container">
			<div class="header-menu">
				<div>
					<span>Danh mục sản phẩm</span>
				</div>
			</div>
			<div class="list">
				<a href="buildpc.php" class="item">
					<span>Build PC</span>
				</a>
			</div>
		</div>
	</div>
</div>

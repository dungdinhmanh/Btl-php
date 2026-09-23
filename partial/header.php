<?php
declare(strict_types=1);

/**
 * Header mega-menu data.
 * Each node: title, url, optional children (flyout column), optional children.children (holder-last list).
 */
$headerCategories = [
    [
        'title' => 'Xây dựng cấu hình PC',
        'url' => '/xay-dung-cau-hinh-pc.html',
        'children' => [
            ['title' => 'PC AMD', 'url' => '/pc-amd.html'],
            ['title' => 'PC Cao Cấp', 'url' => '/pc-cao-cap.html'],
        ],
    ],
    ['title' => 'PC Gaming', 'url' => '/gaming-pc.html'],
    ['title' => 'PC Đồ Họa AI', 'url' => '/pc-do-hoa.html'],
    [
        'title' => 'PC Đồng Bộ',
        'url' => '/pc-dong-bo.html',
        'children' => [
            ['title' => 'PC DELL', 'url' => '/pc-dell-dong-bo.html'],
            ['title' => 'PC HP', 'url' => '/pc-hp.html'],
            ['title' => 'PC Lenovo', 'url' => '/pc-lenovo-all-in-one.html'],
        ],
    ],
    [
        'title' => 'Laptop - Máy Tính Xách Tay',
        'url' => '/lap-top.html',
        'children' => [
            ['title' => 'Laptop Gaming', 'url' => '/gaming-laptop.html'],
            ['title' => 'Laptop Văn Phòng', 'url' => '/laptop-van-phong.html'],
            ['title' => 'Laptop Asus', 'url' => '/laptop-asus.html'],
            ['title' => 'Laptop Acer', 'url' => '/laptop-acer.html'],
            ['title' => 'Laptop MSI', 'url' => '/laptop-msi.html'],
            ['title' => 'Laptop Gigabyte', 'url' => '/laptop-gigabyte.html'],
            ['title' => 'Phụ kiện, Balo laptop', 'url' => '/phu-kien-laptop.html'],
            ['title' => 'Laptop Lenovo', 'url' => '/laptop-lenovo.html'],
            ['title' => 'Laptop HP', 'url' => '/laptop-hp.html'],
        ],
    ],
    [
        'title' => 'Màn Hình Máy Tính',
        'url' => '/monitor.html',
        'children' => [
            ['title' => 'Màn Hình Gaming', 'url' => '/man-hinh-gaming.html'],
            ['title' => 'Màn Hình ASUS', 'url' => '/man-hinh-asus.html'],
            ['title' => 'Màn Hình HP', 'url' => '/man-hinh-hp.html'],
            ['title' => 'Màn Hình DELL', 'url' => '/man-hinh-dell.html'],
            ['title' => 'Màn Hình Đồ Họa', 'url' => '/man-hinh-do-hoa.html'],
            ['title' => 'Màn Hình ACER', 'url' => '/man-hinh-acer.html'],
            ['title' => 'Màn Hình LG', 'url' => '/man-hinh-lg.html'],
            ['title' => 'Màn Hình MSI', 'url' => '/man-hinh-msi.html'],
            ['title' => 'Màn Hình Văn Phòng', 'url' => '/man-hinh-van-phong.html'],
            ['title' => 'Màn Hình HKC', 'url' => '/man-hinh-hkc.html'],
            ['title' => 'Màn hình Philips', 'url' => '/man-hinh-philips.html'],
            ['title' => 'Màn hình Aoc', 'url' => '/man-hinh-aoc.html'],
            ['title' => 'Màn Hình Gigabyte', 'url' => '/man-hinh-gigabyte.html'],
            ['title' => 'Màn Hình Viewsonic', 'url' => '/viewsonic-monitor.html'],
        ],
    ],
    [
        'title' => 'Máy chơi game - Console',
        'url' => '/may-choi-game.html',
        'children' => [
            ['title' => 'Máy PS5 - PlayStation 5', 'url' => '/may-play-station-5.html'],
            ['title' => 'Pc Handheld', 'url' => '/pc-handheld.html'],
            ['title' => 'Đĩa Game PS5', 'url' => '/dia-game-ps5.html'],
            ['title' => 'Phụ Kiện Playstation', 'url' => '/phu-kien-playstation.html'],
            ['title' => 'Máy chơi game Nintendo', 'url' => '/may-choi-game-nintendo.html'],
            ['title' => 'Game Nintendo Switch', 'url' => '/dia-game-nintendo-switch.html'],
        ],
    ],
    [
        'title' => 'Tay Cầm Chơi Game',
        'url' => '/tay-cam-choi-game.html',
        'children' => [
            ['title' => 'Tay Cầm PS5', 'url' => '/tay-cam-ps5.html'],
            ['title' => 'Tay Cầm Xbox', 'url' => '/tay-cam-xbox.html'],
            ['title' => 'Tay cầm Razer', 'url' => '/tay-cam-razer.html'],
            ['title' => 'Tay cầm Asus', 'url' => '/tay-cam-asus.html'],
        ],
    ],
    [
        'title' => 'VGA - Card màn hình',
        'url' => '/vga-card-man-hinh.html',
        'children' => [
            ['title' => 'VGA NVidia RTX 5050', 'url' => '/rtx-5050.html'],
            ['title' => 'VGA AMD RX 9060 XT', 'url' => '/vga-amd-rx-9060.html'],
            ['title' => 'VGA AMD RX 9070 XT - RX 9070', 'url' => '/vga-amd-rx-9070.html'],
            ['title' => 'VGA Nvidia RTX 5000 Series', 'url' => '/vga-nvidia-rtx-5000-series.html'],
            ['title' => 'VGA Nvidia RTX 5090', 'url' => '/vga-nvidia-rtx-5090.html'],
            ['title' => 'VGA Nvidia RTX 5080', 'url' => '/vga-nvidia-rtx-5080.html'],
            ['title' => 'VGA Nvidia RTX 5070/ 5070 Ti', 'url' => '/vga-nvidia-rtx-5070.html'],
            ['title' => 'VGA RTX 5060/ 5060Ti', 'url' => '/vga-rtx-5060.html'],
            ['title' => 'VGA Nvidia RTX 4080 / 4080 Super', 'url' => '/vga-nvidia-rtx-4080.html'],
            ['title' => 'VGA Nvidia RTX 4060 / 4060Ti', 'url' => '/vga-nvidia-rtx-4060.html'],
            ['title' => 'VGA Nvidia RTX 3060/ RTX 3060Ti', 'url' => '/vga-rtx-3060-ti.html'],
            ['title' => 'VGA Nvidia RTX 3050', 'url' => '/vga-nvidia-rtx-3050.html'],
            ['title' => 'VGA ASUS', 'url' => '/vga-asus.html'],
            ['title' => 'VGA MSI', 'url' => '/vga-msi.html'],
            ['title' => 'VGA Gigabyte', 'url' => '/vga-gigabyte.html'],
            ['title' => 'VGA Colorful', 'url' => '/vga-colorful.html'],
            ['title' => 'VGA Inno3D', 'url' => '/vga-inno3d.html'],
            ['title' => 'VGA Galax', 'url' => '/vga-galax.html'],
            ['title' => 'VGA Leadtek', 'url' => '/vga-leadtek.html'],
            ['title' => 'VGA Palit', 'url' => '/vga-palit.html'],
        ],
    ],
    [
        'title' => 'Linh kiện máy tính',
        'url' => '/linh-kien-pc.html',
        'children' => [
            [
                'title' => 'CPU - Bộ vi xử lý',
                'url' => '/cpu.html',
                'children' => [
                    ['title' => 'CPU Intel', 'url' => '/cpu-intel.html'],
                    ['title' => 'CPU AMD', 'url' => '/cpu-amd.html'],
                ],
            ],
            [
                'title' => 'Mainboard - Bo mạch chủ',
                'url' => '/mainboard-bo-mach-chu.html',
                'children' => [
                    ['title' => 'Main Asus', 'url' => '/main-asus.html'],
                    ['title' => 'Main MSI', 'url' => '/main-msi.html'],
                    ['title' => 'Main Gigabyte', 'url' => '/mainboard-gigabyte.html'],
                    ['title' => 'Main ASRock', 'url' => '/main-asrock.html'],
                    ['title' => 'Main Biostar', 'url' => '/main-biostar.html'],
                    ['title' => 'Main NZXT', 'url' => '/main-nzxt.html'],
                ],
            ],
            [
                'title' => 'RAM - Bộ nhớ trong',
                'url' => '/ram.html',
                'children' => [
                    ['title' => 'RAM Silicon Power', 'url' => '/ram-silicon-power.html'],
                    ['title' => 'RAM Corsair', 'url' => '/ram-corsair.html'],
                    ['title' => 'RAM Gigabyte', 'url' => '/ram-gigabyte.html'],
                    ['title' => 'RAM Kingston', 'url' => '/ram-kingston.html'],
                    ['title' => 'RAM Adata', 'url' => '/ram-adata.html'],
                    ['title' => 'RAM Apacer', 'url' => '/ram-apacer.html'],
                    ['title' => 'RAM G.Skill', 'url' => '/ram-gskill.html'],
                    ['title' => 'RAM Kimtigo', 'url' => '/ram-kimtigo.html'],
                    ['title' => 'RAM Lexar', 'url' => '/ram-lexar.html'],
                    ['title' => 'RAM Team Group', 'url' => '/ram-team-group.html'],
                ],
            ],
            [
                'title' => 'Ổ cứng HDD',
                'url' => '/hdd.html',
                'children' => [
                    ['title' => 'Ổ cứng HDD - PC', 'url' => '/o-cung-hdd-pc.html'],
                    ['title' => 'Ổ cứng HDD - Laptop', 'url' => '/o-cung-hdd-laptop.html'],
                ],
            ],
            [
                'title' => 'Case - Vỏ máy tính',
                'url' => '/vo-case.html',
                'children' => [
                    ['title' => 'Case Asus', 'url' => '/case-asus.html'],
                    ['title' => 'Case Corsair', 'url' => '/case-corsair.html'],
                    ['title' => 'Case Lian Li', 'url' => '/case-lian-li.html'],
                    ['title' => 'Case MSI', 'url' => '/case-msi.html'],
                    ['title' => 'Case Gigabyte', 'url' => '/case-gigabyte.html'],
                    ['title' => 'Case Fractal Design', 'url' => '/case-fractal-design.html'],
                    ['title' => 'Case DeepCool', 'url' => '/case-deepcool.html'],
                    ['title' => 'Case Hyte', 'url' => '/case-hyte.html'],
                    ['title' => 'Case Jonsbo', 'url' => '/case-jonsbo.html'],
                    ['title' => 'Case Kenoo', 'url' => '/case-kenoo.html'],
                    ['title' => 'Case MIK', 'url' => '/case-mik.html'],
                    ['title' => 'Case Montech', 'url' => '/case-montech.html'],
                    ['title' => 'Case VITRA', 'url' => '/case-vitra.html'],
                    ['title' => 'Case Xigmatek', 'url' => '/case-xigmatek.html'],
                    ['title' => 'Case Centaur', 'url' => '/case-centaur.html'],
                    ['title' => 'Case Cooler Master', 'url' => '/case-cooler-master.html'],
                    ['title' => 'Case Cougar', 'url' => '/case-cougar.html'],
                    ['title' => 'Case DarkFlash', 'url' => '/case-darkflash.html'],
                    ['title' => 'Case E-Dra', 'url' => '/case-e-dra.html'],
                    ['title' => 'Case NZXT', 'url' => '/case-nzxt.html'],
                    ['title' => 'Case Forgame', 'url' => '/case-forgame.html'],
                ],
            ],
            [
                'title' => 'PSU - Nguồn máy tính',
                'url' => '/nguon-may-tinh.html',
                'children' => [
                    ['title' => 'Nguồn ASUS', 'url' => '/nguon-asus.html'],
                    ['title' => 'Nguồn Cooler Master', 'url' => '/nguon-coolermaster.html'],
                    ['title' => 'Nguồn Kenoo', 'url' => '/nguon-kenoo.html'],
                    ['title' => 'Nguồn Antec', 'url' => '/nguon-antec.html'],
                    ['title' => 'Nguồn DeepCool', 'url' => '/nguon-deepcool.html'],
                    ['title' => 'Nguồn Gigabyte', 'url' => '/nguon-gigabyte.html'],
                    ['title' => 'Nguồn NZXT', 'url' => '/nguon-nzxt.html'],
                    ['title' => 'Nguồn Lian Li', 'url' => '/nguon-lian-li.html'],
                    ['title' => 'Nguồn Leadtek', 'url' => '/nguon-leadtek.html'],
                    ['title' => 'Nguồn MSI', 'url' => '/nguon-msi.html'],
                    ['title' => 'Nguồn Corsair', 'url' => '/nguon-corsair.html'],
                ],
            ],
            [
                'title' => 'Ổ cứng SSD',
                'url' => '/ssd-o-the-ran.html',
                'children' => [
                    ['title' => 'SSD Seagate', 'url' => '/ssd-seagate.html'],
                    ['title' => 'SSD Samsung', 'url' => '/ssd-samsung.html'],
                    ['title' => 'SSD Western Digital', 'url' => '/ssd-western-digital.html'],
                    ['title' => 'SSD Corsair', 'url' => '/ssd-corsair.html'],
                    ['title' => 'SSD Pioneer', 'url' => '/ssd-pioneer.html'],
                    ['title' => 'SSD Kingston', 'url' => '/ssd-kingston.html'],
                    ['title' => 'SSD Lexar', 'url' => '/ssd-lexar.html'],
                    ['title' => 'SSD Gigabyte', 'url' => '/ssd-gigabyte.html'],
                    ['title' => 'SSD Adata', 'url' => '/ssd-adata.html'],
                    ['title' => 'SSD MSI', 'url' => '/ssd-msi.html'],
                    ['title' => 'SSD Apacer', 'url' => '/ssd-apacer.html'],
                    ['title' => 'SSD Biostar', 'url' => '/ssd-biostar.html'],
                    ['title' => 'SSD Intel', 'url' => '/ssd-intel.html'],
                    ['title' => 'SSD Silicon Power', 'url' => '/ssd-silicon-power.html'],
                    ['title' => 'SSD Afox', 'url' => '/ssd-afox.html'],
                    ['title' => 'SSD Teamgroup', 'url' => '/ssd-teamgroup.html'],
                ],
            ],
        ],
    ],
    [
        'title' => 'Gaming Gears',
        'url' => '/gaming-gear.html',
        'children' => [
            ['title' => 'Razer Gaming Gear', 'url' => '/razer-gaming-gear.html'],
            ['title' => 'Bàn Phím Cơ', 'url' => '/ban-phim-co.html'],
            ['title' => 'Bàn Phím Akko', 'url' => '/ban-phim-co-akko.html'],
            ['title' => 'Tai Nghe Gaming', 'url' => '/tai-nghe-choi-game.html'],
            ['title' => 'Chuột Gaming', 'url' => '/chuot-gaming.html'],
            ['title' => 'Bàn di chuột', 'url' => '/ban-di-chuot.html'],
            ['title' => 'Ghế Công Thái Học', 'url' => '/ghe-cong-thai-hoc.html'],
            ['title' => 'Bàn Công Thái Học', 'url' => '/ban-cong-thai-hoc.html'],
            ['title' => 'Ghế Gaming', 'url' => '/ghe-gaming.html'],
            ['title' => 'Bàn Gaming', 'url' => '/ban-gaming.html'],
            ['title' => 'MIC - Microphone', 'url' => '/microphone.html'],
            ['title' => 'Thiết bị Stream', 'url' => '/thiet-bi-stream.html'],
            ['title' => 'Keycaps', 'url' => '/keycap.html'],
        ],
    ],
    [
        'title' => 'PC Văn Phòng',
        'url' => '/pc-van-phong.html',
        'children' => [
            ['title' => 'Máy Tính Để Bàn DELL', 'url' => '/may-tinh-de-ban-dell.html'],
            ['title' => 'Máy Tính Để Bàn HP', 'url' => '/may-tinh-de-ban-hp.html'],
            ['title' => 'Máy Tính Để Bàn ASUS', 'url' => '/may-tinh-de-ban-asus.html'],
            ['title' => 'Máy Tính Intel NUC', 'url' => '/may-tinh-intel-nuc.html'],
            ['title' => 'PC Dành Cho Học Sinh - Sinh Viên', 'url' => '/pc-sinh-vien.html'],
        ],
    ],
    [
        'title' => 'Phụ Kiện - Tản Nhiệt PC',
        'url' => '/tan-nhiet-may-tinh.html',
        'children' => [
            ['title' => 'Tản nhiệt khí cho PC', 'url' => '/tan-nhiet-khi.html'],
            ['title' => 'Tản nước ALL IN ONE', 'url' => '/tan-nuoc-all-in-one.html'],
            ['title' => 'Quạt Tản Nhiệt PC', 'url' => '/quat-tan-nhiet-pc.html'],
            ['title' => 'Dây Nối Dài - Dây Riser', 'url' => '/day-noi-dai.html'],
            ['title' => 'Phụ Kiện Set Up', 'url' => '/phu-kien-set-up.html'],
            ['title' => 'Giá Treo Màn Hình', 'url' => '/gia-treo-man-hinh.html'],
            ['title' => 'Giá Đỡ VGA', 'url' => '/gia-do-vga.html'],
            ['title' => 'Tản nhiệt Valkyrie', 'url' => '/tan-nhiet-valkyrie.html'],
        ],
    ],
    [
        'title' => 'AUDIO',
        'url' => '/audio.html',
        'children' => [
            ['title' => 'Loa Nghe Nhạc', 'url' => '/loa-nghe-nhac.html'],
            ['title' => 'Tai Nghe True Wireless', 'url' => '/tai-nghe-true-wireless.html'],
            ['title' => 'Loa Máy Tính', 'url' => '/loa-may-tinh.html'],
        ],
    ],
    [
        'title' => 'Thiết bị văn phòng',
        'url' => '/thiet-bi-van-phong.html',
        'children' => [
            ['title' => 'Phần mềm bản quyền', 'url' => '/phan-mem-ban-quyen.html'],
            ['title' => 'Bộ Bàn Phím Chuột Không Dây', 'url' => '/ban-phim-chuot-khong-day.html'],
            [
                'title' => 'Máy in Laser',
                'url' => '/may-in-laser.html',
                'children' => [
                    ['title' => 'Máy In Laser Đơn Năng', 'url' => '/may-in-laser-don-nang.html'],
                    ['title' => 'Máy In Laser Đa Năng', 'url' => '/may-in-laser-da-nang.html'],
                    ['title' => 'Máy In Phun Màu', 'url' => '/may-in-phun-mau.html'],
                    ['title' => 'Máy In Laser Màu', 'url' => '/may-in-laser-mau.html'],
                    ['title' => 'Máy Scan', 'url' => '/may-scan.html'],
                    ['title' => 'Máy In Nhiệt', 'url' => '/may-in-nhiet.html'],
                ],
            ],
            ['title' => 'Webcam', 'url' => '/webcam.html'],
            ['title' => 'Bộ Chia/Hub USB', 'url' => '/bo-chia-hub-usb.html'],
            ['title' => 'Cartridge', 'url' => '/cartridge.html'],
        ],
    ],
    [
        'title' => 'Thiết bị mạng',
        'url' => '/thiet-bi-mang.html',
        'children' => [
            ['title' => 'Bộ phát Wifi', 'url' => '/bo-phat-wifi.html'],
            ['title' => 'Card Mạng', 'url' => '/card-mang.html'],
            ['title' => 'Router Wifi', 'url' => '/router-accesspoint.html'],
        ],
    ],
    ['title' => 'Khuyến Mãi', 'url' => '/khuyen-mai.html'],
];

/** Quick links shown on the right side of the header-bottom bar. */
$headerQuickLinks = [
    ['title' => 'Build PC', 'url' => '/buildpc'],
    ['title' => 'PC gaming', 'url' => '/gaming-pc.html'],
    ['title' => 'PC Đồ họa AI', 'url' => '/pc-do-hoa.html'],
    ['title' => 'Màn hình gaming', 'url' => '/man-hinh-gaming.html'],
    ['title' => 'Laptop Gaming', 'url' => '/gaming-laptop.html'],
    ['title' => 'PS5 Slim', 'url' => '/may-choi-game-sony-ps5-slim-chinh-hang-cfi-2018a-01.html'],
    ['title' => 'RTX 5060', 'url' => '/vga-rtx-5060.html'],
    ['title' => 'RTX 5070', 'url' => '/vga-nvidia-rtx-5070.html'],
    ['title' => 'RTX 5080', 'url' => '/vga-nvidia-rtx-5080.html'],
    ['title' => 'RX 9070', 'url' => '/vga-amd-rx-9070.html'],
    ['title' => 'Máy in', 'url' => '/may-in-laser.html'],
];
?>
<div class="banner-top">
	<a href="products.php">
		<img src="assets/img/banner/banner-ad.png" alt="Banner top" />
	</a>
</div>
<div class="header">
	<div class="header-top">
		<div class="container right">
			<a href="sitemap" class="item">
				<i class="bi bi-telephone"></i>
				<span>Tất cả sản phẩm</span>
			</a>
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
			<a class="navbar-brand logo" href="index.php" aria-label="TNC Store home">
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
				<form
					class="search-form mx-lg-4 my-3 my-lg-0"
					role="search"
					action="products.php"
					method="get"
				>
					<div class="category-dropdown">
						<input type="hidden" name="category" id="js-search-catId" value="" />
						<button
							type="button"
							class="category-toggle"
							id="js-search-title"
							aria-expanded="false"
							aria-controls="js-list-option-search"
						>
							<span>Tất cả danh mục</span>
							<i class="bi bi-chevron-down" aria-hidden="true"></i>
						</button>
						<div class="dropdown-menu" id="js-list-option-search">
							<span class="dropdown-item active">Tất cả danh mục</span>
							<span class="dropdown-item">Xây dựng cấu hình PC</span>
							<span class="dropdown-item">PC Gaming</span>
							<span class="dropdown-item">PC Đồ Họa AI</span>
							<span class="dropdown-item">PC Đồng Bộ</span>
							<span class="dropdown-item">Laptop - Máy Tính Xách Tay</span>
							<span class="dropdown-item">Màn Hình Máy Tính</span>
							<span class="dropdown-item">Máy chơi game - Console</span>
							<span class="dropdown-item">Tay Cầm Chơi Game</span>
							<span class="dropdown-item">VGA - Card màn hình</span>
							<span class="dropdown-item">Linh kiện máy tính</span>
							<span class="dropdown-item">Gaming Gears</span>
							<span class="dropdown-item">PC Văn Phòng</span>
							<span class="dropdown-item">Phụ Kiện - Tản Nhiệt PC</span>
							<span class="dropdown-item">AUDIO</span>
							<span class="dropdown-item">Thiết bị văn phòng</span>
							<span class="dropdown-item">Thiết bị mạng</span>
							<span class="dropdown-item">Khuyến Mãi</span>
						</div>
					</div>
					<input
						id="js-global-seach"
						class="form-control"
						type="text"
						name="q"
						placeholder="Nhập sản phẩm cần tìm..."
						autocomplete="off"
						value=""
						aria-label="Nhập sản phẩm cần tìm"
					/>
					<button class="search-button" type="submit">
						<span>Tìm kiếm</span>
						<i class="bi bi-search" aria-hidden="true"></i>
					</button>
				</form>
				<div class="header-actions ms-lg-auto">
					<a
						class="header-action header-button"
						href="#accountModal"
						data-account-toggle
					>
						<i class="bi bi-person-circle" aria-hidden="true"></i>
						<span>Tài khoản</span>
					</a>
					<div class="header-cart-dropdown">
						<a class="header-action cart-action" href="cart.php">
							<span class="cart-icon">
								<i class="bi bi-cart3" aria-hidden="true"></i>
								<b>0</b>
							</span>
							<span>Giỏ hàng</span>
						</a>
						<div class="header-cart-hover" data-cart-hover>
							<b class="d-block text-center p-4">Có 0 sản phẩm trong giỏ hàng</b>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<div class="global-login" data-account-panel>
			<a
				href="javascript:void(0)"
				class="close-login"
				data-account-close
				aria-label="Đóng"
			>
				<i class="bi bi-x-lg" aria-hidden="true"></i>
			</a>
			<div class="content-form-login padding-24">
				<div class="content-login active" data-account-form="login">
					<div class="title">Đăng Nhập</div>
					<div class="content-form">
						<div class="form-input">
							<div class="label">Email</div>
							<input
								type="text"
								id="js-login-email"
								placeholder="Nhập email đăng ký của bạn"
								autocomplete="off"
							/>
							<div class="note-error"></div>
						</div>
						<div class="form-input">
							<div class="label">Mật khẩu</div>
							<input
								type="password"
								id="js-login-password"
								placeholder="Nhập mật khẩu của bạn"
								autocomplete="off"
							/>
							<div class="note-error"></div>
						</div>
						<div id="js-form-note"></div>
						<a href="javascript:void(0)" class="btn-submit">
							<span class="txt">Đăng nhập</span>
						</a>
						<div class="note d-flex align-items text-center space-center">
							<p>Khách hàng mới?</p>
							<a href="javascript:void(0)" data-account-form-link="register">Tạo tài khoản</a>
						</div>
						<div class="note d-flex align-items text-center space-center">
							<p>Quên mật khẩu?</p>
							<a href="javascript:void(0)" data-account-form-link="forgot-password"><span class="txt">Đặt lại mật khẩu</span></a>
						</div>
					</div>
				</div>
				<div class="content-login" data-account-form="register">
					<div class="title">Tạo tài khoản</div>
					<div class="content-form">
						<div class="form-input">
							<div class="label">Họ và tên</div>
							<input
								type="text"
								id="js-popup-register-name"
								placeholder="Nhập họ và tên của bạn"
								autocomplete="off"
							/>
							<div class="note-error"></div>
						</div>
						<div class="form-input">
							<div class="label">Email</div>
							<input
								type="text"
								id="js-popup-register-email"
								placeholder="Nhập email mà bạn muốn đăng ký"
								autocomplete="off"
							/>
							<div class="note-error"></div>
						</div>
						<div class="form-input">
							<div class="label">Mật khẩu</div>
							<input
								type="password"
								id="js-popup-register-password"
								placeholder="Nhập mật khẩu của bạn"
								autocomplete="off"
							/>
							<div class="note-error"></div>
						</div>
						<div id="js-popup-register-note"></div>
						<a href="javascript:void(0)" class="btn-submit">
							<span class="txt">Tạo tài khoản</span>
						</a>
						<div class="note d-flex align-items text-center space-center">
							<p>Đã có tài khoản?</p>
							<a href="javascript:void(0)" data-account-form-link="login"><span class="txt">Đăng nhập</span></a>
						</div>
					</div>
				</div>
				<div class="content-login" data-account-form="forgot-password">
					<div class="title">Đặt lại mật khẩu</div>
					<div class="content-form">
						<div class="form-input">
							<div class="label">Email</div>
							<input
								type="text"
								id="js-forgotpass-email"
								placeholder="Nhập email mà bạn đã đăng ký"
								autocomplete="off"
							/>
						</div>
						<div id="js-forgotpass-note"></div>
						<a href="javascript:void(0)" class="btn-submit">
							<span class="txt">Lấy lại mật khẩu</span>
						</a>
						<div class="note d-flex align-items text-center space-center">
							<p>Nhớ mật khẩu?</p>
							<a href="javascript:void(0)" data-account-form-link="login"><span class="txt">Đăng nhập</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="header-bottom">
		<div class="container">
			<div class="header-bottom-content d-flex">
				<div class="header-menu">
					<a href="javascript:void(0)" class="title">
						<i class="bi bi-list"></i>
						<span class="hover-txt">Danh mục sản phẩm</span>
						<i class="bi bi-chevron-down"></i>
					</a>
					<div class="height-hover"></div>
					<div class="menu_holder">
						<?php foreach ($headerCategories as $category): ?>
							<div class="item">
								<a href="<?= $category['url'] ?>" class="item-cate d-flex align-items">
									<p class="cat-title"><?= $category['title'] ?></p>
									<span class="box-right">
										<i class="bi bi-chevron-right"></i>
									</span>
								</a>
								<?php if (!empty($category['children'])): ?>
									<div class="menu-hover">
										<div class="list-holder d-flex flex-wrap">
											<?php foreach ($category['children'] as $child): ?>
												<div class="item-holder">
													<a href="<?= $child['url'] ?>" class="title-holder"><?= $child['title'] ?></a>
													<?php foreach ($child['children'] ?? [] as $leaf): ?>
														<div class="holder-last">
															<a href="<?= $leaf['url'] ?>"><?= $leaf['title'] ?></a>
														</div>
													<?php endforeach; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="header-bottom-right d-flex align-items">
					<?php foreach ($headerQuickLinks as $link): ?>
						<a href="<?= $link['url'] ?>" class="item">
							<span class="txt" alt="<?= $link['title'] ?>"><?= $link['title'] ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="js/search-form.js" defer></script>

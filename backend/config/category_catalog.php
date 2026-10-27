<?php
declare(strict_types=1);

/**
 * Metadata hiển thị cho từng danh mục linh kiện trên trang sản phẩm.
 *
 * Đây là nguồn duy nhất cho:
 *   - ảnh banner riêng của danh mục;
 *   - các khoảng giá và khoảng min/max của slider.
 *
 * `backend/api/meta.php` đọc file này rồi trả về cho `js/products.js`.
 * Khoảng giá lấy từ trang gốc tncstore.vn (đã đối chiếu ngày 2026-10-27):
 * nhãn, min/max và slider min/max giữ đúng như trang gốc; riêng HDD có
 * slider min = max = 3 triệu nên giao diện sẽ ẩn slider (trang gốc cũng
 * không dùng được slider này).
 *
 * min/max = null nghĩa là không giới hạn phía đó, khớp với link gốc
 * `?max=...` hoặc `?min=...`.
 */
return [
    'cpu' => [
        'banner' => 'assets/img/category-banner/cpu.jpg',
        'bannerAlt' => 'Banner danh mục CPU - Bộ vi xử lý',
        'price' => [
            'min' => 3000000,
            'max' => 11000000,
            'bands' => [
                ['label' => 'Dưới 3 triệu', 'min' => null, 'max' => 3000000],
                ['label' => '3 triệu - 8 triệu', 'min' => 3000000, 'max' => 8000000],
                ['label' => '8 triệu - 11 triệu', 'min' => 8000000, 'max' => 11000000],
                ['label' => 'Trên 11 triệu', 'min' => 11000000, 'max' => null],
            ],
        ],
    ],
    'mainboard' => [
        'banner' => 'assets/img/category-banner/mainboard.jpg',
        'bannerAlt' => 'Banner danh mục Bo mạch chủ - Mainboard',
        'price' => [
            'min' => 1000000,
            'max' => 13000000,
            'bands' => [
                ['label' => '1 triệu - 3 triệu', 'min' => 1000000, 'max' => 3000000],
                ['label' => '3 triệu - 5 triệu', 'min' => 3000000, 'max' => 5000000],
                ['label' => '5 triệu - 7 triệu', 'min' => 5000000, 'max' => 7000000],
                ['label' => '7 triệu - 9 triệu', 'min' => 7000000, 'max' => 9000000],
                ['label' => '9 triệu - 13 triệu', 'min' => 9000000, 'max' => 13000000],
                ['label' => 'Trên 13 triệu', 'min' => 13000000, 'max' => null],
            ],
        ],
    ],
    'ram' => [
        'banner' => 'assets/img/category-banner/ram.jpg',
        'bannerAlt' => 'Banner danh mục RAM - Bộ nhớ trong',
        'price' => [
            'min' => 500000,
            'max' => 5000000,
            'bands' => [
                ['label' => '500 ngàn - 1 triệu', 'min' => 500000, 'max' => 1000000],
                ['label' => '1 triệu - 1.5 triệu', 'min' => 1000000, 'max' => 1500000],
                ['label' => '1.5 triệu - 2.5 triệu', 'min' => 1500000, 'max' => 2500000],
                ['label' => '2.5 triệu - 5 triệu', 'min' => 2500000, 'max' => 5000000],
                ['label' => 'Trên 5 triệu', 'min' => 5000000, 'max' => null],
            ],
        ],
    ],
    'hdd' => [
        'banner' => 'assets/img/category-banner/hdd.jpg',
        'bannerAlt' => 'Banner danh mục Ổ cứng HDD',
        'price' => [
            'min' => 3000000,
            'max' => 3000000,
            'bands' => [
                ['label' => 'Dưới 3 triệu', 'min' => null, 'max' => 3000000],
                ['label' => 'Trên 3 triệu', 'min' => 3000000, 'max' => null],
            ],
        ],
    ],
    'case' => [
        'banner' => 'assets/img/category-banner/case.jpg',
        'bannerAlt' => 'Banner danh mục Case - Vỏ máy tính',
        'price' => [
            'min' => 1000000,
            'max' => 6500000,
            'bands' => [
                ['label' => 'Dưới 1 triệu', 'min' => null, 'max' => 1000000],
                ['label' => '1 triệu - 2 triệu', 'min' => 1000000, 'max' => 2000000],
                ['label' => '2 triệu - 3 triệu', 'min' => 2000000, 'max' => 3000000],
                ['label' => '3 triệu - 5 triệu', 'min' => 3000000, 'max' => 5000000],
                ['label' => '5 triệu - 6.5 triệu', 'min' => 5000000, 'max' => 6500000],
                ['label' => 'Trên 6.5 triệu', 'min' => 6500000, 'max' => null],
            ],
        ],
    ],
    'psu' => [
        'banner' => 'assets/img/category-banner/psu.jpg',
        'bannerAlt' => 'Banner danh mục PSU - Nguồn máy tính',
        'price' => [
            'min' => 1500000,
            'max' => 8500000,
            'bands' => [
                ['label' => 'Dưới 1.5 triệu', 'min' => null, 'max' => 1500000],
                ['label' => '1.5 triệu - 3.5 triệu', 'min' => 1500000, 'max' => 3500000],
                ['label' => '3.5 triệu - 5 triệu', 'min' => 3500000, 'max' => 5000000],
                ['label' => '5 triệu - 8.5 triệu', 'min' => 5000000, 'max' => 8500000],
                ['label' => 'Trên 8.5 triệu', 'min' => 8500000, 'max' => null],
            ],
        ],
    ],
    'ssd' => [
        'banner' => 'assets/img/category-banner/ssd.jpg',
        'bannerAlt' => 'Banner danh mục Ổ cứng SSD',
        'price' => [
            'min' => 1000000,
            'max' => 7000000,
            'bands' => [
                ['label' => 'Dưới 1 triệu', 'min' => null, 'max' => 1000000],
                ['label' => '1 triệu - 2 triệu', 'min' => 1000000, 'max' => 2000000],
                ['label' => '2 triệu - 3 triệu', 'min' => 2000000, 'max' => 3000000],
                ['label' => '3 triệu - 5 triệu', 'min' => 3000000, 'max' => 5000000],
                ['label' => '5 triệu - 7 triệu', 'min' => 5000000, 'max' => 7000000],
                ['label' => 'Trên 7 triệu', 'min' => 7000000, 'max' => null],
            ],
        ],
    ],
];

# PLAN — bàn giao dự án Btl-php

> Cập nhật: **2026-10-19**. Mọi số liệu dưới đây do **chạy lệnh thật** mà có, không phải suy đoán.
> Nếu bạn (agent) thấy tài liệu này khác với code/DB hiện tại → **tin code/DB**, và sửa lại file này.

## 0. Quy tắc chống hallucinate

1. **Đừng tin trí nhớ, hãy chạy lệnh.** Mọi điều về DB phải kiểm bằng `mariadb`/`PDO`, mọi điều về git phải bằng `git`.
2. `information_schema.table_rows` là **số ước lượng của InnoDB và nói dối** (từng báo `order_statuses = 0` nhưng thật là 5). Luôn `COUNT(*)`.
3. Tài liệu cũ trong repo có chỗ **sai**: `backend/README.md`, `README.MD`, `schema.sql` ghi database là `tnc_store`, nhưng DB thật là **`tnc-db`** (có gạch nối).
   → **Đã sửa (2026-10-14):** `README.MD`, `backend/config/.env.example` và `schema.sql`
   (`CREATE DATABASE`/`USE`) đều đã đổi sang `tnc-db`. `backend/README.md` không nhắc tên DB nên không cần sửa.
4. Đừng giả định tính năng nào đã có. Khoản "chưa làm" ở mục 4 là **chưa có một dòng code nào**.

---

## 1. Trạng thái repo (đã kiểm chứng)

```
branch            : main
commit mới nhất   : a85ad51  PLAN.md: thêm mục 8.1b dùng mysql client của XAMPP + cảnh báo nhầm localhost/XAMPP với Aiven
commit trước đó   : d5b11e7  PLAN.md: thêm mục 8 cho máy Windows (DB, autocrlf, lệnh kiểm tra, OPcache)
commit code gần nhất: 7b1bfe9  Thêm products.model + dọn comment thừa
origin/main       : chưa pull khi ghi lại mục này — **chạy `git pull` và `git log --oneline -5` để xác nhận**
working tree      : có thay đổi chưa commit — hệ thống đánh giá (mục 4.1 + 4.2),
                     sửa tên DB trong README.MD / .env.example / schema.sql, và ảnh banner mới
```

**Việc đầu tiên của bạn:** `git pull` (nếu máy khác đã push) rồi `git log --oneline -5` để xác nhận, **đừng tin con số trên nếu repo đã di chuyển**.

### Nhánh cần biết
`feature/chi-tiet-mat-hang` (commit `bebbdd7`) — **chủ dự án đã quyết định BỎ**, vì giao diện chi tiết sản phẩm trong đó không khớp trang gốc. Đừng merge nó.

---

## 2. Database thật — cách kết nối

Aiven MySQL **8.4.8**, schema **`tnc-db`** (có gạch nối, nhớ backtick), TLS qua `backend/config/ca.pem`.
Thông tin đăng nhập nằm trong `backend/config/.env` (không commit).

```bash
# Sinh file cấu hình tạm từ .env (không in mật khẩu ra màn hình)
php /tmp/mk-tnc-cnf.php        # script này không nằm trong repo; nếu mất thì tự viết lại:
                               #   đọc DB_HOST/PORT/USERNAME/PASSWORD/SSL_CA trong .env
                               #   ghi ra /tmp/tnc-mysql.cnf với [client] + chmod 600
mariadb --defaults-extra-file=/tmp/tnc-mysql.cnf -e 'SELECT ...'
```

> `/usr/bin/mysql` trên máy này thực ra là **client MariaDB** → nó **từ chối `ssl-mode`** của MySQL 8.
> Phải dùng `ssl-ca=` + `ssl-verify-server-cert=1`.
> Nhớ `shred -u /tmp/tnc-mysql.cnf` sau khi dùng xong (file có mật khẩu).
>
> ⚠️ **Nếu máy bạn là Windows thì phần này KHÁC — đọc mục 8 trước.**

### 2.1 Cách khuyến nghị: Python + `python-dotenv` (chạy giống nhau trên Linux và Windows)

Cách này **tốt hơn CLI** vì không phụ thuộc client `mysql`/`mariadb` của từng máy
(khỏi vướng chuyện `ssl-mode` vs `ssl-verify-server-cert`).

⚠️ **Máy này CHƯA cài sẵn** — đã kiểm chứng: `dotenv`, `mysql.connector`, `pymysql`,
`MySQLdb`, `mariadb` đều **không có**. `pip` thì có (26.2.1, Python 3.14). Phải cài trước:

```bash
python3 -m venv /tmp/tncvenv
/tmp/tncvenv/bin/pip install python-dotenv mysql-connector-python
/tmp/tncvenv/bin/python /tmp/tnc-py-check.py      # xem script mẫu bên dưới
```

Script mẫu — **đã chạy thật, trả về `products: 138`, `so bang: 19`, `cot model: ['model']`**:

```python
import os
from pathlib import Path
import mysql.connector
from dotenv import load_dotenv

ROOT = Path('<đường dẫn repo>')
load_dotenv(ROOT / 'backend/config/.env')

conn = mysql.connector.connect(
    host=os.environ['DB_HOST'], port=int(os.environ['DB_PORT']),
    user=os.environ['DB_USERNAME'], password=os.environ['DB_PASSWORD'],
    database=os.environ['DB_DATABASE'],
    ssl_ca=str(ROOT / 'backend/config' / os.environ['DB_SSL_CA']),
    ssl_verify_cert=True,
    ssl_verify_identity=False,   # ← xem ghi chú bên dưới
    connection_timeout=20,
)
```

**Hai điều bắt buộc phải nhớ:**
1. `ssl_verify_identity=False`. Script mẫu đặt vậy và chạy được. Tôi **chưa thử**
   `ssl_verify_identity=True` — nếu bật mà lỗi hostname mismatch thì đừng tưởng cert hỏng,
   chỉ là cert Aiven không khớp tên host.
2. `load_dotenv` **tự bỏ dấu nháy**, còn `loadEnvironment()` trong `backend/bootstrap.php` thì
   **không**. Hiện `.env` có 8 khoá và **không có dấu nháy hay `#`** trong giá trị nên hai bên
   đọc ra y nhau. **Nếu sau này ai bọc giá trị trong nháy kép, PHP và Python sẽ đọc khác nhau**
   → đừng bọc nháy.

### Số liệu DB tại 2026-10-14
| Bảng | Số dòng | Ghi chú |
|---|---|---|
| `products` | 138 | 36 SP có `rating_avg = 5.0` (dữ liệu **import**, xem mục 4.1) |
| `product_specifications` | 292 | chỉ dùng 8 `specification_id` đầu |
| `specification_definitions` | **8** | danh sách phẳng, xem mục 5.3 |
| `users` | **1** | |
| `roles` | 3 | customer / staff / admin |
| `order_statuses` | 5 | pending, confirmed, shipping, completed, cancelled |
| `orders`, `order_items` | **0 / 0** | chưa ai mua gì |
| `product_images` | 242 | |
| `products` có `list_price` | 29 | giá gạch đã nạp được |

**Không có bảng nào tên chứa `review`** (đã kiểm bằng `information_schema`, ngày 2026-10-14).

Đã đối chiếu lại bằng PHP/PDO ngày **2026-10-14**: MySQL `8.4.8`, `DATABASE()` = `tnc-db`,
138 sản phẩm, 36 sản phẩm có `rating_avg > 0`, 292 dòng thông số, 8 định nghĩa, 242 ảnh,
1 user (`asdfhsdfgthjefg` / `abc@test.com` / customer / active). Số liệu bảng trên khớp.

---

## 3. Đã hoàn thành

### 3.1 Thẻ sản phẩm (commit `b115043`)
- `js/api.js` → `productCard(product, { tag, showAddToCart })` là template **duy nhất**, sinh ra mọi thẻ.
- DOM/CSS **sao chép nguyên văn** từ stylesheet thật của tncstore.vn (đã đối chiếu bằng Playwright).
- Container tự chia bề rộng, thẻ **không có lớp cột bọc ngoài**.
- `assets/img/icon-star.png` là sprite sao tải từ tncstore (118×144 = bản 2x).

### 3.2 Cột DB mới (đã áp lên DB thật + gộp vào `schema.sql`)
`products.list_price`, `products.rating_avg`, `products.review_count`, `products.model`.

### 3.3 Model
`products.model VARCHAR(80) NULL` là **cột** (không phải dòng EAV). Bảng thông số hiển thị:
`Hãng sản xuất → Model → Chuẩn Socket → specs → Bảo hành`.
Đã nạp `model` thật cho **10 sản phẩm CPU**.

### 3.4 Dọn dẹp
- **Đã xoá thư mục `backend/database/migrations/`.** `backend/database/schema.sql` là nguồn duy nhất.
  Chủ dự án yêu cầu: *không tạo migration riêng nữa, mọi thay đổi schema gộp thẳng vào `schema.sql`*.
- Xoá 98 dòng comment thừa (261 → 172 dòng comment toàn repo).
  Đã chứng minh bằng token stream: **comment không ảnh hưởng runtime**.

### 3.5 Quản trị tin tức (admin) — 2026-10-19

`news_posts` trước đây chỉ đọc ở storefront; nay có màn hình quản trị đầy đủ
(danh sách + soạn thảo WYSIWYG + khung xem trước cạnh bài viết):

| Thành phần | File |
|---|---|
| Danh sách | `admin/news.php` + `admin/js/admin-news.js` |
| Soạn/sửa | `admin/news-edit.php` + `admin/js/admin-news-editor.js` |
| API | `backend/api/admin/news.php` (GET/POST/PUT; DELETE chỉ admin) |
| Upload ảnh | `backend/api/admin/news-upload.php` (JPG/PNG/GIF/WEBP ≤ 5MB) |
| Dữ liệu | `backend/src/Repositories/AdminNewsRepository.php` |

Quy ước đã kiểm chứng:
- `news_posts.content` là **HTML thật** và `news-post.php` render thẳng, nên editor dùng
  `contenteditable` + `execCommand` (toolbar) chứ không phải markdown.
- Ảnh tải lên lưu ở `assets/news/...` — **đường dẫn tương đối từ gốc site**, cùng quy ước
  `assets/products/...`. Trang trong `/admin/` tự thêm `../` khi hiển thị; khi lưu (và ở chế độ
  mã HTML) bỏ `../` trở lại. DB **không** lưu `../`.
- Slug tự sinh từ tiêu đề tiếng Việt (bỏ dấu); trùng thì thêm `-2`, `-3`…
- `post_status` mặc định `draft`; khi `published` mà `published_at` trống, trang soạn thảo gửi
  **giờ địa phương của máy quản trị** (tránh lệch timezone: PHP trên máy này là `Europe/Berlin`
  còn hệ điều hành là UTC+7). Gọi API trực tiếp không kèm ngày thì server mới dùng `date()`.
  Ràng buộc `chk_news_posts_publication` luôn được bảo đảm.
- Dán từ Word/web khác: client lọc allowlist thẻ/thuộc tính (bỏ `script/style/table/on*`,
  `javascript:`), server lọc lần nữa trước khi ghi.
- `DELETE` yêu cầu `ADMIN_ROLES`, các thao tác khác `STAFF_ROLES` (giống `admin/products.php`).
- Sidebar "Tin tức" (`bi-newspaper`) đã thêm vào **tất cả** trang admin hiện có.
- Trang soạn thảo nạp thêm `css/article-layout.css` để khung xem trước khớp trang thật;
  `h2` trong editor phải dùng `font-size: 24px !important` vì rule global của `style.css`.

---

## 4. CHƯA LÀM (đừng tưởng là đã có)

### 4.1 Reset đánh giá import — **ĐÃ CHẠY 2026-10-14**

```sql
UPDATE `tnc-db`.products SET rating_avg = 0.0, review_count = 0;
```

Đã chạy, `UPDATE` 36 dòng. Kết quả kiểm lại sau đó: 138 sản phẩm, **0 sản phẩm có điểm**,
29 sản phẩm có `list_price`, 10 sản phẩm có `model` — hai cột sau không bị đụng.

⚠️ Thẻ sản phẩm **không còn hiện sao** cho tới khi có đánh giá thật. Đây là trạng thái
đúng như chủ dự án chốt, không phải lỗi.

### 4.2 Hệ thống đánh giá — **ĐÃ LÀM XONG 2026-10-14**

Yêu cầu đã chốt với chủ dự án:
- **Khách vãng lai đánh giá được**, không cần đăng nhập.
- Bắt buộc **3 thứ**: điểm + họ tên + số điện thoại.
- **Reply đệ quy** (trả lời được vào chính reply trước).
- **Chặn trùng chỉ khi đã đăng nhập** (1 tài khoản 1 đánh giá gốc / sản phẩm).
  Khách vãng lai đánh giá bao nhiêu lần cũng được — chủ dự án chọn phương án này.
- **Khách trả lời khách + admin**: khách trả lời trong nhánh của chính mình (đúng họ tên
  và SĐT), admin/staff trả lời mọi nhánh và được gắn badge "TNC Store".

File đã tạo:

| Mảnh | Đường dẫn |
|---|---|
| Bảng | `product_reviews` trong `backend/database/schema.sql` (đã tạo trên DB thật) |
| Repository | `backend/src/Repositories/ReviewRepository.php` |
| API khách (GET/POST/PUT/DELETE) | `backend/api/reviews.php` |
| API quản trị | `backend/api/admin/reviews.php` |
| UI khách | tab "Đánh giá" + 2 modal trong `product-detail.php`, `js/reviews.js` |
| UI quản trị | `admin/reviews.php` + `admin/js/admin-reviews.js` |
| CSS | khối review trong `css/product-detail.css` |
| Script tính lại | `backend/database/recalculate_ratings.php` |

Ghi chú kỹ thuật đã đo thật trên DB này, đừng phá:
- `AVG()` trên tập rỗng trả `NULL` → đã bọc `COALESCE(..., 0)` vì `rating_avg` là `NOT NULL`.
- PDO trả `NEWDECIMAL` dưới dạng string → đã cast `(float)`.
- `rating_avg`/`review_count` được cập nhật **trong cùng transaction** với thao tác ghi
  nên thẻ sản phẩm (chỉ `SELECT` trên `products`) không bao giờ đọc số lệch.
- **KHÔNG dùng cột `GENERATED ... STORED`** trong bảng có FK: MySQL 8.4.8 trả
  `ERROR 1215 Cannot add foreign key constraint`. Vì vậy `root_user_id` là cột thường do
  app gán (`= user_id` với đánh giá gốc, `NULL` với reply). UNIQUE
  `(product_id, root_user_id)` chặn trùng cho tài khoản đăng nhập, còn `NULL` thì MySQL coi
  mỗi NULL là một giá trị khác nhau nên khách vãng lai không bị chặn.
- **MySQL native prepare không cho tái sử dụng placeholder**: `ATTR_EMULATE_PREPARES = false`,
  nên `LIKE :term` dùng 4 lần trong cùng một `WHERE` sẽ nổ lỗi 503. Mỗi vị trí phải một
  tên placeholder riêng (`backend/api/admin/reviews.php` đã sửa theo).

Giao diện **sao chép nguyên văn DOM của tncstore.vn** (`#review`, `#show-form`,
`.box-form-review-reply`, `.item-user-review`, `.box-list-reply`) — đã mở trang thật bằng
trình duyệt và đọc `outerHTML` + CSS gốc. Đừng đổi tên class.

### 5.6 Trang chi tiết sản phẩm — layout bám sát tncstore.vn

`product-detail.php` + `css/product-detail.css` đã dựng lại theo DOM gốc (đọc trực tiếp từ
`.product-detail` của trang thật), **không dùng tab nữa**:

```
.product-detail > .container
├── #breadcrumb.breadcrumb
└── .clearfix
    ├── .main-product-detail.background-white
    │   └── .content-main-product.d-flex
    │       ├── .product-images-detail   (290px)  #sync1 ảnh lớn + #sync2 thumbnail
    │       ├── .product-main-detail      (calc(100% - 728px)) tên · MSP · đánh giá · tóm tắt
    │       └── .product-main-right       (390px) khuyến mãi · giá · số lượng · nút · cam kết
    ├── .content-product-detail.d-flex     ← phần "dưới" mà chủ dự án yêu cầu
    │   ├── .content-left  (calc(100% - 447px))  #review.box-review
    │   └── .content-right (415px)               .box-specifications
    └── .product-similar.background-white
```

Nguyên tắc đã chốt khi dựng lại:
- **KHÔNG thêm `border-radius`** vào khối nào. Trang gốc chỉ có đúng một chỗ:
  `.avg-rate-item .box-percent { border-radius: 3px }` — giữ nguyên vì nó thuộc bản gốc.
- **KHÔNG thêm nút "Thêm vào giỏ"** ở trang chi tiết. Trang gốc chỉ có "Mua Ngay" và
  "Nhận tư vấn ngay"; thêm giỏ làm từ thẻ sản phẩm (`.js-quick-add`).
- Icon: trang gốc dùng Font Awesome, dự án chỉ có Bootstrap Icons → thay `fa …` bằng `bi …`
  tương đương. Đây là khác biệt duy nhất so với bản gốc.
- MSP hiển thị `products.sku` (`CPU-0001`), **không phải** `product_slug`. `ProductRepository`
  đã trả thêm trường `sku`.
- Khối `.box-related-news` (Tin tức liên quan) của trang gốc **đã bỏ** vì chưa có API tin
  theo danh mục. Không bịa dữ liệu.

**Biến màu của tncstore.vn** (đọc từ `getComputedStyle(document.documentElement)`, đừng đoán):

| Biến | Giá trị thật | Ghi chú |
|---|---|---|
| `--color-global` | `#3278f6` | **MÀU CHÍNH, XANH DƯƠNG — không phải đỏ** |
| `--color-red` | `#fb4e4e` | chỉ dùng cho giá, khuyến mãi, badge |
| `--grey-bold` | `#8d94ac` | chữ phụ |
| `--text-black` | `#29324e` | icon cam kết, chữ nút "Xem thêm" |
| `--blue` | **không định nghĩa** | rơi về fallback `#3278f6` |

⚠️ **`css/style.css` có rule global đè lên mọi `h2`:**
```css
h2 { text-transform: uppercase; border-bottom: 3px solid var(--color-global);
     display: inline-block; font-size: 24px !important; font-weight: 700 !important; }
```
Vì có `!important`, muốn `h2` khác 24px thì **phải dùng `!important`**, và phải tắc lại
`text-transform` + `border-bottom` vì rule đó không set hai thuộc tính đó. Đã dùng cho
`#review .title` và `.alert-mess`.

**Tiêu đề KHÔNG đồng nhất — đừng gộp chung** (đo thật):
| Khối | font-size | uppercase | gạch dưới |
|---|---|---|---|
| `.box-desciption .title` | 24px | có | 3px `#3278f6` |
| `.box-specifications .title` | 24px | có | 3px `#3278f6` |
| `.product-similar .title` | 24px | có | 3px `#3278f6` |
| **`#review .title`** | **21px** | **không** | **không** |
| **`.alert-mess`** | **21px** | **không** | **không** |

### 5.7 Mô tả sản phẩm rỗng — markup đã đọc từ trang thật

DB **chưa có cột mô tả**. Khi mô tả rỗng, tncstore render (đã đọc `outerHTML` từ 3 trang thật,
ví dụ `/man-hinh-msi-mag-275cqf-e18.html`):

```html
<div class="box-desciption background-white">
  <h2 class="title">Mô tả sản phẩm</h2>
  <h2 class="alert-mess" style="font-style: italic; color: gray; height: 100px; line-height: 100px;">
    Mô tả sản phẩm đang được cập nhật
  </h2>
</div>
```

Số đo thực tế của `.alert-mess`: **21px**, weight 700, italic, **không uppercase**,
`color: gray`, `height: 100px`, `margin: 0`. Khối vẫn hiện, không ẩn đi.

### 5.8 Ảnh logo thương hiệu

Ảnh **đã có sẵn** trong `assets/img/branding/` (143 file, tên đã đặt sẵn theo brand).
Bảng `brand_images` chỉ **link brand với file có sẵn đó**, không tải gì thêm, không hotlink.

| Việc | Đường dẫn |
|---|---|
| Bảng | `brand_images` trong `backend/database/schema.sql` (đã tạo trên DB thật) |
| Nạp/kiểm tra | `php backend/database/link_brand_images.php [--dry-run] [--check]` |
| Truy vấn | `LEFT JOIN brand_images bi ON bi.brand_id = b.brand_id` trong `ProductRepository` |
| API | `products.php` trả thêm `brandImage` |
| Giao diện | `.list-brand .item img` 50×32, thiếu ảnh thì lùi về hiện tên brand |

Quy tắc của script — **không đoán**: chỉ ghi dòng khi (1) brand có trong `brands`,
(2) tồn tại file ảnh khớp, (3) file là PNG/JPG/WebP hợp lệ. Brand không có file thì **không
ghi dòng**, giao diện tự lùi về tên brand.

3 brand có tên file lệch `brand_slug`, đã **đối chiếu bằng mắt với ảnh thật** chứ không đoán
theo tên (`LOGO_FILE_OVERRIDE` trong script):
`lian-li → lianli.png` (đọc ảnh: logo LIAN LI) ·
`cooler-master → coolermaster.png` (đọc ảnh: COOLER MASTER) ·
`human-motion → humanmotion.jpg` (đọc ảnh: chữ Humanmotion).

Kết quả: **24/25 brand có ảnh**, tất cả đều trỏ tới file tồn tại (đã `--check`).
Riêng **Crucial chưa có ảnh** — `assets/img/branding/` không có file nào tên `crucial.*`
(đã kiểm bằng cả `Get-ChildItem -Filter "*crucial*"` lẫn quét DB). Brand này có đúng 1 sản phẩm.

### 5.9 Ba lỗi đã tìm ra bằng đo DOM (đừng phá lại)

Cả ba đều do **đoán thay vì đo**, đã sửa và kiểm chứng lại bằng `getComputedStyle`:

**(1) Nút "Nhận tư vấn ngay" tràn khung.** Bootstrap đặt `p { margin-bottom: 1rem }`, còn trang gốc
không dùng Bootstrap nên `<p>` không có margin. Đo bản gốc: `p` = 15px, `margin-bottom: 0px`,
cao 20px; `<a>` cao **45px** (ta đã viết 48px). Sửa cả hai.
Nguyên nhân gốc rộng hơn: tôi từng thêm `@media (max-width: 1200px)` thu `.product-main-right`
còn 312px → nút còn 151px → nhãn xuống 2 dòng → tràn.
**Đã xác minh tncstore KHÔNG có media query nào cho `.product-detail`** (quét toàn bộ stylesheet) —
cột 290 / calc(100%-728) / 390 và container 1200 là cố định. Đã xoá breakpoint đó.
Còn lại `@media (max-width: 992px)` chỉ xếp dọc, là **thêm của dự án**, không phải bản gốc.

**(2) `.alert-mess` lệch vị trí.** `css/style.css` có rule global
`h2 { display: inline-block; text-transform: uppercase; border-bottom: 3px solid ...;
font-size: 24px !important; font-weight: 700 !important }`. Bản gốc đo được
`display: block`. Đã thêm `display: block` + `!important`.

**(3) Card sản phẩm không đều chiều cao** (337px vs 317px, lệch đúng 20px).
Đo thấy: card có `<del class="old-price">` → `.main-price` 48px; card không có → 28px.
`css/style.css` reserve cứng `.old-price { height: 20px }` nên hàng này phải **luôn tồn tại**.
Đếm trên trang danh mục tncstore: **41/41 card đều có `<del class="old-price">`**.
Đã sửa `productCard()` trong `js/api.js` render `<del>` kể cả khi rỗng. Sau đó 4/4 card = 337px.

Sau khi sửa, đối chiếu 16 chỉ số giữa trang ta và tncstore.vn: **0 chỉ số lệch**.

**(4) Bảng thông số nhạt.** Đo bản gốc: cả `.att-name` và `.att-value` đều `font-weight: 600`
(kể cả `<p>` bên trong ô giá trị), và `td` **không** set `line-height` (trang gốc không dùng
Bootstrap nên không bị `td { line-height: 1.5 }` như ta). Ta đang 400 / 21px.
Đã sửa thành `font-weight: 600` và `line-height: normal`. Sau đó khớp cả 6 chỉ số của bảng.

Hai sprite khác nhau, dùng chỗ khác nhau — đừng gộp:
- `assets/img/icon-star.png` (60×75) = dải **5 sao nhỏ**, dùng cho `.icon-star.star1..5`
  (thẻ sản phẩm, tên người viết).
- `assets/img/star-rating.png` (40×173, tải từ tncstore) = **1 sao lớn** 4 trạng thái xếp dọc,
  dùng cho `.sprite-star.rating-star` = ô chọn sao trong modal.

### 5.10 Breadcrumb 3 cấp + danh mục CHA — 2026-10-15

**Bằng chứng** (đọc `#breadcrumb` trên tncstore.vn, mỗi danh mục lấy 2 sản phẩm):

| Sản phẩm tncstore | Breadcrumb đọc được |
|---|---|
| `/cpu-intel-core-i9-14900kf-tray.html` | Trang chủ › **Linh kiện máy tính** › CPU - Bộ vi xử lý |
| `/bo-mach-chu-gigabyte-x870-aorus-stealth-am5-ddr5.html` | Trang chủ › **Linh kiện máy tính** › Mainboard - Bo mạch chủ |
| `/ram-teamgroup-vulcan-16gb-ddr5-black.html` | Trang chủ › **Linh kiện máy tính** › RAM - Bộ nhớ trong |
| `/card-man-hinh-asus-dual-radeon-rx-9050-8gb-oc-edition.html` | Trang chủ › **VGA - Card màn hình** ← chỉ 2 cấp |
| `/man-hinh-gaming-lg-...html` | Trang chủ › **Màn Hình Máy Tính** › **Màn Hình LG** (cấp lá theo brand) |
| `/ban-phim-gaming-asus-m705-...html` | Trang chủ › **Gaming Gears** › **Bàn Phím Cơ** (cấp lá theo loại) |

Kết luận cấu trúc (không phải 10 danh mục lá cùng hàng):
- **Linh kiện máy tính** → `cpu`, `mainboard`, `ram`, `hdd`, `case`, `psu`, `ssd`
- **Gaming Gears** → `phu-kien`
- **`vga` KHÔNG có cha** — bằng chứng: sản phẩm VGA trên tncstore chỉ có 2 cấp.
  Đừng "sửa cho đủ 3 cấp".
- **`display` KHÔNG có cha** — tncstore tách màn hình theo **brand** ("Màn Hình LG",
  "Màn Hình VSP"), còn ta gộp hết vào một danh mục lá tên "Màn hình máy tính". Gắn cha
  "Màn Hình Máy Tính" vào sẽ ra breadcrumb *"Màn Hình Máy Tính › Màn hình máy tính"* —
  hai tên trùng nghĩa. **Chủ dự án chốt bỏ cha (2026-10-15)** → 2 cấp, cùng cấu trúc với `vga`.
- Tên 10 danh mục lá đã có **trùng khớp** tên trang gốc, không sửa gì.

Kết quả cuối (đã đo trên DB thật, 9/9 danh mục):

| slug | cấp | breadcrumb |
|---|---|---|
| `cpu`, `mainboard`, `ram`, `hdd`, `case`, `psu`, `ssd` | 3 | Trang chủ › Linh kiện máy tính › *tên danh mục* |
| `phu-kien` | 3 | Trang chủ › Gaming Gears › Phụ kiện máy tính |
| `vga` | 2 | Trang chủ › VGA - Card màn hình |
| `display` | 2 | Trang chủ › Màn hình máy tính |

**DOM đo được trên trang gốc** (đã dựng lại y hệt):
- KHÔNG có tên sản phẩm trong breadcrumb; `<h1 class="name">` nằm ở `.product-main-detail`.
- Dấu phân cách `<i>` là **anh em của `<a>`** (không nằm trong `<a>`), và bị `display: none`
  ở `li` cuối — trang gốc vẫn render nó.
- `li` đầu có `class="home"`, chữ + icon màu `--grey-bold` (#8d94ac); các `li` sau chữ đen.
- Mọi `li` `float: left`, cao đúng 80px, `span` `font-size:14px; font-weight:600; line-height:80px`,
  icon `font-size:12px; margin:0 10px`.

Đối chiếu sau khi sửa (đo cả hai trang): khớp **10/10** chỉ số. Chỉ lệch **bề rộng `li`**:
gốc `125.34 / 147.84 / 108.41` px, ta `128.34 / 152.34 / 108.41` px — vì trang gốc dùng Font
Awesome (`fa fa-home`, `fa fa-angle-right`) còn dự án này **không nạp Font Awesome**, nên dùng
Bootstrap Icons (`bi bi-house`, `bi bi-chevron-right`) cùng `font-size:12px`. Tổng lệch ~7px.

**Thay đổi kèm theo:**
- `backend/database/schema.sql`: thêm **2** dòng danh mục cha (`category_id` 101–102).
- `backend/database/link_category_parents.php`: script idempotent (`--dry-run`, `--unlink`)
  gắn cha cho **8** danh mục lá. Hằng `ORPHAN_PARENTS` liệt kê cha đã bỏ
  (`man-hinh-may-tinh`) để script gỡ liên kết rồi xoá hẳn — không để lại dòng rác.
  Đã chạy trên DB thật: cha còn lại là `#11` (Linh kiện máy tính) và `#13` (Gaming Gears);
  `#12` (Màn Hình Máy Tính) đã bị xoá.
- `AdminProductRepository::categories()`: lọc danh mục LÁ. **Đừng dùng
  `WHERE parent_category_id IS NULL`** — điều đó giữ lại chính các danh mụu cha vì chúng cũng
  là top-level; đã đo: bộ lọc sai làm dropdown còn 4 mục thay vì 10. Đúng là `NOT EXISTS` con.
- `ProductRepository::buildFilters()`: filter `?category=` nhận **cả slug lá lẫn slug cha**
  (cha → mọi sản phẩm của các con). Không có nhánh này thì link breadcrumb cấp cha dẫn
  tới trang rỗng. MySQL native prepare không cho tái sử dụng placeholder nên dùng 2 tên.
- `ProductRepository` trả thêm `parentCategory` / `parentCategoryName` qua
  `LEFT JOIN categories pc ON pc.category_id = c.parent_category_id`.
- `product-detail.php` + `js/product-detail.js` (`renderBreadcrumb()`) render lại breadcrumb.
  CSS cũ `.product-detail #breadcrumb ol li h1` đã xoá (hết dùng).

**Hạn chế đã biết:** `products.php?category=<slug-cha>` hiện lọc đúng sản phẩm nhưng tiêu đề
rơi về fallback `"LINH KIỆN MÁY TÍNH"` và breadcrumb của trang đó rút còn 2 cấp, vì
`ProductRepository::categories()` chỉ trả 7 danh mục lá dùng cho bộ lọc. Muốn đúng 3 cấp
phải trả kèm danh mục cha qua API — **chưa làm**.

### 4.3 Đánh số lại catalog thông số — CẦN XIN PHÉP trước khi chạy
Xem mục 5.3.

### 4.4 Thông số cho các danh mục khác
`specification_definitions` mới có 8 khoá. Từng danh mục cần bộ khoá riêng — **đã cào được
danh sách thật** cho cpu (19), mainboard (19), ram (13), display (10), case (11), psu (8),
phu-kien (14); **ssd và vga chưa lấy được**. Chủ dự án chốt: *"lấy data cho vài cái thôi, sau này tự thêm sau"*.

### 4.5 Chuẩn hoá `products.model`
Giá trị lấy từ nguồn ghi **lộn xộn**: `12100F` · `i5-12400F` · `Core-i7 12700KF` ·
`Intel Core™ i5-13400F` · `CPU Intel Core i5 - 13600K`. Cần chốt có chuẩn hoá hay giữ nguyên.

---

## 5. CẠM BẪY — đọc trước khi sửa

### 5.1 Thẻ sản phẩm: DOM là bản sao nguyên văn, **đừng "cải tiến"**
CSS card nằm ở `css/style.css`, các rule `.product-item ...`, sao chép từ tncstore.
**Những thứ trang gốc KHÔNG có — đừng thêm lại:**
- zoom ảnh khi hover (chỉ có `transform: scale(1)`, không có rule `:hover`)
- rule cho `.main-price`
- `.product-tag` (trang gốc dùng `.box-sale`)
- bọc tên trong `<h3>` (trang gốc là `<a class="product-name line-clamp-2">` trực tiếp)
- lớp cột bọc ngoài thẻ

Giá gạch in dạng `2.889.000<u>đ</u>` (chữ **đ gạch chân**, không có dấu cách).
**KHÔNG có nút thêm vào giỏ trên thẻ.** Đếm trên tncstore.vn ngày 2026-10-15:
`.js-quick-add` = **0** ở trang chi tiết, trang danh mục (`/ram.html`, 41 card) và trang chủ.
Đã gỡ khỏi `productCard()` (`js/api.js`) + CSS + handler trong `js/cart.js`. Giỏ dùng qua
trang giỏ và nút Mua Ngay. **Đừng thêm lại.**

### 5.2 `js/cart.js` đọc DOM của thẻ — đổi DOM là hỏng giỏ
Các hook phải giữ: `.product-item`, `.product-name`, `.price`,
`.product-image img`, và `data-id` / `data-brand` / `data-slug` trên `.product-item`.

### 5.3 Thứ tự thông số: KHÔNG có `display_order`
`ProductRepository::findBySlug()` đã `ORDER BY d.specification_id`, nên **thứ tự = thứ tự chèn
định nghĩa**. Chủ dự án chốt là không cần cột `display_order`.

**Nhưng có vướng:** 8 định nghĩa cũ chiếm `specification_id` **1–8** và 292 dòng
`product_specifications` trỏ vào chúng, nên 4 khoá CPU cũ luôn xếp **trước** khoá CPU mới thêm
→ chưa giống thứ tự trang gốc. Muốn đúng thì phải **đánh số lại một lần**
(remap `product_specifications` + ghi lại catalog với id tường minh).
⚠️ **Việc này ghi đè dữ liệu thật → phải xin phép chủ dự án trước khi chạy.**

### 5.4 OPcache trên máy này — PHP sửa xong web vẫn trả bản cũ
`/etc/php/php.ini` bật `opcache.enable=On` với `opcache.revalidate_freq=180`, và `php -S`
chạy SAPI **cli-server** nên bị chi phối bởi `opcache.enable` (KHÔNG phải `enable_cli`).
→ Sau khi sửa file PHP, **khởi động lại `php -S`** hoặc chờ 180s, nếu không sẽ thấy output cũ
(một lần từng thấy thiếu đúng một thẻ `<script>`). Đừng nhầm với cache trình duyệt.

### 5.5 Cách cào dữ liệu tncstore — chỉ CPU là khớp
`https://www.tncstore.vn/<slug>.html` **chỉ hoạt động với slug CPU**.
Toàn bộ danh mục khác (vga, ram, ssd, case, psu, display, phu-kien) trả **404**;
`/tim?q=<tên>` (form tìm kiếm, tham số là `q`) **không** trả link sản phẩm cho ssd/vga.
Trang chi tiết đọc được: JSON-LD `aggregateRating{ratingValue,reviewCount}` và
`div.info-main-price` → `del.old-price` + `div.saleoff`.

### 5.6 zsh trong workspace này
- Không dùng `==` trần (equals expansion), không đặt biến tên `status`.
- Biến không tự tách từ: `M='cmd --flag'; $M -e ...` sẽ lỗi → viết lệnh đầy đủ hoặc `${=M}`.
- File dùng **tab** để indent, một số dòng có space cuối dòng.

---

## 6. Lệnh hữu ích

```bash
# Chạy web — PHP built-in dev server (cách chủ dự án dùng gần như luôn)
php -S localhost:8001 -t .

# Kiểm tra trước khi commit (chạy cả 3)
for f in $(rg -l '' --glob '*.php'); do php -l "$f" | rg -v 'No syntax errors'; done
for j in js/*.js admin/js/*.js; do node --check "$j"; done
for f in css/*.css; do o=$(rg -o '\{' $f | wc -l); c=$(rg -o '\}' $f | wc -l); [ "$o" = "$c" ] || echo "LECH $f"; done

# Xem comment còn lại của repo
rg -n -e '^\s*//' -e '^\s*/\*' --glob '!PLAN.md'

# Đếm sao / giá gạch đã có
mariadb --defaults-extra-file=/tmp/tnc-mysql.cnf -e \
  "SELECT COUNT(*) tong, SUM(review_count>0) co_danh_gia, SUM(list_price IS NOT NULL) co_gia_gach FROM \`tnc-db\`.products;"
```

> **Cổng chạy web:** trên máy chủ dự án, PHP dev server đang chạy ở **`localhost:8001`**
> (`C:\xampp\php\php.exe -S localhost:8001`, đã kiểm chứng bằng `Get-CimInstance Win32_Process`).
> `localhost:8080` không có gì — mở ra thấy `502 Bad Gateway`.
> `APP_URL` trong `.env` vẫn ghi `http://localhost:8000`; đó chỉ là giá trị tham chiếu,
> app không dùng nó để dựng link.
>
> **Apache chỉ là phương án dự phòng.** XAMPP Apache (`http://localhost/tnc/`) ít khi dùng.
> Nếu chuyển sang chạy bằng Apache thì phải kiểm lại link tương đối và `APP_URL`;
> còn với `php -S -t .` thì mọi đường dẫn tương đối (`backend/api/...`, `css/...`) chạy đúng ngay.

## 7. Thứ tự ưu tiên đề xuất

1. **Hệ thống đánh giá** (mục 4.2) — yêu cầu chính, độc lập với phần thông số. Chốt 2 câu hỏi bỏ ngỏ trước.
2. Reset đánh giá import (mục 4.1) — làm cùng lúc với #1.
3. Chuẩn hoá `model` (4.5) — nhanh.
4. Bộ thông số các danh mục (4.3 + 4.4) — nặng nhất, cần xin phép trước.

---

## 8. Nếu máy khác chạy **Windows**

Mục 2 và mục 6 ở trên viết cho Linux. Trên Windows, **các điểm sau khác** — đọc hết trước khi làm.

### 8.1 Cách an toàn nhất để kiểm tra DB: bỏ qua CLI, dùng PHP luôn
→ Còn cách gọn hơn nữa, chạy giống nhau trên cả hai hệ: **Python + `python-dotenv`**,
xem **mục 2.1**.
Trên Windows rất dễ gặp **client Oracle MySQL** (hiểu `ssl-mode`) hoặc **client MariaDB** (không hiểu).
Thay vì đoán, dùng chính `database()` mà app đang dùng — nó đã cấu hình TLS đúng rồi:

```powershell
php -r "require 'backend/bootstrap.php'; var_dump(database()->query('SELECT COUNT(*) FROM products')->fetchColumn());"
```

Nếu vẫn muốn dùng CLI thì kiểm tra trước:

```powershell
mysql --version        # nếu ghi 'MariaDB' -> dùng ssl-ca + ssl-verify-server-cert
                       # nếu ghi 'MySQL'   -> dùng ssl-mode=VERIFY_CA
```

File cấu hình tạm đặt ở `%TEMP%\tnc-mysql.cnf` (thay cho `/tmp/tnc-mysql.cnf`):

```ini
[client]
host=<DB_HOST trong backend/config/.env>
port=<DB_PORT>
user=<DB_USERNAME>
password=<DB_PASSWORD>
ssl-ca=<đường dẫn backend\config\ca.pem>
ssl-verify-server-cert=1
```

> `backend/config/database.php` **đã xử lý được đường dẫn Windows** (`C:\...`) và đường dẫn tương đối
> (tự ghép với `backend/config/`), nên không cần sửa gì trong code.

Xoá file sau khi dùng (Windows không có `shred`): `Remove-Item $env:TEMP\tnc-mysql.cnf`

### 8.1b Dùng mysql client của XAMPP — được, nhưng đọc kỹ 3 điều này

Đường dẫn (không có trên `PATH` mặc định, phải gọi full path):

```powershell
C:\xampp\mysql\bin\mysql.exe --defaults-extra-file="$env:TEMP\tnc-mysql.cnf" -e "SELECT VERSION(), DATABASE();"
```

**(1) Client của XAMPP rất có thể là MariaDB, không phải Oracle MySQL.**
XAMPP bản mới đóng gói **MariaDB**, nên nó **từ chối `ssl-mode`** — đúng lỗi máy Linux này đã gặp:

```
mysql: unknown variable 'ssl-mode=VERIFY_CA'
```

→ **Luôn kiểm trước khi viết câu lệnh:**

```powershell
C:\xampp\mysql\bin\mysql.exe --version
# có chữ "MariaDB" -> dùng  ssl-ca=...  + ssl-verify-server-cert=1
# có chữ "MySQL"   -> dùng  ssl-mode=VERIFY_CA
```

**(2) `--defaults-extra-file` phải đứng ĐẦU TIÊN** trong câu lệnh, nếu không client bỏ qua file đó
(đây là quy tắc của cả MySQL lẫn MariaDB client).

**(3) ⚠️ ĐỪNG NHẦM HAI DATABASE — cạm bẫy dễ mắc nhất khi có XAMPP**

| | XAMPP | Dự án này |
|---|---|---|
| Chạy ở | `localhost:3306`, **cục bộ trên máy bạn** | **Aiven remote** |
| User | thường `root`, mật khẩu rỗng | `avnadmin`, lấy trong `backend/config/.env` |
| Schema | do bạn tự tạo, thường rỗng | `tnc-db` (có gạch nối) |
| Dữ liệu | **không có** | 138 sản phẩm, 242 ảnh… |

DB của dự án **nằm trên Aiven**, không phải trên XAMPP. Luôn lấy `DB_HOST` / `DB_PORT` (`20644`)
từ `backend/config/.env`, **không bao giờ hardcode `localhost`**.

Nếu agent trỏ nhầm vào `localhost` của XAMPP, nó sẽ thấy DB **rỗng** rồi kết luận sai kiểu
"chưa có sản phẩm nào" / "chưa có bảng `products`" — trong khi thực tế chỉ là đang nhìn sai chỗ.
Đó là lỗi tệ nhất có thể xảy ra ở đây.

XAMPP chỉ nên dùng như **client** để kết nối ra Aiven. Đừng import `schema.sql` vào XAMPP rồi
coi như đã xong việc — dữ liệu thật không nằm ở đó.

### 8.2 Đừng quên `git config core.autocrlf`
Repo dùng **LF**. Nếu máy Windows để `core.autocrlf=true` (mặc định của Git for Windows), mọi file sẽ bị đổi
LF → CRLF và `git diff` sẽ **báo sửa toàn bộ repo** dù không sửa gì.

```powershell
git config core.autocrlf input      # hoặc false
git status --short                  # phải sạch; nếu hiện hàng trăm file M thì xử lý ngay
```

### 8.3 Lệnh kiểm tra (tương đương mục 6)

```powershell
# php -l toàn bộ, chỉ in file lỗi
Get-ChildItem -Recurse -Filter *.php | ForEach-Object {
  $o = php -l $_.FullName
  if ($o -notmatch 'No syntax errors') { $o }
}

# node --check
Get-ChildItem js, admin/js -Filter *.js | ForEach-Object { node --check $_.FullName }

# cân bằng ngoặc CSS
Get-ChildItem css -Filter *.css | ForEach-Object {
  $t = Get-Content $_.FullName -Raw
  $o = ([regex]::Matches($t, '\{')).Count
  $c = ([regex]::Matches($t, '\}')).Count
  if ($o -ne $c) { "LECH $($_.Name): {=$o }=$c" }
}

# chạy web (PHP dev server — cách dùng chính; Apache XAMPP chỉ là dự phòng)
php -S localhost:8001 -t .
```

`rg`, `php`, `node` phải có trên `PATH`. Nếu chưa có `rg`: `winget install BurntSushi.ripgrep.MSVC`.

### 8.3b Kiểm tra giao diện bằng trình duyệt — KHÔNG cần chrome-devtools MCP

Máy này **không cài `chrome-devtools-mcp`**, và không có MCP server nào được cấu hình
(đã kiểm: `list_mcp_resources` trả rỗng, `~/.config/opencode/` không có `opencode.json`,
global npm không có package nào liên quan).

Kiểm tra giao diện vẫn làm được bằng **browser tool built-in** của opencode — nó nói chuyện
Chrome DevTools Protocol, đủ dùng cho việc kiểm tra này:

```js
const tab = await tools.browser.tabs.open({ url: "http://localhost:8001" });
await tools.browser.network.list({ tabID: tab.id });            // request + status
await tools.browser.console({ tabID: tab.id, level: "error" }); // lỗi JS
await tools.browser.evaluate({ tabID: tab.id, script: "document.querySelectorAll('.product-item').length" });
await tools.browser.screenshot({ tabID: tab.id });
```

Ba lưu ý đã gặp thật:
- `evaluate` nhận tham số tên **`script`**, không phải `expression`.
- `wait` nhận **`condition`** (`"load" | "text" | "textGone"`), không nhận `time`.
- `screenshot` cần tab **visible**: mở tab với `focus: true` và để cửa sổ desktop hiện ra,
  nếu không sẽ lỗi `browser.screenshot failed. Screenshot needs a visible tab.`
- Script chạy trong cùng một scope giữa các lần `evaluate`, nên khai báo `const btn` ở lần
  trước sẽ gây `SyntaxError: Identifier 'btn' has already been declared`.
  Bọc mọi đoạn kiểm tra vào `(() => { ... })()`.
- Trong `evaluate` của Code Mode **không có `setTimeout`**. Muốn chờ thì dùng
  `await new Promise(r => setTimeout(r, 800))` ngay trong script trang.

### 8.4 OPcache — cạm bẫy y hệt, nhưng **máy này KHÔNG bị**
`php -S` chạy SAPI **cli-server**, bị chi phối bởi `opcache.enable` (KHÔNG phải `enable_cli`).
Sửa file PHP xong web vẫn trả bản cũ tới ~3 phút → **khởi động lại `php -S`** sau mỗi lần sửa PHP.

> **Đã kiểm trên máy này: KHÔNG có OPcache.** `php -r "var_dump(extension_loaded('Zend OPcache'))"`
> trả `bool(false)`. Nên sửa PHP xong reload trình duyệt là thấy bản mới, **không cần restart server**.
> Nếu sau này web trả bản cũ thì kiểm tra lại lệnh trên trước đã.

```powershell
php -r "var_dump(extension_loaded('Zend OPcache'), ini_get('opcache.enable'));"
```

### 8.4b Chỉnh file trên Windows — đừng dùng `Set-Content`
`Set-Content -Encoding UTF8` của PowerShell 5 **thêm BOM** và đổi LF thành CRLF;
`-Encoding Ascii` thì **xoá hết tiếng Việt** trong comment (thành `???`).
Đã làm hỏng `css/product-detail.css` một lần, phải `git checkout` rồi sửa lại bằng tool edit.

- Sửa file bằng **tool `edit`** của agent, hoặc bằng script PHP/Python ghi rõ encoding.
- Kiểm tra sau khi sửa: file không có BOM (3 byte đầu ≠ `EF BB BF`),
  `git diff --stat` không báo "sửa toàn bộ file".
- Working tree của repo này **dùng CRLF** (do `core.autocrlf=true` của Git for Windows quy định),
  nên thấy CRLF trong file là bình thường, không phải lỗi.

### 8.5 Những thứ KHÔNG cần làm gì khác
- Cú pháp PHP/JS/CSS/SQL trong dự án **không có gì riêng cho Linux**.
- `schema.sql`, `apply.php`, `sync_tncstore_stats.php` chạy y nhau trên Windows.
- Thư mục `backend/database/migrations/` **đã bị xoá** — đừng tạo lại.

### 8.6 Cần để ý khi sửa file
- File dùng **tab** để indent. Đừng để formatter tự đổi sang space.
- Giữ **LF**. Nếu editor tự thêm CRLF thì `git diff` sẽ nhiễu toàn bộ file.
- Script tạm ở mục 2 và 6 (`mk-tnc-cnf.php`, `comment-count2.py`) **không nằm trong repo** —
  chúng nằm ở `/tmp` của máy Linux. Trên Windows cứ viết lại theo mô tả trong tài liệu này,
  hoặc bỏ qua và dùng PHP/PDO như mục 8.1.

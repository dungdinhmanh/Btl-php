# PLAN — bàn giao dự án Btl-php

> Cập nhật: **2026-10-14**. Mọi số liệu dưới đây do **chạy lệnh thật** mà có, không phải suy đoán.
> Nếu bạn (agent) thấy tài liệu này khác với code/DB hiện tại → **tin code/DB**, và sửa lại file này.

## 0. Quy tắc chống hallucinate

1. **Đừng tin trí nhớ, hãy chạy lệnh.** Mọi điều về DB phải kiểm bằng `mariadb`/`PDO`, mọi điều về git phải bằng `git`.
2. `information_schema.table_rows` là **số ước lượng của InnoDB và nói dối** (từng báo `order_statuses = 0` nhưng thật là 5). Luôn `COUNT(*)`.
3. Tài liệu cũ trong repo có chỗ **sai**: `backend/README.md`, `README.MD`, `schema.sql` ghi database là `tnc_store`, nhưng DB thật là **`tnc-db`** (có gạch nối).
4. Đừng giả định tính năng nào đã có. Khoản "chưa làm" ở mục 4 là **chưa có một dòng code nào**.

---

## 1. Trạng thái repo (đã kiểm chứng)

```
branch            : main
commit mới nhất   : 7b1bfe9  Thêm products.model + dọn comment thừa
commit trước đó   : b115043  Thẻ sản phẩm: dựng lại theo DOM/CSS gốc tncstore.vn
origin/main       : 024d163  (main đang hơn 2 commit, CHƯA PUSH)
working tree      : sạch
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

**Không có bảng nào tên chứa `review`** (đã kiểm bằng `information_schema`).

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

---

## 4. CHƯA LÀM (đừng tưởng là đã có)

### 4.1 Reset đánh giá import — đã chốt, CHƯA chạy
Chủ dự án đã quyết định **reset toàn bộ** `rating_avg` / `review_count` (36 SP đang giữ điểm `5.0`
nhập từ tncstore, không phân biệt được gì). **Việc này chưa được thực hiện.**

```sql
UPDATE `tnc-db`.products SET rating_avg = 0.0, review_count = 0;
```
⚠️ Sau khi reset, thẻ sản phẩm sẽ **không còn hiện sao** cho tới khi có đánh giá thật.
⚠️ `backend/database/sync_tncstore_stats.php` hiện vẫn ghi cả `rating_avg`/`review_count`;
khi làm hệ thống đánh giá thật thì phải sửa script này để **chỉ đồng bộ `list_price`**.

### 4.2 Hệ thống đánh giá — chưa có dòng code nào
Yêu cầu **đã chốt** với chủ dự án (không cần hỏi lại):
- **Khách vãng lai KHÔNG cần đăng nhập vẫn đánh giá được.**
- **Bắt buộc nhập đủ 3 thứ:** điểm đánh giá + **họ tên** + **số điện thoại**.
- Có **reply phân cấp**, chủ dự án muốn thử **đệ quy**.
- `user_id` nullable (đăng nhập thì gắn vào, không thì để NULL và dùng tên/SĐT).

Việc cần làm (chưa có file nào):
| Mảnh | Đường dẫn đề xuất |
|---|---|
| Bảng `product_reviews` | gộp vào `backend/database/schema.sql` |
| Repository | `backend/src/Repositories/ReviewRepository.php` |
| API ghi (POST/PUT/DELETE) | `backend/api/reviews.php` |
| API đọc | `backend/api/products.php?reviews=1` hoặc endpoint riêng |
| UI | `product-detail.php` + `js/product-detail.js` + CSS mới |
| Admin duyệt/xoá | chưa có trang |

Hai điểm **còn bỏ ngỏ**, phải hỏi chủ dự án trước khi code:
(a) có chống trùng theo số điện thoại không (một người 1 đánh giá / sản phẩm)?
(b) reply thì ai được trả lời — chỉ admin, hay khách trả lời khách?

**Dùng `AVG()` của DB được, nhưng đã đo trên chính DB này:**
- `AVG()` trên tập rỗng trả **`NULL`**, không phải 0 → phải bọc `COALESCE(..., 0)`
  vì `rating_avg` là `NOT NULL`.
- PDO trả `NEWDECIMAL` dưới dạng **string** → phải `(float)` cast.
- Khuyến nghị: cache vào `products.rating_avg`/`review_count` trong **cùng transaction**
  với thao tác thêm/sửa/xoá đánh giá. Đọc chỉ `SELECT` trên `products` nên không chậm thêm.
  Kèm một script "tính lại toàn bộ" để đồng bộ khi nghi ngờ lệch.

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
Nút thêm vào giỏ là `.js-quick-add` — **phần thêm của dự án này**, trang gốc không có;
`showAddToCart: false` thì markup khớp trang gốc.

### 5.2 `js/cart.js` đọc DOM của thẻ — đổi DOM là hỏng giỏ
Các hook phải giữ: `.product-item`, `.js-quick-add`, `.product-name`, `.price`,
`.product-image img`, và `data-id` / `data-brand` / `data-slug` trên `.product-item`.
Nút giỏ nằm trong `<a class="product-image">` nên handler **phải `preventDefault()`**.

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
# Chạy web
php -S 127.0.0.1:8080 -t .

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

# chạy web
php -S 127.0.0.1:8080 -t .
```

`rg`, `php`, `node` phải có trên `PATH`. Nếu chưa có `rg`: `winget install BurntSushi.ripgrep.MSVC`.

### 8.4 OPcache — cạm bẫy y hệt, không phải chỉ Linux
`php -S` chạy SAPI **cli-server**, bị chi phối bởi `opcache.enable` (KHÔNG phải `enable_cli`).
Sửa file PHP xong web vẫn trả bản cũ tới ~3 phút → **khởi động lại `php -S`** sau mỗi lần sửa PHP.

```powershell
php -i | Select-String -Pattern 'opcache' | Select-Object -First 8
```

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

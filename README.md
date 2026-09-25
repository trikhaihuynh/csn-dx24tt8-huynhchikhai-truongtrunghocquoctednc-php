# csn-dx24tt8-huynhchikhai-truongtrunghocquoctednc-php
# XÂY DỰNG WEBSITE TRƯỜNG TRUNG HỌC QUỐC TẾ DNC

## 1. Thông tin đồ án
- **Loại đồ án:** Đồ án Thực tập Cơ sở ngành
- **Sinh viên thực hiện:** Huỳnh Chí Khải
- **MSSV:** 170124955
- **Lớp:** DX24TT8
- **Khóa:** 2024 - 2028
- **Giảng viên hướng dẫn:** Lê Phong Dũ
- **Đơn vị:** Trường Kỹ thuật và Công nghệ - Đại học Trà Vinh

## 2. Giới thiệu
Đề tài **“Xây dựng Website Trường Trung học Quốc tế DNC”** hướng đến một website phục vụ giới thiệu, quản lý thông tin và hỗ trợ tương tác giữa nhà trường với phụ huynh, học sinh.

Website dự kiến cung cấp thông tin trường, chương trình đào tạo, tin tức - hoạt động, thư viện hình ảnh và đăng ký nhập học trực tuyến; đồng thời có khu vực quản trị nội dung và dữ liệu đăng ký.

## 3. Mục tiêu
- Phân tích yêu cầu website Trường Trung học Quốc tế DNC.
- Thiết kế kiến trúc, cơ sở dữ liệu và giao diện.
- Định hướng các chức năng giới thiệu trường, chương trình đào tạo, hình ảnh, tin tức - hoạt động.
- Thiết kế chức năng đăng ký nhập học trực tuyến.
- Thiết kế khu vực quản trị nội dung và dữ liệu đăng ký.
- Làm cơ sở triển khai website bằng PHP/MySQL ở giai đoạn phát triển.

## 4. Người dùng và chức năng dự kiến
### Phụ huynh / Học sinh
- Xem thông tin trường.
- Xem chương trình đào tạo.
- Xem tin tức - hoạt động.
- Xem thư viện hình ảnh.
- Đăng ký nhập học trực tuyến.

### Quản trị viên
- Đăng nhập khu vực quản trị.
- Quản lý nội dung website.
- Quản lý chương trình đào tạo, tin tức và hình ảnh.
- Quản lý dữ liệu đăng ký nhập học.

> Cập nhật 26/09/2026: các chức năng trên đã được cài đặt và kiểm thử (xem mục 7 và mục 11).

## 5. Công nghệ dự kiến
| Thành phần | Công nghệ | Mục đích |
|---|---|---|
| Backend | PHP | Xử lý nghiệp vụ phía máy chủ |
| Database | MySQL | Lưu trữ dữ liệu |
| Frontend | HTML, CSS, JavaScript | Giao diện và tương tác |
| Quản lý mã nguồn | Git/GitHub | Theo dõi phiên bản và tiến độ |
| Môi trường | XAMPP hoặc tương đương | Chạy PHP/MySQL |

## 6. Yêu cầu chức năng
| Mã | Chức năng | Mô tả |
|---|---|---|
| F01 | Giới thiệu trường | Hiển thị thông tin tổng quan |
| F02 | Hình ảnh | Hiển thị hình ảnh hoạt động |
| F03 | Chương trình đào tạo | Cung cấp thông tin chương trình |
| F04 | Tin tức - hoạt động | Hiển thị tin bài và hoạt động |
| F05 | Đăng ký nhập học | Tiếp nhận đăng ký trực tuyến |
| F06 | Quản trị nội dung | Quản lý dữ liệu website |

## 7. Phân tích, thiết kế và cài đặt
### 7.1. Phân tích và thiết kế
Các nội dung đã thực hiện:
- Phân tích yêu cầu chức năng và phi chức năng.
- Xác định hai nhóm người dùng.
- Xây dựng Use Case tổng quát.
- Đặc tả Use Case đăng ký nhập học.
- Thiết kế kiến trúc client-server.
- Phân tích và thiết kế cơ sở dữ liệu.
- Thiết kế bố cục giao diện người dùng và khu vực quản trị.

Các bảng dữ liệu đã cài đặt (MySQL, `utf8mb4_unicode_ci`): `nguoi_dung`, `tin_tuc`, `chuong_trinh`, `hinh_anh`, `dang_ky_nhap_hoc` và bảng bổ sung `cai_dat` (thông tin liên hệ, giới thiệu, mạng xã hội của trường). So với thiết kế ban đầu, `dang_ky_nhap_hoc` dùng khóa ngoại `chuong_trinh_id` thay cho cột `chuong_trinh`; bổ sung các cột `slug`, `tom_tat`, `luot_xem`, `album`, `thu_tu`.

### 7.2. Kiến trúc cài đặt
- PHP 8.2 thuần, không dùng framework, tổ chức theo mô hình MVC: `public/index.php` (front controller) → `app/routes.php` → Controller → Model (PDO) → View.
- Truy cập CSDL bằng PDO với prepared statement; mật khẩu băm bằng `password_hash()`; mọi form POST có token CSRF; dữ liệu xuất ra HTML được escape; tệp tải lên được kiểm tra kiểu MIME thật và đổi tên ngẫu nhiên.
- Giao diện HTML/CSS/JavaScript thuần, responsive; font Inter từ Google Fonts.
- Khu vực quản trị đăng nhập bằng session, phân quyền hai vai trò: `admin` (toàn quyền) và `bien_tap` (không quản lý tài khoản và cài đặt).

### 7.3. Các module đã cài đặt
| Module | Chức năng | Nội dung | Route chính | Trạng thái |
|---|---|---|---|---|
| M0 | Nền tảng | Lõi ứng dụng (router, view, CSDL, CSRF, validate, upload), layout công khai, trang lỗi 404/403/500 | — | Hoàn thành |
| M1 | F01 | Trang chủ, giới thiệu, liên hệ (dữ liệu lấy từ `cai_dat`) | `/`, `/gioi-thieu`, `/lien-he` | Hoàn thành |
| M2 | F03 | Chương trình đào tạo phía công khai | `/chuong-trinh`, `/chuong-trinh/{slug}` | Hoàn thành |
| M3 | F04 | Tin tức phía công khai: phân trang, chi tiết, bài nháp trả 404 | `/tin-tuc`, `/tin-tuc/{slug}` | Hoàn thành |
| M4 | F05 | Form đăng ký nhập học 4 nhóm thông tin, validate, tải học bạ, trang thành công | `/dang-ky-nhap-hoc`, `/dang-ky-nhap-hoc/thanh-cong` | Hoàn thành |
| M5 | F06 | Đăng nhập, đăng xuất, dashboard thống kê, chặn truy cập khi chưa đăng nhập | `/admin/login`, `/admin/logout`, `/admin` | Hoàn thành |
| M6 | F04, F06 | Quản lý tin tức: thêm, sửa, xóa, ảnh đại diện, tìm kiếm, phân trang | `/admin/tin-tuc` | Hoàn thành |
| M7 | F03, F06 | Quản lý chương trình đào tạo: thêm, sửa, xóa, ẩn/hiện, thứ tự | `/admin/chuong-trinh` | Hoàn thành |
| M8 | F02, F06 | Thư viện ảnh (lọc theo album, xem ảnh lớn) và quản lý ảnh (tải nhiều ảnh một lần) | `/hinh-anh`, `/admin/hinh-anh` | Hoàn thành |
| M9 | F05, F01, F06 | Xử lý hồ sơ đăng ký (đổi trạng thái, ghi chú), cài đặt thông tin trường, quản lý tài khoản | `/admin/dang-ky`, `/admin/cai-dat`, `/admin/tai-khoan` | Hoàn thành |
| M10 | — | Hoàn thiện giao diện (responsive, lightbox, tin liên quan, trang lỗi) | `/hinh-anh`, `/tin-tuc/{slug}` | Hoàn thành (thực hiện trong M0, M3, M8) |

"Hoàn thành" nghĩa là module đã chạy được trên môi trường Docker và đạt các ca kiểm thử ở mục 11.

## 8. Cấu trúc repository
```text
/
├── README.md
├── .gitignore
├── progress-report/               # Báo cáo tiến độ hằng tuần
├── docker/                        # Môi trường chạy
│   ├── docker-compose.yml         # web (PHP 8.2 + Apache), db (MySQL 8.0), phpMyAdmin
│   ├── Dockerfile
│   ├── .env.example               # Mẫu biến môi trường (.env không commit)
│   ├── README.md                  # Hướng dẫn chạy chi tiết
│   ├── apache/000-default.conf    # DocumentRoot = src/public
│   ├── php/php.ini
│   └── mysql/init/
│       ├── 01-schema.sql          # Tạo CSDL dnc_school và 6 bảng
│       └── 02-seed.sql            # Dữ liệu mẫu
└── src/
    ├── public/                    # Web root
    │   ├── index.php              # Front controller
    │   ├── .htaccess
    │   ├── assets/
    │   │   ├── css/               # app.css, admin.css
    │   │   ├── js/                # app.js
    │   │   └── img/               # logo, ảnh minh họa, gallery/
    │   └── uploads/               # Tệp tải lên (không commit, chặn thực thi PHP)
    └── app/
        ├── config/config.php
        ├── routes.php
        ├── core/                  # Auth, Controller, Csrf, Database, Model, Paginator,
        │                          # Router, Upload, Validator, View, helpers.php
        ├── controllers/           # Home, Program, News, Gallery, Admission
        │   └── admin/             # Admin (lớp cơ sở), Auth, Dashboard, News, Program,
        │                          # Gallery, Admission, Setting, User
        ├── models/                # Admission, GalleryImage, News, Program, Setting, User
        └── views/
            ├── layouts/           # public.php, admin.php, partials/ (header, nav, footer, flash)
            ├── partials/          # pagination.php
            ├── public/            # home, about, contact, admission, admission-success,
            │                      # gallery, programs/, news/
            ├── admin/             # login, dashboard, news/, programs/, gallery/,
            │                      # admissions/, settings/, users/
            └── errors/            # 403.php, 404.php, 500.php
```

## 9. Tiến độ
| Tuần | Thời gian | Nội dung | Trạng thái |
|---|---|---|---|
| 1 | 16/08/2026 - 23/08/2026 | Tổng quan đề tài và lý thuyết liên quan | Hoàn thành |
| 2 | 24/08/2026 - 30/08/2026 | Chọn lọc lý thuyết áp dụng | Hoàn thành |
| 3 | 31/08/2026 - 06/09/2026 | Nghiên cứu chuyên sâu, phân tích mô hình và dữ liệu | Hoàn thành theo giai đoạn |
| 4 | 07/09/2026 - 13/09/2026 | Tiếp tục phân tích và thiết kế | Hoàn thành theo giai đoạn |
| 5 | 14/09/2026 - 20/09/2026 | Viết và chuẩn hóa báo cáo | Đang thực hiện |
| 6 | 21/09/2026 - 27/09/2026 | Xây dựng chương trình và kiểm thử | Hoàn thành M0–M10, kiểm thử TC01–TC06 đạt |

### Mốc quan trọng
- **02/09/2026:** Đã nộp đề cương báo cáo đồ án.
- **16/09/2026:** Đang thực hiện tuần 5.
- **Phát triển website:** Hoàn thành các chức năng F01–F06 (26/09/2026).
- **Kiểm thử website:** Hoàn thành TC01–TC06, kiểm thử bảo mật cơ bản (26/09/2026).

Chi tiết tiến độ được lưu trong thư mục [`progress-report`](./progress-report/).

## 10. Cài đặt và chạy
### Cách 1: Docker (khuyến nghị)
Yêu cầu: Docker Desktop (có Docker Compose).

```bash
cd docker
cp .env.example .env          # lần đầu; đổi cổng trong .env nếu bị trùng
docker compose up -d --build  # chạy web, db, phpmyadmin
```

| Dịch vụ | Địa chỉ | Ghi chú |
|---|---|---|
| Website | http://localhost:8080 | Trang công khai |
| Trang quản trị | http://localhost:8080/admin | Đăng nhập bằng tài khoản mẫu bên dưới |
| phpMyAdmin | http://localhost:8081 | user `dnc` / `dnc123` |
| MySQL | localhost:3306 | database `dnc_school` |

Lần chạy đầu, MySQL tự tạo bảng từ `docker/mysql/init/01-schema.sql` và nạp dữ liệu mẫu từ `02-seed.sql`. Muốn tạo lại CSDL từ đầu: `docker compose down -v` rồi `docker compose up -d`. Các lệnh khác xem [`docker/README.md`](./docker/README.md).

### Cách 2: XAMPP
1. Trỏ `DocumentRoot` (hoặc một VirtualHost) tới thư mục `src/public`, bật `mod_rewrite` và `AllowOverride All`.
2. Trong phpMyAdmin, import lần lượt `docker/mysql/init/01-schema.sql` rồi `docker/mysql/init/02-seed.sql`.
3. Ứng dụng đọc cấu hình từ biến môi trường; nếu không có thì dùng mặc định `localhost` / `root` / mật khẩu rỗng / `dnc_school`. Khai báo `SetEnv APP_URL http://localhost` (đúng địa chỉ đang chạy) trong VirtualHost để các liên kết được tạo đúng.

### Tài khoản mẫu
| Vai trò | Email | Mật khẩu |
|---|---|---|
| Quản trị viên | `admin@dnc.edu.vn` | `Admin@123` |

Đây là dữ liệu mẫu để chạy thử; cần đổi mật khẩu trước khi đưa lên môi trường thật. Khi demo nên đặt `APP_ENV=production` trong `docker/.env` để không hiển thị chi tiết lỗi.

## 11. Kết quả kiểm thử
Môi trường: Docker (PHP 8.2 + Apache, MySQL 8.0), http://localhost:8080. Ngày chạy: 26/09/2026. Dữ liệu thử được tạo qua form thật và đã xóa sau khi kiểm.

### Bảng 4.1. Kịch bản kiểm thử chức năng
| Mã | Chức năng | Dữ liệu kiểm thử | Kết quả mong đợi | Kết quả thực tế |
|---|---|---|---|---|
| TC01 | Trang chủ | Truy cập URL `/` | Trang chủ hiển thị đủ các khối, không lỗi PHP | Đạt. HTTP 200, không có lỗi PHP. Đủ các khối: banner, "Vì sao chọn DNC" (3 thẻ), chương trình đào tạo, đời sống học sinh (4 ảnh), tin tức mới nhất (chỉ tin công khai), liên kết nhanh, nút "Đăng ký ngay", footer lấy từ bảng `cai_dat`. Không tràn ngang ở 375, 1280 và 1366 px |
| TC02 | Tin tức | Từ `/tin-tuc` chọn bài "Khai giảng năm học mới 2026-2027"; mở thêm `/tin-tuc/bai-viet-nhap` | Hiển thị chi tiết; bài nháp trả 404 | Đạt. Bài công khai trả 200, có tiêu đề, ngày đăng 22/09/2026, nội dung, lượt xem tăng 1. Bài nháp trả 404 với trang "Không tìm thấy trang", không lộ nội dung; `/tin-tuc` không liệt kê bài nháp |
| TC03 | Đăng ký | Đủ 4 nhóm thông tin (học sinh, phụ huynh, học vấn kèm tệp học bạ PDF, thông tin thêm), email `qa+tc03@test.local` | Lưu bản ghi `trang_thai = moi`, báo thành công | Đạt. Chuyển tới `/dang-ky-nhap-hoc/thanh-cong` với thông báo "Gửi đăng ký thành công". Bản ghi mới có đủ 15 cột, `trang_thai = moi`, tệp học bạ được đổi tên ngẫu nhiên |
| TC04 | Đăng ký | (a) Thiếu email; (b) email `abc` | Báo lỗi, giữ dữ liệu đã nhập, không tạo bản ghi | Đạt. (a) báo "Vui lòng nhập Email."; (b) báo "Email không đúng định dạng.". Họ tên, tên phụ huynh, điện thoại, khối lớp, chương trình vẫn được giữ. Số bản ghi không đổi |
| TC05 | Đăng nhập admin | `admin@dnc.edu.vn` với mật khẩu sai; email không tồn tại; mật khẩu có khoảng trắng đầu/cuối | Từ chối, không lộ thông tin tài khoản | Đạt. Cả hai ca sai đều nhận cùng thông báo "Email hoặc mật khẩu không đúng.", `/admin` vẫn chuyển về trang đăng nhập. Mật khẩu có khoảng trắng bị từ chối. Đăng nhập đúng thì mã phiên được cấp mới |
| TC06 | Quản lý tin | Thêm tin "[QA] Tin kiểm thử", nội dung `<p>abc</p>`, trạng thái công khai, ảnh PNG | Tin được lưu, hiện trong danh sách và trang công khai | Đạt. Lưu thành công, slug `qa-tin-kiem-thu`, gắn người tạo, ảnh được lưu. Tin có trong danh sách quản trị, `/tin-tuc`, khối tin mới ở trang chủ; trang chi tiết trả 200. Đã xóa sau khi kiểm |

### Bảng 4.2. Kiểm thử bảo mật cơ bản
| Mã | Nội dung | Cách kiểm | Kết quả mong đợi | Kết quả thực tế |
|---|---|---|---|---|
| SEC01 | CSRF | Gửi POST không có `_token` hoặc token sai tới form đăng ký, đăng nhập, thêm tin; gửi yêu cầu xóa không có token | Từ chối (403), không tạo bản ghi | Đạt. Tất cả trả 403 "Yêu cầu không hợp lệ", không tạo phiên đăng nhập, số bản ghi không đổi |
| SEC02 | XSS | Nhập `<script>alert(1)</script>` vào họ tên đăng ký và tiêu đề tin công khai | Hiển thị dạng văn bản, không thực thi | Đạt. Mọi trang liên quan (form, dashboard, danh sách và chi tiết đăng ký, danh sách tin, trang chủ, chi tiết tin) đều hiển thị dạng `&lt;script&gt;`, không thực thi |
| SEC03 | SQL injection | `?q=' OR 1=1 --`, `?page=1'`, `?album=' OR 1=1 --`, slug có payload, tham số lọc ở các trang quản trị | Không lỗi, không lộ dữ liệu | Đạt. Chỉ trả 200/404, không có thông báo lỗi SQL; kết quả không lộ bài nháp; payload được xử lý như chuỗi thường |
| SEC04 | Upload tệp | Tải tệp `.php`, `.php.png`, tệp PHP đổi đuôi `.jpg`/`.pdf` ở 3 chỗ upload; truy cập trực tiếp tệp `.php` trong `uploads/` | Bị từ chối | Đạt. Cả 9 lần tải đều bị từ chối với thông báo "Định dạng tệp không được hỗ trợ."; tệp `.php`/`.phtml` trong `uploads/` trả 403; không liệt kê được thư mục |
| SEC05 | Bảo vệ admin | Truy cập 12 URL `/admin/...` và 1 yêu cầu xóa khi chưa đăng nhập; truy cập lại sau khi đăng xuất | Chuyển hướng đăng nhập | Đạt. Cả 13 yêu cầu chuyển về `/admin/login`; sau khi đăng xuất cũng vậy |
| SEC06 | Mật khẩu | Kiểm tra cột `mat_khau` trong bảng `nguoi_dung` | Lưu dạng băm bcrypt | Đạt. Mọi tài khoản lưu dạng `$2y$10$...` dài 60 ký tự; đăng nhập dùng `password_verify()` |

Tổng hợp: chức năng đạt 6/6, bảo mật đạt 6/6. Ngoài ra đã kiểm các luồng đầy đủ: đăng ký → quản trị xử lý → xóa; tin công khai → chuyển nháp → xóa; ẩn/hiện chương trình; phân quyền biên tập viên.

## 12. Kế hoạch tiếp theo
- Viết chương cài đặt, kết quả và kiểm thử trong báo cáo, kèm ảnh chụp màn hình các trang.
- Thay dữ liệu mẫu (địa chỉ, số điện thoại, email, hình ảnh, nội dung chương trình và tin tức) bằng thông tin chính thức khi có.
- Ghi nhận các hạn chế còn lại vào phần kết luận của báo cáo.

## 13. Quản lý dự án
Repository được sử dụng để theo dõi quá trình thực hiện đồ án. Trong quá trình làm cần:
- Commit nội dung đã thực hiện ít nhất một lần mỗi tuần.
- Cập nhật `README.md` theo tiến độ thực tế.
- Lưu báo cáo tiến độ trong `progress-report`.
- Không ghi chức năng là hoàn thành nếu chưa phát triển/kiểm thử.
- Tổ chức source code và tài liệu đúng thư mục.
- Mỗi module phát triển trên một nhánh riêng (`feature/m{N}-...`), tạo Pull Request vào `develop`; `master` là bản nộp.

---
**Sinh viên:** Huỳnh Chí Khải  
**MSSV:** 170124955  
**GVHD:** Lê Phong Dũ

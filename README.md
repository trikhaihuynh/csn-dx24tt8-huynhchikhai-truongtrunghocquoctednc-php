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

> Các chức năng trên hiện là **yêu cầu và nội dung thiết kế**, chưa phải chức năng đã cài đặt.

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

## 7. Phân tích và thiết kế hiện tại
Các nội dung đang được thực hiện:
- Phân tích yêu cầu chức năng và phi chức năng.
- Xác định hai nhóm người dùng.
- Xây dựng Use Case tổng quát.
- Đặc tả Use Case đăng ký nhập học.
- Thiết kế kiến trúc client-server.
- Phân tích và thiết kế cơ sở dữ liệu.
- Thiết kế bố cục giao diện người dùng và khu vực quản trị.

Các bảng dữ liệu dự kiến: `nguoi_dung`, `tin_tuc`, `chuong_trinh`, `hinh_anh`, `dang_ky_nhap_hoc`.

## 8. Cấu trúc repository
```text
/
├── setup/                 # Tập tin cài đặt/dữ liệu thử
├── src/                   # Source code (bổ sung khi phát triển)
├── progress-report/       # Báo cáo tiến độ hằng tuần
├── thesis/
│   ├── doc/
│   ├── pdf/
│   ├── html/
│   ├── abs/
│   └── refs/
├── soft/
├── docker/
└── README.md
```

## 9. Tiến độ
| Tuần | Thời gian | Nội dung | Trạng thái |
|---|---|---|---|
| 1 | 16/08/2026 - 23/08/2026 | Tổng quan đề tài và lý thuyết liên quan | Hoàn thành |
| 2 | 24/08/2026 - 30/08/2026 | Chọn lọc lý thuyết áp dụng | Hoàn thành |
| 3 | 31/08/2026 - 06/09/2026 | Nghiên cứu chuyên sâu, phân tích mô hình và dữ liệu | Hoàn thành theo giai đoạn |
| 4 | 07/09/2026 - 13/09/2026 | Tiếp tục phân tích và thiết kế | Hoàn thành theo giai đoạn |
| 5 | 14/09/2026 - 20/09/2026 | Viết và chuẩn hóa báo cáo | Đang thực hiện |

### Mốc quan trọng
- **02/09/2026:** Đã nộp đề cương báo cáo đồ án.
- **16/09/2026:** Đang thực hiện tuần 5.
- **Phát triển website:** Chưa thực hiện.
- **Kiểm thử website:** Chưa thực hiện.

Chi tiết tiến độ được lưu trong thư mục [`progress-report`](./progress-report/).

## 10. Kế hoạch tiếp theo
Tiếp tục viết và chuẩn hóa báo cáo theo timeline môn học, đồng thời hoàn thiện các nội dung phân tích - thiết kế. Phần cài đặt, kết quả và kiểm thử chỉ được cập nhật sau khi website thực sự được phát triển.

## 11. Quản lý dự án
Repository được sử dụng để theo dõi quá trình thực hiện đồ án. Trong quá trình làm cần:
- Commit nội dung đã thực hiện ít nhất một lần mỗi tuần.
- Cập nhật `README.md` theo tiến độ thực tế.
- Lưu báo cáo tiến độ trong `progress-report`.
- Không ghi chức năng là hoàn thành nếu chưa phát triển/kiểm thử.
- Tổ chức source code và tài liệu đúng thư mục.

## 12. Cài đặt và truy cập

### Khởi động bằng Docker
```bash
cd docker
cp .env.example .env
docker compose up -d --build
```

Dừng hệ thống: `docker compose down`. Nạp lại cơ sở dữ liệu từ đầu: `docker compose down -v` rồi chạy lại lệnh khởi động.

### Link và tài khoản truy cập
| Trang | Link truy cập | Tài khoản |
|---|---|---|
| Website | http://localhost:8080 | Không cần đăng nhập |
| Trang quản trị | http://localhost:8080/admin/login | `admin@dnc.edu.vn` / `Admin@123` |
| phpMyAdmin | http://localhost:8081 | `dnc` / `dnc123` (hoặc `root` / `root`) |
| MySQL | `localhost:3306`, database `dnc_school` | `dnc` / `dnc123` |

### Các trang theo chức năng
| Mã | Chức năng | Trang công khai | Trang quản trị |
|---|---|---|---|
| F01 | Giới thiệu trường | http://localhost:8080 , http://localhost:8080/gioi-thieu , http://localhost:8080/lien-he | http://localhost:8080/admin/cai-dat |
| F02 | Hình ảnh | http://localhost:8080/hinh-anh | http://localhost:8080/admin/hinh-anh |
| F03 | Chương trình đào tạo | http://localhost:8080/chuong-trinh | http://localhost:8080/admin/chuong-trinh |
| F04 | Tin tức - hoạt động | http://localhost:8080/tin-tuc | http://localhost:8080/admin/tin-tuc |
| F05 | Đăng ký nhập học | http://localhost:8080/dang-ky-nhap-hoc | http://localhost:8080/admin/dang-ky |
| F06 | Quản trị nội dung | - | http://localhost:8080/admin , http://localhost:8080/admin/tai-khoan |

> Các tài khoản trên là tài khoản mẫu cho môi trường chạy thử. Cần đổi mật khẩu trước khi triển khai thực tế.

---
**Sinh viên:** Huỳnh Chí Khải  
**MSSV:** 170124955  
**GVHD:** Lê Phong Dũ
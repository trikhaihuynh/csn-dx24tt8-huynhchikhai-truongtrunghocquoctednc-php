-- Dữ liệu mẫu cho môi trường phát triển / demo.
-- Tài khoản admin: admin@dnc.edu.vn / Admin@123  (đổi mật khẩu khi triển khai thật)
USE dnc_school;
SET NAMES utf8mb4;

INSERT INTO nguoi_dung (ho_ten, email, mat_khau, vai_tro) VALUES
('Quản trị viên', 'admin@dnc.edu.vn', '$2y$10$NPoZVJzlkP1zLek8ov5pZ.0yPuu.uscBoKYREpEBtMfjyKW39W/4u', 'admin');

INSERT INTO cai_dat (khoa, gia_tri, mo_ta) VALUES
('ten_truong',    'Trường Trung học Quốc tế DNC',   'Tên trường (tiếng Việt)'),
('ten_truong_en', 'DNC International High School',   'Tên trường (tiếng Anh)'),
('slogan',        'Nurturing Tomorrow''s Leaders',   'Khẩu hiệu hiển thị ở hero'),
('dia_chi',       'Thành phố Hồ Chí Minh, Việt Nam', 'Địa chỉ - thay bằng dữ liệu thật'),
('dien_thoai',    '+84 000 000 000',                 'Số điện thoại - thay bằng dữ liệu thật'),
('email',         'info@dnc.edu.vn',                 'Email liên hệ - thay bằng dữ liệu thật'),
('gioi_thieu',    'Trường Trung học Quốc tế DNC hướng đến môi trường học tập hiện đại, kết hợp chương trình quốc tế và chương trình giáo dục Việt Nam, giúp học sinh phát triển toàn diện.', 'Đoạn giới thiệu ở trang chủ'),
('facebook',      'https://facebook.com/',           'Link Facebook'),
('youtube',       'https://youtube.com/',            'Link YouTube');

INSERT INTO chuong_trinh (ten, slug, mo_ta, noi_dung, thu_tu) VALUES
('IGCSE', 'igcse',
 'Chương trình Trung học phổ thông quốc tế Cambridge dành cho học sinh 14-16 tuổi.',
 '<p>Nội dung chi tiết chương trình IGCSE. Thay bằng dữ liệu chính thức của trường.</p>', 1),
('A-Level / IB Diploma', 'a-level-ib-dp',
 'Chương trình dự bị đại học quốc tế, chuẩn bị cho học sinh vào các trường đại học trong và ngoài nước.',
 '<p>Nội dung chi tiết chương trình A-Level / IB DP. Thay bằng dữ liệu chính thức của trường.</p>', 2),
('Chương trình Giáo dục Việt Nam', 'chuong-trinh-viet-nam',
 'Chương trình của Bộ Giáo dục và Đào tạo, tích hợp tăng cường tiếng Anh.',
 '<p>Nội dung chi tiết chương trình Việt Nam. Thay bằng dữ liệu chính thức của trường.</p>', 3);

INSERT INTO tin_tuc (tieu_de, slug, tom_tat, noi_dung, trang_thai, nguoi_dung_id, ngay_dang) VALUES
('Khai giảng năm học mới 2026-2027', 'khai-giang-nam-hoc-moi-2026-2027',
 'Lễ khai giảng năm học mới diễn ra trong không khí trang trọng và ấm áp.',
 '<p>Nội dung bài viết mẫu. Thay bằng tin tức thật của trường.</p>', 'cong_khai', 1, NOW() - INTERVAL 3 DAY),
('Ngày hội Open Day tháng 10', 'ngay-hoi-open-day-thang-10',
 'Phụ huynh và học sinh được tham quan cơ sở vật chất và trải nghiệm lớp học thử.',
 '<p>Nội dung bài viết mẫu. Thay bằng tin tức thật của trường.</p>', 'cong_khai', 1, NOW() - INTERVAL 1 DAY),
('Bài viết nháp (chưa công khai)', 'bai-viet-nhap',
 'Bài này ở trạng thái nháp, không hiển thị ngoài trang công khai.',
 '<p>Nội dung nháp.</p>', 'nhap', 1, NOW());

INSERT INTO hinh_anh (tieu_de, duong_dan, album, thu_tu) VALUES
('Thư viện DNC',         'assets/img/gallery/thu-vien.jpg',   'co-so-vat-chat', 1),
('Phòng thí nghiệm',     'assets/img/gallery/phong-lab.jpg',  'co-so-vat-chat', 2),
('Hoạt động thể thao',   'assets/img/gallery/the-thao.jpg',   'hoat-dong',      1),
('Câu lạc bộ nghệ thuật','assets/img/gallery/nghe-thuat.jpg', 'hoat-dong',      2);

INSERT INTO dang_ky_nhap_hoc (ho_ten, ngay_sinh, gioi_tinh, ten_phu_huynh, quan_he, email, dien_thoai, khoi_lop, chuong_trinh_id, nguon_biet_den, trang_thai) VALUES
('Nguyễn Văn A', '2011-05-20', 'nam', 'Nguyễn Văn B', 'Cha', 'phuhuynh.a@example.com', '0900000001', '10', 1, 'Facebook', 'moi'),
('Trần Thị C',   '2010-09-02', 'nu',  'Trần Văn D',   'Cha', 'phuhuynh.c@example.com', '0900000002', '11', 2, 'Bạn bè giới thiệu', 'da_lien_he');

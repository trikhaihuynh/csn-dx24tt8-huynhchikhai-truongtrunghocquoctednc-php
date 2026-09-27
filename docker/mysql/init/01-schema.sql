-- =====================================================================
-- CSDL Website Trường Trung học Quốc tế DNC
-- Tên bảng/trường thống nhất với báo cáo đồ án (mục 3.5 Thiết kế bảng dữ liệu).
-- File này được MySQL tự chạy khi khởi tạo container lần đầu.
-- Với XAMPP: import file này vào phpMyAdmin (database dnc_school sẽ được tạo).
-- =====================================================================
CREATE DATABASE IF NOT EXISTS dnc_school
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dnc_school;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS dang_ky_nhap_hoc;
DROP TABLE IF EXISTS hinh_anh;
DROP TABLE IF EXISTS tin_tuc;
DROP TABLE IF EXISTS chuong_trinh;
DROP TABLE IF EXISTS nguoi_dung;
DROP TABLE IF EXISTS cai_dat;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- nguoi_dung: tài khoản quản trị và quyền
-- ---------------------------------------------------------------------
CREATE TABLE nguoi_dung (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ho_ten        VARCHAR(100)  NOT NULL,
  email         VARCHAR(150)  NOT NULL,
  mat_khau      VARCHAR(255)  NOT NULL COMMENT 'password_hash() - không lưu plain text',
  vai_tro       ENUM('admin','bien_tap') NOT NULL DEFAULT 'bien_tap',
  trang_thai    TINYINT(1)    NOT NULL DEFAULT 1 COMMENT '1 = hoạt động, 0 = khóa',
  ngay_tao      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_nguoi_dung_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- chuong_trinh: chương trình đào tạo (F03)
-- ---------------------------------------------------------------------
CREATE TABLE chuong_trinh (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ten           VARCHAR(150)  NOT NULL,
  slug          VARCHAR(160)  NOT NULL,
  mo_ta         VARCHAR(500)  NULL COMMENT 'Mô tả ngắn hiển thị ở danh sách/trang chủ',
  noi_dung      LONGTEXT      NULL COMMENT 'Nội dung chi tiết (HTML)',
  hinh_dai_dien VARCHAR(255)  NULL,
  thu_tu        INT           NOT NULL DEFAULT 0,
  trang_thai    TINYINT(1)    NOT NULL DEFAULT 1 COMMENT '1 = hiển thị, 0 = ẩn',
  ngay_tao      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_chuong_trinh_slug (slug),
  KEY idx_chuong_trinh_trang_thai (trang_thai, thu_tu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- tin_tuc: tin tức - hoạt động (F04)
-- ---------------------------------------------------------------------
CREATE TABLE tin_tuc (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tieu_de       VARCHAR(200)  NOT NULL,
  slug          VARCHAR(220)  NOT NULL,
  tom_tat       VARCHAR(500)  NULL,
  noi_dung      LONGTEXT      NOT NULL,
  hinh_dai_dien VARCHAR(255)  NULL,
  ngay_dang     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  trang_thai    ENUM('nhap','cong_khai') NOT NULL DEFAULT 'nhap',
  luot_xem      INT UNSIGNED  NOT NULL DEFAULT 0,
  nguoi_dung_id INT UNSIGNED  NULL COMMENT 'Người đăng',
  ngay_tao      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_tin_tuc_slug (slug),
  KEY idx_tin_tuc_cong_khai (trang_thai, ngay_dang),
  CONSTRAINT fk_tin_tuc_nguoi_dung FOREIGN KEY (nguoi_dung_id)
    REFERENCES nguoi_dung (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- hinh_anh: thư viện hình ảnh (F02)
-- ---------------------------------------------------------------------
CREATE TABLE hinh_anh (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tieu_de       VARCHAR(200)  NOT NULL,
  duong_dan     VARCHAR(255)  NOT NULL COMMENT 'Đường dẫn tương đối trong public/uploads/',
  mo_ta         VARCHAR(500)  NULL,
  album         VARCHAR(100)  NULL COMMENT 'Nhóm ảnh: hoat-dong, co-so-vat-chat, su-kien...',
  thu_tu        INT           NOT NULL DEFAULT 0,
  trang_thai    TINYINT(1)    NOT NULL DEFAULT 1,
  ngay_tao      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_hinh_anh_album (album, trang_thai, thu_tu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- dang_ky_nhap_hoc: phiếu đăng ký nhập học trực tuyến (F05)
-- Các nhóm trường theo form UI: học sinh - phụ huynh - học vấn - thông tin thêm
-- ---------------------------------------------------------------------
CREATE TABLE dang_ky_nhap_hoc (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  -- Thông tin học sinh
  ho_ten           VARCHAR(100)  NOT NULL,
  ngay_sinh        DATE          NULL,
  gioi_tinh        ENUM('nam','nu','khac') NULL,
  quoc_tich        VARCHAR(100)  NULL,
  -- Thông tin phụ huynh
  ten_phu_huynh    VARCHAR(100)  NULL,
  quan_he          VARCHAR(50)   NULL COMMENT 'Cha / Mẹ / Người giám hộ',
  email            VARCHAR(150)  NOT NULL,
  dien_thoai       VARCHAR(20)   NOT NULL,
  -- Học vấn
  truong_hien_tai  VARCHAR(200)  NULL,
  khoi_lop         VARCHAR(20)   NULL COMMENT 'Khối lớp đăng ký: 6..12',
  chuong_trinh_id  INT UNSIGNED  NULL COMMENT 'FK chuong_trinh - chương trình đăng ký',
  tep_hoc_ba       VARCHAR(255)  NULL COMMENT 'File học bạ đính kèm (public/uploads/hoc-ba/)',
  -- Thông tin thêm
  nguon_biet_den   VARCHAR(100)  NULL,
  ghi_chu          TEXT          NULL,
  -- Xử lý phía quản trị
  trang_thai       ENUM('moi','dang_xu_ly','da_lien_he','tu_choi') NOT NULL DEFAULT 'moi',
  ghi_chu_admin    TEXT          NULL,
  ngay_tao         DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_dang_ky_trang_thai (trang_thai, ngay_tao),
  KEY idx_dang_ky_email (email),
  CONSTRAINT fk_dang_ky_chuong_trinh FOREIGN KEY (chuong_trinh_id)
    REFERENCES chuong_trinh (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- cai_dat: thông tin trường / cấu hình chung (F01 Giới thiệu trường, footer)
-- Bảng khóa-giá trị để quản trị viên sửa mà không cần đổi code.
-- ---------------------------------------------------------------------
CREATE TABLE cai_dat (
  khoa          VARCHAR(100)  NOT NULL,
  gia_tri       TEXT          NULL,
  mo_ta         VARCHAR(255)  NULL,
  ngay_cap_nhat DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (khoa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

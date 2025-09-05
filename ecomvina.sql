-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 05, 2025 at 05:56 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecomvina`
--

-- --------------------------------------------------------

--
-- Table structure for table `anh_sanpham`
--

CREATE TABLE `anh_sanpham` (
  `id` int NOT NULL,
  `media` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trang_thai` int DEFAULT NULL,
  `id_sanpham` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anh_sanpham`
--

INSERT INTO `anh_sanpham` (`id`, `media`, `trang_thai`, `id_sanpham`, `created_at`, `updated_at`) VALUES
(1, 'yensaonest100_70ml_1.jpg', 0, 1, '2025-08-09 16:00:00', '2025-08-09 16:00:00'),
(2, 'yensaonest100_70ml_2.jpg', 0, 1, '2025-08-15 07:25:17', '2025-08-15 07:25:17'),
(3, 'yensaonest100_70ml_3.jpg', 0, 1, '2025-08-15 07:25:17', '2025-08-15 07:25:17'),
(4, '1755515978_1755513545159.webp', NULL, 2, '2025-08-18 03:19:38', '2025-08-18 03:19:38'),
(5, '1755515978_1755513541072.webp', NULL, 2, '2025-08-18 03:19:38', '2025-08-18 03:19:38'),
(6, '1755515978_1755513537059.webp', NULL, 2, '2025-08-18 03:19:38', '2025-08-18 03:19:38'),
(7, '1755515978_1755513532522.webp', NULL, 2, '2025-08-18 03:19:38', '2025-08-18 03:19:38'),
(8, '1755515978_1755513527949.webp', NULL, 2, '2025-08-18 03:19:38', '2025-08-18 03:19:38'),
(16, '1755518408_1755518102375.webp', NULL, 8, '2025-08-18 04:00:08', '2025-08-18 04:00:08'),
(17, '1755518408_1755518099245.webp', NULL, 8, '2025-08-18 04:00:08', '2025-08-18 04:00:08'),
(18, '1755518408_1755518092284.webp', NULL, 8, '2025-08-18 04:00:08', '2025-08-18 04:00:08'),
(19, '1755518855_1755518699084.webp', NULL, 9, '2025-08-18 04:07:35', '2025-08-18 04:07:35'),
(20, '1755518855_1755518695237.webp', NULL, 9, '2025-08-18 04:07:35', '2025-08-18 04:07:35'),
(21, '1755518855_1755518680609.webp', NULL, 9, '2025-08-18 04:07:35', '2025-08-18 04:07:35'),
(22, '1755518855_1755518684200.webp', NULL, 9, '2025-08-18 04:07:35', '2025-08-18 04:07:35'),
(23, '1755518855_1755518676090.webp', NULL, 9, '2025-08-18 04:07:35', '2025-08-18 04:07:35'),
(27, 'thuc-pham-bao-ve-suc-khoe-byealco-hop-1-vi-x-5-vien-1.webp', NULL, 11, '2025-08-21 03:04:12', '2025-08-21 03:04:12'),
(28, 'thuc-pham-bao-ve-suc-khoe-byealco-hop-1-vi-x-5-vien-2.webp', NULL, 11, '2025-08-21 03:04:12', '2025-08-21 03:04:12'),
(29, 'thuc-pham-bao-ve-suc-khoe-byealco-hop-1-vi-x-5-vien-3.webp', NULL, 11, '2025-08-21 03:04:12', '2025-08-21 03:04:12'),
(30, 'thuc-pham-bao-ve-suc-khoe-byealco-hop-1-vi-x-5-vien-4.webp', NULL, 11, '2025-08-21 03:04:12', '2025-08-21 03:04:12'),
(31, 'thuc-pham-bao-ve-suc-khoe-byealco-hop-1-vi-x-5-vien-5.webp', NULL, 11, '2025-08-21 03:04:12', '2025-08-21 03:04:12'),
(32, 'thuc-pham-bao-ve-suc-khoe-byealco-hop-1-vi-x-5-vien-6.webp', NULL, 11, '2025-08-21 03:04:12', '2025-08-21 03:04:12'),
(52, 'cherry-extract-bo-sung-vitamin-c-tang-cuong-mien-dich-30-goi-1.jpg', NULL, 14, '2025-08-27 11:38:58', '2025-08-27 11:38:58'),
(53, 'cherry-extract-bo-sung-vitamin-c-tang-cuong-mien-dich-30-goi-2.jpg', NULL, 14, '2025-08-27 11:38:58', '2025-08-27 11:38:58'),
(54, 'cherry-extract-bo-sung-vitamin-c-tang-cuong-mien-dich-30-goi-3.jpg', NULL, 14, '2025-08-27 11:38:58', '2025-08-27 11:38:58'),
(55, 'cherry-extract-bo-sung-vitamin-c-tang-cuong-mien-dich-30-goi-4.jpg', NULL, 14, '2025-08-27 11:38:58', '2025-08-27 11:38:58'),
(56, 'may-xay-nau-da-nang-olivo-cb1000-1.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(57, 'may-xay-nau-da-nang-olivo-cb1000-2.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(58, 'may-xay-nau-da-nang-olivo-cb1000-3.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(59, 'may-xay-nau-da-nang-olivo-cb1000-4.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(60, 'may-xay-nau-da-nang-olivo-cb1000-5.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(61, 'may-xay-nau-da-nang-olivo-cb1000-6.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(62, 'may-xay-nau-da-nang-olivo-cb1000-7.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(63, 'may-xay-nau-da-nang-olivo-cb1000-8.jpg', NULL, 15, '2025-08-27 12:31:00', '2025-08-27 12:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `bienthe_sp`
--

CREATE TABLE `bienthe_sp` (
  `id` int NOT NULL,
  `id_tenloai` int DEFAULT NULL,
  `gia` int DEFAULT NULL,
  `soluong` int DEFAULT '0',
  `trangthai` int DEFAULT '0' COMMENT 'trạng thái hiển thị (0 hiển thị; 1 ẩn)',
  `uutien` int DEFAULT '0' COMMENT '1 ưu tiên ',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `id_sanpham` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bienthe_sp`
--

INSERT INTO `bienthe_sp` (`id`, `id_tenloai`, `gia`, `soluong`, `trangthai`, `uutien`, `created_at`, `updated_at`, `id_sanpham`) VALUES
(1, 1, 34500, 20, 0, 1, '2025-08-28 12:31:33', '2025-08-28 12:31:38', 1),
(4, 2, 290000, 32, 0, 0, '2025-08-18 03:19:38', '2025-08-18 03:19:38', 2),
(10, 1, 590000, 0, 0, 0, '2025-08-18 04:00:08', '2025-08-29 09:13:22', 8),
(13, 2, 59500, 20, 0, 0, '2025-08-21 03:04:12', '2025-08-27 12:32:37', 11),
(17, 2, 340000, 17, 0, 0, '2025-08-22 09:38:56', '2025-08-29 06:33:29', 9),
(26, 5, 290000, 5, 0, 0, '2025-08-27 11:38:58', '2025-08-29 07:53:31', 14),
(28, 6, 1990000, 8, 0, 0, '2025-08-27 12:31:00', '2025-08-29 07:54:23', 15),
(29, 7, 1990000, 32, 0, 0, '2025-08-27 12:31:00', '2025-08-27 12:31:00', 15),
(32, 2, 34500, 0, 0, 0, '2025-08-29 09:14:11', '2025-08-29 09:15:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chitiet_donhang`
--

CREATE TABLE `chitiet_donhang` (
  `id` int NOT NULL,
  `gia` int DEFAULT NULL,
  `soluong` int DEFAULT NULL,
  `tongtien` int DEFAULT NULL,
  `id_donhang` int DEFAULT NULL,
  `id_bienthe` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chuongtrinhsukien`
--

CREATE TABLE `chuongtrinhsukien` (
  `id` int NOT NULL,
  `ten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề của sự kiện (ví dụ: "Sale Lớn Cuối Năm - Mua 1 Tặng 1").',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn thân thiện để truy cập trang sự kiện (ví dụ: sale-lon-cuoi-nam-mua-1-tang-1).',
  `media` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn ảnh banner để hiển thị trên popup hoặc đầu trang.',
  `mota` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung chính của "bài viết". Đây là nơi bạn dùng trình soạn thảo văn bản (WYSIWYG) để viết chi tiết về ưu đãi, thể lệ, điều kiện.',
  `ngaybatdau` datetime NOT NULL,
  `ngayketthuc` datetime NOT NULL,
  `trangthai` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Cho phép admin soạn thảo trước và chỉ công khai khi đã sẵn sàng.',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `id` int NOT NULL,
  `diem` int DEFAULT NULL COMMENT 'điểm đánh giá (5/5)',
  `noidung` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Nội dung đánh giá',
  `media` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ngaydang` date DEFAULT NULL COMMENT 'ngày đăng đánh giá',
  `trangthai` int DEFAULT NULL COMMENT 'trạng thái hiển thị (0 hiển thị; 1 ẩn)',
  `id_sanpham` int DEFAULT NULL,
  `id_nguoidung` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id` int NOT NULL,
  `ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangthai` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `ten`, `trangthai`, `created_at`, `updated_at`) VALUES
(1, 'Thực phẩm chức năng', 0, '0000-00-00 00:00:00', '2025-08-21 00:10:03'),
(2, 'Đồ uống', 0, '2025-08-15 07:39:28', '2025-08-15 07:39:28'),
(5, 'Thực phẩm đóng hộp', 0, '2025-08-21 06:41:19', '2025-08-21 06:41:19'),
(10, 'Đồ dùng điện tử', 0, '2025-08-21 00:11:13', '2025-08-21 00:11:38'),
(11, 'Sức khỏe', 0, '2025-08-27 12:17:53', '2025-08-27 12:18:28'),
(12, 'Điện máy', 0, '2025-08-27 12:18:16', '2025-08-27 12:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `diachi_nguoidung`
--

CREATE TABLE `diachi_nguoidung` (
  `id` int NOT NULL,
  `thanhpho` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `xaphuong` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sonha` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `diachi` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trangthai` int DEFAULT '1' COMMENT 'kiểu địa chỉ (0 chính ; 1 phụ)',
  `id_nguoidung` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diachi_nguoidung`
--

INSERT INTO `diachi_nguoidung` (`id`, `thanhpho`, `xaphuong`, `sonha`, `diachi`, `trangthai`, `id_nguoidung`) VALUES
(1, 'Taichung', 'Shigang', 'No.1240 Fengshi rd', 'No.1240 Fengshi Rd, Shigang District, Taichung City, Taiwan', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dieukien_khuyenmai`
--

CREATE TABLE `dieukien_khuyenmai` (
  `id` int NOT NULL,
  `id_khuyenmai` int NOT NULL COMMENT '	Liên kết tới chương trình khuyến mãi.',
  `id_bienthe` int NOT NULL COMMENT 'Sản phẩm điều kiện (liên kết tới bảng san_pham).',
  `soluongyeucau` int NOT NULL DEFAULT '1' COMMENT 'Số lượng yêu cầu của sản phẩm này. Ví dụ: cần mua 2 cái áo sơ mi.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dieukien_magiamgia`
--

CREATE TABLE `dieukien_magiamgia` (
  `id` int NOT NULL,
  `loaidieukien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại điều kiện. Đây là cột quan trọng nhất. Ví dụ: user_group, user_is_new, usage_limit_per_user.',
  `sosanh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Toán tử so sánh. Ví dụ: eq (bằng), gte (lớn hơn hoặc bằng), lt (nhỏ hơn).',
  `giatri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Giá trị của điều kiện. Ví dụ: 1 (cho nhóm VIP), true, 1 (cho giới hạn 1 lần).',
  `id_magiamgia` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int NOT NULL,
  `ma_donhang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tongtien` int DEFAULT NULL,
  `tongsoluong` int DEFAULT NULL,
  `ghichu` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ngaytao` datetime DEFAULT NULL,
  `trangthai` int DEFAULT '0' COMMENT 'trạng thái đơn hàng (0 chờ thanh toán ; 1 đang giao ; 2 đã giao; 3 đã hủy)',
  `id_nguoidung` int DEFAULT NULL,
  `id_magiamgia` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

CREATE TABLE `gio_hang` (
  `id` int NOT NULL,
  `soluong` int DEFAULT NULL,
  `tongtien` int DEFAULT NULL,
  `id_sanpham` int DEFAULT NULL,
  `id_nguoidung` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `id` int NOT NULL,
  `ten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chương trình (ví dụ: "Chào hè sang, mua 2 tặng 1", "Tri ân khách hàng tháng 10").',
  `mota` text COLLATE utf8mb4_unicode_ci,
  `ngaybatdau` datetime NOT NULL,
  `ngayketthuc` datetime NOT NULL,
  `trangthai` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lichsu_magiamgia`
--

CREATE TABLE `lichsu_magiamgia` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_voucher` int NOT NULL,
  `id_donhang` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loai_bienthe`
--

CREATE TABLE `loai_bienthe` (
  `id` int NOT NULL,
  `ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loai_bienthe`
--

INSERT INTO `loai_bienthe` (`id`, `ten`, `created_at`, `updated_at`) VALUES
(1, 'lọ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'hộp', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'chai', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Chiếc', '2025-08-25 09:10:29', '2025-08-25 09:10:29'),
(5, 'Hộp 30 gói x 3g', '2025-08-27 11:41:55', '2025-08-27 11:41:55'),
(6, 'Màu Be', '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(7, 'Màu Xanh mint', '2025-08-27 12:31:00', '2025-08-27 12:31:00'),
(8, 'Thầy Hộ', '2025-08-27 12:54:44', '2025-08-27 12:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `ma_giamgia`
--

CREATE TABLE `ma_giamgia` (
  `id` int NOT NULL,
  `magiamgia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mota` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `loaigiamgia` enum('percent','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại giảm giá. percent là giảm theo phần trăm, fixed là giảm một số tiền cố định.',
  `giatri` decimal(10,0) DEFAULT NULL COMMENT 'Giá trị giảm. Nếu type là percent, giá trị này là phần trăm (ví dụ: 20). Nếu type là fixed, đây là số tiền sẽ được trừ (ví dụ: 50000).',
  `mindonhang` decimal(10,0) NOT NULL DEFAULT '0' COMMENT 'Giá trị đơn hàng tối thiểu để có thể áp dụng voucher (ví dụ: Chỉ áp dụng cho đơn hàng từ 200k trở lên).',
  `soluong` int DEFAULT NULL COMMENT 'Tổng số lượt sử dụng của voucher này.',
  `luotsudung` int NOT NULL DEFAULT '0' COMMENT 'Số lượt đã sử dụng. Khi used_count bằng quantity, voucher sẽ hết hiệu lực.',
  `ngaybatdau` date DEFAULT NULL,
  `ngayketthuc` date DEFAULT NULL,
  `trangthai` int DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hoten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gioitinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `sodienthoai` int DEFAULT NULL,
  `id_uudai` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `username`, `email`, `password`, `hoten`, `gioitinh`, `ngaysinh`, `sodienthoai`, `id_uudai`) VALUES
(1, 'lyhuu123', 'lyhuu5570@gmail.com', '13102004caokienhuu', 'Cao Kiến Hựu', '0', '2004-10-13', 845381121, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quatang_khuyenmai`
--

CREATE TABLE `quatang_khuyenmai` (
  `id` int NOT NULL,
  `id_khuyenmai` int NOT NULL,
  `id_bienthe` int NOT NULL COMMENT 'Sản phẩm được tặng (liên kết tới bảng san_pham).',
  `soluong` int NOT NULL DEFAULT '1' COMMENT 'Số lượng được tặng của sản phẩm này.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanpham_danhmuc`
--

CREATE TABLE `sanpham_danhmuc` (
  `id` int NOT NULL,
  `id_sanpham` int DEFAULT NULL,
  `id_danhmuc` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sanpham_danhmuc`
--

INSERT INTO `sanpham_danhmuc` (`id`, `id_sanpham`, `id_danhmuc`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(7, 8, 1),
(8, 9, 2),
(10, 11, 1),
(16, 9, 1),
(17, 8, 2),
(18, 14, 1),
(19, 15, 12),
(20, 15, 1),
(21, 15, 5);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int NOT NULL,
  `ten` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `xuatxu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sanxuat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mediaurl` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trangthai` int DEFAULT '0' COMMENT 'trạng thái hiển thị (0 hiển thị; 1 ẩn)',
  `luotxem` int DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `id_thuonghieu` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`id`, `ten`, `mota`, `xuatxu`, `sanxuat`, `mediaurl`, `trangthai`, `luotxem`, `created_at`, `updated_at`, `id_thuonghieu`) VALUES
(1, 'Nước Yến sào Nest100 - Có đường (lọ 70ml)', '<p><strong>1. Xuất xứ:</strong> Khánh Hòa, Nha Trang, Việt Nam&nbsp;<br><strong>2. Các chứng nhận và giải thưởng:</strong></p><p>Hàng Việt Nam chất lượng cao 2023</p><p>Hàng Việt Nam chất lượng cao - Chuẩn hội nhập 2023</p><p>TOP thương hiệu số 1 VN 2022</p><p>TOP 100 sản phẩm dịch vụ tốt nhất cho gia đình và trẻ em 2023</p><p>Sản phẩm vàng vì sức khỏe cộng đồng</p><p>Thương hiệu vàng phát triển bền vững thời đại số - 2024</p><p><strong>3. Thành phần:</strong><br>Nước, yến sào đã chế biến (35%), đường phèn (8,5%), chất làm dày (401, 415, 406, 327), chất bảo quản (211), hương tổng hợp dùng cho thực phẩm.<br>&nbsp;</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-09 16:00:00', '2025-08-29 09:14:11', 1),
(2, 'Cà Phê Sâm Canada (Hộp 30 gói)', '<p><strong>MÔ TẢ SẢN PHẨM:</strong><br>Khi nhắc đến cà phê chất lượng cao, cả thế giới nghĩ ngay đến Việt Nam: Hương thơm nổi bật, vị đậm đà, mạnh mẽ - Là một tài sản quý giá mà thiên nhiên ban tặng cho mảnh đất hình chữ S vốn giàu truyền thống văn hóa, lịch sử. Sự kết hợp hài hòa giữa truyền thống cà phê thượng hạng và sâm Canada hiện đại mang đến lại cà phê đậm đà mỗi ngày, vừa mang đến sức khỏe từ bên trong</p><p><strong>THÀNH PHẦN:</strong><br>Bột cà phê hòa tan, Sâm Canada, Vitamin A, Vitamin E</p><p><strong>CÁCH DÙNG:</strong><br>Dùng 1-2 gói/ngày, pha trong 50ml nước nóng, khuấy đều. Có thể uống nóng hoặc thêm đá, tùy sở thích.</p><p><strong>THỜI HẠN SỬ DỤNG:</strong><br>Ngày sản xuất và hạn sử dụng được ghi trên nhãn chính của sản phẩm</p><p><strong>BẢO QUẢN:</strong><br>Bảo quản nơi khô ráo và thoáng mát, tránh ánh nắng chiếu trực tiếp.</p>', 'Việt Nam', 'Việt Nam', '...', 0, NULL, '2025-08-18 03:19:38', '2025-08-25 09:10:29', 1),
(8, 'Viên uống hỗ trợ giảm cân Goraka Slimming (60 viên)', '<p>Đang trong quá trình cập nhật thông tin sản phẩm …</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-18 04:00:08', '2025-08-27 11:22:11', 1),
(9, 'Cà phê bào tử Linh Chi phá vách - Giúp tỉnh táo (20 gói x 15g)', '<p>Thông tin sản phẩm hiện đang cập nhật ….</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-18 04:07:35', '2025-08-25 08:58:52', 1),
(11, 'Thực phẩm bảo vệ sức khỏe ByeAlco (Hộp 1 vỉ x 5 viên)', '<p><strong>TPBVSK BYEALCO – VIÊN UỐNG GIẢI RƯỢU, HỖ TRỢ CẢI THIỆN CHỨC NĂNG GAN</strong></p><p><span style=\"color:hsl(0,75%,60%);\"><strong><u>Mô tả sản phẩm:&nbsp;</u></strong></span><br>BYEALCO&nbsp;là sản phẩm thiên nhiên với công thức đặc biệt&nbsp;được nghiên cứu chiết xuất từ các dược liệu quý. Sản phẩm giúp bảo vệ gan, tăng cường sức khỏe và giảm thiểu tác hại của rượu bia.&nbsp;Sản phẩm được sản xuất tại nhà máy đạt chuẩn GMP, ISO - Đạt các tiêu chuẩn nghiêm ngặt của Bộ y Tế Việt Nam và các tiêu chuẩn Quốc tế.&nbsp;<br><strong>Hiệu quả nhanh chóng:</strong>&nbsp;Giúp giảm nhanh các triệu chứng say rượu, giúp bạn tỉnh táo và thoải mái hơn. An toàn&nbsp;cho sức khỏe, tiện lợi,&nbsp;dễ mang theo bên mình, sử dụng mọi lúc mọi nơi.</p><p><span style=\"color:hsl(0,75%,60%);\"><strong><u>Thông tin sản phẩm:&nbsp;</u></strong></span><br><strong>1. Thành phần:&nbsp;</strong><br>Thành phần trong 1 viên nang chứa:&nbsp;<br>- Cao khô quả Khúng khéng (50 mg)&nbsp;<br>- Cao khô rễ Gừng (50 mg)&nbsp;<br>- Cao khô rẽ Nghệ vàng (50 mg)&nbsp;<br>- Cao khô quả Khổ qua (40 mg)&nbsp;<br>- Cao khô quả thể Linh chi (30 mg)&nbsp;<br>- Cao khô rễ Nhân sâm (30 mg)&nbsp;<br>- Cao khô toàn cây Diệp hạ châu (30 mg)&nbsp;<br><strong>2. Công dụng chính</strong>&nbsp;<br>-&nbsp;Giúp tăng cường quá trình phân giải rượu và loại bỏ các chất độc hại ra khỏi gan.&nbsp;<br>- Hỗ trợ giảm triệu chứng khó chịu khi say rượu như: buồn nôn, khát, khô cổ, đau đầu, chóng mặt.&nbsp;<br>- Bảo vệ và phục hồi chức năng gan, thận do uống nhiều rượu, bia.&nbsp;<br>- Chống lại sự ức chế của rượu, bia lên thần kinh trung ương.&nbsp;<br>- Hỗ trợ cơ thể phục hồi nhanh chóng sau khi uống rượu bia.&nbsp;<br><strong>3. Đối tượng sử dụng:&nbsp;</strong>&nbsp;<br>- Người thường xuyên sử dụng rượu bia, có các triệu chứng khó chịu khi say rượu bia.&nbsp;<br>- Người muốn bảo vệ sức khỏe trước tác hại của rượu bia.&nbsp;<br>- Người cần tỉnh táo sau khi uống rượu, bia.&nbsp;<br>- Người phục hồi chức năng gan kém.&nbsp;<br><span style=\"color:hsl(0,75%,60%);\"><i><strong><u>Lưu ý:</u></strong></i></span>&nbsp;<i>Thực phẩm này không phải là thuốc, không có tác dụng thay thế thuốc chữa bệnh.</i></p><p><span style=\"color:hsl(0,75%,60%);\"><strong><u>Hướng dẫn sử dụng &amp; bảo quản:</u></strong></span><br><span style=\"color:hsl(0,0%,0%);\"><strong>Cách dùng: </strong>Người lớn uống 2 viên/lần/ngày, uống trước hoặc sau khi uống rượu bia 30 phút.</span><br><strong>Bảo quản: </strong>Bảo quản ở nơi khô ráo và thoáng mát, tránh ánh nắng chiếu trực tiếp.</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-21 03:04:12', '2025-08-27 12:32:37', 1),
(14, 'Cherry Extract bổ sung Vitamin C tăng cường miễn dịch (30 gói)', '<p><strong>1. Tên sản phẩm:</strong> TPBVSK Cherry Extract - Bổ sung Vitamin C, tăng đề kháng</p><p><strong>2. Xuất xứ:</strong> Việt Nam</p><p><strong>3. Thương hiệu:</strong>&nbsp;Wellness By Life Gift VN</p><p><strong>4. Thành phần đầy đủ:</strong> trong 1 gói (3g) chứa:<br>- Vitamin C: 475mg<br>- Cherry extract (Chiết xuất quả anh đào chua): 100mg<br>- Citrus Bioflavonoids Dehydrated (chiết xuất từ quả Cam): 35mg</p><p><strong>5. Công dụng sản phẩm:</strong> Bổ sung vitamin C, hỗ trợ tăng cường sức khỏe, nâng cao sức đề kháng.</p><p><i><strong>- Điểm nổi bật:</strong></i><br>-Sản phẩm cung cấp hàm lượng vitamin C theo khuyến nghị.<br>-Chứa chất chống oxy hóa cao, giúp ngăn ngừa các gốc tự do gây ra.<br>-Hỗ trợ thúc đẩy các enzyme kích thích quá trình sản sinh collagen, giúp làn da đàn hồi, tươi trẻ từ sâu bên trong<br>-Giúp nướu răng khỏe mạnh, hạn chế tình trạng chảy máu chân răng do thiếu hụt vitamin C.<br>-Giúp cơ thể hấp thụ nhanh tối đa tất cả các dinh dưỡng.</p><p><strong>6. Đối tượng sử dụng:</strong> Người có sức đề kháng kém, người có nhu cầu bổ sung vitamin C do chế độ ăn thiếu hụt.</p><p><strong>7. Hướng dẫn sử dụng:</strong><br><strong>- </strong>Hòa 1 gói với 200ml nước, uống trước bữa ăn.<br>- Trẻ em từ 5 - 12 tuổi: Uống 1 gói/ lần/ ngày.<br>- Trẻ em từ 12 tuổi trở lên và người lớn: Uống 1 gói/ lần x 2 lần/ ngày.</p><p><strong>8. Lưu ý khi sử dụng:</strong>&nbsp;Không sử dụng cho người có mẫn cảm, kiêng kỵ với bất kỳ thành phần nào của sản phẩm.</p><p><strong>9. Quy cách đóng gói:</strong> Hộp 30 gói, mỗi gói 3g.</p><p><strong>10. Hướng dẫn bảo quản:</strong> Bảo quản nơi khô ráo, thoáng mát, tránh ánh chiếu trực tiếp.</p><p><strong>11. Thời hạn sử dụng sản phẩm:</strong>&nbsp;36 tháng kể từ ngày sản xuất. Ngày sản xuất và hạn sử dụng được ghi trên nhãn chính của sản phẩm.</p><p><strong>Mô tả sản phẩm</strong></p><p>TPBVSK Cherry Extract - Bổ sung Vitamin C, tăng đề kháng được nghiên cứu sản xuất tại Nhà máy đạt chuẩn GMP; thành phần chiết xuất tự nhiên, lành tính, đạt hàm lượng ‘vitamin C’ lý tưởng. Đặc biệt, có thể thấy thành phần Vitamin C 500mg “sánh đôi” cùng hợp chất Citrus Bioflavonoid Dehydrated có trong Cherry Extract sẽ là giải pháp hoàn hảo nâng cao thể trạng và duy trì hệ miễn dịch tự nhiên.</p><p>Sản phẩm thích hợp dành cho người suy nhược cơ thể, người cần hồi phục sức khỏe sau ốm, người có sức đề kháng kém và người có nhu cầu bổ sung vitamin C do chế độ ăn thiếu hụt.</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-27 11:38:58', '2025-08-27 11:41:55', 1),
(15, 'Máy xay nấu đa năng Olivo CB1000', '<p><strong>1. Thông số kỹ thuật:</strong><br>- Model: Olivo CB1000<br>- Dung tích: 1000ML<br>- Bảng điều khiển: Cảm ứng<br>- Công suất nấu: 700W<br>- Công suất xay: 180W<br>- Điện áp định mức: 220-240V, 50/60Hz<br>- Trọng lượng: 1.5Kg<br>- Kích thước: 195x142x275mm<br><strong>2. Công năng:</strong> Nấu sữa từ các loại hạt tự nhiên, nấu cháo, xay sinh tố, đun sôi, hẹn giờ, tự động làm sạch.</p><p><strong>3. Đối tượng sử dụng</strong>: Bất kỳ ai cũng có thể sử dụng, đối với trẻ em cần có sự giám sát của người lớn để đảm bảo an toàn.</p><p><strong>4. Quy cách:</strong><br>Một hộp sản phẩm Olivo CB1000 bao gồm:&nbsp;<br>1 thân máy<br>1 nắp kính viền INOX<br>1 dây nguồn<br>1 cốc đong nguyên liệu<br>1 chổi vệ sinh<br>1 sách hướng dẫn sử dụng<br>1 sách công thức sữa hạt<br>1 phiếu hướng dẫn kích hoạt bảo hành<br>1 vỏ hộp&nbsp;</p><p><strong>5. Bảo quản:</strong> Để sản phẩm nơi khô ráo, thoáng mát.&nbsp;</p><p><strong>6. &nbsp;Mô tả sản phẩm:</strong><br>9 chế độ cài sẵn hiển thị tiếng Việt bao gồm: Sữa hạt, sữa lạnh, sữa hạt 35 phút, cháo mịn, cháo hạt, sinh tố, đun sôi, làm sạch, hẹn giờ. Bảng điều khiển cảm ứng thao tác dễ dàng, hiển thị chính xác. Cảm biến NTC chống trào tuyệt đối với mọi nguyên liệu.Dung tích 1000ML vừa đủ cho gia đình từ 3-5 thành viên. Thiết kế nhỏ gọn tiện lợi mang theo đi du lịch, về quê, công tác,...Nắp INOX 304 kết hợp kính cường lực duy nhất trên thị trường, có khả năng chịu nhiệt và va đập tốt, dễ dàng quan sát được thành phẩm bên trong.Lưỡi dao 10 cánh tiếp xúc với thực phẩm tốt hơn giúp xay nhuyễn mịn mọi tuyệt đối.Hoạt động êm ái, độ ồn chỉ 68dB, không ảnh hưởng đến mọi người xung quanh.Tạm dừng 10 giây cho phép mở nắp thêm nguyên liệu khi đang nấuDễ dàng làm sạch với chế độ “Làm Sạch” cài sẵn.</p>', 'Hoa Kỳ', 'China', NULL, 0, NULL, '2025-08-27 12:31:00', '2025-08-27 12:54:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sukien_khuyenmai`
--

CREATE TABLE `sukien_khuyenmai` (
  `id` int NOT NULL,
  `id_khuyenmai` int NOT NULL,
  `id_sukien` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id` int NOT NULL,
  `nganhang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gia` int DEFAULT NULL,
  `noidung` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `magiaodich` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngaythanhtoan` datetime DEFAULT NULL,
  `trangthai` int DEFAULT NULL,
  `id_donhang` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `id` int NOT NULL,
  `ten` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trangthai` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thuong_hieu`
--

INSERT INTO `thuong_hieu` (`id`, `ten`, `mota`, `trangthai`, `created_at`, `updated_at`) VALUES
(1, 'NEST100', '<p>Đây là thương hiệu của <strong>NEST100</strong></p><p><strong>Trách nhiệm - Niềm tin - Hàng đầu</strong></p>', 0, '0000-00-00 00:00:00', '2025-08-21 11:47:27'),
(2, 'LIFE GIFT VN', '<p>Đây là thương hiệu của <strong>LIFE GIFT VN</strong></p><p><strong>Trách nhiệm - Niềm tin - Chất lượng</strong></p>', 0, '2025-08-18 10:46:22', '2025-08-21 00:47:16'),
(5, 'ACCECOOK', '<p>Thương hiệu <strong>Việt Nam </strong>nổi tiếng với đa dạng các sản phẩm Việt</p>', 0, '2025-08-23 10:52:13', '2025-08-23 10:53:38'),
(6, 'Khác', '<p>Thương hiệu khác</p>', 0, '2025-08-27 11:44:43', '2025-08-27 11:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `uudaithanhvien`
--

CREATE TABLE `uudaithanhvien` (
  `id` int NOT NULL,
  `hanmuc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `giamgia` float DEFAULT NULL,
  `dieukien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uudaithanhvien`
--

INSERT INTO `uudaithanhvien` (`id`, `hanmuc`, `giamgia`, `dieukien`, `created_at`, `updated_at`) VALUES
(1, 'Thành Viên Mới', 0, '0', '2025-08-31 12:25:36', '2025-08-31 12:25:36'),
(2, 'Bậc Sắt', 5, '100000', '2025-08-31 12:25:36', '2025-08-31 12:25:36'),
(3, 'Bậc Đồng', 10, '500000', '2025-08-31 12:25:36', '2025-08-31 12:25:36'),
(4, 'Bậc Bạc', 15, '1000000', '2025-08-31 12:25:36', '2025-08-31 12:25:36'),
(5, 'Bậc Vàng', 20, '2000000', '2025-08-31 12:25:36', '2025-08-31 12:25:36'),
(6, 'Bậc Bạch Kim', 25, '3000000', '2025-08-31 12:25:36', '2025-08-31 12:25:36'),
(7, 'Bậc Kim Cương', 30, '4000000', '2025-08-31 12:25:36', '2025-08-31 12:25:36'),
(8, 'Siêu Tân Tinh', 40, '5000000', '2025-08-31 12:25:36', '2025-08-31 12:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `yeu_thich`
--

CREATE TABLE `yeu_thich` (
  `id` int NOT NULL,
  `trangthai` int NOT NULL,
  `id_sanpham` int DEFAULT NULL,
  `id_nguoidung` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anh_sanpham`
--
ALTER TABLE `anh_sanpham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanpham_anh` (`id_sanpham`);

--
-- Indexes for table `bienthe_sp`
--
ALTER TABLE `bienthe_sp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanpham_bienthe` (`id_sanpham`),
  ADD KEY `loai_bienthe` (`id_tenloai`);

--
-- Indexes for table `chitiet_donhang`
--
ALTER TABLE `chitiet_donhang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `bienthe_chitietdonhang` (`id_bienthe`),
  ADD KEY `donhang_chitietdonhang` (`id_donhang`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `nguoidung_danhgia` (`id_nguoidung`),
  ADD KEY `sanpham_danhgia` (`id_sanpham`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diachi_nguoidung`
--
ALTER TABLE `diachi_nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoidung_diachi` (`id_nguoidung`);

--
-- Indexes for table `dieukien_khuyenmai`
--
ALTER TABLE `dieukien_khuyenmai`
  ADD KEY `dieukien_khuyenmai` (`id_khuyenmai`),
  ADD KEY `khuyenmai_sanpham` (`id_bienthe`);

--
-- Indexes for table `dieukien_magiamgia`
--
ALTER TABLE `dieukien_magiamgia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `magiamgia_dieukien` (`id_magiamgia`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `magiamgia_donhang` (`id_magiamgia`),
  ADD KEY `nguoidung_donhang` (`id_nguoidung`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `nguoidung_giohang` (`id_nguoidung`),
  ADD KEY `sanpham_giohang` (`id_sanpham`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lichsu_magiamgia`
--
ALTER TABLE `lichsu_magiamgia`
  ADD KEY `lichsugiamgia_donhang` (`id_donhang`),
  ADD KEY `lichsugiamgia_nguoidung` (`id_user`),
  ADD KEY `lichsugiamgia_magiamgia` (`id_voucher`);

--
-- Indexes for table `loai_bienthe`
--
ALTER TABLE `loai_bienthe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ma_giamgia`
--
ALTER TABLE `ma_giamgia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uudai_nguoidung` (`id_uudai`);

--
-- Indexes for table `quatang_khuyenmai`
--
ALTER TABLE `quatang_khuyenmai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quatang_khuyenmai` (`id_khuyenmai`),
  ADD KEY `qtkm_sanpham` (`id_bienthe`);

--
-- Indexes for table `sanpham_danhmuc`
--
ALTER TABLE `sanpham_danhmuc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanpham` (`id_sanpham`),
  ADD KEY `danhmuc` (`id_danhmuc`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thuonghieu_sanpham` (`id_thuonghieu`);

--
-- Indexes for table `sukien_khuyenmai`
--
ALTER TABLE `sukien_khuyenmai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `donhang_thanhtoan` (`id_donhang`);

--
-- Indexes for table `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uudaithanhvien`
--
ALTER TABLE `uudaithanhvien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yeu_thich`
--
ALTER TABLE `yeu_thich`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `nguoidung_yeuthich` (`id_nguoidung`),
  ADD KEY `sanpham_yeuthich` (`id_sanpham`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anh_sanpham`
--
ALTER TABLE `anh_sanpham`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `bienthe_sp`
--
ALTER TABLE `bienthe_sp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `chitiet_donhang`
--
ALTER TABLE `chitiet_donhang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `diachi_nguoidung`
--
ALTER TABLE `diachi_nguoidung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dieukien_magiamgia`
--
ALTER TABLE `dieukien_magiamgia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loai_bienthe`
--
ALTER TABLE `loai_bienthe`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ma_giamgia`
--
ALTER TABLE `ma_giamgia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quatang_khuyenmai`
--
ALTER TABLE `quatang_khuyenmai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sanpham_danhmuc`
--
ALTER TABLE `sanpham_danhmuc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sukien_khuyenmai`
--
ALTER TABLE `sukien_khuyenmai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `uudaithanhvien`
--
ALTER TABLE `uudaithanhvien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `yeu_thich`
--
ALTER TABLE `yeu_thich`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anh_sanpham`
--
ALTER TABLE `anh_sanpham`
  ADD CONSTRAINT `sanpham_anh` FOREIGN KEY (`id_sanpham`) REFERENCES `san_pham` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `bienthe_sp`
--
ALTER TABLE `bienthe_sp`
  ADD CONSTRAINT `loai_bienthe` FOREIGN KEY (`id_tenloai`) REFERENCES `loai_bienthe` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham_bienthe` FOREIGN KEY (`id_sanpham`) REFERENCES `san_pham` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `chitiet_donhang`
--
ALTER TABLE `chitiet_donhang`
  ADD CONSTRAINT `bienthe_chitietdonhang` FOREIGN KEY (`id_bienthe`) REFERENCES `bienthe_sp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `donhang_chitietdonhang` FOREIGN KEY (`id_donhang`) REFERENCES `don_hang` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `nguoidung_danhgia` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham_danhgia` FOREIGN KEY (`id_sanpham`) REFERENCES `san_pham` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `diachi_nguoidung`
--
ALTER TABLE `diachi_nguoidung`
  ADD CONSTRAINT `nguoidung_diachi` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `dieukien_khuyenmai`
--
ALTER TABLE `dieukien_khuyenmai`
  ADD CONSTRAINT `dieukien_khuyenmai` FOREIGN KEY (`id_khuyenmai`) REFERENCES `khuyenmai` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `khuyenmai_sanpham` FOREIGN KEY (`id_bienthe`) REFERENCES `bienthe_sp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `dieukien_magiamgia`
--
ALTER TABLE `dieukien_magiamgia`
  ADD CONSTRAINT `magiamgia_dieukien` FOREIGN KEY (`id_magiamgia`) REFERENCES `ma_giamgia` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `magiamgia_donhang` FOREIGN KEY (`id_magiamgia`) REFERENCES `ma_giamgia` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `nguoidung_donhang` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `nguoidung_giohang` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham_giohang` FOREIGN KEY (`id_sanpham`) REFERENCES `san_pham` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `lichsu_magiamgia`
--
ALTER TABLE `lichsu_magiamgia`
  ADD CONSTRAINT `lichsugiamgia_donhang` FOREIGN KEY (`id_donhang`) REFERENCES `don_hang` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lichsugiamgia_magiamgia` FOREIGN KEY (`id_voucher`) REFERENCES `ma_giamgia` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lichsugiamgia_nguoidung` FOREIGN KEY (`id_user`) REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `uudai_nguoidung` FOREIGN KEY (`id_uudai`) REFERENCES `uudaithanhvien` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `quatang_khuyenmai`
--
ALTER TABLE `quatang_khuyenmai`
  ADD CONSTRAINT `qtkm_sanpham` FOREIGN KEY (`id_bienthe`) REFERENCES `bienthe_sp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `quatang_khuyenmai` FOREIGN KEY (`id_khuyenmai`) REFERENCES `khuyenmai` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sanpham_danhmuc`
--
ALTER TABLE `sanpham_danhmuc`
  ADD CONSTRAINT `danhmuc` FOREIGN KEY (`id_danhmuc`) REFERENCES `danh_muc` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham` FOREIGN KEY (`id_sanpham`) REFERENCES `san_pham` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `thuonghieu_sanpham` FOREIGN KEY (`id_thuonghieu`) REFERENCES `thuong_hieu` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `donhang_thanhtoan` FOREIGN KEY (`id_donhang`) REFERENCES `don_hang` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `yeu_thich`
--
ALTER TABLE `yeu_thich`
  ADD CONSTRAINT `nguoidung_yeuthich` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham_yeuthich` FOREIGN KEY (`id_sanpham`) REFERENCES `san_pham` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

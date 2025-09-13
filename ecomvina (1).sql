-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2025 at 10:11 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
  `media` text COLLATE utf8mb4_unicode_ci,
  `trang_thai` int DEFAULT NULL,
  `id_sanpham` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anh_sanpham`
--

UPDATE `anh_sanpham` SET `id` = 2,`media` = 'yensaonest100_70ml_2.jpg',`trang_thai` = 0,`id_sanpham` = 1,`created_at` = '2025-08-15 07:25:17',`updated_at` = '2025-08-15 07:25:17' WHERE `anh_sanpham`.`id` = 2;
UPDATE `anh_sanpham` SET `id` = 3,`media` = 'yensaonest100_70ml_3.jpg',`trang_thai` = 0,`id_sanpham` = 1,`created_at` = '2025-08-15 07:25:17',`updated_at` = '2025-08-15 07:25:17' WHERE `anh_sanpham`.`id` = 3;
UPDATE `anh_sanpham` SET `id` = 13,`media` = 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-1.jpg',`trang_thai` = NULL,`id_sanpham` = 6,`created_at` = '2025-08-24 05:20:14',`updated_at` = '2025-08-24 05:20:14' WHERE `anh_sanpham`.`id` = 13;
UPDATE `anh_sanpham` SET `id` = 14,`media` = 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-2.jpg',`trang_thai` = NULL,`id_sanpham` = 6,`created_at` = '2025-08-24 05:20:14',`updated_at` = '2025-08-24 05:20:14' WHERE `anh_sanpham`.`id` = 14;
UPDATE `anh_sanpham` SET `id` = 15,`media` = 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-3.jpg',`trang_thai` = NULL,`id_sanpham` = 6,`created_at` = '2025-08-24 05:20:14',`updated_at` = '2025-08-24 05:20:14' WHERE `anh_sanpham`.`id` = 15;
UPDATE `anh_sanpham` SET `id` = 16,`media` = 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-1.jpg',`trang_thai` = NULL,`id_sanpham` = 7,`created_at` = '2025-08-24 05:30:45',`updated_at` = '2025-08-24 05:30:45' WHERE `anh_sanpham`.`id` = 16;
UPDATE `anh_sanpham` SET `id` = 17,`media` = 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-2.jpg',`trang_thai` = NULL,`id_sanpham` = 7,`created_at` = '2025-08-24 05:30:45',`updated_at` = '2025-08-24 05:30:45' WHERE `anh_sanpham`.`id` = 17;
UPDATE `anh_sanpham` SET `id` = 18,`media` = 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-3.jpg',`trang_thai` = NULL,`id_sanpham` = 7,`created_at` = '2025-08-24 05:30:45',`updated_at` = '2025-08-24 05:30:45' WHERE `anh_sanpham`.`id` = 18;
UPDATE `anh_sanpham` SET `id` = 19,`media` = 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-4.jpg',`trang_thai` = NULL,`id_sanpham` = 7,`created_at` = '2025-08-24 05:30:45',`updated_at` = '2025-08-24 05:30:45' WHERE `anh_sanpham`.`id` = 19;
UPDATE `anh_sanpham` SET `id` = 20,`media` = 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-5.jpg',`trang_thai` = NULL,`id_sanpham` = 7,`created_at` = '2025-08-24 05:30:45',`updated_at` = '2025-08-24 05:30:45' WHERE `anh_sanpham`.`id` = 20;
UPDATE `anh_sanpham` SET `id` = 21,`media` = 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-1.jpg',`trang_thai` = NULL,`id_sanpham` = 8,`created_at` = '2025-09-08 05:21:36',`updated_at` = '2025-09-08 05:21:36' WHERE `anh_sanpham`.`id` = 21;
UPDATE `anh_sanpham` SET `id` = 22,`media` = 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-2.jpg',`trang_thai` = NULL,`id_sanpham` = 8,`created_at` = '2025-09-08 05:21:36',`updated_at` = '2025-09-08 05:21:36' WHERE `anh_sanpham`.`id` = 22;
UPDATE `anh_sanpham` SET `id` = 23,`media` = 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-3.jpg',`trang_thai` = NULL,`id_sanpham` = 8,`created_at` = '2025-09-08 05:21:36',`updated_at` = '2025-09-08 05:21:36' WHERE `anh_sanpham`.`id` = 23;
UPDATE `anh_sanpham` SET `id` = 24,`media` = 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-4.jpg',`trang_thai` = NULL,`id_sanpham` = 8,`created_at` = '2025-09-08 05:21:36',`updated_at` = '2025-09-08 05:21:36' WHERE `anh_sanpham`.`id` = 24;
UPDATE `anh_sanpham` SET `id` = 25,`media` = 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-5.jpg',`trang_thai` = NULL,`id_sanpham` = 8,`created_at` = '2025-09-08 05:21:36',`updated_at` = '2025-09-08 05:21:36' WHERE `anh_sanpham`.`id` = 25;

-- --------------------------------------------------------

--
-- Table structure for table `bienthe_sp`
--

CREATE TABLE `bienthe_sp` (
  `id` int NOT NULL,
  `id_tenloai` int DEFAULT NULL,
  `gia` int DEFAULT NULL,
  `soluong` int DEFAULT NULL,
  `trangthai` int DEFAULT '0' COMMENT 'trạng thái hiển thị (0 hiển thị; 1 ẩn)',
  `uutien` int DEFAULT '0' COMMENT '1 ưu tiên ',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `id_sanpham` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bienthe_sp`
--

UPDATE `bienthe_sp` SET `id` = 1,`id_tenloai` = 1,`gia` = 34500,`soluong` = 20,`trangthai` = 0,`uutien` = 1,`created_at` = '0000-00-00 00:00:00',`updated_at` = '0000-00-00 00:00:00',`id_sanpham` = 1 WHERE `bienthe_sp`.`id` = 1;
UPDATE `bienthe_sp` SET `id` = 13,`id_tenloai` = 9,`gia` = 109000,`soluong` = 3,`trangthai` = 0,`uutien` = 0,`created_at` = '2025-08-24 05:20:14',`updated_at` = '2025-08-24 05:20:14',`id_sanpham` = 6 WHERE `bienthe_sp`.`id` = 13;
UPDATE `bienthe_sp` SET `id` = 14,`id_tenloai` = 2,`gia` = 395000,`soluong` = 291,`trangthai` = 0,`uutien` = 0,`created_at` = '2025-08-24 05:30:45',`updated_at` = '2025-08-24 05:30:45',`id_sanpham` = 7 WHERE `bienthe_sp`.`id` = 14;
UPDATE `bienthe_sp` SET `id` = 15,`id_tenloai` = 10,`gia` = 360000,`soluong` = 1000,`trangthai` = 0,`uutien` = 0,`created_at` = '2025-09-08 05:21:36',`updated_at` = '2025-09-08 05:21:36',`id_sanpham` = 8 WHERE `bienthe_sp`.`id` = 15;

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
  `ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề của sự kiện (ví dụ: "Sale Lớn Cuối Năm - Mua 1 Tặng 1").',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn thân thiện để truy cập trang sự kiện (ví dụ: sale-lon-cuoi-nam-mua-1-tang-1).',
  `media` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn ảnh banner để hiển thị trên popup hoặc đầu trang.',
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung chính của "bài viết". Đây là nơi bạn dùng trình soạn thảo văn bản (WYSIWYG) để viết chi tiết về ưu đãi, thể lệ, điều kiện.',
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
  `noidung` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Nội dung đánh giá',
  `media` mediumtext COLLATE utf8mb4_unicode_ci,
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
  `ten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trangthai` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danh_muc`
--

UPDATE `danh_muc` SET `id` = 1,`ten` = 'Thực phẩm chức năng',`trangthai` = 0,`created_at` = '0000-00-00 00:00:00',`updated_at` = '0000-00-00 00:00:00' WHERE `danh_muc`.`id` = 1;
UPDATE `danh_muc` SET `id` = 2,`ten` = 'Đồ uống',`trangthai` = 0,`created_at` = '2025-08-15 07:39:28',`updated_at` = '2025-08-15 07:39:28' WHERE `danh_muc`.`id` = 2;
UPDATE `danh_muc` SET `id` = 3,`ten` = 'Đồ điện tử',`trangthai` = 0,`created_at` = '2025-08-24 03:17:00',`updated_at` = '2025-08-24 03:17:00' WHERE `danh_muc`.`id` = 3;
UPDATE `danh_muc` SET `id` = 4,`ten` = 'Mỹ phẩm',`trangthai` = 0,`created_at` = '2025-08-24 05:12:05',`updated_at` = '2025-08-24 05:12:05' WHERE `danh_muc`.`id` = 4;

-- --------------------------------------------------------

--
-- Table structure for table `diachi_nguoidung`
--

CREATE TABLE `diachi_nguoidung` (
  `id` int NOT NULL,
  `ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sodienthoai` int DEFAULT NULL,
  `thanhpho` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `xaphuong` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sonha` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `diachi` mediumtext COLLATE utf8mb4_unicode_ci,
  `trangthai` int DEFAULT '1' COMMENT 'kiểu địa chỉ (0 chính ; 1 phụ)',
  `id_nguoidung` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diachi_nguoidung`
--

UPDATE `diachi_nguoidung` SET `id` = 1,`ten` = NULL,`sodienthoai` = NULL,`thanhpho` = 'Taichung',`xaphuong` = 'Shigang',`sonha` = 'No.1240 Fengshi rd',`diachi` = 'No.1240 Fengshi Rd, Shigang District, Taichung City, Taiwan',`trangthai` = 1,`id_nguoidung` = 1 WHERE `diachi_nguoidung`.`id` = 1;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int NOT NULL,
  `ma_donhang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tongtien` int DEFAULT NULL,
  `tongsoluong` int DEFAULT NULL,
  `ghichu` mediumtext COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `loai_bienthe`
--

CREATE TABLE `loai_bienthe` (
  `id` int NOT NULL,
  `ten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loai_bienthe`
--

UPDATE `loai_bienthe` SET `id` = 1,`ten` = 'lọ',`created_at` = '0000-00-00 00:00:00',`updated_at` = '0000-00-00 00:00:00' WHERE `loai_bienthe`.`id` = 1;
UPDATE `loai_bienthe` SET `id` = 2,`ten` = 'hộp',`created_at` = '0000-00-00 00:00:00',`updated_at` = '0000-00-00 00:00:00' WHERE `loai_bienthe`.`id` = 2;
UPDATE `loai_bienthe` SET `id` = 3,`ten` = 'chai',`created_at` = '0000-00-00 00:00:00',`updated_at` = '0000-00-00 00:00:00' WHERE `loai_bienthe`.`id` = 3;
UPDATE `loai_bienthe` SET `id` = 4,`ten` = 'Chiếc',`created_at` = '2025-08-24 04:00:41',`updated_at` = '2025-08-24 04:00:41' WHERE `loai_bienthe`.`id` = 4;
UPDATE `loai_bienthe` SET `id` = 5,`ten` = 'Thùng',`created_at` = '2025-08-24 04:13:36',`updated_at` = '2025-08-24 04:13:36' WHERE `loai_bienthe`.`id` = 5;
UPDATE `loai_bienthe` SET `id` = 6,`ten` = 'Cái',`created_at` = '2025-08-24 04:13:36',`updated_at` = '2025-08-24 04:13:36' WHERE `loai_bienthe`.`id` = 6;
UPDATE `loai_bienthe` SET `id` = 7,`ten` = 'hehe',`created_at` = '2025-08-24 04:19:47',`updated_at` = '2025-08-24 04:19:47' WHERE `loai_bienthe`.`id` = 7;
UPDATE `loai_bienthe` SET `id` = 8,`ten` = 'haha',`created_at` = '2025-08-24 04:20:19',`updated_at` = '2025-08-24 04:20:19' WHERE `loai_bienthe`.`id` = 8;
UPDATE `loai_bienthe` SET `id` = 9,`ten` = 'Lọ (265ml)',`created_at` = '2025-08-24 05:20:14',`updated_at` = '2025-08-24 05:20:14' WHERE `loai_bienthe`.`id` = 9;
UPDATE `loai_bienthe` SET `id` = 10,`ten` = 'Hộp (30 ống)',`created_at` = '2025-09-08 05:21:36',`updated_at` = '2025-09-08 05:21:36' WHERE `loai_bienthe`.`id` = 10;

-- --------------------------------------------------------

--
-- Table structure for table `ma_giamgia`
--

CREATE TABLE `ma_giamgia` (
  `id` int NOT NULL,
  `magiamgia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mota` mediumtext COLLATE utf8mb4_unicode_ci,
  `giatri` int DEFAULT NULL,
  `dieukien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngaybatdau` date DEFAULT NULL,
  `ngayketthuc` date DEFAULT NULL,
  `trangthai` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'customer.png',
  `hoten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gioitinh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `sodienthoai` int DEFAULT NULL,
  `trangthai` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

UPDATE `nguoi_dung` SET `id` = 1,`username` = 'lyhuu123',`email` = 'lyhuu5570@gmail.com',`password` = '13102004caokienhuu',`remember_token` = NULL,`avatar` = 'customer.jpg',`hoten` = 'Cao Kiến Hựu',`gioitinh` = '0',`ngaysinh` = '2004-10-13',`sodienthoai` = 845381121,`trangthai` = 0,`created_at` = '2025-09-09 07:00:08',`updated_at` = '2025-09-09 07:00:08' WHERE `nguoi_dung`.`id` = 1;

-- --------------------------------------------------------

--
-- Table structure for table `quatang_khuyenmai`
--

CREATE TABLE `quatang_khuyenmai` (
  `id` int NOT NULL,
  `id_bienthe` int NOT NULL COMMENT 'Sản phẩm được tặng (liên kết tới bảng san_pham).',
  `soluong` int NOT NULL DEFAULT '1' COMMENT 'Số lượng được tặng của sản phẩm này.',
  `mota` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngaybatdau` datetime NOT NULL,
  `ngayketthuc` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `min_donhang` int NOT NULL,
  `id_thuonghieu` int NOT NULL
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

UPDATE `sanpham_danhmuc` SET `id` = 1,`id_sanpham` = 1,`id_danhmuc` = 1 WHERE `sanpham_danhmuc`.`id` = 1;
UPDATE `sanpham_danhmuc` SET `id` = 2,`id_sanpham` = 1,`id_danhmuc` = 2 WHERE `sanpham_danhmuc`.`id` = 2;
UPDATE `sanpham_danhmuc` SET `id` = 11,`id_sanpham` = 6,`id_danhmuc` = 4 WHERE `sanpham_danhmuc`.`id` = 11;
UPDATE `sanpham_danhmuc` SET `id` = 12,`id_sanpham` = 7,`id_danhmuc` = 2 WHERE `sanpham_danhmuc`.`id` = 12;
UPDATE `sanpham_danhmuc` SET `id` = 13,`id_sanpham` = 8,`id_danhmuc` = 1 WHERE `sanpham_danhmuc`.`id` = 13;

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int NOT NULL,
  `ten` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `xuatxu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sanxuat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

UPDATE `san_pham` SET `id` = 1,`ten` = 'Nước Yến sào Nest100 - Có đường (lọ 70ml)',`mota` = '<p><strong>1. Xuất xứ:</strong> Khánh Hòa, Nha Trang, Việt Nam&nbsp;<br><strong>2. Các chứng nhận và giải thưởng:</strong></p><p>Hàng Việt Nam chất lượng cao 2023</p><p>Hàng Việt Nam chất lượng cao - Chuẩn hội nhập 2023</p><p>TOP thương hiệu số 1 VN 2022</p><p>TOP 100 sản phẩm dịch vụ tốt nhất cho gia đình và trẻ em 2023</p><p>Sản phẩm vàng vì sức khỏe cộng đồng</p><p>Thương hiệu vàng phát triển bền vững thời đại số - 2024</p><p><strong>3. Thành phần:</strong><br>Nước, yến sào đã chế biến (35%), đường phèn (8,5%), chất làm dày (401, 415, 406, 327), chất bảo quản (211), hương tổng hợp dùng cho thực phẩm.<br>BẢO HÀNH&nbsp;<br><strong>Đối tượng sử dụng:</strong> Người lớn &amp; trẻ em từ 1 tuổi&nbsp;<br><strong>Cách dùng:</strong> Uống từ 1-2 lọ/ngày; lắc nhẹ trước khi uống &amp; ngon hơn khi uống lạnh.<br><strong>Bảo quản:</strong> Nhiệt độ thường, nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp. <strong>Thông tin cảnh báo an toàn:</strong> Không sử dụng khi sản phẩm hết hạn sử dụng hoặc có mùi vị lạ.</p>',`xuatxu` = 'Việt Nam',`sanxuat` = 'Việt Nam',`mediaurl` = NULL,`trangthai` = 0,`luotxem` = NULL,`created_at` = '2025-08-18 07:12:41',`updated_at` = '2025-08-24 05:01:40',`id_thuonghieu` = 1 WHERE `san_pham`.`id` = 1;
UPDATE `san_pham` SET `id` = 6,`ten` = 'Sữa Tắm Nước Hoa Dưỡng Da Parisian Chic for Her 265ml',`mota` = '<p><strong>PARISIAN PERFUMED SHOWER GEL – CHIC FOR HER 265ML</strong><br>Sữa tắm Nước Hoa Parisian giúp làm sạch và dưỡng ẩm da, mang lại làn da trông tươi sáng và mịn màng, cùng hương nước hoa quyến rũ và thanh lịch.</p><p><strong>Tầng hương</strong><br><u>Hương đầu:</u> Quả Lý Chua Đen, Cam Bergamot, Quả Mâm Xôi<br><u>Hương giữa:</u> Hoa Diên Vĩ, Hoa Hồng, Hoa Lan Nam Phi<br><u>Hương cuối:</u> Xạ Hương, Hương Vani, Gourmand<br>&nbsp;</p><p><strong>Thành phần:</strong> Water, Sodium Laureth Sulfate, Cocamidopropyl Betaine, Glycerin, PEG-7 Glyceryl Cocoate, Cocamide DEA, Fragrance, Polyquaternium-7, Sodium Chloride, Citric Acid, Tocopheryl Acetate, Macadamia Integrifolia Seed Oil, Glycine Soja (Soybean) Oil, Persea Gratissima (Avocado) Oil, Simmondsia Chinensis (Jojoba) Seed Oil, Bisabolol, Triticum Vulgare (Wheat) Bran Extract, Calendula Officinalis Flower Extract, Chamomilla Recutita (Matricaria) Flower Extract, Fucus Vesiculosus Extract, Methylchloroisothiazolinone, Methylisothiazolinone.</p><p><strong>Thể tích thực:</strong> 265 ml</p><p><strong>Hướng dẫn sử dụng:</strong> Cho một lượng vừa đủ sữa tắm vào lòng bàn tay hay bông tắm. Tạo bọt và xoa đều lên da. Sau đó, tắm sạch với nước.</p><p><strong>Hướng dẫn bảo quản:</strong> Để nơi khô ráo, thoáng mát. Không để nơi có nhiệt độ cao. Tránh ánh nắng trực tiếp.</p><p><strong>Lưu ý:</strong> Tránh tiếp xúc với mắt. Nếu sản phẩm dính vào mắt hãy rửa kỹ với nước. Để ngoài tầm tay trẻ em.</p><p>--------------------------------------------</p><p><strong>Chịu trách nhiệm đưa sản phẩm ra thị trường: CÔNG TY CỔ PHẦN VENUS INC. VIETNAM</strong><br>- Tầng 16, Tòa Nhà Saigon Tower Số 29 Đường Lê Duẩn, Phường Bến Nghé, Quận 1, Thành phố Hồ Chí Minh, Việt Nam.</p><p><strong>Sản xuất tại Việt Nam.</strong><br><strong>Ngày sản xuất và Hạn sử dụng:</strong> Xem trên bao bì.<br>&nbsp;</p>',`xuatxu` = 'Việt Nam',`sanxuat` = 'Việt Nam',`mediaurl` = NULL,`trangthai` = 0,`luotxem` = NULL,`created_at` = '2025-08-24 05:20:14',`updated_at` = '2025-08-24 05:22:40',`id_thuonghieu` = 3 WHERE `san_pham`.`id` = 6;
UPDATE `san_pham` SET `id` = 7,`ten` = 'Cà phê dừa Cappuccino Collagen - Giúp tỉnh táo, đẹp da (20 gói x 18g)',`mota` = '<p><span style=\"font-size:18px;\"><strong>Cà phê dừa Cappuccino Collagen - Giúp tỉnh táo, đẹp da (20 gói x 18g)</strong></span></p><p><strong>Mô tả ngắn:</strong>&nbsp;TPBS Cappuccino Collagen -&nbsp;Cà Phê Cappuccino Collagen Vị Dừa là sự kết hợp hoàn hảo giữa vị bùi béo của dừa sánh quyện cùng hương cà phê nồng nàn, tinh tế không chỉ giúp cho tinh thần sảng khoái để bắt đầu một ngày mới thật năng động, lượng collagen nguyên chất được thêm vào sản phẩm còn hỗ trợ làm giảm quá trình lão hóa da, hỗ trợ tốt cho đường tiêu hóa.</p><p><strong>Thông tin sản phẩm chi tiết:&nbsp;</strong></p><p><strong>1. Tên sản phẩm:</strong> TPBS Cappuccino Collagen - Cà phê Cappuccino Collagen vị Dừa</p><p><strong>2. Thương hiệu:</strong> Wellness By Life Gift VN</p><p><strong>3. Xuất xứ:</strong>&nbsp;Việt Nam</p><p><strong>4. Thành phần:&nbsp;</strong><br>Bột sữa dừa: 20%Bột cà phê hòa tan: 12,5%Đường, bột kem thực vật, Maltodextrin, Collagen, muối i-ốt, DL - alpha tocopherol.&nbsp;</p><p><strong>5. Cách dùng sản phẩm:</strong><br><u>Uống nóng:</u> Hòa tan 1 gói cà phê với 70ml nước nóng, sau đó khuấy đều.<br><u>Uống lạnh:</u> Hòa tan 1 gói cà phê với 60ml nước nóng, sau đó khuấy đều, thêm đá.<br><u>Đá xay: </u>Hòa tan 2 gói cà phê với 50ml nước nóng, sau đó khuấy đều, thêm 8-10 viên đá và xay nhuyễn.</p><p><strong>7. Quy cách:</strong> 20 gói/hộp. Một gói có định lượng là 18g.</p><p><strong>8. Hạn sử dụng sản phẩm:</strong> 36 tháng kể từ ngày sản xuất. Ngày sản xuất và hạn sử dụng được ghi trên nhãn chính của sản phẩm.&nbsp;</p><p><strong>9. Bảo quản:</strong> Bảo quản ở nơi khô ráo và thoáng mát, tránh ánh nắng chiếu trực tiếp.&nbsp;</p><p><strong>10. Chú ý:&nbsp;</strong>Không sử dụng sản phẩm khi phát hiện tình trạng hư hỏng, nấm mốc hoặc hết hạn sử dụng.&nbsp;</p>',`xuatxu` = 'Việt Nam',`sanxuat` = 'Việt Nam',`mediaurl` = NULL,`trangthai` = 0,`luotxem` = NULL,`created_at` = '2025-08-24 05:30:45',`updated_at` = '2025-08-24 05:30:45',`id_thuonghieu` = 1 WHERE `san_pham`.`id` = 7;
UPDATE `san_pham` SET `id` = 8,`ten` = 'Thực phẩm bảo vệ sức khỏe: Midu MenaQ7 180mcg',`mota` = '**1. Mô tả ngắn:** **Thực phẩm bảo vệ sức khỏe: MiduMenaQ7 180mcg**\r\nMidu MenaQ7 180mcg bổ sung canxi, Vitamin D3, Vitamin K2 dạng MenaQ7 và Arginine phù hợp với tất cả độ tuổi từ 1 đến 100 tuổi. Đặc biệt giúp phát triển chiều cao cho trẻ em 1-15 tuổi; mẹ bầu bổ sung canxi trong giai đoạn thai kì không gây tiểu đường, không gây táo bón và giúp con cao ngay từ trong bụng mẹ.\r\n\r\n**2. Thương hiệu:** Midu MenaQ7\r\n\r\n**3. Xuất xứ:** Việt Nam\r\n\r\n**4. Thành phần:** **10 ml dung dịch uống chứa:**\r\n\r\n|                             |               |\r\n| --------------------------- | ------------- |\r\n| **Thành phần**              | **Hàm lượng** |\r\n| Calci glucoheptonate        | 1100 mg       |\r\n| L-Arginine HCl              | 100 mg        |\r\n| MenaQ7 (Vitamin K2 - MK-7)  | 180 mcg       |\r\n| Magnesi gluconat            | 200 mg        |\r\n| Vitamin D3 (Colecalciferol) | 800 IU        |\r\n| Vitamin PP (Nicotinamid)    | 40 mg         |\r\n| Vitamin B6 (Pyridoxin HCI)  | 3 mg          |\r\n\r\n**Các thành phần khác (phụ liệu):** Methyl parahydroxybenzoat (INS 218), propyl parahydroxybenzoat (INS 216), dung dịch sorbitol (INS 420i), natri edetat (INS 386), acid boric, PEG 400 (INS 1521), acrysol K140, butyl hydroxy toluen (INS 321), sucralose (INS 955), natri lauryl sulfat, caramen, dung dịch hương vanilin, dung dịch hương cam, ethanol 96%, nước tinh khiết.\r\n\r\n**5. Công dụng:&#x20;**&#x42;ổ sung calci, menaQ7 (vitamin K2 – MK-7),vitamin D3 cho cơ thể, hỗ trợ xương răng chắc khỏe, giảm nguy cơ thiếu hụt calci cho trẻ em và người cao tuổi, phụ nữ có thai. \r\n\r\n**6. Đối tượng sử dụng:** Trẻ em, thanh thiếu niên đang trong thời kỳ phát triển chiều cao cần bổ sung calci. - Người cao tuổi bị loãng xương. - Phụ nữ có thai hoặc cho con bú cần bổ sug canxi\r\n\r\n**7. Quy cách:** Hộp 30 ống\r\n\r\n**8. NSX & HSD:** 36 tháng kể từ ngày sản xuất\r\n\r\n**Lưu ý:**\r\n– Sản phẩm này không phải là thuốc, không có tác dụng thay thế thuốc chữa bệnh.\r\n– Không sử dụng cho người mẫn cảm với bất kỳ thành phần nào của sản phẩm.',`xuatxu` = 'Việt Nam',`sanxuat` = 'Việt Nam',`mediaurl` = NULL,`trangthai` = 0,`luotxem` = NULL,`created_at` = '2025-09-08 05:21:35',`updated_at` = '2025-09-08 05:46:29',`id_thuonghieu` = 1 WHERE `san_pham`.`id` = 8;

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
  `nganhang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gia` int DEFAULT NULL,
  `noidung` mediumtext COLLATE utf8mb4_unicode_ci,
  `magiaodich` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

UPDATE `thuong_hieu` SET `id` = 1,`ten` = 'NEST100',`mota` = NULL,`trangthai` = 0,`created_at` = '0000-00-00 00:00:00',`updated_at` = '0000-00-00 00:00:00' WHERE `thuong_hieu`.`id` = 1;
UPDATE `thuong_hieu` SET `id` = 3,`ten` = 'GENNIE',`mota` = '<p><strong>THƯƠNG HIỆU GENNIE - SẢN XUẤT MỸ PHẨM HÀNG ĐẦU TẠI VIỆT NAM</strong></p>',`trangthai` = 0,`created_at` = '2025-08-24 05:21:01',`updated_at` = '2025-08-24 05:21:01' WHERE `thuong_hieu`.`id` = 3;
UPDATE `thuong_hieu` SET `id` = 4,`ten` = 'LIFE GIFT VN',`mota` = '<p>THƯƠNG HIỆU LIFT GIFT VN - SẢN XUẤT HÀNG ĐẦU TẠI VIỆT NAM</p>',`trangthai` = 0,`created_at` = '2025-08-24 05:31:17',`updated_at` = '2025-08-24 05:31:57' WHERE `thuong_hieu`.`id` = 4;
UPDATE `thuong_hieu` SET `id` = 5,`ten` = 'MIDU MENAQ7',`mota` = 'Midu MenaQ7 - Phát triển chiều cao tổng thể, nâng tầm vóc người Việt',`trangthai` = 0,`created_at` = '2025-09-08 05:08:07',`updated_at` = '2025-09-08 05:08:07' WHERE `thuong_hieu`.`id` = 5;

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
-- Indexes for table `chuongtrinhsukien`
--
ALTER TABLE `chuongtrinhsukien`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `loai_bienthe`
--
ALTER TABLE `loai_bienthe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ma_giamgia`
--
ALTER TABLE `ma_giamgia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quatang_khuyenmai`
--
ALTER TABLE `quatang_khuyenmai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bienthe` (`id_bienthe`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sukien` (`id_sukien`),
  ADD KEY `sukien_khuyenmai_ibfk_1` (`id_khuyenmai`);

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
-- Indexes for table `yeu_thich`
--
ALTER TABLE `yeu_thich`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoidung_yeuthich` (`id_nguoidung`),
  ADD KEY `sanpham_yeuthich` (`id_sanpham`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anh_sanpham`
--
ALTER TABLE `anh_sanpham`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bienthe_sp`
--
ALTER TABLE `bienthe_sp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `chitiet_donhang`
--
ALTER TABLE `chitiet_donhang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chuongtrinhsukien`
--
ALTER TABLE `chuongtrinhsukien`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `diachi_nguoidung`
--
ALTER TABLE `diachi_nguoidung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `loai_bienthe`
--
ALTER TABLE `loai_bienthe`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ma_giamgia`
--
ALTER TABLE `ma_giamgia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `quatang_khuyenmai`
--
ALTER TABLE `quatang_khuyenmai`
  ADD CONSTRAINT `quatang_khuyenmai_ibfk_1` FOREIGN KEY (`id_bienthe`) REFERENCES `bienthe_sp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
-- Constraints for table `sukien_khuyenmai`
--
ALTER TABLE `sukien_khuyenmai`
  ADD CONSTRAINT `sukien_khuyenmai_ibfk_1` FOREIGN KEY (`id_khuyenmai`) REFERENCES `quatang_khuyenmai` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sukien_khuyenmai_ibfk_2` FOREIGN KEY (`id_sukien`) REFERENCES `chuongtrinhsukien` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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

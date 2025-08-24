-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 24, 2025 at 06:33 AM
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

INSERT INTO `anh_sanpham` (`id`, `media`, `trang_thai`, `id_sanpham`, `created_at`, `updated_at`) VALUES
(2, 'yensaonest100_70ml_2.jpg', 0, 1, '2025-08-15 07:25:17', '2025-08-15 07:25:17'),
(3, 'yensaonest100_70ml_3.jpg', 0, 1, '2025-08-15 07:25:17', '2025-08-15 07:25:17'),
(13, 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-1.jpg', NULL, 6, '2025-08-24 05:20:14', '2025-08-24 05:20:14'),
(14, 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-2.jpg', NULL, 6, '2025-08-24 05:20:14', '2025-08-24 05:20:14'),
(15, 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-3.jpg', NULL, 6, '2025-08-24 05:20:14', '2025-08-24 05:20:14'),
(16, 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-1.jpg', NULL, 7, '2025-08-24 05:30:45', '2025-08-24 05:30:45'),
(17, 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-2.jpg', NULL, 7, '2025-08-24 05:30:45', '2025-08-24 05:30:45'),
(18, 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-3.jpg', NULL, 7, '2025-08-24 05:30:45', '2025-08-24 05:30:45'),
(19, 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-4.jpg', NULL, 7, '2025-08-24 05:30:45', '2025-08-24 05:30:45'),
(20, 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-5.jpg', NULL, 7, '2025-08-24 05:30:45', '2025-08-24 05:30:45');

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

INSERT INTO `bienthe_sp` (`id`, `id_tenloai`, `gia`, `soluong`, `trangthai`, `uutien`, `created_at`, `updated_at`, `id_sanpham`) VALUES
(1, 1, 34500, 20, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(13, 9, 109000, 3, 0, 0, '2025-08-24 05:20:14', '2025-08-24 05:20:14', 6),
(14, 2, 395000, 291, 0, 0, '2025-08-24 05:30:45', '2025-08-24 05:30:45', 7);

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

INSERT INTO `danh_muc` (`id`, `ten`, `trangthai`, `created_at`, `updated_at`) VALUES
(1, 'Thực phẩm chức năng', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Đồ uống', 0, '2025-08-15 07:39:28', '2025-08-15 07:39:28'),
(3, 'Đồ điện tử', 0, '2025-08-24 03:17:00', '2025-08-24 03:17:00'),
(4, 'Mỹ phẩm', 0, '2025-08-24 05:12:05', '2025-08-24 05:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `diachi_nguoidung`
--

CREATE TABLE `diachi_nguoidung` (
  `id` int NOT NULL,
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

INSERT INTO `diachi_nguoidung` (`id`, `thanhpho`, `xaphuong`, `sonha`, `diachi`, `trangthai`, `id_nguoidung`) VALUES
(1, 'Taichung', 'Shigang', 'No.1240 Fengshi rd', 'No.1240 Fengshi Rd, Shigang District, Taichung City, Taiwan', 1, 1);

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

INSERT INTO `loai_bienthe` (`id`, `ten`, `created_at`, `updated_at`) VALUES
(1, 'lọ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'hộp', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'chai', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Chiếc', '2025-08-24 04:00:41', '2025-08-24 04:00:41'),
(5, 'Thùng', '2025-08-24 04:13:36', '2025-08-24 04:13:36'),
(6, 'Cái', '2025-08-24 04:13:36', '2025-08-24 04:13:36'),
(7, 'hehe', '2025-08-24 04:19:47', '2025-08-24 04:19:47'),
(8, 'haha', '2025-08-24 04:20:19', '2025-08-24 04:20:19'),
(9, 'Lọ (265ml)', '2025-08-24 05:20:14', '2025-08-24 05:20:14');

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
  `hoten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gioitinh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(11, 6, 4),
(12, 7, 2);

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

INSERT INTO `san_pham` (`id`, `ten`, `mota`, `xuatxu`, `sanxuat`, `mediaurl`, `trangthai`, `luotxem`, `created_at`, `updated_at`, `id_thuonghieu`) VALUES
(1, 'Nước Yến sào Nest100 - Có đường (lọ 70ml)', '<p><strong>1. Xuất xứ:</strong> Khánh Hòa, Nha Trang, Việt Nam&nbsp;<br><strong>2. Các chứng nhận và giải thưởng:</strong></p><p>Hàng Việt Nam chất lượng cao 2023</p><p>Hàng Việt Nam chất lượng cao - Chuẩn hội nhập 2023</p><p>TOP thương hiệu số 1 VN 2022</p><p>TOP 100 sản phẩm dịch vụ tốt nhất cho gia đình và trẻ em 2023</p><p>Sản phẩm vàng vì sức khỏe cộng đồng</p><p>Thương hiệu vàng phát triển bền vững thời đại số - 2024</p><p><strong>3. Thành phần:</strong><br>Nước, yến sào đã chế biến (35%), đường phèn (8,5%), chất làm dày (401, 415, 406, 327), chất bảo quản (211), hương tổng hợp dùng cho thực phẩm.<br>BẢO HÀNH&nbsp;<br><strong>Đối tượng sử dụng:</strong> Người lớn &amp; trẻ em từ 1 tuổi&nbsp;<br><strong>Cách dùng:</strong> Uống từ 1-2 lọ/ngày; lắc nhẹ trước khi uống &amp; ngon hơn khi uống lạnh.<br><strong>Bảo quản:</strong> Nhiệt độ thường, nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp. <strong>Thông tin cảnh báo an toàn:</strong> Không sử dụng khi sản phẩm hết hạn sử dụng hoặc có mùi vị lạ.</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-18 07:12:41', '2025-08-24 05:01:40', 1),
(6, 'Sữa Tắm Nước Hoa Dưỡng Da Parisian Chic for Her 265ml', '<p><strong>PARISIAN PERFUMED SHOWER GEL – CHIC FOR HER 265ML</strong><br>Sữa tắm Nước Hoa Parisian giúp làm sạch và dưỡng ẩm da, mang lại làn da trông tươi sáng và mịn màng, cùng hương nước hoa quyến rũ và thanh lịch.</p><p><strong>Tầng hương</strong><br><u>Hương đầu:</u> Quả Lý Chua Đen, Cam Bergamot, Quả Mâm Xôi<br><u>Hương giữa:</u> Hoa Diên Vĩ, Hoa Hồng, Hoa Lan Nam Phi<br><u>Hương cuối:</u> Xạ Hương, Hương Vani, Gourmand<br>&nbsp;</p><p><strong>Thành phần:</strong> Water, Sodium Laureth Sulfate, Cocamidopropyl Betaine, Glycerin, PEG-7 Glyceryl Cocoate, Cocamide DEA, Fragrance, Polyquaternium-7, Sodium Chloride, Citric Acid, Tocopheryl Acetate, Macadamia Integrifolia Seed Oil, Glycine Soja (Soybean) Oil, Persea Gratissima (Avocado) Oil, Simmondsia Chinensis (Jojoba) Seed Oil, Bisabolol, Triticum Vulgare (Wheat) Bran Extract, Calendula Officinalis Flower Extract, Chamomilla Recutita (Matricaria) Flower Extract, Fucus Vesiculosus Extract, Methylchloroisothiazolinone, Methylisothiazolinone.</p><p><strong>Thể tích thực:</strong> 265 ml</p><p><strong>Hướng dẫn sử dụng:</strong> Cho một lượng vừa đủ sữa tắm vào lòng bàn tay hay bông tắm. Tạo bọt và xoa đều lên da. Sau đó, tắm sạch với nước.</p><p><strong>Hướng dẫn bảo quản:</strong> Để nơi khô ráo, thoáng mát. Không để nơi có nhiệt độ cao. Tránh ánh nắng trực tiếp.</p><p><strong>Lưu ý:</strong> Tránh tiếp xúc với mắt. Nếu sản phẩm dính vào mắt hãy rửa kỹ với nước. Để ngoài tầm tay trẻ em.</p><p>--------------------------------------------</p><p><strong>Chịu trách nhiệm đưa sản phẩm ra thị trường: CÔNG TY CỔ PHẦN VENUS INC. VIETNAM</strong><br>- Tầng 16, Tòa Nhà Saigon Tower Số 29 Đường Lê Duẩn, Phường Bến Nghé, Quận 1, Thành phố Hồ Chí Minh, Việt Nam.</p><p><strong>Sản xuất tại Việt Nam.</strong><br><strong>Ngày sản xuất và Hạn sử dụng:</strong> Xem trên bao bì.<br>&nbsp;</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-24 05:20:14', '2025-08-24 05:22:40', 3),
(7, 'Cà phê dừa Cappuccino Collagen - Giúp tỉnh táo, đẹp da (20 gói x 18g)', '<p><span style=\"font-size:18px;\"><strong>Cà phê dừa Cappuccino Collagen - Giúp tỉnh táo, đẹp da (20 gói x 18g)</strong></span></p><p><strong>Mô tả ngắn:</strong>&nbsp;TPBS Cappuccino Collagen -&nbsp;Cà Phê Cappuccino Collagen Vị Dừa là sự kết hợp hoàn hảo giữa vị bùi béo của dừa sánh quyện cùng hương cà phê nồng nàn, tinh tế không chỉ giúp cho tinh thần sảng khoái để bắt đầu một ngày mới thật năng động, lượng collagen nguyên chất được thêm vào sản phẩm còn hỗ trợ làm giảm quá trình lão hóa da, hỗ trợ tốt cho đường tiêu hóa.</p><p><strong>Thông tin sản phẩm chi tiết:&nbsp;</strong></p><p><strong>1. Tên sản phẩm:</strong> TPBS Cappuccino Collagen - Cà phê Cappuccino Collagen vị Dừa</p><p><strong>2. Thương hiệu:</strong> Wellness By Life Gift VN</p><p><strong>3. Xuất xứ:</strong>&nbsp;Việt Nam</p><p><strong>4. Thành phần:&nbsp;</strong><br>Bột sữa dừa: 20%Bột cà phê hòa tan: 12,5%Đường, bột kem thực vật, Maltodextrin, Collagen, muối i-ốt, DL - alpha tocopherol.&nbsp;</p><p><strong>5. Cách dùng sản phẩm:</strong><br><u>Uống nóng:</u> Hòa tan 1 gói cà phê với 70ml nước nóng, sau đó khuấy đều.<br><u>Uống lạnh:</u> Hòa tan 1 gói cà phê với 60ml nước nóng, sau đó khuấy đều, thêm đá.<br><u>Đá xay: </u>Hòa tan 2 gói cà phê với 50ml nước nóng, sau đó khuấy đều, thêm 8-10 viên đá và xay nhuyễn.</p><p><strong>7. Quy cách:</strong> 20 gói/hộp. Một gói có định lượng là 18g.</p><p><strong>8. Hạn sử dụng sản phẩm:</strong> 36 tháng kể từ ngày sản xuất. Ngày sản xuất và hạn sử dụng được ghi trên nhãn chính của sản phẩm.&nbsp;</p><p><strong>9. Bảo quản:</strong> Bảo quản ở nơi khô ráo và thoáng mát, tránh ánh nắng chiếu trực tiếp.&nbsp;</p><p><strong>10. Chú ý:&nbsp;</strong>Không sử dụng sản phẩm khi phát hiện tình trạng hư hỏng, nấm mốc hoặc hết hạn sử dụng.&nbsp;</p>', 'Việt Nam', 'Việt Nam', NULL, 0, NULL, '2025-08-24 05:30:45', '2025-08-24 05:30:45', 1);

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

INSERT INTO `thuong_hieu` (`id`, `ten`, `mota`, `trangthai`, `created_at`, `updated_at`) VALUES
(1, 'NEST100', NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'GENNIE', '<p><strong>THƯƠNG HIỆU GENNIE - SẢN XUẤT MỸ PHẨM HÀNG ĐẦU TẠI VIỆT NAM</strong></p>', 0, '2025-08-24 05:21:01', '2025-08-24 05:21:01'),
(4, 'LIFE GIFT VN', '<p>THƯƠNG HIỆU LIFT GIFT VN - SẢN XUẤT HÀNG ĐẦU TẠI VIỆT NAM</p>', 0, '2025-08-24 05:31:17', '2025-08-24 05:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `uudaithanhvien`
--

CREATE TABLE `uudaithanhvien` (
  `id` int NOT NULL,
  `hanmuc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `giamgia` float DEFAULT NULL,
  `dieukien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uudaithanhvien`
--

INSERT INTO `uudaithanhvien` (`id`, `hanmuc`, `giamgia`, `dieukien`) VALUES
(1, 'Thành Viên Mới', 0, '0'),
(2, 'Bậc Sắt', 5, '100000'),
(3, 'Bậc Đồng', 10, '500000'),
(4, 'Bậc Bạc', 15, '1000000'),
(5, 'Bậc Vàng', 20, '2000000'),
(6, 'Bậc Bạch Kim', 25, '3000000'),
(7, 'Bậc Kim Cương', 30, '4000000'),
(8, 'Siêu Tân Tinh', 40, '5000000');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `uudai_nguoidung` (`id_uudai`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bienthe_sp`
--
ALTER TABLE `bienthe_sp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT for table `sanpham_danhmuc`
--
ALTER TABLE `sanpham_danhmuc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `uudai_nguoidung` FOREIGN KEY (`id_uudai`) REFERENCES `uudaithanhvien` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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

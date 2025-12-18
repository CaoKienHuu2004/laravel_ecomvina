<?php

namespace App\Observers;

use App\Models\GiohangModel;
use App\Models\BientheModel;
use Illuminate\Support\Facades\DB;

/**
 * Observer xử lý các hành vi liên quan đến model GiohangModel
 *
 * Công dụng chính:
 * 1. Thay thế trigger SQL BEFORE INSERT trong bảng 'giohang'.
 * 2. Tự động tính toán cột 'thanhtien' dựa trên:
 *    - Giá gốc của biến thể sản phẩm (giagoc)
 *    - Số lượng sản phẩm trong giỏ (soluong)
 *    - Các chương trình quà tặng còn hiệu lực (quatang_sukien)
 * 3. Cập nhật số lượt tặng ('luottang') của biến thể sản phẩm nếu áp dụng ưu đãi.
 */
class GioHangObserver
{
    public function creating(GiohangModel $gioHang)
    {
        $this->calculatePrice($gioHang);
    }

    public function updating(GiohangModel $gioHang)
    {
        if ($gioHang->isDirty('soluong')) {
            $this->calculatePrice($gioHang);
        }
    }

    protected function calculatePrice(GiohangModel $gioHang)
    {
        // 🔒 Khóa biến thể
        $bienthe = BientheModel::with('sanpham')
            ->lockForUpdate()
            ->find($gioHang->id_bienthe);

        if (!$bienthe || !$bienthe->sanpham) {
            return;
        }

        $quantity = (int) $gioHang->soluong;

        /**
         * 1️⃣ GIÁ GỐC
         */
        $giaGoc = (int) $bienthe->giagoc;

        /**
         * 2️⃣ GIẢM GIÁ % TỪ SẢN PHẨM
         */
        $phanTramGiam = (int) $bienthe->sanpham->giamgia; // ví dụ: 10 = 10%

        $donGiaSauGiam = $giaGoc;
        if ($phanTramGiam > 0) {
            $donGiaSauGiam = (int) round(
                $giaGoc * (100 - $phanTramGiam) / 100
            );
        }

        /**
         * 3️⃣ TỔNG GIỎ (runtime, controller set)
         */
        $tongGioHang = (int) ($gioHang->tong_gio_hang ?? 0);

        /**
         * 4️⃣ KIỂM TRA QUÀ TẶNG
         */
        $promotion = DB::table('quatang_sukien as qs')
            ->where('qs.id_bienthe', $gioHang->id_bienthe)
            ->where('qs.trangthai', 'Hiển thị')
            ->where('qs.dieukiensoluong', '<=', $quantity)
            ->where('qs.dieukiengiatri', '<=', $tongGioHang)
            ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
            ->first();

        if ($promotion) {
            $promotionCount = intdiv(
                $quantity,
                (int) $promotion->dieukiensoluong
            );

            $numFree = max($promotionCount, 0);
            $numPay  = max($quantity - $numFree, 0);

            $gioHang->thanhtien = $numPay * $donGiaSauGiam;
        } else {
            $gioHang->thanhtien = $quantity * $donGiaSauGiam;
        }
    }
}
///// mua 2  tặng 1 chỉ trừ tiền tính tiền 1

// DELIMITER //

// CREATE TRIGGER cap_nhat_thanhtien_giohang_TRICKY
// BEFORE INSERT ON giohang
// FOR EACH ROW
// BEGIN
//     DECLARE promotion_count INT DEFAULT 0;
//     DECLARE discount_multiplier INT DEFAULT 0;
//     DECLARE price_unit DECIMAL(10, 2);
//     DECLARE num_to_pay INT DEFAULT 0;
//     DECLARE num_free INT DEFAULT 0;
//     DECLARE current_luottang INT DEFAULT 0;

//     -- Lấy thông tin ưu đãi
//     SELECT
//         bt.giagoc,
//         qs.dieukien,
//         bt.luottang
//     INTO
//         price_unit,
//         discount_multiplier,
//         current_luottang
//     FROM quatang_sukien AS qs
//     JOIN bienthe AS bt ON NEW.id_bienthe = bt.id
//     WHERE
//         qs.id_bienthe = NEW.id_bienthe
//         AND bt.luottang > 0
//         AND NEW.soluong >= qs.dieukien
//         AND NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc
//     LIMIT 1;

//     -- Nếu có ưu đãi
//     IF price_unit IS NOT NULL AND discount_multiplier > 0 THEN
//         SET promotion_count = FLOOR(NEW.soluong / discount_multiplier);
//         SET num_free = LEAST(promotion_count, current_luottang);
//         SET num_to_pay = NEW.soluong - num_free;

//         -- Tính thành tiền
//         SET NEW.thanhtien = num_to_pay * price_unit;

//         -- Giảm lượt tặng
//         UPDATE bienthe
//         SET luottang = luottang - num_free
//         WHERE id = NEW.id_bienthe;
//     ELSE
//         -- Không có ưu đãi
//         IF price_unit IS NULL THEN
//             SELECT giagoc INTO price_unit FROM bienthe WHERE id = NEW.id_bienthe;
//         END IF;
//         SET NEW.thanhtien = NEW.soluong * price_unit;
//     END IF;
// END//

// DELIMITER ;


// dùng cho giỏ hang update số lượng sản phẩm trong giỏ hàng
// DELIMITER //

// CREATE TRIGGER cap_nhat_thanhtien_giohang_UPDATE
// BEFORE UPDATE ON giohang
// FOR EACH ROW
// BEGIN
//     DECLARE promotion_count INT DEFAULT 0;
//     DECLARE discount_multiplier INT DEFAULT 0;
//     DECLARE price_unit DECIMAL(10, 2);
//     DECLARE num_to_pay INT DEFAULT 0;
//     DECLARE num_free INT DEFAULT 0;
//     DECLARE current_luottang INT DEFAULT 0;

//     -- Lấy thông tin ưu đãi
//     SELECT
//         bt.giagoc,
//         qs.dieukien,
//         bt.luottang
//     INTO
//         price_unit,
//         discount_multiplier,
//         current_luottang
//     FROM quatang_sukien AS qs
//     JOIN bienthe AS bt ON NEW.id_bienthe = bt.id
//     WHERE
//         qs.id_bienthe = NEW.id_bienthe
//         AND bt.luottang > 0
//         AND NEW.soluong >= qs.dieukien
//         AND NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc
//     LIMIT 1;

//     -- Nếu có ưu đãi
//     IF price_unit IS NOT NULL AND discount_multiplier > 0 THEN
//         SET promotion_count = FLOOR(NEW.soluong / discount_multiplier);
//         SET num_free = LEAST(promotion_count, current_luottang);
//         SET num_to_pay = NEW.soluong - num_free;
//         SET NEW.thanhtien = num_to_pay * price_unit;
//     ELSE
//         -- Không có ưu đãi
//         IF price_unit IS NULL THEN
//             SELECT giagoc INTO price_unit FROM bienthe WHERE id = NEW.id_bienthe;
//         END IF;
//         SET NEW.thanhtien = NEW.soluong * price_unit;
//     END IF;
// END//

// DELIMITER ;
///// mua 2  tặng 1 chỉ trừ tiền tính tiền 1


///// mua 2  tặng 1 thêm 1 bienthe tặng, 2 bienth góc giử nguyên
// CREATE TABLE IF NOT EXISTS giohang_quatang_queue (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     id_nguoidung INT NOT NULL,
//     id_bienthe INT NOT NULL,
//     soluong INT DEFAULT 1,
//     thanhtien DECIMAL(10,2) DEFAULT 0,
//     trangthai VARCHAR(50) DEFAULT 'Hiển thị',
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );

// DELIMITER //
// CREATE TRIGGER cap_nhat_thanhtien_giohang_TRICKY
// BEFORE INSERT ON giohang
// FOR EACH ROW
// BEGIN
//     DECLARE promotion_count INT DEFAULT 0;
//     DECLARE discount_multiplier INT DEFAULT 0;
//     DECLARE price_unit DECIMAL(10, 2);
//     DECLARE num_to_pay INT DEFAULT 0;
//     DECLARE num_free INT DEFAULT 0;
//     DECLARE current_luottang INT DEFAULT 0;

//     -- 1️⃣ Lấy dữ liệu ưu đãi (nếu có)
//     SELECT
//         bt.giagoc,
//         qs.dieukien,
//         bt.luottang
//     INTO
//         price_unit,
//         discount_multiplier,
//         current_luottang
//     FROM quatang_sukien AS qs
//     JOIN bienthe AS bt ON NEW.id_bienthe = bt.id
//     WHERE
//         qs.id_bienthe = NEW.id_bienthe
//         AND bt.luottang > 0
//         AND NEW.soluong >= qs.dieukien
//         AND NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc
//     LIMIT 1;

//     -- 2️⃣ Nếu có ưu đãi
//     IF price_unit IS NOT NULL AND discount_multiplier > 0 THEN
//         SET promotion_count = FLOOR(NEW.soluong / discount_multiplier);
//         SET num_free = LEAST(promotion_count, current_luottang);
//         SET num_to_pay = NEW.soluong - num_free;
//         SET NEW.thanhtien = num_to_pay * price_unit;

//         -- Cập nhật lại lượt tặng
//         UPDATE bienthe
//         SET luottang = luottang - num_free
//         WHERE id = NEW.id_bienthe;

//         -- Ghi yêu cầu tặng quà vào hàng đợi
//         IF num_free > 0 THEN
//             INSERT INTO giohang_quatang_queue (id_nguoidung, id_bienthe, soluong, thanhtien, trangthai)
//             VALUES (NEW.id_nguoidung, NEW.id_bienthe, num_free, 0, 'Hiển thị');
//         END IF;
//     ELSE
//         -- 3️⃣ Không có ưu đãi → tính bình thường
//         IF price_unit IS NULL THEN
//             SELECT giagoc INTO price_unit FROM bienthe WHERE id = NEW.id_bienthe;
//         END IF;
//         SET NEW.thanhtien = NEW.soluong * price_unit;
//     END IF;
// END;
// //
// DELIMITER ;


// Bật event scheduler
// SET GLOBAL event_scheduler = ON;
// SHOW VARIABLES LIKE 'event_scheduler';
// DELIMITER //

// CREATE EVENT IF NOT EXISTS ev_process_giohang_quatang_queue
// ON SCHEDULE EVERY 1 MINUTE
// DO
// BEGIN
//     -- Chèn tất cả bản ghi trong giohang_quatang_queue vào giohang
//     INSERT INTO giohang (id_bienthe, id_nguoidung, soluong, thanhtien, trangthai)
//     SELECT id_bienthe, id_nguoidung, soluong, thanhtien, trangthai
//     FROM giohang_quatang_queue;

//     -- Xóa toàn bộ hàng đã được chèn
//     DELETE FROM giohang_quatang_queue;
// END;
// //

// DELIMITER ;


///// mua 2  tặng 1 thêm 1 bienthe tặng, 2 bienth góc giử nguyên

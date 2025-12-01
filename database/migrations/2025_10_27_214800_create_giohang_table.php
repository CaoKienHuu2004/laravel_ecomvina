<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiohangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giohang', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) AUTO_INCREMENT
            $table->increments('id');

            // Cột 2 & 3: Khóa ngoại (FK1 & FK2) - int(11) - Không NULL
            $table->integer('id_bienthe');
            $table->integer('id_nguoidung');

            // Cột 4: soluong - int(11) - Không NULL
            $table->integer('soluong');

            // Cột 5: thanhtien - int(11) - Không NULL
            // Nên dùng decimal cho tiền tệ để đảm bảo tính chính xác
            $table->integer('thanhtien');

            // Cột 6: trangthai - enum - Không NULL - Mặc định 'Hiển thị'
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])
                  ->default('Hiển thị');


            // Thiết lập Khóa ngoại (Foreign Keys)

            // FK1: Liên kết với bảng 'bienthe'
            $table->foreign('id_bienthe')
                  ->references('id')
                  ->on('bienthe')
                  ->onDelete('cascade');

            // FK2: Liên kết với bảng 'nguoidung'
            // Giả định bảng người dùng là 'nguoidung'
            $table->foreign('id_nguoidung')
                  ->references('id')
                  ->on('nguoidung')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('giohang');
    }
}


// CREATE TRIGGER `cap_nhat_thanhtien_giohang_BEFORE` BEFORE INSERT ON `giohang`
//  FOR EACH ROW BEGIN
//     -- Khai báo biến
//     DECLARE promotion_found INT DEFAULT 0;

//     -- Kiểm tra điều kiện ưu đãi trong bảng quatang_sukien
//     SELECT 1 INTO promotion_found
//     FROM quatang_sukien AS qs
//     JOIN bienthe AS bt ON NEW.id_bienthe = bt.id
//     WHERE
//         qs.id_bienthe = NEW.id_bienthe
//         AND qs.dieukien = NEW.soluong
//         -- Giả định tên cột ngày là 'ngaybatdau' và 'ngayketthuc'
//         AND NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc
//         AND bt.luottang > 0 -- Vẫn giữ điều kiện này để chỉ tìm ưu đãi còn lượt tặng
//     LIMIT 1;

//     -- Nếu tìm thấy ưu đãi thỏa mãn
//     IF promotion_found = 1 THEN
//         -- 1. Cập nhật thanhtien của DÒNG SẮP CHÈN (NEW)
//         SET NEW.thanhtien = 0;

//         -- 2. CẬP NHẬT luottang CỦA bienthe, đảm bảo luottang KHÔNG ÂM.
//         UPDATE bienthe
//         SET luottang = GREATEST(0, luottang - 1)
//         -- GREATEST(0, X) sẽ trả về 0 nếu kết quả trừ là âm, và X nếu kết quả là dương.
//         WHERE id = NEW.id_bienthe;

//     END IF;
// END




// DELIMITER //

// -- Lưu ý: CREATE OR REPLACE TRIGGER không được hỗ trợ trong một số phiên bản MySQL/MariaDB.
// -- Nếu bị lỗi, hãy dùng DROP TRIGGER rồi CREATE TRIGGER.
// CREATE TRIGGER cap_nhat_thanhtien_giohang_TRICKY
// BEFORE INSERT ON giohang
// FOR EACH ROW
// BEGIN
//     DECLARE promotion_count INT DEFAULT 0;  -- Số lần ưu đãi được áp dụng
//     DECLARE discount_multiplier INT DEFAULT 0; -- Số lượng cần mua để nhận quà (từ qs.dieukien)
//     DECLARE price_unit DECIMAL(10, 2);     -- Giá đơn vị sản phẩm (từ bt.giagoc)
//     DECLARE num_to_pay INT DEFAULT 0;       -- Số lượng cần trả tiền
//     DECLARE num_free INT DEFAULT 0;         -- Số lượng được tặng (đã điều chỉnh theo luottang)
//     DECLARE current_luottang INT DEFAULT 0; -- Lượt tặng hiện tại

//     -- 1. Lấy giá gốc sản phẩm đơn vị và thông tin ưu đãi (nếu còn lượt tặng và thỏa mãn điều kiện mua)
//     SELECT
//         bt.giagoc,
//         qs.dieukien,
//         bt.luottang -- Lấy luottang hiện tại
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

//     -- 2. Xử lý Logic Ưu đãi
//     -- Kiểm tra xem có dữ liệu ưu đãi hợp lệ nào được tìm thấy không
//     IF price_unit IS NOT NULL AND discount_multiplier > 0 THEN

//         -- Số lần ưu đãi tối đa có thể áp dụng dựa trên số lượng mua
//         SET promotion_count = FLOOR(NEW.soluong / discount_multiplier);

//         -- Số lượng miễn phí/giảm giá ban đầu (Giả sử Mua X tặng 1)
//         -- Logic: Nếu Mua 2 tặng 1, thì số lượng miễn phí là promotion_count (tổng số lần Mua 2)
//         SET num_free = promotion_count;

//         -- Điều chỉnh số lượng miễn phí dựa trên LƯỢT TẶNG CÒN LẠI (luottang)
//         SET num_free = LEAST(num_free, current_luottang); -- Lấy giá trị nhỏ hơn

//         -- Số lượng cần trả tiền = Tổng số lượng - Số lượng miễn phí
//         SET num_to_pay = NEW.soluong - num_free;

//         -- 3. Cập nhật Thanhtien (tính lại giá)
//         SET NEW.thanhtien = num_to_pay * price_unit;

//         -- 4. Cập nhật Luottang trong bảng bienthe
//         -- Chỉ trừ đi số lần tặng thực tế đã áp dụng (num_free)
//         UPDATE bienthe
//         SET luottang = luottang - num_free
//         WHERE id = NEW.id_bienthe;

//     ELSE
//         -- 5. Nếu không có ưu đãi/hết lượt/hết hạn: Tính thanhtien bằng giá gốc
//         -- Nếu price_unit chưa được gán (vì không tìm thấy ưu đãi), phải lấy giá đơn vị
//         IF price_unit IS NULL THEN
//             SELECT giagoc INTO price_unit FROM bienthe WHERE id = NEW.id_bienthe;
//         END IF;

//         SET NEW.thanhtien = NEW.soluong * price_unit;
//     END IF;
// END;
// //

// DELIMITER ;

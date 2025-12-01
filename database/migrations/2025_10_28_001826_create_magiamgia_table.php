<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tạo bảng 'magiamgia' (Discount Codes / Coupons)
        Schema::create('magiamgia', function (Blueprint $table) {

            // 1. Cột Khóa chính
            $table->increments('id');

            // 2. Cột magiamgia: Mã code (vd: SALE20, FREE_SHIP)
            // Tôi sử dụng string thay vì int(11) vì đây là mã chữ/số và cần là UNIQUE.
            // Nếu bạn chắc chắn mã chỉ là số, có thể dùng $table->integer('magiamgia')->unique();
            $table->integer('magiamgia')->unique()->comment('Mã giảm giá, ví dụ: 2321321');

            // 3. Cột dieukien (Điều kiện áp dụng, vd: min_order_100k, for_new_user)
            $table->string('dieukien', 255)->comment('Điều kiện áp dụng mã');

            // 4. Cột mota (Mô tả) - CÓ THỂ NULL
            $table->text('mota')->nullable()->comment('Mô tả chi tiết mã giảm giá');

            // 5. Cột giatri (Giá trị giảm, có thể là số tiền hoặc phần trăm)
            // Sử dụng integer/decimal tùy thuộc vào cách bạn lưu trữ giá trị
            $table->integer('giatri')->comment('Giá trị giảm (số tiền hoặc phần trăm)');

            // 6, 7. Cột ngày bắt đầu và ngày kết thúc (BẮT BUỘC)
            $table->date('ngaybatdau')->comment('Ngày bắt đầu có hiệu lực');
            $table->date('ngayketthuc')->comment('Ngày hết hạn');

            // 8. Cột trangthai (Trạng thái hoạt động)
            $table->enum('trangthai', ['Hoạt động', 'Tạm khóa', 'Dừng hoạt động'])->default('Hoạt động');

            // 9. Cột Soft Deletes (deleted_at)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magiamgia');
    }
};

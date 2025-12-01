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
        // Tạo bảng 'chitiet_donhang' (Order Details)
        Schema::create('chitiet_donhang', function (Blueprint $table) {

            // 1. Cột Khóa chính
            $table->increments('id'); // Tương đương: $table->bigIncrements('id');

            // 2. Khóa ngoại FK1: id_bienthe (Biến thể sản phẩm)
            $table->integer('id_bienthe');

            // 3. Khóa ngoại FK2: id_donhang (Đơn hàng)
            $table->integer('id_donhang');

            // 4. Cột soluong (Số lượng sản phẩm trong chi tiết)
            $table->integer('soluong');

            // 5. Cột dongia (Đơn giá tại thời điểm đặt hàng)
            // Có thể dùng decimal nếu muốn lưu trữ chính xác hơn
            $table->integer('dongia');

            // Bổ sung: Cột trangthai (có trong ERD, không có trong cấu trúc mẫu)
            // Nếu bạn không cần cột này, có thể xóa.
            $table->enum('trangthai', ['Đã đặt', 'Đã hủy'])->default('Đã đặt');
            // Bổ sung: Cột deleted_at (có trong ERD, không có trong cấu trúc mẫu)
            // Nếu bạn không cần Soft Deletes, có thể xóa.
            $table->softDeletes();

            // --- Khai báo Khóa ngoại (Foreign Keys) ---

            // Liên kết với bảng 'bienthe' (product_variants - giả sử tên bảng là bienthe)
            // Nếu biến thể bị xóa, chi tiết đơn hàng này vẫn cần giữ lại, nên dùng onDelete('restrict') hoặc không dùng.
            $table->foreign('id_bienthe')->references('id')->on('bienthe')->onDelete('restrict');

            // Liên kết với bảng 'donhang' (orders)
            // Nếu đơn hàng bị xóa (hard delete), chi tiết phải bị xóa theo.
            $table->foreign('id_donhang')->references('id')->on('donhang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chitiet_donhang');
    }
};

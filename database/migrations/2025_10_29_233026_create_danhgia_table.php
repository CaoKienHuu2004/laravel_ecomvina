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
        // Tạo bảng 'danhgia' (Reviews/Ratings)
        Schema::create('danhgia', function (Blueprint $table) {

            // 1. Cột Khóa chính: id (tương đương BigIncrements)
            $table->increments('id'); // Tương đương: $table->bigIncrements('id');

            // 2, 3, 4. Các cột Khóa ngoại (FK)
            // Sử dụng unsignedBigInteger để tương thích với $table->id() của các bảng gốc
            $table->integer('id_nguoidung');
            $table->integer('id_sanpham');
            $table->integer('id_chitietdonhang'); // FK3: Chi tiết đơn hàng

            $table->foreign('id_nguoidung')->references('id')->on('nguoidung')->onDelete('cascade');
            $table->foreign('id_sanpham')->references('id')->on('sanpham')->onDelete('cascade');
            $table->foreign('id_chitietdonhang')->references('id')->on('chitiet_donhang')->onDelete('cascade');

            // --- Thiết lập chỉ mục duy nhất (Tùy chọn) ---
            // Đảm bảo mỗi chi tiết đơn hàng (id_chitietdonhang) chỉ được đánh giá một lần duy nhất
            // $table->unique('id_chitietdonhang', 'unique_danhgia_per_detail');

            // 5. Cột diem (Điểm đánh giá - BẮT BUỘC)
            // Dùng tinyInteger (hoặc unsignedInteger) cho điểm từ 1 đến 5 hoặc 1 đến 10
            $table->Integer('diem');

            // 6. Cột noidung (Nội dung đánh giá - CÓ THỂ NULL)
            $table->text('noidung')->nullable();

            // 7. Cột trangthai (Trạng thái hiển thị - BẮT BUỘC, Mặc định là 'Hiển thị')
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])->default('Hiển thị');

            // --- Khai báo Khóa ngoại (Foreign Keys) ---





        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danhgia');
    }
};

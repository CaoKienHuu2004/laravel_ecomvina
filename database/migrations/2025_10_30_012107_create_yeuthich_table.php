<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng 'yeuthich' (Wishlist)
        Schema::create('yeuthich', function (Blueprint $table) {

            // 1. Cột Khóa chính
            $table->increments('id');

            $table->integer('id_nguoidung');
            $table->integer('id_sanpham');

            $table->foreign('id_nguoidung')->references('id')->on('nguoidung')->onDelete('cascade');
            $table->foreign('id_sanpham')->references('id')->on('sanpham')->onDelete('cascade');

            // 4. Cột trangthai (Trạng thái)
            // Cột này ít khi được dùng trong bảng yeuthich, nhưng nếu cần:
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])->default('Hiển thị');

            // Thường bảng yeuthich chỉ cần cột created_at để biết thời điểm thêm vào

            // --- Khai báo Khóa ngoại (Foreign Keys) ---

            // FK1: Liên kết với bảng 'nguoidung' (users)


            // --- Thiết lập chỉ mục duy nhất (Quan trọng) ---
            // Đảm bảo mỗi người dùng chỉ thêm một sản phẩm vào danh sách 1 lần
            // $table->unique(['id_nguoidung', 'id_sanpham']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yeuthich');
    }
};



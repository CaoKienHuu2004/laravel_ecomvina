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
        Schema::create('chuongtrinh', function (Blueprint $table) {

            // Cột Khóa chính
            $table->increments('id');

            // Tiêu đề: Giữ kiểu text theo yêu cầu (mặc dù string(255) là phổ biến hơn)
            $table->text('tieude');

            // Slug: Sử dụng string(255) và unique để tối ưu cho URL và đảm bảo duy nhất
            $table->string('slug')->unique();

            // Hình ảnh: Đường dẫn ảnh
            $table->string('hinhanh', 255);

            // Nội dung chi tiết: longtext
            $table->longText('noidung');

            // Trạng thái: enum('Hiển thị', 'Tạm ẩn')
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])->default('Hiển thị');




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chuongtrinh');
    }
};

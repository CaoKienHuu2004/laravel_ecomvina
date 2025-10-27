<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diachi_giaohang', function (Blueprint $table) {
            $table->increments('id');

            // 2. id_nguoidung (FK, int(11))
            // Liên kết với bảng 'nguoidung'
            $table->foreignId('id_nguoidung')
                  ->constrained('nguoidung') // Giả định tên bảng người dùng là 'nguoidung'
                  ->onDelete('cascade');

            // 3. hoten (varchar(255))
            $table->string('hoten', 255);

            // 4. sodienthoai (varchar(10))
            $table->string('sodienthoai', 10);

            // 5. diachi (text) -> Đã đổi từ string sang text
            $table->text('diachi');

            $table->string('tinhthanh', 100);

            // 6. trangthai (enum('Mặc định', 'Khác', 'Tạm ẩn'), DEFAULT 'Khác')
            $table->enum('trangthai', ['Mặc định', 'Khác', 'Tạm ẩn'])->default('Khác');

            // 7. deleted_at (timestamp, NULL)
            $table->softDeletesDatetime('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diachi_giaohang');
    }
};

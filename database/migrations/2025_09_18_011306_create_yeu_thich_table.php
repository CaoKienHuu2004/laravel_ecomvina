<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_thich', function (Blueprint $table) {
            $table->id();

            // Trạng thái yêu thích
            $table->enum('trangthai', ['dang_thich', 'bo_thich'])->default('dang_thich');

            // Khóa ngoại sản phẩm
            $table->foreignId('id_sanpham')
                ->constrained('san_pham')
                ->cascadeOnUpdate();

            // Khóa ngoại người dùng
            $table->foreignId('id_nguoidung')
                ->constrained('nguoi_dung')
                ->cascadeOnUpdate();

            // Một người dùng chỉ được thích 1 sản phẩm duy nhất 1 lần
            $table->unique(['id_sanpham', 'id_nguoidung']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_thich');
    }
};

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
        Schema::create('thongbao', function (Blueprint $table) {
            $table->id();
            $table->mediumText('tieude');
            $table->text('noidung');
            $table->string('phanloai', 100); // nếu chỉ lưu loại thông báo ngắn
            $table->string('url', 500)->nullable(); // URL có thể dài, cho nullable
            $table->enum('trangthai',['hoat_dong','ngung_hoat_dong'])->default('hoat_dong');
            $table->foreignId('id_nguoidung')->constrained('nguoi_dung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thongbao');
    }
};

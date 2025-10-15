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
        Schema::create('diachi_nguoidung', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('sodienthoai');
            $table->mediumText('diachi')->nullable();
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');

            $table->foreignId('id_nguoidung')->constrained('nguoi_dung');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diachi_nguoidung');
    }
};

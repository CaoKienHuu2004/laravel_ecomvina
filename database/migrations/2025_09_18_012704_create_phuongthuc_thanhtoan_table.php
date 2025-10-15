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
        Schema::create('phuongthuc_thanhtoan', function (Blueprint $table) {
            $table->id(); // Tương đương $table->bigIncrements('id');

            $table->string('ten', 100); // Tên phương thức thanh toán
            $table->string('ma', 50)->unique(); // Mã (code), thường là duy nhất
            $table->text('mota')->nullable(); // Mô tả, có thể là kiểu 'text' hoặc 'string' không giới hạn

            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong'])->default('hoat_dong');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phuongthuc_thanhtoan');
    }
};

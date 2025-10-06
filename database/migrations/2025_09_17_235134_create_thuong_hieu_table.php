<?php

// database/migrations/xxxx_xx_xx_create_thuong_hieu_table.php

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
        Schema::create('thuong_hieu', function (Blueprint $table) {
        $table->id();
        $table->text('ten')->unique();
        $table->text('mota')->nullable();
        $table->text('media');
        $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');

        // Khai báo FK trỏ đến thongtin_nguoibanhang(id)
        $table->foreignId('id_cuahang')
            ->constrained('thongtin_nguoibanhang')
            ->cascadeOnDelete();

        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thuong_hieu');
    }
};

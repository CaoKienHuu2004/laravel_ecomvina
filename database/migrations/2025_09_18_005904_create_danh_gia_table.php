<?php

// database/migrations/xxxx_xx_xx_create_danh_gia_table.php

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
        Schema::create('danh_gia', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('diem');
            $table->mediumText('noidung')->nullable();
            $table->mediumText('media')->nullable()->comment('phần nội dung đa phương tiện đi kèm với đánh giá (ảnh/video).');
            $table->datetime('ngaydang');
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');
            $table->foreignId('id_sanpham')->constrained('san_pham');
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
        Schema::dropIfExists('danh_gia');
    }
};

<?php

// database/migrations/xxxx_xx_xx_create_ma_giamgia_table.php

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
        Schema::create('ma_giamgia', function (Blueprint $table) {
            $table->id();
            $table->string('magiamgia')->nullable();
            $table->mediumText('mota')->nullable();
            $table->decimal('giatri', 15, 2);
            $table->string('dieukien')->nullable();
            $table->datetime('ngaybatdau');
            $table->datetime('ngayketthuc');
            $table->enum('trangthai', [
                'hoat_dong',       // Mã đang hoạt động, còn hạn và có thể sử dụng
                'het_han',         // Mã đã hết hạn (dựa trên ngayketthuc)
                'tam_khoa',        // Mã bị tạm khóa (do admin vô hiệu hóa)
                'da_xoa'           // Mã đã bị xóa/không còn dùng
            ])->default('hoat_dong')->comment('Trạng thái của mã giảm giá');

            $table->timestamps();
            $table->softDeletes()->comment('lịch sử các mã giảm giá đã từng tạo (phục vụ báo cáo, thống kê)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ma_giamgia');
    }
};

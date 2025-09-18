<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hanhvi_nguoidung', function (Blueprint $table) {
            $table->id();

            // Người dùng (có thể null nếu khách vãng lai)
            $table->foreignId('id_nguoidung')->nullable()->constrained('nguoi_dung');

            // Sản phẩm và biến thể
            $table->foreignId('id_sanpham')->nullable()->constrained('san_pham');
            $table->foreignId('id_bienthe')->nullable()->constrained('bienthe_sp');

            // Hành động: xem, click, them_gio, mua, danh_gia...
            $table->enum('hanhdong', [
                'xem',
                'click_bienthe',
                'them_gio',
                'mua',
                'danh_gia'
            ]);

            $table->text('ghichu')->nullable()->comment('Thông tin thêm về hành vi nếu cần');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hanhvi_nguoidung');
    }
};

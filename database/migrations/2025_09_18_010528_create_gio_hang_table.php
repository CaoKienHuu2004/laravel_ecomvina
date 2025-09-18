<?php

// database/migrations/xxxx_xx_xx_create_gio_hang_table.php

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
        Schema::create('gio_hang', function (Blueprint $table) {
            $table->id();
            $table->integer('soluong');
            $table->decimal('tongtien', 15, 2);

            $table->foreignId('id_sanpham')->constrained('san_pham');
            $table->foreignId('id_nguoidung')->constrained('nguoi_dung');

            $table->timestamps();
            $table->softDeletes(); // nhầm phân tích hành vi người dùng và kiệt vụ thống kê đã chọn nhưng chưa mua hàng
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gio_hang');
    }
};

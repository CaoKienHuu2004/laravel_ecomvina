<?php

// database/migrations/xxxx_xx_xx_create_chitiet_donhang_table.php

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
        Schema::create('chitiet_donhang', function (Blueprint $table) {
            $table->id();
            $table->decimal('gia', 15, 2);
            $table->integer('soluong');
            $table->decimal('tongtien', 15, 2);

            $table->foreignId('id_donhang')->constrained('don_hang')->onDelete('cascade');
            $table->foreignId('id_bienthe')->constrained('bienthe')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
            // Bạn muốn giữ lịch sử chi tiết đơn hàng, ngay cả khi đơn hàng bị xóa mềm hoặc hủy.

            // Phục vụ báo cáo, phân tích, audit: ví dụ biết khách đã mua gì, số lượng, giá tiền, dù đơn đã bị hủy.

            // Có thể kết hợp với softDeletes ở bảng don_hang → khi xóa mềm đơn hàng, chi tiết đơn hàng cũng có thể xóa mềm để đồng bộ.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chitiet_donhang');
    }
};

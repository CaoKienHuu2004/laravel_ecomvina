<?php

// database/migrations/xxxx_xx_xx_create_thanh_toan_table.php

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
        Schema::create('lichsu_thanhtoan', function (Blueprint $table) {
            $table->id();
            $table->string('nganhang')->nullable();
            $table->decimal('gia', 15, 2);
            $table->mediumText('noidung')->nullable();
            $table->string('magiaodich')->unique()->nullable();
            $table->dateTime('ngaythanhtoan');

            $table->enum('trangthai', [
                'cho_xac_nhan',             // Thanh toán đã tạo nhưng chưa xác nhận
                'dang_xu_ly',               // Giao dịch đang được xử lý
                'thanh_cong',               // Thanh toán thành công
                'that_bai',                 // Thanh toán thất bại
                'da_huy',                   // Thanh toán bị hủy
                'hoan_tien',                // Giao dịch đã được hoàn tiền
                'tre_han',                  // Thanh toán bị trễ, chờ xử lý
                'cho_xac_nhan_ngan_hang'   // Chờ xác nhận từ ngân hàng/cổng thanh toán
            ])->default('cho_xac_nhan')->comment('Trạng thái thanh toán');
            // $table->int('trangthai');

            $table->foreignId('id_donhang')->constrained('don_hang')->onUpdate('cascade');
            $table->foreignId('id_phuongthuc_thanhtoan')->constrained('phuongthuc_thanhtoan')->onUpdate('cascade');;
            $table->timestamps();
            $table->softDeletes()->comment('Xóa mềm để lưu lịch sử giao dịch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lichsu_thanhtoan');
    }
};

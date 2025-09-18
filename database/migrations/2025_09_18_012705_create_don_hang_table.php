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
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id();

            $table->string('ma_donhang')->nullable();

            $table->decimal('tongtien', 15, 2);

            $table->integer('tongsoluong');

            $table->mediumText('ghichu')->nullable();

            $table->dateTime('ngaytao');

            $table->enum('trangthai', [
                'cho_xac_nhan',    // Khách vừa tạo đơn, chờ shop xác nhận
                'da_xac_nhan',     // Shop đã xác nhận đơn
                'dang_giao',       // Đơn đang được vận chuyển
                'da_giao',         // Đơn đã giao thành công
                'da_huy'           // Đơn đã bị hủy
            ])->default('cho_xac_nhan')->comment('Trạng thái đơn hàng');

            $table->foreignId('id_nguoidung')->constrained('nguoi_dung');
            $table->foreignId('id_magiamgia')->constrained('ma_giamgia');

            $table->timestamps();
            $table->softDeletes();
            // Bạn muốn giữ lịch sử đơn hàng, ngay cả khi admin hoặc khách hủy/xóa đơn.

            // Dữ liệu vẫn tồn tại trong DB để báo cáo, thống kê, hoặc audit.

            // Cho phép khôi phục đơn hàng đã xóa nhầm.

            // Kết hợp với enum trangthai = 'da_huy' để phân biệt đơn hủy thực tế với xóa mềm.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};

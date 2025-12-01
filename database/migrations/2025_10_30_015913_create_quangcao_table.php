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
        Schema::create('quangcao', function (Blueprint $table) {
            $table->increments('id'); // Khóa chính tự tăng

            // Vị trí hiển thị quảng cáo (banner, event, slider, v.v.)
            $table->enum('vitri', [
                'home_banner_slider',
                'home_banner_event_1',
                'home_banner_event_2',
                'home_banner_sidebar',
                'product_banner_top',
                'product_banner_bottom'
            ]);

            // Đường dẫn hình ảnh quảng cáo
            $table->string('hinhanh', 255);

            // Liên kết (khi click vào quảng cáo)
            $table->text('lienket');

            // Mô tả nội dung quảng cáo
            $table->text('mota');

            // Trạng thái hiển thị
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])->default('Hiển thị');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quangcao');
    }
};

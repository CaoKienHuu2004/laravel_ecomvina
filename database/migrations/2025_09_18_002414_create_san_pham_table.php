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
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id();
            $table->text('ten')->unique();
            $table->text('mota')->nullable();
            $table->string('xuatxu')->nullable();
            $table->string('sanxuat')->nullable();
            $table->text('mediaurl')->default('uploads/sanpham/mediaurl/sanpham.png');
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');
            $table->integer('luotxem')->default(0);

            /**
             * id_cuahang là khóa ngoại trỏ đến bảng thongtin_nguoibanhang.
             * - Dùng nullable() vì có thể có sản phẩm chưa được gán cho cửa hàng nào.
             * - Nếu bạn muốn sản phẩm luôn phải thuộc cửa hàng, bỏ nullable() đi.
             * - cascadeOnDelete(): xóa cửa hàng sẽ tự động xóa sản phẩm thuộc cửa hàng đó.
             */
            $table->foreignId('id_cuahang')->nullable()
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
        Schema::dropIfExists('san_pham');
    }
};

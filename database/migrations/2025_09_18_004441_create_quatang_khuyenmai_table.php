<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quatang_khuyenmai', function (Blueprint $table) {
            $table->id();

            $table->integer('soluong');
            $table->text('mota')->nullable();
            $table->dateTime('ngaybatdau')->useCurrent();;
            $table->dateTime('ngayketthuc')->useCurrent();;

            $table->integer('soluongapdung')
                ->comment('Số lượng áp dụng giảm giá, ví dụ: mua 2 giảm 50%, mua 2 tặng 1');

            $table->enum('kieuapdung', ['giam_%', 'tang_1'])
                ->comment('Kiểu áp dụng khuyến mãi: giảm theo %, hoặc tặng sản phẩm');

            $table->foreignId('id_bienthe')
                ->constrained('bienthe_sp')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('id_cuahang')
                ->constrained('thongtin_nguoibanhang')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('id_chuongtrinhsukien')
                ->constrained('chuongtrinhsukien')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quatang_khuyenmai');
    }
};

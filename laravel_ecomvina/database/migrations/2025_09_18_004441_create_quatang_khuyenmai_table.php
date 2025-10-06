<?php

// database/migrations/xxxx_xx_xx_create_quatang_khuyenmai_table.php

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
        Schema::create('quatang_khuyenmai', function (Blueprint $table) {
            $table->id();

            $table->integer('soluong');
            $table->text('mota')->nullable();
            $table->dateTime('ngaybatdau');
            $table->dateTime('ngayketthuc');

            $table->decimal('min_donhang', 15, 2)->comment('giá trị đơn hàng tối thiểu');

            $table->foreignId('id_bienthe')->constrained('bienthe_sp');
            $table->foreignId('id_thuonghieu')->constrained('thuong_hieu');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quatang_khuyenmai');
    }
};

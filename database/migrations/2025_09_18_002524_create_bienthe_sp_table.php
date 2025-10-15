<?php

// database/migrations/xxxx_xx_xx_create_bienthe_sp_table.php

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
        Schema::create('bienthe_sp', function (Blueprint $table) {
            $table->id();

            $table->decimal('gia', 15, 2);
            $table->decimal('giagiam', 15, 2)->default(0);
            $table->integer('soluong')->default(1);
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');
            $table->integer('uutien')->comment('Độ ưu tiên hiển thị của biến thể (số nhỏ hơn = ưu tiên cao hơn)');

            $table->foreignId('id_sanpham')->constrained('san_pham');
            $table->foreignId('id_tenloai')->constrained('loai_bienthe');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bienthe_sp');
    }
};

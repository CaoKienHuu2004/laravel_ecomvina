<?php

// database/migrations/xxxx_xx_xx_create_yeu_thich_table.php

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
        Schema::create('yeu_thich', function (Blueprint $table) {
            $table->id();

            $table->enum('trangthai', ['dang_thich', 'bo_thich'])->default('dang_thich');
            $table->foreignId('id_sanpham')->constrained('san_pham')->onDelete('cascade');
            $table->foreignId('id_nguoidung')->constrained('nguoi_dung')->onDelete('cascade');
            $table->unique(['id_sanpham', 'id_nguoidung']);

            $table->timestamps();
            //$table->softDeletes(); bảng trung gian ko cần xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yeu_thich');
    }
};

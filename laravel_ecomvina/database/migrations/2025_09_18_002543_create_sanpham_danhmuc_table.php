<?php

// database/migrations/xxxx_xx_xx_create_sanpham_danhmuc_table.php

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
        Schema::create('sanpham_danhmuc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sanpham')->constrained('san_pham')->onDelete('cascade');
            $table->foreignId('id_danhmuc')->constrained('danh_muc')->onDelete('cascade');
            $table->unique(['id_sanpham', 'id_danhmuc']); // vẫn là N-N chỉ để đảm bảo một sản phẩm không bị gán trùng vào cùng một danh mục nhiều lần.

            $table->timestamps();
            // $table->softDeletes(); bảng trung gian ko cần xóa mềm mà nên xóa hẳn, trước khi xóa hẳn thì field của các bảng tham gia môi quan hệ n - n này phải được xóa trước
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanpham_danhmuc');
    }
};

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
        Schema::create('chitiet_giohang', function (Blueprint $table) {
            $table->id();

            $table->integer('soluong')->default(1);
            $table->decimal('tongtien', 15, 2)->default(0); // giá * số lượng
            $table->timestamps();

            $table->foreignId('gio_hang_id')->constrained('gio_hang')->onDelete('cascade');
            $table->foreignId('bienthe_sp_id')->constrained('bienthe_sp')->onDelete('cascade');
            $table->unique(['gio_hang_id', 'bienthe_sp_id']); // tránh trùng sản phẩm trong cùng giỏ hàng
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chitiet_giohang');
    }
};

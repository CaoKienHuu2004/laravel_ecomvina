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
        Schema::create('sukien_khuyenmai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_khuyenmai')->constrained('quatang_khuyenmai')->onDelete('cascade');
            $table->foreignId('id_sukien')->constrained('chuongtrinhsukien')->onDelete('cascade');

            $table->timestamps();
            // $table->softDeletes(); bảng trung gian ko cần xóa mềm mà nên xóa hẳn, trước khi xóa hẳn thì field của các bảng tham gia môi quan hệ n - n này phải được xóa trước
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sukien_khuyenmai');
    }
};

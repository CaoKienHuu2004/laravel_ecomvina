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
        Schema::create('quatang_sukien', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_bienthe');
            $table->integer('id_chuongtrinh');
            $table->string('dieukien', 255);
            $table->text('tieude');

            $table->longText('thongtin');
            $table->text('hinhanh');
            $table->integer('luotxem');

            $table->date('ngaybatdau');
            $table->date('ngayketthuc');
            $table->enum('trangthai', ['Hoạt động', 'Tạm khóa'])->default('Hoạt động');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quatang_sukien');
    }
};

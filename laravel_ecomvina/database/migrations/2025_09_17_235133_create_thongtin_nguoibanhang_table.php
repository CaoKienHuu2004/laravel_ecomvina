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
        Schema::create('thongtin_nguoibanhang', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('id_nguoidung')->unique()->comment('Khóa ngoại tham chiếu đến bảng người dùng');
            $table->foreignId('id_nguoidung')->constrained('nguoi_dung');
            $table->unique(['id_nguoidung']);
            $table->string('ten_cuahang', 255)->unique(); // VARCHAR(255)
            $table->string('giayphep_kinhdoanh', 255)->unique(); // VARCHAR(255)
            $table->integer('theodoi')->default(0); // INT
            $table->integer('luotban')->default(0); // INT
            $table->string('logo', 255)->nullable(); // VARCHAR(255)
            $table->string('bianen', 255)->nullable(); // VARCHAR(255)
             $table->text('mota')->nullable();
            $table->string('diachi', 255)->nullable(); // VARCHAR(255)

            $table->string('sodienthoai', 20)->unique()->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoa_dong'])->default('hoat_dong');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thongtin_nguoibanhang');
    }
};

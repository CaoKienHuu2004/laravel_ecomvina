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
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->string('usename')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar');
            $table->string('hoten');
            $table->enum('giotinh',['nam','nữ'])->default('nam');
            $table->date('ngaysinh')->nullable();
            $table->string('sodienthoai', 15)->unique()->nullable();
            $table->enum('vaitro',['user','admin','assistant','anonymous'])->default('user');
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('phien_dang_nhap', function (Blueprint $table) {
            $table->string('id')->primary();
            // Mã phiên (session ID) là khóa chính

            $table->foreignId('nguoi_dung_id')
                ->nullable()
                ->constrained('nguoi_dung')
                ->cascadeOnDelete();
            // Liên kết tới bảng nguoi_dung, xóa user thì xóa phiên

            $table->string('dia_chi_ip', 45)->nullable();
            // Hỗ trợ IPv4 + IPv6

            $table->text('trinh_duyet')->nullable();
            // User-Agent: trình duyệt, thiết bị

            $table->longText('du_lieu');
            // Payload của session

            $table->integer('hoat_dong_cuoi')->index();
            // Unix timestamp: hoạt động cuối

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
        Schema::dropIfExists('phien_dang_nhap');
    }
};

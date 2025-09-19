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
        // Tạo bảng nguoi_dung
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            // $table->string('username')->unique(); // email là duy nhất rồi ko cần username
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar');
            $table->string('hoten');
            $table->enum('gioitinh', ['nam', 'nữ'])->default('nam')->comment('Giới tính của người dùng');
            $table->date('ngaysinh')->nullable();
            $table->string('sodienthoai', 15)->unique()->nullable();
            $table->enum('vaitro',['user','admin','assistant','anonymous'])->default('user');
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');

            // Các cột two-factor authentication
            $table->text('two_factor_secret')->nullable()->after('password');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tạo bảng phiên_dang_nhap
        Schema::create('phien_dang_nhap', function (Blueprint $table) {
            $table->string('id')->primary(); // Mã phiên (session ID) là khóa chính

            $table->foreignId('nguoi_dung_id')
                ->nullable()
                ->constrained('nguoi_dung')
                ->cascadeOnDelete();
            // Liên kết tới bảng nguoi_dung, xóa user thì xóa phiên

            $table->string('dia_chi_ip', 45)->nullable(); // IPv4 + IPv6
            $table->text('trinh_duyet')->nullable(); // User-Agent: trình duyệt, thiết bị
            $table->longText('du_lieu'); // Payload của session
            $table->integer('hoat_dong_cuoi')->index(); // Unix timestamp: hoạt động cuối
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phien_dang_nhap');
        Schema::dropIfExists('nguoi_dung');
    }
};

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
        // Bảng người dùng (thay users)
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();

            // Cột cơ bản từ bảng users
            // $table->string('name')->nullable(); // giữ name từ users
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('123456789');

            // Cột thêm từ bảng nguoi_dung
            $table->string('avatar')->default('uploads/nguoidung/avatar/nguoidung.png');
            $table->string('hoten')->nullable();
            $table->enum('gioitinh', ['nam', 'nữ'])->default('nam')->comment('Giới tính');
            $table->date('ngaysinh')->nullable();
            $table->string('sodienthoai', 15)->unique()->nullable();
            $table->enum('vaitro',['user','admin','seller'])->default('user');
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');

            // Jetstream
            // $table->foreignId('current_team_id')->nullable(); // bản Jetstream team mới cần
            $table->string('profile_photo_path', 2048)->nullable();

            // Two factor
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();

            // Token & timestamps
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Bảng sessions (chuẩn Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index(); // khóa ngoại thay vì user_id
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('nguoi_dung');
    }
};

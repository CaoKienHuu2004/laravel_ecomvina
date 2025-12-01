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
        Schema::create('nguoidung', function (Blueprint $table) {
            // 1. id (PK, int(11), AUTO_INCREMENT)
            $table->id();

            // 2. username (varchar(255), UNIQUE)
            $table->string('username')->unique();

            // 3. password (varchar(255))
            $table->string('password');

            // 4. sodienthoai (varchar(10)) - Giả định là UNIQUE nếu cần
            $table->string('sodienthoai', 10);

            // 5. hoten (varchar(255))
            $table->string('hoten', 255);

            // 6. gioitinh (enum('Nam', 'Nữ'))
            $table->enum('gioitinh', ['Nam', 'Nữ'])->default('Nam');

            // 7. ngaysinh (date)
            $table->date('ngaysinh');

            // 8. avatar (varchar(255), DEFAULT 'khachhang.jpg')
            $table->string('avatar', 255)->default('khachhang.jpg');

            // 9. vaitro (enum('admin', 'seller', 'client'))
            $table->enum('vaitro', ['admin', 'seller', 'client']);

            // 10. trangthai (enum('Hoạt động', 'Tạm khóa', 'Dừng hoạt động'))
            $table->enum('trangthai', ['Hoạt động', 'Tạm khóa', 'Dừng hoạt động']);

            // Hỗ trợ timestamps (created_at và updated_at) - Không có trong danh sách bạn gửi, nhưng nên giữ lại
            $table->timestamps();

            // 11. deleted_at (timestamp, NULL) - Dùng softDeletes với kiểu timestamp
            $table->softDeletesTz('deleted_at'); // Dùng softDeletesTz cho timestamp có múi giờ, hoặc softDeletes() cho không có múi giờ (timestamp)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoidung');
    }
};

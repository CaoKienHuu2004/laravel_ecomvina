<?php

// database/migrations/xxxx_xx_xx_create_gio_hang_table.php

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
        Schema::create('gio_hang', function (Blueprint $table) {
            $table->id();
            $table->integer('soluong');
            $table->decimal('tongtien', 15, 2);

            $table->foreignId('id_sanpham')
                ->constrained('san_pham')
                ->cascadeOnUpdate();
            // $table->foreignId('id_sanpham')->nullable() // Nếu bị xóa thật thì set null
            // ->constrained('san_pham')
            // ->nullOnDelete() // Nếu bị xóa thật thì set null, mà bỏ Unique đi mới được
            // ->cascadeOnUpdate();
            $table->foreignId('id_nguoidung')
                ->constrained('nguoi_dung')
                ->cascadeOnUpdate();
            // $table->foreignId('id_nguoidung')->nullable()
            //     ->constrained('nguoi_dung')
            //     ->nullOnDelete()
            //     ->cascadeOnUpdate();

            $table->unique(['id_sanpham', 'id_nguoidung']); // tránh trùng sản phẩm trong cùng giỏ hàng, vì mình có soluong để quý định số lượng trong giỏ rồi

            $table->timestamps();
            // $table->softDeletes(); // nhầm phân tích hành vi người dùng và nghiệp vụ thống kê đã chọn nhưng chưa mua hàng
        });
        // Schema::create('gio_hang', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('id_nguoidung')->nullable()->constrained('nguoi_dung')->onDelete('cascade'); // user
        //     $table->uuid('guest_id')->nullable(); // guest
        //     $table->decimal('tongtien', 15, 2)->default(0); // tổng tiền giỏ hàng
        //     $table->timestamps();
        //     $table->softDeletes(); // nhầm phân tích hành vi người dùng và nghiệp vụ thống kê đã chọn nhưng chưa mua hàng
        // });
        // Schema::create('chitiet_giohang', function (Blueprint $table) {
        //  $table->integer('soluong')->default(1);
        //     $table->decimal('tongtien', 15, 2)->default(0); // giá * số lượng
        //     $table->timestamps();

        //     $table->foreignId('gio_hang_id')->constrained('gio_hang')->onDelete('cascade');
        //     $table->foreignId('bienthe_sp_id')->constrained('bienthe_sp')->onDelete('cascade');
        //     $table->unique(['gio_hang_id', 'bienthe_sp_id']); // tránh trùng sản phẩm trong cùng giỏ hàng
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gio_hang');
        // Schema::dropIfExists('chitiet_giohang');
    }
};

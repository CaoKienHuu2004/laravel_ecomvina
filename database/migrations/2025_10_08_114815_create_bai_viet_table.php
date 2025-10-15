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
        Schema::create('bai_viet', function (Blueprint $table) {
            $table->id(); // Tương đương với 'id' (bigint unsigned auto_increment)



            // Các trường dữ liệu khác
            $table->string('tieude')->default("<h1>Siêu Thị Vina - Đối Tác Phân Phối Hàng Đầu Cho Mọi Nhà</h1>"); // 'tieude' (varchar) - Giả sử giới hạn mặc định 255
            $table->text('mota')->nullable(); // 'mota' (text) - Thường cho phép NULL
            $table->longText('noidung')->default("<p class=\"text-success\">Siêu Thị Vina tự hào là đối tác phân phối đáng tin cậy, cung cấp đa dạng các mặt hàng thiết yếu từ Sức khỏe, Chăm sóc cá nhân, Điện máy đến Thiết bị y tế, Bách hóa và nhiều hơn nữa. Chúng tôi cam kết mang đến những sản phẩm chất lượng với giá cả cạnh tranh nhất.</p>");

            $table->integer('luotxem')->default(0);

            $table->enum('trangthai', ['nháp', 'đã xuất bản', 'đã lưu trữ', 'đang chờ duyệt'])->default('nháp');

            $table->foreignId('id_nguoidung')->constrained('nguoi_dung')->onUpdate('cascade');;

            $table->timestamps(); // Tạo 'created_at' và 'updated_at'
            $table->softDeletes(); // Tạo 'deleted_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_viet');
    }
};

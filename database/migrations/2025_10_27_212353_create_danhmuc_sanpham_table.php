<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanhmucSanphamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danhmuc_sanpham', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) AUTO_INCREMENT
            $table->increments('id');

            // Cột 2: id_danhmuc (FK1) - int(11) - Không NULL
            $table->unsignedBigInteger('id_danhmuc');

            // Cột 3: id_sanpham (FK2) - int(11) - Không NULL
            $table->unsignedBigInteger('id_sanpham');

            // FK1: Liên kết với bảng 'danhmuc'
            $table->foreign('id_danhmuc')
                  ->references('id')
                  ->on('danhmuc')
                  ->onDelete('cascade'); // Xóa liên kết khi danh mục bị xóa

            // FK2: Liên kết với bảng 'sanpham'
            $table->foreign('id_sanpham')
                  ->references('id')
                  ->on('sanpham')
                  ->onDelete('cascade'); // Xóa liên kết khi sản phẩm bị xóa

            // Tùy chọn: Tạo khóa chính hỗn hợp (Composite Primary Key)
            // Đảm bảo mỗi cặp (danh mục, sản phẩm) là duy nhất.
            // Nếu bạn dùng $table->id(), bạn có thể dùng unique thay cho primary.
            // $table->unique(['id_danhmuc', 'id_sanpham']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danhmuc_sanpham');
    }
}

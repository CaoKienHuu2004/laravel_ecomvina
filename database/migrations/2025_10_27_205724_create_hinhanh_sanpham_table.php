<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHinhanhSanphamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hinhanh_sanpham', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) AUTO_INCREMENT
            $table->increments('id');

            // Cột 2: id_sanpham (FK) - int(11) - Không NULL
            $table->integer('id_sanpham');

            // Cột 3: hinhanh - varchar(255) - Không NULL
            // Lưu đường dẫn file hình ảnh
            $table->string('hinhanh', 255);

            // Cột 4: trangthai - enum - Không NULL - Mặc định 'Hiển thị'
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])
                  ->default('Hiển thị');

            // Cột 5: deleted_at - timestamp Có NULL -> $table->softDeletes()
            $table->softDeletes();


            // Khai báo Khóa ngoại (FK)
            // Liên kết với cột 'id' trong bảng 'sanpham'
            $table->foreign('id_sanpham')
                  ->references('id')
                  ->on('sanpham')
                  ->onDelete('cascade'); // Xóa hình ảnh khi sản phẩm bị xóa
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hinhanh_sanpham');
    }
}

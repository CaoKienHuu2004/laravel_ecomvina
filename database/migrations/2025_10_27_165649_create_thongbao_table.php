<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thongbao', function (Blueprint $table) {
            $table->increments('id');

            // Cột 2: id_nguoidung (FK) - int(11) - Không NULL
            // Giả định nó tham chiếu đến bảng 'nguoidung'
            $table->unsignedInteger('id_nguoidung');

            // Cột 3: tieude - text - Không NULL
            $table->text('tieude');

            // Cột 4: noidung - text - Không NULL
            $table->text('noidung');

            // Cột 5: lienket - text - Có NULL
            $table->text('lienket')->nullable();

            // Cột 6: trangthai - enum - Không NULL - Mặc định 'Chưa đọc' nếu cần
            $table->enum('trangthai', ['Đã đọc', 'Chưa đọc', 'Tạm ẩn'])->default('Chưa đọc');

            // Khai báo Khóa ngoại (FK)
            // Giả định bảng người dùng là 'nguoidung'
            $table->foreign('id_nguoidung')
                  ->references('id') // Cột PK trong bảng 'nguoidung'
                  ->on('nguoidung') // Tên bảng người dùng
                  ->onDelete('cascade'); // Tùy chọn: Xóa thông báo khi người dùng bị xóa

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thongbao');
    }
};

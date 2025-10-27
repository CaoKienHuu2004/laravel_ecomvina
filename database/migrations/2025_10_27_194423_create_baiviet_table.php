<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBaivietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baiviet', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) -> $table->id()
            $table->increments('id');
            // DB::statement('ALTER TABLE baiviet MODIFY id INT(11) UNSIGNED AUTO_INCREMENT');

            // Cột 2: id_nguoidung (FK) - int(11) - Không NULL
            $table->unsignedBigInteger('id_nguoidung');

            // Cột 3: tieude - text - Không NULL
            $table->text('tieude');

            // Cột 4: slug - text - Không NULL
            // Mặc dù cấu trúc cũ là text, nên dùng string (varchar) và unique cho slug
            $table->string('slug')->unique();

            // Cột 5: noidung - longtext - Không NULL
            $table->longText('noidung');

            // Cột 6: luotxem - int(11) - Không NULL - Mặc định 0
            $table->integer('luotxem')->default(0);

            // Cột 7: hinhanh - varchar(255) - Không NULL
            $table->string('hinhanh', 255);

            // Cột 8: trangthai - enum - Không NULL - Mặc định 'Hiển thị'
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])->default('Hiển thị');

            // Khai báo Khóa ngoại (FK)
            // Giả định bảng người dùng là 'nguoidung'
            $table->foreign('id_nguoidung')
                  ->references('id')
                  ->on('nguoidung')
                  ->onDelete('cascade'); // Xóa bài viết khi người dùng bị xóa
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baiviet');
    }
}

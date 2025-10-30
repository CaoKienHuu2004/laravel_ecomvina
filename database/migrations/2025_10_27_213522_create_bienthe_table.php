<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBientheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bienthe', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) AUTO_INCREMENT
            $table->increments('id');

            // Cột 2 & 3: Khóa ngoại (FK1 & FK2) - int(11) - Không NULL
            $table->integer('id_sanpham');
            $table->integer('id_loaibienthe');

            // Cột 4: giagoc - int(11) - Không NULL
            // Nên dùng decimal cho giá tiền để đảm bảo tính chính xác
            $table->integer('giagoc');

            // Cột 5, 6, 7: soluong, luottang, luotban - int(11) - Mặc định 0
            $table->integer('soluong')->default(0);
            $table->integer('luottang')->default(0)->comment('Số lượng quà tặng sư kiện sinh ra');
            $table->integer('luotban')->default(0);

            // Cột 8: trangthai - enum - Có NULL - Mặc định 'Còn hàng'
            $table->enum('trangthai', ['Còn hàng', 'Hết hàng', 'Sắp hết hàng'])
                  ->default('Còn hàng');

            // Cột 9: deleted_at - timestamp Có NULL -> $table->softDeletes()
            $table->softDeletes();


            // Thiết lập Khóa ngoại (Foreign Keys)

            // FK1: Liên kết với bảng 'sanpham'
            $table->foreign('id_sanpham')
                  ->references('id')
                  ->on('sanpham')
                  ->onDelete('cascade');

            // FK2: Liên kết với bảng 'loaibienthe'
            $table->foreign('id_loaibienthe')
                  ->references('id')
                  ->on('loaibienthe')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bienthe');
    }
}

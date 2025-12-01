<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoaibientheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loaibienthe', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) AUTO_INCREMENT
            $table->increments('id');

            // Cột 2: ten - varchar(255) - Không NULL
            $table->string('ten', 255);
            // Có thể thêm ->unique() nếu bạn muốn tên loại biến thể không trùng lặp

            // Cột 3: trangthai - enum - Không NULL - Mặc định 'Hiển thị'
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])
                  ->default('Hiển thị');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loaibienthe');
    }
}

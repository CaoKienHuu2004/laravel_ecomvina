<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThuonghieuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thuonghieu', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) AUTO_INCREMENT -> $table->id()
            $table->increments('id');

            // Cột 2: ten - text - Không NULL
            $table->text('ten');

            // Cột 3: slug - text - Không NULL
            // Nên dùng string và unique cho slug để đảm bảo URL duy nhất
            $table->string('slug')->unique();

            // Cột 4: logo - varchar(255) - Không NULL - Mặc định 'logo_shop.jpg'
            $table->string('logo', 255)->default('logo_shop.jpg');

            // Cột 5: mota (Có trong ERD, không có trong cấu trúc mẫu. Giả định text, nullable)
            // Nếu bạn không muốn cột này, hãy xóa dòng này.
            $table->text('mota')->nullable();

            // Cột 6: trangthai - enum - Không NULL
            $table->enum('trangthai', ['Hoạt động', 'Tạm khóa', 'Dừng hoạt động'])
                  ->default('Hoạt động'); // Đặt mặc định là 'Hoạt động' cho hợp lý
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thuonghieu');
    }
}

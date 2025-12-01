<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhuongthucTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phuongthuc', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) -> $table->id()
            $table->increments('id');

            // Cột 2: ten - varchar(255) - Không NULL
            $table->string('ten', 255);
            // Có thể thêm ->unique() nếu tên phương thức phải là duy nhất.

            // Cột 3: maphuongthuc - text - Có NULL
            $table->text('maphuongthuc')->nullable();

            // Cột 4: trangthai - enum - Không NULL - Mặc định 'Hoạt động'
            $table->enum('trangthai', ['Hoạt động', 'Tạm khóa', 'Dừng hoạt động'])
                  ->default('Hoạt động');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phuongthuc');
    }
}

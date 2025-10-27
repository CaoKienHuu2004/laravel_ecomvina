<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanhmucTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danhmuc', function (Blueprint $table) {
            // Cột 1: id (PK) - int(11) AUTO_INCREMENT
            $table->increments('id');

            // Cột 2: ten - varchar(255) - Không NULL
            $table->string('ten', 255);

            // Cột 3: slug - text - Không NULL
            // Mặc dù cấu trúc cũ là text, nên dùng string và unique cho slug để tối ưu hóa
            $table->string('slug')->unique();

            // Cột 4: logo - varchar(255) - Không NULL - Mặc định 'danhmuc.jpg'
            $table->string('logo', 255)->default('danhmuc.jpg');

            // Cột 5: parent - enum - Không NULL - Mặc định 'Cha'
            $table->enum('parent', ['Cha', 'Con'])->default('Cha');

            // Cột 6: trangthai - enum - Không NULL - Mặc định 'Hiển thị'
            $table->enum('trangthai', ['Hiển thị', 'Tạm ẩn'])->default('Hiển thị');

            // Thêm timestamps (created_at và updated_at)
            // LƯU Ý: Nếu parent được hiểu là self-referencing FK (quan hệ cha-con)
            // với ID của danh mục, bạn cần thay đổi cột 'parent' thành:
            // $table->unsignedBigInteger('parent_id')->nullable();
            // $table->foreign('parent_id')->references('id')->on('danhmuc')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danhmuc');
    }
}

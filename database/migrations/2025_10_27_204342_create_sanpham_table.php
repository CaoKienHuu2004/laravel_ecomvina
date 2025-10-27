<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanphamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanpham', function (Blueprint $table) {
            // Cột 1: id (PK) - $table->id()
            $table->increments('id');

            // Cột 2: id_thuonghieu (FK) - int(11) - Không NULL
            $table->unsignedBigInteger('id_thuonghieu');

            // Cột 3: ten - text - Không NULL
            $table->text('ten');

            // Cột 4: slug - text - Không NULL
            // Nên dùng string và unique cho slug để tối ưu hóa
            $table->string('slug')->unique();

            // Cột 5: mota - longtext - Không NULL
            $table->longText('mota');

            // Cột 6 & 7: xuatxu & sanxuat - varchar(255) - Có NULL
            $table->string('xuatxu', 255)->nullable();
            $table->string('sanxuat', 255)->nullable();

            // Cột 8: trangthai - enum - Không NULL - Mặc định 'Chờ duyệt'
            // Chú ý: Cấu trúc mẫu có 'Tạm khóa...', tôi sẽ dùng các giá trị hợp lý
            $table->enum('trangthai', ['Công khai', 'Chờ duyệt', 'Tạm ẩn', 'Tạm khóa'])
                  ->default('Chờ duyệt');

            // Cột 9 & 10: giamgia & luotxem - int(11) - Không NULL - Mặc định 0
            $table->integer('giamgia')->default(0);
            $table->integer('luotxem')->default(0);

            // Cột 11: deleted_at - timestamp Có NULL -> $table->softDeletes()
            $table->softDeletes(); // Laravel Soft Deletes

            // Khai báo Khóa ngoại (FK)
            // Giả định bảng thương hiệu là 'thuonghieu'
            $table->foreign('id_thuonghieu')
                  ->references('id')
                  ->on('thuonghieu')
                  ->onDelete('cascade'); // Xóa sản phẩm khi thương hiệu bị xóa
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sanpham');
    }
}

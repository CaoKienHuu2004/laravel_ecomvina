<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('phivanchuyen', function (Blueprint $table) {
            // Cột 1: id (PK) - Khóa chính tự tăng
            $table->increments('id');


            // Cột 2: ten - Tên loại phí (Giả định là chuỗi tối đa 50 ký tự)
            $table->string('ten', 50)->unique();
            // Thêm unique() để đảm bảo tên phí vận chuyển không trùng lặp

            // Cột 3: phi - Giá trị phí (Giả định là số thập phân để lưu tiền tệ)
            $table->unsignedInteger('phi')->default(0);

            // 10 chữ số tổng cộng, 2 chữ số sau dấu thập phân

            // Cột 4: trangthai - Trạng thái (Giả định là boolean: 1=Hoạt động, 0=Không hoạt động)
            $table->enum('trangthai',['hiển thị','ẩn'])->default('hiển thị');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phivanchuyen');
    }
};

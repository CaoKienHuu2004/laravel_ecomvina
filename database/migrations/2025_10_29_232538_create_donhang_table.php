<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('donhang', function (Blueprint $table) {
            // Cột Khóa chính
            $table->increments('id'); // Tương đương: $table->bigIncrements('id');

            // Cột Khóa ngoại và các trường bắt buộc theo cấu trúc
            // FK1: id_phuongthuc (Phương thức thanh toán/vận chuyển...)
            // FK3: id_nguoidung (Người đặt hàng)
            // Cấu trúc bảng bạn cung cấp có id_nguoidung trước id_phuongthuc, tôi sắp xếp lại theo cấu trúc đó.

            $table->integer('id_phuongthuc');
            // FK2: id_magiamgia (Mã giảm giá) - CÓ THỂ NULL
            $table->integer('id_magiamgia')->nullable();
            $table->integer('id_nguoidung');
            $table->integer('id_phivanchuyen');
            $table->integer('id_diachigiaohang');

            $table->foreign('id_phuongthuc')->references('id')->on('phuongthuc');
            $table->foreign('id_magiamgia')->references('id')->on('magiamgia')->onDelete('set null'); // Set Null khi mã giảm giá bị xóa
            $table->foreign('id_nguoidung')->references('id')->on('nguoidung')->onDelete('cascade');
            $table->foreign('id_phivanchuyen')->references('id')->on('phivanchuyen')->onDelete('cascade');
            $table->foreign('id_diachigiaohang')->references('id')->on('diachi_giaohang')->onDelete('cascade');

            // Liên kết với bảng 'phuongthuc' (payment/shipping methods)


            // Liên kết với bảng 'magiamgia' (discount codes)




            // FK4: id_phivanchuyen - Không có trong cấu trúc bảng bạn cung cấp, nhưng có trong ERD. Tôi tạm thời bỏ qua nếu cấu trúc bảng ưu tiên hơn.
            // Nếu bạn muốn thêm: $table->unsignedBigInteger('id_phivanchuyen');

            // FK5: id_diachigiaohang - Không có trong cấu trúc bảng bạn cung cấp, nhưng có trong ERD.
            // Nếu bạn muốn thêm: $table->unsignedBigInteger('id_diachigiaohang');

            // Các cột dữ liệu khác
            $table->string('madon', 10)->unique(); // Mã đơn hàng, độ dài 10, Không Null
            $table->integer('tongsoluong'); // Tổng số lượng sản phẩm
            $table->integer('tamtinh');
            $table->integer('thanhtien'); // Thành tiền (tổng tiền)

            // 1. Cột trangthaithanhtoan (Payment Status)
            $table->enum('trangthaithanhtoan', [
                'Chưa thanh toán',   // Pending/Unpaid
                'Đã thanh toán',     // Paid/Completed
                'Thanh toán thất bại', // Failed
                'Đã hoàn tiền'       // Refunded
            ])->default('Chưa thanh toán')->comment('Trạng thái thanh toán của đơn hàng');

            // 2. Cột trangthai (Order Status)
            $table->enum('trangthai', [
                'Chờ xử lý',       // Order received, awaiting processing
                'Đã xác nhận',     // Confirmed/Accepted
                'Đang chuẩn bị hàng', // Preparing/Packaging
                'Đang giao hàng',  // Shipping/Out for Delivery
                'Đã giao hàng',    // Delivered/Completed
                'Đã hủy'          // Cancelled
            ])->default('Chờ xử lý')->comment('Trạng thái xử lý và vận chuyển đơn hàng');

            // Cột created_at và updated_at
            $table->timestamps(); // Tạo 2 cột created_at và updated_at

            // Cột deleted_at cho Soft Deletes (có thể NULL)
            $table->softDeletes(); // Tạo cột deleted_at

            // Khai báo Khóa ngoại (Foreign Keys)

            // Liên kết với bảng 'nguoidung' (users)

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donhang');
    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiaChiGiaoHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ⚙️ Tạm tắt kiểm tra khóa ngoại để tránh lỗi truncate/delete
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('diachi_giaohang')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $data = [
            [
                'id' => 1,
                'id_nguoidung' => 1,
                'hoten' => 'Quản trị viên',
                'sodienthoai' => '0900000001',
                'diachi' => '123 Lê Lợi, Quận 1',
                'tinhthanh' => 'TP. Hồ Chí Minh',
                'trangthai' => 'Mặc định',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'id_nguoidung' => 2,
                'hoten' => 'Người bán hàng',
                'sodienthoai' => '0900000002',
                'diachi' => '45 Hoàng Diệu, Quận Hải Châu',
                'tinhthanh' => 'Đà Nẵng',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'id_nguoidung' => 3,
                'hoten' => 'Khách hàng 1',
                'sodienthoai' => '0900000003',
                'diachi' => '78 Nguyễn Huệ, Quận Ba Đình',
                'tinhthanh' => 'Hà Nội',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'id_nguoidung' => 4,
                'hoten' => 'Khách hàng 2',
                'sodienthoai' => '0900000004',
                'diachi' => '56 Trần Phú, Quận Ninh Kiều',
                'tinhthanh' => 'Cần Thơ',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'id_nguoidung' => 5,
                'hoten' => 'Khách hàng 3',
                'sodienthoai' => '0900000005',
                'diachi' => '12 Lý Thường Kiệt, Phường 2',
                'tinhthanh' => 'Huế',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 6,
                'id_nguoidung' => 6,
                'hoten' => 'Khách hàng 4',
                'sodienthoai' => '0900000006',
                'diachi' => '22 Nguyễn Văn Cừ, Quận 5',
                'tinhthanh' => 'TP. Hồ Chí Minh',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 7,
                'id_nguoidung' => 7,
                'hoten' => 'Khách hàng 5',
                'sodienthoai' => '0900000007',
                'diachi' => '9A Hai Bà Trưng, Quận Hoàn Kiếm',
                'tinhthanh' => 'Hà Nội',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 8,
                'id_nguoidung' => 8,
                'hoten' => 'Khách hàng 6',
                'sodienthoai' => '0900000008',
                'diachi' => '67 Nguyễn Trãi, Phường 3',
                'tinhthanh' => 'Đà Lạt',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 9,
                'id_nguoidung' => 9,
                'hoten' => 'Khách hàng 7',
                'sodienthoai' => '0900000009',
                'diachi' => '101 Pasteur, Quận 3',
                'tinhthanh' => 'TP. Hồ Chí Minh',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
            [
                'id' => 10,
                'id_nguoidung' => 10,
                'hoten' => 'Khách hàng 8',
                'sodienthoai' => '0900000010',
                'diachi' => '32 Nguyễn Đình Chiểu, Quận 1',
                'tinhthanh' => 'TP. Hồ Chí Minh',
                'trangthai' => 'Khác',
                'deleted_at' => null,
            ],
        ];


        DB::table('diachi_giaohang')->insert($data);
    }

}

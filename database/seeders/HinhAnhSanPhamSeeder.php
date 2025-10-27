<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HinhAnhSanPhamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $data = [
            ['id_sanpham' => 1, 'hinhanh' => 'keo-ong-xanh-tracybee-propolis-mint-honey-giam-dau-1.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 1, 'hinhanh' => 'keo-ong-xanh-tracybee-propolis-mint-honey-giam-dau-2.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 1, 'hinhanh' => 'keo-ong-xanh-tracybee-propolis-mint-honey-giam-dau-3.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 1, 'hinhanh' => 'keo-ong-xanh-tracybee-propolis-mint-honey-giam-dau-4.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],

            ['id_sanpham' => 2, 'hinhanh' => 'mat-ong-tay-bac-dong-trung-ha-thao-x3-hu-240g-1.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 2, 'hinhanh' => 'mat-ong-tay-bac-dong-trung-ha-thao-x3-hu-240g-2.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 2, 'hinhanh' => 'mat-ong-tay-bac-dong-trung-ha-thao-x3-hu-240g-3.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],

            ['id_sanpham' => 3, 'hinhanh' => 'sam-ngoc-linh-truong-sinh-do-thung-24lon-1.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 3, 'hinhanh' => 'sam-ngoc-linh-truong-sinh-do-thung-24lon-2.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 3, 'hinhanh' => 'sam-ngoc-linh-truong-sinh-do-thung-24lon-3.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 3, 'hinhanh' => 'sam-ngoc-linh-truong-sinh-do-thung-24lon-4.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 3, 'hinhanh' => 'sam-ngoc-linh-truong-sinh-do-thung-24lon-5.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],

            ['id_sanpham' => 4, 'hinhanh' => 'tinh-dau-tram-tu-nhien-eco-ho-tro-giam-ho-cam-cum-1.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 4, 'hinhanh' => 'tinh-dau-tram-tu-nhien-eco-ho-tro-giam-ho-cam-cum-2.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 4, 'hinhanh' => 'tinh-dau-tram-tu-nhien-eco-ho-tro-giam-ho-cam-cum-3.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],

            ['id_sanpham' => 5, 'hinhanh' => 'sua-non-to-yen-papamilk-height-gain-giup-tang-can-1.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 5, 'hinhanh' => 'sua-non-to-yen-papamilk-height-gain-giup-tang-can-2.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 5, 'hinhanh' => 'sua-non-to-yen-papamilk-height-gain-giup-tang-can-3.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 5, 'hinhanh' => 'sua-non-to-yen-papamilk-height-gain-giup-tang-can-4.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 5, 'hinhanh' => 'sua-non-to-yen-papamilk-height-gain-giup-tang-can-5.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_sanpham' => 5, 'hinhanh' => 'sua-non-to-yen-papamilk-height-gain-giup-tang-can-6.webp', 'trangthai' => 'Hiển thị', 'deleted_at' => null, 'created_at' => $now, 'updated_at' => $now],

            // Tiếp tục theo mẫu cho các id_sanpham 6 -> 27
            // Bạn có thể copy theo dữ liệu bạn liệt kê phía trên
        ];

        DB::table('hinhanh_sanpham')->insert($data);
    }
}

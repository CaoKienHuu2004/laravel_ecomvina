<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhMucSanPhamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_danhmuc' => 1, 'id_sanpham' => 5],
            ['id_danhmuc' => 1, 'id_sanpham' => 4],
            ['id_danhmuc' => 2, 'id_sanpham' => 1],
            ['id_danhmuc' => 2, 'id_sanpham' => 3],
            ['id_danhmuc' => 1, 'id_sanpham' => 2],
            ['id_danhmuc' => 2, 'id_sanpham' => 9],
            ['id_danhmuc' => 3, 'id_sanpham' => 9],
            ['id_danhmuc' => 1, 'id_sanpham' => 9],
            ['id_danhmuc' => 7, 'id_sanpham' => 6],
            ['id_danhmuc' => 1, 'id_sanpham' => 10],
            ['id_danhmuc' => 4, 'id_sanpham' => 10],
            ['id_danhmuc' => 4, 'id_sanpham' => 11],
            ['id_danhmuc' => 4, 'id_sanpham' => 12],
            ['id_danhmuc' => 7, 'id_sanpham' => 15],
            ['id_danhmuc' => 7, 'id_sanpham' => 14],
            ['id_danhmuc' => 7, 'id_sanpham' => 13],
            ['id_danhmuc' => 11, 'id_sanpham' => 14],
            ['id_danhmuc' => 11, 'id_sanpham' => 15],
            ['id_danhmuc' => 7, 'id_sanpham' => 16],
            ['id_danhmuc' => 7, 'id_sanpham' => 17],
            ['id_danhmuc' => 7, 'id_sanpham' => 18],
            ['id_danhmuc' => 6, 'id_sanpham' => 19],
            ['id_danhmuc' => 6, 'id_sanpham' => 20],
            ['id_danhmuc' => 6, 'id_sanpham' => 21],
            ['id_danhmuc' => 6, 'id_sanpham' => 22],
            ['id_danhmuc' => 6, 'id_sanpham' => 23],
            ['id_danhmuc' => 4, 'id_sanpham' => 24],
            ['id_danhmuc' => 4, 'id_sanpham' => 25],
            ['id_danhmuc' => 4, 'id_sanpham' => 26],
            ['id_danhmuc' => 4, 'id_sanpham' => 27],
            // Thêm các bản ghi tiếp theo nếu cần
        ];

        DB::table('danhmuc_sanpham')->insert($data);
    }
}

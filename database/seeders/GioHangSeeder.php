<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GioHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $giohangs = [
            ['id_bienthe' => 1, 'id_nguoidung' => 2, 'soluong' => 2, 'thanhtien' => 540000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 1, 'id_nguoidung' => 3, 'soluong' => 2, 'thanhtien' => 540000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 2, 'id_nguoidung' => 4, 'soluong' => 1, 'thanhtien' => 385000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 3, 'id_nguoidung' => 5, 'soluong' => 3, 'thanhtien' => 1399680, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 4, 'id_nguoidung' => 6, 'soluong' => 1, 'thanhtien' => 260000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 5, 'id_nguoidung' => 7, 'soluong' => 2, 'thanhtien' => 1024000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 6, 'id_nguoidung' => 8, 'soluong' => 1, 'thanhtien' => 270000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 7, 'id_nguoidung' => 3, 'soluong' => 5, 'thanhtien' => 1800000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 8, 'id_nguoidung' => 4, 'soluong' => 2, 'thanhtien' => 138000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 9, 'id_nguoidung' => 5, 'soluong' => 1, 'thanhtien' => 260000, 'trangthai' => 'Hiển thị'],
            ['id_bienthe' => 10, 'id_nguoidung' => 6, 'soluong' => 1, 'thanhtien' => 699000, 'trangthai' => 'Hiển thị'],
        ];

        DB::table('giohang')->insert($giohangs);
    }
}

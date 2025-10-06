<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class YeuThichSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $data = [
            [
                'id_sanpham' => 1, // Vitamin C 500mg
                'id_nguoidung' => 2, // Assistant
                'trangthai' => 'dang_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 2, // Sữa rửa mặt
                'id_nguoidung' => 3, // Anonymous
                'trangthai' => 'dang_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 3,
                'id_nguoidung' => 4,
                'trangthai' => 'bo_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 4,
                'id_nguoidung' => 5,
                'trangthai' => 'dang_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 5,
                'id_nguoidung' => 6,
                'trangthai' => 'dang_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 6,
                'id_nguoidung' => 7,
                'trangthai' => 'bo_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 7,
                'id_nguoidung' => 8,
                'trangthai' => 'dang_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 8,
                'id_nguoidung' => 9,
                'trangthai' => 'dang_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 9,
                'id_nguoidung' => 10,
                'trangthai' => 'dang_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_sanpham' => 10,
                'id_nguoidung' => 11,
                'trangthai' => 'bo_thich',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('yeu_thich')->insert($data);
    }
}

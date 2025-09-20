<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BientheSpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bienthe_sp')->insert([
            [
                'id' => 1,
                'id_tenloai' => 1,       // tham chiếu loai_bienthe.id
                'gia' => 34500.00,
                'soluong' => 20,
                'trangthai' => 'hoat_dong', // map từ 0 => hoat_dong
                'uutien' => 1,
                'id_sanpham' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'id_tenloai' => 9,
                'gia' => 109000.00,
                'soluong' => 3,
                'trangthai' => 'hoat_dong',
                'uutien' => 0,
                'id_sanpham' => 6,
                'created_at' => '2025-08-24 05:20:14',
                'updated_at' => '2025-08-24 05:20:14',
            ],
            [
                'id' => 14,
                'id_tenloai' => 2,
                'gia' => 395000.00,
                'soluong' => 291,
                'trangthai' => 'hoat_dong',
                'uutien' => 0,
                'id_sanpham' => 7,
                'created_at' => '2025-08-24 05:30:45',
                'updated_at' => '2025-08-24 05:30:45',
            ],
            [
                'id' => 15,
                'id_tenloai' => 10,
                'gia' => 360000.00,
                'soluong' => 1000,
                'trangthai' => 'hoat_dong',
                'uutien' => 0,
                'id_sanpham' => 8,
                'created_at' => '2025-09-08 05:21:36',
                'updated_at' => '2025-09-08 05:21:36',
            ],
        ]);
    }
}

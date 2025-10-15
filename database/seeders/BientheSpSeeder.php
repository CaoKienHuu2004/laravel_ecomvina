<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BientheSpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $data = [];

        // tạo 30 biến thể sản phẩm
        for ($i = 1; $i <= 30; $i++) {
            $gia = rand(50000, 5000000); // giá từ 50k - 5 triệu
            $giagiam = (rand(0, 1) ? rand(20000, $gia - 1000) : 0); // có thể giảm hoặc không
            $soluong = rand(5, 200);
            $uutien = rand(1, 5);

            $data[] = [
                'gia' => $gia,
                'giagiam' => $giagiam,
                'soluong' => $soluong,
                'trangthai' => 'hoat_dong',
                'uutien' => $uutien,
                'id_sanpham' => rand(1, 20), // vì bảng san_pham có 20 bản ghi
                'id_tenloai' => rand(1, 10), // vì bảng loai_bienthe có 10 bản ghi
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // insert dữ liệu vào bảng
        DB::table('bienthe_sp')->insert($data);
    }
}

 // public function run(): void
    // {

    //     $now = Carbon::now('Asia/Ho_Chi_Minh');
    //     DB::table('bienthe_sp')->insert([
    //         [
    //             'id' => 1,
    //             'id_tenloai' => 1,       // tham chiếu loai_bienthe.id
    //             'gia' => 34500.00,
    //             'soluong' => 20,
    //             'trangthai' => 'hoat_dong', // map từ 0 => hoat_dong
    //             'uutien' => 1,
    //             'id_sanpham' => 1,
    //             'created_at' => $now,
    //             'updated_at' => $now,
    //         ],
    //         [
    //             'id' => 13,
    //             'id_tenloai' => 9,
    //             'gia' => 109000.00,
    //             'soluong' => 3,
    //             'trangthai' => 'hoat_dong',
    //             'uutien' => 0,
    //             'id_sanpham' => 6,
    //             'created_at' => $now,
    //             'updated_at' => $now,
    //         ],
    //         [
    //             'id' => 14,
    //             'id_tenloai' => 2,
    //             'gia' => 395000.00,
    //             'soluong' => 291,
    //             'trangthai' => 'hoat_dong',
    //             'uutien' => 0,
    //             'id_sanpham' => 7,
    //             'created_at' => $now,
    //             'updated_at' => $now,
    //         ],
    //         [
    //             'id' => 15,
    //             'id_tenloai' => 10,
    //             'gia' => 360000.00,
    //             'soluong' => 1000,
    //             'trangthai' => 'hoat_dong',
    //             'uutien' => 0,
    //             'id_sanpham' => 8,
    //             'created_at' => $now,
    //             'updated_at' => $now,
    //         ],
    //     ]);

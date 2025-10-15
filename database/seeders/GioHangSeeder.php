<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GioHangSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách user có vai trò "user"
        $userIds = DB::table('nguoi_dung')
            ->where('vaitro', 'user')
            ->pluck('id')
            ->toArray();

        // Lấy danh sách sản phẩm hiện có
        $sanPhams = DB::table('san_pham')
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        $gioHangs = [];

        // Mỗi user sẽ có 1-3 sản phẩm trong giỏ
        foreach ($userIds as $userId) {
            $soSanPham = rand(1, 3);
            $chonSanPham = collect($sanPhams)->random($soSanPham);

            foreach ($chonSanPham as $idSanPham) {
                $soluong = rand(1, 5);
                $giaSanPham = DB::table('bienthe_sp')
                    ->where('id_sanpham', $idSanPham)
                    ->value('gia') ?? rand(10000, 50000);

                $tongtien = $giaSanPham * $soluong;

                $gioHangs[] = [
                    'id_nguoidung' => $userId,
                    'id_sanpham'   => $idSanPham,
                    'soluong'      => $soluong,
                    'tongtien'     => $tongtien,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }

        // Thêm 1 giỏ mẫu cho admin (id = 1)
        if (DB::table('nguoi_dung')->where('id', 1)->exists()) {
            $sanPhamMau = collect($sanPhams)->random();
            $soluong = 2;
            $giaSanPham = DB::table('bienthe_sp')
                ->where('id_sanpham', $sanPhamMau)
                ->value('gia') ?? rand(20000, 60000);

            $gioHangs[] = [
                'id_nguoidung' => 1,
                'id_sanpham'   => $sanPhamMau,
                'soluong'      => $soluong,
                'tongtien'     => $giaSanPham * $soluong,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('gio_hang')->insert($gioHangs);
    }
}
// namespace Database\Seeders;

// use Carbon\Carbon;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Str;

// class GioHangSeeder extends Seeder
// {
//     public function run(): void
//         {
//             $now = Carbon::now('Asia/Ho_Chi_Minh');

//             // Lấy danh sách user có vai trò "user"
//             $userIds = DB::table('nguoi_dung')
//                 ->where('vaitro', 'user')
//                 ->pluck('id')
//                 ->toArray();

//             $gioHangs = [];

//             for ($i = 1; $i <= 10; $i++) {
//                 $gioHangs[] = [
//                     'id_nguoidung' => collect($userIds)->random(), // chỉ chọn user
//                     'guest_id'     => null, // có thể null vì là user
//                     'tongtien'     => 0, // sẽ cập nhật sau khi thêm chitiet_giohang
//                     'created_at'   => $now,
//                     'updated_at'   => $now,
//                 ];
//             }
//             $gioHangs[] = [
//                 'id_nguoidung' => 1, // user admin
//                 'guest_id' => null,
//                 'tongtien' => 0,
//                 'created_at' => $now,
//                 'updated_at' => $now,
//             ];

//             DB::table('gio_hang')->insert($gioHangs);
//         }
// }


// public function run(): void
//     {
//         $now = Carbon::now('Asia/Ho_Chi_Minh');
//         $gioHangs = [
//             [
//                 'id' => 1,
//                 'id_nguoidung' => 1, // user admin
//                 'guest_id' => null,
//                 'tongtien' => 0,
//                 'created_at' => $now,
//                 'updated_at' => $now,
//             ],
//             [
//                 'id' => 2,
//                 'id_nguoidung' => 2, // user assistant
//                 'guest_id' => null,
//                 'tongtien' => 0,
//                 'created_at' => $now,
//                 'updated_at' => $now,
//             ],
//             [
//                 'id' => 3,
//                 'id_nguoidung' => null,
//                 'guest_id' => (string) Str::uuid(), // guest user
//                 'tongtien' => 0,
//                 'created_at' => $now,
//                 'updated_at' => $now,
//             ],
//         ];

//         DB::table('gio_hang')->insert($gioHangs);
//     }

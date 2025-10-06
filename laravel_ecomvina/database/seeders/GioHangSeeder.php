<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

            $gioHangs = [];

            for ($i = 1; $i <= 10; $i++) {
                $gioHangs[] = [
                    'id_nguoidung' => collect($userIds)->random(), // chỉ chọn user
                    'guest_id'     => null, // có thể null vì là user
                    'tongtien'     => 0, // sẽ cập nhật sau khi thêm chitiet_giohang
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
            $gioHangs[] = [
                'id_nguoidung' => 1, // user admin
                'guest_id' => null,
                'tongtien' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            DB::table('gio_hang')->insert($gioHangs);
        }
}


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

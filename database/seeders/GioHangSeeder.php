<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GioHangSeeder extends Seeder
{
    public function run(): void
    {
        // Map id_bienthesp => giá
        $giaBienthe = [
            1  => 34500.00,
            13 => 109000.00,
            14 => 395000.00,
            15 => 360000.00,
        ];

        // Lấy user theo role (admin, user, anonymous)
        $userIds = DB::table('nguoi_dung')
            ->whereIn('vaitro', ['admin', 'user', 'anonymous'])
            ->pluck('id')
            ->toArray();

        $bientheIds = array_keys($giaBienthe);
        $gioHang = [];

        for ($i = 0; $i < 30; $i++) {
            $idBienthe = $bientheIds[array_rand($bientheIds)];
            $soluong   = rand(1, 5);
            $gia       = $giaBienthe[$idBienthe];

            $gioHang[] = [
                'soluong'      => $soluong,
                'tongtien'     => $soluong * $gia,
                'id_bienthesp' => $idBienthe,
                'id_nguoidung' => $userIds[array_rand($userIds)],
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        DB::table('gio_hang')->insert($gioHang);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietGioHangSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $chitiet = [
            [
                'gio_hang_id' => 1, // của user admin
                'bienthe_sp_id' => 1,
                'soluong' => 2,
                'tongtien' => 34500 * 2, // 69,000
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'gio_hang_id' => 1,
                'bienthe_sp_id' => 13,
                'soluong' => 1,
                'tongtien' => 109000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'gio_hang_id' => 2, // của user assistant
                'bienthe_sp_id' => 14,
                'soluong' => 3,
                'tongtien' => 395000 * 3, // 1,185,000
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'gio_hang_id' => 3, // guest
                'bienthe_sp_id' => 15,
                'soluong' => 5,
                'tongtien' => 360000 * 5, // 1,800,000
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('chitiet_giohang')->insert($chitiet);

        // cập nhật tổng tiền giỏ hàng dựa trên chi tiết
        $gioHangs = DB::table('gio_hang')->get();
        foreach ($gioHangs as $gio) {
            $tong = DB::table('chitiet_giohang')
                ->where('gio_hang_id', $gio->id)
                ->sum('tongtien');

            DB::table('gio_hang')
                ->where('id', $gio->id)
                ->update(['tongtien' => $tong]);
        }
    }
}

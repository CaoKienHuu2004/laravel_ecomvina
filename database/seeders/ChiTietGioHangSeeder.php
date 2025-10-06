<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChitietGioHangSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách giỏ hàng vừa seed
        $gioHangIds = DB::table('gio_hang')->pluck('id')->toArray();

        // Lấy danh sách biến thể sản phẩm
        $bientheList = DB::table('bienthe_sp')
            ->select('id', 'gia')
            ->get()
            ->toArray();

        $details = [];
        $updateTongTien = [];

        foreach ($gioHangIds as $gioHangId) {
            // Random số sản phẩm trong 1 giỏ (1-3 sản phẩm)
            $numProducts = rand(1, 3);
            $chosenVariants = collect($bientheList)->random($numProducts);

            $totalCart = 0;

            foreach ($chosenVariants as $variant) {
                $soluong = rand(1, 5);
                $tongtien = $variant->gia * $soluong;
                $totalCart += $tongtien;

                $details[] = [
                    'gio_hang_id'   => $gioHangId,
                    'bienthe_sp_id' => $variant->id,
                    'soluong'       => $soluong,
                    'tongtien'      => $tongtien,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }

            // lưu tổng tiền giỏ hàng để cập nhật
            $updateTongTien[$gioHangId] = $totalCart;
        }

        // Insert chi tiết giỏ hàng
        DB::table('chitiet_giohang')->insert($details);

        // Update tổng tiền giỏ hàng
        foreach ($updateTongTien as $gioHangId => $total) {
            DB::table('gio_hang')
                ->where('id', $gioHangId)
                ->update(['tongtien' => $total]);
        }
    }
}

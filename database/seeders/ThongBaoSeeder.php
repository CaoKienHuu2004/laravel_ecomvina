<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongBaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy tất cả user có vai trò = user
        $userIds = DB::table('nguoi_dung')
            ->where('vaitro', 'user')
            ->pluck('id');

        $notifications = [];

        foreach ($userIds as $userId) {
            $notifications[] = [
                "tieude"     => "Chào mừng bạn đến với hệ thống!",
                "noidung"    => "Xin chào User #$userId, chúc bạn có trải nghiệm vui vẻ.",
                "phanloai"   => "welcome",
                "url"        => null,
                "trangthai"  => "hoat_dong",
                "id_nguoidung" => $userId,
                "created_at" => $now,
                "updated_at" => $now,
            ];
        }

        DB::table('thong_bao')->insert($notifications);
    }
}

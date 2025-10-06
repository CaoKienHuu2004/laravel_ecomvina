<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BannerQuangCaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $data = [
            [
                'vitri' => 'header',
                'hinhanh' => 'banner1.jpg',
                'duongdan' => 'https://fpt.edu.vn',
                'tieude' => 'Chào mừng đến với FPT Polytechnic',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'vitri' => 'sidebar',
                'hinhanh' => 'banner2.jpg',
                'duongdan' => 'https://shopee.vn',
                'tieude' => 'Mua sắm giảm giá 50%',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'vitri' => 'footer',
                'hinhanh' => 'banner3.jpg',
                'duongdan' => 'https://tiki.vn',
                'tieude' => 'Flash Sale cuối tuần',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'vitri' => 'main',
                'hinhanh' => 'banner4.jpg',
                'duongdan' => 'https://lazada.vn',
                'tieude' => 'Khuyến mãi đặc biệt hôm nay',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'vitri' => 'popup',
                'hinhanh' => 'banner5.jpg',
                'duongdan' => 'https://tiktok.com',
                'tieude' => 'Theo dõi chúng tôi trên TikTok',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('banner_quangcao')->insert($data);
    }
}

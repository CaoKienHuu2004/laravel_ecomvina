<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuangCaoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('quangcao')->insert([
            [
                'id' => 1,
                'vitri' => 'home_banner_slider',
                'hinhanh' => 'banner-droppii-1.png',
                'lienket' => 'https://droppii.vn',
                'mota' => 'Liên kết đến Droppii Mall',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 2,
                'vitri' => 'home_banner_slider',
                'hinhanh' => 'banner-droppii-2.png',
                'lienket' => 'https://droppii.vn',
                'mota' => 'Liên kết đến Droppii Mall',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 3,
                'vitri' => 'home_banner_slider',
                'hinhanh' => 'banner-droppii-3.png',
                'lienket' => 'https://droppii.vn',
                'mota' => 'Liên kết đến Droppii Mall',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 4,
                'vitri' => 'home_banner_event_1',
                'hinhanh' => 'shopee-1.jpg',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 5,
                'vitri' => 'home_banner_event_2',
                'hinhanh' => 'shopee-2.jpg',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 6,
                'vitri' => 'home_banner_event_3',
                'hinhanh' => 'shopee-3.jpg',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 7,
                'vitri' => 'home_banner_event_4',
                'hinhanh' => 'shopee-04.webp',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 8,
                'vitri' => 'home_banner_promotion_1',
                'hinhanh' => 'shopee-05.jpg',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 9,
                'vitri' => 'home_banner_promotion_2',
                'hinhanh' => 'shopee-06.jpg',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 10,
                'vitri' => 'home_banner_promotion_3',
                'hinhanh' => 'shopee-07.jpg',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 11,
                'vitri' => 'home_banner_ads',
                'hinhanh' => 'shopee-05.jpg',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id' => 12,
                'vitri' => 'home_banner_product',
                'hinhanh' => 'shopee-09.jfif',
                'lienket' => 'https://shopee.tw',
                'mota' => 'Liên kết đến Shopee',
                'trangthai' => 'Hiển thị',
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChuongTrinhSuKienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $now = Carbon::now('Asia/Ho_Chi_Minh');

        DB::table('chuongtrinhsukien')->insert([
            [
                'ten' => 'Tuần Lễ Vàng - Flash Sale 9.9',

                'media' => 'https://example.com/media/flashsale99.png',
                'mota' => 'Sự kiện giảm giá lớn nhất cho sản phẩm Droppi màu vàng trên App Store - chỉ diễn ra trong 1 ngày!',
                'ngaybatdau' => '2025-09-09 00:00:00',
                'ngayketthuc' => '2025-09-09 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Tuần Lễ Vàng - Tuần lễ Vàng',

                'media' => 'https://example.com/media/tuanlevang.png',
                'mota' => 'Chương trình khuyến mãi đặc biệt kéo dài 7 ngày dành riêng cho Droppi vàng.',
                'ngaybatdau' => '2025-10-01 00:00:00',
                'ngayketthuc' => '2025-10-07 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Tuần Lễ Vàng - Black Friday 2025',

                'media' => 'https://example.com/media/blackfriday.png',
                'mota' => 'Giảm giá sâu cho các sản phẩm Droppi màu vàng trên App Store nhân dịp Black Friday.',
                'ngaybatdau' => '2025-11-28 00:00:00',
                'ngayketthuc' => '2025-11-28 23:59:59',
                'trangthai' => 'cho_duyet',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Tuần Lễ Vàng - Mua 1 Tặng 1',

                'media' => 'https://example.com/media/mua1tang1.png',
                'mota' => 'Mua một sản phẩm Droppi màu vàng tặng thêm một sản phẩm bất kỳ trong danh mục.',
                'ngaybatdau' => '2025-12-01 00:00:00',
                'ngayketthuc' => '2025-12-03 23:59:59',
                'trangthai' => 'ngung_hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

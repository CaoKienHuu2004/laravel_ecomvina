<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChuongTrinhSuKienSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        DB::table('chuongtrinhsukien')->insert([
            // ==== 4 cái cũ ====
            [
                'ten' => 'Tuần Lễ Vàng - Flash Sale 9.9',
                // 'media' => 'https://example.com/media/flashsale99.png',
                'mota' => 'Sự kiện giảm giá lớn nhất cho sản phẩm Droppi màu vàng trên App Store - chỉ diễn ra trong 1 ngày!',
                'ngaybatdau' => '2025-09-09 00:00:00',
                'ngayketthuc' => '2025-09-09 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Tuần Lễ Vàng - Tuần lễ Vàng',
                // 'media' => 'https://example.com/media/tuanlevang.png',
                'mota' => 'Chương trình khuyến mãi đặc biệt kéo dài 7 ngày dành riêng cho Droppi vàng.',
                'ngaybatdau' => '2025-10-01 00:00:00',
                'ngayketthuc' => '2025-10-07 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Tuần Lễ Vàng - Black Friday 2025',
                // 'media' => 'https://example.com/media/blackfriday.png',
                'mota' => 'Giảm giá sâu cho các sản phẩm Droppi màu vàng trên App Store nhân dịp Black Friday.',
                'ngaybatdau' => '2025-11-28 00:00:00',
                'ngayketthuc' => '2025-11-28 23:59:59',
                'trangthai' => 'cho_duyet',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Tuần Lễ Vàng - Mua 1 Tặng 1',
                // 'media' => 'https://example.com/media/mua1tang1.png',
                'mota' => 'Mua một sản phẩm Droppi màu vàng tặng thêm một sản phẩm bất kỳ trong danh mục.',
                'ngaybatdau' => '2025-12-01 00:00:00',
                'ngayketthuc' => '2025-12-03 23:59:59',
                'trangthai' => 'ngung_hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ==== 10 cái mới ====
            [
                'ten' => 'Giáng Sinh An Lành 2025',
                // 'media' => 'https://example.com/media/giangsinh.png',
                'mota' => 'Khuyến mãi quà tặng hấp dẫn cho mùa Giáng Sinh.',
                'ngaybatdau' => '2025-12-20 00:00:00',
                'ngayketthuc' => '2025-12-26 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Tết Nguyên Đán 2026 - Siêu Sale',
                // 'media' => 'https://example.com/media/tet.png',
                'mota' => 'Đón Tết cùng ưu đãi đặc biệt cho khách hàng thân thiết.',
                'ngaybatdau' => '2026-01-25 00:00:00',
                'ngayketthuc' => '2026-02-05 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Valentine Ngọt Ngào 2026',
                // 'media' => 'https://example.com/media/valentine.png',
                'mota' => 'Ưu đãi lãng mạn cho các cặp đôi.',
                'ngaybatdau' => '2026-02-10 00:00:00',
                'ngayketthuc' => '2026-02-15 23:59:59',
                'trangthai' => 'cho_duyet',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Ngày Quốc Tế Phụ Nữ 8/3',
                // 'media' => 'https://example.com/media/phunu.png',
                'mota' => 'Tôn vinh phái đẹp với hàng ngàn ưu đãi.',
                'ngaybatdau' => '2026-03-05 00:00:00',
                'ngayketthuc' => '2026-03-08 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Ngày Cá Tháng Tư - Deal Bất Ngờ',
                // 'media' => 'https://example.com/media/aprilfool.png',
                'mota' => 'Ưu đãi cực sốc, chỉ có trong ngày 1/4.',
                'ngaybatdau' => '2026-04-01 00:00:00',
                'ngayketthuc' => '2026-04-01 23:59:59',
                'trangthai' => 'ngung_hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Mùa Hè Sôi Động 2026',
                // 'media' => 'https://example.com/media/summer.png',
                'mota' => 'Chào hè rực rỡ với nhiều chương trình khuyến mãi hấp dẫn.',
                'ngaybatdau' => '2026-06-01 00:00:00',
                'ngayketthuc' => '2026-06-30 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Siêu Sale Mùa Thu 2026',
                // 'media' => 'https://example.com/media/autumn.png',
                'mota' => 'Ưu đãi hấp dẫn trong mùa thu cho các sản phẩm hot.',
                'ngaybatdau' => '2026-09-15 00:00:00',
                'ngayketthuc' => '2026-09-25 23:59:59',
                'trangthai' => 'cho_duyet',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Ngày Nhà Giáo Việt Nam 20/11',
                // 'media' => 'https://example.com/media/nhagiaovietnam.png',
                'mota' => 'Tri ân thầy cô với quà tặng và ưu đãi đặc biệt.',
                'ngaybatdau' => '2026-11-18 00:00:00',
                'ngayketthuc' => '2026-11-21 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Noel 2026 - Sale Cuối Năm',
                // 'media' => 'https://example.com/media/noel.png',
                'mota' => 'Giáng sinh an lành với siêu khuyến mãi cuối năm.',
                'ngaybatdau' => '2026-12-20 00:00:00',
                'ngayketthuc' => '2026-12-27 23:59:59',
                'trangthai' => 'ngung_hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaGiamGiaSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        DB::table('ma_giamgia')->insert([
            [
                'magiamgia' => 'FLASHSALE99',
                'mota' => 'Giảm 99K cho đơn hàng trong ngày 9.9',
                'giatri' => 99000,
                'dieukien' => 'Áp dụng cho đơn hàng từ 500.000đ',
                'ngaybatdau' => '2025-09-09',
                'ngayketthuc' => '2025-09-09',
                'trangthai' => 'hoat_dong',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'NEWUSER100',
                'mota' => 'Voucher 100K cho khách hàng mới',
                'giatri' => 100000,
                'dieukien' => 'Chỉ áp dụng cho tài khoản đăng ký mới',
                'ngaybatdau' => '2025-09-01',
                'ngayketthuc' => '2025-12-31',
                'trangthai' => 'hoat_dong',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'FREESHIP50',
                'mota' => 'Giảm tối đa 50K phí ship',
                'giatri' => 50000,
                'dieukien' => 'Áp dụng cho tất cả đơn hàng',
                'ngaybatdau' => '2025-09-01',
                'ngayketthuc' => '2025-11-30',
                'trangthai' => 'hoat_dong',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'BIRTHDAY200',
                'mota' => 'Giảm 200K cho khách hàng sinh nhật trong tháng',
                'giatri' => 200000,
                'dieukien' => 'Áp dụng trong tháng sinh nhật, đơn hàng từ 1 triệu',
                'ngaybatdau' => '2025-01-01',
                'ngayketthuc' => '2025-12-31',
                'trangthai' => 'tam_khoa',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'BLACKFRIDAY25',
                'mota' => 'Giảm 25% cho toàn bộ đơn hàng Black Friday',
                'giatri' => 250000, // ví dụ giả định 25% max 250K
                'dieukien' => 'Đơn hàng từ 1 triệu',
                'ngaybatdau' => '2025-11-28',
                'ngayketthuc' => '2025-11-28',
                'trangthai' => 'hoat_dong',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'XMAS150',
                'mota' => 'Giáng Sinh - Giảm 150K',
                'giatri' => 150000,
                'dieukien' => 'Áp dụng cho tất cả sản phẩm Droppi vàng',
                'ngaybatdau' => '2025-12-20',
                'ngayketthuc' => '2025-12-25',
                'trangthai' => 'hoat_dong',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'CLEARANCE50',
                'mota' => 'Giảm 50% cho hàng tồn kho',
                'giatri' => 500000,
                'dieukien' => 'Áp dụng cho danh mục "Điện máy"',
                'ngaybatdau' => '2025-08-01',
                'ngayketthuc' => '2025-08-31',
                'trangthai' => 'het_han',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'VIPCUSTOMER20',
                'mota' => 'Giảm 20% cho khách VIP',
                'giatri' => 200000,
                'dieukien' => 'Chỉ áp dụng cho tài khoản VIP',
                'ngaybatdau' => '2025-09-01',
                'ngayketthuc' => '2025-12-31',
                'trangthai' => 'hoat_dong',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'HALLOWEEN66',
                'mota' => 'Halloween Sale - Giảm 66K',
                'giatri' => 66000,
                'dieukien' => 'Đơn hàng từ 399K',
                'ngaybatdau' => '2025-10-31',
                'ngayketthuc' => '2025-10-31',
                'trangthai' => 'hoat_dong',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'magiamgia' => 'TET2026',
                'mota' => 'Tết 2026 - Giảm 300K',
                'giatri' => 300000,
                'dieukien' => 'Áp dụng cho đơn từ 2 triệu',
                'ngaybatdau' => '2026-01-15',
                'ngayketthuc' => '2026-02-05',
                'trangthai' => 'da_xoa',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}

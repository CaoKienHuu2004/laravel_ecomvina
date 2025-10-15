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
                'dieukien' => 'donhang_toi_thieu_500k',
                'ngaybatdau' => '2025-09-09 00:00:00',
                'ngayketthuc' => '2025-09-09 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'NEWUSER100',
                'mota' => 'Voucher 100K cho khách hàng mới',
                'giatri' => 100000,
                'dieukien' => 'khachhang_moi',
                'ngaybatdau' => '2025-09-01 00:00:00',
                'ngayketthuc' => '2025-12-31 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'FREESHIP50',
                'mota' => 'Giảm tối đa 50K phí ship',
                'giatri' => 50000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2025-09-01 00:00:00',
                'ngayketthuc' => '2025-11-30 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'BIRTHDAY200',
                'mota' => 'Giảm 200K cho khách hàng sinh nhật trong tháng',
                'giatri' => 200000,
                'dieukien' => 'khachhang_than_thiet',
                'ngaybatdau' => '2025-01-01 00:00:00',
                'ngayketthuc' => '2025-12-31 23:59:59',
                'trangthai' => 'tam_khoa',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'BLACKFRIDAY25',
                'mota' => 'Giảm 25% cho toàn bộ đơn hàng Black Friday',
                'giatri' => 250000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2025-11-28 00:00:00',
                'ngayketthuc' => '2025-11-28 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'XMAS150',
                'mota' => 'Giáng Sinh - Giảm 150K',
                'giatri' => 150000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2025-12-20 00:00:00',
                'ngayketthuc' => '2025-12-25 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'CLEARANCE50',
                'mota' => 'Giảm 50% cho hàng tồn kho',
                'giatri' => 500000,
                'dieukien' => 'the_loai_cu_the_ban_cham',
                'ngaybatdau' => '2025-08-01 00:00:00',
                'ngayketthuc' => '2025-08-31 23:59:59',
                'trangthai' => 'het_han',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'VIPCUSTOMER20',
                'mota' => 'Giảm 20% cho khách VIP',
                'giatri' => 200000,
                'dieukien' => 'khachhang_than_thiet',
                'ngaybatdau' => '2025-09-01 00:00:00',
                'ngayketthuc' => '2025-12-31 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'HALLOWEEN66',
                'mota' => 'Halloween Sale - Giảm 66K',
                'giatri' => 66000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2025-10-31 00:00:00',
                'ngayketthuc' => '2025-10-31 23:59:59',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'magiamgia' => 'TET2026',
                'mota' => 'Tết 2026 - Giảm 300K',
                'giatri' => 300000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2026-01-15 00:00:00',
                'ngayketthuc' => '2026-02-05 23:59:59',
                'trangthai' => 'da_xoa',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

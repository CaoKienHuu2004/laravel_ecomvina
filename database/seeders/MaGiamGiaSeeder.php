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

        DB::table('magiamgia')->insert([
            [
                'magiamgia' => 10001,
                'mota' => 'Giảm 99K cho đơn hàng trong ngày 9.9',
                'giatri' => 99000,
                'dieukien' => 'donhang_toi_thieu_500k',
                'ngaybatdau' => '2025-09-09',
                'ngayketthuc' => '2025-09-09',
                'trangthai' => 'Hoạt động',
            ],
            [
                'magiamgia' => 10002,
                'mota' => 'Voucher 100K cho khách hàng mới',
                'giatri' => 100000,
                'dieukien' => 'khachhang_moi',
                'ngaybatdau' => '2025-09-01',
                'ngayketthuc' => '2025-12-31',
                'trangthai' => 'Hoạt động',
            ],
            [
                'magiamgia' => 10003,
                'mota' => 'Giảm tối đa 50K phí ship',
                'giatri' => 50000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2025-09-01',
                'ngayketthuc' => '2025-11-30',
                'trangthai' => 'Hoạt động',
            ],
            [
                'magiamgia' => 10004,
                'mota' => 'Giảm 200K cho khách hàng sinh nhật trong tháng',
                'giatri' => 200000,
                'dieukien' => 'khachhang_than_thiet',
                'ngaybatdau' => '2025-01-01',
                'ngayketthuc' => '2025-12-31',
                'trangthai' => 'Tạm khóa',
            ],
            // [
            //     'magiamgia' => 10005,
            //     'mota' => 'Giảm 25% cho toàn bộ đơn hàng Black Friday',
            //     'giatri' => 25,
            //     'dieukien' => 'tatca',
            //     'ngaybatdau' => '2025-11-28',
            //     'ngayketthuc' => '2025-11-28',
            //     'trangthai' => 'Hoạt động',
            // ],
            [
                'magiamgia' => 10006,
                'mota' => 'Giáng Sinh - Giảm 150K',
                'giatri' => 150000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2025-12-20',
                'ngayketthuc' => '2025-12-25',
                'trangthai' => 'Hoạt động',
            ],
            // [
            //     'magiamgia' => 10007,
            //     'mota' => 'Giảm 50% cho hàng tồn kho',
            //     'giatri' => 50,
            //     'dieukien' => 'the_loai_cu_the_ban_cham',
            //     'ngaybatdau' => '2025-08-01',
            //     'ngayketthuc' => '2025-08-31',
            //     'trangthai' => 'Dừng hoạt động',
            // ],
            // [
            //     'magiamgia' => 10008,
            //     'mota' => 'Giảm 20% cho khách VIP',
            //     'giatri' => 20,
            //     'dieukien' => 'khachhang_than_thiet',
            //     'ngaybatdau' => '2025-09-01',
            //     'ngayketthuc' => '2025-12-31',
            //     'trangthai' => 'Hoạt động',
            // ],
            [
                'magiamgia' => 10009,
                'mota' => 'Halloween Sale - Giảm 66K',
                'giatri' => 66000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2025-10-31',
                'ngayketthuc' => '2025-10-31',
                'trangthai' => 'Hoạt động',
            ],
            [
                'magiamgia' => 10010,
                'mota' => 'Tết 2026 - Giảm 300K',
                'giatri' => 300000,
                'dieukien' => 'tatca',
                'ngaybatdau' => '2026-01-15',
                'ngayketthuc' => '2026-02-05',
                'trangthai' => 'Tạm khóa',
            ],
        ]);
    }
}

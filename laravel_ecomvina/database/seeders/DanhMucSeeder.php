<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DanhMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $data = [
            [
                'ten' => 'Sức khỏe',
                'media' => 'suc_khoe.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Chăm sóc cá nhân',
                'media' => 'cham_soc_ca_nhan.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Điện máy',
                'media' => 'dien_may.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Thiết bị y tế',
                'media' => 'thiet_bi_y_te.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Bách hóa',
                'media' => 'bach_hoa.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Nhà cửa - Đời sống',
                'media' => 'nha_cua.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Mẹ và bé',
                'media' => 'me_va_be.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Thời trang',
                'media' => 'thoi_trang.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Sản phẩm khác',
                'media' => 'san_pham_khac.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'ten' => 'Làm đẹp',
                'media' => 'lam_dep.png',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table('danh_muc')->insert($data);
    }
}

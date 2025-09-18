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
        $now = Carbon::now();

        $data = [
            ['ten' => 'Sức khỏe', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Chăm sóc cá nhân', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Điện máy', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Thiết bị y tế', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Bách hóa', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Nhà cửa - Đời sống', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Mẹ và bé', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Thời trang', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
            ['ten' => 'Sản phẩm khác', 'trangthai' => 'hoat_dong', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('danh_muc')->insert($data);
    }
}

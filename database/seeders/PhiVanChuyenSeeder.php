<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhiVanChuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ để tránh trùng lặp
        DB::table('phivanchuyen')->delete();

        // Thêm 3 bản ghi mẫu
        DB::table('phivanchuyen')->insert([
            [
                'ten' => 'Nội thành',
                'phi' => 25000,
                'trangthai' => 'hiển thị',
            ],
            [
                'ten' => 'Ngoại thành',
                'phi' => 35000,
                'trangthai' => 'hiển thị',
            ],
            [
                'ten' => 'Miễn phí',
                'phi' => 0,
                'trangthai' => 'hiển thị',
            ],
        ]);
    }
}

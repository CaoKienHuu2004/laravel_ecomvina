<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiBienTheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['ten' => 'Lọ', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Hộp', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Gói', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Túi 500ml', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Hộp (Vỏ lụa) 500g', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Hộp (đã lột vỏ) 500g', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Chai', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Bình xịt', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Cái', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Chai 45ml', 'trangthai' => 'Hiển thị'],
            ['ten' => 'Loại 15ml', 'trangthai' => 'Hiển thị'],
        ];

        DB::table('loaibienthe')->insert($data);
    }
}

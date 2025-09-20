<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoaiBientheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loai_bienthe')->insert([
            [
                'id' => 1,
                'ten' => 'lọ',
                'trangthai' => 'hoat_dong',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'ten' => 'hộp',
                'trangthai' => 'hoat_dong',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'ten' => 'chai',
                'trangthai' => 'hoat_dong',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'ten' => 'Chiếc',
                'trangthai' => 'hoat_dong',
                'created_at' => '2025-08-24 04:00:41',
                'updated_at' => '2025-08-24 04:00:41',
            ],
            [
                'id' => 5,
                'ten' => 'Thùng',
                'trangthai' => 'hoat_dong',
                'created_at' => '2025-08-24 04:13:36',
                'updated_at' => '2025-08-24 04:13:36',
            ],
            [
                'id' => 6,
                'ten' => 'Cái',
                'trangthai' => 'hoat_dong',
                'created_at' => '2025-08-24 04:13:36',
                'updated_at' => '2025-08-24 04:13:36',
            ],
            [
                'id' => 7,
                'ten' => 'hehe',
                'trangthai' => 'hoat_dong',
                'created_at' => '2025-08-24 04:19:47',
                'updated_at' => '2025-08-24 04:19:47',
            ],
            [
                'id' => 8,
                'ten' => 'haha',
                'trangthai' => 'hoat_dong',
                'created_at' => '2025-08-24 04:20:19',
                'updated_at' => '2025-08-24 04:20:19',
            ],
            [
                'id' => 9,
                'ten' => 'Lọ (265ml)',
                'trangthai' => 'hoat_dong',
                'created_at' => '2025-08-24 05:20:14',
                'updated_at' => '2025-08-24 05:20:14',
            ],
            [
                'id' => 10,
                'ten' => 'Hộp (30 ống)',
                'trangthai' => 'hoat_dong',
                'created_at' => '2025-09-08 05:21:36',
                'updated_at' => '2025-09-08 05:21:36',
            ],
        ]);
    }
}

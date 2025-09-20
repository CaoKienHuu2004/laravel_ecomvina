<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuKienKhuyenMaiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('sukien_khuyenmai')->insert([
            [
                'id_khuyenmai' => 1, // quà tặng id 1
                'id_sukien' => 1,    // gắn vào sự kiện "Flash Sale 9.9"
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_khuyenmai' => 2,
                'id_sukien' => 2,    // gắn vào sự kiện "Tuần lễ vàng"
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_khuyenmai' => 3,
                'id_sukien' => 3,    // gắn vào sự kiện Black Friday
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_khuyenmai' => 4,
                'id_sukien' => 4,    // gắn vào sự kiện Mua 1 Tặng 1
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

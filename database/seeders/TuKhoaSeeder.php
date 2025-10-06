<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TuKhoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $data = [
            ['dulieu' => 'Chăm sóc cá nhân', 'soluot' => 150
            , 'created_at' => $now, 'updated_at' => $now],
            ['dulieu' => 'Làm đẹp', 'soluot' => 200
            , 'created_at' => $now, 'updated_at' => $now],

            ['dulieu' => 'Tìm kiếm sản phẩm', 'soluot' => 80
            , 'created_at' => $now, 'updated_at' => $now],
            ['dulieu' => 'Thực phâm thức năng', 'soluot' => 120
            , 'created_at' => $now, 'updated_at' => $now],
            ['dulieu' => 'Điện máy', 'soluot' => 90
            , 'created_at' => $now, 'updated_at' => $now],
            ['dulieu' => 'Thời trang', 'soluot' => 70
            , 'created_at' => $now, 'updated_at' => $now],
            ['dulieu' => 'Bách hóa', 'soluot' => 70
            , 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('tu_khoa')->insert($data);
    }
}

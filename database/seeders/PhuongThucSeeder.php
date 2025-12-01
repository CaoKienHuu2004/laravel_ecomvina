<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhuongThucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        DB::table('phuongthuc')->delete();

        // Thêm dữ liệu mới
        DB::table('phuongthuc')->insert([
            [
                'ten' => 'Chuyển khoản ngân hàng trực tiếp',
                'maphuongthuc' => 'dbt',
                'trangthai' => 'Hoạt động',
            ],
            [
                'ten' => 'Kiểm tra thanh toán',
                'maphuongthuc' => 'cp',
                'trangthai' => 'Hoạt động',
            ],
            [
                'ten' => 'Thanh toán khi nhận hàng (COD)',
                'maphuongthuc' => 'cod',
                'trangthai' => 'Hoạt động',
            ],
        ]);

    }
}

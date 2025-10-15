<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhuongThucThanhToanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        DB::table('phuongthuc_thanhtoan')->insert([
            [
                'ten' => 'Chuyển khoản ngân hàng trực tiếp',
                'ma' => 'dbt',
                'mota' => 'Khách hàng thanh toán trực tiếp bằng chuyển khoản ngân hàng. Sau khi chuyển, vui lòng gửi minh chứng để xác nhận đơn hàng.',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Kiểm tra thanh toán',
                'ma' => 'cp',
                'mota' => 'Phương thức dành cho các đơn hàng cần xác nhận thủ công trước khi hoàn tất thanh toán (Check payments).',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten' => 'Thanh toán khi nhận hàng (COD)',
                'ma' => 'cod',
                'mota' => 'Khách hàng thanh toán trực tiếp bằng tiền mặt cho nhân viên giao hàng khi nhận sản phẩm.',
                'trangthai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ThanhToanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách đơn hàng đã có (tối thiểu 10 đơn từ DonHangSeeder)
        $orderIds = DB::table('don_hang')->pluck('id')->toArray();

        $payments = [];

        foreach (array_slice($orderIds, 0, 10) as $i => $orderId) {
            $gia = rand(50000, 2000000);
            $hinhthucthanhtoan = collect(['dbt', 'cp', 'cod'])->random();
            $trangthai = collect([
                'cho_xac_nhan',
                'dang_xu_ly',
                'thanh_cong',
                'that_bai',
                'da_huy',
                'hoan_tien',
                'tre_han',
                'cho_xac_nhan_ngan_hang'
            ])->random();

            $payments[] = [
                'nganhang'       => $hinhthucthanhtoan === 'dbt' ? 'Vietcombank' : null,
                'gia'            => $gia,
                'noidung'        => "Thanh toán cho đơn hàng #$orderId",
                'magiaodich'     => strtoupper(Str::random(12)), // random mã giao dịch
                'ngaythanhtoan'  => $now->subDays(rand(0, 15)),
                'hinhthucthanhtoan' => $hinhthucthanhtoan,
                'trangthai'      => $trangthai,
                'id_donhang'     => $orderId,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        DB::table('thanh_toan')->insert($payments);
    }
}

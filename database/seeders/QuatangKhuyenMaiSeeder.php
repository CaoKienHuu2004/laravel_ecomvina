<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuatangKhuyenMaiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('quatang_khuyenmai')->insert([
            [
                'soluong' => 100,
                'mota' => 'Tặng kèm Droppi vàng mini khi đơn hàng từ 500k',
                'ngaybatdau' => '2025-09-01 00:00:00',
                'ngayketthuc' => '2025-09-30 23:59:59',
                'min_donhang' => 500000,
                'id_bienthe' => 1, // phải tồn tại trong bảng bienthe_sp
                'id_thuonghieu' => 1, // phải tồn tại trong bảng thuong_hieu
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 50,
                'mota' => 'Voucher 100k áp dụng cho sản phẩm Droppi vàng',
                'ngaybatdau' => '2025-10-01 00:00:00',
                'ngayketthuc' => '2025-10-07 23:59:59',
                'min_donhang' => 1000000,
                'id_bienthe' => 13,
                'id_thuonghieu' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 200,
                'mota' => 'Mua 2 Droppi vàng tặng thêm 1 hộp',
                'ngaybatdau' => '2025-11-20 00:00:00',
                'ngayketthuc' => '2025-11-30 23:59:59',
                'min_donhang' => 700000,
                'id_bienthe' => 14,
                'id_thuonghieu' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 30,
                'mota' => 'Quà tặng Black Friday: Droppi vàng Limited Edition',
                'ngaybatdau' => '2025-11-28 00:00:00',
                'ngayketthuc' => '2025-11-28 23:59:59',
                'min_donhang' => 2000000,
                'id_bienthe' => 15,
                'id_thuonghieu' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuatangKhuyenMaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách id_bienthe có sẵn trong bảng bienthe_sp
        $bienthes = DB::table('bienthe_sp')->pluck('id')->toArray();

        // Nếu bảng bienthe_sp chưa có dữ liệu thì bỏ qua
        if (empty($bienthes)) {
            $this->command->warn('⚠️ Bảng bienthe_sp chưa có dữ liệu, bỏ qua seeder QuatangKhuyenMaiSeeder.');
            return;
        }

        $now = Carbon::now();

        $data = [
            [
                'id_bienthe' => $bienthes[0] ?? null,
                'id_thuonghieu' => 1,
                'min_donhang' => 500000,
                'mota' => 'Tặng kèm Droppi vàng mini khi đơn hàng từ 500k',
                'ngaybatdau' => '2025-09-01',
                'ngayketthuc' => '2025-09-30',
                'soluong' => 100,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[1] ?? $bienthes[0],
                'id_thuonghieu' => 2,
                'min_donhang' => 1000000,
                'mota' => 'Voucher 100k áp dụng cho sản phẩm Droppi vàng',
                'ngaybatdau' => '2025-10-01',
                'ngayketthuc' => '2025-10-07',
                'soluong' => 50,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[2] ?? $bienthes[0],
                'id_thuonghieu' => 1,
                'min_donhang' => 700000,
                'mota' => 'Mua 2 Droppi vàng tặng thêm 1 hộp',
                'ngaybatdau' => '2025-11-20',
                'ngayketthuc' => '2025-11-30',
                'soluong' => 200,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[0],
                'id_thuonghieu' => 3,
                'min_donhang' => 2000000,
                'mota' => 'Quà tặng Black Friday: Droppi vàng Limited Edition',
                'ngaybatdau' => '2025-11-28',
                'ngayketthuc' => '2025-11-28',
                'soluong' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[1] ?? $bienthes[0],
                'id_thuonghieu' => 2,
                'min_donhang' => 800000,
                'mota' => 'Mua 1 tặng 1 cho khách hàng VIP',
                'ngaybatdau' => '2025-12-01',
                'ngayketthuc' => '2025-12-15',
                'soluong' => 75,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[2] ?? $bienthes[0],
                'id_thuonghieu' => 1,
                'min_donhang' => 300000,
                'mota' => 'Tặng kèm túi vải sinh thái khi mua từ 300k',
                'ngaybatdau' => '2025-12-20',
                'ngayketthuc' => '2025-12-31',
                'soluong' => 40,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[0],
                'id_thuonghieu' => 2,
                'min_donhang' => 400000,
                'mota' => 'Voucher 50k cho đơn hàng từ 400k',
                'ngaybatdau' => '2026-01-05',
                'ngayketthuc' => '2026-01-20',
                'soluong' => 120,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[1] ?? $bienthes[0],
                'id_thuonghieu' => 3,
                'min_donhang' => 1500000,
                'mota' => 'Tặng kèm sản phẩm nhỏ cho combo gia đình',
                'ngaybatdau' => '2026-02-01',
                'ngayketthuc' => '2026-02-10',
                'soluong' => 60,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[2] ?? $bienthes[0],
                'id_thuonghieu' => 1,
                'min_donhang' => 1000000,
                'mota' => 'Khuyến mãi Valentine: Tặng kèm hộp quà đặc biệt',
                'ngaybatdau' => '2026-02-14',
                'ngayketthuc' => '2026-02-14',
                'soluong' => 25,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_bienthe' => $bienthes[0],
                'id_thuonghieu' => 2,
                'min_donhang' => 600000,
                'mota' => 'Tết Nguyên Đán: Tặng bao lì xì may mắn',
                'ngaybatdau' => '2026-01-25',
                'ngayketthuc' => '2026-02-05',
                'soluong' => 80,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('quatang_khuyenmai')->insert($data);
    }
}

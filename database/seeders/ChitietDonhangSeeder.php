<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChitietDonhangSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách id đơn hàng
        $orderIds = DB::table('don_hang')->pluck('id')->toArray();

        // Lấy danh sách biến thể sản phẩm
        $variantIds = DB::table('bienthe_sp')
            ->select('id', 'gia', 'giagiam')
            ->get();

        $details = [];
        $usedPairs = []; // Lưu các cặp đã sử dụng id_donhang-id_bienthe

        foreach ($orderIds as $orderId) {
            // Tạo 1-5 biến thể cho mỗi đơn
            $numVariants = rand(1, 5);

            $availableVariants = $variantIds->pluck('id')->toArray();

            for ($j = 0; $j < $numVariants; $j++) {
                if (empty($availableVariants)) {
                    break; // không còn biến thể để chọn
                }

                // Chọn ngẫu nhiên biến thể chưa dùng cho đơn này
                $variantId = collect($availableVariants)->random();

                // Loại biến thể vừa chọn khỏi danh sách để tránh trùng
                $availableVariants = array_diff($availableVariants, [$variantId]);

                // Kiểm tra nếu trùng cặp, bỏ qua
                if (in_array("$orderId-$variantId", $usedPairs)) {
                    continue;
                }

                $variant = $variantIds->firstWhere('id', $variantId);
                $giaBan = $variant->giagiam > 0 ? $variant->giagiam : $variant->gia;
                $soluong = rand(1, 5);
                $tongtien = $giaBan * $soluong;

                $details[] = [
                    'gia'        => $giaBan,
                    'soluong'    => $soluong,
                    'tongtien'   => $tongtien,
                    'id_donhang' => $orderId,
                    'id_bienthe' => $variantId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $usedPairs[] = "$orderId-$variantId"; // đánh dấu đã dùng
            }
        }

        DB::table('donhang_chitiet')->insert($details);
    }
}

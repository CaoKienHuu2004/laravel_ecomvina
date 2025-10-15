<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonHangSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách user có vai trò 'user'
        $userIds = DB::table('nguoi_dung')
            ->where('vaitro', 'user')
            ->pluck('id')
            ->toArray();

        if (empty($userIds)) {
            throw new \Exception("Chưa có người dùng vai trò 'user'");
        }

        // Lấy danh sách mã giảm giá đang hoạt động
        $couponIds = DB::table('ma_giamgia')
            ->where('trangthai', 'hoat_dong')
            ->pluck('id')
            ->toArray();

        if (empty($couponIds)) {
            throw new \Exception("Chưa có mã giảm giá hoạt động");
        }

        // Lấy danh sách phương thức thanh toán đang hoạt động
        $paymentMethodIds = DB::table('phuongthuc_thanhtoan')
            ->where('trangthai', 'hoat_dong')
            ->pluck('id')
            ->toArray();

        if (empty($paymentMethodIds)) {
            throw new \Exception("Chưa có phương thức thanh toán hoạt động");
        }

        $usedPairs = []; // lưu các cặp id_nguoidung-id_magiamgia đã dùng
        $orders = [];

        for ($i = 1; $i <= 10; $i++) {
            $tongsoluong = rand(1, 10);
            $tongtien = $tongsoluong * rand(50000, 1000000);

            // Chọn user ngẫu nhiên
            $userId = collect($userIds)->random();

            // Lấy coupon cho đơn này
            $availableCoupons = array_filter($couponIds, function ($cid) use ($userId, $usedPairs) {
                return !in_array("$userId-$cid", $usedPairs);
            });

            // Nếu user đã dùng hết coupon, reset để cho phép lặp lại
            if (empty($availableCoupons)) {
                $availableCoupons = $couponIds;
            }

            $couponId = collect($availableCoupons)->random();
            $usedPairs[] = "$userId-$couponId";

            // Chọn phương thức thanh toán ngẫu nhiên
            $paymentId = collect($paymentMethodIds)->random();

            $orders[] = [
                'ma_donhang'   => 'DH' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tongtien'     => $tongtien,
                'tongsoluong'  => $tongsoluong,
                'ghichu'       => "Ghi chú cho đơn hàng số $i",
                'ngaytao'      => $now->copy()->subDays(rand(0, 30)),
                'trangthai'    => collect(['cho_xac_nhan', 'da_xac_nhan', 'dang_giao', 'da_giao', 'da_huy'])->random(),
                'id_nguoidung' => $userId,
                'id_magiamgia' => $couponId,
                'id_phuongthuc_thanhtoan' => $paymentId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('don_hang')->insert($orders);
    }
}

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;

// class DonHangSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $now = Carbon::now('Asia/Ho_Chi_Minh');

//         // Lấy danh sách user có vai trò 'user'
//         $userIds = DB::table('nguoi_dung')
//             ->where('vaitro', 'user')
//             ->pluck('id')
//             ->toArray();

//         if (empty($userIds)) {
//             throw new \Exception("Chưa có người dùng vai trò 'user'");
//         }

//         // Lấy danh sách mã giảm giá đang hoạt động
//         $couponIds = DB::table('ma_giamgia')
//             ->where('trangthai', 'hoat_dong')
//             ->pluck('id')
//             ->toArray();

//         if (empty($couponIds)) {
//             throw new \Exception("Chưa có mã giảm giá hoạt động");
//         }

//         $usedPairs = []; // lưu các cặp id_nguoidung-id_magiamgia đã dùng
//         $orders = [];

//         for ($i = 1; $i <= 10; $i++) {
//             $tongsoluong = rand(1, 10);
//             $tongtien = $tongsoluong * rand(50000, 1000000);

//             // Chọn user ngẫu nhiên
//             $userId = collect($userIds)->random();

//             // Lấy coupon cho đơn này
//             $availableCoupons = array_filter($couponIds, function($cid) use ($userId, $usedPairs) {
//                 return !in_array("$userId-$cid", $usedPairs);
//             });

//             // Nếu user đã dùng hết coupon, reset để cho phép lặp lại
//             if (empty($availableCoupons)) {
//                 $availableCoupons = $couponIds;
//             }

//             $couponId = collect($availableCoupons)->random();
//             $usedPairs[] = "$userId-$couponId";

//             $orders[] = [
//                 'ma_donhang'   => 'DH' . str_pad($i, 4, '0', STR_PAD_LEFT),
//                 'tongtien'     => $tongtien,
//                 'tongsoluong'  => $tongsoluong,
//                 'ghichu'       => "Ghi chú cho đơn hàng số $i",
//                 'ngaytao'      => $now->copy()->subDays(rand(0, 30)),
//                 'trangthai'    => collect(['cho_xac_nhan', 'da_xac_nhan','dang_giao', 'da_giao', 'da_huy'])->random(),
//                 'id_nguoidung' => $userId,
//                 'id_magiamgia'=> $couponId, // luôn có mã giảm giá
//                 'created_at'   => $now,
//                 'updated_at'   => $now,
//             ];
//         }

//         DB::table('don_hang')->insert($orders);
//     }
// }

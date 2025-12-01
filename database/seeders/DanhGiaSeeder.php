<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DanhGiaSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $faker = [
            "Sản phẩm rất tốt, mình rất hài lòng với chất lượng và dịch vụ.",
            "Giao hàng nhanh chóng, đóng gói cẩn thận. Sẽ ủng hộ lần sau!",
            "Chất lượng sản phẩm vượt mong đợi, đáng đồng tiền bát gạo.",
            "Mình đã mua nhiều lần và luôn cảm thấy hài lòng với sản phẩm này.",
            "Sản phẩm đúng như mô tả, không có gì để chê cả.",
            "Dịch vụ khách hàng rất tận tình và chu đáo, cảm ơn shop nhiều!",
            "Sản phẩm sử dụng rất tiện lợi và hiệu quả, mình rất thích.",
            "Giá cả hợp lý, chất lượng tốt. Sẽ giới thiệu cho bạn bè.",
            "Sản phẩm có thiết kế đẹp mắt, sử dụng rất thích.",
            "Mình rất ấn tượng với chất lượng sản phẩm và dịch vụ giao hàng."
        ];

        // Lấy danh sách chi tiết đơn hàng kèm thông tin liên quan
        $chiTietDonHang = DB::table('chitiet_donhang')
            ->join('donhang', 'chitiet_donhang.id_donhang', '=', 'donhang.id')
            ->join('bienthe', 'chitiet_donhang.id_bienthe', '=', 'bienthe.id')
            ->join('sanpham', 'bienthe.id_sanpham', '=', 'sanpham.id')
            ->select('chitiet_donhang.id as id_chitietdonhang', 'donhang.id_nguoidung', 'sanpham.id as id_sanpham')
            ->get();

        if ($chiTietDonHang->isEmpty()) {
            $this->command->warn('⚠️ Không có dữ liệu chi tiết đơn hàng để tạo đánh giá.');
            return;
        }

        $data = [];
        foreach ($chiTietDonHang as $item) {
            // Chỉ tạo khoảng 50% có đánh giá thôi
            if (rand(0, 1) === 0) continue;

            $data[] = [
                'id_nguoidung' => $item->id_nguoidung,
                'id_sanpham' => $item->id_sanpham,
                'id_chitietdonhang' => $item->id_chitietdonhang,
                'diem' => rand(3, 5),
                'noidung' => $faker[array_rand($faker)],
                'trangthai' => 'Hiển thị',
            ];
        }

        if (!empty($data)) {
            DB::table('danhgia')->insert($data);
            $this->command->info('✅ Đã tạo ' . count($data) . ' đánh giá.');
        } else {
            $this->command->warn('⚠️ Không có đánh giá nào được tạo.');
        }
    }
}

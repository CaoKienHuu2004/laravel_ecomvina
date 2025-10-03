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

        $data = [
            [
                'diem' => 4,
                'noidung' => 'Sản phẩm rất tốt, chất lượng đúng như mô tả. Giao hàng nhanh.',
                'media' => 'https://example.com/reviews/review1.jpg',
                'ngaydang' => $now->subDays(5),
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 1,
                'id_nguoidung' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'diem' => 3,
                'noidung' => 'Hàng ổn, nhưng đóng gói chưa kỹ. Cần cải thiện thêm.',
                'media' => 'https://example.com/reviews/review2.jpg',
                'ngaydang' => $now->subDays(2),
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 2,
                'id_nguoidung' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Tạo thêm 18 đánh giá ngẫu nhiên
        for ($i = 3; $i <= 20; $i++) {
            $data[] = [
                'diem' => rand(3, 5), // điểm 3 -> 5
                'noidung' => "Đánh giá thử số $i: chất lượng sản phẩm tốt, hài lòng.",
                'media' => "https://example.com/reviews/review$i.jpg",
                'ngaydang' => $now->subDays(rand(1, 30)),
                'trangthai' => 'hoat_dong',
                'id_sanpham' => rand(1, 20), // chọn ngẫu nhiên trong 20 sản phẩm có trong SanPhamSeeder
                'id_nguoidung' => rand(1, 70), // chọn ngẫu nhiên trong 70 người dùng có trong NguoiDungSeeder
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('danh_gia')->insert($data);
    }
}

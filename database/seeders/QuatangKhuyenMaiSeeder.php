<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuaTangKhuyenMaiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $quatang = [
            // 5 bản cũ
            [
                'soluong' => 2,
                'mota' => 'Khuyến mãi mùa lễ hội - mua 2 tặng 1 cho các sản phẩm chăm sóc cá nhân.',
                'ngaybatdau' => $now->copy()->addDays(2),
                'ngayketthuc' => $now->copy()->addDays(15),
                'soluongapdung' => 2,
                'kieuapdung' => 'tang_1',
                'id_bienthe' => 1,
                'id_cuahang' => 1,
                'id_chuongtrinhsukien' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 2,
                'mota' => 'Giảm 20% cho đơn hàng trên 500.000đ.',
                'ngaybatdau' => $now->copy()->addDays(5),
                'ngayketthuc' => $now->copy()->addDays(20),
                'soluongapdung' => 2,
                'kieuapdung' => 'giam_%',
                'id_bienthe' => 2,
                'id_cuahang' => 1,
                'id_chuongtrinhsukien' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 2,
                'mota' => 'Mua 3 sản phẩm bất kỳ - giảm ngay 30%.',
                'ngaybatdau' => $now->copy()->addDays(10),
                'ngayketthuc' => $now->copy()->addDays(30),
                'soluongapdung' => 3,
                'kieuapdung' => 'giam_%',
                'id_bienthe' => 3,
                'id_cuahang' => 2,
                'id_chuongtrinhsukien' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 2,
                'mota' => 'Mua 2 tặng 1 cho tất cả sản phẩm sữa bột.',
                'ngaybatdau' => $now->copy()->addDays(3),
                'ngayketthuc' => $now->copy()->addDays(18),
                'soluongapdung' => 2,
                'kieuapdung' => 'tang_1',
                'id_bienthe' => 4,
                'id_cuahang' => 3,
                'id_chuongtrinhsukien' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 2,
                'mota' => 'Giảm giá 15% toàn bộ sản phẩm thể thao Adidas.',
                'ngaybatdau' => $now->copy()->addDays(1),
                'ngayketthuc' => $now->copy()->addDays(10),
                'soluongapdung' => 1,
                'kieuapdung' => 'giam_%',
                'id_bienthe' => 5,
                'id_cuahang' => 4,
                'id_chuongtrinhsukien' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 7 bản mới thêm
            [
                'soluong' => 2,
                'mota' => 'Ưu đãi đặc biệt nhân dịp khai trương - giảm ngay 25% cho toàn bộ sản phẩm.',
                'ngaybatdau' => $now->copy()->addDays(1),
                'ngayketthuc' => $now->copy()->addDays(7),
                'soluongapdung' => 1,
                'kieuapdung' => 'giam_%',
                'id_bienthe' => 6,
                'id_cuahang' => 5,
                'id_chuongtrinhsukien' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 2,
                'mota' => 'Giảm 10% khi mua combo 2 sản phẩm thời trang mùa đông.',
                'ngaybatdau' => $now->copy()->addDays(4),
                'ngayketthuc' => $now->copy()->addDays(14),
                'soluongapdung' => 2,
                'kieuapdung' => 'giam_%',
                'id_bienthe' => 7,
                'id_cuahang' => 6,
                'id_chuongtrinhsukien' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 2,
                'mota' => 'Tặng kèm túi vải khi mua giày thể thao chính hãng.',
                'ngaybatdau' => $now->copy()->addDays(3),
                'ngayketthuc' => $now->copy()->addDays(13),
                'soluongapdung' => 1,
                'kieuapdung' => 'tang_1',
                'id_bienthe' => 8,
                'id_cuahang' => 7,
                'id_chuongtrinhsukien' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 2,
                'mota' => 'Giảm giá 50.000đ cho đơn hàng trên 300.000đ.',
                'ngaybatdau' => $now->copy()->addDays(2),
                'ngayketthuc' => $now->copy()->addDays(12),
                'soluongapdung' => 1,
                'kieuapdung' => 'tang_1',
                'id_bienthe' => 9,
                'id_cuahang' => 8,
                'id_chuongtrinhsukien' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 3,
                'mota' => 'Flash Sale cuối tuần - giảm 40% cho tất cả sản phẩm công nghệ.',
                'ngaybatdau' => $now->copy()->addDays(6),
                'ngayketthuc' => $now->copy()->addDays(8),
                'soluongapdung' => 1,
                'kieuapdung' => 'giam_%',
                'id_bienthe' => 10,
                'id_cuahang' => 9,
                'id_chuongtrinhsukien' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 3,
                'mota' => 'Tặng thêm 1 sản phẩm bất kỳ khi mua đơn hàng trên 1.000.000đ.',
                'ngaybatdau' => $now->copy()->addDays(5),
                'ngayketthuc' => $now->copy()->addDays(15),
                'soluongapdung' => 1,
                'kieuapdung' => 'tang_1',
                'id_bienthe' => 11,
                'id_cuahang' => 10,
                'id_chuongtrinhsukien' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'soluong' => 3,
                'mota' => 'Mua 4 sản phẩm cùng loại - giảm ngay 25%.',
                'ngaybatdau' => $now->copy()->addDays(7),
                'ngayketthuc' => $now->copy()->addDays(17),
                'soluongapdung' => 4,
                'kieuapdung' => 'giam_%',
                'id_bienthe' => 12,
                'id_cuahang' => 1,
                'id_chuongtrinhsukien' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('quatang_khuyenmai')->insert($quatang);
    }
}


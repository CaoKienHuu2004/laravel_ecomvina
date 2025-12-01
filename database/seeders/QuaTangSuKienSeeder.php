<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuaTangSuKienSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('quatang_sukien')->truncate(); // Xóa dữ liệu cũ để seed lại

        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $data = [
            [
                'id' => 1,
                'id_bienthe' => 1,
                'id_chuongtrinh' => 1,
                'dieukien' => '2',
                'tieude' => 'Ưu đãi sinh nhật 13/10 - Tặng 1 sản phẩm bất kỳ',
                'thongtin' => 'Mua 2 sản phẩm từ Trung Tâm Bán Hàng Siêu Thị Vina...',
                'hinhanh' => 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-2.webp',
                'luotxem' => 0,
                'ngaybatdau' => '2025-10-18',
                'ngayketthuc' => '2025-11-02',
                'trangthai' => 'Hoạt động',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'id_bienthe' => 2,
                'id_chuongtrinh' => 1,
                'dieukien' => '5',
                'tieude' => 'Tặng 1 sản phẩm từ thương hiệu khi thêm 5 sản phẩm...',
                'thongtin' => 'Không có thông tin',
                'hinhanh' => 'sam-ngoc-linh-truong-sinh-do-thung-24lon-1.webp',
                'luotxem' => 5,
                'ngaybatdau' => '2025-10-18',
                'ngayketthuc' => '2025-10-25',
                'trangthai' => 'Hoạt động',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'id_bienthe' => 18,
                'id_chuongtrinh' => 1,
                'dieukien' => '3',
                'tieude' => 'Tặng 1 quà Trung Thu khi mua 3 sản phẩm từ Trung T...',
                'thongtin' => 'Không có thông tin',
                'hinhanh' => 'banh-trung-thu-2025-thu-an-nhien-banh-chay-hop-2-b.webp',
                'luotxem' => 12,
                'ngaybatdau' => '2025-10-01',
                'ngayketthuc' => '2025-11-20',
                'trangthai' => 'Hoạt động',
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'id_bienthe' => 27,
                'id_chuongtrinh' => 1,
                'dieukien' => '2',
                'tieude' => 'Tặng 1 thiết bị y tế khi 2 sản phẩm y tế khác nhau...',
                'thongtin' => 'Không có thông tin',
                'hinhanh' => 'tam-lot-abena-pad-45x45-1.webp',
                'luotxem' => 0,
                'ngaybatdau' => '2025-10-13',
                'ngayketthuc' => '2025-12-31',
                'trangthai' => 'Hoạt động',
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'id_bienthe' => 17,
                'id_chuongtrinh' => 1,
                'dieukien' => '3',
                'tieude' => 'Tặng 1 sản phẩm bách hóa khi mua 3 sản phẩm bất kỳ...',
                'thongtin' => 'Không có thông tin',
                'hinhanh' => 'nuoc-rua-bat-bio-formula-bo-va-lo-hoi-tui-500ml-1.webp',
                'luotxem' => 1200,
                'ngaybatdau' => '2025-10-13',
                'ngayketthuc' => '2025-12-31',
                'trangthai' => 'Hoạt động',
                'deleted_at' => null,
            ],
            [
                'id' => 6,
                'id_bienthe' => 21,
                'id_chuongtrinh' => 1,
                'dieukien' => '2',
                'tieude' => 'test cập nhật giỏ',
                'thongtin' => 'test cập nhật giỏ',
                'hinhanh' => 'ss.png',
                'luotxem' => 2,
                'ngaybatdau' => '2025-10-27',
                'ngayketthuc' => '2025-10-28',
                'trangthai' => 'Hoạt động',
                'deleted_at' => null,
            ],
        ];

        DB::table('quatang_sukien')->insert($data);

        $this->command->info('✅ Đã thêm ' . count($data) . ' bản ghi vào bảng quatang_sukien.');
    }
}

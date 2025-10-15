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

        // Lấy ID thật từ bảng
        $userIds = DB::table('nguoi_dung')->where('vaitro', 'user')   // chỉ lấy role user
                ->pluck('id')
                ->toArray();
        $productIds = DB::table('san_pham')->pluck('id')->toArray();

        $noidungs = [
            'Sản phẩm rất tốt, chất lượng đúng như mô tả. Giao hàng nhanh.',
            'Hàng ổn, nhưng đóng gói chưa kỹ. Cần cải thiện thêm.',
            'Đánh giá thử: chất lượng sản phẩm tốt, hài lòng.',
            'Mình sẽ tiếp tục ủng hộ trong những lần sau.',
            'Giá cả hợp lý, chất lượng tuyệt vời.',
            'Sản phẩm giống hình, giao đúng hẹn.',
        ];

        $data = [];
        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'id_nguoidung' => $userIds[array_rand($userIds)],
                'id_sanpham'   => $productIds[array_rand($productIds)],
                'diem'         => rand(3, 5),
                'noidung'      => $noidungs[array_rand($noidungs)],
                'media'        => "uploads/danhgia/media/danhgia".$i.".png",
                'ngaydang'     => $now,
                'trangthai'    => 'hoat_dong',
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('danh_gia')->insert($data);
    }
}

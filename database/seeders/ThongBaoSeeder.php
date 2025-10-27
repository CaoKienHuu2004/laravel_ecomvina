<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongBaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        DB::table('thongbao')->delete();

        $nguoiDungs = DB::table('nguoidung')->pluck('id');

        $suKien = [
            [
                'tieude' => 'Sự kiện “Siêu Sale 11.11” sắp bắt đầu!',
                'noidung' => 'Sự kiện khuyến mãi lớn nhất tháng 11 sẽ bắt đầu vào ngày 10/11 và kết thúc 12/11. Giảm giá đến 70% toàn bộ ngành hàng điện tử, thời trang và gia dụng!',
                'lienket' => '/sukien/sieu-sale-11-11',
            ],
            [
                'tieude' => 'Đơn hàng mới của bạn đang được xử lý',
                'noidung' => 'Cảm ơn bạn đã thanh toán đơn hàng. Đơn hàng của bạn hiện đang được chuẩn bị và sẽ sớm được giao đến địa chỉ bạn đã đăng ký.',
                'lienket' => '/taikhoan/donhang',
            ],
            [
                'tieude' => 'Đơn hàng đang được giao!',
                'noidung' => 'Đơn hàng của bạn đã được giao cho đơn vị vận chuyển. Dự kiến sẽ đến tay bạn trong 2-3 ngày tới.',
                'lienket' => '/taikhoan/donhang/tracking',
            ],
            [
                'tieude' => 'Sự kiện “Giáng Sinh Rộn Ràng” sắp bắt đầu!',
                'noidung' => 'Từ ngày 20/12 đến 25/12, nhận ưu đãi đặc biệt cho các sản phẩm quà tặng, đồ trang trí và thời trang mùa đông. Mua sắm ngay hôm nay để nhận quà hấp dẫn!',
                'lienket' => '/sukien/giang-sinh-ron-rang',
            ],
            [
                'tieude' => 'Thông báo: Tích điểm khách hàng thân thiết',
                'noidung' => 'Bạn vừa nhận được thêm 120 điểm thưởng từ đơn hàng gần nhất. Đừng quên sử dụng điểm để giảm giá trong lần mua tiếp theo nhé!',
                'lienket' => '/taikhoan/diem-thuong',
            ],
            [
                'tieude' => 'Ưu đãi “Freeship toàn quốc” đã bắt đầu!',
                'noidung' => 'Từ ngày 1/11 đến 5/11, tất cả đơn hàng từ 200.000đ trở lên đều được miễn phí vận chuyển. Đặt hàng ngay để không bỏ lỡ!',
                'lienket' => '/sukien/freeship-toan-quoc',
            ],
        ];

        $data = [];

        foreach ($nguoiDungs as $idNguoiDung) {
            // Mỗi người dùng nhận ngẫu nhiên 2 thông báo
            $thongBaoNgauNhien = collect($suKien)->random(2);

            foreach ($thongBaoNgauNhien as $tb) {
                $data[] = [
                    'id_nguoidung' => $idNguoiDung,
                    'tieude' => $tb['tieude'],
                    'noidung' => $tb['noidung'],
                    'lienket' => $tb['lienket'],
                    'trangthai' => rand(0, 1) ? 'Đã đọc' : 'Chưa đọc',
                ];
            }
        }

        DB::table('thongbao')->insert($data);
    }
}

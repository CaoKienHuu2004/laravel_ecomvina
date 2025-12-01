<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class YeuThichSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ để tránh trùng lặp
        DB::table('yeuthich')->truncate();

        // Lấy danh sách id người dùng KHÔNG phải admin hoặc seller
        $nguoiDungs = DB::table('nguoidung')
            ->whereNotIn('vaitro', ['admin', 'seller'])
            ->pluck('id')
            ->toArray();

        // Lấy danh sách id sản phẩm
        $sanPhams = DB::table('sanpham')->pluck('id')->toArray();

        if (empty($nguoiDungs) || empty($sanPhams)) {
            $this->command->warn('⚠️ Không có dữ liệu người dùng hoặc sản phẩm để tạo yêu thích.');
            return;
        }

        $data = [];
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Tạo 10 bản ghi ngẫu nhiên (có thể đổi số lượng tùy ý)
        for ($i = 0; $i < 10; $i++) {
            $idNguoiDung = $nguoiDungs[array_rand($nguoiDungs)];
            $idSanPham = $sanPhams[array_rand($sanPhams)];

            // Kiểm tra trùng cặp người dùng - sản phẩm
            if (collect($data)->contains(fn($item) =>
                $item['id_nguoidung'] === $idNguoiDung && $item['id_sanpham'] === $idSanPham
            )) {
                $i--; // Nếu trùng thì thử lại
                continue;
            }

            $data[] = [
                'id_nguoidung' => $idNguoiDung,
                'id_sanpham' => $idSanPham,
                'trangthai' => 'Hiển thị',
            ];
        }

        DB::table('yeuthich')->insert($data);
        $this->command->info('✅ Đã thêm ' . count($data) . ' bản ghi vào bảng yeuthich (loại bỏ admin & seller).');
    }
}

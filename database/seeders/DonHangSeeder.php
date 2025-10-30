<?php

namespace Database\Seeders;

use App\Models\MagiamgiaModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonHangSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách dữ liệu FK
        $nguoiDungIds = DB::table('nguoidung')->pluck('id')->toArray();
        $maGiamGiaIds = MagiamgiaModel::where('trangthai', 'Hoạt động')->pluck('id')->toArray();
        $phivanchuyenIds = DB::table('phivanchuyen')->pluck('id')->toArray();
        $phuongthucIds = DB::table('phuongthuc')->pluck('id')->toArray();

        // Kiểm tra đủ dữ liệu chưa
        if (empty($nguoiDungIds) || empty($phivanchuyenIds) || empty($phuongthucIds)) {
            throw new \Exception("Thiếu dữ liệu ở 1 trong các bảng FK (nguoidung / phivanchuyen / phuongthuc)");
        }

        $orders = [];

        for ($i = 1; $i <= 10; $i++) {
            $idNguoiDung = collect($nguoiDungIds)->random();

            $diaChiCuaNguoiDung = DB::table('diachi_giaohang')
                ->where('id_nguoidung', $idNguoiDung)
                ->pluck('id')
                ->toArray();

            if (empty($diaChiCuaNguoiDung)) continue;

            $bientheIds = DB::table('bienthe')->pluck('id')->toArray();
            if (empty($bientheIds)) {
                $this->command->warn("⚠️ Không có dữ liệu trong bảng bienthe — bỏ qua seeding chi tiết đơn hàng");
                break;
            }

            $idBienthe = collect($bientheIds)->random();
            $soluong = rand(1, 5);
            $dongia = rand(100000, 500000);
            $tamtinh = $soluong * $dongia;

            $idMagiamgia = !empty($maGiamGiaIds) ? collect($maGiamGiaIds)->random() : null;
            $thanhtien = $idMagiamgia ? $tamtinh - rand(10000, 50000) : $tamtinh;

            // ✅ Tạo đơn hàng
            $idDonhang = DB::table('donhang')->insertGetId([
                'id_phuongthuc'      => collect($phuongthucIds)->random(),
                'id_magiamgia'       => $idMagiamgia,
                'id_nguoidung'       => $idNguoiDung,
                'id_phivanchuyen'    => collect($phivanchuyenIds)->random(),
                'id_diachigiaohang'  => collect($diaChiCuaNguoiDung)->random(),
                'madon'              => 'DH' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tongsoluong'        => $soluong,
                'tamtinh'            => $tamtinh,
                'thanhtien'          => $thanhtien,
                'trangthaithanhtoan' => collect(['Chưa thanh toán', 'Đã thanh toán', 'Thanh toán thất bại', 'Đã hoàn tiền'])->random(),
                'trangthai'          => collect(['Chờ xử lý', 'Đã xác nhận', 'Đang chuẩn bị hàng', 'Đang giao hàng', 'Đã giao hàng', 'Đã hủy'])->random(),
                'created_at'         => $now->copy()->subDays(rand(0, 30)),
                'updated_at'         => $now,
                'deleted_at'         => null,
            ]);

            dump("✅ Tạo đơn hàng #{$idDonhang}");

            // ✅ Thêm chi tiết đơn hàng
            DB::table('chitiet_donhang')->insert([
                'id_bienthe' => $idBienthe,
                'id_donhang' => $idDonhang,
                'soluong'    => $soluong,
                'dongia'     => $dongia,
                'trangthai'  => 'Đã đặt',
            ]);

            dump("→ Đã thêm chi tiết cho đơn hàng #{$idDonhang}");
        }

        // Nếu có đơn hợp lệ thì insert
        if (!empty($orders)) {
            DB::table('donhang')->insert($orders);
        } else {
            $this->command->warn("⚠️ Không có đơn hàng nào được tạo do thiếu dữ liệu địa chỉ người dùng.");
        }
    }
}

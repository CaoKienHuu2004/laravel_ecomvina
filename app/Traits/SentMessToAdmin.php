<?php

namespace App\Traits;

use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;

trait SentMessToAdmin
{
    /**
     * Gửi thông báo đến admin với tiêu đề, nội dung, liên kết và loại thông báo cho trước.
     *
     * Hàm kiểm tra xem loại thông báo ($loaithongbao) có thuộc danh sách hợp lệ hay không:
     * 'Đơn hàng', 'Khuyến mãi', 'Hệ thống', 'Quà tặng'.
     * Nếu loại không hợp lệ, hàm sẽ ném ngoại lệ InvalidArgumentException.
     *
     * @param string $tieude        Tiêu đề thông báo
     * @param string $noidung       Nội dung chi tiết của thông báo
     * @param string|null $lienket  Đường dẫn liên kết kèm theo thông báo (có thể null)
     * @param string $loaithongbao  Loại thông báo, phải thuộc enum: 'Đơn hàng', 'Khuyến mãi', 'Hệ thống', 'Quà tặng'
     *
     * @throws \InvalidArgumentException Nếu $loaithongbao không thuộc danh sách hợp lệ
     *
     * @return void
     */
    public function sentMessToAdmin($tieude, $noidung, $lienket, $loaithongbao)
    {
        $allowedTypes = ['Đơn hàng', 'Khuyến mãi', 'Hệ thống', 'Quà tặng'];

        if (!in_array($loaithongbao, $allowedTypes)) {
            // Có thể chọn 1 trong các cách xử lý:
            // 1. Bỏ qua không gửi thông báo
            // return false;

            // 2. Ném ngoại lệ
            throw new \InvalidArgumentException("Loại thông báo không hợp lệ: {$loaithongbao}");

            // 3. Gán mặc định, ví dụ 'Hệ thống'
            // $loaithongbao = 'Hệ thống';
        }

        $adminUser = NguoidungModel::where('vaitro', 'admin')->first();
        ThongbaoModel::create([
            'tieude' => $tieude,
            'noidung' => $noidung,
            'lienket' => $lienket ?? null,
            'loaithongbao' => $loaithongbao,
            'id_nguoidung' => $adminUser->id,
            'trangthai' => "Chưa đọc",
        ]);
    }
}

<?php

namespace App\Traits;

use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;

trait SentMessToClient
{
    /**
     * Gửi thông báo đến client dựa trên ID người dùng.
     *
     * Hàm sẽ kiểm tra:
     * - Loại thông báo ($loaithongbao) phải thuộc enum hợp lệ: 'Đơn hàng', 'Khuyến mãi', 'Hệ thống', 'Quà tặng'.
     * - ID người dùng ($nguoidungId) hợp lệ và tồn tại trong cơ sở dữ liệu.
     *
     * Nếu tất cả hợp lệ, hàm tạo một bản ghi thông báo mới với tiêu đề, nội dung, liên kết (nếu có),
     * và trạng thái mặc định là "Chưa đọc".
     *
     * @param string      $tieude        Tiêu đề của thông báo.
     * @param string      $noidung       Nội dung chi tiết của thông báo.
     * @param string|null $lienket       Liên kết liên quan đến thông báo (có thể null).
     * @param string      $loaithongbao  Loại thông báo, phải thuộc một trong các giá trị enum: 'Đơn hàng', 'Khuyến mãi', 'Hệ thống', 'Quà tặng'.
     * @param int         $nguoidungId   ID của người dùng nhận thông báo.
     *
     * @return bool Trả về true nếu thông báo được tạo thành công,
     *              false nếu loại thông báo không hợp lệ, ID không hợp lệ hoặc người dùng không tồn tại.
     */
    public function sentMessToClient($tieude, $noidung, $lienket, $loaithongbao, int $nguoidungId): bool
    {
        $allowedTypes = ['Đơn hàng', 'Khuyến mãi', 'Hệ thống', 'Quà tặng'];

        if (!in_array($loaithongbao, $allowedTypes, true)) {
            return false;
        }

        if (!$nguoidungId) {
            return false; // id không hợp lệ
        }
        $existingUser = NguoidungModel::find($nguoidungId);
        if (!$existingUser) {
            return false; // người dùng không tồn tại trong DB
        }
        ThongbaoModel::create([
            'tieude' => $tieude,
            'noidung' => $noidung,
            'lienket' => $lienket ?? null,
            'loaithongbao' => $loaithongbao,
            'id_nguoidung' => $nguoidungId,
            'trangthai' => "Chưa đọc",
        ]);
        return true;
    }
}

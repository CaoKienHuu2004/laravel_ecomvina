<?php

namespace App\Traits;

use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;

trait SentMessToClient
{
    /**
     * Gửi thông báo đến client dựa trên id người dùng.
     *
     * Hàm kiểm tra xem id người dùng có hợp lệ và tồn tại trong cơ sở dữ liệu không.
     * Nếu tồn tại, tạo một thông báo mới với tiêu đề, nội dung và liên kết (nếu có),
     * và trạng thái mặc định là "Chưa đọc".
     *
     * @param string $tieude Tiêu đề của thông báo.
     * @param string $noidung Nội dung chi tiết của thông báo.
     * @param string|null $lienket Liên kết liên quan đến thông báo (có thể null).
     * @param int $nguoidungId ID của người dùng nhận thông báo.
     *
     * @return bool Trả về true nếu thông báo được tạo thành công, false nếu id không hợp lệ hoặc người dùng không tồn tại.
     */
    public function sentMessToClient($tieude, $noidung, $lienket, int $nguoidungId): bool
    {
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
            'id_nguoidung' => $nguoidungId,
            'trangthai' => "Chưa đọc",
        ]);
        return true;
    }
}

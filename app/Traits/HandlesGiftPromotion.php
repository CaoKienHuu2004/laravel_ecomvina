<?php

namespace App\Traits;

use App\Models\GiohangModel;
use Illuminate\Support\Facades\DB;

trait HandlesGiftPromotion
{
    /**
     * Áp dụng quà tặng cho 1 biến thể trong giỏ
     *
     * @param int $userId
     * @param int $bientheId
     * @param int $soluong
     * @param int $tongGiaGioHang
     */
    protected function applyGiftPromotion(
        int $userId,
        int $bientheId,
        int $soluong,
        int $tongGiaGioHang,
        // ?int $idChuongTrinh = null
    ): void {

        $query = DB::table('quatang_sukien as qs')
            ->where('qs.id_bienthe', $bientheId)
            ->where('qs.trangthai', 'Hiển thị')
            ->where('qs.dieukiensoluong', '<=', $soluong)
            ->where('qs.dieukiengiatri', '<=', $tongGiaGioHang)
            ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc');

        // if ($idChuongTrinh !== null) {
        //     $query->where('qs.id_chuongtrinh', $idChuongTrinh);
        // }

        $promotion = $query->first();

        // ❌ Không có KM → xóa quà nếu tồn tại
        if (!$promotion) {
            GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $bientheId)
                ->where('thanhtien', 0)
                ->delete();
            return;
        }

        // 🎁 Số lượng quà
        $soQua = intdiv($soluong, (int) $promotion->dieukiensoluong);

        if ($soQua <= 0) {
            GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $bientheId)
                ->where('thanhtien', 0)
                ->delete();
            return;
        }

        // 🔄 Update hoặc create quà tặng
        $giftItem = GiohangModel::where('id_nguoidung', $userId)
            ->where('id_bienthe', $bientheId)
            ->where('thanhtien', 0)
            ->lockForUpdate()
            ->first();

        if ($giftItem) {
            $giftItem->update([
                'soluong'   => $soQua,
                'trangthai' => 'Hiển thị',
            ]);
        } else {
            GiohangModel::create([
                'id_nguoidung' => $userId,
                'id_bienthe'   => $bientheId,
                'soluong'      => $soQua,
                'thanhtien'    => 0,
                'trangthai'    => 'Hiển thị',
            ]);
        }
    }
}

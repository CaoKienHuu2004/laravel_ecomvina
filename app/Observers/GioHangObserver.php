<?php

namespace App\Observers;

use App\Models\GiohangModel;
use App\Models\BientheModel;
use Illuminate\Support\Facades\DB;

/**
 * Observer xử lý các hành vi liên quan đến model GiohangModel
 *
 * Công dụng chính:
 * 1. Thay thế trigger SQL BEFORE INSERT trong bảng 'giohang'.
 * 2. Tự động tính toán cột 'thanhtien' dựa trên:
 *    - Giá gốc của biến thể sản phẩm (giagoc)
 *    - Số lượng sản phẩm trong giỏ (soluong)
 *    - Các chương trình quà tặng còn hiệu lực (quatang_sukien)
 * 3. Cập nhật số lượt tặng ('luottang') của biến thể sản phẩm nếu áp dụng ưu đãi.
 */
class GioHangObserver
{
    /**
     * Xử lý logic trước khi tạo mới bản ghi giỏ hàng
     * (tương đương trigger BEFORE INSERT)
     *
     * @param GiohangModel $gioHang
     */
    public function creating(GiohangModel $gioHang)
    {
        // Lấy thông tin biến thể
        $bienthe = BientheModel::find($gioHang->id_bienthe);
        if (!$bienthe) {
            return;
        }

        $priceUnit = $bienthe->giagoc;
        $quantity = $gioHang->soluong;

        // Tìm sự kiện quà tặng còn hiệu lực
        $promotion = DB::table('quatang_sukien as qs')
            ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
            ->where('qs.id_bienthe', $gioHang->id_bienthe)
            ->where('bt.luottang', '>', 0)
            ->where('qs.dieukien', '<=', $quantity)
            ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
            ->select('qs.dieukien', 'bt.luottang')
            ->first();

        if ($promotion) {
            $promotionCount = floor($quantity / $promotion->dieukien);
            $numFree = min($promotionCount, $promotion->luottang);
            $numToPay = $quantity - $numFree;

            // Cập nhật thanhtien
            $gioHang->thanhtien = $numToPay * $priceUnit;

            // Cập nhật luottang của biến thể
            $bienthe->decrement('luottang', $numFree);
        } else {
            // Không có ưu đãi, tính bình thường
            $gioHang->thanhtien = $quantity * $priceUnit;
        }
    }
}

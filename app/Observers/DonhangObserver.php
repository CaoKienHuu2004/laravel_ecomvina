<?php

namespace App\Observers;

use App\Models\DonhangModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 🧠 DonhangObserver
 *
 * Tự động xử lý logic sau khi đơn hàng cập nhật:
 * - Khi trạng thái chuyển sang 'Đã Giao Hàng' → trừ kho, tăng lượt mua.
 * - Khi trạng thái chuyển sang 'Đã Hủy Đơn' → hoàn kho, giảm lượt mua.
 *
 * ✅ Thay thế hoàn toàn trigger SQL AFTER UPDATE trên bảng `donhang`.
 */
class DonhangObserver
{
    /**
     * Sự kiện xảy ra sau khi đơn hàng được cập nhật.
     *
     * @param  \App\Models\DonhangModel  $donhang
     * @return void
     */
    public function updated(DonhangModel $donhang)
    {
        // Chỉ xử lý nếu cột 'trangthai' thực sự thay đổi
        if (!$donhang->isDirty('trangthai')) {
            return;
        }

        $trangThaiMoi = $donhang->trangthai;
        $trangThaiCu = $donhang->getOriginal('trangthai');

            //logic đơn hàng thành công
            // Nếu trạng thái thanh toán và trạng thái giao hàng đạt điều kiện
            if (
                $donhang->trangthaithanhtoan === 'Đã thanh toán' &&
                $trangThaiMoi === 'Đã giao hàng'
            ) {
                // Cập nhật trạng thái đơn hàng thành "Thành công"
                $donhang->trangthai = 'Thành công';
                $donhang->save();
                $trangThaiMoi = 'Thành công';
            }
            //logic đơn hàng thành công

        Log::info("🧩 DonhangObserver: Trạng thái thay đổi từ '{$trangThaiCu}' → '{$trangThaiMoi}' (ID đơn: {$donhang->id})");

        DB::transaction(function () use ($donhang, $trangThaiMoi) {
            $donhang->load('chitietdonhang.bienthe');

            foreach ($donhang->chitietdonhang as $ct) {
                $bienthe = $ct->bienthe;

                if (!$bienthe) {
                    continue;
                }

                // 🟢 Nếu đơn hàng giao thành công → trừ tồn kho, tăng lượt mua, giảm lượt tặng
                if ($trangThaiMoi === 'Thành công') {
                    $bienthe->decrement('soluong', $ct->soluong);
                    $bienthe->increment('luotban', $ct->soluong);
                    $bienthe->increment('luottang', $ct->soluong);
                }
                // if ($trangThaiMoi === 'Đã giao hàng') {
                //     $bienthe->decrement('soluong', $ct->soluong);
                //     $bienthe->increment('luotmua', $ct->soluong);
                //     $bienthe->increment('luottang', $ct->soluong);
                // }

                // 🔴 Nếu đơn hàng bị hủy → hoàn lại kho, giảm lượt mua (nếu đã từng giao)
                if ($trangThaiMoi === 'Đã hủy') {
                    $bienthe->increment('soluong', $ct->soluong);
                    $bienthe->decrement('luotban', $ct->soluong);
                    $bienthe->decrement('luottang', $ct->soluong);
                }

                // Cập nhật trạng thái chi tiết đơn hàng để đồng bộ
                $ct->update(['trangthai' => $trangThaiMoi]);
            }
        });
    }
}

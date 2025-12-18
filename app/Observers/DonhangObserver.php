<?php

namespace App\Observers;

use App\Models\DonhangModel;
use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;
// use CanhBaoTonKhoNotification;
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
                // if ($trangThaiMoi === 'Thành công') {
                //     $bienthe->decrement('soluong', $ct->soluong);
                //     $bienthe->increment('luotban', $ct->soluong);
                //     $bienthe->increment('luottang', $ct->soluong);
                // }
                if ($trangThaiMoi === 'Thành công') {

                    // Reload biến thể mới nhất để tránh race condition
                    $bienthe->refresh();

                    if ($bienthe->soluong < $ct->soluong) {
                        throw new \Exception('Số lượng tồn kho không đủ');
                    }

                    $bienthe->decrement('soluong', $ct->soluong);
                    $bienthe->increment('luotban', $ct->soluong);
                    $bienthe->increment('luottang', $ct->soluong);
                }
                // if ($trangThaiMoi === 'Đang chuẩn bị hàng' && $bienthe->soluong <= 5) {

                //     // Reload biến thể mới nhất để tránh race condition
                //     $admins = NguoidungModel::where('vaitro', 'admin')->get();

                //     foreach ($admins as $admin) {
                //         $admin->notify(new CanhBaoTonKhoNotification(
                //             $bienthe,
                //             $donhang
                //         ));
                //     }

                // }
                if ($trangThaiMoi === 'Đang chuẩn bị hàng') {

                    $bienthe->refresh(); // lấy số lượng mới nhất

                    if ($bienthe->soluong <= 5) {
                        $this->taoThongBaoCanhBaoTonKho($bienthe, $donhang);
                    }
                }
                // if ($trangThaiMoi === 'Đã giao hàng') {
                //     $bienthe->decrement('soluong', $ct->soluong);
                //     $bienthe->increment('luotmua', $ct->soluong);
                //     $bienthe->increment('luottang', $ct->soluong);
                // }

                // 🔴 Nếu đơn hàng bị hủy → hoàn lại kho, giảm lượt mua (nếu đã từng giao)
                if ($trangThaiMoi === 'Đã hủy') {
                    $bienthe->increment('soluong', $ct->soluong);
                    // $bienthe->decrement('luotban', $ct->soluong);
                    // $bienthe->decrement('luottang', $ct->soluong);
                }

                // Cập nhật trạng thái chi tiết đơn hàng để đồng bộ
                $ct->update(['trangthai' => $trangThaiMoi]);
            }
        });
    }

    /**
     * 🛎 TẠO THÔNG BÁO TỒN KHO
     */
    protected function taoThongBaoCanhBaoTonKho($bienthe, $donhang)
    {
        $admins = NguoidungModel::where('vaitro', 'admin')->get();

        foreach ($admins as $admin) {
            ThongbaoModel::create([
                'id_nguoidung' => $admin->id,
                'tieude'       => '⚠️ Cảnh báo tồn kho',
                'noidung'      =>
                    'Biến thể "' . $bienthe->sanpham->ten .
                    '" sắp hết hàng. Còn lại: ' . $bienthe->soluong,
                'lienket' => env('DOMAIN', 'http://148.230.100.215/'). 'donhang/show/' . $donhang->id,
                'loaithongbao' => 'Hệ thống',
                'trangthai'    => 'Chưa đọc',
            ]);
        }
    }
}

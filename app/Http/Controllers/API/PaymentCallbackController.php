<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ThanhToan;
use Illuminate\Http\Request;

class PaymentCallbackController extends  BaseController
{
    /**
     * VNPay trả về (returnUrl)
     */
    public function vnpayReturn(Request $request)
    {
        $orderId = $request->input('vnp_TxnRef');       // ID đơn hàng
        $magiaodich = $request->input('vnp_TransactionNo'); // Mã giao dịch VNPay
        $responseCode = $request->input('vnp_ResponseCode'); // 00 = thành công

        $status = $responseCode === "00" ? "thanh_cong" : "that_bai";

        $thanhToan = ThanhToan::where('id_donhang', $orderId)
            ->where('nganhang', 'VNPay')
            ->latest()
            ->first();

        if ($thanhToan) {
            $thanhToan->update([
                'magiaodich'   => $magiaodich,
                'trangthai'    => $status,
                'ngaythanhtoan'=> now(),
            ]);
        }

        return $this->jsonResponse([
            'message' => 'Cập nhật VNPay thành công',
            'status'  => $status
        ]);
    }

    /**
     * MoMo trả về (returnUrl)
     */
    public function momoReturn(Request $request)
    {
        $orderId = $request->input('orderId'); // ID đơn hàng
        $resultCode = $request->input('resultCode'); // 0 = thành công
        $transId = $request->input('transId'); // Mã giao dịch MoMo

        $status = $resultCode == 0 ? "thanh_cong" : "that_bai";

        $donhangId = explode('_', $orderId)[0]; // orderId có dạng idDonHang_timestamp

        $thanhToan = ThanhToan::where('id_donhang', $donhangId)
            ->where('nganhang', 'MoMo')
            ->latest()
            ->first();

        if ($thanhToan) {
            $thanhToan->update([
                'magiaodich'   => $transId,
                'trangthai'    => $status,
                'ngaythanhtoan'=> now(),
            ]);
        }

        return $this->jsonResponse([
            'message' => 'Cập nhật MoMo thành công',
            'status'  => $status
        ]);
    }

    /**
     * MoMo notifyUrl (server to server callback)
     */
    public function momoNotify(Request $request)
    {
        $orderId = $request->input('orderId');
        $resultCode = $request->input('resultCode');
        $transId = $request->input('transId');

        $status = $resultCode == 0 ? "thanh_cong" : "that_bai";
        $donhangId = explode('_', $orderId)[0];

        $thanhToan = ThanhToan::where('id_donhang', $donhangId)
            ->where('nganhang', 'MoMo')
            ->latest()
            ->first();

        if ($thanhToan) {
            $thanhToan->update([
                'magiaodich'   => $transId,
                'trangthai'    => $status,
                'ngaythanhtoan'=> now(),
            ]);
        }

        return $this->jsonResponse(['message' => 'MoMo notify handled', 'status' => $status]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\ThanhToan;
use App\Services\MomoService;
use App\Services\VnpayService;
use Illuminate\Http\Request;

class CheckOutController extends BaseController
{
    private $momoService;
    private $vnpayService;

    public function __construct(MomoService $momoService, VnpayService $vnpayService)
    {
        $this->momoService = $momoService;
        $this->vnpayService = $vnpayService;
    }

    // Tạo thanh toán VNPay
    public function vnpayCheckout(Request $request)
    {
        $donHangId = $request->input('id_donhang');
        $soTien = $request->input('gia');

        // Tạo bản ghi thanh toán (trạng thái mặc định: chờ xác nhận)
        $thanhToan = ThanhToan::create([
            'nganhang' => 'VNPay',
            'gia' => $soTien,
            'noidung' => 'Thanh toán đơn hàng ' . $donHangId,
            'magiaodich' => null,
            'ngaythanhtoan' => now(),
            'trangthai' => 'cho_xac_nhan',
            'id_donhang' => $donHangId,
        ]);

        // Gọi service để tạo URL thanh toán
        $paymentUrl = $this->vnpayService->createPayment($thanhToan);

        return $this->jsonResponse([
            'code' => '00',
            'message' => 'success',
            'data' => $paymentUrl
        ]);
    }

    // Tạo thanh toán MoMo
    public function momoCheckout(Request $request)
    {
        $donHangId = $request->input('id_donhang');
        $soTien = $request->input('gia');

        $thanhToan = ThanhToan::create([
            'nganhang' => 'MoMo',
            'gia' => $soTien,
            'noidung' => 'Thanh toán đơn hàng ' . $donHangId,
            'magiaodich' => null,
            'ngaythanhtoan' => now(),
            'trangthai' => 'cho_xac_nhan',
            'id_donhang' => $donHangId,
        ]);

        $paymentUrl = $this->momoService->createPayment($thanhToan);

        return $this->jsonResponse([
            'code' => '00',
            'message' => 'success',
            'data' => $paymentUrl
        ]);
    }


    /**
     * Callback cập nhật kết quả từ MoMo / VNPay
     */
    public function vnpayReturn(Request $request)
    {
        $magiaodich = $request->input('vnp_TransactionNo');
        $orderId = $request->input('vnp_TxnRef');
        $status = $request->input('vnp_ResponseCode') === "00" ? "thanh_cong" : "that_bai";

        $thanhToan = ThanhToan::where('id_donhang', $orderId)
            ->where('nganhang', 'VNPay')
            ->latest()
            ->first();

        if ($thanhToan) {
            $thanhToan->update([
                'magiaodich' => $magiaodich,
                'trangthai' => $status,
                'ngaythanhtoan' => now(),
            ]);
        }

        return $this->jsonResponse([
            'message' => 'Cập nhật kết quả thanh toán thành công',
            'status' => $status
        ]);
    }


}

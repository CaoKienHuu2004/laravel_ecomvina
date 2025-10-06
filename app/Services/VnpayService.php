<?php
// app/Services/VnpayService.php

namespace App\Services;

use App\Models\ThanhToan;

class VnpayService
{
    private $tmnCode;
    private $hashSecret;
    private $vnpUrl;
    private $returnUrl;

    public function __construct()
    {
        $this->tmnCode   = config('vnpay.tmn_code');
        $this->hashSecret = config('vnpay.hash_secret');
        $this->vnpUrl    = config('vnpay.url');
        $this->returnUrl = config('vnpay.return_url');
    }
    // Getter / Setter
    // public function getUrl() { return $this->vnp_Url; }
    // public function setUrl($url) { $this->vnp_Url = $url; }

    // public function getReturnUrl() { return $this->vnp_Returnurl; }
    // public function setReturnUrl($url) { $this->vnp_Returnurl = $url; }

    // public function getTmnCode() { return $this->vnp_TmnCode; }
    // public function setTmnCode($code) { $this->vnp_TmnCode = $code; }

    // public function getHashSecret() { return $this->vnp_HashSecret; }
    // public function setHashSecret($secret) { $this->vnp_HashSecret = $secret; }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPayment(ThanhToan $thanhToan): string
    {
        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $this->tmnCode,
            "vnp_Amount"     => $thanhToan->gia * 100, // VNPay yêu cầu nhân 100
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => request()->ip(),
            "vnp_Locale"     => "vn",
            "vnp_OrderInfo"  => $thanhToan->noidung,
            "vnp_OrderType"  => "billpayment",
            "vnp_ReturnUrl"  => $this->returnUrl,
            "vnp_TxnRef"     => $thanhToan->id_donhang,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashData = urldecode($query);
        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return $this->vnpUrl . "?" . $query . "&vnp_SecureHash=" . $secureHash;
    }
}

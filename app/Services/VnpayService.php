<?php

namespace App\Services;

class VnpayService
{
    private $vnp_Url;
    private $vnp_Returnurl;
    private $vnp_TmnCode;
    private $vnp_HashSecret;

    public function __construct()
    {
        $this->vnp_Url        = config('vnpay.url');
        $this->vnp_Returnurl  = config('vnpay.return_url');
        $this->vnp_TmnCode    = config('vnpay.tmn_code');
        $this->vnp_HashSecret = config('vnpay.hash_secret');
    }

    // Getter / Setter
    public function getUrl() { return $this->vnp_Url; }
    public function setUrl($url) { $this->vnp_Url = $url; }

    public function getReturnUrl() { return $this->vnp_Returnurl; }
    public function setReturnUrl($url) { $this->vnp_Returnurl = $url; }

    public function getTmnCode() { return $this->vnp_TmnCode; }
    public function setTmnCode($code) { $this->vnp_TmnCode = $code; }

    public function getHashSecret() { return $this->vnp_HashSecret; }
    public function setHashSecret($secret) { $this->vnp_HashSecret = $secret; }

    // Tạo URL thanh toán
    public function createPaymentUrl(array $data): string
    {
        $inputData = [
            "vnp_Version"   => "2.1.0",
            "vnp_TmnCode"   => $this->vnp_TmnCode,
            "vnp_Amount"    => $data['amount'] * 100,
            "vnp_Command"   => "pay",
            "vnp_CreateDate"=> date('YmdHis'),
            "vnp_CurrCode"  => "VND",
            "vnp_IpAddr"    => request()->ip(),
            "vnp_Locale"    => $data['locale'] ?? 'vn',
            "vnp_OrderInfo" => $data['order_desc'],
            "vnp_OrderType" => $data['order_type'] ?? 'billpayment',
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef"    => $data['order_id'],
        ];

        if (!empty($data['bank_code'])) {
            $inputData['vnp_BankCode'] = $data['bank_code'];
        }

        ksort($inputData);

        $hashData = urldecode(http_build_query($inputData));
        $query    = http_build_query($inputData);

        $vnp_Url = $this->vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        return $vnp_Url . '&vnp_SecureHash=' . $vnpSecureHash;
    }
}

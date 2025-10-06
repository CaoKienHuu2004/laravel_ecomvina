<?php
// app/Services/MomoService.php

namespace App\Services;

use App\Models\ThanhToan;

class MomoService
{
    private $endpoint;
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $returnUrl;
    private $notifyUrl;

    public function __construct()
    {
        $this->endpoint    = config('momo.endpoint');
        $this->partnerCode = config('momo.partner_code');
        $this->accessKey   = config('momo.access_key');
        $this->secretKey   = config('momo.secret_key');
        $this->returnUrl   = config('momo.return_url');
        $this->notifyUrl   = config('momo.notify_url');
    }

    /**
     * Tạo URL thanh toán MoMo
     */
    public function createPayment(ThanhToan $thanhToan): string
    {
        $orderId   = $thanhToan->id_donhang . "_" . time();
        $requestId = time() . "";
        $amount    = (string)$thanhToan->gia;
        $orderInfo = $thanhToan->noidung;
        $extraData = "";

        $rawHash = "partnerCode=" . $this->partnerCode .
                   "&accessKey=" . $this->accessKey .
                   "&requestId=" . $requestId .
                   "&amount=" . $amount .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&returnUrl=" . $this->returnUrl .
                   "&notifyUrl=" . $this->notifyUrl .
                   "&extraData=" . $extraData;

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'accessKey'   => $this->accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'returnUrl'   => $this->returnUrl,
            'notifyUrl'   => $this->notifyUrl,
            'extraData'   => $extraData,
            'requestType' => "captureWallet",
            'signature'   => $signature,
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);

        $jsonResult = json_decode($result, true);
        return $jsonResult['payUrl'] ?? '';
    }
}

<?php

namespace App\Services;

class MomoService
{
    private $endpoint;
    private $partnerCode;
    private $accessKey;
    private $secretKey;

    public function __construct()
    {
        $this->endpoint    = config('momo.endpoint');
        $this->partnerCode = config('momo.partner_code');
        $this->accessKey   = config('momo.access_key');
        $this->secretKey   = config('momo.secret_key');
    }

    // ==============================
    // Getter & Setter
    // ==============================
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function getPartnerCode(): string
    {
        return $this->partnerCode;
    }

    public function setPartnerCode(string $partnerCode): void
    {
        $this->partnerCode = $partnerCode;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function setAccessKey(string $accessKey): void
    {
        $this->accessKey = $accessKey;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    // ==============================
    // Nội dung cũ
    // ==============================
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function createPayment(array $data): array
    {
        $orderId     = $data['order_id'] ?? time();
        $orderInfo   = $data['order_desc'] ?? "Thanh toán qua MoMo";
        $amount      = $data['amount'] ?? "10000";
        $returnUrl   = $data['return_url'] ?? "http://localhost:8000/atm/result_atm.php";
        $notifyUrl   = $data['notify_url'] ?? "http://localhost:8000/atm/ipn_momo.php";
        $requestId   = time() . "";
        $requestType = "payWithMoMoATM";
        $extraData   = "";
        $bankCode    = $data['bank_code'] ?? "SML";

        $rawHash = "partnerCode={$this->partnerCode}&accessKey={$this->accessKey}&requestId={$requestId}"
            ."&bankCode={$bankCode}&amount={$amount}&orderId={$orderId}&orderInfo={$orderInfo}"
            ."&returnUrl={$returnUrl}&notifyUrl={$notifyUrl}&extraData={$extraData}&requestType={$requestType}";

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $payload = [
            'partnerCode' => $this->partnerCode,
            'accessKey'   => $this->accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'returnUrl'   => $returnUrl,
            'bankCode'    => $bankCode,
            'notifyUrl'   => $notifyUrl,
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature
        ];

        $result = $this->execPostRequest($this->endpoint, json_encode($payload));
        return json_decode($result, true);
    }
}

<?php
return [
    'url'         => "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html",
    'return_url'  => "https://localhost/vnpay_php/vnpay_return.php",
    'tmn_code'    => env('VNPAY_TMN_CODE'),
    'hash_secret' => env('VNPAY_HASH_SECRET'),
];

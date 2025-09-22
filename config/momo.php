<?php
return [
    'endpoint'     => "https://test-payment.momo.vn/gw_payment/transactionProcessor",
    'partner_code' => env('MOMO_PARTNER_CODE'),
    'access_key'   => env('MOMO_ACCESS_KEY'),
    'secret_key'   => env('MOMO_SECRET_KEY'),
];

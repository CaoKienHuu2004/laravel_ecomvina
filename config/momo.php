<?php
return [
    'endpoint'     => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/gw_payment/transactionProcessor'),
    'partner_code' => env('MOMO_PARTNER_CODE'),
    'access_key'   => env('MOMO_ACCESS_KEY'),
    'secret_key'   => env('MOMO_SECRET_KEY'),
    'return_url'   => env('MOMO_RETURN_URL', 'http://localhost/momo_return'),
    'notify_url'   => env('MOMO_NOTIFY_URL', 'http://localhost/momo_notify'),
];

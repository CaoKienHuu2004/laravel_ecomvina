<?php
// //config/vietqr
// $payload = [
//     'accountNo'   => '123456789',
//     'accountName' => 'CONG TY ABC',
//     'acqId'       => 970415, // Mã ngân hàng Vietinbank, tùy ngân hàng của bạn
//     'addInfo'     => 'THANH TOAN DON HANG ' . $donhang->madon,
//     'amount'      => $donhang->thanhtien,
//     'template'    => 'compact' // hoặc qr_only
// ];


// return [
//     'accountNo'   => '113366668888', //edit
//     'accountName' => 'QUY VAC XIN PHONG CHONG COVID', //edit
//     'acqId'       => 970415,
//     // 'template'    => 'compact',
//     'template'    => 'compact2', //edit
// ];

return [
    'accountNo'   => '113366668888', //edit
    'accountName' => 'QUY VAC XIN PHONG CHONG COVID', //edit
    'acqId'       => 970415,
    "addInfo" => "Ung Ho Quy Vac Xin", //new
    "amount" => "79000", // new
    // 'template'    => 'compact',
    'template'    => 'compact2', //edit
];

// --data-raw '{
//     //     "accountNo": "113366668888",
//     //     "accountName": "QUY VAC XIN PHONG CHONG COVID",
//     //     "acqId": "970415",
//     //     "addInfo": "Ung Ho Quy Vac Xin",
//     //     "amount": "79000",
//     //     "template": "compact"
//     // }'


// curl --location --request POST 'https://api.vietqr.io/v2/generate' \
    // --header 'x-client-id: <CLIENT_ID_HERE>' \
    // --header 'x-api-key: <API_KEY_HERE>' \
    // --header 'Content-Type: application/json' \
    // --data-raw '{
    //     "accountNo": "113366668888",
    //     "accountName": "QUY VAC XIN PHONG CHONG COVID",
    //     "acqId": "970415",
    //     "addInfo": "Ung Ho Quy Vac Xin",
    //     "amount": "79000",
    //     "template": "compact"
    // }'
    // in config/vietqr.php

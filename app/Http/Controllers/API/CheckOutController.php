<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\VnpayService;
use App\Services\MomoService;
use Illuminate\Http\Request;

class CheckOutController extends BaseController
{

    private $vnpayService;
    private $momoService;

    public function __construct(VnpayService $vnpayService, MomoService $momoService)
    {
        $this->vnpayService = $vnpayService;
        $this->momoService  = $momoService;
    }

    public function vnpayPayment(Request $request)
    {
        $url = $this->vnpayService->createPaymentUrl([
            'amount'     => $request->input('total_vnpay'),
            'order_id'   => rand(1000,9999),
            'order_desc' => "Thanh toán đơn hàng test",
            'locale'     => 'vn',
            'bank_code'  => 'NCB'
        ]);

        return $this->jsonResponse([
            'code'    => '00',
            'message' => 'success',
            'data'    => $url
        ]);
    }

    public function momoPayment(Request $request)
    {
        $result = $this->momoService->createPayment([
            'amount'     => $request->input('amount', 10000),
            'order_id'   => time(),
            'order_desc' => "Thanh toán qua MoMo",
        ]);

        return $this->jsonResponse($result);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

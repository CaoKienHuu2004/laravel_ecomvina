<?php

namespace App\Http\Controllers\API\Frontend;


use App\Http\Controllers\Controller;
use App\Models\TrangNoiDungModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TrangGioiThieu extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $data = TrangNoiDungModel::where('tieude', 'Trang giới thiệu sieuthivina')
                    ->orderBy('id', 'desc')
                    ->get();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách các selection và html của trang giới thiệu',
            'data'    => $data,
        ], Response::HTTP_OK);
    }
}

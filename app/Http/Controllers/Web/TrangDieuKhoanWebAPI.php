<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\TrangDieuKhoanResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\TrangNoiDungModel;


class TrangDieuKhoanWebAPI extends Controller
{
    //
    public function index(Request $request)
    {
        $data = TrangNoiDungModel::where('tieude', 'Trang điều khoản sử dụng')
                    ->orderBy('id', 'desc')
                    ->get();
        TrangDieuKhoanResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(TrangDieuKhoanResource::collection($data), Response::HTTP_OK);

        // return $this->jsonResponse([
        //     'status'  => true,
        //     'message' => 'Danh sách các selection và html của trang điều khoản',
        //     'data'    => $data,
        // ], Response::HTTP_OK);


    }
}

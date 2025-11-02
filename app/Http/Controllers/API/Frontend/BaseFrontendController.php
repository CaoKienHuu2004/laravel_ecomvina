<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DanhgiaModel;
use App\Models\ThuongHieuModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BaseFrontendController extends Controller
{
    use ApiResponse;

    /**
     * $user = $this->authUser($req); khi cần dùng
     */
    protected function authUser(Request $req)
    {
        return $req->get('auth_user');
    }

    protected function getMenuFilterAside()
    {
        $danhmucs = DanhgiaModel::withCount('sanpham as tong_sanpham')->get(['id', 'ten','slug']);
        $thuonghieus = ThuongHieuModel::has('sanpham')->get(['id', 'ten','slug']);
        $priceRanges = [
            ['label' => 'Dưới 100.000đ', 'min' => 0, 'max' => 100000,'value' => 'low100'],
            ['label' => '100.000đ - 200.000đ', 'min' => 100000, 'max' => 200000,'value' => 'to200'],
            ['label' => '200.000đ - 300.000đ', 'min' => 200000, 'max' => 300000,'value' => 'to300'],
            ['label' => '300.000đ - 500.000đ', 'min' => 300000, 'max' => 500000,'value' => 'to500'],
            ['label' => '500.000đ - 700.000đ', 'min' => 500000, 'max' => 700000,'value' => 'to700'],
            ['label' => '700.000đ - 1.000.000đ', 'min' => 700000, 'max' => 1000000,'value' => 'to1000'],
            ['label' => 'Trên 1.000.000đ', 'min' => 1000000, 'max' => null,'value' => 'high1000'],
        ];

        return ([
            'danhmucs' => $danhmucs,
            'price_ranges' => $priceRanges,
            'thuonghieus' => $thuonghieus,
        ]);
    }



}

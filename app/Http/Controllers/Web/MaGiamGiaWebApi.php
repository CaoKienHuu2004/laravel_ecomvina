<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\MaGiamGiaResource;
use App\Models\MagiamgiaModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\GiohangModel;
use Illuminate\Support\Facades\Redis;

class MaGiamGiaWebApi extends Controller
{
    //
    public function index(Request $request)
    {
        // $perPage = (int) $request->get('per_page', 10);
        // $q       = $request->get('q');

        // $items = MagiamgiaModel::orderBy('id', 'desc')
        //     ->when($q, function ($query) use ($q) {
        //         $query->where(function ($sub) use ($q) {
        //             $sub->where('magiamgia', 'like', "%{$q}%")
        //                 ->orWhere('mota', 'like', "%{$q}%")
        //                 ->orWhere('trangthai', 'like', "%{$q}%");
        //         });
        //     })
        //     ->simplePaginate($perPage);
        $listConpont = $this->kiemTraDieuKienMaGiamGia($request);

        MaGiamGiaResource::withoutWrapping();

        return response()->json(
            MaGiamGiaResource::collection($listConpont),
            Response::HTTP_OK
        );
    }
    public function show($id)
    {
        $item = MagiamgiaModel::find($id);
        MaGiamGiaResource::withoutWrapping();
        return response()->json(
            new MaGiamGiaResource($item),
            Response::HTTP_OK
        );
    }
    public function kiemTraDieuKienMaGiamGia($request)
    {
        // -----------------------------------------
        //   XÁC ĐỊNH NGƯỜI DÙNG: TOKEN hoặc SESSION
        // -----------------------------------------
        $token = $request->bearerToken();
        $cartTotal = 0;


        if ($token) {
            /** USER ĐÃ ĐĂNG NHẬP → kích hoạt token */
            $redisKey = "api_token:$token";
            $userId = Redis::get($redisKey);

            if ($userId) {
                // lấy tổng tiền giỏ hàng từ DB
                $cartTotal = GiohangModel::where('id_nguoidung', $userId)
                    ->sum('thanhtien');
            }

        }
        else {
            /** USER KHÔNG ĐĂNG NHẬP → giỏ hàng session */
            $cart_session = config('cart_session.session_key_cart', 'cart_session');
            $sessionCart = $request->session()->get($cart_session, []);

            // tính tổng tiền session
            $cartTotal = collect($sessionCart)
                ->where('thanhtien', '>', 0)     // loại bỏ quà tặng (thanhtien = 0)
                ->sum('thanhtien');
        } // chỉ dùng cho routes webapi

        // -----------------------------------------
        //   XÁC ĐỊNH NGƯỜI DÙNG: IP cho mã giảm giá id 2 theo mô tả của nó  NEWSTORE50K
        // -----------------------------------------
        $ip = $request->getClientIp();
        $redisIpKey = "used_voucher_ip:$ip";

        // -----------------------------------------
        //   diều kiện
        // -----------------------------------------
        $magiamgiaList = MagiamgiaModel::all();
        $result = [];

        $ipExists = Redis::exists($redisIpKey);

        if (!$ipExists) {
            $voucher2 = MagiamgiaModel::find(2);

            if ($voucher2) {
                $result[] = $voucher2;
            }
        }

        foreach ($magiamgiaList as $magiamgia) {

            if ($magiamgia->id == 2) {
                continue;
            }

            // kiểm tra dieukien là số
            if (!is_numeric($magiamgia->dieukien)) {
                continue; // bỏ qua nếu không phải số
            }

            // kiểm tra điều kiện: tổng giỏ hàng >= dieukien
            if ($cartTotal >= $magiamgia->dieukien) {
                $result[] = $magiamgia; // thêm record vào danh sách kết quả
            }
        }

        return $result;

    }
}

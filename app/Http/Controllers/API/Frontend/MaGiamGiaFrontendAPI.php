<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\MaGiamGiaResource;
use App\Models\MagiamgiaModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\GiohangModel;
use Illuminate\Support\Facades\Redis;

/**
 * @OA\Tag(
 *     name="Mã giảm giá",
 *     description="Hiện thị tất các mã giảm giá đang có trên hệ thống"
 * )
 */
class MaGiamGiaFrontendAPI extends Controller
{
    use ApiResponse;
    /**
     * @OA\Get(
     *     path="/api/ma-giam-gia",
     *     tags={"Mã giảm giá"},
     *     summary="Lấy danh sách mã giảm giá mà người dùng đủ điều kiện sử dụng",
     *     description="
     * - API sẽ tự xác định người dùng qua Bearer Token (nếu có).
     * - Nếu user đã đăng nhập → tính tổng tiền giỏ hàng từ database.
     * - Sau đó lọc các mã giảm giá có trường 'dieukien' là số và tổng giỏ hàng >= dieukien.
     * - Trả về danh sách các mã mà người dùng đủ điều kiện sử dụng.
     * ",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách mã giảm giá phù hợp",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách mã giảm giá"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/MaGiamGia")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Token không hợp lệ hoặc không xác định được người dùng"
     *     )
     * )
     */
    public function index(Request $request)
    {
        // $perPage = (int) $request->get('per_page', 10);
        // $q = $request->get('q');

        // $query = MagiamgiaModel::orderBy('id', 'desc');

        // if ($q) {
        //     $query->where(function ($sub) use ($q) {
        //         $sub->where('magiamgia', 'like', "%{$q}%")
        //             ->orWhere('mota', 'like', "%{$q}%")
        //             ->orWhere('trangthai', 'like', "%{$q}%");
        //     });
        // }

        // $items = $query->paginate($perPage);

        $listVocher = $this->kiemTraDieuKienMaGiamGia($request);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách mã giảm giá',
            'data' => MaGiamGiaResource::collection($listVocher), // truyền nguyên paginator
            // 'pagination' => [
            //     'total' => $items->total(),
            //     'per_page' => $items->perPage(),
            //     'current_page' => $items->currentPage(),
            //     'last_page' => $items->lastPage(),
            //     'from' => $items->firstItem(),
            //     'to' => $items->lastItem(),
            // ],
        ], Response::HTTP_OK);
    }


    public function show($id)
    {
        $item = MagiamgiaModel::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Mã giảm giá không tồn tại',
            ], Response::HTTP_NOT_FOUND);
        }

        MaGiamGiaResource::withoutWrapping();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết mã giảm giá',
            'data' => new MaGiamGiaResource($item),
        ], Response::HTTP_OK);
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
        // else {
        //     /** USER KHÔNG ĐĂNG NHẬP → giỏ hàng session */
        //     $cart_session = config('cart_session.session_key_cart', 'cart_session');
        //     $sessionCart = $request->session()->get($cart_session, []);

        //     // tính tổng tiền session
        //     $cartTotal = collect($sessionCart)
        //         ->where('thanhtien', '>', 0)     // loại bỏ quà tặng (thanhtien = 0)
        //         ->sum('thanhtien');
        // } // chỉ dùng cho routes webapi

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

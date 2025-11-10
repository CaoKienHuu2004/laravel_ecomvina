<?php

namespace App\Http\Controllers\API\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\Frontend\TinhThanhVietNamResource;


/**
 * @OA\Tag(
 *     name="Tỉnh Thành Việt Nam",
 *     description="Hiển thị danh sách tỉnh/thành Việt Nam"
 * )
 */
class TinhThanhVietNamFrontendAPI extends BaseFrontendController
{
    protected $provinces;
    public function __construct()
    {
        $this->provinces = config('tinhthanh');
    }
    /**
     * @OA\Get(
     *     path="/api/tinh-thanh",
     *     summary="Lấy danh sách tất cả các tỉnh/thành Việt Nam, dùng cho selectbox địa chỉ giao hàng",
     *     description="Trả về danh sách các tỉnh/thành, có thể lọc theo khu vực (khuvuc) bằng query param",
     *     tags={"Tỉnh Thành Việt Nam"},
     *     @OA\Parameter(
     *         name="khuvuc",
     *         in="query",
     *         description="Tên khu vực để lọc, ví dụ: Đông Nam Bộ",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách tỉnh/thành được trả về thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh Sách Tỉnh/Thành Phố Việt Nam"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/TinhThanhVietNam")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Cho phép lọc theo vùng (khuvuc) nếu có query param ?khuvuc=Đông Nam Bộ
        $khuvuc = $request->query('khuvuc');

        $provinces = $this->provinces;

        if ($khuvuc) {
            $provinces = array_filter($provinces, function ($province) use ($khuvuc) {
                return stripos($province['khuvuc'], $khuvuc) !== false;
            });
        }
        $resoult =array_values($provinces);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh Sách Tỉnh/Thành Phố Việt Nam',
            'data' => TinhThanhVietNamResource::collection($resoult),
        ], Response::HTTP_OK);
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

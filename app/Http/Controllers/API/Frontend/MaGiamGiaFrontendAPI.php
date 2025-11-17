<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\MaGiamGiaResource;
use App\Models\MagiamgiaModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     *     summary="Lấy danh sách mã giảm giá có hỗ trợ tìm kiếm và phân trang",
     *     description="Trả về danh sách các mã giảm giá, hỗ trợ tìm kiếm theo mã giảm giá, mô tả hoặc trạng thái, kèm phân trang.",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Từ khóa tìm kiếm (theo mã giảm giá, mô tả hoặc trạng thái)",
     *         @OA\Schema(type="string", example="SALE50")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Số trang cần lấy",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng bản ghi trên mỗi trang",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách mã giảm giá kèm phân trang",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách mã giảm giá"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/MaGiamGia")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="to", type="integer", example=10)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');

        $query = MagiamgiaModel::orderBy('id', 'desc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('magiamgia', 'like', "%{$q}%")
                    ->orWhere('mota', 'like', "%{$q}%")
                    ->orWhere('trangthai', 'like', "%{$q}%");
            });
        }

        $items = $query->paginate($perPage);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách mã giảm giá',
            'data' => MaGiamGiaResource::collection($items), // truyền nguyên paginator
            'pagination' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
            ],
        ], Response::HTTP_OK);
    }


    /**
     * @OA\Get(
     *     path="/api/ma-giam-gia/{id}",
     *     tags={"Mã giảm giá"},
     *     summary="Lấy chi tiết mã giảm giá theo ID",
     *     description="Trả về thông tin chi tiết của một mã giảm giá dựa trên ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của mã giảm giá cần lấy",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin chi tiết mã giảm giá",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chi tiết mã giảm giá"),
     *             @OA\Property(property="data", ref="#/components/schemas/MaGiamGia")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy mã giảm giá",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Mã giảm giá không tồn tại")
     *         )
     *     )
     * )
     */
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
}

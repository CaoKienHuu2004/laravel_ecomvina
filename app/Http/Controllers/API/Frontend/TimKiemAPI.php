<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\HotSaleResource;
use Illuminate\Http\Request;
use App\Models\SanphamModel; // Model sản phẩm
/**
 * @OA\Schema(
 *     schema="HotSaleResource",
 *     type="object",
 *     title="Hot Sale Sản phẩm",
 *     description="Thông tin chi tiết của một sản phẩm trong danh sách tìm kiếm hoặc hot sale",
 *     @OA\Property(property="id", type="integer", example=15),
 *     @OA\Property(property="ten", type="string", example="Laptop Dell Inspiron 15"),
 *     @OA\Property(property="avg_rating", type="number", format="float", example=4.5),
 *     @OA\Property(property="review_count", type="integer", example=128),
 *     @OA\Property(property="total_sold", type="integer", example=560),
 *     @OA\Property(
 *         property="hinhanhsanpham",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=3),
 *             @OA\Property(property="url", type="string", example="https://example.com/images/sp15-1.jpg")
 *         )
 *     ),
 *     @OA\Property(
 *         property="thuonghieu",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=2),
 *         @OA\Property(property="ten", type="string", example="Dell")
 *     ),
 *     @OA\Property(
 *         property="danhmuc",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=4),
 *         @OA\Property(property="ten", type="string", example="Laptop Văn Phòng")
 *     )
 * )
 */
class TimKiemAPI extends BaseFrontendController
{
    /**
     * @OA\Get(
     *     path="/api/tim-kiem",
     *     tags={"Tìm kiếm sản phẩm"},
     *     summary="Tìm kiếm sản phẩm theo tên hoặc danh mục",
     *     description="
     *     ✅ API tìm kiếm sản phẩm cho trang Tìm Kiếm.
     *     - Hỗ trợ tìm theo **tên sản phẩm** hoặc **tên danh mục**.
     *     - Kết quả bao gồm: hình ảnh, thương hiệu, danh mục, đánh giá, biến thể và tổng lượt bán.
     *     - Có phân trang và thông tin tổng số sản phẩm.
     *     ",
     *
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         description="Từ khóa cần tìm kiếm (theo tên sản phẩm hoặc danh mục)",
     *         @OA\Schema(type="string", example="Laptop Dell")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số sản phẩm trên mỗi trang (mặc định 10)",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm tìm thấy",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Kết quả tìm kiếm thành công"),
     *             @OA\Property(property="filters", type="object",
     *                 description="Các bộ lọc bên trái (danh mục, thương hiệu, khoảng giá)",
     *                 @OA\Property(property="danhmucs", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Bánh kẹo"),
     *                         @OA\Property(property="slug", type="string", example="banh-keo"),
     *                         @OA\Property(property="tong_sanpham", type="integer", example=50)
     *                     )
     *                 ),
     *                 @OA\Property(property="thuonghieus", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Oreo"),
     *                         @OA\Property(property="slug", type="string", example="oreo")
     *                     )
     *                 ),
     *                 @OA\Property(property="price_ranges", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="label", type="string", example="100.000đ - 200.000đ"),
     *                         @OA\Property(property="min", type="integer", example=100000),
     *                         @OA\Property(property="max", type="integer", example=200000),
     *                         @OA\Property(property="value", type="string", example="to200"),
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/HotSaleResource")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 description="Thông tin phân trang",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=25)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Thiếu tham số query",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tham số query không được để trống")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm nào phù hợp",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không có sản phẩm nào khớp với từ khóa 'Điện thoại'")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $keyword = $request->query('query'); // 🔍 Lấy từ khóa tìm kiếm
        $perPage = $request->get('per_page', 10);

        // ⚠️ Nếu không có query
        if (!$keyword) {
            return response()->json([
                'status' => false,
                'message' => 'Tham số query không được để trống'
            ], 400);
        }

        $productsQuery = SanphamModel::with([
                'hinhanhsanpham',
                'thuonghieu',
                'danhgia',
                'danhmuc',
                'bienthe',
                'loaibienthe',
            ])
            ->withAvg('danhgia as avg_rating', 'diem')
            ->withCount('danhgia as review_count')
            ->withSum('bienthe as total_sold', 'luotban')
            ->where(function ($q) use ($keyword) {
                $q->where('ten', 'like', '%' . $keyword . '%')
                ->orWhereHas('danhmuc', function ($q2) use ($keyword) {
                    $q2->where('ten', 'like', '%' . $keyword . '%');
                });
            })
            ->orderByRaw('COALESCE((SELECT giagoc FROM bienthe WHERE id_sanpham = sanpham.id ORDER BY giagoc DESC LIMIT 1), 0) DESC')
            ->orderByDesc('total_sold');

        $products = $productsQuery->paginate($perPage);

        // ⚠️ Nếu không có sản phẩm nào
        if ($products->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Không có sản phẩm nào khớp với từ khóa "' . $keyword . '"'
            ], 404);
        }

        // ✅ Nếu có sản phẩm → trả kèm bộ lọc
        $filterAside = $this->getMenuFilterAside();

        return response()->json([
            'status' => true,
            'message' => 'Kết quả tìm kiếm thành công',
            'filters' => $filterAside,
            'data' => HotSaleResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ]);
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

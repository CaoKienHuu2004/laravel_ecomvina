<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPhamModel;
use App\Models\DanhMucModel;
use App\Models\LoaibientheModel;
use App\Models\ThuongHieuModel;
use App\Models\MauSacModel;

use App\Http\Resources\Frontend\SanPhamAllDetailResources;
use App\Http\Resources\Frontend\SanPhamAllResources;

class TrangShopFrontendAPI extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/trang-shop",
     *     tags={"Trang Shop"},
     *     summary="Lấy toàn bộ dữ liệu cho trang shop (sản phẩm, danh mục, thương hiệu, màu sắc, bộ lọc...)",
     *     description="API này trả về toàn bộ dữ liệu cần thiết để hiển thị trang shop, bao gồm danh sách sản phẩm, danh mục cha, thương hiệu, màu sắc và các bộ lọc giá.",
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Trang hiện tại (dùng cho phân trang sản phẩm)",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng sản phẩm mỗi trang (mặc định 20)",
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Từ khóa tìm kiếm sản phẩm",
     *         @OA\Schema(type="string", example="iphone")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lấy dữ liệu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     description="Danh sách sản phẩm hiển thị",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="iPhone 15 Pro Max"),
     *                         @OA\Property(property="slug", type="string", example="iphone-15-pro-max"),
     *                         @OA\Property(property="giagoc", type="number", example=32990000),
     *                         @OA\Property(property="giaban", type="number", example=29990000),
     *                         @OA\Property(property="avg_rating", type="number", example=4.8),
     *                         @OA\Property(property="review_count", type="integer", example=150),
     *                         @OA\Property(property="luotxem", type="integer", example=1, description="vì detail có tự động +1 khi gọi tới show nên thêm vào"),
     *                         @OA\Property(property="total_sold", type="integer", example=350),
     *                         @OA\Property(property="total_quantity", type="integer", example=50),
     *                         @OA\Property(property="thuonghieu", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="ten", type="string", example="Apple")
     *                         ),
     *                         @OA\Property(property="hinhanhsanpham", type="array",
     *                             @OA\Items(
     *                                 @OA\Property(property="url", type="string", example="iphone15.jpg")
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                     description="Danh mục cấp cha",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Điện thoại"),
     *                         @OA\Property(property="sanpham_count", type="integer", example=12)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="brands",
     *                     type="array",
     *                     description="Danh sách thương hiệu",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Samsung")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="colors",
     *                     type="array",
     *                     description="Danh sách màu sắc (hoặc loại biến thể)",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Màu đen")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="filters",
     *                     type="object",
     *                     @OA\Property(
     *                         property="price",
     *                         type="array",
     *                         description="Các mức giá lọc sản phẩm",
     *                         @OA\Items(
     *                             @OA\Property(property="label", type="string", example="0 vnđ - 625.000 vnđ"),
     *                             @OA\Property(property="min", type="number", example=0),
     *                             @OA\Property(property="max", type="number", example=625000)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Lỗi truy vấn hoặc tham số không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Bad Request: Lỗi không xác định")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            // --- 1️⃣ Danh sách sản phẩm (phân trang nếu cần) ---
            $perPage     = $request->get('per_page', 20);
            $currentPage = $request->get('page', 1);
            $q           = $request->get('q'); // từ khóa tìm kiếm

            $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe'])
                ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng bán
                ->withSum('bienthe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
                ->withAvg('danhgia as avg_rating', 'diem') // điểm
                ->withCount('danhgia as review_count') // số lượng đánh giá
                ->when($q, function ($query) use ($q) {
                    $query->where(function ($sub) use ($q) {
                        $sub->where('ten', 'like', "%$q%")
                            ->orWhere('mota', 'like', "%$q%");
                    });
                })
                ->with(['bienthe' => function ($q) {
                    $q->orderByDesc('giagoc')->limit(1);
                }]);

            $products = $query->latest('updated_at')->paginate($perPage, ['*'], 'page', $currentPage);

            // --- 2️⃣ Danh mục sản phẩm ---
            $categories = DanhMucModel::select('id', 'ten')
                ->withCount('sanpham')
                ->whereNull('parent')
                ->get();

            // --- 3️⃣ Thương hiệu ---
            $brands = ThuongHieuModel::select('id', 'ten')->get();

            // --- 4️⃣ Màu sắc ---
            $colors = LoaibientheModel::select('id', 'ten')->get();

            // --- 5️⃣ Bộ lọc giá (có thể định nghĩa sẵn) ---
            $priceFilters = [
                ['label' => '0 vnđ - 625.000 vnđ', 'min' => 0, 'max' => 625000],
                ['label' => '625.000 vnđ - 1.250.000 vnđ', 'min' => 625000, 'max' => 1250000],
                ['label' => '1.250.000 vnđ - 2.500.000 vnđ', 'min' => 1250000, 'max' => 2500000],
                ['label' => '2.500.000 vnđ - 5.000.000 vnđ', 'min' => 2500000, 'max' => 5000000],
            ];

            // --- 6️⃣ Trả về dữ liệu tổng hợp ---

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => SanPhamAllResources::collection($products),
                    'categories' => $categories,
                    'brands' => $brands,
                    'colors' => $colors,
                    'filters' => [
                        'price' => $priceFilters
                    ],
                ]
            ], 200);

        } catch (\Exception $e) {
            // Trả về lỗi 400 nếu có exception
            return response()->json([
                'success' => false,
                'message' => 'Bad Request: ' . $e->getMessage()
            ], 400);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/trang-shop/{id}",
     *     tags={"Trang Shop"},
     *     summary="Xem chi tiết một sản phẩm theo ID, và tự động lượt xem lên 1",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm cần xem chi tiết",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy dữ liệu sản phẩm chi tiết thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Lỗi truy vấn hoặc ID không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            // 🔹 Tìm sản phẩm theo ID kèm theo các mối quan hệ liên quan
            $query = SanphamModel::with([
                    'hinhanhsanpham',
                    'thuonghieu',
                    'danhgia',
                    'danhmuc',
                    'bienthe',
                    'loaibienthe',
                    'danhgia.nguoidung'
                ])
                ->withSum('chitietdonhang as total_sold', 'soluong') // Tổng số lượng đã bán
                ->withSum('bienthe as total_quantity', 'soluong')    // Tổng tồn kho
                ->withAvg('danhgia as avg_rating', 'diem')           // Điểm đánh giá trung bình
                ->withCount('danhgia as review_count')               // Tổng số đánh giá
                ->with(['bienthe' => function ($q) {
                    $q->orderByDesc('giagoc')->limit(1);             // Biến thể có giá cao nhất
                }])
                ->findOrFail($id); // Nếu không tìm thấy → tự động 404

            // 🔹 Tăng lượt xem lên 1 mỗi khi người dùng truy cập chi tiết sản phẩm
            $query->increment('luotxem');

            // 🔹 Trả dữ liệu chi tiết về cho client
            return response()->json([
                'success' => true,
                'data' => new SanPhamAllDetailResources($query)
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Không tìm thấy sản phẩm
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm với ID = ' . $id,
            ], 404);

        } catch (\Exception $e) {
            // Các lỗi khác (SQL, query, ...)
            return response()->json([
                'success' => false,
                'message' => 'Bad Request: ' . $e->getMessage(),
            ], 400);
        }
    }


}

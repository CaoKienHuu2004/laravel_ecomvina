<?php



namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\API\SanphamAPI;
use App\Http\Resources\Frontend\BestProductResource;
use App\Http\Resources\Frontend\BrandsHotResource;
use Illuminate\Http\Request;
use App\Http\Resources\Frontend\SanphamResources;
use App\Models\Sanpham;
use App\Models\Danhmuc;
use App\Models\Thuonghieu;
use Illuminate\Http\Response;
use App\Http\Resources\Frontend\CategoriesHotResource;
use App\Http\Resources\Frontend\HotSaleResource;
use App\Http\Resources\Frontend\RecommentResource;
use App\Models\DanhgiaModel;
use App\Models\DanhmucModel;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;
use Illuminate\Support\Facades\DB;




/**
 * @OA\Tag(
 *     name="Sản phẩm (trang-chu)",
 *     description=" sản phẩm của trang chủ được lọc theo yêu cầu nghiệp vụ của từng selection, tên cũ sanphams-selection"
 * )
 */
class SanPhamFrontendAPI extends SanphamAPI
{
    /**
     * @OA\Get(
     *     path="/api/sanphams-selection",
     *     summary="Danh sách sản phẩm trang chủ",
     *     description="Trả về tất cả nhóm sản phẩm bao gồm: hot_sales, top_categories, top_brands, best_products, recommend và default.",
     *     tags={"Sản phẩm (trang-chu)"},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm trang chủ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách sản phẩm trang chủ"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="hot_sales",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                 @OA\Property(
     *                     property="top_categories",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                 @OA\Property(
     *                     property="top_brands",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                 @OA\Property(
     *                     property="best_products",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                 @OA\Property(
     *                     property="recommend",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                 @OA\Property(
     *                     property="default",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="SanphamItem",
     *     type="object",
     *     title="Sản phẩm",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="ten", type="string", example="Điện thoại iPhone 15 Pro Max"),
     *     @OA\Property(property="slug", type="string", example="iphone-15-pro-max"),
     *     @OA\Property(property="hinh_anh", type="string", nullable=true, example="https://example.com/images/iphone15.jpg"),
     *     @OA\Property(
     *         property="gia",
     *         type="object",
     *         @OA\Property(property="current", type="number", format="float", example=27990000),
     *         @OA\Property(property="before_discount", type="number", format="float", example=30990000),
     *         @OA\Property(property="discount_percent", type="integer", example=10)
     *     ),
     *     @OA\Property(
     *         property="store",
     *         type="object",
     *         @OA\Property(property="name", type="string", example="Apple Store"),
     *         @OA\Property(property="icon_url", type="string", nullable=true, example="https://example.com/store-logo.png")
     *     ),
     *     @OA\Property(
     *         property="rating",
     *         type="object",
     *         @OA\Property(property="average", type="number", format="float", example=4.8),
     *         @OA\Property(property="count", type="integer", example=128)
     *     )
     * )
     */
    public function index(Request $request)
    {
        $data = [
            'hot_sales'      => $this->getHotSales($request),
            'top_categories' => $this->getTopCategories($request),
            'top_brands'     => $this->getTopBrands($request),
            'best_products'  => $this->getBestProducts($request),
            'recommend'      => $this->getRecommend($request, $request->get('danhmuc_id')),
            'default'        => $this->getDefaultProducts($request),
        ];

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách sản phẩm trang chủ',
            'data'    => $data,
        ], Response::HTTP_OK);
    }




    /**
     * @OA\Get(
     *     path="/api/sanphams-selection?selection=hot_sales",
     *     tags={"Sản phẩm (trang-chu)"},
     *     summary="Lấy danh sách sản phẩm Hot Sales",
     *     description="Lấy danh sách sản phẩm bán chạy, ưu tiên hot sales",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số sản phẩm trên 1 trang",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm hot sales",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="ten", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="hinh_anh", type="string", nullable=true),
     *                     @OA\Property(
     *                         property="gia",
     *                         type="object",
     *                         @OA\Property(property="current", type="number", format="float"),
     *                         @OA\Property(property="before_discount", type="number", format="float"),
     *                         @OA\Property(property="discount_percent", type="integer")
     *                     ),
     *                     @OA\Property(
     *                         property="store",
     *                         type="object",
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="icon_url", type="string", nullable=true)
     *                     ),
     *                     @OA\Property(
     *                         property="rating",
     *                         type="object",
     *                         @OA\Property(property="average", type="number", format="float"),
     *                         @OA\Property(property="count", type="integer")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Yêu cầu không hợp lệ"
     *     )
     * )
     */
    protected function getHotSales(Request $request)
    {
        /** HOT SALES */
        //@OA\Items(ref="#/components/schemas/HotSaleResource")
        //----------------  limit 10 //  giả cả rẻ + giảm giá + nhiều đơn hàng của sản phẩm nhất //
        // chitietdonhang , hinhanhsanpham , thuonghieu , bienthe , danhmuc mới
        // chiTietDonHang , anhSanPham , thuonghieu , bienThe , danhmuc, danhgia, loaibienthe củ (loaibienthe, danhgia)
        $perPage = $request->get('per_page', 10);

        // Lấy sản phẩm với quan hệ mới
        $query = SanphamModel::with([
                'hinhanhsanpham',   // hình ảnh sản phẩm
                'thuonghieu',       // thương hiệu
                'danhgia',          // đánh giá
                'danhmuc',          // danh mục
                'bienthe',          // biến thể
                'loaibienthe',      // loại biến thể (tabs SEO)
            ])

            ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng đã bán
            ->withAvg('danhgia as avg_rating', 'diem')      // Thêm avg_rating
            ->withCount('danhgia as review_count')         // Thêm review_count
            ->orderByRaw('COALESCE((SELECT giagoc
                        FROM bienthe
                        WHERE id_sanpham = sanpham.id
                        ORDER BY giagoc DESC LIMIT 1), 0) DESC')
            ->orderByDesc('total_sold'); // ưu tiên hot sales

        $products = $query->paginate($perPage);
        //     dd($query);
        // exit();
        // Trả về resource cho frontend //
        return HotSaleResource::collection($products);
    }


    /**
     * @OA\Get(
     *     path="/api/sanphams-selection?selection=top_categories",
     *     tags={"Sản phẩm (trang-chu)"},
     *     summary="Lấy danh sách danh mục hàng đầu",
     *     description="Danh sách các danh mục có nhiều sản phẩm bán chạy nhất. Limit mặc định 6.",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số danh mục trả về",
     *         required=false,
     *         @OA\Schema(type="integer", default=6)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách danh mục hot",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="ten_danhmuc", type="string"),
     *                     @OA\Property(property="total_sold", type="integer"),
     *                     @OA\Property(
     *                         property="sanpham",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="ten", type="string"),
     *                             @OA\Property(property="slug", type="string"),
     *                             @OA\Property(property="hinh_anh", type="string", nullable=true),
     *                             @OA\Property(
     *                                 property="gia",
     *                                 type="object",
     *                                 @OA\Property(property="current", type="number", format="float"),
     *                                 @OA\Property(property="before_discount", type="number", format="float"),
     *                                 @OA\Property(property="discount_percent", type="integer")
     *                             ),
     *                             @OA\Property(
     *                                 property="store",
     *                                 type="object",
     *                                 @OA\Property(property="name", type="string"),
     *                                 @OA\Property(property="icon_url", type="string", nullable=true)
     *                             ),
     *                             @OA\Property(
     *                                 property="rating",
     *                                 type="object",
     *                                 @OA\Property(property="average", type="number", format="float"),
     *                                 @OA\Property(property="count", type="integer")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Yêu cầu không hợp lệ"
     *     )
     * )
     */
    protected function getTopCategories(Request $request)
    {
        /** DANH MỤC HÀNG ĐẦU */
        //--------------------------------  + nhiều đơn hàng của sản phẩm nhất , UI chỉ có 6 limmit danh mục con, All là 4 limmit
        // @OA\Items(ref="#/components/schemas/CategoriesHotResource")
        $categoryLimit = $request->get('per_page', 6);
        $productLimit = 6;

        $categories = DanhmucModel::with(['sanpham' => function($q) use ($productLimit) {
            $q->withSum('chitietdonhang as total_sold', 'soluong')
            ->withAvg('danhgia as avg_rating', 'diem')      // Thêm avg_rating
            ->withCount('danhgia as review_count')         // Thêm review_count
            ->with(['hinhanhsanpham', 'thuonghieu', 'bienthe', 'loaibienthe'])
            ->orderByRaw('COALESCE((SELECT giagoc FROM bienthe WHERE id_sanpham = sanpham.id ORDER BY giagoc DESC LIMIT 1), 0) DESC')
            ->orderByDesc('total_sold')
            ->limit($productLimit);
        }])
        ->get()
        ->map(function ($danhmuc) {
            $danhmuc->setAttribute('total_sold', $danhmuc->sanpham->sum('total_sold'));
            return $danhmuc;
        })
        ->sortByDesc('total_sold')
        ->take($categoryLimit);

        return CategoriesHotResource::collection($categories);
    }





    /**
     * @OA\Get(
     *     path="/api/sanphams-selection?selection=top_brands",
     *     summary="Lấy top thương hiệu có sản phẩm bán chạy",
     *     description="Trả về danh sách thương hiệu top bán chạy, limit 10",
     *     tags={"Sản phẩm (trang-chu)"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số thương hiệu trả về",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách thương hiệu hot",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="logo", type="string", nullable=true),
     *                     @OA\Property(property="total_sold", type="integer")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Yêu cầu không hợp lệ"
     *     )
     * )
     */
    protected function getTopBrands(Request $request)
    {
        /** THƯƠNG HIỆU HÀNG ĐẦU */

        //--------------------------- limit 10 // nhiều đơn hàng của sản phẩm nhất // list danh sách thuong hieu ko phải sản phẩm
        $perPage = $request->get('per_page', 10);

        // Lấy thương hiệu với tổng sản phẩm bán được
        $brands = ThuongHieuModel::with(['sanpham' => function($q) {
            $q->withSum('chitietdonhang as total_sold', 'soluong')
            ->with(['bienthe']); // load biến thể nếu cần
        }])
        ->get()
        ->map(function ($brand) {
            $brand->total_sold = $brand->sanpham->sum('total_sold') ?? 0;
            return $brand;
        })
        ->sortByDesc('total_sold') // sắp xếp theo tổng bán
        ->take($perPage); // giới hạn số lượng

        // Trả về resource
        return BrandsHotResource::collection($brands);
    }


    /**
     * @OA\Get(
     *     path="/api/sanphams-selection?selection=best_products",
     *     summary="Lấy sản phẩm tốt nhất (Best Products)",
     *     description="Trả về sản phẩm bán chạy + đánh giá cao, limit 8",
     *     tags={"Sản phẩm (trang-chu)"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số sản phẩm trả về",
     *         required=false,
     *         @OA\Schema(type="integer", default=8)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm best products",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="ten", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="hinh_anh", type="string", nullable=true),
     *                     @OA\Property(
     *                         property="gia",
     *                         type="object",
     *                         @OA\Property(property="current", type="number", format="float"),
     *                         @OA\Property(property="before_discount", type="number", format="float"),
     *                         @OA\Property(property="discount_percent", type="integer")
     *                     ),
     *                     @OA\Property(
     *                         property="store",
     *                         type="object",
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="icon_url", type="string", nullable=true)
     *                     ),
     *                     @OA\Property(
     *                         property="rating",
     *                         type="object",
     *                         @OA\Property(property="average", type="number", format="float"),
     *                         @OA\Property(property="count", type="integer")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Yêu cầu không hợp lệ"
     *     )
     * )
     */
    protected function getBestProducts(Request $request)
    {
        // @OA\Items(ref="#/components/schemas/HotSaleResource")
        // GET /api/sanphams-selection?selection=best_products // limit 8 // nhiều đơn hàng của sản phẩm nhất và đánh giá
        $perPage = $request->get('per_page', 8);

        $query = SanphamModel::with([
                'hinhanhsanpham',   // hình ảnh sản phẩm
                'thuonghieu',       // thương hiệu
                'danhgia',          // đánh giá
                'danhmuc',          // danh mục
                'bienthe',          // biến thể
                'loaibienthe',      // loại biến thể (tabs SEO)
            ])

            ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng đã bán
            ->withAvg('danhgia as avg_rating', 'diem')      // Thêm avg_rating
            ->withCount('danhgia as review_count')         // Thêm review_count
            ->orderByRaw('COALESCE((SELECT giagoc
                        FROM bienthe
                        WHERE id_sanpham = sanpham.id
                        ORDER BY giagoc DESC LIMIT 1), 0) DESC')
            ->orderByDesc('total_sold')
            ->orderByDesc('avg_rating');

        $products = $query->paginate($perPage);
        //     dd($query);
        // exit();
        // Trả về resource cho frontend //
        return HotSaleResource::collection($products);
    }


    /**
     * @OA\Get(
     *     path="/api/sanphams-selection?selection=recommend",
     *     summary="Sản phẩm gợi ý",
     *     description="Trả về sản phẩm gợi ý theo lượt xem cao + giá rẻ + giảm giá, limit 8",
     *     tags={"Sản phẩm (trang-chu)"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số sản phẩm trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=8)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm gợi ý",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="ten", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="hinh_anh", type="string", nullable=true),
     *                     @OA\Property(
     *                         property="gia",
     *                         type="object",
     *                         @OA\Property(property="current", type="number", format="float"),
     *                         @OA\Property(property="before_discount", type="number", format="float"),
     *                         @OA\Property(property="discount_percent", type="integer")
     *                     ),
     *                     @OA\Property(
     *                         property="store",
     *                         type="object",
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="icon_url", type="string", nullable=true)
     *                     ),
     *                     @OA\Property(
     *                         property="rating",
     *                         type="object",
     *                         @OA\Property(property="average", type="number", format="float"),
     *                         @OA\Property(property="count", type="integer")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Yêu cầu không hợp lệ"
     *     )
     * )
     */
    protected function getRecommend(Request $request)
    {
        /** GỢI Ý */
        ////@OA\Items(ref="#/components/schemas/HotSaleResource")
        // tùy theo lược xem + giả cả rẻ + giảm giá
        $perPage = $request->get('per_page', 8);

        $query = SanphamModel::with([
            'hinhanhsanpham',
            'thuonghieu',
            'danhgia',
            'danhmuc',
            'bienthe',
            'loaibienthe',
        ])
        ->withSum('chitietdonhang as total_sold', 'soluong')
        ->withAvg('danhgia as avg_rating', 'diem')
        ->withCount('danhgia as review_count');

        // Sắp xếp theo 3 tiêu chí cùng lúc
        $query->orderByDesc('luotxem') // lượt xem cao hơn
            ->orderByRaw('COALESCE((SELECT MIN(giagoc) FROM bienthe WHERE id_sanpham = sanpham.id), 0) ASC') // giá rẻ trước
            ->orderByDesc('giamgia')
            ->orderByDesc('total_sold')
            ->orderByDesc('avg_rating');

        $products = $query->paginate($perPage);

        return HotSaleResource::collection($products);
    }




    /**
     * @OA\Get(
     *     path="/api/sanphams-selection?selection=default",
     *     summary="Danh sách sản phẩm mặc định",
     *     description="Phân trang + filter theo thương hiệu, danh mục, giá min/max, sắp xếp lượt xem + giá rẻ + giảm giá + tổng bán + đánh giá",
     *     tags={"Sản phẩm (trang-chu)"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số sản phẩm trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Trang hiện tại",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="thuonghieu",
     *         in="query",
     *         description="Lọc theo id thương hiệu",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="danhmuc",
     *         in="query",
     *         description="Lọc theo id danh mục",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="gia_min",
     *         in="query",
     *         description="Lọc theo giá tối thiểu",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="gia_max",
     *         in="query",
     *         description="Lọc theo giá tối đa",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm mặc định",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="ten", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="hinh_anh", type="string", nullable=true),
     *                     @OA\Property(
     *                         property="gia",
     *                         type="object",
     *                         @OA\Property(property="current", type="number", format="float"),
     *                         @OA\Property(property="before_discount", type="number", format="float"),
     *                         @OA\Property(property="discount_percent", type="integer")
     *                     ),
     *                     @OA\Property(
     *                         property="store",
     *                         type="object",
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="icon_url", type="string", nullable=true)
     *                     ),
     *                     @OA\Property(
     *                         property="rating",
     *                         type="object",
     *                         @OA\Property(property="average", type="number", format="float"),
     *                         @OA\Property(property="count", type="integer")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Yêu cầu không hợp lệ"
     *     )
     * )
     */
    protected function getDefaultProducts(Request $request)
    {
        /** Default: phân trang + filter */
        $perPage = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);

        $query = SanphamModel::with([
            'hinhanhsanpham',
            'thuonghieu',
            'danhgia',
            'danhmuc',
            'bienthe',
            'loaibienthe',
        ])
        ->withSum('chitietdonhang as total_sold', 'soluong')
        ->withAvg('danhgia as avg_rating', 'diem')
        ->withCount('danhgia as review_count');

        // Filter theo thương hiệu
        if ($request->filled('thuonghieu')) {
            $query->where('id_thuonghieu', (int) $request->thuonghieu);
        }

        // Filter theo danh mục
        if ($request->filled('danhmuc')) {
            $query->whereHas('danhmuc', fn($q) => $q->where('danhmuc.id', (int) $request->danhmuc));
        }

        // Filter theo khoảng giá (dựa trên giá thấp nhất của biến thể)
        if ($request->filled('gia_min')) {
            $query->whereHas('bienthe', fn($q) => $q->where('giagoc', '>=', (int) $request->gia_min));
        }

        if ($request->filled('gia_max')) {
            $query->whereHas('bienthe', fn($q) => $q->where('giagoc', '<=', (int) $request->gia_max));
        }

        // Sắp xếp ưu tiên theo: lượt xem + giá rẻ + % giảm + tổng bán + đánh giá
        $query->orderByDesc('luotxem')
            ->orderByRaw('COALESCE((SELECT MIN(giagoc) FROM bienthe WHERE id_sanpham = sanpham.id), 0) ASC')
            ->orderByDesc('giamgia')
            ->orderByDesc('total_sold')
            ->orderByDesc('avg_rating');

        // Paginate
        $sanphams = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return HotSaleResource::collection($sanphams);
    }
}

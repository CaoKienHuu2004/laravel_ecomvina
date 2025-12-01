<?php



namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\API\QuaTangSuKienAPI;
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
use App\Http\Resources\Frontend\GiftHotResource;
use App\Http\Resources\Frontend\HotSaleResource;
use App\Http\Resources\Frontend\RecommentResource;
use App\Models\DanhgiaModel;
use App\Models\DanhmucModel;
use App\Models\QuangcaoModel;
use App\Models\QuatangsukienModel;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;
use App\Models\TukhoaModel;
use Illuminate\Support\Facades\DB;




/**
 * @OA\Tag(
 *     name="Trang Chủ",
 *     description=" sản phẩm của trang chủ được lọc theo yêu cầu nghiệp vụ của từng selection, tên cũ sanphams-selection"
 * )
 */
class TrangChuAPI extends BaseFrontendController
{
    /**
     * @OA\Get(
     *     path="/api/trang-chu",
     *     summary="Danh Sách Các Selection Trang Chủ",
     *     description="Trả về các nhóm dữ liệu cho trang chủ bao gồm: hot_sales, hot_gift, top_categories, top_brands, best_products, recommend.",
     *     tags={"Trang Chủ"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng phần tử mỗi trang (mặc định: 4 cho hot_gift, 20 cho hot_sales, v.v.)",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách dữ liệu trang chủ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách các selection của trang chủ"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
    *                  @OA\Property(
    *                     property="hot_keywords",
    *                     type="array",
    *                     description="🔥 Danh sách từ khóa hot với trường lienket tạo link tìm kiếm",
    *                     @OA\Items(ref="#/components/schemas/HotKeywordItem")
    *                 ),
    *                  @OA\Property(
    *                     property="new_banners",
    *                     type="array",
    *                     description="🔥 Danh sách banner quảng cáo mới nhất (bảng quangcao)",
    *                     @OA\Items(ref="#/components/schemas/NewBannerItem")
    *                 ),
    *                       * @OA\Property(
    *     property="hot_categories",
    *     type="array",
    *     description="🔥 Danh sách danh mục nổi bật được sắp xếp theo tổng số sản phẩm bán chạy nhất (total_luotban)",
    *     @OA\Items(
    *         type="object",
    *         @OA\Property(property="id", type="integer", example=3),
    *         @OA\Property(property="ten", type="string", example="Điện thoại"),
    *         @OA\Property(property="slug", type="string", example="dien-thoai"),
    *         @OA\Property(property="logo", type="string", example="danhmuc.jpg"),
    *         @OA\Property(property="total_luotban", type="integer", example=1243, description="Tổng số lượt bán của tất cả sản phẩm trong danh mục"),
    *         @OA\Property(property="lienket", type="string", example="https://localhost:8000/api/sanphams-all?danhmuc=noi-that-trang-tri", description="Link tìm kiếm sản phẩm theo danh mục")
    *     )
    * ),
     *                 @OA\Property(
     *                     property="hot_sales",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                 @OA\Property(
     *                     property="hot_gift",
     *                     type="array",
     *                     description="🎁 Danh sách quà tặng sự kiện hot (nhiều lượt xem, sắp hết hạn)",
     *                     @OA\Items(ref="#/components/schemas/HotGiftItem")
     *                 ),
     *                 @OA\Property(
     *                     property="top_categories",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/CategoryHotItem")
     *                 ),
     *                 @OA\Property(
     *                     property="top_brands",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/BrandHotItem")
     *                 ),
     *                 @OA\Property(
     *                     property="best_products",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                 @OA\Property(
     *                     property="new_launch",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/SanphamItem")
     *                 ),
     *                  @OA\Property(
     *                     property="most_watched",
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
     *     @OA\Property(property="hinh_anh", type="string", nullable=true, example="iphone15.jpg"),
     *     @OA\Property(
     *         property="gia",
     *         type="object",
     *         @OA\Property(property="current", type="number", format="float", example=27990000),
     *         @OA\Property(property="before_discount", type="number", format="float", example=30990000),
     *         @OA\Property(property="discount_percent", type="integer", example=10)
     *     ),
     *     @OA\Property(
     *         property="rating",
     *         type="object",
     *         @OA\Property(property="average", type="number", format="float", example=4.8),
     *         @OA\Property(property="count", type="integer", example=128)
     *     ),
     *     @OA\Property(property="sold_count", type="integer", example=532)
     * )
     *
     * @OA\Schema(
     *     schema="CategoryHotItem",
     *     type="object",
     *     title="Danh mục nổi bật",
     *     @OA\Property(property="id", type="integer", example=3),
     *     @OA\Property(property="ten", type="string", example="Điện thoại"),
     *     @OA\Property(property="slug", type="string", example="dien-thoai"),
     *     @OA\Property(property="total_sold", type="integer", example=1243),
     *     @OA\Property(
     *         property="sanpham",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/SanphamItem")
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="BrandHotItem",
     *     type="object",
     *     title="Thương hiệu nổi bật",
     *     @OA\Property(property="id", type="integer", example=7),
     *     @OA\Property(property="ten", type="string", example="Apple"),
     *     @OA\Property(property="slug", type="string", example="apple"),
     *     @OA\Property(property="logo", type="string", example="apple.png"),
     *     @OA\Property(property="mota", type="string", example="Thương hiệu công nghệ hàng đầu thế giới."),
     *     @OA\Property(property="total_sold", type="integer", example=3219)
     * )
     *
     * @OA\Schema(
     *     schema="HotGiftItem",
     *     type="object",
     *     title="Quà tặng hot",
     *     description="Thông tin quà tặng sự kiện nổi bật",
     *     @OA\Property(property="id", type="integer", example=10),
     *     @OA\Property(property="ten", type="string", example="Tặng Tai Nghe Bluetooth khi mua iPhone 15"),
     *     @OA\Property(property="slug", type="string", example="tang-tai-nghe-iphone-15"),
     *     @OA\Property(property="hinh_anh", type="string", example="gift_iphone15.png"),
     *     @OA\Property(property="mota", type="string", example="Áp dụng cho đơn hàng trên 20 triệu, đến hết ngày 30/11/2025."),
     *     @OA\Property(property="ngaybatdau", type="string", format="date", example="2025-11-01"),
     *     @OA\Property(property="ngayketthuc", type="string", format="date", example="2025-11-30"),
     *     @OA\Property(property="luotxem", type="integer", example=1450),
     *     @OA\Property(
     *         property="chuongtrinh",
     *         type="object",
     *         @OA\Property(property="tenchuongtrinh", type="string", example="Tháng tri ân khách hàng"),
     *         @OA\Property(property="slug", type="string", example="tri-an-khach-hang")
     *     )
     * )
     * * @OA\Schema(
    *     schema="NewBannerItem",
    *     type="object",
    *     title="Banner quảng cáo mới nhất",
    *     description="Thông tin banner quảng cáo hiển thị trên trang chủ (bảng quangcao)",
    *     @OA\Property(property="id", type="integer", example=12),
    *     @OA\Property(property="vitri", type="string", example="home_banner_slider"),
    *     @OA\Property(property="hinhanh", type="string", example="banner_khuyenmai_12.jpg"),
    *     @OA\Property(property="lienket", type="string", example="https://nextjsproject/khuyen-mai"),
    *     @OA\Property(property="mota", type="string", example="Giảm giá 50% cho đơn hàng đầu tiên trong tháng 11."),
    *     @OA\Property(property="trangthai", type="string", example="Hiển thị")
    * )
    * @OA\Schema(
    *     schema="HotKeywordItem",
    *     type="object",
    *     title="Từ khóa hot",
    *     description="Thông tin từ khóa hot cùng link tìm kiếm (lienket)",
    *     @OA\Property(property="id", type="integer", example=1),
    *     @OA\Property(property="tukhoa", type="string", example="iphone"),
    *     @OA\Property(property="luottruycap", type="integer", example=1520),
    *     @OA\Property(property="lienket", type="string", example="https://localhost:8000/api/tim-kiem/?query=iphone")
    * )
     */
    public function index(Request $request)
    {
        $data = [
            'hot_keywords'      => $this->getHotKeywords($request),

            'new_banners'      => $this->getNewBanners($request),
            'hot_categories'      => $this->getHotCategories($request),
            'hot_sales'      => $this->getHotSales($request),
            'hot_gift'         => $this->getHotGift($request),
            'top_categories' => $this->getTopCategories($request),
            'top_brands'     => $this->getTopBrands($request),
            'best_products'  => $this->getBestProducts($request),
            // 'recommend'      => $this->getRecommend($request, $request->get('danhmuc_id')), // bỏ phần recommend
            // Hàng mới chào sân, mới thêm vào hệ thống
            // Được quan tâm nhiều nhất, lượt xem cao nhất, mới thêm vào hệ thống
            // 'default'        => $this->getDefaultProducts($request),
            'new_launch'  => $this->getNewLaunch($request),
            'most_watched'  => $this->getMostWatChed($request),
        ];


        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách các selection của trang chủ',
            'data'    => $data,
        ], Response::HTTP_OK);
    }





    protected function getHotSales(Request $request)
    {
        /** HOT SALES */
        //@OA\Items(ref="#/components/schemas/HotSaleResource")
        //---------------- v1  limit 10 //  giả cả rẻ + giảm giá + nhiều đơn hàng của sản phẩm nhất
        // v2 luot bban cao nhất + phải có thì mới được lên giảm giả. v3 có thể luotban cố định lên bao nhieu , giam gia theo vd85%
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

            // ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng đã bán
            ->withAvg('danhgia as avg_rating', 'diem')      // Thêm avg_rating
            ->withCount('danhgia as review_count')         // Thêm review_count
            ->withSum('bienthe as total_sold', 'luotban')
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



    protected function getTopCategories(Request $request)
    {
        /** 🔥 DANH MỤC HÀNG ĐẦU DỰA THEO LUOTBAN CỦA BIẾN THỂ */
        /** DANH MỤC HÀNG ĐẦU */ //-------------------------------- + nhiều đơn hàng của sản phẩm nhất , UI chỉ có 6 limmit danh mục con, All là 4 limmit //
        $categoryLimit = $request->get('per_page', 6);
        $productLimit = 6;

        $categories = DanhmucModel::with(['sanpham' => function($q) use ($productLimit) {
            $q->withAvg('danhgia as avg_rating', 'diem')
            ->withCount('danhgia as review_count')
            ->with(['hinhanhsanpham', 'thuonghieu', 'bienthe', 'loaibienthe'])
            ->orderByRaw('COALESCE((SELECT giagoc FROM bienthe WHERE id_sanpham = sanpham.id ORDER BY giagoc DESC LIMIT 1), 0) DESC')
            ->limit($productLimit);
        }])
        ->get()
        ->map(function ($danhmuc) {
            // ✅ Tính tổng lượt bán theo tất cả biến thể của tất cả sản phẩm trong danh mục
            if ($danhmuc instanceof DanhmucModel) {
                $danhmuc->total_sold = $danhmuc->sanpham->reduce(function ($carry, $product) {
                    return $carry + $product->bienthe->sum('luotban');
                }, 0);
            }

            // ✅ Đồng thời, sắp xếp lại danh sách sản phẩm trong danh mục theo tổng lượt bán của biến thể

            if ($danhmuc instanceof DanhmucModel) {
                $danhmuc->sanpham = $danhmuc->sanpham->sortByDesc(function ($product) {
                    return $product->bienthe->sum('luotban');
                })->take(6)->values();
            }

            return $danhmuc;
        })
        ->sortByDesc('total_sold')
        ->take($categoryLimit)
        ->values();

        return CategoriesHotResource::collection($categories);
    }






    protected function getTopBrands(Request $request)
    {
        /** 🔥 THƯƠNG HIỆU HÀNG ĐẦU DỰA THEO LUOTBAN CỦA BIẾN THỂ */
        //--------------------------- limit 10 // nhiều đơn hàng của sản phẩm nhất // list danh sách thuong hieu ko phải sản phẩm

        $perPage = $request->get('per_page', 10);

        // Lấy thương hiệu kèm theo sản phẩm và biến thể
        $brands = ThuongHieuModel::with(['sanpham.bienthe'])
            ->get()
            ->map(function ($brand) {
                // Tính tổng lượt bán từ tất cả biến thể của tất cả sản phẩm
                if ($brand instanceof ThuongHieuModel) {
                    $brand->total_sold = $brand->sanpham->reduce(function ($carry, $product) {
                        return $carry + $product->bienthe->sum('luotban');
                    }, 0);
                }

                return $brand;
            })
            ->sortByDesc('total_sold')
            ->take($perPage)
            ->values(); // reset lại index

        return BrandsHotResource::collection($brands);
    }



    protected function getBestProducts(Request $request)
    {
        // @OA\Items(ref="#/components/schemas/HotSaleResource")
        // v1 GET /api/sanphams-selection?selection=best_products // limit 8 // nhiều đơn hàng của sản phẩm nhất và đánh giá
        // v2 từ 4 -5 sao trở lên, bán chạy uy tín
        $perPage = $request->get('per_page', 8);

        $query = SanphamModel::with([
                'hinhanhsanpham',   // hình ảnh sản phẩm
                'thuonghieu',       // thương hiệu
                'danhgia',          // đánh giá
                'danhmuc',          // danh mục
                'bienthe',          // biến thể
                'loaibienthe',      // loại biến thể (tabs SEO)
            ])

            ->withAvg('danhgia as avg_rating', 'diem')      // Thêm avg_rating
            ->withCount('danhgia as review_count')         // Thêm review_count
            ->withSum('bienthe as total_sold', 'luotban')
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



    // protected function getRecommend(Request $request)
    // {
    //     /** GỢI Ý */
    //     ////@OA\Items(ref="#/components/schemas/HotSaleResource")
    //     // tùy theo lược xem + giả cả rẻ + giảm giá
    //     $perPage = $request->get('per_page', 8);

    //     $query = SanphamModel::with([
    //             'hinhanhsanpham',
    //             'thuonghieu',
    //             'danhgia',
    //             'danhmuc',
    //             'bienthe',
    //             'loaibienthe',
    //         ])
    //         ->withAvg('danhgia as avg_rating', 'diem')
    //         ->withCount('danhgia as review_count')
    //         ->withSum('bienthe as total_sold', 'luotban')
    //         ->orderByRaw('COALESCE((SELECT giagoc
    //                     FROM bienthe
    //                     WHERE id_sanpham = sanpham.id
    //                     ORDER BY giagoc DESC LIMIT 1), 0) DESC')
    //         ->orderByDesc('total_sold')
    //         ->orderByDesc('avg_rating');

    //     $products = $query->paginate($perPage);

    //     return HotSaleResource::collection($products);
    // }

    protected function getHotGift(Request $request)
    {
        /** 🎁 QUÀ TẶNG */
        // limit 8 // nhiều lượt xem + sắp hết hạn
        $perPage = $request->get('per_page', 8);

        $query = QuatangsukienModel::with('chuongtrinh')
            ->where('trangthai', 'Hiển thị')
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('ngaybatdau')
                ->orWhere('ngaybatdau', '<=', $today);
            })
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('ngayketthuc')
                ->orWhere('ngayketthuc', '>=', $today);
            })
            ->orderByDesc('luotxem')
            ->orderBy('ngayketthuc');

        $gifts = $query->paginate($perPage);

        return GiftHotResource::collection($gifts);
    }

    protected function getNewBanners(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $banners = QuangcaoModel::where('trangthai', 'Hiển thị')
            ->orderByDesc('id') // Mới nhất trước
            ->limit($perPage)
            ->get(['id', 'vitri', 'hinhanh', 'lienket', 'mota', 'trangthai']);

        return $banners;
    }
    protected function getHotKeywords(Request $request)
    {
        // limit 5 từ khóa, lọc theo lượt truy cập
        $perPage = $request->get('per_page', 5);

        // Lấy dữ liệu từ model
        $hotKeywords = TukhoaModel::orderByDesc('luottruycap')
            ->limit($perPage)
            ->get();

        // Thêm trường lienket vào từng item, giả sử bạn tạo link tìm kiếm từ từ khóa
        $hotKeywords->transform(function ($item) {
            $item->lienket = url('/api/tim-kiem/?query=' . urlencode($item->tukhoa));
            return $item;
        });

        return $hotKeywords;
    }
    protected function getHotCategories(Request $request)
    {
        // litmit 11 số lượng sản phẩm bán chạy nhất (tổng luotban) hoặc theo lượt xem nhiều nhất (giả sử lượt xem là luotxem hoặc tương tự)
        $perPage = $request->get('per_page', 11);

        // Lấy danh mục "Hiển thị" và "Cha"
        $query = DanhmucModel::select('danhmuc.id', 'danhmuc.ten', 'danhmuc.slug', 'danhmuc.logo',
            DB::raw('COALESCE(SUM(bienthe.luotban), 0) as total_luotban')
        )
        ->leftJoin('danhmuc_sanpham', 'danhmuc.id', '=', 'danhmuc_sanpham.id_danhmuc')
        ->leftJoin('sanpham', 'danhmuc_sanpham.id_sanpham', '=', 'sanpham.id')
        ->leftJoin('bienthe', 'sanpham.id', '=', 'bienthe.id_sanpham')
        ->where('danhmuc.trangthai', 'Hiển thị')
        ->where('danhmuc.parent', 'Cha')
        ->groupBy('danhmuc.id', 'danhmuc.ten', 'danhmuc.slug', 'danhmuc.logo')
        ->orderByDesc('total_luotban')  // Sắp xếp theo tổng lượt bán giảm dần
        ->orderBy('danhmuc.id');

        $categories = $query->paginate($perPage);

        $data = $categories->toArray();

        foreach ($data['data'] as &$category) {
            $category['lienket'] = url('/api/sanphams-all?danh-muc=' . $category['slug']);
        }

        return $data['data'];
    }

    protected function getNewLaunch(Request $request)
    {
        // @OA\Items(ref="#/components/schemas/HotSaleResource")
        // v1 GET /api/sanphams-selection?selection=new_launchs // limit 8 // mới thêm vào hệ thống
        // v2 từ 4 -5 sao trở lên, mới thêm vào hệ thống
        $perPage = $request->get('per_page', 18);

        $query = SanphamModel::with([
                'hinhanhsanpham',   // hình ảnh sản phẩm
                'thuonghieu',       // thương hiệu
                'danhgia',          // đánh giá
                'danhmuc',          // danh mục
                'bienthe',          // biến thể
                'loaibienthe',      // loại biến thể (tabs SEO)
            ])

            ->withAvg('danhgia as avg_rating', 'diem')      // Thêm avg_rating
            ->withCount('danhgia as review_count')         // Thêm review_count
            ->withSum('bienthe as total_sold', 'luotban')
            ->orderByDesc('id');

        $products = $query->paginate($perPage);
        //     dd($query);
        // exit();
        // Trả về resource cho frontend //
        return HotSaleResource::collection($products);
    }

    protected function getMostWatChed(Request $request)
    {
        // @OA\Items(ref="#/components/schemas/HotSaleResource")
        // v1 GET /api/sanphams-selection?selection=most_watched // limit 8 // nhiều lượt xem nhất
        // v2 từ 4 -5 sao trở lên, nhiều lượt xem nhất
        $perPage = $request->get('per_page', 18);

        $query = SanphamModel::with([
                'hinhanhsanpham',   // hình ảnh sản phẩm
                'thuonghieu',       // thương hiệu
                'danhgia',          // đánh giá
                'danhmuc',          // danh mục
                'bienthe',          // biến thể
                'loaibienthe',      // loại biến thể (tabs SEO)
            ])

            ->withAvg('danhgia as avg_rating', 'diem')      // Thêm avg_rating
            ->withCount('danhgia as review_count')         // Thêm review_count
            ->withSum('bienthe as total_sold', 'luotban')
            ->orderByDesc('luotxem');

        $products = $query->paginate($perPage);
        //     dd($query);
        // exit();
        // Trả về resource cho frontend //
        return HotSaleResource::collection($products);
    }

}




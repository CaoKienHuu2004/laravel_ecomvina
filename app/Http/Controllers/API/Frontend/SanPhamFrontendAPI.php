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
use Illuminate\Support\Facades\DB;

class SanPhamFrontendAPI extends SanphamAPI
{
    public function index(Request $request)
    {
        $selection = $request->get('selection');

        switch ($selection) {
            case 'hot_sales':
                $data = $this->getHotSales($request);
                break;
            case 'top_categories':
                $data = $this->getTopCategories($request);
                break;
            case 'top_brands':
                $data = $this->getTopBrands($request);
                break;
            case 'best_products':
                $data = $this->getBestProducts($request);
                break;
            case 'recommend':
                $data = $this->getRecommend($request, $request->get('danhmuc_id'));
                break;
            default:
                $data = $this->getDefaultProducts($request);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách sản phẩm',
            'data'    => $data
        ], Response::HTTP_OK);
    }

    /** HOT SALES */
    //----------------  limit 10 // nhiều đơn hàng của sản phẩm nhất
    // protected function getHotSales(Request $request)
    // {
    //     $perPage = $request->get('per_page', 10); // team 10
    //     // $currentPage = $request->get('page', 1);
    //     $query = Sanpham::with(['anhSanPham', 'thuonghieu','danhgia','danhmuc','bienThe','loaibienthe'])
    //         ->withSum('chiTietDonHang as total_sold', 'soluong')
    //         ->orderByDesc('total_sold');
    //     // $products = $query->paginate($perPage, ['*'], 'page', $currentPage);
    //     $products = $query->paginate($perPage);

    //     return SanphamResources::collection($products);
    // }

    //----------------  limit 10 //  giả cả rẻ + giảm giá + nhiều đơn hàng của sản phẩm nhất // thêm của mình bienThe.uutien chọn bienThe ưu tiên hiển thị sanPham
    protected function getHotSales(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        // Lấy sản phẩm với quan hệ
        $query = Sanpham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienThe', 'loaibienthe'])
            ->withSum('chiTietDonHang as total_sold', 'soluong')
            ->with(['bienThe' => function ($q) {
                $q->orderBy('uutien', 'asc');
            }])
            ->orderByRaw('COALESCE((SELECT gia - giagiam FROM bienthe_sp WHERE id_sanpham = san_pham.id ORDER BY uutien ASC LIMIT 1), 0) ASC')
            ->orderByRaw('COALESCE((SELECT giagiam FROM bienthe_sp WHERE id_sanpham = san_pham.id ORDER BY uutien ASC LIMIT 1), 0) DESC')
            ->orderByDesc('total_sold');
        $products = $query->paginate($perPage);

        // return SanphamResources::collection($products);
        return HotSaleResource::collection($products);

    }

    /** DANH MỤC HÀNG ĐẦU */
    //--------------------------------  + theo số lượng sản phẩm nhiều nhất , UI chỉ có 6 limmit danh mục con, All là 4 limmit
    // protected function getTopCategories(Request $request)
    // {

    //     // -------------------------------- UI chỉ có 6 limmit danh mục con, All là 4 limmit
    //     // -Danh mục 1: Điện thoại di động (tổng lượt bán: 200)
    //     // ---Sản phẩm1: Iphone17 (lượt bán: 100)
    //     // ---Sản phẩm 2:Samsung (lượt bán: 100)

    //     // -Danh mục 2: Laptop (tổng lượt bán: 15)
    //     // --- Sản phẩm 1: Asus Desk (lượt bán:10)
    //     // --- Sản phẩm 2: LENOVO (lượt bán: 5)

    //     // $categories = Danhmuc::with(['sanpham' => function ($q) {
    //     // $q->withSum('chiTietDonHang as total_sold', 'soluong')
    //     //   ->orderByDesc('total_sold'); // sắp xếp sản phẩm trong danh mục theo số lượng bán
    //     //     }])
    //     //     ->withSum('sanpham.chiTietDonHang as category_total_sold', 'soluong') // tổng lượt bán của cả danh mục
    //     //     ->orderByDesc('category_total_sold') // sắp xếp danh mục theo tổng lượt bán
    //     //     ->get();
    //     // --------------------------------
    //     $perPage = $request->get('per_page', 8);

    //     // Lấy danh mục theo số lượng sản phẩm nhiều nhất
    //     $topCategories = Danhmuc::withCount('sanphams')
    //         ->orderByDesc('sanphams_count')
    //         ->take($perPage)
    //         ->get();

    //     // Lấy toàn bộ sản phẩm thuộc các danh mục này
    //     $sanphams = Sanpham::with(['anhSanPham', 'thuonghieu','danhgia','danhmuc','bienThe','loaibienthe'])
    //         ->whereHas('danhmuc', function ($query) use ($topCategories) {
    //             $query->whereIn('id_danhmuc', $topCategories->pluck('id'));
    //         })
    //         ->paginate($perPage);

    //     return SanphamResources::collection($sanphams);
    // }
    //--------------------------------  + nhiều đơn hàng của sản phẩm nhất , UI chỉ có 6 limmit danh mục con, All là 4 limmit
    // protected function getTopCategories(Request $request)
    // {
    //     $perPage = $request->get('per_page', 6);

    //     // Lấy top danh mục theo tổng lượt mua
    //     $topCategories = Danhmuc::with(['sanphams'])
    //         ->get()
    //         ->sortByDesc(function ($category) {
    //             // Tổng lượt mua của tất cả sản phẩm trong danh mục
    //             return $category->sanphams->sum('luot_mua');
    //         })
    //         ->take($perPage);

    //     $sanphams = Sanpham::with(['anhSanPham', 'thuonghieu','danhgia','danhmuc','bienThe','loaibienthe'])
    //         ->whereIn('id_danhmuc', $topCategories->pluck('id'))
    //         ->paginate($perPage);

    //     return SanphamResources::collection($sanphams);
    // }

    //--------------------------------  + nhiều đơn hàng của sản phẩm nhất , UI chỉ có 6 limmit danh mục con, All là 4 limmit
    protected function getTopCategories(Request $request)
    {
        $categoryLimit = $request->get('per_page', 6); // UI chỉ hiển thị 6 danh mục
        $productLimit = 6; // Số sản phẩm tối đa mỗi danh mục

        // Lấy tất cả danh mục kèm sản phẩm, tính tổng lượt bán
        $categories = Danhmuc::with(['sanphams' => function ($q) use ($productLimit) {
                $q->withSum('chiTietDonHang as total_sold', 'soluong')
                ->orderByDesc('total_sold') // sản phẩm bán nhiều trước
                ->limit($productLimit);
            }])
            ->get()
            ->map(function ($danhmuc) {
                // Tính tổng lượt bán của danh mục
                $danhmuc->setAttribute('total_sold', $danhmuc->sanphams->sum('total_sold'));
                return $danhmuc;
            })
            ->sortByDesc('total_sold') // danh mục bán nhiều trước
            ->take($categoryLimit);

        // Trả về resource
        return CategoriesHotResource::collection($categories);
    }





    /** THƯƠNG HIỆU HÀNG ĐẦU */
    //--------------------------- limit 10 thương hiệu có nhiều sản phẩm nhất
    //    protected function getTopBrands(Request $request)
    //     {
    //         $perPage = $request->get('per_page', 10);

    //         // Lấy ra danh sách sản phẩm theo thương hiệu, sắp xếp theo số lượng sản phẩm trong thương hiệu
    //         $sanphams = Sanpham::with(['anhSanPham', 'thuonghieu','danhgia','danhmuc','bienThe','loaibienthe'])
    //             ->withCount('thuonghieu as sanpham_count') // đếm số sp theo brand
    //             ->orderByDesc('sanpham_count')
    //             ->paginate($perPage);

    //         return SanphamResources::collection($sanphams);
    //     }

    //--------------------------- limit 10 // nhiều đơn hàng của sản phẩm nhất // list danh sách thuong hieu ko phải sản phẩm
    protected function getTopBrands(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $brands = DB::table('thuong_hieu as th')
            ->select(
                'th.id',
                'th.ten',
                'th.mota',
                // 'th.namthanhlap',
                'th.media',
                DB::raw('COALESCE(SUM(ct.soluong), 0) as total_sold')
            )
            ->leftJoin('san_pham as sp', 'sp.id_thuonghieu', '=', 'th.id')
            ->leftJoin('bienthe_sp as bt', 'bt.id_sanpham', '=', 'sp.id')
            ->leftJoin('chitiet_donhang as ct', 'ct.id_bienthe', '=', 'bt.id')
            ->groupBy('th.id', 'th.ten', 'th.mota')
            ->orderByDesc('total_sold')
            ->paginate($perPage);

        return BrandsHotResource::collection($brands);
    }
    //-- lấy hêt
    // protected function getTopBrands(Request $request)
    // {
    //     $perPage = $request->get('per_page'); // có thể null

    //     $query = DB::table('thuong_hieu as th')
    //         ->select(
    //             'th.id',
    //             'th.ten',
    //             'th.mota',
    //             'th.namthanhlap',
    //             'th.media',
    //             DB::raw('COALESCE(SUM(ct.soluong), 0) as total_sold')
    //         )
    //         ->leftJoin('san_pham as sp', 'sp.id_thuonghieu', '=', 'th.id')
    //         ->leftJoin('bienthe_sp as bt', 'bt.id_sanpham', '=', 'sp.id')
    //         ->leftJoin('chitiet_donhang as ct', 'ct.id_bienthe', '=', 'bt.id')
    //         ->groupBy('th.id', 'th.ten', 'th.mota', 'th.namthanhlap', 'th.media')
    //         ->orderByDesc('total_sold');

    //     // Nếu có per_page thì phân trang, ngược lại lấy hết
    //     if ($perPage) {
    //         $brands = $query->paginate($perPage);
    //     } else {
    //         $brands = $query->get();
    //     }

    //     return BrandsHotResource::collection($brands);
    // }

    /** SẢN PHẨM HÀNG ĐẦU */
    // protected function getBestProducts(Request $request)
    // {
    //     $perPage = $request->get('per_page', 8);

    //     $query = Sanpham::with(['anhSanPham', 'thuonghieu','danhgia','danhmuc','bienThe','loaibienthe'])
    //         ->withAvg('danhgia as avg_rating', 'diem')
    //         ->orderByDesc('avg_rating');

    //     $products = $query->paginate($perPage);

    //     return SanphamResources::collection($products);
    // }
    // GET /api/sanphams-selection?selection=best_products // limit 8 // nhiều đơn hàng của sản phẩm nhất và đánh giá
    protected function getBestProducts(Request $request)
    {
        $perPage = $request->get('per_page', 8);

        $query = SanPham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienThe', 'loaibienthe'])
            ->withAvg('danhgia as avg_rating', 'diem')
            ->withSum('chiTietDonHang as total_sold', 'soluong')
            ->orderByDesc('total_sold')
            ->orderByDesc('avg_rating');

        $products = $query->paginate($perPage);

        return BestProductResource::collection($products);
    }


    /** GỢI Ý */
    // protected function getRecommend(Request $request, $danhmucId = null)
    // {
    //     $perPage = $request->get('per_page', 8);

    //     $query = Sanpham::with(['anhSanPham', 'thuonghieu','danhgia','danhmuc','bienThe','loaibienthe']);

    //     if ($danhmucId) {
    //         $query->whereHas('danhmuc', fn($q) => $q->where('id_danhmuc', $danhmucId));
    //     }

    //     $products = $query->inRandomOrder()->paginate($perPage);

    //     return SanphamResources::collection($products);
    // }

    /** GỢI Ý */
    // tùy theo lược xem + giả cả rẻ + giảm giá
    protected function getRecommend(Request $request, $danhmucId = null)
    {
        $perPage = $request->get('per_page', 8);

        $query = SanPham::query()
            ->select('san_pham.*')
            ->leftJoin('bienthe_sp', 'san_pham.id', '=', 'bienthe_sp.id_sanpham')
            ->withAvg('danhgia as avg_rating', 'diem')
            ->withSum('chiTietDonHang as total_sold', 'soluong')
            ->orderByRaw('((bienthe_sp.gia - bienthe_sp.giagiam) / bienthe_sp.gia) DESC')
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_sold');

        $products = $query->paginate($perPage);

        return RecommentResource::collection($products);
    }




    /** Default: phân trang + filter */
    protected function getDefaultProducts(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);

        $query = Sanpham::query()->with([
            'bienThe.loaiBienThe',
            'anhSanPham',
            'danhmuc',
            'thuonghieu',
        ]);

        if ($request->filled('thuonghieu')) {
            $query->where('id_thuonghieu', (int) $request->thuonghieu);
        }

        if ($request->filled('danhmuc')) {
            $query->whereHas('danhmuc', fn($q) => $q->where('id_danhmuc', (int) $request->danhmuc));
        }

        if ($request->filled('gia_min')) {
            $query->whereHas('bienThe', fn($q) => $q->where('gia', '>=', (int) $request->gia_min));
        }

        if ($request->filled('gia_max')) {
            $query->whereHas('bienThe', fn($q) => $q->where('gia', '<=', (int) $request->gia_max));
        }

        $sanphams = $query->latest('updated_at')->paginate($perPage, ['*'], 'page', $currentPage);

        return SanphamResources::collection($sanphams);
    }
}


// namespace App\Http\Controllers\API\Frontend;

// use App\Http\Controllers\API\SanphamAPI;
// use Illuminate\Http\Request;
// use App\Http\Resources\SanphamResources;
// use App\Models\Sanpham;
// use App\Models\Danhmuc;
// use App\Models\Thuonghieu;
// use Illuminate\Http\Response;

// class SanPhamFrontendAPI extends SanphamAPI
// {
//     public function index(Request $request)
//     {
//         $selection = $request->get('selection');

//         switch ($selection) {
//             case 'hot_sales':
//                 $result = $this->getHotSales($request);
//                 break;
//             case 'top_categories':
//                 $result = $this->getTopCategories($request);
//                 break;
//             case 'top_brands':
//                 $result = $this->getTopBrands($request);
//                 break;
//             case 'best_products':
//                 $result = $this->getBestProducts($request);
//                 break;
//             case 'recommend':
//                 $result = $this->getRecommend($request, $request->get('danhmuc_id'));
//                 break;
//             default:
//                 $result = $this->getDefaultProducts($request);
//         }

//         return $this->jsonResponse([
//             'status'  => true,
//             'message' => 'Danh sách sản phẩm',
//             'data'    => $result
//         ], Response::HTTP_OK);
//     }

//     /** HOT SALES */
//     protected function getHotSales(Request $request)
//     {
//         $perPage = $request->get('per_page', 10);
//         $currentPage = $request->get('page', 1);

//         $query = Sanpham::with(['anhSanPham', 'thuonghieu'])
//             ->withSum('chiTietDonHang as total_sold', 'soluong')
//             ->orderByDesc('total_sold');

//         $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

//         return $this->formatResponse($products);
//     }

//     /** DANH MỤC HÀNG ĐẦU */
//     protected function getTopCategories(Request $request)
//     {
//         $perPage = $request->get('per_page', 5);
//         $categories = Danhmuc::withCount('sanpham')
//             ->orderByDesc('sanpham_count')
//             ->paginate($perPage);

//         return $this->formatResponse($categories, false);
//     }

//     /** THƯƠNG HIỆU HÀNG ĐẦU */
//     protected function getTopBrands(Request $request)
//     {
//         $perPage = $request->get('per_page', 5);
//         $brands = Thuonghieu::withCount('sanpham')
//             ->orderByDesc('sanpham_count')
//             ->paginate($perPage);

//         return $this->formatResponse($brands, false);
//     }

//     /** SẢN PHẨM HÀNG ĐẦU */
//     protected function getBestProducts(Request $request)
//     {
//         $perPage = $request->get('per_page', 10);
//         $currentPage = $request->get('page', 1);

//         $query = Sanpham::with(['anhSanPham', 'thuonghieu'])
//             ->withAvg('danhgia as avg_rating', 'sao')
//             ->orderByDesc('avg_rating');

//         $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

//         return $this->formatResponse($products);
//     }

//     /** GỢI Ý */
//     protected function getRecommend(Request $request, $danhmucId = null)
//     {
//         $perPage = $request->get('per_page', 8);
//         $currentPage = $request->get('page', 1);

//         $query = Sanpham::with(['anhSanPham', 'thuonghieu']);

//         if ($danhmucId) {
//             $query->whereHas('danhmuc', fn($q) => $q->where('id_danhmuc', $danhmucId));
//         }

//         $products = $query->inRandomOrder()
//             ->paginate($perPage, ['*'], 'page', $currentPage);

//         return $this->formatResponse($products);
//     }

//     /** Default: phân trang + filter */
//     protected function getDefaultProducts(Request $request)
//     {
//         $perPage = $request->get('per_page', 20);
//         $currentPage = $request->get('page', 1);

//         $query = Sanpham::query()->with([
//             'bienThe.loaiBienThe',
//             'anhSanPham',
//             'danhmuc',
//             'thuonghieu',
//         ]);

//         if ($request->filled('thuonghieu')) {
//             $query->where('id_thuonghieu', (int) $request->thuonghieu);
//         }

//         if ($request->filled('danhmuc')) {
//             $query->whereHas('danhmuc', fn($q) => $q->where('id_danhmuc', (int) $request->danhmuc));
//         }

//         if ($request->filled('gia_min')) {
//             $query->whereHas('bienThe', fn($q) => $q->where('gia', '>=', (int) $request->gia_min));
//         }

//         if ($request->filled('gia_max')) {
//             $query->whereHas('bienThe', fn($q) => $q->where('gia', '<=', (int) $request->gia_max));
//         }

//         $products = $query->latest('updated_at')
//             ->paginate($perPage, ['*'], 'page', $currentPage);

//         return $this->formatResponse($products);
//     }

//     /** Hàm dùng chung để format data + meta */
//     protected function formatResponse($paginator, $useResource = true)
//     {
//         $data = $useResource ? SanphamResources::collection($paginator) : $paginator->items();

//         return [
//             'data' => $data,
//             'meta' => [
//                 'current_page' => $paginator->currentPage(),
//                 'last_page'    => $paginator->lastPage(),
//                 'per_page'     => $paginator->perPage(),
//                 'total'        => $paginator->total(),
//             ]
//         ];
//     }
// }

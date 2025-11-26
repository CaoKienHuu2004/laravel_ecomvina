<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\Frontend\BaseFrontendController;
use App\Http\Controllers\Controller;
use App\Models\BaivietModel;
use App\Http\Resources\Web\BaiVietTrangChuResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DanhmucModel;
use App\Models\MagiamgiaModel;
use App\Models\QuangcaoModel;
use App\Models\QuatangsukienModel;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;
use App\Models\TukhoaModel;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class TrangChuWebAPI extends BaseFrontendController
{
    //
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

            'new_coupon' => $this->getNewCoupon($request),

            // 'recommend'      => $this->getRecommend($request, $request->get('danhmuc_id')), // bỏ phần recommend
            // Hàng mới chào sân, mới thêm vào hệ thống
            // Được quan tâm nhiều nhất, lượt xem cao nhất, mới thêm vào hệ thống
            // 'default'        => $this->getDefaultProducts($request),
            'new_launch'  => $this->getNewLaunch($request),
            'most_watched'  => $this->getMostWatChed($request),
            'posts_to_explore' => $this->getPostsToExplore($request),
        ];
        // return ($data); // nếu muốn { "ten_selection" : [ {} {} ... ] }
        $flatData = collect($data)->flatten(1)->values();

        return response()->json($flatData, 200); // Trả về mảng các đối tượng [ {}, {}, ... ]

    }

    protected function transformProducts($products)
    {
        if ($products->isEmpty()) {
            return [];
        }

        return $products->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->ten,
                'slug' => $item->slug,
                'have_gift' => $item->have_gift ?? false,
                'originalPrice' => (int) optional(
                    $item->bienthe->where('giagoc', '>', 0)->sortBy('giagoc')->first()
                )->giagoc,
                'discount' => (int) $item->giamgia,
                'sold' => (int) $item->total_sold,
                'rating' => round($item->avg_rating ?? 5, 1),
                'brand' => $item->thuonghieu->ten ?? null,
                'categories' => $item->danhmuc->pluck('ten')->toArray(),
                'image' => $item->hinhanhsanpham->first()->hinhanh ?? null,
            ];
        });
    }
    protected function transformGifts($gifts)
    {
        if ($gifts->isEmpty()) {
            return [];
        }

        return $gifts->map(function ($item) {
            // Tính thời gian còn lại
            $remainingDays = null;
            if ($item->ngayketthuc) {
                $diff = \Carbon\Carbon::parse($item->ngayketthuc)->diff(\Carbon\Carbon::now());
                $remainingDays = "Remaining {$diff->days} days {$diff->h} hours";
            }

            return [
                'id' => $item->id,
                'title' => $item->tieude,
                'slug'  => Str::slug($item->tieude),
                'condition' => $item->dieukien,
                'information' => $item->thongtin,
                'image' => $item->hinhanh,
                'views' => (int) $item->luotxem,
                'start_date' => $item->ngaybatdau,
                'end_date' => $item->ngayketthuc,
                'time_remaining' => $remainingDays,
                'program' => $item->chuongtrinh ? [
                    'id' => $item->chuongtrinh->id,
                    'title' => $item->chuongtrinh->tieude,
                    'image' => $item->chuongtrinh->hinhanh,
                ] : null,
            ];
        });
    }
    protected function transformCategoriesProducts($categories)
    {
        if ($categories->isEmpty()) {
            return [];
        }

        return $categories->map(function ($category) {
            // Transform danh sách sản phẩm trong mỗi danh mục
            $category->sanpham = $category->sanpham->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->ten,
                    'slug' => $item->slug,
                    'have_gift' => $item->have_gift ?? false,
                    'originalPrice' => (int)optional(
                        $item->bienthe->where('giagoc', '>', 0)->sortBy('giagoc')->first()
                    )->giagoc,
                    'discount' => (int)$item->giamgia,
                    'sold' => (int)$item->total_sold,
                    'rating' => round($item->avg_rating ?? 5, 1),
                    'brand' => $item->thuonghieu->ten ?? null,
                    'categories' => $item->danhmuc->pluck('ten')->toArray(),
                    'image' => $item->hinhanhsanpham->first()->hinhanh ?? null,
                ];
            });

            // Trả về danh mục (đã có sanpham transform)
            return [
                'id' => $category->id,
                'name' => $category->ten,
                'slug' => $category->slug,
                'total_sold' => $category->total_sold,
                'products' => $category->sanpham,
            ];
        });
    }
    protected function transformBrands($brands)
    {
        if ($brands->isEmpty()) {
            return [];
        }

        return $brands->map(function ($brand) {
            return [
                'id' => $brand->id,
                'name' => $brand->ten,
                'slug' => $brand->slug,
                'logo' => $brand->logo,
                'description' => $brand->mota,
                'total_sold' => $brand->total_sold,
            ];
        });
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
            ->withExists([
                'bienthe as have_gift' => function ($query) {
                    $query->whereHas('quatangsukien', function ($q) {
                        $q->where('trangthai', 'Hiển thị')
                        ->whereDate('ngaybatdau', '<=', now())
                        ->whereDate('ngayketthuc', '>=', now())
                        ->whereNull('deleted_at');
                    });
                }
            ])
            ->orderByRaw('COALESCE((SELECT giagoc
                        FROM bienthe
                        WHERE id_sanpham = sanpham.id
                        ORDER BY giagoc DESC LIMIT 1), 0) DESC')
            ->orderByDesc('total_sold'); // ưu tiên hot sales

        $products = $query->paginate($perPage);
        //     dd($query);
        // exit();
        // Trả về resource cho frontend //

       $products = $this->transformProducts($products);

        return $products;
    }

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
        $gifts=  $this->transformGifts($gifts);

        return $gifts;
    }

    protected function getTopCategories(Request $request)
    {
        /** 🔥 DANH MỤC HÀNG ĐẦU DỰA THEO LUOTBAN CỦA BIẾN THỂ */
        /** DANH MỤC HÀNG ĐẦU */ //-------------------------------- + nhiều đơn hàng của sản phẩm nhất , UI chỉ có 6 limmit danh mục con, All là 4 limmit //
        $categoryLimit = $request->get('per_page', 5); //ban đầu là 6
        $productLimit = 12; // ban đầu là 6

        $categories = DanhmucModel::all();

        $categories->load(['sanpham' => function ($q) use ($productLimit) {
            $q->withAvg('danhgia as avg_rating', 'diem')
            ->withCount('danhgia as review_count')
            ->with(['hinhanhsanpham', 'thuonghieu', 'bienthe', 'loaibienthe'])
            ->withExists([
                'bienthe as have_gift' => function ($query) {
                    $query->whereHas('quatangsukien', function ($q) {
                        $q->where('trangthai', 'Hiển thị')
                            ->whereDate('ngaybatdau', '<=', now())
                            ->whereDate('ngayketthuc', '>=', now())
                            ->whereNull('deleted_at');
                    });
                }
            ]);
        }]);

        // Tính tổng lượt bán và sắp xếp
        $categories = $categories->map(function ($danhmuc) use ($productLimit) {
            $danhmuc->total_sold = $danhmuc->sanpham->reduce(function ($carry, $product) {
                return $carry + $product->bienthe->sum('luotban');
            }, 0);

            // Sắp xếp sản phẩm theo lượt bán, lấy 6 sản phẩm đầu
            $danhmuc->sanpham = $danhmuc->sanpham
                ->sortByDesc(fn($product) => $product->bienthe->sum('luotban'))
                ->take($productLimit)
                ->values();

            return $danhmuc;
        });

        // Sắp xếp danh mục theo tổng lượt bán, lấy $categoryLimit
        $categories = $categories->sortByDesc('total_sold')->take($categoryLimit)->values();

        // Biến đổi dữ liệu nếu cần
        $categories = $this->transformCategoriesProducts($categories);

        return $categories;
        // sql thuần kiểm tra:
        // SELECT d.id AS id_danhmuc, d.ten AS danhmuc_ten, COALESCE(SUM(bt.luotban), 0) AS total_sold FROM danhmuc d LEFT JOIN danhmuc_sanpham sd ON sd.id_danhmuc = d.id LEFT JOIN sanpham sp ON sp.id = sd.id_sanpham LEFT JOIN bienthe bt ON bt.id_sanpham = sp.id GROUP BY d.id, d.ten ORDER BY total_sold DESC LIMIT 25;
    }
    protected function getTopBrands(Request $request)
    {
        /** 🔥 THƯƠNG HIỆU HÀNG ĐẦU DỰA THEO LUOTBAN CỦA BIẾN THỂ */
        //--------------------------- limit 10 // nhiều đơn hàng của sản phẩm nhất // list danh sách thuong hieu ko phải sản phẩm

        $perPage = $request->get('per_page', 5); // ban đầu là 10

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
          $brands =  $this->transformBrands($brands);

        return $brands;
    }
    protected function getBestProducts(Request $request)
    {
        // @OA\Items(ref="#/components/schemas/HotSaleResource")
        // v1 GET /api/sanphams-selection?selection=best_products // limit 8 // nhiều đơn hàng của sản phẩm nhất và đánh giá
        // v2 từ 4 -5 sao trở lên, bán chạy uy tín
        $perPage = $request->get('per_page', 10);

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
            ->withExists([
                'bienthe as have_gift' => function ($query) {
                    $query->whereHas('quatangsukien', function ($q) {
                        $q->where('trangthai', 'Hiển thị')
                        ->whereDate('ngaybatdau', '<=', now())
                        ->whereDate('ngayketthuc', '>=', now())
                        ->whereNull('deleted_at');
                    });
                }
            ])
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
        $products = $this->transformProducts($products);

        return $products;
    }
    protected function getNewBanners(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $banners = QuangcaoModel::where('trangthai', 'Hiển thị')
            ->orderByDesc('id') // Mới nhất trước
            ->limit($perPage)
            ->get(['id', 'vitri', 'hinhanh', 'lienket', 'mota', 'trangthai']);

        return $banners;
        // return $banners->toArray()['data'];
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
            $item->lienket = url('/api-tim-kiem/?query=' . urlencode($item->tukhoa));
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
            ->withExists([
                'bienthe as have_gift' => function ($query) {
                    $query->whereHas('quatangsukien', function ($q) {
                        $q->where('trangthai', 'Hiển thị')
                        ->whereDate('ngaybatdau', '<=', now())
                        ->whereDate('ngayketthuc', '>=', now())
                        ->whereNull('deleted_at');
                    });
                }
            ])
            ->orderByDesc('id');

        $products = $query->paginate($perPage);
        //     dd($query);
        // exit();
        // Trả về resource cho frontend //

          $products =$this->transformProducts($products);

        return $products;
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
            ->withExists([
                'bienthe as have_gift' => function ($query) {
                    $query->whereHas('quatangsukien', function ($q) {
                        $q->where('trangthai', 'Hiển thị')
                        ->whereDate('ngaybatdau', '<=', now())
                        ->whereDate('ngayketthuc', '>=', now())
                        ->whereNull('deleted_at');
                    });
                }
            ])
            ->orderByDesc('luotxem');

        $products = $query->paginate($perPage);
        //     dd($query);
        // exit();
        // Trả về resource cho frontend //

         $products = $this->transformProducts($products);

        return $products;
    }

    public function getNewCoupon(Request $request)
    {
        /** 🎁 MÃ GIẢM GIÁ MỚI NHẤT */
        $perPage = $request->get('per_page', 10);
        $query = MagiamgiaModel::whereNull('deleted_at')
            ->where('trangthai', 'Hoạt động')
            ->orderByDesc('id');
        if ($q = $request->get('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('magiamgia', 'LIKE', "%$q%")
                    ->orWhere('dieukien', 'LIKE', "%$q%");
            });
        }
        $coupon = $query->limit($perPage)->get();

        return $coupon;
    }

    public function getPostsToExplore(Request $request)
    {
        // limt 4 theo bài viết mới nhất
        $perPage = $request->get('per_page', 4);

        $query = BaivietModel::where('trangthai', 'Hiển thị')
                ->orderBy('id', 'desc');

        $posts = $query->limit($perPage)->get();

        BaiVietTrangChuResource::withoutWrapping();
        return BaiVietTrangChuResource::collection($posts);
    }


}

<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\QuatangAllResource;
use App\Http\Resources\Frontend\QuatangResource;
use App\Http\Resources\Frontend\SanphamCoQuatangCoBientheDeThemVaoGioResource;
use App\Models\QuatangsukienModel;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuatangAllFrontendAPI extends BaseFrontendController
{


    /**
     * @OA\Get(
     *     path="/api/quatangs-all",
     *     summary="Lấy danh sách quà tặng với bộ lọc phổ biến, mới nhất, sắp hết hạn, nhà cung cấp",
     *     tags={"Quà Tặng"},
     *
     *     @OA\Parameter(
     *         name="popular",
     *         in="query",
     *         description="Lọc theo quà tặng phổ biến. Giá trị: 'popular'",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="newest",
     *         in="query",
     *         description="Lọc theo quà tặng mới nhất. Giá trị: 'newest'",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="expiring",
     *         in="query",
     *         description="Lọc theo quà tặng sắp hết hạn. Giá trị: 'expiring'",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="provider",
     *         in="query",
     *         description="Lọc theo ID nhà cung cấp (provider)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách quà tặng trả về kèm bộ lọc và phân trang",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="id_bienthe", type="integer", example=16),
     *                     @OA\Property(property="id_chuongtrinh", type="integer", example=22),
     *
     *                     @OA\Property(
     *                         property="thongtin_thuonghieu",
     *                         type="object",
     *                         @OA\Property(property="id_thuonghieu", type="integer", example=1),
     *                         @OA\Property(property="ten_thuonghieu", type="string", example="Trung Tâm Bán Hàng Siêu Thị Vina"),
     *                         @OA\Property(property="slug_thuonghieu", type="string", example="trung-tam-ban-hang-sieu-thi-vina"),
     *                         @OA\Property(property="logo_thuonghieu", type="string", format="url", example="http://148.230.100.215/assets/client/images/brands/trung-tam-ban-hang-sieu-thi-vina.png")
     *                     ),
     *
     *                     @OA\Property(property="dieukiensoluong", type="integer", example="2"),
     *                     @OA\Property(property="dieukiengiatri", type="integer", example="2000"),
     *                     @OA\Property(property="tieude", type="string", example="Siêu thị Vina đón trung thu 6/10"),
     *                     @OA\Property(property="slug", type="string", example="sieu-thi-vina-don-trung-thu-6-10"),
     *                     @OA\Property(property="thongtin", type="string", example="Siêu thị Vina đón trung thu 6/10 với không khí rộn ràng, ngập tràn sắc màu v..."),
     *                     @OA\Property(property="hinhanh", type="string", format="url", example="http://148.230.100.215/assets/client/images/thumbs/sieu-thi-vina-don-trung-thu-6-10.jpg"),
     *                     @OA\Property(property="luotxem", type="integer", example=0),
     *                     @OA\Property(property="ngaybatdau", type="string", format="date", example="2025-10-01"),
     *                     @OA\Property(property="thoigian_conlai", type="integer", example=2),
     *                     @OA\Property(property="ngayketthuc", type="string", format="date", example="2025-11-30"),
     *                     @OA\Property(property="trangthai", type="string", example="Hiển thị")
     *                 )
     *             ),
     *
     *             @OA\Property(
     *                 property="filters",
     *                 type="object",
     *                 @OA\Property(
     *                     property="popular",
     *                     type="object",
     *                     @OA\Property(property="label", type="string", example="Phổ biến"),
     *                     @OA\Property(property="param", type="string", example="popular"),
     *                     @OA\Property(property="value", type="string", example="popular")
     *                 ),
     *                 @OA\Property(
     *                     property="newest",
     *                     type="object",
     *                     @OA\Property(property="label", type="string", example="Mới nhất"),
     *                     @OA\Property(property="param", type="string", example="newest"),
     *                     @OA\Property(property="value", type="string", example="newest")
     *                 ),
     *                 @OA\Property(
     *                     property="expiring",
     *                     type="object",
     *                     @OA\Property(property="label", type="string", example="Sắp hết hạn"),
     *                     @OA\Property(property="param", type="string", example="expiring"),
     *                     @OA\Property(property="value", type="string", example="expiring")
     *                 ),
     *                 @OA\Property(
     *                     property="thuonghieus",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Trung Tâm Bán Hàng Siêu Thị Vina")
     *                     )
     *                 )
     *             ),
     *
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=5),
     *                 @OA\Property(property="total", type="integer", example=2)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $popular = $request->get('popular', null);
        $newest = $request->get('newest', null);
        $expiring = $request->get('expiring', null);
        $provider = $request->get('provider', null);
        $limit = 5; // đang theo Khải


        $quatangs = QuatangsukienModel::query()
                ->with([
                'bienthe',
                'bienthe.sanpham',
                'bienthe.sanpham.thuonghieu'
            ]);
        $hasFilter = false;
        if ($popular) {
            if($popular === "popular"){
                $fromDate = now()->subDays(4)->toDateString(); // 4 vì dưới nó là 2,3,4 suy ra đây phải là 4 // hên xui
                // $fromDate = now()->subDays(7)->toDateString();
                $toDate = now()->toDateString();
                $quatangs->whereDate('ngaybatdau', '<=', $toDate)
                        ->whereDate('ngayketthuc', '>=', $fromDate);
                $quatangs->orderBy('luotxem', 'desc');
            }
            $hasFilter = true;
        }
        if ($newest) {
            if($newest === "newest"){
                $quatangs->orderBy('id', 'desc');
            }
            $hasFilter = true;
        }
        if ($expiring) {
            if($expiring === "expiring"){
                $today = now()->toDateString(); // YYYY-MM-DD
                $soon = now()->addDays(4)->toDateString(); // 4 vì dưới nó là 2,3,4 suy ra đây phải là 4// hên xui
                $quatangs->where(function ($query) use ($today, $soon) {
                    $query->whereDate('ngayketthuc', '>=', $today)  // chưa hết hạn
                        ->whereDate('ngayketthuc', '<=', $soon); // sắp tới hạn
                });
                // 🔥 Sắp xếp theo ngày kết thúc gần nhất → xa nhất
                $quatangs->orderBy('ngayketthuc', 'desc'); // đang theo Khải 2 ngày 3 ngày 4 ngày
                // 🔥 Sắp xếp theo ngày kết thúc xa nhất → gần nhất
                // $quatangs->orderBy('ngayketthuc', 'asc');
            }
            $hasFilter = true;
        }
        if ($provider) {
            $quatangs->whereHas('bienthe.sanpham.thuonghieu', function ($query) use ($provider) {
                $query->where('id', $provider);
            });
            $quatangs->orderBy('id', 'desc');
            $hasFilter = true;
        }
        if (!$hasFilter) {
            // $today = now()->toDateString();
            // $soon = now()->addDays(4)->toDateString();
            // $quatangs->where(function ($query) use ($today, $soon) {
            //     $query->whereDate('ngayketthuc', '>=', $today)
            //         ->whereDate('ngayketthuc', '<=', $soon);
            // });
            // $quatangs->orderBy('ngayketthuc', 'asc'); // đang theo Khải 4 ngày 2 ngày 1 ngày

            $quatangs->orderBy('id', 'desc');
        }

        $result = $quatangs->paginate($limit);

        $filterMenu = $this->getMenuFilterAsideInQuaTang();

        return $this->jsonResponse([
            'data' => QuatangAllResource::collection($result->items()),
            'filters' => $filterMenu,
            'pagination' => [
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
            ],
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/quatangs-all/{id}",
     *     summary="Lấy chi tiết một quà tặng theo ID hoặc slug",
     *     tags={"Quà Tặng"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID số hoặc slug dạng chuỗi của quà tặng",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết quà tặng cùng danh sách sản phẩm có quà",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="id_bienthe", type="integer", example=17),
     *                 @OA\Property(property="id_chuongtrinh", type="integer", example=1),
     *
     *                 @OA\Property(
     *                     property="thongtin_thuonghieu",
     *                     type="object",
     *                     @OA\Property(property="id_thuonghieu", type="integer", example=1),
     *                     @OA\Property(property="ten_thuonghieu", type="string", example="Trung Tâm Bán Hàng Siêu Thị Vina"),
     *                     @OA\Property(property="slug_thuonghieu", type="string", example="trung-tam-ban-hang-sieu-thi-vina"),
     *                     @OA\Property(property="logo_thuonghieu", type="string", format="url", example="http://148.230.100.215/assets/client/images/brands/trung-tam-ban-hang-sieu-thi-vina.png")
     *                 ),
     *
     *                 @OA\Property(property="dieukiensoluong", type="integer", example="3"),
     *                 @OA\Property(property="dieukiengiatri", type="integer", example="3000"),
     *                 @OA\Property(property="tieude", type="string", example="Tặng 1 sản phẩm bách hóa khi mua 3 sản phẩm bất kỳ từ Trung Tâm Bán Hàng nhân ngày sinh nhật 13/10"),
     *                 @OA\Property(property="thongtin", type="string", example="Không có thông tin"),
     *                 @OA\Property(property="hinhanh", type="string", format="url", example="http://148.230.100.215/assets/client/images/thumbs/nuoc-rua-bat-bio-formula-bo-va-lo-hoi-tui-500ml-1.webp"),
     *                 @OA\Property(property="luotxem", type="integer", example=1206),
     *                 @OA\Property(property="ngaybatdau", type="string", format="date", example="2025-10-13"),
     *                 @OA\Property(property="thoigian_conlai", type="integer", example=33),
     *                 @OA\Property(property="ngayketthuc", type="string", format="date", example="2025-12-31"),
     *                 @OA\Property(property="trangthai", type="string", example="Hiển thị"),
     *
     *                 @OA\Property(
     *                     property="bienthe_quatang",
     *                     type="object",
     *                     @OA\Property(property="ten_bienthe_quatang", type="string", example="Hạt điều rang muối loại 1 (còn vỏ lụa) Happy Nuts 500g"),
     *                     @OA\Property(property="ten_loaibienthe_quatang", type="string", example="Hộp (Vỏ lụa) 500g"),
     *                     @OA\Property(property="slug_bienthe_quatang_sanpham", type="string", example="hat-dieu-rang-muoi-loai-1-con-vo-lua-happy-nuts-500g"),
     *                     @OA\Property(property="hinhanh", type="string", example="http://148.230.100.215/assets/client/images/thumbs/hat-dieu-rang-muoi-loai-1-con-vo-lua-happy-nuts-500g-3.webp"),
     *                     @OA\Property(property="soluong", type="integer", example=1)
     *                 )
     *             ),
     *
     *             @OA\Property(
     *                 property="sanpham_coqua",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="ten", type="string", example="Sâm Ngọc Linh trường sinh đỏ (Thùng 24lon)"),
     *                     @OA\Property(property="slug", type="string", example="sam-ngoc-linh-truong-sinh-do-thung-24lon"),
     *                     @OA\Property(property="have_gift", type="boolean", example=true),
     *                     @OA\Property(property="hinh_anh", type="string", format="url", example="http://148.230.100.215/assets/client/images/thumbs/sam-ngoc-linh-truong-sinh-do-thung-24lon-5.webp"),
     *
     *                     @OA\Property(
     *                         property="rating",
     *                         type="object",
     *                         @OA\Property(property="average", type="number", example=0),
     *                         @OA\Property(property="count", type="integer", example=0)
     *                     ),
     *
     *                     @OA\Property(property="luotxem", type="integer", example=2),
     *
     *                     @OA\Property(
     *                         property="sold",
     *                         type="object",
     *                         @OA\Property(property="total_sold", type="integer", example=23),
     *                         @OA\Property(property="total_quantity", type="integer", example=10)
     *                     ),
     *
     *                     @OA\Property(
     *                         property="gia",
     *                         type="object",
     *                         @OA\Property(property="current", type="number", example=466560),
     *                         @OA\Property(property="before_discount", type="number", example=466560),
     *                         @OA\Property(property="discount_percent", type="integer", example=0)
     *                     ),
     *
     *                     @OA\Property(
     *                         property="trangthai",
     *                         type="object",
     *                         @OA\Property(property="active", type="string", example="Công khai"),
     *                         @OA\Property(property="in_stock", type="boolean", example=true)
     *                     ),
     *
     *                     @OA\Property(property="id_bienthe_de_them_vao_gio", type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy quà tặng"
     *     )
     * )
     */
    public function getMenuFilterAsideInQuaTang()
    {
        $now = now()->toDateString();

        $thuonghieus = ThuongHieuModel::whereHas('sanpham.bienthe.quatangsukien', function ($query) use ($now) {
            $query->where('trangthai', 'Hiển thị')
                ->whereDate('ngaybatdau', '<=', $now)
                ->whereDate('ngayketthuc', '>=', $now)
                ->whereNull('deleted_at');
        })
        ->get(['id', 'ten']);
        // ->get(['id', 'ten', 'slug']);

        $expiring = ['label' => 'Sắp hết hạn','param' => 'expiring','value' => 'expiring'];
        $newest = ['label' => 'Mới nhất','param' => 'newest','value' => 'newest'];
        $popular = ['label' => 'Phổ biến','param' => 'popular','value' => 'popular'];

        return ([
            'popular' => $popular,
            'newest' => $newest,
            'expiring' => $expiring,
            'thuonghieus' => $thuonghieus,
        ]);
    }


    public function show(string $id)
    {
        if (is_numeric($id)) {
            // $quatang = QuatangsukienModel::where('id', $id)->first(); // firstOrFail() 404 luôn
            $quatang = QuatangsukienModel::with([
                'bienthe',
                'bienthe.sanpham',
                'bienthe.loaibienthe',
                'bienthe.sanpham.hinhanhsanpham',
                'bienthe.sanpham.thuonghieu'
            ])->where('id', $id)->first();
        } else {
            // Nếu $id không phải số → xem nó là slug

            $slug = $id;
            // $quatang = QuatangsukienModel::get()
            // ->first(function ($item) use ($slug) {
            //     return Str::slug($item->tieude) === $slug;
            // });
            $quatang = QuatangsukienModel::with([
                'bienthe',
                'bienthe.sanpham',
                'bienthe.loaibienthe',
                'bienthe.sanpham.hinhanhsanpham',
                'bienthe.sanpham.thuonghieu'
            ])->get()->first(function ($item) use ($slug) {
                return $item->slug === $slug;
            });
        }

        if (!$quatang) {
            return $this->error('Không tìm thấy quà tặng', [], 404);
        }

        $quatang->increment('luotxem');


        $sanphamCoQua = SanphamModel::whereHas('bienthe.quatangsukien', function ($q) {
                $q->where('trangthai', 'Hiển thị')
                ->whereDate('ngaybatdau', '<=', now())
                ->whereDate('ngayketthuc', '>=', now())
                ->whereNull('deleted_at');
            })
            ->with([
                'hinhanhsanpham',
                'thuonghieu',
                'danhgia',
                'danhmuc',
                'bienthe',
                'loaibienthe',
                'bienthe.loaibienthe',
                'bienthe.sanpham'
            ])
            ->withSum('bienthe as total_sold', 'luotban')
            ->withSum('bienthe as total_quantity', 'soluong')
            ->withAvg('danhgia as avg_rating', 'diem')
            ->withCount('danhgia as review_count')
            ->withExists(['bienthe as have_gift' => function ($query) {
                $query->whereHas('quatangsukien', function ($q) {
                    $q->where('trangthai', 'Hiển thị')
                    ->whereDate('ngaybatdau', '<=', now())
                    ->whereDate('ngayketthuc', '>=', now())
                    ->whereNull('deleted_at');
                });
            }])
            ->limit(5)
            ->get();

        return $this->jsonResponse([
            'data' => new QuatangResource($quatang),
            'sanpham_coqua' => SanphamCoQuatangCoBientheDeThemVaoGioResource::collection($sanphamCoQua),
        ]);
    }
}

<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\SanphamAPI;
use App\Http\Resources\Frontend\SanPhamAllDetailResources;
use App\Http\Resources\Frontend\SanPhamAllResources;
use App\Models\DanhmucModel;
use App\Models\SanPham;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Trang Tất Cả Sản phẩm (khi click vào nút xem tất cả)",
 *     description="Các API hiển thị danh sách và chi tiết sản phẩm cho Trang tất cả sản phẩm"
 * )
 */
class SanPhamAllFrontendAPI extends BaseFrontendController
{
    public function index(Request $request)
    {
        //
         $filtering = $request->get('filter');

        switch ($filtering) {
            //Dựa trên sự tương tác/lượt xem cao nhất trong một khoảng thời gian nhất định.
            case 'popular':
                $data = $this->getPopular($request);
                break;
            //Dựa trên thời gian tạo hoặc cập nhật gần nhất (Timestamp).
            case 'latest':
                $data = $this->getLatest($request);
                break;
            //Dựa trên tốc độ tăng trưởng tương tác/lượt xem gần đây.
            case 'trending':
                $data = $this->getTrending($request);
                break;
            //Dựa trên mức độ liên quan đến tìm kiếm hoặc sở thích cá nhân.
            case 'matches':
                $data = $this->getMatches($request);
                break;
            default:
                $data = $this->getDefaultProducts($request);
        }
        $filterMenu = $this->getMenuFilterAside();

        return response()->json([
            'status'  => true,
            'message' => 'Danh sách sản phẩm',
            'filters' => $filterMenu,
            'data'    => SanPhamAllResources::collection($data),
        ]);


        // return SanPhamAllResources::collection($data);
        // return $this->jsonResponse([
        //     'status'  => true,
        //     'message' => 'Danh sách sản phẩm',
        //     'data'    => $data
        // ], Response::HTTP_OK);
    }





    protected function getPopular(Request $request)
    {
        //----------------  limit 20 //Dựa trên sự tương tác/lượt xem cao nhất trong một khoảng thời gian nhất định.
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng bán
            ->withSum('bienthe as total_sold', 'luotban')
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
                $q->orderByDesc('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);
        // Sắp xếp:  rồi giagiam, rồi số lượng bán, rồi lượt xem
        // $query->orderByRaw('COALESCE((SELECT gia - giagiam FROM bienthe
        //                             WHERE id_sanpham = san_pham.id
        //                             ORDER BY uutien ASC LIMIT 1), 0) ASC')
            // $query->orderByRaw('COALESCE((SELECT giamgia FROM sanpham
            //                         WHERE id_sanpham = sanpham.id
            //                         ORDER BY uutien ASC LIMIT 1), 0) DESC')
            $query->orderByDesc('giamgia')
            ->orderByDesc('total_sold')
            ->orderByDesc('luotxem'); // thêm lượt xem để tính "phổ biến"

        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    //--------------------------------   limit 20 // Dựa trên thời gian tạo hoặc cập nhật gần nhất (Timestamp)(model đẫ ép kiểu datetime).
    protected function getLatest(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienthe as total_quantity', 'soluong')      // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình đánh giá
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienthe' => function ($q) {
                $q->orderByDesc('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);

        // 🔥 Sắp xếp theo thời gian mới nhất (updated_at trước, rồi created_at)
        // $query->orderByDesc('updated_at')
        //     ->orderByDesc('created_at');
        $query->latest('id'); // id tăng dần theo thời gian tạo và cập nhật gần nhất

        // Giới hạn 20 sản phẩm
        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    //--------------------------- limit 20 //Dựa trên tốc độ tăng trưởng tương tác/lượt xem gần đây.
    protected function getTrending(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        // khoảng thời gian để tính "gần đây" (vd: 7 ngày qua)
        // $days = $request->get('days', 7);
        // $fromDate = now()->subDays($days);

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienthe as total_quantity', 'soluong')      // tổng tồn kho
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienthe' => function ($q) {
                $q->orderByDesc('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }])
            // chỉ lấy sản phẩm được cập nhật gần đây
            ->latest('id') //->where('updated_at', '>=', $fromDate)

            // 🔥 sắp xếp theo lượt xem giảm dần (gần đây)
            ->orderByDesc('luotxem');

        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    //--------------------------- limit 20 //Dựa trên mức độ liên quan đến tìm kiếm hoặc sở thích cá nhân.
    protected function getMatches(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm
        $userId      = $request->get('user_id'); // giả sử có user_id để gợi ý theo sở thích

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienthe as total_quantity', 'soluong')      // tổng tồn kho
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->with(['bienthe' => function ($q) {
                $q->orderByDesc('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);

        // 🔎 Nếu có từ khóa tìm kiếm
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('mota', 'like', "%$q%");
            })
            // Thêm điểm relevance để ưu tiên tên hơn mô tả
            ->selectRaw("
                sanpham.*,
                (CASE
                    WHEN ten LIKE ? THEN 3
                    WHEN mota LIKE ? THEN 1
                    ELSE 0
                END) as relevance
            ", ["%$q%", "%$q%"])
            ->orderByDesc('relevance')
            ->orderByDesc('luotxem');
        }
        // ❤️ Nếu không có q mà có user_id → gợi ý theo sản phẩm yêu thích
        elseif ($userId) {
            $query->whereIn('id', function($sub) use ($userId) {
                $sub->select('id_sanpham')
                    ->from('yeuthich')
                    ->where('id_nguoidung', $userId);
            })
            ->latest('id');
        }
        // fallback: nếu không có cả q và user_id → lấy ngẫu nhiên
        else {
            $query->inRandomOrder();
        }

        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }


    /**
     * @OA\Get(
     *     path="/api/sanphams-all",
     *     tags={"Tất Cả Sản phẩm (Trang Tất Cả Sản Phẩm)"},
     *     summary="Danh sách sản phẩm (phân trang + tìm kiếm + bộ lọc + lọc giá + thương hiệu + danh mục)",
     *     description="
     *     ✅ Lấy danh sách sản phẩm kèm phân trang, có thể lọc theo:
     *     - Từ khóa tìm kiếm (`q`)
     *     - Thương hiệu (`thuonghieu` - slug)
     *     - Danh mục (`danhmuc` - slug)
     *     - Khoảng giá (`locgia` - mã khoảng giá)
     *     - Bộ lọc nhanh (`filter`): popular, latest, trending, matches
     *
     *     Mã khoảng giá (locgia):
     *     - `to100` → Dưới 100.000đ
     *     - `to200` → 100.000đ - 200.000đ
     *     - `to300` → 200.000đ - 300.000đ
     *     - `to500` → 300.000đ - 500.000đ
     *     - `to700` → 500.000đ - 700.000đ
     *     - `to1000` → 700.000đ - 1.000.000đ
     *     - `high1000` → Trên 1.000.000đ
     *     ",
     *
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Bộ lọc nhanh:
     *         - popular: sản phẩm được xem nhiều nhất
     *         - latest: sản phẩm mới nhất
     *         - trending: sản phẩm đang hot
     *         - matches: sản phẩm gợi ý phù hợp",
     *         required=false,
     *         @OA\Schema(type="string", enum={"popular","latest","trending","matches"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số lượng sản phẩm mỗi trang (mặc định 20)",
     *         required=false,
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Số trang hiện tại (mặc định 1)",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Từ khóa tìm kiếm theo tên hoặc mô tả sản phẩm",
     *         required=false,
     *         @OA\Schema(type="string", example="bánh quy")
     *     ),
     *     @OA\Parameter(
     *         name="thuonghieu",
     *         in="query",
     *         description="Slug thương hiệu cần lọc",
     *         required=false,
     *         @OA\Schema(type="string", example="oreo")
     *     ),
     *     @OA\Parameter(
     *         name="danhmuc",
     *         in="query",
     *         description="Slug danh mục cần lọc",
     *         required=false,
     *         @OA\Schema(type="string", example="banh-keo")
     *     ),
     *     @OA\Parameter(
     *         name="locgia",
     *         in="query",
     *         description="Lọc theo khoảng giá (xem bảng mã ở trên)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"to100","to200","to300","to500","to700","to1000","high1000"}, example="to500")
     *     ),
        *     @OA\Parameter(
        *         name="sortby",
        *         in="query",
        *         description="Tham số sắp xếp sản phẩm theo các tiêu chí đặc biệt:
        *             - `topdeals`: sản phẩm có giảm giá cao nhất
        *             - `top-bach-hoa`: sản phẩm thuộc danh mục 'bách hoá'
        *             - `latest`: sản phẩm mới nhất
        *             - `quantamnhieunhat`: sản phẩm được quan tâm (xem) nhiều nhất
        *         ",
        *         required=false,
        *         @OA\Schema(
        *             type="string",
        *             enum={"topdeals", "top-bach-hoa", "latest", "quantamnhieunhat"},
        *             example="latest"
        *         )
        *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm trả về thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách sản phẩm"),
     *             @OA\Property(
     *                 property="filters",
     *                 type="object",
     *                 description="Bộ lọc hiển thị bên phải giao diện (menu aside)",
     *                 @OA\Property(
     *                     property="danhmucs",
     *                     type="array",
     *                     description="Danh sách danh mục có số lượng sản phẩm tương ứng",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Bánh kẹo"),
     *                         @OA\Property(property="slug", type="string", example="banh-keo"),
     *
     *                         @OA\Property(property="tong_sanpham", type="integer", example=25)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="thuonghieus",
     *                     type="array",
     *                     description="Danh sách thương hiệu có sản phẩm",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="ten", type="string", example="Oreo"),
     *                         @OA\Property(property="slug", type="string", example="oreo")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="price_ranges",
     *                     type="array",
     *                     description="Các khoảng giá khả dụng cho bộ lọc",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="label", type="string", example="300.000đ - 500.000đ"),
     *                         @OA\Property(property="min", type="integer", example=300000),
     *                         @OA\Property(property="max", type="integer", example=500000),
     *                         @OA\Property(property="value", type="integer", example="to500")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Danh sách sản phẩm",
     *                 @OA\Items(ref="#/components/schemas/SanPhamAllResources")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Tham số không hợp lệ hoặc lỗi khi truy vấn"
     *     )
     * )
     */
    protected function getDefaultProducts(Request $request)
    {
        /** Default: phân trang + filter + q + param lọc danhmuc,thuonghieu,locgia theo string covert về number */
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanphamModel::with([
            'hinhanhsanpham',
            'thuonghieu',
            'danhgia',
            'danhmuc',
            'bienthe',
            'loaibienthe',
            'bienthe.loaibienthe',
            'bienthe.sanpham',
        ])
        ->withAvg('danhgia as avg_rating', 'diem')       // điểm trung bình
        ->withCount('danhgia as review_count')           // tổng số đánh giá
        ->withSum('bienthe as total_quantity', 'soluong') // tổng tồn kho
        ->withSum('bienthe as total_sold', 'luotban');

        // --- Tìm kiếm theo tên hoặc mô tả ---
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('mota', 'like', "%$q%");
            });
        }

        // --- Filter thương hiệu ---
        if ($request->filled('thuonghieu')) {
            $query->whereHas('thuonghieu', fn($q) => $q->where('slug', $request->thuonghieu));
        }

        // --- Filter danh mục ---
        if ($request->filled('danhmuc')) {
            $query->whereHas('danhmuc', fn($q) => $q->where('slug', $request->danhmuc));
        }

        // --- Filter giá ---
        if ($request->filled('locgia')) {
            $mapGia = [
                'to100'    => [null, 100000],
                'to200'    => [100000, 200000],
                'to300'    => [200000, 300000],
                'to500'    => [300000, 500000],
                'to700'    => [500000, 700000],
                'to1000'   => [700000, 1000000],
                'high1000' => [1000000, null],
            ];

            $giaMin = $mapGia[$request->locgia][0] ?? null;
            $giaMax = $mapGia[$request->locgia][1] ?? null;

            $query->whereHas('bienthe', function ($q) use ($giaMin, $giaMax) {
                if (!is_null($giaMin)) {
                    $q->where('giagoc', '>=', $giaMin);
                }
                if (!is_null($giaMax)) {
                    $q->where('giagoc', '<=', $giaMax);
                }
            });
        }

        if ($request->filled('sortby')) {
            switch ($request->sortby) {
                case 'topdeals':
                    // Sản phẩm có giảm giá cao nhất → giamgia giảm dần
                    $query->orderByDesc('giamgia')
                        ->orderByDesc('total_sold')
                        ->orderByDesc('avg_rating');
                    break;

                case 'top-bach-hoa':
                    // Giả sử đây là danh mục đặc biệt (bạn có thể thay slug cụ thể)
                    $query->whereHas('danhmuc', fn($q) => $q->where('slug', 'bach-hoa'))
                        ->orderByDesc('total_sold')
                        ->orderByDesc('avg_rating');
                    break;

                case 'latest':
                    // Sản phẩm mới nhất → sắp xếp theo ngày tạo giảm dần
                    $query->orderByDesc('id');
                    break;

                case 'quantamnhieunhat':
                    // Sản phẩm được xem nhiều nhất → luotxem giảm dần
                    $query->orderByDesc('luotxem')
                        ->orderByDesc('avg_rating');
                    break;

                default:
                    // Nếu sortby không hợp lệ thì dùng thứ tự mặc định
                    $query->orderByDesc('luotxem')
                        ->orderByRaw('COALESCE((SELECT MIN(giagoc) FROM bienthe WHERE id_sanpham = sanpham.id), 0) ASC')
                        ->orderByDesc('giamgia')
                        ->orderByDesc('total_sold')
                        ->orderByDesc('avg_rating');
                    break;
            }
        } else {
            // --- Sắp xếp mặc định ---
            $query->orderByDesc('luotxem')
                ->orderByRaw('COALESCE((SELECT MIN(giagoc) FROM bienthe WHERE id_sanpham = sanpham.id), 0) ASC')
                ->orderByDesc('giamgia')
                ->orderByDesc('total_sold')
                ->orderByDesc('avg_rating');
        }

        // --- Phân trang ---
        $sanphams = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $sanphams;
    }

    /**
     * @OA\Get(
     *     path="/api/sanphams-all/{id}",
     *     tags={"Tất Cả Sản phẩm (Trang Tất Cả Sản Phẩm)"},
     *     summary="Lấy chi tiết sản phẩm, tự động tăng lượt xem lên 1",
     *     description="Hiển thị chi tiết sản phẩm bao gồm hình ảnh, thương hiệu, danh mục, đánh giá và biến thể có giá cao nhất. Kèm danh sách sản phẩm tương tự.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID sản phẩm cần xem chi tiết",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết sản phẩm và sản phẩm tương tự",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/SanPhamAllDetailResources"
     *             ),
     *             @OA\Property(
     *                 property="sanpham_tuongtu",
     *                 type="array",
     *                 description="Danh sách sản phẩm tương tự",
     *                 @OA\Items(ref="#/components/schemas/SanPhamAllResources")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm"
     *     )
     * )
     */
    public function show(string $id)
    {
        // $product = Sanpham::with([
        //     'bienThe.loaiBienThe',
        //     'anhSanPham',
        //     'danhmuc',
        //     'thuonghieu',
        // ])->findOrFail($id);

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc',
         'bienthe', 'loaibienthe','danhgia.nguoidung','bienthe.loaibienthe','bienthe.sanpham'])
        // $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc',
        //  'bienthe', 'loaibienthe','danhgia.nguoidung','bienthe.loaibienthe','loaibienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng bán
            // trong Detailreources đã tính tổng số lượng bán từ luotban ở bảng biến thể, nen ko cần subquery như sanphams-all method get
            ->withSum('bienthe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem') // điểm
            ->withCount('danhgia as review_count') // số lượng đánh giá
            ->with(['bienthe' => function ($q) {
                $q->orderByDesc('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);
            // }])->findOrFail($id);
        if (is_numeric($id)) {
            $query = $query->where('id', $id)->firstOrFail();
        } else {
            $query = $query->where('slug', $id)->firstOrFail();
        }
        $query->increment('luotxem');

        // dd($query);
        // exit;
        $sanphamTuongtu = SanphamModel::with([
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
            ->with(['bienthe' => function ($q) {
                $q->orderByDesc('giagoc');
            }])
            ->whereHas('danhmuc', function ($q) use ($query) {
                $q->whereIn('danhmuc.id', $query->danhmuc->pluck('id')->toArray());
            })
            ->where('sanpham.id', '!=', $query->id)
            ->limit(5)
            ->get();


        return (new SanPhamAllDetailResources($query))->additional([
            'sanpham_tuongtu' => $sanphamTuongtu->isNotEmpty()
                ? SanPhamAllResources::collection($sanphamTuongtu)
                : [],
        ]);
    }


}

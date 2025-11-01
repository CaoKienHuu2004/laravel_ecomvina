<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\SanphamAPI;
use App\Http\Resources\Frontend\SanPhamAllDetailResources;
use App\Http\Resources\Frontend\SanPhamAllResources;
use App\Models\SanPham;
use App\Models\SanphamModel;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Trang Tất Cả Sản phẩm (khi click vào nút xem tất cả)",
 *     description="Các API hiển thị danh sách và chi tiết sản phẩm cho Trang tất cả sản phẩm"
 * )
 */
class SanPhamAllFrontendAPI extends SanphamAPI
{
    /**
     * @OA\Get(
     *     path="/api/sanphams-all",
     *     tags={"Tất Cả Sản phẩm (Trang Tất Cả Sản Phẩm)"},
     *     summary="Lấy danh sách sản phẩm",
     *     description="Hiển thị danh sách sản phẩm theo nhiều tiêu chí như phổ biến, mới nhất, xu hướng,...",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         required=false,
     *         description="Bộ lọc sản phẩm (popular, latest, trending, matches, default)",
     *         @OA\Schema(type="string", example="popular")
     *     ),
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Từ khóa tìm kiếm",
     *         @OA\Schema(type="string", example="bánh")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng sản phẩm trên mỗi trang (mặc định 20)",
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách sản phẩm"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/SanPhamAllResources")
     *             )
     *         )
     *     )
     * )
     */
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

        return SanPhamAllResources::collection($data);
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

    /** Default: phân trang + filter + q */
    protected function getDefaultProducts(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng bán
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

        $products = $query->latest('id')->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    /**
     * @OA\Get(
    *     path="/api/sanphams-all/{id}",
     *     tags={"Tất Cả Sản phẩm (Trang Tất Cả Sản Phẩm)"},
     *     summary="Lấy chi tiết sản phẩm , và tự động lượt xem lên 1",
     *     description="Hiển thị chi tiết sản phẩm bao gồm hình ảnh, thương hiệu, danh mục, đánh giá và biến thể có giá cao nhất.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID sản phẩm cần xem chi tiết",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết sản phẩm",
     *         @OA\JsonContent(ref="#/components/schemas/SanPhamAllDetailResources")
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
            ->withSum('bienthe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem') // điểm
            ->withCount('danhgia as review_count') // số lượng đánh giá
            ->with(['bienthe' => function ($q) {
                $q->orderByDesc('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }])->findOrFail($id);
        $query->increment('luotxem');

        // dd($query);
        // exit;
        return (new SanPhamAllDetailResources($query));
    }


}

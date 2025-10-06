<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\SanphamAPI;
use App\Http\Resources\Frontend\SanPhamAllDetailResources;
use App\Http\Resources\Frontend\SanPhamAllResources;
use App\Models\SanPham;
use Illuminate\Http\Request;

class SanPhamAllFrontendAPI extends SanphamAPI
{
    /**
     * Display a listing of the resource.
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
    //----------------  limit 20 //Dựa trên sự tương tác/lượt xem cao nhất trong một khoảng thời gian nhất định.
    protected function getPopular(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanPham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienThe', 'loaibienthe'])
            ->withSum('chiTietDonHang as total_sold', 'soluong') // tổng số lượng bán
            ->withSum('bienThe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem') // điểm
            ->withCount('danhgia as review_count') // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienThe' => function ($q) {
                $q->orderBy('uutien', 'asc');
            }]);
        // Sắp xếp: giá ưu tiên thấp nhất, rồi giagiam, rồi số lượng bán, rồi lượt xem
        $query->orderByRaw('COALESCE((SELECT gia - giagiam FROM bienthe_sp
                                    WHERE id_sanpham = san_pham.id
                                    ORDER BY uutien ASC LIMIT 1), 0) ASC')
            ->orderByRaw('COALESCE((SELECT giagiam FROM bienthe_sp
                                    WHERE id_sanpham = san_pham.id
                                    ORDER BY uutien ASC LIMIT 1), 0) DESC')
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

        $query = SanPham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienThe', 'loaibienthe'])
            ->withSum('chiTietDonHang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienThe as total_quantity', 'soluong')      // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình đánh giá
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienThe' => function ($q) {
                $q->orderBy('uutien', 'asc');
            }]);

        // 🔥 Sắp xếp theo thời gian mới nhất (updated_at trước, rồi created_at)
        $query->orderByDesc('updated_at')
            ->orderByDesc('created_at');

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
        $days = $request->get('days', 7);
        $fromDate = now()->subDays($days);

        $query = SanPham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienThe', 'loaibienthe'])
            ->withSum('chiTietDonHang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienThe as total_quantity', 'soluong')      // tổng tồn kho
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienThe' => function ($q) {
                $q->orderBy('uutien', 'asc');
            }])
            // chỉ lấy sản phẩm được cập nhật gần đây
            ->where('updated_at', '>=', $fromDate)
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

        $query = SanPham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienThe', 'loaibienthe'])
            ->withSum('chiTietDonHang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienThe as total_quantity', 'soluong')      // tổng tồn kho
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->with(['bienThe' => function ($q) {
                $q->orderBy('uutien', 'asc');
            }]);

        // 🔎 Nếu có từ khóa tìm kiếm
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('mota', 'like', "%$q%");
            })
            // Thêm điểm relevance để ưu tiên tên hơn mô tả
            ->selectRaw("
                san_pham.*,
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
                    ->from('yeu_thich')
                    ->where('id_nguoidung', $userId);
            })
            ->orderByDesc('updated_at');
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

        $query = SanPham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienThe', 'loaibienthe'])
            ->withSum('chiTietDonHang as total_sold', 'soluong') // tổng số lượng bán
            ->withSum('bienThe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem') // điểm
            ->withCount('danhgia as review_count') // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienThe' => function ($q) {
                $q->orderBy('uutien', 'asc');
            }]);

        $products = $query->latest('updated_at')->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $product = Sanpham::with([
        //     'bienThe.loaiBienThe',
        //     'anhSanPham',
        //     'danhmuc',
        //     'thuonghieu',
        // ])->findOrFail($id);

        $query = SanPham::with(['anhSanPham', 'thuonghieu', 'danhgia', 'danhmuc',
         'bienThe', 'loaibienthe','danhgia.nguoidung'])
            ->withSum('chiTietDonHang as total_sold', 'soluong') // tổng số lượng bán
            ->withSum('bienThe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem') // điểm
            ->withCount('danhgia as review_count') // số lượng đánh giá
            ->with(['bienThe' => function ($q) {
                $q->orderBy('uutien', 'asc');
            }])->findOrFail($id);

        return (new SanPhamAllDetailResources($query));
    }


}

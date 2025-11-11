<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\Frontend\BaseFrontendController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\HotSaleResource;
use App\Models\SanphamModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TimKiemWebApi extends BaseFrontendController
{
    /**
     * Display a listing of the resource.
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
        // $filterAside = $this->getMenuFilterAside();
        // HotSaleResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        // return response()->json(HotSaleResource::collection($products), Response::HTTP_OK);

        HotSaleResource::withoutWrapping();
        $filterAside = $this->getMenuFilterAside();

        $resource = HotSaleResource::collection($products)
            ->map(function ($item) use ($filterAside) {
                // ép thành mảng rồi thêm filter vào từng phần tử
                return array_merge($item->toArray(request()), [
                    'filters' => $filterAside
                ]);
            });
        return response()->json($resource->values(), Response::HTTP_OK);
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

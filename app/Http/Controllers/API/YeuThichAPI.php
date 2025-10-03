<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\YeuThich;
use App\Http\Resources\YeuThichResource;
use Illuminate\Http\Response;

class YeuThichAPI extends BaseController
{
    /**
     * Lấy danh sách yêu thích
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = YeuThich::with(['sanpham', 'nguoidung'])
            ->latest('created_at')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('trangthai', 'like', "%$q%")
                        ->orWhereHas('sanpham', function ($p) use ($q) {
                            $p->where('ten', 'like', "%$q%");
                        })
                        ->orWhereHas('nguoidung', function ($u) use ($q) {
                            $u->where('ten', 'like', "%$q%");
                        });
                });
            });

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ]
            ], 404);
    }

    return $this->jsonResponse([
        'status' => true,
        'message' => 'Danh sách yêu thích',
        'data' => YeuThichResource::collection($items),
        'meta' => [
            'current_page' => $items->currentPage(),
            'last_page'    => $items->lastPage(),
            'per_page'     => $items->perPage(),
            'total'        => $items->total(),
        ]
    ], Response::HTTP_OK);
}


    /**
     * Xem chi tiết
     */
    public function show(string $id)
    {
        $item = YeuThich::with(['sanpham', 'nguoidung'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết yêu thích',
            'data' => new YeuThichResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới yêu thích
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sanpham'   => 'required|exists:san_pham,id',
            'id_nguoidung' => 'required|exists:nguoi_dung,id',
            'trangthai'    => 'required|in:dang_thich,bo_thich',
        ]);

        $item = YeuThich::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Thêm vào danh sách yêu thích thành công',
            'data' => new YeuThichResource($item->load(['sanpham', 'nguoidung']))
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật trạng thái yêu thích
     */
    public function update(Request $request, string $id)
    {
        $item = YeuThich::findOrFail($id);

        $validated = $request->validate([
            'trangthai' => 'required|in:dang_thich,bo_thich',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật trạng thái yêu thích thành công',
            'data' => new YeuThichResource($item->load(['sanpham', 'nguoidung']))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa yêu thích
     */
    public function destroy(string $id)
    {
        $item = YeuThich::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa yêu thích thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

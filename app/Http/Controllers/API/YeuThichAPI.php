<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\YeuThich;
use Illuminate\Http\Request;
use App\Http\Resources\YeuThichResource;
use Illuminate\Http\Response;

class YeuThichAPI extends Controller
{
    /**
     * Lấy danh sách yêu thích
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $items = YeuThich::with(['sanpham', 'nguoidung'])
            ->latest('created_at')
            ->paginate($perPage);

        return response()->json([
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

        return new YeuThichResource($item);
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

        return new YeuThichResource($item);
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

        return new YeuThichResource($item);
    }

    /**
     * Xóa yêu thích
     */
    public function destroy(string $id)
    {
        $item = YeuThich::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa yêu thích thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

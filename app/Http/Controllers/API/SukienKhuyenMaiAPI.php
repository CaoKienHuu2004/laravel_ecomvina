<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\SukienKhuyenMai;
use App\Http\Resources\SukienKhuyenMaiResource;
use Illuminate\Http\Response;

class SukienKhuyenMaiAPI extends BaseController
{
    /**
     * Lấy danh sách các liên kết sự kiện ↔ quà tặng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $items = SukienKhuyenMai::with(['khuyenmai', 'sukien'])
                    ->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $items->lastPage(),
                    'per_page' => $perPage,
                    'total' => $items->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách liên kết sự kiện khuyến mãi',
            'data' => SukienKhuyenMaiResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết liên kết
     */
    public function show(string $id)
    {
        $item = SukienKhuyenMai::with(['khuyenmai', 'sukien'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết liên kết sự kiện khuyến mãi',
            'data' => new SukienKhuyenMaiResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Tạo liên kết mới (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_khuyenmai' => 'required|exists:quatang_khuyenmai,id',
            'id_sukien'    => 'required|exists:chuongtrinhsukien,id',
        ]);

        $item = SukienKhuyenMai::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo liên kết sự kiện khuyến mãi thành công',
            'data' => new SukienKhuyenMaiResource($item->load(['khuyenmai', 'sukien']))
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật liên kết (admin)
     */
    public function update(Request $request, string $id)
    {
        $item = SukienKhuyenMai::findOrFail($id);

        $validated = $request->validate([
            'id_khuyenmai' => 'sometimes|required|exists:quatang_khuyenmai,id',
            'id_sukien'    => 'sometimes|required|exists:chuongtrinhsukien,id',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật liên kết sự kiện khuyến mãi thành công',
            'data' => new SukienKhuyenMaiResource($item->load(['khuyenmai', 'sukien']))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa liên kết (admin)
     */
    public function destroy(Request $request, string $id)
    {
        $item = SukienKhuyenMai::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa liên kết thành công'
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\QuatangKhuyenMai;
use App\Http\Resources\QuatangKhuyenMaiResource;
use Illuminate\Http\Response;

class QuatangKhuyenMaiAPI extends BaseController
{
    /**
     * Lấy danh sách quà tặng khuyến mãi (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $items = QuatangKhuyenMai::with(['bienthe', 'thuonghieu'])
                    ->latest('ngaybatdau')
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
            'message' => 'Danh sách quà tặng khuyến mãi',
            'data' => QuatangKhuyenMaiResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 quà tặng
     */
    public function show(string $id)
    {
        $item = QuatangKhuyenMai::with(['bienthe', 'thuonghieu'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết quà tặng khuyến mãi',
            'data' => new QuatangKhuyenMaiResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới quà tặng (chỉ admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soluong'       => 'required|integer|min:1',
            'mota'          => 'nullable|string',
            'ngaybatdau'    => 'required|date',
            'ngayketthuc'   => 'required|date|after_or_equal:ngaybatdau',
            'min_donhang'   => 'required|numeric|min:0',
            'id_bienthe'    => 'required|exists:bienthe_sp,id',
            'id_thuonghieu' => 'required|exists:thuong_hieu,id',
        ]);

        $item = QuatangKhuyenMai::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo quà tặng khuyến mãi thành công',
            'data' => new QuatangKhuyenMaiResource($item->load(['bienthe', 'thuonghieu']))
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật quà tặng (chỉ admin)
     */
    public function update(Request $request, string $id)
    {
        $item = QuatangKhuyenMai::findOrFail($id);

        $validated = $request->validate([
            'soluong'       => 'sometimes|required|integer|min:1',
            'mota'          => 'nullable|string',
            'ngaybatdau'    => 'sometimes|required|date',
            'ngayketthuc'   => 'sometimes|required|date|after_or_equal:ngaybatdau',
            'min_donhang'   => 'sometimes|required|numeric|min:0',
            'id_bienthe'    => 'sometimes|required|exists:bienthe_sp,id',
            'id_thuonghieu' => 'sometimes|required|exists:thuong_hieu,id',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật quà tặng khuyến mãi thành công',
            'data' => new QuatangKhuyenMaiResource($item->load(['bienthe', 'thuonghieu']))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa quà tặng (chỉ admin)
     */
    public function destroy(Request $request, string $id)
    {
        $item = QuatangKhuyenMai::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa quà tặng thành công'
        ], Response::HTTP_OK);
    }
}

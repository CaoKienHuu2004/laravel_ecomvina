<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ChiTietDonHang;
use App\Http\Resources\ChiTietDonHangResource;
use Illuminate\Http\Response;

class ChiTietDonHangAPI extends BaseController
{
    /**
     * Lấy danh sách chi tiết đơn hàng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $query = ChiTietDonHang::with(['donhang', 'bienthe'])->latest('updated_at');

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

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
            'message' => 'Danh sách chi tiết đơn hàng',
            'data' => ChiTietDonHangResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 bản ghi
     */
    public function show(string $id)
    {
        $item = ChiTietDonHang::with(['donhang', 'bienthe'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết đơn hàng',
            'data' => new ChiTietDonHangResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới chi tiết đơn hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gia'        => 'required|numeric',
            'soluong'    => 'required|integer',
            'tongtien'   => 'required|numeric',
            'id_donhang' => 'required|exists:don_hang,id',
            'id_bienthe' => 'required|exists:bienthe,id',
        ]);

        $item = ChiTietDonHang::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo chi tiết đơn hàng thành công',
            'data' => new ChiTietDonHangResource($item)
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật chi tiết đơn hàng
     */
    public function update(Request $request, string $id)
    {
        $item = ChiTietDonHang::findOrFail($id);

        $validated = $request->validate([
            'gia'        => 'sometimes|numeric',
            'soluong'    => 'sometimes|integer',
            'tongtien'   => 'sometimes|numeric',
            'id_donhang' => 'sometimes|exists:don_hang,id',
            'id_bienthe' => 'sometimes|exists:bienthe,id',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật chi tiết đơn hàng thành công',
            'data' => new ChiTietDonHangResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Xóa chi tiết đơn hàng (soft delete)
     */
    public function destroy(string $id)
    {
        $item = ChiTietDonHang::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa chi tiết đơn hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

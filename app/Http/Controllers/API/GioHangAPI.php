<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Http\Resources\GioHangResource;
use Illuminate\Http\Response;

class GioHangAPI extends BaseController
{
    /**
     * Danh sách giỏ hàng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $giohangs = GioHang::with(['nguoidung', 'sanpham'])
            ->latest('updated_at')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $giohangs->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $giohangs->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $giohangs->lastPage(),
                    'per_page' => $perPage,
                    'total' => $giohangs->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách giỏ hàng',
            'data' => GioHangResource::collection($giohangs),
            'meta' => [
                'current_page' => $giohangs->currentPage(),
                'last_page'    => $giohangs->lastPage(),
                'per_page'     => $giohangs->perPage(),
                'total'        => $giohangs->total(),
            ]
        ], 200);
    }

    /**
     * Tạo mới giỏ hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soluong'      => 'required|integer|min:1',
            'tongtien'     => 'required|numeric|min:0',
            'id_sanpham'   => 'required|exists:san_pham,id',
            'id_nguoidung' => 'required|exists:nguoi_dung,id',
        ]);

        $giohang = GioHang::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo giỏ hàng thành công',
            'data' => new GioHangResource($giohang->load(['nguoidung', 'sanpham']))
        ], Response::HTTP_CREATED);
    }

    /**
     * Chi tiết giỏ hàng
     */
    public function show(string $id)
    {
        $giohang = GioHang::with(['nguoidung', 'sanpham'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết giỏ hàng',
            'data' => new GioHangResource($giohang)
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật giỏ hàng
     */
    public function update(Request $request, string $id)
    {
        $giohang = GioHang::findOrFail($id);

        $validated = $request->validate([
            'soluong'      => 'sometimes|integer|min:1',
            'tongtien'     => 'sometimes|numeric|min:0',
            'id_sanpham'   => 'sometimes|exists:san_pham,id',
            'id_nguoidung' => 'sometimes|exists:nguoi_dung,id',
        ]);

        $giohang->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật giỏ hàng thành công',
            'data' => new GioHangResource($giohang->refresh()->load(['nguoidung', 'sanpham']))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa giỏ hàng
     */
    public function destroy(string $id)
    {
        $giohang = GioHang::findOrFail($id);
        $giohang->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa giỏ hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

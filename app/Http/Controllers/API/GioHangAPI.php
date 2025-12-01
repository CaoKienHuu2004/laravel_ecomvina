<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GiohangModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GioHangAPI extends Controller
{
    /**
     * Lấy danh sách giỏ hàng (có phân trang + tìm kiếm)
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q', '');

        $query = GiohangModel::with(['nguoidung', 'bienthe'])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('bienthe', function ($sub) use ($q) {
                    $sub->where('ten', 'LIKE', "%$q%");
                });
            })
            ->latest('updated_at');

        $giohangs = $query->paginate($perPage, ['*'], 'page', $currentPage);

        // Nếu trang vượt quá lastPage
        if ($currentPage > $giohangs->lastPage() && $currentPage > 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $giohangs->lastPage(),
                'meta'    => [
                    'current_page' => $currentPage,
                    'last_page'    => $giohangs->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $giohangs->total(),
                ],
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Danh sách giỏ hàng',
            'data'    => $giohangs->items(),
            'meta'    => [
                'current_page' => $giohangs->currentPage(),
                'last_page'    => $giohangs->lastPage(),
                'per_page'     => $giohangs->perPage(),
                'total'        => $giohangs->total(),
            ],
        ], Response::HTTP_OK);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_bienthe'   => 'required|exists:bienthe,id',
            'id_nguoidung' => 'required|exists:nguoidung,id',
            'soluong'      => 'required|integer|min:1',
            'thanhtien'    => 'required|integer|min:0',
            'trangthai'    => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $giohang = GiohangModel::create($validated);

        return response()->json([
            'status'  => true,
            'message' => '🟢 Thêm vào giỏ hàng thành công',
            'data'    => $giohang->load(['nguoidung', 'bienthe']),
        ], Response::HTTP_CREATED);
    }

    /**
     * Xem chi tiết giỏ hàng
     */
    public function show(string $id)
    {
        $giohang = GiohangModel::with(['nguoidung', 'bienthe'])->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Chi tiết giỏ hàng',
            'data'    => $giohang,
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật thông tin trong giỏ hàng
     */
    public function update(Request $request, string $id)
    {
        $giohang = GiohangModel::findOrFail($id);

        $validated = $request->validate([
            'id_bienthe'   => 'sometimes|exists:bienthe,id',
            'id_nguoidung' => 'sometimes|exists:nguoidung,id',
            'soluong'      => 'sometimes|integer|min:1',
            'thanhtien'    => 'sometimes|integer|min:0',
            'trangthai'    => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $giohang->update($validated);

        return response()->json([
            'status'  => true,
            'message' => '🟡 Cập nhật giỏ hàng thành công',
            'data'    => $giohang->fresh(['nguoidung', 'bienthe']),
        ], Response::HTTP_OK);
    }

    /**
     * Xóa giỏ hàng
     */
    public function destroy(string $id)
    {
        $giohang = GiohangModel::findOrFail($id);
        $giohang->delete();

        return response()->json([
            'status'  => true,
            'message' => '🔴 Xóa giỏ hàng thành công',
        ], Response::HTTP_OK);
    }
}

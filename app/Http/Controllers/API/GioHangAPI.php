<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Http\Resources\GioHangResource;
use Illuminate\Http\Response;

class GioHangAPI extends Controller
{
    /**
     * Danh sách giỏ hàng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $giohangs = GioHang::with(['nguoidung', 'sanpham'])
            ->latest('updated_at')
            ->paginate($perPage);

        return GioHangResource::collection($giohangs)
            ->additional([
                'status' => true,
                'message' => 'Danh sách giỏ hàng',
                'meta' => [
                    'current_page' => $giohangs->currentPage(),
                    'last_page'    => $giohangs->lastPage(),
                    'per_page'     => $giohangs->perPage(),
                    'total'        => $giohangs->total(),
                ]
            ]);
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

        return (new GioHangResource($giohang->load(['nguoidung', 'sanpham'])))
            ->additional([
                'status' => true,
                'message' => 'Tạo giỏ hàng thành công'
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Chi tiết giỏ hàng
     */
    public function show(string $id)
    {
        $giohang = GioHang::with(['nguoidung', 'sanpham'])->findOrFail($id);

        return (new GioHangResource($giohang))
            ->additional([
                'status' => true,
                'message' => 'Chi tiết giỏ hàng'
            ]);
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

        return (new GioHangResource($giohang->refresh()->load(['nguoidung', 'sanpham'])))
            ->additional([
                'status' => true,
                'message' => 'Cập nhật giỏ hàng thành công'
            ]);
    }

    /**
     * Xóa giỏ hàng
     */
    public function destroy(string $id)
    {
        $giohang = GioHang::findOrFail($id);
        $giohang->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa giỏ hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

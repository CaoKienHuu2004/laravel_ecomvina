<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Http\Resources\GioHangCollectionResource;
use App\Http\Resources\GioHangResource;
use Illuminate\Http\Response;

class GioHangFrontendAPI extends BaseFrontendController
{
    /**
     * Xem toàn bộ giỏ hàng của user hiện tại
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $giohangs = GioHang::where('id_nguoidung', $userId)
            ->with('bienthesp.sanpham') // eager load biến thể và sản phẩm gốc
            ->get();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Giỏ hàng hiện tại',
            'data'    => new GioHangCollectionResource($giohangs),
        ], Response::HTTP_OK);
    }

    /**
     * Thêm sản phẩm vào giỏ
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_bienthesp' => 'required|exists:bienthe_sp,id',
            'soluong'      => 'required|integer|min:1',
        ]);

        $validated['id_nguoidung'] = $request->user()->id;

        $giohang = GioHang::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Thêm sản phẩm vào giỏ thành công',
            'data'    => new GioHangResource($giohang->load('bienthesp.sanpham'))
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function update(Request $request, $id)
    {
        $giohang = GioHang::where('id_nguoidung', $request->user()->id)
                          ->findOrFail($id);

        $validated = $request->validate([
            'soluong' => 'required|integer|min:1',
        ]);

        $giohang->update($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật giỏ hàng thành công',
            'data'    => new GioHangResource($giohang->refresh()->load('bienthesp.sanpham'))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa sản phẩm khỏi giỏ
     */
    public function destroy(Request $request, $id)
    {
        $giohang = GioHang::where('id_nguoidung', $request->user()->id)
                          ->findOrFail($id);

        $giohang->delete();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Xóa sản phẩm khỏi giỏ thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use Illuminate\Http\Request;
use App\Http\Resources\DonHangResource;
use Illuminate\Http\Response;

class DonHangAPI extends Controller
{
    /**
     * Lấy danh sách đơn hàng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = DonHang::with(['nguoidung', 'magiamgia'])->latest('ngaytao');

        $items = $query->paginate($perPage);

        return response()->json([
            'data' => DonHangResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 đơn hàng
     */
    public function show(string $id)
    {
        $item = DonHang::with(['nguoidung', 'magiamgia'])->findOrFail($id);

        return new DonHangResource($item);
    }

    /**
     * Tạo mới đơn hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_donhang'     => 'nullable|string|max:255',
            'tongtien'       => 'required|numeric',
            'tongsoluong'    => 'required|integer',
            'ghichu'         => 'nullable|string',
            'ngaytao'        => 'required|date',
            'trangthai'      => 'nullable|in:cho_xac_nhan,da_xac_nhan,dang_giao,da_giao,da_huy',
            'id_nguoidung'   => 'required|exists:nguoi_dung,id',
            'id_magiamgia'   => 'required|exists:ma_giamgia,id',
        ]);

        $item = DonHang::create($validated);

        return (new DonHangResource($item))
            ->additional(['status'=>true,'message'=>'Tạo đơn hàng thành công'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Cập nhật đơn hàng
     */
    public function update(Request $request, string $id)
    {
        $item = DonHang::findOrFail($id);

        $validated = $request->validate([
            'ma_donhang'     => 'nullable|string|max:255',
            'tongtien'       => 'sometimes|numeric',
            'tongsoluong'    => 'sometimes|integer',
            'ghichu'         => 'nullable|string',
            'ngaytao'        => 'nullable|date',
            'trangthai'      => 'nullable|in:cho_xac_nhan,da_xac_nhan,dang_giao,da_giao,da_huy',
            'id_nguoidung'   => 'sometimes|exists:nguoi_dung,id',
            'id_magiamgia'   => 'sometimes|exists:ma_giamgia,id',
        ]);

        $item->update($validated);

        return (new DonHangResource($item))
            ->additional(['status'=>true,'message'=>'Cập nhật đơn hàng thành công']);
    }

    /**
     * Xóa đơn hàng (soft delete)
     */
    public function destroy(string $id)
    {
        $item = DonHang::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message'=> 'Xóa đơn hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

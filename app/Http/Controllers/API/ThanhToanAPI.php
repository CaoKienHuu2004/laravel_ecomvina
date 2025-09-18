<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ThanhToan;
use Illuminate\Http\Request;
use App\Http\Resources\ThanhToanResource;
use Illuminate\Http\Response;

class ThanhToanAPI extends Controller
{
    /**
     * Lấy danh sách thanh toán (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = ThanhToan::with('donhang')->latest('ngaythanhtoan');

        $items = $query->paginate($perPage);

        return response()->json([
            'data' => ThanhToanResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 thanh toán
     */
    public function show(string $id)
    {
        $item = ThanhToan::with('donhang')->findOrFail($id);

        return new ThanhToanResource($item);
    }

    /**
     * Tạo thanh toán mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nganhang'      => 'nullable|string|max:255',
            'gia'           => 'required|numeric',
            'noidung'       => 'nullable|string',
            'magiaodich'    => 'nullable|string|max:255',
            'ngaythanhtoan' => 'required|date',
            'trangthai'     => 'nullable|in:cho_xac_nhan,dang_xu_ly,thanh_cong,that_bai,da_huy,hoan_tien,tre_han,cho_xac_nhan_ngan_hang',
            'id_donhang'    => 'required|exists:don_hang,id',
        ]);

        $item = ThanhToan::create($validated);

        return (new ThanhToanResource($item))
            ->additional(['status'=>true,'message'=>'Tạo thanh toán thành công'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Cập nhật thanh toán
     */
    public function update(Request $request, string $id)
    {
        $item = ThanhToan::findOrFail($id);

        $validated = $request->validate([
            'nganhang'      => 'sometimes|string|max:255',
            'gia'           => 'sometimes|numeric',
            'noidung'       => 'sometimes|string',
            'magiaodich'    => 'sometimes|string|max:255',
            'ngaythanhtoan' => 'sometimes|date',
            'trangthai'     => 'sometimes|in:cho_xac_nhan,dang_xu_ly,thanh_cong,that_bai,da_huy,hoan_tien,tre_han,cho_xac_nhan_ngan_hang',
            'id_donhang'    => 'sometimes|exists:don_hang,id',
        ]);

        $item->update($validated);

        return (new ThanhToanResource($item))
            ->additional(['status'=>true,'message'=>'Cập nhật thanh toán thành công']);
    }

    /**
     * Xóa thanh toán (soft delete)
     */
    public function destroy(string $id)
    {
        $item = ThanhToan::findOrFail($id);
        $item->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Xóa thanh toán thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

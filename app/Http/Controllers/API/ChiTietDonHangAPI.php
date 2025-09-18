<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChiTietDonHang;
use Illuminate\Http\Request;
use App\Http\Resources\ChiTietDonHangResource;
use Illuminate\Http\Response;

class ChiTietDonHangAPI extends Controller
{
    /**
     * Lấy danh sách chi tiết đơn hàng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = ChiTietDonHang::with(['donhang', 'bienthe'])->latest('updated_at');

        $items = $query->paginate($perPage);

        return response()->json([
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

        return new ChiTietDonHangResource($item);
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

        return (new ChiTietDonHangResource($item))
            ->additional(['status'=>true,'message'=>'Tạo chi tiết đơn hàng thành công'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
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

        return (new ChiTietDonHangResource($item))
            ->additional(['status'=>true,'message'=>'Cập nhật chi tiết đơn hàng thành công']);
    }

    /**
     * Xóa chi tiết đơn hàng (soft delete)
     */
    public function destroy(string $id)
    {
        $item = ChiTietDonHang::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message'=> 'Xóa chi tiết đơn hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

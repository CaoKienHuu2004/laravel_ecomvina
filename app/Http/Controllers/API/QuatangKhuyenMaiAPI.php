<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\QuatangKhuyenMai;
use Illuminate\Http\Request;
use App\Http\Resources\QuatangKhuyenMaiResource;
use Illuminate\Http\Response;

class QuatangKhuyenMaiAPI extends Controller
{
    /**
     * Lấy danh sách quà tặng khuyến mãi (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $items = QuatangKhuyenMai::with(['bienthe', 'thuonghieu'])
                    ->latest('ngaybatdau')
                    ->paginate($perPage);

        return response()->json([
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

        return new QuatangKhuyenMaiResource($item);
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

        return (new QuatangKhuyenMaiResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
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

        return new QuatangKhuyenMaiResource($item);
    }

    /**
     * Xóa quà tặng (chỉ admin)
     */
    public function destroy(Request $request, string $id)
    {
        $item = QuatangKhuyenMai::findOrFail($id);
        $item->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Xóa quà tặng thành công'
        ], Response::HTTP_OK);
    }
}

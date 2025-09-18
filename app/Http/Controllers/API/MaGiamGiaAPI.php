<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MaGiamGia;
use Illuminate\Http\Request;
use App\Http\Resources\MaGiamGiaResource;
use Illuminate\Http\Response;

class MaGiamGiaAPI extends Controller
{
    /**
     * Lấy danh sách mã giảm giá (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = MaGiamGia::latest('updated_at');

        $items = $query->paginate($perPage);

        return response()->json([
            'data' => MaGiamGiaResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 mã giảm giá
     */
    public function show(string $id)
    {
        $item = MaGiamGia::with('donHang')->findOrFail($id);

        return new MaGiamGiaResource($item);
    }

    /**
     * Tạo mới mã giảm giá (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'magiamgia'    => 'nullable|string|max:255',
            'mota'         => 'nullable|string',
            'giatri'       => 'required|numeric',
            'dieukien'     => 'nullable|string|max:255',
            'ngaybatdau'   => 'required|date',
            'ngayketthuc'  => 'required|date|after_or_equal:ngaybatdau',
            'trangthai'    => 'nullable|in:hoat_dong,het_han,tam_khoa,da_xoa',
        ]);

        $item = MaGiamGia::create($validated);

        return (new MaGiamGiaResource($item))
            ->additional(['status'=>true,'message'=>'Tạo mã giảm giá thành công'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Cập nhật mã giảm giá (admin)
     */
    public function update(Request $request, string $id)
    {
        $item = MaGiamGia::findOrFail($id);

        $validated = $request->validate([
            'magiamgia'    => 'nullable|string|max:255',
            'mota'         => 'nullable|string',
            'giatri'       => 'sometimes|numeric',
            'dieukien'     => 'nullable|string|max:255',
            'ngaybatdau'   => 'nullable|date',
            'ngayketthuc'  => 'nullable|date|after_or_equal:ngaybatdau',
            'trangthai'    => 'nullable|in:hoat_dong,het_han,tam_khoa,da_xoa',
        ]);

        $item->update($validated);

        return (new MaGiamGiaResource($item))
            ->additional(['status'=>true,'message'=>'Cập nhật mã giảm giá thành công']);
    }

    /**
     * Xóa mã giảm giá (soft delete, admin)
     */
    public function destroy(Request $request, string $id)
    {
        $item = MaGiamGia::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message'=> 'Xóa mã giảm giá thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

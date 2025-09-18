<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SukienKhuyenMai;
use Illuminate\Http\Request;
use App\Http\Resources\SukienKhuyenMaiResource;
use Illuminate\Http\Response;

class SukienKhuyenMaiAPI extends Controller
{
    /**
     * Lấy danh sách các liên kết sự kiện ↔ quà tặng
     */
    public function index(Request $request)
    {
        $items = SukienKhuyenMai::with(['khuyenmai', 'sukien'])->get();

        return SukienKhuyenMaiResource::collection($items);
    }

    /**
     * Tạo liên kết mới (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_khuyenmai' => 'required|exists:quatang_khuyenmai,id',
            'id_sukien'    => 'required|exists:chuongtrinhsukien,id',
        ]);

        $item = SukienKhuyenMai::create($validated);

        return (new SukienKhuyenMaiResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Xóa liên kết (admin)
     */
    public function destroy(Request $request, string $id)
    {
        $item = SukienKhuyenMai::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa liên kết thành công'
        ], Response::HTTP_OK);
    }
}

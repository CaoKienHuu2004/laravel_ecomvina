<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use Illuminate\Http\Request;
use App\Http\Resources\DanhGiaResource;
use Illuminate\Http\Response;

class DanhGiaAPI extends Controller
{
    /**
     * Lấy danh sách đánh giá (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = DanhGia::with(['sanpham', 'nguoidung'])->latest('ngaydang');

        $items = $query->paginate($perPage);

        return response()->json([
            'data' => DanhGiaResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 đánh giá
     */
    public function show(string $id)
    {
        $item = DanhGia::with(['sanpham', 'nguoidung'])->findOrFail($id);

        return new DanhGiaResource($item);
    }

    /**
     * Tạo đánh giá mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'diem'       => 'required|numeric|min:0|max:5',
            'noidung'    => 'nullable|string',
            'media'      => 'nullable|string',
            'ngaydang'   => 'required|date',
            'trangthai'  => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
            'id_sanpham' => 'required|exists:san_pham,id',
            'id_nguoidung'=> 'required|exists:nguoi_dung,id',
        ]);

        $item = DanhGia::create($validated);

        return (new DanhGiaResource($item))
            ->additional(['status'=>true,'message'=>'Tạo đánh giá thành công'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Cập nhật đánh giá
     */
    public function update(Request $request, string $id)
    {
        $item = DanhGia::findOrFail($id);

        $validated = $request->validate([
            'diem'       => 'sometimes|numeric|min:0|max:5',
            'noidung'    => 'sometimes|string',
            'media'      => 'sometimes|string',
            'ngaydang'   => 'sometimes|date',
            'trangthai'  => 'sometimes|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
            'id_sanpham' => 'sometimes|exists:san_pham,id',
            'id_nguoidung'=> 'sometimes|exists:nguoi_dung,id',
        ]);

        $item->update($validated);

        return (new DanhGiaResource($item))
            ->additional(['status'=>true,'message'=>'Cập nhật đánh giá thành công']);
    }

    /**
     * Xóa đánh giá (soft delete)
     */
    public function destroy(string $id)
    {
        $item = DanhGia::findOrFail($id);
        $item->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Xóa đánh giá thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

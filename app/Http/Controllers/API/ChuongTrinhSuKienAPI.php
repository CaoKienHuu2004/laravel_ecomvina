<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChuongTrinhSuKien;
use Illuminate\Http\Request;
use App\Http\Resources\ChuongTrinhSuKienResource;
use Illuminate\Http\Response;

class ChuongTrinhSuKienAPI extends Controller
{
    /**
     * Lấy danh sách chương trình sự kiện
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = ChuongTrinhSuKien::latest('ngaybatdau');

        $items = $query->paginate($perPage);

        return response()->json([
            'data' => ChuongTrinhSuKienResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 sự kiện
     */
    public function show(string $id)
    {
        $item = ChuongTrinhSuKien::findOrFail($id);

        return new ChuongTrinhSuKienResource($item);
    }

    /**
     * Tạo sự kiện mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten'         => 'required|string|unique:chuongtrinhsukien,ten',
            'slug'        => 'nullable|string|unique:chuongtrinhsukien,slug',
            'media'       => 'nullable|string',
            'mota'        => 'nullable|string',
            'ngaybatdau'  => 'required|date',
            'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau',
            'trangthai'   => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $item = ChuongTrinhSuKien::create($validated);

        return (new ChuongTrinhSuKienResource($item))
            ->additional(['status'=>true,'message'=>'Tạo sự kiện thành công'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Cập nhật sự kiện
     */
    public function update(Request $request, string $id)
    {
        $item = ChuongTrinhSuKien::findOrFail($id);

        $validated = $request->validate([
            'ten'         => 'sometimes|required|string|unique:chuongtrinhsukien,ten,'.$item->id,
            'slug'        => 'sometimes|nullable|string|unique:chuongtrinhsukien,slug,'.$item->id,
            'media'       => 'sometimes|nullable|string',
            'mota'        => 'sometimes|nullable|string',
            'ngaybatdau'  => 'sometimes|date',
            'ngayketthuc' => 'sometimes|date|after_or_equal:ngaybatdau',
            'trangthai'   => 'sometimes|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $item->update($validated);

        return (new ChuongTrinhSuKienResource($item))
            ->additional(['status'=>true,'message'=>'Cập nhật sự kiện thành công']);
    }

    /**
     * Xóa sự kiện (soft delete)
     */
    public function destroy(Request $request, string $id)
    {
        $item = ChuongTrinhSuKien::findOrFail($id);
        $item->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Xóa sự kiện thành công'
        ], Response::HTTP_OK);
    }
}

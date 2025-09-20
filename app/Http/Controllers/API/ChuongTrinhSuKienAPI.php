<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ChuongTrinhSuKien;
use App\Http\Resources\ChuongTrinhSuKienResource;
use Illuminate\Http\Response;

class ChuongTrinhSuKienAPI extends BaseController
{
    /**
     * Lấy danh sách chương trình sự kiện
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $query = ChuongTrinhSuKien::latest('ngaybatdau');

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $items->lastPage(),
                    'per_page' => $perPage,
                    'total' => $items->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách chương trình sự kiện',
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

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết chương trình sự kiện',
            'data' => new ChuongTrinhSuKienResource($item)
        ], Response::HTTP_OK);
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

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo sự kiện thành công',
            'data' => new ChuongTrinhSuKienResource($item)
        ], Response::HTTP_CREATED);
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

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật sự kiện thành công',
            'data' => new ChuongTrinhSuKienResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Xóa sự kiện (soft delete)
     */
    public function destroy(Request $request, string $id)
    {
        $item = ChuongTrinhSuKien::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa sự kiện thành công'
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\DanhGia;
use App\Http\Resources\DanhGiaResource;
use Illuminate\Http\Response;

class DanhGiaAPI extends BaseController
{
    /**
     * Lấy danh sách đánh giá (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = DanhGia::with(['sanpham', 'nguoidung'])
            ->latest('updated_at')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('noidung', 'like', "%$q%")
                        ->orWhere('trangthai', 'like', "%$q%")
                        ->orWhereHas('sanpham', function ($sp) use ($q) {
                            $sp->where('ten', 'like', "%$q%");
                        })
                        ->orWhereHas('nguoidung', function ($u) use ($q) {
                            $u->where('hoten', 'like', "%$q%");
                        });
                });
            });

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách đánh giá',
            'data'    => DanhGiaResource::collection($items),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'next_page_url'=> $items->nextPageUrl(),
                'prev_page_url'=> $items->previousPageUrl(),
            ]
        ], Response::HTTP_OK);
    }


    /**
     * Xem chi tiết 1 đánh giá
     */
    public function show(string $id)
    {
        $item = DanhGia::with(['sanpham', 'nguoidung'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết đánh giá',
            'data' => new DanhGiaResource($item)
        ], Response::HTTP_OK);
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

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo đánh giá thành công',
            'data' => new DanhGiaResource($item->load(['sanpham', 'nguoidung']))
        ], Response::HTTP_CREATED);
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

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật đánh giá thành công',
            'data' => new DanhGiaResource($item->load(['sanpham', 'nguoidung']))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa đánh giá (soft delete)
     */
    public function destroy(string $id)
    {
        $item = DanhGia::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa đánh giá thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\ThuonghieuResources;
use App\Models\Thuonghieu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThuongHieuAPI extends BaseController
{
    /**
     * Danh sách thương hiệu (có phân trang + tìm kiếm)
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q');

        $query = Thuonghieu::with('sanpham')
            ->orderBy('created_at', 'desc')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%")
                        ->orWhere('trangthai', 'like', "%$q%");
                });
            });

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        // Nếu page vượt quá lastPage
        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta'    => [
                    'current_page' => $currentPage,
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách thương hiệu',
            'data'    => ThuonghieuResources::collection($items),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Chi tiết thương hiệu
     */
    public function show(string $id)
    {
        $item = Thuonghieu::with('sanpham')->findOrFail($id);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Chi tiết thương hiệu',
            'data'    => new ThuonghieuResources($item)
        ], Response::HTTP_OK);
    }

    /**
     * Tạo thương hiệu mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten'         => 'required|string|max:255|unique:thuong_hieu,ten',
            'mota'        => 'nullable|string',
            'namthanhlap' => 'required|integer|min:1000|max:' . date('Y'),
            'media'       => 'required|string',
            'trangthai'   => 'in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $item = Thuonghieu::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Tạo thương hiệu thành công',
            'data'    => new ThuonghieuResources($item->load('sanpham'))
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật thương hiệu
     */
    public function update(Request $request, string $id)
    {
        $item = Thuonghieu::findOrFail($id);

        $validated = $request->validate([
            'ten'         => 'sometimes|required|string|max:255|unique:thuong_hieu,ten,' . $item->id,
            'mota'        => 'nullable|string',
            'namthanhlap' => 'sometimes|required|integer|min:1000|max:' . date('Y'),
            'media'       => 'sometimes|required|string',
            'trangthai'   => 'in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật thương hiệu thành công',
            'data'    => new ThuonghieuResources($item->load('sanpham'))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa mềm thương hiệu
     */
    public function destroy(string $id)
    {
        $item = Thuonghieu::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Xóa thương hiệu thành công'
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * Khôi phục thương hiệu đã xóa mềm
     */
    // public function restore(string $id)
    // {
    //     $item = Thuonghieu::withTrashed()->findOrFail($id);

    //     if ($item->trashed()) {
    //         $item->restore();
    //         return $this->jsonResponse([
    //             'status'  => true,
    //             'message' => 'Khôi phục thương hiệu thành công',
    //             'data'    => new ThuonghieuResources($item)
    //         ], Response::HTTP_OK);
    //     }

    //     return $this->jsonResponse([
    //         'status'  => false,
    //         'message' => 'Thương hiệu chưa bị xóa'
    //     ], Response::HTTP_BAD_REQUEST);
    // }
}

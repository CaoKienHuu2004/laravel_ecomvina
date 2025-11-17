<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuatangsukienModel;
use Illuminate\Http\Response;



class QuaTangSuKienAPI extends BaseController
{

    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q');

        $query = QuatangsukienModel::with(['bienthe', 'thuonghieu', 'sukien'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('tieude', 'like', "%$q%")
                        ->orWhere('thongtin', 'like', "%$q%")
                        ->orWhereHas('sukien', function ($s) use ($q) {
                            $s->where('tieude', 'like', "%$q%");
                        })
                        ->orWhereHas('bienthe', function ($b) use ($q) {
                            $b->where('ten', 'like', "%$q%");
                        });
                });
            })
            ->latest();

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);
        // dd($items);
        // exit;

        // ⚠️ Nếu page vượt quá giới hạn
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
            'status' => true,
            'message' => 'Danh sách quà tặng sự kiện',
            'data' => $items->items(),
            'meta' => [
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
     * 🔹 Xem chi tiết 1 quà tặng sự kiện
     */
    public function show(string $id)
    {
        $item = QuatangsukienModel::with(['bienthe', 'cuahang', 'sukien'])
            ->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết quà tặng sự kiện',
            'data' => $item
        ], Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_bienthe'      => 'required|exists:bienthe,id',
            'id_cuahang'      => 'required|exists:cuahang,id',
            'id_sukien'       => 'required|exists:sukien,id',
            'soluongapdung'   => 'required|integer|min:1',
            'tieude'          => 'required|string|max:255',
            'thongtin'        => 'nullable|string',
            'trangthai'       => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $item = QuatangsukienModel::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Thêm quà tặng sự kiện thành công',
            'data' => $item->load(['bienthe', 'cuahang', 'sukien'])
        ], Response::HTTP_CREATED);
    }

    /**
     * 🔹 Cập nhật quà tặng sự kiện
     */
    public function update(Request $request, string $id)
    {
        $item = QuatangsukienModel::findOrFail($id);

        $validated = $request->validate([
            'id_bienthe'      => 'sometimes|required|exists:bienthe,id',
            'id_cuahang'      => 'sometimes|required|exists:cuahang,id',
            'id_sukien'       => 'sometimes|required|exists:sukien,id',
            'soluongapdung'   => 'sometimes|required|integer|min:1',
            'tieude'          => 'sometimes|required|string|max:255',
            'thongtin'        => 'sometimes|nullable|string',
            'trangthai'       => 'sometimes|in:Hiển thị,Tạm ẩn',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật quà tặng sự kiện thành công',
            'data' => $item->load(['bienthe', 'cuahang', 'sukien'])
        ], Response::HTTP_OK);
    }

    /**
     * 🔹 Xóa mềm quà tặng sự kiện
     */
    public function destroy(string $id)
    {
        $item = QuatangsukienModel::findOrFail($id);
        $item->delete(); // Soft delete (do có use SoftDeletes trong model)

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đã xóa (soft delete) quà tặng sự kiện thành công'
        ], Response::HTTP_OK);
    }

    /**
     * 🔹 Khôi phục quà tặng đã xóa mềm
     */
    public function restore(string $id)
    {
        $item = QuatangsukienModel::onlyTrashed()->findOrFail($id);
        $item->restore();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Khôi phục quà tặng sự kiện thành công',
            'data' => $item
        ], Response::HTTP_OK);
    }

    /**
     * 🔹 Xóa vĩnh viễn quà tặng sự kiện
     */
    public function forceDelete(string $id)
    {
        $item = QuatangsukienModel::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đã xóa vĩnh viễn quà tặng sự kiện'
        ], Response::HTTP_OK);
    }
}

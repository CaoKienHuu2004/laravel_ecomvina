<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\LoaibientheModel;
use Illuminate\Http\Response;

class LoaiBienTheAPI extends BaseController
{
    /**
     * 📄 Lấy danh sách loại biến thể (có phân trang + tìm kiếm)
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $keyword     = $request->get('q'); // từ khóa tìm kiếm

        $query = LoaibientheModel::withCount('bienthe')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($sub) use ($keyword) {
                    $sub->where('ten', 'like', "%$keyword%")
                        ->orWhere('trangthai', 'like', "%$keyword%");
                });
            })
            ->latest('updated_at');

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

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
            'message' => 'Danh sách loại biến thể',
            'data'    => $items->items(),
            'meta'    => [
                'current_page'  => $items->currentPage(),
                'last_page'     => $items->lastPage(),
                'per_page'      => $items->perPage(),
                'total'         => $items->total(),
                'next_page_url' => $items->nextPageUrl(),
                'prev_page_url' => $items->previousPageUrl(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * 👁️ Lấy chi tiết 1 loại biến thể
     */
    public function show($id)
    {
        $item = LoaibientheModel::with('bienthe')->find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy loại biến thể'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Chi tiết loại biến thể',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * ➕ Tạo mới loại biến thể
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten'       => 'required|string|max:255|unique:loaibienthe,ten',
            'trangthai' => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $item = LoaibientheModel::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Tạo loại biến thể thành công',
            'data'    => $item
        ], Response::HTTP_CREATED);
    }

    /**
     * ✏️ Cập nhật loại biến thể
     */
    public function update(Request $request, $id)
    {
        $item = LoaibientheModel::find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy loại biến thể'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'ten'       => 'sometimes|required|string|max:255|unique:loaibienthe,ten,' . $id,
            'trangthai' => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật loại biến thể thành công',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * ❌ Xóa loại biến thể (nếu chưa có biến thể con)
     */
    public function destroy($id)
    {
        $item = LoaibientheModel::find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy loại biến thể'
            ], Response::HTTP_NOT_FOUND);
        }

        // Kiểm tra có biến thể con không
        if ($item->bienthe()->count() > 0) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không thể xóa! Loại biến thể này vẫn còn biến thể con.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $item->delete();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Đã xóa loại biến thể thành công'
        ], Response::HTTP_OK);
    }
    /// thieu xóa mềm rồi
}

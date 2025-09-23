<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Loaibienthe;
use App\Http\Resources\LoaibientheResources;
use Illuminate\Http\Response;

class LoaibientheAPI extends BaseController
{
    /**
     * Lấy danh sách loại biến thể (có phân trang + đếm số biến thể sản phẩm).
     * Phân quyền hiển thị theo user.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        // $query = Loaibienthe::withCount('bienthesps');
        $query = Loaibienthe::with(['bienthesps', 'sanphams'])
                ->withCount('bienthesps');

        $items = $query->latest('updated_at')->paginate($perPage, ['*'], 'page', $currentPage);

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
            'message' => 'Danh sách loại biến thể',
            'data'    => LoaibientheResources::collection($items),
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
     * Tạo mới loại biến thể.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten'       => 'required|string|max:255|unique:loai_bienthe,ten',
            'trangthai' => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $loaibienthe = Loaibienthe::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Tạo loại biến thể thành công',
            'data'    => new LoaibientheResources($loaibienthe)
        ], Response::HTTP_CREATED);
    }

    /**
     * Lấy chi tiết loại biến thể.
     */
    public function show(Request $request, string $id)
    {
        $query = Loaibienthe::with(['bienthesps', 'sanphams']);

        // // Admin có thể xem cả soft deleted
        // if (!optional($request->user())->isAdmin()) {
        //     $query->whereNull('deleted_at');
        // }

        $loaibienthe = $query->findOrFail($id);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Chi tiết loại biến thể',
            'data'    => new LoaibientheResources($loaibienthe)
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật loại biến thể.
     */
    public function update(Request $request, string $id)
    {
        $loaibienthe = Loaibienthe::findOrFail($id);

        $validated = $request->validate([
            'ten'       => 'sometimes|required|string|max:255|unique:loai_bienthe,ten,' . $id,
            'trangthai' => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $loaibienthe->update($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật loại biến thể thành công',
            'data'    => new LoaibientheResources($loaibienthe)
        ], Response::HTTP_OK);
    }

    /**
     * Xóa loại biến thể (chỉ khi không còn biến thể sản phẩm liên quan).
     */
    public function destroy(Request $request, string $id)
    {
        $loaibienthe = Loaibienthe::findOrFail($id);

        if ($loaibienthe->bienthesps()->count() > 0) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không thể xóa! Loại biến thể này vẫn còn biến thể sản phẩm.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $loaibienthe->delete();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Xóa loại biến thể thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

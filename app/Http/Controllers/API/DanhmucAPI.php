<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Danhmuc;
use App\Http\Resources\DanhmucResources;
use Illuminate\Http\Response;

class DanhmucAPI extends Controller
{
    /**
     * Lấy danh sách danh mục (có phân trang + đếm số sản phẩm).
     * Phân quyền hiển thị theo user.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = Danhmuc::withCount('sanphams');

        // Nếu không phải admin (hoặc chưa đăng nhập), loại bỏ soft deleted
        if (!optional($request->user())->isAdmin()) {
            $query->whereNull('deleted_at');
        }

        $items = $query->latest('updated_at')->paginate($perPage);

        return DanhmucResources::collection($items)
            ->additional([
                'status' => true,
                'message' => 'Danh sách danh mục',
                'meta' => [
                    'current_page' => $items->currentPage(),
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $items->perPage(),
                    'total'        => $items->total(),
                    'next_page_url'=> $items->nextPageUrl(),
                    'prev_page_url'=> $items->previousPageUrl(),
                ]
            ]);
    }

    /**
     * Tạo mới danh mục.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten' => 'required|string|max:255|unique:danh_muc,ten',
            'trangthai' => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $dm = Danhmuc::create($validated);

        return (new DanhmucResources($dm))
            ->additional([
                'status' => true,
                'message' => 'Tạo danh mục thành công'
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Lấy chi tiết danh mục.
     */
    public function show(Request $request, string $id)
    {
        $query = Danhmuc::withCount('sanphams');

        // Admin có thể xem cả soft deleted
        if (!optional($request->user())->isAdmin()) {
            $query->whereNull('deleted_at');
        }

        $dm = $query->findOrFail($id);

        return (new DanhmucResources($dm))
            ->additional([
                'status' => true,
                'message' => 'Chi tiết danh mục'
            ]);
    }

    /**
     * Cập nhật danh mục.
     */
    public function update(Request $request, string $id)
    {
        $dm = Danhmuc::findOrFail($id);

        $validated = $request->validate([
            'ten' => 'sometimes|required|string|max:255|unique:danh_muc,ten,' . $id,
            'trangthai' => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $dm->update($validated);

        return (new DanhmucResources($dm))
            ->additional([
                'status' => true,
                'message' => 'Cập nhật danh mục thành công'
            ]);
    }

    /**
     * Xóa danh mục (chỉ khi không còn sản phẩm).
     */
    public function destroy(Request $request, string $id)
    {
        $dm = Danhmuc::findOrFail($id);

        if ($dm->sanpham()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể xóa! Danh mục này vẫn còn sản phẩm.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dm->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa danh mục thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

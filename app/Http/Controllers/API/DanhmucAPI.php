<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Danhmuc;
use App\Http\Resources\DanhmucResources;
use Illuminate\Http\Response;

class DanhmucAPI extends BaseController
{
    /**
     * Lấy danh sách danh mục (có phân trang + đếm số sản phẩm).
     * Phân quyền hiển thị theo user.
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = Danhmuc::with(['sanphams'])
            ->withCount('sanphams')
            ->when(!optional($request->user())->isAdmin(), function ($query) {
                $query->whereNull('deleted_at');
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('trangthai', 'like', "%$q%")
                        ->orWhereHas('sanphams', function ($sp) use ($q) {
                            $sp->where('ten', 'like', "%$q%");
                        });
                });
            });

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
            'message' => 'Danh sách danh mục',
            'data'    => DanhmucResources::collection($items),
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
     * Tạo mới danh mục.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten' => 'required|string|max:255|unique:danh_muc,ten',
            'trangthai' => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $dm = Danhmuc::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo danh mục thành công',
            'data' => new DanhmucResources($dm)
        ], Response::HTTP_CREATED);
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

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết danh mục',
            'data' => new DanhmucResources($dm)
        ], Response::HTTP_OK);
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

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật danh mục thành công',
            'data' => new DanhmucResources($dm)
        ], Response::HTTP_OK);
    }

    /**
     * Xóa danh mục (chỉ khi không còn sản phẩm).
     */
    public function destroy(Request $request, string $id)
    {
        $dm = Danhmuc::findOrFail($id);

        if ($dm->sanphams()->count() > 0) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không thể xóa! Danh mục này vẫn còn sản phẩm.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dm->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa danh mục thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

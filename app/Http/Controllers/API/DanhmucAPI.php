<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\DanhmucModel;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


/**
 * @OA\Schema(
 *     schema="Danhmuc",
 *     type="object",
 *     title="Danh mục",
 *     description="Thông tin danh mục sản phẩm",
 *     @OA\Property(property="id", type="integer", example=1, description="ID danh mục"),
 *     @OA\Property(property="ten", type="string", example="Điện thoại", description="Tên danh mục"),
 *     @OA\Property(property="slug", type="string", example="dien-thoai", description="Đường dẫn thân thiện"),
 *     @OA\Property(property="logo", type="string", example="danhmuc.jpg", description="Ảnh hoặc logo danh mục"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-10T12:00:00Z", description="Ngày tạo"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-12T14:30:00Z", description="Ngày cập nhật")
 * )
 */
class DanhmucAPI extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/danhmucs",
     *     tags={"Danh mục"},
     *     summary="Lấy danh sách danh mục (phân trang + tìm kiếm)",
     *     description="Trả về danh sách danh mục có hỗ trợ tìm kiếm theo tên hoặc slug, và tự động phân trang bằng query ?page=",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Từ khóa tìm kiếm theo tên hoặc slug",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số lượng danh mục mỗi trang (mặc định 10)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách danh mục"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="ten", type="string", example="Điện thoại"),
     *                 @OA\Property(property="slug", type="string", example="dien-thoai"),
     *                 @OA\Property(property="logo", type="string", example="danhmuc.jpg")
     *             )),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=50),
     *                 @OA\Property(property="next_page_url", type="string", example="http://localhost/api/danhmucs?page=2"),
     *                 @OA\Property(property="prev_page_url", type="string", example=null)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $q = $request->get('q');

        $query = DanhmucModel::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        // Laravel tự động lấy ?page= từ query string
        $items = $query->latest('updated_at')->paginate($perPage);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách danh mục',
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'next_page_url'=> $items->nextPageUrl(),
                'prev_page_url'=> $items->previousPageUrl(),
            ],
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới danh mục
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten' => 'required|string|max:255|unique:danhmuc,ten',
            'logo' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['ten']);
        $validated['logo'] = $validated['logo'] ?? 'danhmuc.jpg';

        $dm = DanhmucModel::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo danh mục thành công',
            'data' => $dm,
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/danhmucs/{id}",
     *     tags={"Danh mục"},
     *     summary="Xem chi tiết 1 danh mục",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID danh mục",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chi tiết danh mục"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="ten", type="string", example="Điện thoại"),
     *                 @OA\Property(property="slug", type="string", example="dien-thoai"),
     *                 @OA\Property(property="logo", type="string", example="danhmuc.jpg")
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $dm = DanhmucModel::findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết danh mục',
            'data' => $dm,
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id)
    {
        $dm = DanhmucModel::findOrFail($id);

        $validated = $request->validate([
            'ten' => 'sometimes|required|string|max:255|unique:danhmuc,ten,' . $id,
            'logo' => 'nullable|string|max:255',
        ]);

        if (isset($validated['ten'])) {
            $validated['slug'] = Str::slug($validated['ten']);
        }

        $dm->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật danh mục thành công',
            'data' => $dm,
        ], Response::HTTP_OK);
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id)
    {
        $dm = DanhmucModel::findOrFail($id);
        $dm->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa danh mục thành công',
        ], Response::HTTP_OK);
    }
    // thiếu xóa mềm rồi
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuatangsukienModel;
use Illuminate\Http\Response;


/**
 * @OA\Tag(
 *     name="Quà tặng sự kiện",
 *     description="API quản lý quà tặng khuyến mãi trong các chương trình sự kiện"
 * )
 */
class QuaTangSuKienAPI extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/quatangkhuyenmais",
     *     tags={"Quà tặng sự kiện"},
     *     summary="Lấy danh sách quà tặng sự kiện (phân trang, tìm kiếm)",
     *     description="Trả về danh sách quà tặng có thể tìm kiếm theo tiêu đề hoặc tên sản phẩm, có phân trang.",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Từ khóa tìm kiếm (theo tiêu đề, tên biến thể hoặc sự kiện)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Trang hiện tại",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng phần tử mỗi trang",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách quà tặng sự kiện",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách quà tặng sự kiện"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     */
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

     /**
     * @OA\Get(
     *     path="/api/quatangkhuyenmais/{id}",
     *     tags={"Quà tặng sự kiện"},
     *     summary="Xem chi tiết 1 quà tặng sự kiện",
     *     description="Trả về thông tin chi tiết 1 quà tặng sự kiện theo ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của quà tặng sự kiện",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết quà tặng sự kiện",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chi tiết quà tặng sự kiện"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy quà tặng sự kiện")
     * )
     */
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

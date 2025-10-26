<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SukienModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Chương trình & Sự kiện",
 *     description="API xem danh sách và chi tiết các chương trình, sự kiện đang hoạt động"
 * )
 */
class SuKienAPI extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/chuongtrinhsukiens",
     *     tags={"Chương trình & Sự kiện"},
     *     summary="Lấy danh sách sự kiện (có hỗ trợ tìm kiếm và phân trang)",
     *     description="Trả về danh sách các sự kiện đang có trong hệ thống, có thể tìm kiếm bằng từ khóa hoặc phân trang.",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Từ khóa tìm kiếm (theo tiêu đề, slug hoặc nội dung)",
     *         required=false,
     *         @OA\Schema(type="string", example="khuyến mãi")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số lượng kết quả mỗi trang (mặc định: 10)",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sự kiện",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách sự kiện"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="tieude", type="string", example="Sự kiện Black Friday"),
     *                         @OA\Property(property="slug", type="string", example="su-kien-black-friday"),
     *                         @OA\Property(property="hinhanh", type="string", example="event.jpg"),
     *                         @OA\Property(property="noidung", type="string", example="Giảm giá toàn bộ sản phẩm 50%"),
     *                         @OA\Property(property="ngaybatdau", type="string", format="date", example="2025-11-01"),
     *                         @OA\Property(property="ngayketthuc", type="string", format="date", example="2025-11-30"),
     *                         @OA\Property(property="trangthai", type="string", example="Hiển thị")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=15)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $q = $request->get('q'); // từ khóa tìm kiếm

        $query = SukienModel::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('tieude', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%")
                    ->orWhere('noidung', 'like', "%$q%");
            });
        }

        $items = $query->latest('created_at')->paginate($perPage);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách sự kiện',
            'data' => $items,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/chuongtrinhsukiens/{id}",
     *     tags={"Chương trình & Sự kiện"},
     *     summary="Lấy chi tiết một sự kiện",
     *     description="Trả về thông tin chi tiết của một sự kiện theo ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sự kiện cần xem chi tiết",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết sự kiện",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chi tiết sự kiện"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="tieude", type="string", example="Sự kiện Noel 2025"),
     *                 @OA\Property(property="slug", type="string", example="su-kien-noel-2025"),
     *                 @OA\Property(property="hinhanh", type="string", example="noel.jpg"),
     *                 @OA\Property(property="noidung", type="string", example="Mua 1 tặng 1 trong mùa Noel"),
     *                 @OA\Property(property="ngaybatdau", type="string", format="date", example="2025-12-01"),
     *                 @OA\Property(property="ngayketthuc", type="string", format="date", example="2025-12-25"),
     *                 @OA\Property(property="trangthai", type="string", example="Hiển thị")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy sự kiện")
     * )
     */
    public function show($id)
    {
        $item = SukienModel::findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết sự kiện',
            'data' => $item,
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới một sự kiện (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tieude'      => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:sukien,slug',
            'hinhanh'     => 'required|string|max:255',
            'noidung'     => 'required|string',
            'ngaybatdau'  => 'required|date',
            'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau',
            'trangthai'   => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $item = SukienModel::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo sự kiện thành công',
            'data' => $item,
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật thông tin sự kiện
     */
    public function update(Request $request, $id)
    {
        $item = SukienModel::findOrFail($id);

        $validated = $request->validate([
            'tieude'      => 'sometimes|required|string|max:255',
            'slug'        => 'sometimes|required|string|max:255|unique:sukien,slug,' . $item->id,
            'hinhanh'     => 'sometimes|required|string|max:255',
            'noidung'     => 'sometimes|required|string',
            'ngaybatdau'  => 'sometimes|required|date',
            'ngayketthuc' => 'sometimes|required|date|after_or_equal:ngaybatdau',
            'trangthai'   => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật sự kiện thành công',
            'data' => $item,
        ], Response::HTTP_OK);
    }

    /**
     * Xóa mềm sự kiện
     */
    public function destroy($id)
    {
        $item = SukienModel::findOrFail($id);
        $item->delete(); // nhờ SoftDeletes => chỉ cập nhật deleted_at

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đã xóa (ẩn) sự kiện thành công',
        ], Response::HTTP_OK);
    }

    /**
     * Khôi phục sự kiện đã xóa mềm
     */
    public function restore($id)
    {
        $item = SukienModel::onlyTrashed()->findOrFail($id);
        $item->restore();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Khôi phục sự kiện thành công',
            'data' => $item,
        ], Response::HTTP_OK);
    }

    /**
     * Xóa vĩnh viễn sự kiện (hard delete)
     */
    public function forceDelete($id)
    {
        $item = SukienModel::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đã xóa vĩnh viễn sự kiện',
        ], Response::HTTP_OK);
    }
}

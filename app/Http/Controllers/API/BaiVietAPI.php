<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BaivietModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;


/**
 * @OA\Schema(
 *     schema="Baiviet",
 *     title="Bài viết",
 *     description="Thông tin chi tiết bài viết",
 *     @OA\Property(property="id", type="integer", example=1, description="ID bài viết"),
 *     @OA\Property(property="id_nguoidung", type="integer", example=5, description="ID người dùng tạo bài viết"),
 *     @OA\Property(property="tieude", type="string", example="Tiêu đề bài viết", description="Tiêu đề bài viết"),
 *     @OA\Property(property="slug", type="string", example="tieu-de-bai-viet", description="Slug URL của bài viết"),
 *     @OA\Property(property="noidung", type="string", example="Nội dung chi tiết bài viết...", description="Nội dung bài viết"),
 *     @OA\Property(property="luotxem", type="integer", example=125, description="Số lượt xem bài viết"),
 *     @OA\Property(property="hinhanh", type="string", example="images/bai-viet/1.jpg", description="Hình ảnh đại diện bài viết"),
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Hiển thị","Tạm ẩn"},
 *         example="Hiển thị",
 *         description="Trạng thái hiển thị bài viết"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-15T19:53:24Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-15T19:55:40Z"),
 *     @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
 * )
 */
class BaiVietAPI extends BaseController
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/api/baiviets",
     *     tags={"Bài viết"},
     *     summary="Lấy danh sách bài viết đang hiển thị",
     *     description="Hỗ trợ phân trang và tìm kiếm theo từ khóa",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Trang hiện tại",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số lượng bài viết trên một trang",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Từ khóa tìm kiếm theo tiêu đề hoặc nội dung",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách bài viết thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trang không tồn tại"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page    = $request->get('page', 1);
        $q       = $request->get('q');

        $query = BaivietModel::where('trangthai', 'Hiển thị')->orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('tieude', 'like', "%$q%")
                    ->orWhere('noidung', 'like', "%$q%");
            });
        }

        $items = $query->paginate($perPage, ['*'], 'page', $page);

        if ($page > $items->lastPage() && $page > 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Danh sách bài viết',
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/baiviets/{id}",
     *     tags={"Bài viết"},
     *     summary="Xem chi tiết một bài viết",
     *     description="Chỉ xem bài viết đang hiển thị, lượt xem sẽ tự động tăng",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của bài viết",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết bài viết thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bài viết không tồn tại hoặc đang tạm ẩn"
     *     )
     * )
     */
    public function show($id)
    {
        $baiviet = BaivietModel::where('id', $id)
            ->where('trangthai', 'Hiển thị')
            ->first();

        if (!$baiviet) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Bài viết không tồn tại hoặc đang tạm ẩn',
            ], Response::HTTP_NOT_FOUND);
        }

        // Tăng lượt xem
        $baiviet->increment('luotxem');

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết bài viết',
            'data' => $baiviet,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * Chỉ admin mới sử dụng, frontend không cần
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Chỉ admin mới sử dụng, frontend không cần
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * Chỉ admin mới sử dụng, frontend không cần
     */
    public function destroy(string $id)
    {
        //
    }
}

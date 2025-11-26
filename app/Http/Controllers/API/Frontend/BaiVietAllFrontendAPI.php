<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\BaiVietAllResource;
use App\Http\Resources\Frontend\BaiVietResource;
use App\Models\BaivietModel;
use Illuminate\Http\Request;

class BaiVietAllFrontendAPI extends BaseFrontendController
{

    /**
     * @OA\Get(
     *     path="/baiviets-all",
     *     tags={"Bài Viết"},
     *     summary="Lấy danh sách bài viết với phân trang và lọc tiêu đề, nội dung",
     *     description="Trả về danh sách bài viết đang 'Hiển thị', hỗ trợ lọc theo từ khóa trên tiêu đề hoặc nội dung, có phân trang.",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         required=false,
     *         description="Từ khóa lọc bài viết theo tiêu đề hoặc nội dung",
     *         @OA\Schema(type="string", example="khuyến mãi")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Số trang phân trang",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách bài viết phân trang",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BaiVietResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', null);

        $baiviets = BaivietModel::query();
        if ($filter) {
            $baiviets->where(function ($query) use ($filter) {
                $query->where('tieude', 'like', "%{$filter}%")
                    ->orWhere('noidung', 'like', "%{$filter}%");
            });
        }
        $result = $baiviets->orderBy('id', 'desc')->paginate(10);
        return $this->jsonResponse([
            'data' => BaiVietAllResource::collection($result->items()),
            'pagination' => [
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/baiviets-all/{id}",
     *     tags={"Bài Viết"},
     *     summary="Lấy chi tiết bài viết theo ID hoặc slug, tự động tăng lượt xem",
     *     description="Trả về thông tin chi tiết bài viết dựa trên ID hoặc slug. Tăng lượt xem bài viết lên 1. Kèm danh sách 2 bài viết tương tự dựa trên tiêu đề.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID hoặc slug của bài viết cần lấy chi tiết",
     *         @OA\Schema(type="string", example="5 hoặc 'bai-viet-mau'")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết bài viết và danh sách bài viết tương tự",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BaiVietResource"
     *             ),
     *             @OA\Property(
     *                 property="baiviet_tuongtu",
     *                 type="array",
     *                 description="Danh sách bài viết tương tự",
     *                 @OA\Items(ref="#/components/schemas/BaiVietResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy bài viết"
     *     )
     * )
     */
    public function show(string $id)
    {
        if (is_numeric($id)) {
            $baiviet = BaivietModel::where('id', $id)->first(); // firstOrFail() 404 luôn
        } else {
            // Nếu $id không phải số → xem nó là slug
            $baiviet = BaivietModel::where('slug', $id)->first();  // firstOrFail() 404 luôn
        }

        // Không tìm thấy
        if (!$baiviet) {
            return $this->error('Không tìm thấy bài viết', [], 404);
        }

        $baiviet->increment('luotxem');

        // Lấy bài viết liên quan: cùng chuyên mục, khác id hiện tại, giới hạn 2 bài
        $keyword = $baiviet->tieude;
        $baitviets = BaivietModel::where('tieude', 'like', "%{$keyword}%")
                ->where('id', '!=', $baiviet->id)
                ->limit(2)
                ->get();

        return $this->jsonResponse([
            'data' => new BaiVietResource($baiviet),
            'baiviet_tuongtu' => BaiVietResource::collection($baitviets),
        ]);
    }


    // public function getPreviousAndNext(int $id)
    // {
    //     $current = BaivietModel::find($id);
    //     if (!$current) {
    //         return $this->error('Không tìm thấy bài viết hiện tại', [], 404);
    //     }

    //     // Bài viết trước (id < $id, lấy bản ghi lớn nhất nhỏ hơn $id)
    //     $previous = BaivietModel::where('id', '<', $id)
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     // Bài viết sau (id > $id, lấy bản ghi nhỏ nhất lớn hơn $id)
    //     $next = BaivietModel::where('id', '>', $id)
    //         ->orderBy('id', 'asc')
    //         ->first();

    //     return $this->jsonResponse([
    //         'previous' => $previous ? [
    //             'id' => $previous->id,
    //             'title' => $previous->title ?? $previous->ten ?? '', // tùy trường tên bài viết
    //             'slug' => $previous->slug,
    //         ] : null,
    //         'next' => $next ? [
    //             'id' => $next->id,
    //             'title' => $next->title ?? $next->ten ?? '',
    //             'slug' => $next->slug,
    //         ] : null,
    //     ]);
    // }
}

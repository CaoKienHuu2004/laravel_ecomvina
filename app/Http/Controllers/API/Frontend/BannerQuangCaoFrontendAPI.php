<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\QuangcaoModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @OA\Schema(
 *     schema="Quangcao",
 *     type="object",
 *     title="Banner Quảng Cáo",
 *     description="Thông tin banner quảng cáo hiển thị trên trang web",
 *     @OA\Property(property="id", type="integer", example=1, description="ID của banner"),
 *     @OA\Property(
 *         property="vitri",
 *         type="string",
 *         example="home_banner_slider",
 *         description="Vị trí hiển thị banner (ví dụ: home_banner_slider, home_banner_event_1, v.v.)"
 *     ),
 *     @OA\Property(
 *         property="hinhanh",
 *         type="string",
 *         example="banner1.jpg",
 *         description="Đường dẫn hình ảnh banner"
 *     ),
 *     @OA\Property(
 *         property="lienket",
 *         type="string",
 *         example="https://shopee.tw",
 *         description="Liên kết khi người dùng nhấp vào banner"
 *     ),
 *     @OA\Property(
 *         property="mota",
 *         type="string",
 *         example="Banner quảng cáo sản phẩm hot tháng 10",
 *         description="Mô tả nội dung banner"
 *     ),
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Hiển thị", "Tạm ẩn"},
 *         example="Hiển thị",
 *         description="Trạng thái hiển thị của banner"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-15T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-15T10:30:00Z")
 * )
 */
class BannerQuangCaoFrontendAPI extends BaseFrontendController
{
    /**
     * @OA\Get(
     *     path="/api/bannerquangcaos",
     *     summary="Lấy danh sách banner quảng cáo",
     *     description="Trả về danh sách tất cả banner quảng cáo (có thể tìm kiếm và phân trang).",
     *     tags={"Banner Quảng Cáo"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Từ khóa để tìm kiếm theo mô tả hoặc vị trí banner",
     *         @OA\Schema(type="string", example="event")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng banner trên mỗi trang (nếu muốn phân trang)",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách banner quảng cáo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách banner"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Quangcao")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="per_page", type="integer", example=5),
     *                 @OA\Property(property="total", type="integer", example=15),
     *                 @OA\Property(property="next_page_url", type="string", example="http://localhost:8000/api/bannerquangcaos?page=2"),
     *                 @OA\Property(property="prev_page_url", type="string", example=null)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = QuangcaoModel::query();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('mota', 'like', "%{$q}%")
                  ->orWhere('vitri', 'like', "%{$q}%");
        }

        if ($request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 5);
            $banners = $query->orderByDesc('created_at')->paginate($perPage);

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Danh sách banner',
                'data' => $banners->items(),
                'meta' => [
                    'current_page' => $banners->currentPage(),
                    'last_page' => $banners->lastPage(),
                    'per_page' => $banners->perPage(),
                    'total' => $banners->total(),
                    'next_page_url' => $banners->nextPageUrl(),
                    'prev_page_url' => $banners->previousPageUrl(),
                ]
            ], Response::HTTP_OK);
        }

        $banners = $query->orderByDesc('created_at')->get();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách banner',
            'data' => $banners
        ], Response::HTTP_OK);
    }

    /**
     * ➕ Tạo mới banner quảng cáo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vitri' => 'required|string|max:255',
            'hinhanh' => 'required|string|max:255',
            'lienket' => 'required|string',
            'mota' => 'nullable|string',
            'trangthai' => 'in:Hiển thị,Tạm ẩn',
        ]);

        $banner = QuangcaoModel::create([
            'vitri' => $validated['vitri'],
            'hinhanh' => $validated['hinhanh'],
            'lienket' => $validated['lienket'],
            'mota' => $validated['mota'] ?? null,
            'trangthai' => $validated['trangthai'] ?? 'Hiển thị',
        ]);

        return $this->jsonResponse([
            'status' => true,
            'message' => '🟢 Tạo banner thành công',
            'data' => $banner
        ], Response::HTTP_CREATED);
    }

    /**
     * 🔍 Xem chi tiết banner
     */
    public function show($id)
    {
        $banner = QuangcaoModel::findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết banner',
            'data' => $banner
        ], Response::HTTP_OK);
    }

    /**
     * ✏️ Cập nhật banner
     */
    public function update(Request $request, $id)
    {
        $banner = QuangcaoModel::findOrFail($id);

        $validated = $request->validate([
            'vitri' => 'sometimes|string|max:255',
            'hinhanh' => 'sometimes|string|max:255',
            'lienket' => 'sometimes|string',
            'mota' => 'sometimes|string|nullable',
            'trangthai' => 'sometimes|in:Hiển thị,Tạm ẩn',
        ]);

        $banner->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => '🟡 Cập nhật banner thành công',
            'data' => $banner
        ], Response::HTTP_OK);
    }

    /**
     * ❌ Xóa banner
     */
    public function destroy($id)
    {
        $banner = QuangcaoModel::findOrFail($id);
        $banner->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => '🔴 Xóa banner thành công'
        ], Response::HTTP_OK);
    }
}

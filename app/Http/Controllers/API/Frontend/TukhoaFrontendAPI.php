<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TukhoaModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @OA\Schema(
 *     schema="Tukhoa",
 *     type="object",
 *     title="Từ khóa",
 *     description="Thông tin từ khóa được tìm kiếm hoặc truy cập nhiều",
 *     @OA\Property(property="id", type="integer", example=1, description="ID của từ khóa"),
 *     @OA\Property(property="tukhoa", type="string", example="iPhone 15 Pro", description="Nội dung từ khóa"),
 *     @OA\Property(property="luottruycap", type="integer", example=125, description="Số lượt truy cập của từ khóa")
 * )
 */
class TukhoaFrontendAPI extends BaseFrontendController
{
    /**
     * @OA\Get(
     *     path="/api/tukhoas",
     *     tags={"Từ khóa"},
     *     summary="Lấy danh sách từ khóa (có thể tìm kiếm, phân trang)",
     *     description="Trả về danh sách các từ khóa phổ biến được tìm kiếm nhiều nhất.",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Tìm kiếm theo từ khóa",
     *         @OA\Schema(type="string", example="giày thể thao")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng bản ghi mỗi trang",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách từ khóa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách từ khóa"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Tukhoa")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="per_page", type="integer", example=5),
     *                 @OA\Property(property="total", type="integer", example=15)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $q = $request->get('q');

        $query = TukhoaModel::query();

        if ($q) {
            $query->where('tukhoa', 'like', "%{$q}%");
        }

        $tuKhoa = $query->orderByDesc('luottruycap')->paginate($perPage);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách từ khóa',
            'data' => $tuKhoa->items(),
            'meta' => [
                'current_page' => $tuKhoa->currentPage(),
                'last_page' => $tuKhoa->lastPage(),
                'per_page' => $tuKhoa->perPage(),
                'total' => $tuKhoa->total(),
                'next_page_url' => $tuKhoa->nextPageUrl(),
                'prev_page_url' => $tuKhoa->previousPageUrl(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/tukhoas",
     *     tags={"Từ khóa"},
     *     summary="Tạo mới một từ khóa",
     *     description="Thêm từ khóa mới vào hệ thống.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tukhoa"},
     *             @OA\Property(property="tukhoa", type="string", example="áo sơ mi nữ"),
     *             @OA\Property(property="luottruycap", type="integer", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo từ khóa thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="✅ Tạo từ khóa thành công"),
     *             @OA\Property(property="data", ref="#/components/schemas/Tukhoa")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tukhoa' => 'required|string|max:255',
            'luottruycap' => 'nullable|integer|min:0',
        ]);

        $tuKhoa = TukhoaModel::create([
            'tukhoa' => $validated['tukhoa'],
            'luottruycap' => $validated['luottruycap'] ?? 0,
        ]);

        return $this->jsonResponse([
            'status' => true,
            'message' => '✅ Tạo từ khóa thành công',
            'data' => $tuKhoa,
        ], Response::HTTP_CREATED);
    }

    /**
     * Hiển thị chi tiết một từ khóa
     */
    public function show($id)
    {
        $tuKhoa = TukhoaModel::findOrFail($id);

        $tuKhoa->increment('luottruycap');
        $tuKhoa->refresh();

        return $this->jsonResponse([
            'status' => true,
            'message' => '📄 Chi tiết từ khóa',
            'data' => $tuKhoa,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/tukhoas/{id}",
     *     tags={"Từ khóa"},
     *     summary="Cập nhật hoặc tăng lượt truy cập cho từ khóa",
     *     description="Cập nhật thông tin từ khóa hoặc tự động tăng số lượt truy cập lên 1 nếu không truyền dữ liệu.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của từ khóa cần cập nhật",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="tukhoa", type="string", example="áo thun nam"),
     *             @OA\Property(property="luottruycap", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="🔄 Cập nhật từ khóa thành công"),
     *             @OA\Property(property="data", ref="#/components/schemas/Tukhoa")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $tuKhoa = TukhoaModel::findOrFail($id);

        // Nếu có dữ liệu cập nhật cụ thể
        if ($request->has('tukhoa') || $request->has('luottruycap')) {
            $validated = $request->validate([
                'tukhoa' => 'sometimes|string|max:255',
                'luottruycap' => 'sometimes|integer|min:0',
            ]);

            $tuKhoa->update($validated);
        } else {
            // Nếu không có dữ liệu cụ thể thì tăng lượt truy cập lên 1
            $tuKhoa->increment('luottruycap');
            $tuKhoa->refresh();
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => '🔄 Cập nhật từ khóa thành công',
            'data' => $tuKhoa,
        ], Response::HTTP_OK);
    }

    /**
     * Xóa từ khóa
     */
    public function destroy($id)
    {
        $tuKhoa = TukhoaModel::findOrFail($id);
        $tuKhoa->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => '🗑️ Xóa từ khóa thành công',
        ], Response::HTTP_OK);
    }
}

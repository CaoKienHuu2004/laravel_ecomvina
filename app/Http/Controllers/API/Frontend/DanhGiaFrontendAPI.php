<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DanhgiaModel;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="DanhGia",
 *     type="object",
 *     title="Đánh giá sản phẩm",
 *     description="Thông tin chi tiết của một đánh giá sản phẩm do người dùng tạo",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID tự tăng của đánh giá",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="id_sanpham",
 *         type="integer",
 *         description="ID sản phẩm được đánh giá",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="id_chitietdonhang",
 *         type="integer",
 *         description="ID chi tiết đơn hàng liên quan đến đánh giá",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="id_nguoidung",
 *         type="integer",
 *         description="ID người dùng đã tạo đánh giá",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="diem",
 *         type="integer",
 *         description="Số điểm đánh giá (thường từ 1 đến 5)",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="noidung",
 *         type="string",
 *         nullable=true,
 *         description="Nội dung bình luận đánh giá sản phẩm",
 *         example="Sản phẩm rất tốt, đóng gói cẩn thận"
 *     ),
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Hiển thị", "Tạm ẩn"},
 *         description="Trạng thái hiển thị của đánh giá",
 *         example="Hiển thị"
 *     )
 * )
 */
class DanhGiaFrontendAPI extends Controller
{
    /**
     * @OA\Schema(
     *     schema="DanhGia",
     *     type="object",
     *     title="Đánh giá sản phẩm",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="id_nguoidung", type="integer", example=5),
     *     @OA\Property(property="id_chitietdonhang ", type="integer", example=5),
     *     @OA\Property(property="id_sanpham", type="integer", example=10),
     *     @OA\Property(property="diem", type="integer", example=5),
     *     @OA\Property(property="noidung", type="string", example="Sản phẩm rất tốt!"),
     *     @OA\Property(property="trangthai", type="string", example="Hiển thị"),
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/toi/danhgias",
     *     tags={"Đánh giá (tôi)"},
     *     summary="Lấy danh sách đánh giá của người dùng đã đăng nhập",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách đánh giá của người dùng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/DanhGia")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->get('auth_user');

        $danhgias = DanhgiaModel::with('sanpham','chitietdonhang')
            ->where('id_nguoidung', $user->id)
            ->orderByDesc('created_at')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $danhgias
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/toi/danhgias",
     *     tags={"Đánh giá (tôi)"},
     *     summary="Thêm đánh giá mới (chỉ cho sản phẩm đã mua)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_sanpham", "diem"},
     *             @OA\Property(property="id_sanpham", type="integer", example=1),
     *             @OA\Property(property="diem", type="integer", example=5),
     *             @OA\Property(property="noidung", type="string", example="Sản phẩm rất tốt!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm đánh giá thành công",
     *         @OA\JsonContent(ref="#/components/schemas/DanhGia")
     *     ),
     *     @OA\Response(response=403, description="Chưa mua hàng hoặc không hợp lệ"),
     *     @OA\Response(response=400, description="Đã đánh giá sản phẩm này rồi")
     * )
     */
    public function store(Request $request)
    {
        $user = $request->get('auth_user');

        $validated = $request->validate([
            'id_sanpham' => 'required|exists:sanpham,id',
            'diem'       => 'required|integer|min:1|max:5',
            'noidung'    => 'nullable|string',
        ]);

        // ✅ Tìm chi tiết đơn hàng mà user đã mua và giao thành công
        $chiTiet = DB::table('chitiet_donhang')
            ->join('donhang', 'chitiet_donhang.id_donhang', '=', 'donhang.id')
            ->join('bienthe', 'chitiet_donhang.id_bienthe', '=', 'bienthe.id')
            ->join('sanpham', 'bienthe.id_sanpham', '=', 'sanpham.id')
            ->where('donhang.id_nguoidung', $user->id)
            ->where('sanpham.id', $validated['id_sanpham'])
            ->whereIn('donhang.trangthai', ['Đã giao', 'Hoàn tất'])
            ->select('chitiet_donhang.id')
            ->first();

        if (!$chiTiet) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ có thể đánh giá sản phẩm đã mua và giao thành công.'
            ], Response::HTTP_FORBIDDEN);
        }

        // ✅ Kiểm tra đã đánh giá chưa (theo 3 trường duy nhất)
        $daDanhGia = DanhgiaModel::where('id_nguoidung', $user->id)
            ->where('id_sanpham', $validated['id_sanpham'])
            ->where('id_chitietdonhang', $chiTiet->id)
            ->exists();

        if ($daDanhGia) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá sản phẩm này rồi.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // ✅ Lưu đánh giá mới
        $danhgia = DanhgiaModel::create([
            'id_nguoidung'       => $user->id,
            'id_sanpham'         => $validated['id_sanpham'],
            'id_chitietdonhang'  => $chiTiet->id,
            'diem'               => $validated['diem'],
            'noidung'            => $validated['noidung'] ?? null,
            'trangthai'          => 'Hiển thị',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm đánh giá thành công',
            'data'    => $danhgia->load(['nguoidung:id,hoten', 'sanpham:id,ten'])
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/toi/danhgias/{id}",
     *     tags={"Đánh giá (tôi)"},
     *     summary="Cập nhật đánh giá của chính mình",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="diem", type="integer", example=4),
     *             @OA\Property(property="noidung", type="string", example="Cập nhật nội dung đánh giá")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=403, description="Không có quyền hoặc không tìm thấy đánh giá")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = $request->get('auth_user');

        // ✅ Chỉ tìm đánh giá của chính người dùng, chưa bị xóa
        $danhgia = DanhgiaModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->whereNull('deleted_at')
            ->first();

        if (!$danhgia) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá hoặc bạn không có quyền'
            ], Response::HTTP_FORBIDDEN);
        }

        // ✅ Chỉ cho phép cập nhật điểm và nội dung
        $validated = $request->validate([
            'diem' => 'nullable|integer|min:1|max:5',
            'noidung' => 'nullable|string',
        ]);

        $danhgia->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật đánh giá thành công',
            'data' => $danhgia->load(['nguoidung:id,hoten', 'sanpham:id,ten'])
        ], Response::HTTP_OK);
    }


    /**
     * @OA\Delete(
     *     path="/api/toi/danhgias/{id}",
     *     tags={"Đánh giá (tôi)"},
     *     summary="Xóa đánh giá của chính mình",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa đánh giá thành công"),
     *     @OA\Response(response=403, description="Không có quyền hoặc không tìm thấy đánh giá")
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $danhgia = DanhgiaModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$danhgia) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá hoặc bạn không có quyền'
            ], Response::HTTP_FORBIDDEN);
        }

        $danhgia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa đánh giá thành công'
        ], Response::HTTP_OK);
    }
}

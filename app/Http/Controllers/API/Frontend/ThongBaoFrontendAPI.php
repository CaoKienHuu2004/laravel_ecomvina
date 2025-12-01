<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ThongbaoModel;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Schema(
 *     schema="Thongbao",
 *     type="object",
 *     title="Thông báo",
 *     description="Thông tin chi tiết một thông báo của người dùng",
 *     @OA\Property(property="id", type="integer", example=1, description="ID của thông báo"),
 *     @OA\Property(property="id_nguoidung", type="integer", example=5, description="ID người dùng nhận thông báo"),
 *     @OA\Property(property="tieude", type="string", example="Thông báo mới", description="Tiêu đề thông báo"),
 *     @OA\Property(property="noidung", type="string", example="Nội dung chi tiết thông báo", description="Nội dung thông báo"),
 *     @OA\Property(property="lienket", type="string", nullable=true, example="https://example.com", description="Đường dẫn liên kết kèm theo thông báo"),
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Đã đọc", "Chưa đọc", "Tạm ẩn"},
 *         example="Chưa đọc",
 *         description="Trạng thái của thông báo"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-15T09:30:00Z", description="Thời gian tạo thông báo"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-15T09:35:00Z", description="Thời gian cập nhật thông báo"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null, description="Thời gian xóa mềm thông báo nếu có")
 * )
 */
class ThongBaoFrontendAPI extends BaseFrontendController
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/toi/thongbaos",
     *     tags={"Thông báo (tôi)"},
     *     summary="Lấy danh sách tất cả thông báo của người dùng",
     *     description="Trả về danh sách thông báo của người dùng đang đăng nhập",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách thông báo thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách thông báo"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Thongbao")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->get('auth_user');

        $thongbaos = ThongbaoModel::where('id_nguoidung', $user->id)
            ->orderByRaw("FIELD(trangthai, 'Chưa đọc', 'Đã đọc', 'Tạm ẩn')")
            ->get();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách thông báo',
            'data' => $thongbaos,
        ], Response::HTTP_OK);
    }



    /**
     * @OA\Delete(
     *     path="/toi/thongbaos/{id}",
     *     tags={"Thông báo (tôi)"},
     *     summary="Xóa thông báo (soft delete)",
     *     description="Xóa mềm thông báo của người dùng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID thông báo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy thông báo")
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $thongbao = ThongbaoModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$thongbao) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy thông báo',
            ], Response::HTTP_NOT_FOUND);
        }

        $thongbao->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa thông báo thành công',
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Patch(
     *     path="/toi/thongbaos/{id}/daxem",
     *     tags={"Thông báo (tôi)"},
     *     summary="Đánh dấu thông báo đã đọc",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID thông báo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đánh dấu đã đọc thành công",
     *         @OA\JsonContent(ref="#/components/schemas/Thongbao")
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy thông báo")
     * )
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $thongbao = ThongbaoModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$thongbao) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy thông báo',
            ], Response::HTTP_NOT_FOUND);
        }

        $thongbao->update(['trangthai' => 'Đã đọc']);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đánh dấu đã đọc thành công',
            'data' => $thongbao,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Patch(
     *     path="/toi/thongbaos/{id}/tam-an",
     *     tags={"Thông báo (tôi)"},
     *     summary="Thay đổi trạng thái Tạm ẩn / Khác",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID thông báo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật trạng thái thành công",
     *         @OA\JsonContent(ref="#/components/schemas/Thongbao")
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy thông báo")
     * )
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $thongbao = ThongbaoModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$thongbao) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy thông báo',
            ], Response::HTTP_NOT_FOUND);
        }

        // Toggle giữa Tạm ẩn và Chưa đọc/Đã đọc (không cho tạm ẩn nếu đã đọc?)
        if ($thongbao->trangthai === 'Tạm ẩn') {
            $thongbao->update(['trangthai' => 'Chưa đọc']);
        } else {
            $thongbao->update(['trangthai' => 'Tạm ẩn']);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật trạng thái thông báo thành công',
            'data' => $thongbao,
        ], Response::HTTP_OK);
    }
}

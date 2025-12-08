<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ThongbaoResource;
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
 *
 *     @OA\Property(property="id", type="integer", example=55, description="ID của thông báo"),
 *     @OA\Property(property="tieude", type="string", example="Cập nhật thông tin cá nhân", description="Tiêu đề thông báo"),
 *     @OA\Property(property="noidung", type="string", example="Bạn vui lòng cập nhật thông tin cá nhân để hoàn thiện hồ sơ.", description="Nội dung thông báo"),
 *
 *     @OA\Property(
 *         property="lienket",
 *         type="string",
 *         nullable=true,
 *         example="http://148.230.100.215:3000/qua-tang",
 *         description="Đường dẫn liên kết kèm theo thông báo"
 *     ),
 *
 *     @OA\Property(
 *         property="loaithongbao",
 *         type="string",
 *         example="Hệ thống",
 *         description="Loại thông báo (Hệ thống, Khuyến mãi, Quà tặng, Đơn hàng, ...)"
 *     ),
 *
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Đã đọc", "Chưa đọc", "Tạm ẩn"},
 *         example="Chưa đọc",
 *         description="Trạng thái của thông báo"
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-12-02T16:57:27+07:00",
 *         description="Thời gian tạo thông báo"
 *     ),
 *
 *     @OA\Property(
 *         property="thoigian",
 *         type="string",
 *         example="5 phút trước",
 *         description="Thời gian tương đối kể từ khi tạo (diffForHumans)"
 *     )
 * )
 */
class ThongBaoFrontendAPI extends BaseFrontendController
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="api/tai-khoan/thongbaos",
     *     tags={"Thông báo (Tài khoản)"},
     *     summary="Lấy danh sách tất cả thông báo của người dùng",
     *     description="Trả về danh sách thông báo của người dùng đang đăng nhập",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách thông báo thành công",
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách thông báo"),
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
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
            'data' => ThongbaoResource::collection($thongbaos),
        ], Response::HTTP_OK);
    }




    // public function destroy(Request $request, $id)
    // {
    //     $user = $request->get('auth_user');

    //     $thongbao = ThongbaoModel::where('id', $id)
    //         ->where('id_nguoidung', $user->id)
    //         ->first();

    //     if (!$thongbao) {
    //         return $this->jsonResponse([
    //             'status' => false,
    //             'message' => 'Không tìm thấy thông báo',
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     $thongbao->delete();

    //     return $this->jsonResponse([
    //         'status' => true,
    //         'message' => 'Xóa thông báo thành công',
    //     ], Response::HTTP_OK);
    // }

    /**
     * @OA\Patch(
     *     path="api/tai-khoan/thongbaos/{id}/daxem",
     *     tags={"Thông báo (Tài khoản)"},
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
     *     path="api/tai-khoan/thongbaos/{id}/tam-an",
     *     tags={"Thông báo (Tài khoản)"},
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

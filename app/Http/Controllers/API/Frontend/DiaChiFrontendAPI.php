<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DiaChiGiaoHangModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="DiaChiNguoiDung",
 *     title="Địa chỉ người dùng",
 *     description="Thông tin địa chỉ của người dùng",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_nguoidung", type="integer", example=5, description="ID người dùng"),
 *     @OA\Property(property="hoten", type="string", example="Nguyễn Văn A", description="Họ tên người nhận"),
 *     @OA\Property(property="sodienthoai", type="string", example="0987654321", description="Số điện thoại liên hệ"),
 *     @OA\Property(property="diachi", type="string", example="101 Nguyễn Tất Thành, p1, q.12, TP.HCM", description="Địa chỉ chi tiết"),
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Mặc định","Khác","Tạm ẩn"},
 *         example="Mặc định",
 *         description="Trạng thái của địa chỉ"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-15T19:53:24Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-15T19:55:40Z"),
 *     @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
 * )
 */
class DiaChiFrontendAPI extends BaseFrontendController
{
    use ApiResponse;

    protected $provinces;

    public function __construct()
    {
        $this->provinces = config('tinhthanh');
    }

    /**
     * @OA\Get(
     *     path="/api/toi/diachis",
     *     summary="Lấy danh sách địa chỉ người dùng",
     *     description="Trả về danh sách tất cả địa chỉ của người dùng hiện tại, sắp xếp Mặc định lên đầu",
     *     tags={"Địa chỉ (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách địa chỉ thành công"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token không hợp lệ hoặc chưa đăng nhập"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->get('auth_user');

        $diachis = DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
            ->orderByRaw("FIELD(trangthai, 'Mặc định', 'Khác', 'Tạm ẩn')")
            ->get();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách địa chỉ',
            'data' => $diachis,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/toi/diachis",
     *     summary="Thêm địa chỉ mới",
     *     description="Người dùng thêm địa chỉ mới. Nếu chọn 'Mặc định', các địa chỉ khác sẽ chuyển thành 'Khác'.",
     *     tags={"Địa chỉ (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hoten","sodienthoai","diachi"},
     *             @OA\Property(property="hoten", type="string", example="Nguyễn Văn A"),
     *             @OA\Property(property="sodienthoai", type="string", example="0987654321"),
     *             @OA\Property(property="diachi", type="string", example="101 Nguyễn Tất Thành, Q.1, TP.HCM"),
     *             @OA\Property(property="trangthai", type="string", enum={"Mặc định","Khác","Tạm ẩn"}, example="Khác")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm địa chỉ thành công"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dữ liệu không hợp lệ"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        $trangthaiEnum = DiaChiGiaoHangModel::getEnumValues('trangthai');
        $user = $request->get('auth_user');

        $validated = $request->validate([
            'hoten' => 'required|string|max:255',
            'sodienthoai' => 'required|string|size:10',
            'diachi' => 'required|string',
            'diachi' => 'required|string',
            'tinhthanh' => ['required', 'string', Rule::in($provinceNames)],
            'trangthai' => 'required|in:' . implode(',', $trangthaiEnum),
        ]);

        DB::beginTransaction();
        try {
            // Nếu thêm địa chỉ mặc định, các địa chỉ khác sẽ thành "Khác"
            if (($validated['trangthai'] ?? null) === 'Mặc định') {
                DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
                    ->update(['trangthai' => 'Khác']);
            }

            $diachi = DiaChiGiaoHangModel::create(array_merge($validated, [
                'id_nguoidung' => $user->id,
                'trangthai' => $validated['trangthai'] ?? 'Khác',
            ]));

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Thêm địa chỉ thành công',
                'data' => $diachi,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi thêm địa chỉ: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/toi/diachis/{id}",
     *     summary="Cập nhật địa chỉ",
     *     description="Cho phép cập nhật thông tin địa chỉ. Nếu chọn 'Mặc định', các địa chỉ khác sẽ chuyển thành 'Khác'.",
     *     tags={"Địa chỉ (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID địa chỉ cần cập nhật",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="hoten", type="string"),
     *             @OA\Property(property="sodienthoai", type="string"),
     *             @OA\Property(property="diachi", type="string"),
     *             @OA\Property(property="trangthai", type="string", enum={"Mặc định","Khác","Tạm ẩn"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật địa chỉ thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy địa chỉ"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        $trangthaiEnum = DiaChiGiaoHangModel::getEnumValues('trangthai');
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'hoten' => 'sometimes|string|max:255',
            'sodienthoai' => 'sometimes|string|size:10',
            'diachi' => 'sometimes|string',
            'tinhthanh' => ['required', 'string', Rule::in($provinceNames)],
            'trangthai' => ['required', Rule::in($trangthaiEnum)],
        ]);

        DB::beginTransaction();
        try {
            // Nếu cập nhật thành mặc định
            if (($validated['trangthai'] ?? null) === 'Mặc định') {
                DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
                    ->update(['trangthai' => 'Khác']);
            }

            $diachi->update($validated);

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Cập nhật địa chỉ thành công',
                'data' => $diachi,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi cập nhật địa chỉ: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/toi/diachis/{id}",
     *     summary="Xóa địa chỉ (soft delete)",
     *     description="Xóa địa chỉ của người dùng theo ID (soft delete).",
     *     tags={"Địa chỉ (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID địa chỉ cần xóa",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa địa chỉ thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy địa chỉ"
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        $diachi->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa địa chỉ thành công',
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Patch(
     *     path="/api/toi/diachis/{id}/macdinh",
     *     summary="Đặt địa chỉ mặc định",
     *     description="Đặt địa chỉ thành Mặc định và các địa chỉ khác chuyển thành Khác.",
     *     tags={"Địa chỉ (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID địa chỉ cần đặt mặc định",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đặt mặc định thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy địa chỉ"
     *     )
     * )
     */
    public function setDefault(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            // Các địa chỉ khác thành "Khác"
            DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
                ->update(['trangthai' => 'Khác']);

            $diachi->update(['trangthai' => 'Mặc định']);

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Đặt địa chỉ mặc định thành công',
                'data' => $diachi,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi đặt mặc định: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/toi/diachis/{id}/trangthai",
     *     summary="Thay đổi trạng thái địa chỉ",
     *     description="Chuyển trạng thái địa chỉ giữa Khác và Tạm ẩn. Địa chỉ mặc định không được tạm ẩn.",
     *     tags={"Địa chỉ (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID địa chỉ cần thay đổi trạng thái",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật trạng thái thành công"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Không thể tạm ẩn địa chỉ mặc định"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy địa chỉ"
     *     )
     * )
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        // Nếu địa chỉ đang mặc định thì không cho tạm ẩn
        if ($diachi->trangthai === 'Mặc định') {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Địa chỉ mặc định không thể tạm ẩn!',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Toggle giữa Tạm ẩn và Khác
        $diachi->update([
            'trangthai' => $diachi->trangthai === 'Tạm ẩn' ? 'Khác' : 'Tạm ẩn',
        ]);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật trạng thái địa chỉ thành công',
            'data' => $diachi,
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Models\ChitietdonhangModel;
use App\Models\GiohangModel;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Donhang",
 *     title="Đơn hàng",
 *     description="Thông tin đơn hàng của người dùng",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_nguoidung", type="integer", example=5, description="ID người dùng"),
 *     @OA\Property(property="id_phuongthuc", type="integer", example=2, description="ID phương thức thanh toán"),
 *     @OA\Property(property="id_magiamgia", type="integer", nullable=true, example=null, description="ID mã giảm giá (nếu có)"),
 *     @OA\Property(property="madon", type="string", example="DH20251015A"),
 *     @OA\Property(property="tongsoluong", type="integer", example=3),
 *     @OA\Property(property="thanhtien", type="integer", example=450000),
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Chờ xử lý","Đã chấp nhận","Đang giao hàng","Đã giao hàng","Đã hủy đơn"},
 *         example="Chờ xử lý"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-15T09:30:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-15T09:35:00Z"),
 *     @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
 * )
 */
class DonHangFrontendAPI extends BaseFrontendController
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/api/toi/donhangs",
     *     summary="Lấy danh sách đơn hàng của người dùng",
     *     description="Trả về danh sách tất cả các đơn hàng của người dùng hiện tại (theo token).",
     *     tags={"Đơn hàng (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách đơn hàng được trả về thành công"
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

        $donhang = DonhangModel::with([
            'chitietdonhang.bienthe.sanpham',
            'phuongthuc',
            'magiamgia'
        ])
            ->where('id_nguoidung', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách đơn hàng của bạn',
            'data' => $donhang,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/toi/donhangs",
     *     summary="Tạo đơn hàng mới",
     *     description="Người dùng tạo đơn hàng mới từ giỏ hàng của họ.",
     *     tags={"Đơn hàng (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_phuongthuc"},
     *             @OA\Property(property="id_phuongthuc", type="integer", example=1, description="ID phương thức thanh toán"),
     *             @OA\Property(property="id_magiamgia", type="integer", nullable=true, example=null, description="ID mã giảm giá (nếu có)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo đơn hàng thành công"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Giỏ hàng trống hoặc dữ liệu không hợp lệ"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token không hợp lệ hoặc chưa đăng nhập"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_phuongthuc' => 'required|exists:phuongthuc,id',
            'id_magiamgia' => 'nullable|exists:magiamgia,id',
        ]);

        $user = $request->get('auth_user');
        $giohang = GiohangModel::with('bienthe')
            ->where('id_nguoidung', $user->id)
            ->where('trangthai', 'Hiển thị')
            ->get();

        if ($giohang->isEmpty()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Giỏ hàng trống, không thể tạo đơn hàng!',
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            $donhang = DonhangModel::create([
                'id_nguoidung' => $user->id,
                'id_phuongthuc' => $validated['id_phuongthuc'],
                'id_magiamgia' => $validated['id_magiamgia'] ?? null,
                'madon' => strtoupper(Str::random(10)),
                'tongsoluong' => $giohang->sum('soluong'),
                'thanhtien' => $giohang->sum('thanhtien'),
                'trangthai' => 'Chờ xử lý',
            ]);

            foreach ($giohang as $item) {
                ChitietdonhangModel::create([
                    'id_donhang' => $donhang->id,
                    'id_bienthe' => $item->id_bienthe,
                    'soluong' => $item->soluong,
                    'dongia' => $item->thanhtien,
                ]);
            }

            GiohangModel::where('id_nguoidung', $user->id)->delete();
            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Tạo đơn hàng thành công!',
                'data' => $donhang->load('chitietdonhang.bienthe.sanpham'),
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/toi/donhangs/{id}",
     *     summary="Cập nhật thông tin đơn hàng",
     *     description="Cho phép người dùng cập nhật phương thức thanh toán hoặc mã giảm giá, chỉ khi đơn hàng ở trạng thái 'Chờ xử lý'.",
     *     tags={"Đơn hàng (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID đơn hàng cần cập nhật",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_phuongthuc", type="integer", example=2),
     *             @OA\Property(property="id_magiamgia", type="integer", nullable=true, example=null),
     *             @OA\Property(property="trangthai", type="string", enum={"Chờ xử lý","Đã chấp nhận","Đang giao hàng","Đã giao hàng","Đã hủy đơn"}, example="Chờ xử lý")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật đơn hàng thành công"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không thể cập nhật đơn hàng đã được xử lý"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng hoặc không có quyền"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $validated = $request->validate([
            'id_phuongthuc' => 'sometimes|exists:phuongthuc,id',
            'id_magiamgia' => 'nullable|exists:magiamgia,id',
            'trangthai' => 'sometimes|string|in:Chờ xử lý,Đã chấp nhận,Đang giao hàng,Đã giao hàng,Đã hủy đơn',
        ]);

        $donhang = DonhangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$donhang) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy đơn hàng hoặc bạn không có quyền!',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($donhang->trangthai !== 'Chờ xử lý') {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không thể cập nhật đơn hàng đã xử lý!',
            ], Response::HTTP_FORBIDDEN);
        }

        $donhang->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật đơn hàng thành công!',
            'data' => $donhang,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Patch(
     *     path="/api/toi/donhangs/{id}/huy",
     *     summary="Hủy đơn hàng của người dùng",
     *     description="Hủy đơn hàng khi đơn vẫn còn trong trạng thái 'Chờ xử lý'.",
     *     tags={"Đơn hàng (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID đơn hàng cần hủy",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đơn hàng đã được hủy thành công"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Đơn hàng đã được xử lý, không thể hủy"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng hoặc không có quyền"
     *     )
     * )
     */
    public function cancel(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $donhang = DonhangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$donhang) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy đơn hàng hoặc bạn không có quyền!',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($donhang->trangthai !== 'Chờ xử lý') {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Đơn hàng đã được xử lý, không thể hủy!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $donhang->update(['trangthai' => 'Đã hủy đơn']);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đơn hàng đã được hủy thành công!',
            'data' => $donhang,
        ], Response::HTTP_OK);
    }
}

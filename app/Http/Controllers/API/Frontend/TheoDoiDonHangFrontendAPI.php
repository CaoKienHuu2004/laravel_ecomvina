<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Traits\ApiResponse;
use App\Http\Resources\Toi\TheoDoiDonHang\TheoDoiDonHangResource;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Tag(
 *     name="Theo dõi đơn hàng (tôi)",
 *     description="API cho phép người dùng xem và cập nhật trạng thái đơn hàng của họ"
 * )
 */
class TheoDoiDonHangFrontendAPI extends Controller
{
    use ApiResponse;


    /**
     * @OA\Get(
     *     path="/api/toi/theodoi-donhang",
     *     summary="Lấy danh sách đơn hàng của người dùng (theo trạng thái)",
     *     description="API này trả về danh sách các đơn hàng của người dùng hiện tại, được phân loại theo trạng thái (VD: Chờ thanh toán, Đang xác nhận,...).",
     *     tags={"Theo dõi đơn hàng (tôi)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="trangthai",
     *         in="query",
     *         required=false,
     *         description="Lọc đơn hàng theo trạng thái",
     *         @OA\Schema(
     *             type="string",
     *             enum={"Chờ xử lý","Đã xác nhận","Đang chuẩn bị hàng","Đang giao hàng","Đã giao hàng","Đã hủy"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="madon",
     *         in="query",
     *         required=false,
     *         description="Tìm kiếm đơn hàng theo mã đơn (VD: DH000123)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách đơn hàng được nhóm theo trạng thái",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh Sách Đơn Hàng Theo Trạng Thái Đơn Hàng Của Khách Hàng #5: Nguyễn Văn A"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="label", type="string", example="Đang xác nhận"),
     *                     @OA\Property(property="trangthai", type="string", example="Đã xác nhận"),
     *                     @OA\Property(property="soluong", type="integer", example=3),
     *                     @OA\Property(
     *                         property="donhang",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/TheoDoiDonHangResource")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không xác thực được người dùng"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->get('auth_user');

        if (!$user) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không xác thực được user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Danh sách trạng thái thực tế trong DB
        $validTrangThai = [
            'Chờ xử lý',
            'Đã xác nhận',
            'Đang chuẩn bị hàng',
            'Đang giao hàng',
            'Đã giao hàng',
            'Đã hủy',
        ];

        // Label hiển thị tương ứng
        $labels = [
            'Chờ xử lý' => 'Chờ thanh toán',
            'Đã xác nhận' => 'Đang xác nhận',
            'Đang chuẩn bị hàng' => 'Đang đóng gói',
            'Đang giao hàng' => 'Đang giao hàng',
            'Đã giao hàng' => 'Đã giao',
            'Đã hủy' => 'Đã hủy',
        ];

        $query = DonhangModel::with([
            'chitietdonhang.bienthe.sanpham',
            'chitietdonhang.bienthe.loaibienthe',
            'chitietdonhang.bienthe.sanpham.hinhanhsanpham'
        ])->where('id_nguoidung', $user->id);

        // Lọc theo trạng thái (nếu có)
        if ($request->filled('trangthai') && in_array($request->trangthai, $validTrangThai)) {
            $query->where('trangthai', $request->trangthai);
        }

        // Lọc theo mã đơn hàng (nếu có)
        if ($request->filled('madon')) {
            $query->where('madon', $request->madon);
        }

        $donhangs = $query->latest()->get();

        // Gom nhóm theo trạng thái và đếm số lượng
        $grouped = [];
        foreach ($validTrangThai as $status) {
            $donTheoTrangThai = $donhangs->where('trangthai', $status);
            $grouped[] = [
                'label' => $labels[$status] ?? $status,
                'trangthai' => $status,
                'soluong' => $donTheoTrangThai->count(),
                'donhang' => TheoDoiDonHangResource::collection($donTheoTrangThai),
            ];
        }

        // ✅ Trả về theo định dạng chuẩn { status, message, data }
        return $this->jsonResponse([
            'status' => true,
            'message' => "Danh Sách Đơn Hàng Theo Trạng Thái Đơn Hàng Của Khách Hàng #{$user->id}: {$user->hoten}",
            'data' => $grouped
        ], Response::HTTP_OK);
    }


    /**
     * @OA\Put(
     *     path="/api/toi/theodoi-donhang/{id}",
     *     summary="Cập nhật trạng thái đơn hàng",
     *     description="API này cho phép người dùng cập nhật trạng thái đơn hàng (VD: Hủy, Xác nhận, Giao hàng, ...).",
     *     tags={"Theo dõi đơn hàng (tôi)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của đơn hàng cần cập nhật",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trangthai"},
     *             @OA\Property(
     *                 property="trangthai",
     *                 type="string",
     *                 enum={"Chờ xử lý","Đã xác nhận","Đang chuẩn bị hàng","Đang giao hàng","Đã giao hàng","Đã hủy"},
     *                 example="Đã hủy"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật trạng thái đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cập Nhật Trang Thái Đơn Hàng Thành Công"),
     *             @OA\Property(property="data", ref="#/components/schemas/TheoDoiDonHangResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không xác thực được người dùng"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng hoặc bạn không có quyền"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = $request->get('auth_user');
        if (!$user) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không xác thực được user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $donhang = DonhangModel::with('chitietdonhang')->find($id);

        if (!$donhang || $donhang->id_nguoidung !== $user->id) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy đơn hàng hoặc bạn không có quyền.',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'trangthai' => 'required|string|in:Chờ xử lý,Đã xác nhận,Đang chuẩn bị hàng,Đang giao hàng,Đã giao hàng,Đã hủy',
        ]);

        $chiTietTrangThai = ($validated['trangthai'] === 'Đã hủy') ? 'Đã hủy' : 'Đã đặt';

        DB::transaction(function () use ($donhang, $validated, $chiTietTrangThai) {
            $donhang->trangthai = $validated['trangthai'];
            $donhang->save();

            foreach ($donhang->chitietdonhang as $chitiet) {
                $chitiet->trangthai = $chiTietTrangThai;
                $chitiet->save();
            }
        });

        $donhang->load(['chitietdonhang.bienthe.loaibienthe', 'chitietdonhang.bienthe.sanpham','chitietdonhang.bienthe.sanpham.hinhanhsanpham']);

        // TheoDoiDonHangResource::withoutWrapping();
        // return response()->json(new TheoDoiDonHangResource($donhang), Response::HTTP_OK);
        return $this->jsonResponse([
                'status' => true,
                'message' => "Cập Nhật Trang Thái Đơn Hàng Thành Công Của Danh Sách Đơn Hàng Theo Trạng Thái Đơn Hàng Của Khách Hàng ".$user->id.":".$user->hoten,
                'data' => TheoDoiDonHangResource::collection($donhang)
        ], Response::HTTP_OK);
    }
}

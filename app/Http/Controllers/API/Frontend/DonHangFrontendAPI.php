<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Models\ChitietdonhangModel;
use App\Models\GiohangModel;
use App\Models\PhuongthucModel;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            'phuongthuc',
            'magiamgia',
            'nguoidung',
            'phivanchuyen',
            'diachigiaohang',
            'chitietdonhang.bienthe.sanpham',
            'chitietdonhang.bienthe.loaibienthe',
        ])
            ->where('id_nguoidung', $user->id)
            ->latest('id')
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
     *     summary="Tạo đơn hàng mới (tự động xử lý kho và lượt mua qua Observer)",
     *     description="API cho phép người dùng tạo đơn hàng mới từ giỏ hàng của họ.
     *     Khi đơn hàng được cập nhật sang trạng thái **'Thành công'**, hệ thống sẽ tự động:
     *     - Giảm số lượng tồn kho (`bienthe.soluong`)
     *     - Tăng số lượt mua (`bienthe.luotmua`)
     *     - Giảm số lượt tặng (`bienthe.luottang`)
     *     Cơ chế này được thực hiện **tự động qua Laravel Observer**, không cần gọi thêm API phụ.
     *  *     🧩 Quy trình xử lý khi tạo đơn hàng mới:
    *     - Bước 1: Kiểm tra và xác thực dữ liệu đầu vào.
    *     - Bước 2: Tạo bản ghi đơn hàng (bảng `donhang`).
    *     - Bước 3: Lấy danh sách sản phẩm trong giỏ hàng (`giohang`) của người dùng.
    *     - Bước 4: Tự động tạo chi tiết đơn hàng (`chitietdonhang`) cho từng sản phẩm:
    *         + Liên kết `id_donhang` và `id_bienthe`.
    *         + Lưu số lượng, đơn giá, và trạng thái ban đầu là `Đã đặt`.
    *     - Bước 5: Khi trạng thái đơn hàng chuyển sang **thành công**, hệ thống sẽ:
    *         + Giảm tồn kho (`bienthe.soluong -= 1`).
    *         + Tăng lượt mua (`bienthe.luotban += 1`).
    *         + (Tùy chọn) Đồng bộ sang bảng `chitiethoadon`.
    *     ",
     *     tags={"Đơn hàng (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_phuongthuc", "id_phivanchuyen", "id_diachigiaohang", "tongsoluong", "tamtinh", "thanhtien"},
     *             @OA\Property(property="id_phuongthuc", type="integer", example=1, description="ID phương thức thanh toán"),
     *             @OA\Property(property="id_phivanchuyen", type="integer", example=2, description="ID phí vận chuyển"),
     *             @OA\Property(property="id_diachigiaohang", type="integer", example=3, description="ID địa chỉ giao hàng"),
     *             @OA\Property(property="id_magiamgia", type="integer", nullable=true, example=null, description="ID mã giảm giá (nếu có)"),
     *             @OA\Property(property="tongsoluong", type="integer", example=3, description="Tổng số lượng sản phẩm trong đơn"),
     *             @OA\Property(property="tamtinh", type="integer", example=250000, description="Tổng tạm tính của đơn hàng (chưa trừ mã giảm giá)"),
     *             @OA\Property(property="thanhtien", type="integer", example=230000, description="Tổng tiền sau khi áp dụng giảm giá và phí vận chuyển")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tạo đơn hàng thành công!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Thông tin đơn hàng và chi tiết đơn hàng đi kèm"
     *             )
     *         )
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
        // 🧩 Bước 1: Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'id_phuongthuc'      => 'required|integer|exists:phuongthuc,id',
            'id_nguoidung'       => 'required|integer|exists:nguoidung,id',
            'id_phivanchuyen'    => 'required|integer|exists:phivanchuyen,id',
            'id_diachigiaohang'  => 'required|integer|exists:diachi_giaohang,id',
            'id_magiamgia'       => 'nullable|integer|exists:magiamgia,id',
            'tongsoluong'        => 'required|integer|min:1',
            'tamtinh'            => 'required|integer|min:0',
            'thanhtien'          => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();

        // 🧩 Bước 2: Lấy giỏ hàng người dùng
        $user = $request->get('auth_user');
        $giohang = GiohangModel::with('bienthe')
            ->where('id_nguoidung', $user->id)
            ->where('trangthai', 'Hiển thị')
            ->get();

        if ($giohang->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Giỏ hàng trống, không thể tạo đơn hàng!',
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();

        try {
            $idPhuongthuc = $validated['id_phuongthuc'];

            // Lấy trạng thái đơn hàng theo id_phuongthuc
            $phuongthuc = PhuongthucModel::find($idPhuongthuc);

            $trangthaiDonhang = 'Chờ xử lý'; // default
            $trangthaiThanhtoan = 'Chưa thanh toán';

            if ($phuongthuc) {
                if ($idPhuongthuc != 3) {
                    $mapTrangthai = [
                        'Hoạt động' => 'Chờ xử lý',
                        'Tạm khóa' => 'Đã hủy', // 2 cái này ko cần lắm liên quan đến trangthai bẳng phương thức
                        'Dừng hoạt động' => 'Đã hủy', // 2 cái này ko cần lắm liên quan đến trangthai bẳng phương thức
                    ];
                    $trangthaiDonhang = $mapTrangthai[$phuongthuc->trangthai] ?? 'Chờ xử lý';
                    $trangthaiThanhtoan = 'Đã thanh toán';
                }
            }

            // 🧩 Bước 3: Tạo đơn hàng
            $donhang = DonhangModel::create([
                'id_phuongthuc'       => $idPhuongthuc,
                'id_nguoidung'        => $user->id,
                'id_phivanchuyen'     => $validated['id_phivanchuyen'],
                'id_diachigiaohang'   => $validated['id_diachigiaohang'],
                'id_magiamgia'        => $validated['id_magiamgia'] ?? null,
                'madon'               => DonhangModel::generateOrderCode(),
                'tongsoluong'         => $giohang->sum('soluong'),
                'tamtinh'             => $validated['tamtinh'],
                'thanhtien'           => $validated['thanhtien'],
                'trangthaithanhtoan'  => $trangthaiThanhtoan,
                'trangthai'           => $trangthaiDonhang,
            ]);

            // 🧩 Bước 4: Tạo chi tiết đơn hàng
            foreach ($giohang as $item) {
                ChitietdonhangModel::create([
                    'id_bienthe' => $item->id_bienthe,
                    'id_donhang' => $donhang->id,
                    'soluong'    => $item->soluong,
                    'dongia'     => $item->bienthe->giagoc ?? 0,
                    'trangthai'  => 'Đã đặt',
                ]);
            }

            // 🧩 Bước 5: Xóa giỏ hàng sau khi đặt
            GiohangModel::where('id_nguoidung', $user->id)->delete();

            DB::commit();

            // 🧩 Bước 6: Trả về JSON đơn hàng vừa tạo
            return response()->json([
                'status'  => true,
                'message' => 'Tạo đơn hàng thành công!',
                'data'    => $donhang->makeVisible(['created_at'])->load('chitietdonhang.bienthe.sanpham'),
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/toi/donhangs/{id}",
     *     summary="Cập nhật thông tin và trạng thái đơn hàng (đồng bộ chi tiết)",
     *     description="
     *     ✅ Cho phép người dùng:
     *     - Cập nhật `id_phuongthuc`, `id_magiamgia` khi đơn còn ở trạng thái **'Chờ xử lý'**.
     *     - Cập nhật `trangthai` (Đã chấp nhận, Đang giao hàng, Đã giao hàng, Đã hủy đơn).
     *
     *     🔁 Khi thay đổi `trangthai`:
     *     - Hệ thống tự **đồng bộ tất cả chi tiết đơn hàng** (`chitiet_donhang.trangthai` = trạng thái mới).
     *     - Nếu trạng thái là **'Đã giao hàng'** → `DonhangObserver` sẽ tự động trừ kho (`bienthe.soluong -= chitietdonhang.soluong`) và tăng `luotmua`.
     *     - Nếu trạng thái là **'Đã hủy đơn'** → `DonhangObserver` sẽ tự động hoàn lại tồn kho (`bienthe.soluong += chitietdonhang.soluong`).
     *     ",
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
     *             @OA\Property(property="trangthai", type="string", enum={"Chờ xử lý","Đã chấp nhận","Đang giao hàng","Đã giao hàng","Đã hủy đơn"}, example="Đã giao hàng")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật đơn hàng và chi tiết thành công"),
     *     @OA\Response(response=400, description="Trạng thái không hợp lệ hoặc không thể cập nhật"),
     *     @OA\Response(response=404, description="Không tìm thấy đơn hàng hoặc không có quyền"),
     *     @OA\Response(response=500, description="Lỗi hệ thống khi xử lý đơn hàng")
     * )
     */
    public function update(Request $request, $id)
    {
        $enumTrangthai = DonhangModel::getEnumValues('trangthai');
        $user = $request->get('auth_user');

        // Giả sử bạn có cách kiểm tra admin, ví dụ:
        $isAdmin = $user->role === 'admin'; // hoặc tùy cách bạn định nghĩa quyền

        // Validate input, các trường có thể không bắt buộc nếu người dùng không update
        $validated = $request->validate([
            'id_phuongthuc' => ['sometimes', 'exists:phuongthuc,id'],
            'id_magiamgia'  => ['nullable', 'exists:magiamgia,id'],
            'trangthai'     => ['sometimes', Rule::in($enumTrangthai)],
        ]);

        $donhang = DonhangModel::with('chitietdonhang.bienthe')
            ->where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$donhang) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy đơn hàng hoặc bạn không có quyền!',
            ], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            // Chỉ cho phép cập nhật id_phuongthuc hoặc id_magiamgia khi đơn hàng đang "Chờ xử lý"
            if ((isset($validated['id_phuongthuc']) || array_key_exists('id_magiamgia', $validated))
                && $donhang->trangthai !== 'Chờ xử lý') {
                DB::rollBack();
                return $this->jsonResponse([
                    'status'  => false,
                    'message' => 'Chỉ có thể thay đổi thông tin thanh toán khi đơn hàng đang ở trạng thái "Chờ xử lý".',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Kiểm tra trạng thái mới (nếu có) có hợp lệ (không được lùi trạng thái trừ admin)
            if (isset($validated['trangthai'])) {
                $currentStatus = $donhang->trangthai;
                $newStatus = $validated['trangthai'];

                // Danh sách thứ tự trạng thái (giả định theo quy trình)
                $statusOrder = [
                    'Chờ xử lý'    => 1,
                    'Đã chấp nhận' => 2,
                    'Đang giao hàng'=> 3,
                    'Đã giao hàng' => 4,
                    'Đã hủy đơn'   => 5,
                ];

                if (!$isAdmin && $statusOrder[$newStatus] < $statusOrder[$currentStatus]) {
                    DB::rollBack();
                    return $this->jsonResponse([
                        'status'  => false,
                        'message' => 'Không được phép thay đổi trạng thái lùi lại trừ khi có quyền quản trị.',
                    ], Response::HTTP_FORBIDDEN);
                }
            }

            // Cập nhật thông tin đơn hàng
            $donhang->update($validated);

            // Đồng bộ trạng thái thanh toán theo id_phuongthuc (nếu có thay đổi)
            if (isset($validated['id_phuongthuc'])) {
                if (in_array($validated['id_phuongthuc'], [1, 2])) {
                    $donhang->trangthaithanhtoan = 'Đã thanh toán';
                } elseif ($validated['id_phuongthuc'] == 3) {
                    $donhang->trangthaithanhtoan = 'Chưa thanh toán';
                }
                $donhang->save();
            }

            // Đồng bộ trạng thái chi tiết nếu cập nhật trạng thái đơn hàng
            if (isset($validated['trangthai'])) {
                foreach ($donhang->chitietdonhang as $ct) {
                    $ct->update(['trangthai' => $validated['trangthai']]);
                }
            }

            DB::commit();

            return $this->jsonResponse([
                'status'  => true,
                'message' => 'Cập nhật đơn hàng và chi tiết thành công!',
                'data'    => $donhang->fresh('chitietdonhang.bienthe'),
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Lỗi khi cập nhật đơn hàng!',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/toi/donhangs/{id}/huy",
     *     summary="Hủy đơn hàng của người dùng (đồng bộ kho tự động)",
     *     description="
     *     ❌ Hủy đơn hàng khi đơn vẫn còn trong trạng thái 'Chờ xử lý'.
     *     🔁 Khi đơn bị hủy, **Observer DonhangObserver** sẽ tự hoàn lại số lượng sản phẩm trong kho (`bienthe.soluong += chitietdonhang.soluong`).
     *     ",
     *     tags={"Đơn hàng (tôi)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID đơn hàng cần hủy",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(response=200, description="Đơn hàng đã được hủy thành công"),
     *     @OA\Response(response=400, description="Đơn hàng đã được xử lý, không thể hủy"),
     *     @OA\Response(response=404, description="Không tìm thấy đơn hàng hoặc không có quyền")
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

            // #Begin------------------- Tích hợp thanh toán VNPAY, cần thêm 3 route ----------------------//


    /**
     * @OA\Post(
     *     path="/api/toi/donhangs/{id}/create-payment-url",
     *     summary="Tạo URL thanh toán VNPAY cho đơn hàng",
     *     description="
     *         Tạo URL thanh toán VNPAY dựa trên thông tin đơn hàng và trả về URL này cho frontend để người dùng tiến hành thanh toán.
     *         - Chỉ tạo cho đơn hàng có trạng thái thanh toán là 'Chưa thanh toán'.
     *         - Trả về URL đầy đủ có chữ ký bảo mật của VNPAY.
     *         - Frontend sẽ chuyển hướng người dùng sang URL này để thực hiện thanh toán.
     *     ",
     *     tags={"Thanh toán VNPAY"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID đơn hàng cần tạo URL thanh toán",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trả về URL thanh toán VNPAY thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="payment_url", type="string", example="https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?...&vnp_SecureHash=...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Đơn hàng không hợp lệ hoặc đã được thanh toán",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Đơn hàng không hợp lệ hoặc đã thanh toán.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token không hợp lệ hoặc chưa đăng nhập"
     *     )
     * )
     */
    public function createPaymentUrl(Request $request, $id)
    {
        $user = $request->get('auth_user');
        $donhang = DonhangModel::where('id', $id)->where('id_nguoidung', $user->id)->first();

        if (!$donhang || $donhang->trangthaithanhtoan !== 'Chưa thanh toán') {
            return response()->json(['status' => false, 'message' => 'Đơn hàng không hợp lệ hoặc đã thanh toán.'], 400);
        }

        $vnp_Url = config('vnpay.payment_url');
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Returnurl = route('api.toi.donhangs.payment-callback');

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => $donhang->thanhtien * 100,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $request->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => "Thanh toán đơn hàng #{$donhang->madon}",
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_TxnRef' => $donhang->madon,
        ];

        ksort($inputData);
        $query = http_build_query($inputData, '', '&');
        $vnp_SecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
        $paymentUrl = $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        return response()->json(['status' => true, 'payment_url' => $paymentUrl]);
        // return redirect($paymentUrl); có thể dùng redirect nếu muốn chuyển hướng ngay
    }


    /**
     * @OA\Get(
     *     path="/api/toi/donhangs/payment-callback",
     *     summary="Xử lý callback từ VNPAY sau khi thanh toán",
     *     description="
     *         Nhận thông tin callback từ VNPAY về kết quả thanh toán.
     *         - Xác thực chữ ký bảo mật (secure hash) để đảm bảo dữ liệu hợp lệ.
     *         - Kiểm tra mã đơn hàng và trạng thái thanh toán (vnp_ResponseCode).
     *         - Nếu thành công (ResponseCode = '00'), cập nhật đơn hàng thành 'Đã thanh toán' và trạng thái đơn hàng là 'Chờ xử lý'.
     *         - Nếu thất bại, cập nhật trạng thái thanh toán là 'Thanh toán thất bại' và trạng thái đơn hàng là 'Đã hủy'.
     *         - Trả về chuỗi 'OK' khi thành công để VNPAY ghi nhận callback.
     *         - Trả về lỗi 400 nếu chữ ký không hợp lệ hoặc dữ liệu không đúng.
     *     ",
     *     tags={"Thanh toán VNPAY"},
     *     @OA\Parameter(
     *         name="vnp_Amount",
     *         in="query",
     *         description="Số tiền thanh toán (đơn vị 100 VND)",
     *         required=true,
     *         @OA\Schema(type="integer", example=7500000)
     *     ),
     *     @OA\Parameter(
     *         name="vnp_ResponseCode",
     *         in="query",
     *         description="Mã kết quả thanh toán (00: thành công, khác: thất bại)",
     *         required=true,
     *         @OA\Schema(type="string", example="00")
     *     ),
     *     @OA\Parameter(
     *         name="vnp_TxnRef",
     *         in="query",
     *         description="Mã đơn hàng",
     *         required=true,
     *         @OA\Schema(type="string", example="VNA1122001")
     *     ),
     *     @OA\Parameter(
     *         name="vnp_SecureHash",
     *         in="query",
     *         description="Chữ ký bảo mật của VNPAY để xác thực dữ liệu",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xử lý callback thành công",
     *         @OA\MediaType(mediaType="text/plain")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Chữ ký không hợp lệ hoặc dữ liệu thiếu",
     *         @OA\MediaType(mediaType="text/plain")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng tương ứng"
     *     )
     * )
     */
    public function handlePaymentCallback(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $inputData = $request->all();

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $query = http_build_query($inputData, '', '&');
        $computedHash = hash_hmac('sha512', $query, $vnp_HashSecret);

        if ($computedHash !== $vnp_SecureHash) {
            return response('Chữ ký không hợp lệ', 400);
        }

        $orderCode = $inputData['vnp_TxnRef'] ?? null;
        $responseCode = $inputData['vnp_ResponseCode'] ?? null;

        if (!$orderCode) {
            return response('Không tìm thấy đơn hàng', 400);
        }

        $donhang = DonhangModel::where('madon', $orderCode)->first();

        if (!$donhang) {
            return response('Đơn hàng không tồn tại', 404);
        }

        if ($responseCode === '00') {
            $donhang->trangthaithanhtoan = 'Đã thanh toán';
            $donhang->trangthai = 'Chờ xử lý';
            $donhang->save();
            return response('OK', 200);
            // // return response()->json([ // ko dùng 3xx được, vì nhiều trình duyệt ko hiểu json trong 3xx, ko tự động chuyểnt hướng
            // //     'message' => 'Thanh toán thành công, chuyển hướng...',
            // //     'redirect_url' => config('app.client_url') . '/payment-success'
            // // ], 302)->header('http://148.230.100.215:3000', url('/payment-success'));
            // return response()->noContent(302)
            // ->header('Location', config('app.client_url') . '/payment-success');

        } else {
            $donhang->trangthaithanhtoan = 'Thanh toán thất bại';
            $donhang->trangthai = 'Đã hủy';
            $donhang->save();
            return response('Thanh toán thất bại', 200);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/toi/donhangs/{id}/payment-status",
     *     summary="Lấy trạng thái thanh toán đơn hàng",
     *     description="
     *         API cho phép frontend hoặc client kiểm tra trạng thái thanh toán và trạng thái đơn hàng.
     *         - Dùng để hiển thị thông tin cập nhật cho người dùng sau khi thanh toán.
     *         - Trả về:
     *           + payment_status: trạng thái thanh toán (ví dụ: 'Chưa thanh toán', 'Đã thanh toán', 'Thanh toán thất bại')
     *           + order_status: trạng thái đơn hàng (ví dụ: 'Chờ xử lý', 'Đã hủy', ...)
     *     ",
     *     tags={"Thanh toán VNPAY"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID đơn hàng cần kiểm tra trạng thái thanh toán",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trả về trạng thái thanh toán và trạng thái đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="payment_status", type="string", example="Đã thanh toán"),
     *             @OA\Property(property="order_status", type="string", example="Chờ xử lý")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Đơn hàng không tồn tại")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token không hợp lệ hoặc chưa đăng nhập"
     *     )
     * )
     */
    public function getPaymentStatus(Request $request, $id)
    {
        $user = $request->get('auth_user');
        $donhang = DonhangModel::where('id', $id)->where('id_nguoidung', $user->id)->first();

        if (!$donhang) {
            return response()->json(['status' => false, 'message' => 'Đơn hàng không tồn tại'], 404);
        }

        return response()->json([
            'status' => true,
            'payment_status' => $donhang->trangthaithanhtoan,
            'order_status' => $donhang->trangthai,
        ]);
    }
            // #End------------------- Tích hợp thanh toán VNPAY, cần thêm 3 route ----------------------//
}

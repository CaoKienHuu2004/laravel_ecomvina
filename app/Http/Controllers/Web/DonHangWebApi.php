<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\Frontend\BaseFrontendController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Toi\DonHangResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Models\ChitietdonhangModel;
use App\Models\GiohangModel;
use App\Models\MagiamgiaModel;
use App\Models\PhuongthucModel;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use App\Traits\SentMessToAdmin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Http\Resources\Toi\TheoDoiDonHang\TheoDoiDonHangResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\TheoDoiDonHangResource as TheoDoiDonHangDetailResource;
use App\Models\BientheModel;
use App\Traits\SentMessToClient;

use Illuminate\Support\Facades\Redis;


class DonHangWebApi extends BaseFrontendController
{
    use ApiResponse;
    use SentMessToAdmin;
    use SentMessToClient;


    protected $domain;
    protected $domainClient;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
        $this->domainClient = env('CLIENT_URL', 'http://148.230.100.215:3000');
    }


    // database : 'Chờ xử lý','Đã xác nhận','Đang chuẩn bị hàng','Đang giao hàng','Đã giao hàng','Đã hủy'
    // .. UI Shoppee : Chờ xác nhận, Chờ lấy hang,  chờ giaohang, Đã giao, trả hàng, Đã hủy
    // .. UI sieuthivina : Chờ xác nhận, Chờ lấy hang,  chờ giaohang, Đã giao, trả hàng, Đã hủy

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
        // DonHangResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        // return response()->json(DonHangResource::collection($donhang), Response::HTTP_OK);
    }

    public function show(Request $request, $id)
    {
        $user = $request->get('auth_user');

        if (!$user) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không xác thực được user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Lấy đơn hàng kèm quan hệ cần thiết
        $donhang = DonhangModel::with([
            'chitietdonhang.bienthe.sanpham',
            'chitietdonhang.bienthe.loaibienthe',
            'chitietdonhang.bienthe.sanpham.hinhanhsanpham',
            'phuongthuc',
            'phivanchuyen',
            'diachigiaohang',
            'magiamgia'
        ])->find($id);

        if (!$donhang) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy đơn hàng.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Kiểm tra quyền: đơn hàng phải thuộc về user đang đăng nhập
        if ($donhang->id_nguoidung !== $user->id) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Bạn không có quyền xem đơn hàng này.',
            ], Response::HTTP_FORBIDDEN);
        }

        // Trả về resource theo chuẩn
        return $this->jsonResponse([
            'status' => true,
            'message' => "Chi tiết đơn hàng #{$donhang->madon}",
            'data' => new TheoDoiDonHangDetailResource($donhang)
        ], Response::HTTP_OK);

    }



    public function store(Request $request)
    {
        // Bước 1: Validate dữ liệu đầu vào
        $validator = Validator::make($request->only('ma_phuongthuc', 'ma_magiamgia', 'id_diachigiaohang'), [
            'ma_phuongthuc'      => 'required|string|exists:phuongthuc,maphuongthuc',
            'ma_magiamgia'       => 'nullable|string|exists:magiamgia,magiamgia',
            'id_diachigiaohang'  => 'required|integer|exists:diachi_giaohang,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();

        // Bước 2: Lấy giỏ hàng người dùng
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
            $ma_phuongthuc = $validated['ma_phuongthuc'];

            $phuongthuc = PhuongthucModel::where('maphuongthuc', $ma_phuongthuc)->first();
            if (!$phuongthuc) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phương thức thanh toán không hợp lệ',
                ], Response::HTTP_BAD_REQUEST);
            }

            $trangthaiDonhang = 'Chờ xử lý';
            $trangthaiThanhtoan = 'Chưa thanh toán';

            if ($ma_phuongthuc != 'cod') {
                $mapTrangthai = [
                    'Hoạt động' => 'Chờ xử lý',
                    'Tạm khóa' => 'Đã hủy',
                    'Dừng hoạt động' => 'Đã hủy',
                ];
                $trangthaiDonhang = $mapTrangthai[$phuongthuc->trangthai] ?? 'Chờ xử lý';
            }

            $freeship = MagiamgiaModel::where('magiamgia', $request->input('ma_magiamgia'))
                ->where('giatri', 0)
                ->where('ngaybatdau', '<=', now())
                ->where('ngayketthuc', '>=', now())
                ->where('trangthai', 'Hoạt động')
                ->exists();

            $id_diachigiaohang = $validated['id_diachigiaohang'];

            $diachiGiaoHang = $user->diachi()->where('id', $id_diachigiaohang)->first();
            if (!$diachiGiaoHang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Địa chỉ giao hàng không thuộc tài khoản của bạn!',
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($freeship) {
                $id_phivanchuyen = 3;
            } elseif ($diachiGiaoHang->tinhthanh === "Thành phố Hồ Chí Minh") {
                $id_phivanchuyen = 1;
            } else {
                $id_phivanchuyen = 2;
            }

            $id_magiamgia = MagiamgiaModel::where('magiamgia', $request->input('ma_magiamgia'))
                ->where('ngaybatdau', '<=', now())
                ->where('ngayketthuc', '>=', now())
                ->where('trangthai', 'Hoạt động')
                ->value('id');

            $tongsoluong = $giohang->sum('soluong');

            $tamtinh = $giohang->sum('thanhtien') + ($id_phivanchuyen == 1 ? 25000 : ($id_phivanchuyen == 2 ? 35000 : 0));

            $giatriMagiamgia = $id_magiamgia ? MagiamgiaModel::where('id', $id_magiamgia)->value('giatri') : 0;

            $thanhtien = $tamtinh - $giatriMagiamgia;

            if ($thanhtien < 0) $thanhtien = 0; // tránh âm

            $donhang = DonhangModel::create([
                'id_phuongthuc'       => $phuongthuc->id,
                'id_nguoidung'        => $user->id,
                'id_phivanchuyen'     => $id_phivanchuyen,
                'id_diachigiaohang'   => $id_diachigiaohang,
                'id_magiamgia'        => $id_magiamgia ?? null,
                'madon'               => DonhangModel::generateOrderCode(),
                'tongsoluong'         => $tongsoluong,
                'tamtinh'             => $tamtinh,
                'thanhtien'           => $thanhtien,
                'trangthaithanhtoan'  => $trangthaiThanhtoan,
                'trangthai'           => $trangthaiDonhang,
            ]);

            foreach ($giohang as $item) {
                $bienthe = BientheModel::with(['loaibienthe', 'sanpham'])->find($item->id_bienthe);
                if (!$bienthe) {
                    continue; // Nếu biến thể không tồn tại thì bỏ qua
                }
                $tenloaibienthe = $bienthe->loaibienthe->ten ?? "Không có";
                $tensanpham = $bienthe->sanpham->ten ?? "Không có";
                ChitietdonhangModel::create([
                    'id_bienthe' => $item->id_bienthe,
                    'tenloaibienthe' => $tenloaibienthe,
                    'tensanpham' => $tensanpham,
                    'id_donhang' => $donhang->id,
                    'soluong'    => $item->soluong,
                    'dongia'     => $item->bienthe->giagoc ?? 0,
                ]);
            }

            GiohangModel::where('id_nguoidung', $user->id)->delete();

            $this->sentMessToAdmin(
                'Đơn hàng mới từ ' . $user->hoten . '-' . $user->sodienthoai,
                'Người dùng ' . $user->hoten . '-' . $user->sodienthoai . '-' . $user->username . '-' . $user->email . ' vừa tạo đơn hàng mới mã ' . $donhang->madon . '. Vui lòng kiểm tra và gọi điện cho khách hàng để truyển trạng thái đơn hàng từ Chờ xử lý -> Đã xác nhận và xử lý đơn hàng kịp thời.',
                $this->domain . 'donhang/show/' . $donhang->id,
                "Đơn hàng"
            );
            $this->SentMessToClient(
                'Xác nhận đơn hàng mới của bạn',
                'Chào ' . $user->hoten . ', bạn đã tạo thành công đơn hàng mã ' . $donhang->madon .
                '. Vui lòng chờ nhân viên liên hệ để xác nhận và xử lý đơn hàng. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
                $this->domainClient.'/' . 'don-hang', // http://14.321321.241342/don-hang/id
                // $this->domainClient.'/' . 'don-hang/' . $donhang->id, // http://14.321321.241342/don-hang/id
                "Đơn hàng",
                $user->id
            ); // trả về bool $check true/false

            /// Lưu IP vào bảng IP redis chỉ để check điều kiện người dùng mới cho bảng magiamgia

           $magiamgiaId = $id_magiamgia; // $magiamgiaId = $request->input('magiamgia_id'); // mã giảm giá user chọn
            $ip = $request->getClientIp();
            if ($magiamgiaId == 2) { // 2 là vì trong database mô tả của magiamgia đầy là mã kiểm tra người dùng mới, nền suy ra dùng IP để check
                $redisIpKey = "used_voucher_ip:$ip";

                // Lưu IP 1 năm
                Redis::setex($redisIpKey, 86400 * 365, true);
            }
            /// Lưu IP vào bảng IP redis chỉ để check điều kiện người dùng mới cho bảng magiamgia

            DB::commit();

            $donhang->created_at = $donhang->created_at ? $donhang->created_at->toIso8601String() : null;
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

    public function update(Request $request, $id)
    {
        $enumTrangthai = DonhangModel::getEnumValues('trangthai');
        $user = $request->get('auth_user');

        // Giả sử bạn có cách kiểm tra admin, ví dụ:
        $isAdmin = $user->role === 'admin'; // hoặc tùy cách bạn định nghĩa quyền

        // Validate input, các trường có thể không bắt buộc nếu người dùng không update
        $validated = $request->validate([
            'ma_phuongthuc'      => 'sometimes|string|exists:phuongthuc,maphuongthuc',
            'ma_magiamgia'       => 'nullable|string|exists:magiamgia,magiamgia',
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

        $donhang->update([
            'trangthai' => 'Đã hủy'
        ]);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đơn hàng đã được hủy thành công!',
            'data' => $donhang,
        ], Response::HTTP_OK);
    }

    // #Begin------------------- Tích hợp thanh toán VNPAY, cần thêm 3 route ----------------------//

    public function createPaymentUrl(Request $request, $id)
    {
        $user = $request->get('auth_user');
        $donhang = DonhangModel::where('id', $id)->where('id_nguoidung', $user->id)->first();

        $allowedBankCodes = [
            'NCB', 'AGRIBANK', 'VIETCOMBANK', 'VIETINBANK',
            'VISA', 'MASTERCARD', 'JCB'
        ];
        $bankCode = $request->input('bankcode');

        if ($bankCode && !in_array($bankCode, $allowedBankCodes)) {
            return response()->json([
                'status' => false,
                'message' => 'Mã ngân hàng không hợp lệ.',
            ], 422);
        }

        if (!$donhang || $donhang->trangthaithanhtoan !== 'Chưa thanh toán') {
            return response()->json(['status' => false, 'message' => 'Đơn hàng không hợp lệ hoặc đã thanh toán.'], 400);
        }
        // Kiểm tra chỉ tạo URL thanh toán cho phương thức thanh toán online (id_phuongthuc = 1) dbt Chuyển khoản ngân hàng trực tiếp
        if ((int)$donhang->id_phuongthuc !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Phương thức thanh toán không hỗ trợ tạo URL thanh toán online.'
            ], 400);
        }

        $vnp_Url = config('vnpay.payment_url');
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Returnurl = route('tai-khoan.donhang.payment-callback');

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
        if ($bankCode) {
            $inputData['vnp_BankCode'] = $bankCode;
        }

        ksort($inputData);
        $query = http_build_query($inputData, '', '&');
        $vnp_SecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
        $paymentUrl = $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        return response()->json(['status' => true, 'payment_url' => $paymentUrl]);
        // return redirect($paymentUrl); có thể dùng redirect nếu muốn chuyển hướng ngay
    }

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
        } else {
            $donhang->trangthaithanhtoan = 'Thanh toán thất bại';
            $donhang->trangthai = 'Đã hủy';
            $donhang->save();
            return response('Thanh toán thất bại', 200);
        }
    }

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

    // #begin------------------- Tích hợp thanh toán VietQR ----------------------//

    public function createVietqrtUrl(Request $request, $id)
    {
        $user = $request->get('auth_user');

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Chưa xác thực user',
            ], 401);
        }

        // Tìm đơn hàng theo ID và user hiện tại
        $donhang = DonhangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$donhang) {
            return response()->json([
                'status' => false,
                'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn',
            ], 404);
        }

        // Kiểm tra id_phuongthuc == 2 mới được tạo QR
        if ($donhang->id_phuongthuc != 2) {
            return response()->json([
                'status' => false,
                'message' => 'Phương thức thanh toán không hỗ trợ tạo mã QR',
            ], 403);
        }

        $payload = config('vietqr'); // tài khoản đã đăng ký vietqr, gắn với chủ website hoặc người có trách nhiệm nhận tiền

        // Tạo URL VietQR động theo đơn hàng
        $qr = "https://img.vietqr.io/image/{$payload['acqId']}-{$payload['accountNo']}-{$payload['template']}.png"
            . "?amount={$donhang->thanhtien}"
            . "&addInfo=" . urlencode('THANH TOAN DON HANG ' . $donhang->madon)
            . "&accountName=" . urlencode($payload['accountName']);

        $this->sentMessToAdmin(
            'Thanh toán mới từ ' . $user->hoten . '-' . $user->sodienthoai,
            'Người dùng ' . $user->hoten . '-' . $user->sodienthoai . '-' . $user->username . '-' . $user->email
            . ' vừa tạo thanh toán mã cp, đơn hàng mã ' . $donhang->madon . ' với phương thức thanh toán kiểm tra thành toán thủ công. '
            . 'Vui lòng kiểm tra tài khoản VietQR xem đã nhận tiền chưa. '
            . 'Nếu đã nhận tiền, vui lòng cập nhật trạng thái đơn hàng thủ công từ "Chờ xử lý" sang "Đã xác nhận" để xử lý kịp thời.',
            $this->domain . 'donhang/show/' . $donhang->id,
            "Đơn hàng"
        );

        return response()->json([
            'status'  => true,
            'message' => 'Tạo url VietQR thành công',
            'data'    => $qr,
        ]);
    }
    // #end------------------- Tích hợp thanh toán VietQR ----------------------//

}



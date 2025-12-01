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


class DonHangWebApi extends BaseFrontendController
{
    use ApiResponse;
    use SentMessToAdmin;


    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }


    // database : 'Chờ xử lý','Đã xác nhận','Đang chuẩn bị hàng','Đang giao hàng','Đã giao hàng','Đã hủy'
    // .. UI Shoppee : Chờ xác nhận, Chờ lấy hang,  chờ giaohang, Đã giao, trả hàng, Đã hủy
    // .. UI sieuthivina : Chờ xác nhận, Chờ lấy hang,  chờ giaohang, Đã giao, trả hàng, Đã hủy
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

        // return $this->jsonResponse([
        //     'status' => true,
        //     'message' => 'Danh sách đơn hàng của bạn',
        //     'data' => $donhang,
        // ], Response::HTTP_OK);
        DonHangResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(DonHangResource::collection($donhang), Response::HTTP_OK);
    }



    public function store(Request $request)
    {
        // 🧩 Bước 1: Validate dữ liệu đầu vào
        try {
            // $validator = Validator::make($request->all(), [
            //     'id_phuongthuc'      => 'required|integer|exists:phuongthuc,id',
            //     'id_nguoidung'       => 'required|integer|exists:nguoidung,id',
            //     'id_phivanchuyen'    => 'required|integer|exists:phivanchuyen,id',
            //     'id_diachigiaohang'  => 'required|integer|exists:diachi_giaohang,id',
            //     'id_magiamgia'       => 'nullable|integer|exists:magiamgia,id',
            //     'tongsoluong'        => 'required|integer|min:1',
            //     'tamtinh'            => 'required|integer|min:4000',
            //     'thanhtien'          => 'required|integer|min:4000|lte:tamtinh',
            // ]);
            $validator = Validator::make($request->all(), [
                'ma_phuongthuc'      => 'required|string|exists:phuongthuc,maphuongthuc',
                'ma_magiamgia'       => 'nullable|string|exists:magiamgia,magiamgia',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return $this->jsonResponse([
                'error' => true,
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        }

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
            $ma_phuongthuc = $validated['ma_phuongthuc'];

            // Lấy trạng thái đơn hàng theo id_phuongthuc
            $phuongthuc = PhuongthucModel::where('maphuongthuc', $ma_phuongthuc)->first();

            $trangthaiDonhang = 'Chờ xử lý'; // default
            $trangthaiThanhtoan = 'Chưa thanh toán';

            if ($phuongthuc) {
                if ($ma_phuongthuc != 'cod') {
                    $mapTrangthai = [
                        'Hoạt động' => 'Chờ xử lý',
                        'Tạm khóa' => 'Đã hủy', // 2 cái này ko cần lắm liên quan đến trangthai bẳng phương thức
                        'Dừng hoạt động' => 'Đã hủy', // 2 cái này ko cần lắm liên quan đến trangthai bẳng phương thức
                    ];
                    $trangthaiDonhang = $mapTrangthai[$phuongthuc->trangthai] ?? 'Chờ xử lý';
                    // $trangthaiThanhtoan = 'Đã thanh toán';
                }
            }
            $freeship = MagiamgiaModel::where('magiamgia', $request->input('ma_magiamgia'))
                ->where('giatri', 0)
                ->where('ngaybatdau', '<=', now())
                ->where('ngayketthuc', '>=', now())
                ->where('trangthai', 'Hoạt động')
                ->exists();
            $diachiMacDinh = $user->diachi()
                ->where('trangthai', 'Mặc định')
                ->first();
            if(!$diachiMacDinh)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng thiết lập địa chỉ giao hàng mặc định trước khi đặt hàng!',
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($freeship) {
                $id_phivanchuyen = 3;
            } elseif ($diachiMacDinh && $diachiMacDinh->tinhthanh === "Thành phố Hồ Chí Minh") {
                $id_phivanchuyen = 1; // ngoại thành TP.hcm = 25000
            } else {
                $id_phivanchuyen = 2; // ngoại thành TP.hcm = 35000
            }
            $id_diachigiaohang = $diachiMacDinh->id;
            $id_magiamgia = MagiamgiaModel::where('magiamgia', $request->input('ma_magiamgia'))
            ->where('ngaybatdau', '<=', now())
            ->where('ngayketthuc', '>=', now())
            ->where('trangthai', 'Hoạt động')
            ->value('id');
            $tongsoluong = $giohang->sum('soluong');

            $tamtinh = $giohang->sum('thanhtien') + ($id_phivanchuyen == 1 ? 25000 : ($id_phivanchuyen == 2 ? 35000 : 0));
            $thanhtien = $tamtinh - ($id_magiamgia ? MagiamgiaModel::where('id', $id_magiamgia)->value('giatri') : 0);
            // 🧩 Bước 3: Tạo đơn hàng
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

            //Bước 6: Gửi thông báo đến admin về đơn hàng mới
            $this->sentMessToAdmin(
                'Đơn hàng mới từ ' . $user->hoten .'-'. $user->sodienthoai,
                'Người dùng ' . $user->hoten .'-'. $user->sodienthoai.'-'. $user->username.'-'. $user->email. ' vừa tạo đơn hàng mới mã ' . $donhang->madon . '. Vui lòng kiểm tra và gọi điện cho khách hàng để truyển trạng thái đơn hàng từ Chờ xử lý -> Đã xác nhận và xử lý đơn hàng kịp thời.',
                $this->domain.'donhang/show/' . $donhang->id
            );

            DB::commit();
            $donhang->created_at = $donhang->created_at ? $donhang->created_at->toIso8601String() : null;
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

    public function update(Request $request, $id)
    {
        $enumTrangthai = DonhangModel::getEnumValues('trangthai');
        $user = $request->get('auth_user');

        $validated = $request->validate([
            'id_phuongthuc' => ['required', 'exists:phuongthuc,id'],
            'id_magiamgia'  => ['nullable', 'exists:magiamgia,id'],
            'trangthai'     => ['required', Rule::in($enumTrangthai)],
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
            // 🧩 Nếu cập nhật phương thức hoặc mã giảm giá → chỉ cho phép khi còn "Chờ xử lý"
            if (isset($validated['id_phuongthuc']) || isset($validated['id_magiamgia'])) {
                if ($donhang->trangthai !== 'Chờ xử lý') {
                    DB::rollBack();
                    return $this->jsonResponse([
                        'status'  => false,
                        'message' => 'Chỉ có thể thay đổi thông tin thanh toán khi đơn hàng đang ở trạng thái "Chờ xử lý".',
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            // 🧩 Cập nhật thông tin đơn hàng
            $donhang->update($validated);

            // 🧩 Nếu thay đổi trạng thái, đồng bộ chi tiết
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

        $donhang->update(['trangthai' => 'Đã hủy đơn']);

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
        $vnp_Returnurl = route('toi.donhang.payment-callback');

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



}



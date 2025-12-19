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
use App\Models\PhiVanChuyenModel;
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
            'Chờ thanh toán',
            'Chờ xử lý',
            'Đã xác nhận',
            'Đang chuẩn bị hàng',
            'Đang giao hàng',
            'Đã giao hàng',
            'Đã hủy',
        ];

        // Label hiển thị tương ứng
        $labels = [
            'Chờ thanh toán' => 'Chờ thanh toán',
            'Chờ xử lý' => 'Đang xử lý',
            'Đã xác nhận' => 'Đang xử lý',
            'Đang chuẩn bị hàng' => 'Đang xử lý',
            'Đang giao hàng' => 'Đang giao hàng',
            'Đã giao hàng' => 'Đã giao',
            'Đã hủy' => 'Đã hủy',
            'Thành công' => 'Đã giao',
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

    // public function index(Request $request)
    // {
    //     $user = $request->get('auth_user');

    //     // ❌ Chưa đăng nhập
    //     if (!$user) {
    //         return $this->jsonResponse([
    //             'status' => false,
    //             'message' => 'Không xác thực được user.',
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Nhóm trạng thái hiển thị cho FE
    //     |--------------------------------------------------------------------------
    //     | Thứ tự:
    //     | 1. Chờ xác nhận
    //     | 2. Đang xử lý (Đã xác nhận + Đang chuẩn bị hàng)
    //     | 3. Đang vận chuyển
    //     | 4. Đã giao
    //     | 5. Đã hoàn thành
    //     | 6. Đã hủy
    //     */
    //     $statusGroups = [
    //         [
    //             'label' => 'Chờ thanh toán',
    //             'trangthai' => ['Chờ thanh toán'],
    //         ],
    //         [
    //             'label' => 'Chờ xác nhận',
    //             'trangthai' => ['Chờ xử lý'],
    //         ],
    //         [
    //             'label' => 'Đang xử lý',
    //             'trangthai' => ['Đã xác nhận', 'Đang chuẩn bị hàng'],
    //         ],
    //         [
    //             'label' => 'Đang vận chuyển',
    //             'trangthai' => ['Đang giao hàng'],
    //         ],
    //         [
    //             'label' => 'Đã giao',
    //             'trangthai' => ['Đã giao hàng'],
    //         ],
    //         [
    //             'label' => 'Đã hoàn thành',
    //             'trangthai' => ['Thành công'],
    //         ],
    //         [
    //             'label' => 'Đã hủy',
    //             'trangthai' => ['Đã hủy'],
    //         ],
    //     ];

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Query đơn hàng của user
    //     |--------------------------------------------------------------------------
    //     */
    //     $query = DonhangModel::with([
    //         'chitietdonhang.bienthe.sanpham',
    //         'chitietdonhang.bienthe.loaibienthe',
    //         'chitietdonhang.bienthe.sanpham.hinhanhsanpham'
    //     ])->where('id_nguoidung', $user->id);

    //     // Lọc theo trạng thái DB (nếu FE truyền)
    //     if ($request->filled('trangthai')) {
    //         $query->where('trangthai', $request->trangthai);
    //     }

    //     // Lọc theo mã đơn
    //     if ($request->filled('madon')) {
    //         $query->where('madon', $request->madon);
    //     }

    //     $donhangs = $query->latest()->get();

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Gom nhóm đơn hàng theo trạng thái hiển thị
    //     |--------------------------------------------------------------------------
    //     */
    //     $grouped = [];

    //     foreach ($statusGroups as $group) {
    //         $donTheoTrangThai = $donhangs->filter(function ($don) use ($group) {
    //             return in_array($don->trangthai, $group['trangthai']);
    //         });

    //         $grouped[] = [
    //             'label' => $group['label'],
    //             'trangthai' => $group['trangthai'],
    //             'soluong' => $donTheoTrangThai->count(),
    //             'donhang' => TheoDoiDonHangResource::collection($donTheoTrangThai),
    //         ];
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Response chuẩn API
    //     |--------------------------------------------------------------------------
    //     */
    //     return $this->jsonResponse([
    //         'status' => true,
    //         'message' => "Danh sách đơn hàng của khách hàng #{$user->id}: {$user->hoten}",
    //         'data' => $grouped
    //     ], Response::HTTP_OK);
    // }

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
        // $provinces = config('tinhthanh', []);
        // // lấy danh sách khu vực (khi config trả mảng hoặc object)
        // $arrKhuvuc = [];
        // if (is_array($provinces)) {
        //     $arrKhuvuc = $provinces['khuvuc'] ?? [];
        // } elseif (is_object($provinces)) {
        //     $arrKhuvuc = $provinces->khuvuc ?? [];
        // }

        // // nếu arrKhuvuc là mảng, chuyển sang chuỗi cho rule in:
        // $inKhuvuc = is_array($arrKhuvuc) && count($arrKhuvuc) ? implode(',', $arrKhuvuc) : '';

        // Bước 1: Validate dữ liệu đầu vào
        $validator = Validator::make($request->only(
            'ma_phuongthuc',
            'ma_magiamgia',
            'id_diachinguoidung',
            // 'nguoinhan',
            // 'diachinhan',
            // 'sodienthoai',
            // 'khuvucgiao'
        ), [
            'ma_phuongthuc'     => 'required|string|exists:phuongthuc,maphuongthuc',
            'ma_magiamgia'      => 'nullable|string|exists:magiamgia,magiamgia',
            'id_diachinguoidung'=> 'required|integer|exists:diachi_nguoidung,id',
            // 'nguoinhan'         => 'required|string',
            // 'diachinhan'        => 'required|string',
            // 'sodienthoai'       => 'required|string|max:10',
            // // nếu không có khu vực hợp lệ thì bỏ rule in: để không gây fail
            // 'khuvucgiao'        => $inKhuvuc ? 'required|string|in:' . $inKhuvuc : 'required|string',
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

            // $trangthaiDonhang = 'Chờ xử lý';
            // $trangthaiThanhtoan = 'Chưa thanh toán';

            // if ($ma_phuongthuc != 'cod') {
            //     $mapTrangthai = [
            //         'Hoạt động' => 'Chờ xử lý',
            //         'Tạm khóa' => 'Đã hủy',
            //         'Dừng hoạt động' => 'Đã hủy',
            //     ];
            //     $trangthaiDonhang = $mapTrangthai[$phuongthuc->trangthai] ?? 'Chờ xử lý';
            // }
            $trangthaiThanhtoan = 'Chưa thanh toán';

            if ($ma_phuongthuc === 'cp' || $ma_phuongthuc === 'dbt') {
                // Nếu là chuyển khoản trực tiếp hoặc thanh toán online
                $trangthaiDonhang = 'Chờ thanh toán';
            } elseif ($ma_phuongthuc === 'cod') {
                // Nếu là COD (nhận tiền khi giao hàng)
                $mapTrangthai = [
                    'Hoạt động' => 'Chờ xử lý',
                    'Tạm khóa' => 'Đã hủy',
                    'Dừng hoạt động' => 'Đã hủy',
                ];
                $trangthaiDonhang = $mapTrangthai[$phuongthuc->trangthai] ?? 'Chờ xử lý';
            } else {
                // Trường hợp khác nếu có
                $trangthaiDonhang = 'Chờ xử lý';
            }

            $freeship = false;
            $maMagiamgiaInput = $request->input('ma_magiamgia');
            if ($maMagiamgiaInput) {
                $freeship = MagiamgiaModel::where('magiamgia', $maMagiamgiaInput)
                    ->where('giatri', 0)
                    ->where('ngaybatdau', '<=', now())
                    ->where('ngayketthuc', '>=', now())
                    ->where('trangthai', 'Hoạt động')
                    ->exists();
            }

            $id_diachinguoidung = $validated['id_diachinguoidung'];

            $diachiGiaoHang = $user->diachi()->where('id', $id_diachinguoidung)->first();
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

            $id_magiamgia = null;
            if ($maMagiamgiaInput) {
                $id_magiamgia = MagiamgiaModel::where('magiamgia', $maMagiamgiaInput)
                    ->where('ngaybatdau', '<=', now())
                    ->where('ngayketthuc', '>=', now())
                    ->where('trangthai', 'Hoạt động')
                    ->value('id');
            }

            $tongsoluong = $giohang->sum('soluong');

            $phigia = ($id_phivanchuyen == 1 ? 25000 : ($id_phivanchuyen == 2 ? 35000 : 0));
            $tamtinh = $giohang->sum('thanhtien') + $phigia;

            $giatriMagiamgia = $id_magiamgia ? MagiamgiaModel::where('id', $id_magiamgia)->value('giatri') : 0;

            $thanhtien = $tamtinh - $giatriMagiamgia;
            if ($thanhtien < 0) $thanhtien = 0; // tránh âm

            // $sodienthoai = $validated['sodienthoai'];
            // $diachinhan = $validated['diachinhan'];
            // $nguoinhan = $validated['nguoinhan'];
            $ma_magiamgia = MagiamgiaModel::find($id_magiamgia) ?? null;

            $ma_phuongthuc = $validated['ma_phuongthuc'];

            // Xác định hinh thuc thanh toan
            $hinhthucthanhtoan = '';
            if ($ma_phuongthuc === "cod") {
                $hinhthucthanhtoan = "Nhận tiền khi giao hàng.";
            } elseif ($ma_phuongthuc === "dbt") {
                $hinhthucthanhtoan = "Thanh toán online.";
            } elseif ($ma_phuongthuc === "cp") {
                $hinhthucthanhtoan = "Chuyển khoản trực tiếp.";
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Phương thức thanh toán không được hỗ trợ.',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Lấy phí vận chuyển — kiểm tra null an toàn
            $phivanchuyen = PhiVanChuyenModel::find($id_phivanchuyen);
            if (!$phivanchuyen) {
                // fallback: đặt tên mặc định và phí = 0
                $ten_phivanchuyen = 'Không xác định';
                $phigia = 0;
            } else {
                $ten_phivanchuyen = $phivanchuyen->ten;
            }

            // $khuvucgiao
            // $khuvucgiao = $validated['khuvucgiao'];

            $nguoinhan   = $diachiGiaoHang->hoten ?? $user->hoten;
            $diachinhan  = $diachiGiaoHang->diachi ?? $diachiGiaoHang->diachi;
            $sodienthoai = $diachiGiaoHang->sodienthoai ?? $user->sodienthoai;
            $khuvucgiao = $diachiGiaoHang->tinhthanh;

            $donhang = DonhangModel::create([
                'id_phuongthuc'       => $phuongthuc->id,
                'id_nguoidung'        => $user->id,
                'id_phivanchuyen'     => $id_phivanchuyen,
                'id_diachinguoidung'  => $id_diachinguoidung,
                'id_magiamgia'        => $id_magiamgia ?? null,
                'madon'               => DonhangModel::generateOrderCode(),
                'tongsoluong'         => $tongsoluong,
                'tamtinh'             => $tamtinh,
                'thanhtien'           => $thanhtien,
                'trangthaithanhtoan'  => $trangthaiThanhtoan,
                'trangthai'           => $trangthaiDonhang,
                // thông tin giao hàng
                'sodienthoai'         => $sodienthoai,
                'diachinhan'          => $diachinhan,
                'nguoinhan'           => $nguoinhan,
                'khuvucgiao'          => $khuvucgiao,
                // thông tin vận chuyển / voucher

                'hinhthucvanchuyen'   => $ten_phivanchuyen ?? 'Không xác định',
                'phigiaohang'         => $phigia,
                'hinhthucthanhtoan'   => $hinhthucthanhtoan,
                'mavoucher'           => $ma_magiamgia ? $ma_magiamgia->magiamgia : null,
                'giagiam'             => $giatriMagiamgia
            ]);

            foreach ($giohang as $item) {
                $bienthe = BientheModel::with(['loaibienthe', 'sanpham'])->find($item->id_bienthe);
                if (!$bienthe) {
                    continue; // Nếu biến thể không tồn tại thì bỏ qua
                }
                $tenloaibienthe = $bienthe->loaibienthe->ten ?? "Không có";
                $tensanpham = $bienthe->sanpham->ten ?? "Không có";
                $dongia = 0;
                if ($item->thanhtien > 0 && $item->soluong > 0) {
                    $dongia = intval($item->thanhtien / $item->soluong);
                }
                ChitietdonhangModel::create([
                    'id_donhang'     => $donhang->id,
                    'id_bienthe'     => $item->id_bienthe,
                    'tensanpham'     => $tensanpham,
                    'tenloaibienthe' => $tenloaibienthe,
                    'soluong'        => $item->soluong,
                    'dongia'         => $dongia,
                ]);
            }

            GiohangModel::where('id_nguoidung', $user->id)->delete();

            // gửi thông báo
            $this->sentMessToAdmin(
                'Đơn hàng mới từ ' . $user->hoten . '-' . $user->sodienthoai,
                'Người dùng ' . $user->hoten . '-' . $user->sodienthoai . '-' . $user->username . '-' . $user->email . ' vừa tạo đơn hàng mới mã ' . $donhang->madon . '. Vui lòng kiểm tra và gọi điện cho khách hàng để truyền trạng thái đơn hàng từ Chờ xử lý -> Đã xác nhận và xử lý đơn hàng kịp thời.',
                $this->domain . 'donhang/show/' . $donhang->id,
                "Đơn hàng"
            );

            $this->SentMessToClient(
                'Xác nhận đơn hàng mới của bạn',
                'Chào ' . $user->hoten . ', bạn đã tạo thành công đơn hàng mã ' . $donhang->madon .
                '. Vui lòng chờ nhân viên liên hệ để xác nhận và xử lý đơn hàng. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
                $this->domainClient . '/' . 'don-hang',
                "Đơn hàng",
                $user->id
            );

            // Lưu IP vào Redis nếu voucher là mã người dùng mới (theo logic cũ bạn để id = 2)
            $magiamgiaId = $id_magiamgia;
            $ip = $request->getClientIp();
            if ($magiamgiaId == 2) {
                $redisIpKey = "used_voucher_ip:$ip";
                Redis::setex($redisIpKey, 86400 * 365, true);
            }

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

    public function update_trangthai(Request $request, $id)
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
        try {
            $validated = $request->validate([
                'trangthai' => 'required|string|in:Đã giao hàng,Đã hủy',
            ]);
        }  catch (\Illuminate\Validation\ValidationException $e) {

            return $this->jsonResponse([
                'error' => true,
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        }

        // Định nghĩa thứ tự trạng thái hợp lệ
        $orderStates = [
            'Chờ thanh toán' => 1,
            'Chờ xử lý' => 2,
            'Đã xác nhận' => 3,
            'Đang chuẩn bị hàng' => 4,
            'Đang giao hàng' => 5,
            'Đã giao hàng' => 6,
            'Đã hủy' => 7,
            'Thành công' => 8,
        ];

        $currentStatus = $donhang->trangthai;
        $newStatus = $validated['trangthai'];

        // Kiểm tra trạng thái mới có hợp lệ không (không được lùi lại, trừ trường hợp là "Đã hủy")
        if ($newStatus !== 'Đã hủy' && $orderStates[$newStatus] < $orderStates[$currentStatus]) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không thể chuyển trạng thái ngược lại.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $chiTietTrangThai = ($newStatus === 'Đã hủy') ? 'Đã hủy' : 'Đã đặt';

        DB::transaction(function () use ($donhang, $newStatus, $chiTietTrangThai) {
            $donhang->trangthai = $newStatus;
            $donhang->save();

            foreach ($donhang->chitietdonhang as $chitiet) {
                $chitiet->trangthai = $chiTietTrangThai;
                $chitiet->save();
            }
        });
        // đã giao hàng thanh công với COD thì thangthaithanhtoan là Chưa thanh toán phải gửi kèm thông báo lấy tiền đơn vị vận chuyển và chuyển trangthaithanhtoan về Đã thanh toán

        $message = "Vui lòng kiểm tra và gọi điện cho khách hàng để xác nhận và xử lý đơn hàng kịp thời.";
        // Nếu trạng thái là "Đã giao hàng", gửi thông báo cho admin
        if ($newStatus === 'Đã giao hàng') {

            // Thông báo khách nhận hàng thành công
            $tieude = "Thông báo khách hàng đã nhân hàng thành công {$donhang->madon}";
            $noidung = "Đơn hàng #{$donhang->id} - {$donhang->madon} của người dùng #{$user->hoten} đã cập nhật nhân hàng thành công.".$message;
            $lienket = $this->domain . "donhang/edit/{$donhang->id}";
            $this->sentMessToAdmin($tieude,$noidung,$lienket,"Đơn hàng");

            // Nếu phương thức thanh toán là COD (3), nhắc admin gọi đơn vị vận chuyển nhận tiề
            if ($donhang->id_phuongthuc == 3) {
                $tieudeCod = "Nhắc nhận tiền từ đơn vị vận chuyển cho đơn hàng {$donhang->madon}";
                $noidungCod = "Đơn hàng #{$donhang->id} - {$donhang->madon} đã được khách nhận. Vui lòng liên hệ đơn vị vận chuyển để nhận tiền thanh toán COD.";
                $lienket = $this->domain . "donhang/edit/{$donhang->id}";
                $this->sentMessToAdmin($tieudeCod, $noidungCod, $lienket,"Đơn hàng");
            }
        }

        // Nếu trạng thái là "Đã hủy", gửi thông báo cho admin
        if ($newStatus === 'Đã hủy') {
            $tieude = "Thông báo hủy đơn hàng {$donhang->madon}";
            $noidung = "Đơn hàng #{$donhang->id} - {$donhang->madon} của người dùng #{$user->hoten} đã cập nhật hủy đơn hàng.".$message;

            $lienket = $this->domain . "donhang/edit/{$donhang->id}";

            $this->sentMessToAdmin($tieude,$noidung,$lienket,"Đơn hàng");
        }

        $donhang->load(['chitietdonhang.bienthe.loaibienthe', 'chitietdonhang.bienthe.sanpham','chitietdonhang.bienthe.sanpham.hinhanhsanpham']);

        return response()->json(new TheoDoiDonHangDetailResource($donhang), Response::HTTP_OK);
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


    public function update_phuongthuc(Request $request, $id)
    {
        $user = $request->get('auth_user');
        if (!$user) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không xác thực được user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Lấy đơn hàng
        $donhang = DonhangModel::find($id);

        if (!$donhang || $donhang->id_nguoidung !== $user->id) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy đơn hàng hoặc bạn không có quyền.',
            ], Response::HTTP_NOT_FOUND);
        }

        // ❌ Không cho đổi nếu đơn đã xử lý
        if ($donhang->trangthai !== 'Chờ xử lý') {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Chỉ được thay đổi phương thức thanh toán khi đơn đang ở trạng thái Chờ xử lý.',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Validate
        try {
            $validated = $request->validate([
                'ma_phuongthuc' => 'required|exists:phuongthuc,maphuongthuc',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Dữ liệu đầu vào không hợp lệ.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Lấy phương thức thanh toán
        $phuongthuc = PhuongThucModel::where('maphuongthuc', $validated['ma_phuongthuc'])->first();

        // Update
        $donhang->id_phuongthuc = $phuongthuc->id;
        $donhang->save();

        // Gửi thông báo admin
        $tieude = "Khách hàng thay đổi phương thức thanh toán - {$donhang->madon}";
        $noidung = "Đơn hàng #{$donhang->id} ({$donhang->madon}) của khách {$user->hoten} đã thay đổi phương thức thanh toán sang: {$phuongthuc->ten_phuongthuc}.";
        $lienket = $this->domain . "donhang/edit/{$donhang->id}";
        $this->sentMessToAdmin($tieude, $noidung, $lienket, "Đơn hàng");

        // Load lại quan hệ cần thiết
        $donhang->load([
            'phuongthuc',
            'chitietdonhang.bienthe.sanpham'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật phương thức thanh toán thành công.',
            'data' => new TheoDoiDonHangDetailResource($donhang)
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

    // #begin------------------- Mua Lại Đơn Hàng Và Đặt hàng lại đơn hàng ----------------------//
    public function thanhToanLaiDonHang(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $donHang = DonHangModel::find($id);

        if (!$donHang) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        // Kiểm tra trạng thái đơn hàng có phải 'Đã hủy' không
        if ($donHang->trangthai != 'Đã hủy') {
            return response()->json(['message' => 'Đơn hàng chưa bị hủy không thể thanh toán lại'], 400);
        }

        $donHang->trangthai = 'Chờ xử lý';
        $donHang->save();

        $donhang = $donHang;

        $this->sentMessToAdmin(
            'Đơn hàng thanh toán lại từ ' . $user->hoten . '-' . $user->sodienthoai,
            'Người dùng ' . $user->hoten . '-' . $user->sodienthoai . '-' . $user->username . '-' . $user->email . ' vừa tạo đơn hàng mới mã ' . $donhang->madon . '. Vui lòng kiểm tra và gọi điện cho khách hàng để truyền trạng thái đơn hàng từ Chờ xử lý -> Đã xác nhận và xử lý đơn hàng kịp thời.',
            $this->domain . 'donhang/show/' . $donhang->id,
            "Đơn hàng"
        );

        $this->SentMessToClient(
            'Xác nhận đơn hàng mới của bạn',
            'Chào ' . $user->hoten . ', bạn đã tạo thành công đơn hàng mã ' . $donhang->madon .
            '. Vui lòng chờ nhân viên liên hệ để xác nhận và xử lý đơn hàng. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
            $this->domainClient . '/' . 'don-hang',
            "Đơn hàng",
            $user->id
        );

        // TODO: gọi xử lý thanh toán (redirect hoặc gọi API thanh toán)

        return response()->json([
            'message' => 'Đơn hàng đã được cập nhật trạng thái, bạn có thể tiến hành thanh toán lại',
            'id_donhang' => $donHang->id,
            'trangthai' => $donHang->trangthai,
        ]);
    }


    public function muaLaiDonHang(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $donHangCu = DonHangModel::with([
            'chitietdonhang.bienthe.sanpham'
        ])
            ->where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->where('trangthai', 'Thành công')
            ->first();

        if (!$donHangCu) {
            return response()->json([
                'status' => false,
                'message' => 'Đơn hàng không tồn tại hoặc chưa hoàn thành'
            ], 404);
        }

        DB::beginTransaction();

        try {

            /**
             * 1️⃣ Xóa toàn bộ giỏ hàng hiện tại
             */
            GioHangModel::where('id_nguoidung', $user->id)->delete();

            $tongGiaGioHang = 0;
            $items = [];

            /**
             * 2️⃣ Rebuild hàng chính
             */
            foreach ($donHangCu->chitietdonhang as $ct) {

                $bienthe = $ct->bienthe;
                $sanpham = $bienthe?->sanpham;

                if (!$bienthe || !$sanpham) continue;
                if ($sanpham->trangthai !== 'Công khai') continue;

                $giaGoc  = (int) $bienthe->giagoc;
                $giamGia = (int) $bienthe->giamgia;
                $soLuong = (int) $ct->soluong;

                // 🔥 Giá sau giảm %
                $donGiaSauGiam = $giaGoc;
                if ($giamGia > 0) {
                    $donGiaSauGiam = (int) round(
                        $giaGoc * (100 - $giamGia) / 100
                    );
                }

                $thanhtien = $donGiaSauGiam * $soLuong;
                $tongGiaGioHang += $thanhtien;

                GioHangModel::create([
                    'id_bienthe'   => $bienthe->id,
                    'id_nguoidung' => $user->id,
                    'soluong'      => $soLuong,
                    'thanhtien'    => $thanhtien,
                    'trangthai'    => 'Hiển thị',
                ]);

                $items[] = [
                    'bienthe' => $bienthe,
                    'soluong' => $soLuong,
                ];
            }

            /**
             * 3️⃣ Tính & thêm QUÀ TẶNG (thanhtien = 0)
             */
            foreach ($items as $item) {

                $bienthe = $item['bienthe'];
                $soluong = $item['soluong'];

                $promotion = DB::table('quatang_sukien')
                    ->where('id_bienthe', $bienthe->id)
                    ->where('trangthai', 'Hiển thị')
                    ->where('dieukiensoluong', '<=', $soluong)
                    ->where('dieukiengiatri', '<=', $tongGiaGioHang)
                    ->whereRaw('NOW() BETWEEN ngaybatdau AND ngayketthuc')
                    ->first();

                if (!$promotion) continue;

                $soQua = intdiv($soluong, (int) $promotion->dieukiensoluong);
                if ($soQua <= 0) continue;

                GioHangModel::create([
                    'id_bienthe'   => $bienthe->id,
                    'id_nguoidung' => $user->id,
                    'soluong'      => $soQua,
                    'thanhtien'    => 0, // 🎁 QUÀ TẶNG
                    'trangthai'    => 'Hiển thị',
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Khôi phục giỏ hàng thành công'
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi mua lại đơn hàng',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    // public function muaLaiDonHang(Request $request, $id)
    // {
    //     $user = $request->get('auth_user');

    //     $donHangCu = DonHangModel::with('chitietdonhang')->find($id);

    //     if (!$donHangCu || $donHangCu->trangthai != 'Thành công') {
    //         return response()->json(['message' => 'Đơn hàng không tồn tại hoặc chưa thành công'], 404);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $donHangMoi = $donHangCu->replicate();
    //         $donHangMoi->madon = DonHangModel::generateOrderCode();
    //         $donHangMoi->trangthaithanhtoan = 'Chưa thanh toán';
    //         $donHangMoi->trangthai = 'Chờ xử lý';
    //         $donHangMoi->created_at = now();
    //         $donHangMoi->updated_at = now();
    //         $donHangMoi->save();

    //         foreach ($donHangCu->chiTietDonHang as $chiTiet) {
    //             $chiTietMoi = $chiTiet->replicate();
    //             $chiTietMoi->id_donhang = $donHangMoi->id;
    //             $chiTietMoi->save();
    //         }

    //         $donhang = $donHangMoi;
    //         // gửi thông báo
    //         $this->sentMessToAdmin(
    //             'Đơn hàng mua lại từ ' . $user->hoten . '-' . $user->sodienthoai,
    //             'Người dùng ' . $user->hoten . '-' . $user->sodienthoai . '-' . $user->username . '-' . $user->email . ' vừa tạo đơn hàng mới mã ' . $donhang->madon . '. Vui lòng kiểm tra và gọi điện cho khách hàng để truyền trạng thái đơn hàng từ Chờ xử lý -> Đã xác nhận và xử lý đơn hàng kịp thời.',
    //             $this->domain . 'donhang/show/' . $donhang->id,
    //             "Đơn hàng"
    //         );

    //         $this->SentMessToClient(
    //             'Xác nhận đơn hàng mới của bạn',
    //             'Chào ' . $user->hoten . ', bạn đã tạo thành công đơn hàng mã ' . $donhang->madon .
    //             '. Vui lòng chờ nhân viên liên hệ để xác nhận và xử lý đơn hàng. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
    //             $this->domainClient . '/' . 'don-hang',
    //             "Đơn hàng",
    //             $user->id
    //         );

    //         DB::commit();

    //         // return redirect()->route('checkout', ['order_id' => $donHangMoi->id]);
    //         return response()->json(['message' => 'Id đơn hàng mới '.$donHangMoi->id],200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'Lỗi khi tạo đơn hàng mới: ' . $e->getMessage()], 500);
    //     }
    // }
    // #end------------------- Mua Lại Đơn Hàng Và Đặt hàng lại đơn hàng ----------------------//

}



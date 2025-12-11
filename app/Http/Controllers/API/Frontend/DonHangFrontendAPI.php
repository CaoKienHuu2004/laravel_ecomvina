<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\DonHangDetailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Models\ChitietdonhangModel;
use App\Models\GiohangModel;
use App\Models\MagiamgiaModel;
use App\Models\NguoidungModel;
use App\Models\PhuongthucModel;
use App\Models\ThongbaoModel;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use App\Traits\SentMessToAdmin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

use App\Http\Resources\Toi\TheoDoiDonHang\TheoDoiDonHangResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\TheoDoiDonHangResource as TheoDoiDonHangDetailResource;
use App\Models\BientheModel;
use App\Models\PhiVanChuyenModel;
use App\Traits\SentMessToClient;

use Illuminate\Support\Facades\Redis;

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
    use SentMessToAdmin;
    use SentMessToClient;


    protected $domain;
    protected $domainClient;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
        $this->domainClient = env('CLIENT_URL', 'http://148.230.100.215:3000');
    }

    /**
     * @OA\Get(
     *     path="/api/tai-khoan/donhangs",
     *     summary="Lấy danh sách đơn hàng của người dùng (theo trạng thái)",
     *     description="API này trả về danh sách các đơn hàng của người dùng hiện tại, được phân loại theo trạng thái (VD: Chờ thanh toán, Đang xác nhận,...).",
     *     tags={"Đơn hàng (Tài khoản)"},
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
            'Chờ xử lý', //1
            'Đã xác nhận', //2
            'Đang chuẩn bị hàng', //3
            'Đang giao hàng', //4
            'Đã giao hàng', //5
            'Đã hủy', //6
            'Thành công', //7
        ];

        // Label hiển thị tương ứng
        $labels = [
            'Chờ xử lý' => 'Chờ thanh toán',
            'Đã xác nhận' => 'Đang xác nhận',
            'Đang chuẩn bị hàng' => 'Đang đóng gói',
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
    }

    /**
     * @OA\Get(
     *     path="/api/tai-khoan/donhangs/{id}",
     *     summary="Xem chi tiết một đơn hàng của người dùng hiện tại",
     *     tags={"Đơn hàng (Tài khoản)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID đơn hàng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết đơn hàng",
     *         @OA\JsonContent(ref="#/components/schemas/TheoDoiDonHangResource")
     *     ),
     *     @OA\Response(response=401, description="Không xác thực được user"),
     *     @OA\Response(response=403, description="Không có quyền xem đơn hàng này"),
     *     @OA\Response(response=404, description="Không tìm thấy đơn hàng")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/tai-khoan/donhangs",
     *     summary="Tạo đơn hàng mới từ giỏ hàng của người dùng",
     *     description="
     *         API cho phép người dùng tạo đơn hàng mới từ giỏ hàng hiện tại.
     *         Khi đơn hàng được tạo, hệ thống sẽ:
     *         - Tạo đơn IP người dùng vào table used_voucher_ip tương ứng ở redis, phụ vụ magiamgia dieukien (id -2 - khách hàng mới được giảm 50k, với giỏ hàng 50k trở lên).
     *         - Tạo đơn hàng với trạng thái và phương thức thanh toán tương ứng.
     *         - Tạo chi tiết đơn hàng cho từng sản phẩm trong giỏ.
     *         - Xóa giỏ hàng của người dùng sau khi tạo đơn.
     *         - Trạng thái thanh toán mặc định là 'Chưa thanh toán' hoặc 'Đã thanh toán' tùy phương thức.
     *
     *         **Lưu ý**:
     *         - Các xử lý giảm tồn kho, tăng lượt mua được thực hiện tự động qua Observer khi đơn hàng chuyển sang trạng thái 'Thành công'.
     *     ",
     *     tags={"Đơn hàng (Tài khoản)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ma_phuongthuc"},
     *             @OA\Property(property="ma_phuongthuc", type="string", example="cod", description="Mã phương thức thanh toán, ví dụ 'cod', 'paypal', ..."),
     *             @OA\Property(property="ma_magiamgia", type="string", nullable=true, example=null, description="Mã giảm giá (nếu có)"),
     *             @OA\Property(property="id_diachinguoidung", type="int", example=19, description="id dia chỉ ngươi dùng FE lấy được trước đó rồi truyền vào component donhang (nếu có)"),
     *             @OA\Property(property="nguoinhan", type="string", example="Lê văn B", description="Họ tên người nhận"),
     *             @OA\Property(property="diachinhan", type="string", example="123 pham văn trị, q.bình thạnh, Thành phố hồ chí minh", description="địa chỉ người nhận hàng"),
     *             @OA\Property(property="sodienthoai", type="string", example="1234567890", description="10 chử số "),
     *             @OA\Property(property="khuvucgiao", type="string", example="Thành phố Hồ Chí Minh", description="thuộc enum tỉnh thành việt nam")
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
     *                 description="Thông tin đơn hàng vừa tạo, bao gồm chi tiết đơn hàng và sản phẩm",
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="madon", type="string", example="DH20251122001"),
     *                 @OA\Property(property="tongsoluong", type="integer", example=3),
     *                 @OA\Property(property="tamtinh", type="integer", example=250000),
     *                 @OA\Property(property="thanhtien", type="integer", example=230000),
     *                 @OA\Property(property="trangthaithanhtoan", type="string", example="Chưa thanh toán"),
     *                 @OA\Property(property="trangthai", type="string", example="Chờ xử lý"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-22T07:45:00Z"),
     *                 @OA\Property(
     *                     property="chitietdonhang",
     *                     type="array",
     *                     description="Danh sách chi tiết đơn hàng",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id_bienthe", type="integer", example=10),
     *                         @OA\Property(property="soluong", type="integer", example=2),
     *                         @OA\Property(property="dongia", type="integer", example=120000),
     *                         @OA\Property(property="trangthai", type="string", example="Đã đặt"),
     *                         @OA\Property(
     *                             property="bienthe",
     *                             type="object",
     *                             description="Thông tin biến thể sản phẩm",
     *                             @OA\Property(property="giagoc", type="integer", example=120000),
     *                             @OA\Property(
     *                                 property="sanpham",
     *                                 type="object",
     *                                 description="Thông tin sản phẩm",
     *                                 @OA\Property(property="ten", type="string", example="Áo thun nam"),
     *                                 @OA\Property(property="ma_sp", type="string", example="SP001")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Giỏ hàng trống hoặc dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Giỏ hàng trống, không thể tạo đơn hàng!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu đầu vào không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", description="Các lỗi validate, key là tên trường, value là mảng lỗi"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi server khi tạo đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Lỗi khi tạo đơn hàng: ...")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $provinces = config('tinhthanh', []);
        // lấy danh sách khu vực (khi config trả mảng hoặc object)
        $arrKhuvuc = [];
        if (is_array($provinces)) {
            $arrKhuvuc = $provinces['khuvuc'] ?? [];
        } elseif (is_object($provinces)) {
            $arrKhuvuc = $provinces->khuvuc ?? [];
        }

        // nếu arrKhuvuc là mảng, chuyển sang chuỗi cho rule in:
        $inKhuvuc = is_array($arrKhuvuc) && count($arrKhuvuc) ? implode(',', $arrKhuvuc) : '';

        // Bước 1: Validate dữ liệu đầu vào
        $validator = Validator::make($request->only(
            'ma_phuongthuc',
            'ma_magiamgia',
            'id_diachinguoidung',
            'nguoinhan',
            'diachinhan',
            'sodienthoai',
            'khuvucgiao'
        ), [
            'ma_phuongthuc'     => 'required|string|exists:phuongthuc,maphuongthuc',
            'ma_magiamgia'      => 'nullable|string|exists:magiamgia,magiamgia',
            'id_diachinguoidung'=> 'required|integer|exists:diachi_nguoidung,id',
            'nguoinhan'         => 'required|string',
            'diachinhan'        => 'required|string',
            'sodienthoai'       => 'required|string|max:10',
            // nếu không có khu vực hợp lệ thì bỏ rule in: để không gây fail
            'khuvucgiao'        => $inKhuvuc ? 'required|string|in:' . $inKhuvuc : 'required|string',
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

            $sodienthoai = $validated['sodienthoai'];
            $diachinhan = $validated['diachinhan'];
            $nguoinhan = $validated['nguoinhan'];
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
            $khuvucgiao = $validated['khuvucgiao'];

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
                // thông tin vận chuyển / voucher
                'khuvucgiao'          => $khuvucgiao,
                'hinhthucvanchuyen'   => $ten_phivanchuyen ?? 'Không xác định',
                'phigiaohang'         => $phigia,
                'hinhthucthanhtoan'   => $hinhthucthanhtoan,
                'mavoucher'           => $ma_magiamgia,
                'giagiam'             => $giatriMagiamgia
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
    // public function store(Request $request)
    // {
    //     // Bước 1: Validate dữ liệu đầu vào
    //     $validator = Validator::make($request->only('ma_phuongthuc', 'ma_magiamgia', 'id_diachinguoidung'), [
    //         'ma_phuongthuc'      => 'required|string|exists:phuongthuc,maphuongthuc',
    //         'ma_magiamgia'       => 'nullable|string|exists:magiamgia,magiamgia',
    //         'id_diachinguoidung'  => 'required|integer|exists:diachi_nguoidung,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => $validator->errors(),
    //         ], Response::HTTP_UNPROCESSABLE_ENTITY);
    //     }

    //     $validated = $validator->validated();

    //     // Bước 2: Lấy giỏ hàng người dùng
    //     $user = $request->get('auth_user');
    //     $giohang = GiohangModel::with('bienthe')
    //         ->where('id_nguoidung', $user->id)
    //         ->where('trangthai', 'Hiển thị')
    //         ->get();

    //     if ($giohang->isEmpty()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Giỏ hàng trống, không thể tạo đơn hàng!',
    //         ], Response::HTTP_BAD_REQUEST);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $ma_phuongthuc = $validated['ma_phuongthuc'];

    //         $phuongthuc = PhuongthucModel::where('maphuongthuc', $ma_phuongthuc)->first();
    //         if (!$phuongthuc) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Phương thức thanh toán không hợp lệ',
    //             ], Response::HTTP_BAD_REQUEST);
    //         }

    //         $trangthaiDonhang = 'Chờ xử lý';
    //         $trangthaiThanhtoan = 'Chưa thanh toán';

    //         if ($ma_phuongthuc != 'cod') {
    //             $mapTrangthai = [
    //                 'Hoạt động' => 'Chờ xử lý',
    //                 'Tạm khóa' => 'Đã hủy',
    //                 'Dừng hoạt động' => 'Đã hủy',
    //             ];
    //             $trangthaiDonhang = $mapTrangthai[$phuongthuc->trangthai] ?? 'Chờ xử lý';
    //         }

    //         $freeship = MagiamgiaModel::where('magiamgia', $request->input('ma_magiamgia'))
    //             ->where('giatri', 0)
    //             ->where('ngaybatdau', '<=', now())
    //             ->where('ngayketthuc', '>=', now())
    //             ->where('trangthai', 'Hoạt động')
    //             ->exists();

    //         $id_diachinguoidung = $validated['id_diachinguoidung'];

    //         $diachiGiaoHang = $user->diachi()->where('id', $id_diachinguoidung)->first();
    //         if (!$diachiGiaoHang) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Địa chỉ giao hàng không thuộc tài khoản của bạn!',
    //             ], Response::HTTP_BAD_REQUEST);
    //         }

    //         if ($freeship) {
    //             $id_phivanchuyen = 3;
    //         } elseif ($diachiGiaoHang->tinhthanh === "Thành phố Hồ Chí Minh") {
    //             $id_phivanchuyen = 1;
    //         } else {
    //             $id_phivanchuyen = 2;
    //         }

    //         $id_magiamgia = MagiamgiaModel::where('magiamgia', $request->input('ma_magiamgia'))
    //             ->where('ngaybatdau', '<=', now())
    //             ->where('ngayketthuc', '>=', now())
    //             ->where('trangthai', 'Hoạt động')
    //             ->value('id');

    //         $tongsoluong = $giohang->sum('soluong');

    //         $tamtinh = $giohang->sum('thanhtien') + ($id_phivanchuyen == 1 ? 25000 : ($id_phivanchuyen == 2 ? 35000 : 0));

    //         $giatriMagiamgia = $id_magiamgia ? MagiamgiaModel::where('id', $id_magiamgia)->value('giatri') : 0;

    //         $thanhtien = $tamtinh - $giatriMagiamgia;

    //         if ($thanhtien < 0) $thanhtien = 0; // tránh âm
    //         // khuvucgiao hinhthucvanchuyen phigiaohang hinhthucthanhtoan mavoucher giagiam
    //         $donhang = DonhangModel::create([
    //             'id_phuongthuc'       => $phuongthuc->id,
    //             'id_nguoidung'        => $user->id,
    //             'id_phivanchuyen'     => $id_phivanchuyen,
    //             'id_diachinguoidung'   => $id_diachinguoidung,
    //             'id_magiamgia'        => $id_magiamgia ?? null,
    //             'madon'               => DonhangModel::generateOrderCode(),
    //             'tongsoluong'         => $tongsoluong,
    //             'tamtinh'             => $tamtinh,
    //             'thanhtien'           => $thanhtien,
    //             'trangthaithanhtoan'  => $trangthaiThanhtoan,
    //             'trangthai'           => $trangthaiDonhang,
    //         ]);

    //         foreach ($giohang as $item) {
    //             $bienthe = BientheModel::with(['loaibienthe', 'sanpham'])->find($item->id_bienthe);
    //             if (!$bienthe) {
    //                 continue; // Nếu biến thể không tồn tại thì bỏ qua
    //             }
    //             $tenloaibienthe = $bienthe->loaibienthe->ten ?? "Không có";
    //             $tensanpham = $bienthe->sanpham->ten ?? "Không có";
    //             ChitietdonhangModel::create([
    //                 'id_bienthe' => $item->id_bienthe,
    //                 'tenloaibienthe' => $tenloaibienthe,
    //                 'tensanpham' => $tensanpham,
    //                 'id_donhang' => $donhang->id,
    //                 'soluong'    => $item->soluong,
    //                 'dongia'     => $item->bienthe->giagoc ?? 0,
    //             ]);
    //         }

    //         GiohangModel::where('id_nguoidung', $user->id)->delete();

    //         $this->sentMessToAdmin(
    //             'Đơn hàng mới từ ' . $user->hoten . '-' . $user->sodienthoai,
    //             'Người dùng ' . $user->hoten . '-' . $user->sodienthoai . '-' . $user->username . '-' . $user->email . ' vừa tạo đơn hàng mới mã ' . $donhang->madon . '. Vui lòng kiểm tra và gọi điện cho khách hàng để truyển trạng thái đơn hàng từ Chờ xử lý -> Đã xác nhận và xử lý đơn hàng kịp thời.',
    //             $this->domain . 'donhang/show/' . $donhang->id,
    //             "Đơn hàng"
    //         );
    //         $this->SentMessToClient(
    //             'Xác nhận đơn hàng mới của bạn',
    //             'Chào ' . $user->hoten . ', bạn đã tạo thành công đơn hàng mã ' . $donhang->madon .
    //             '. Vui lòng chờ nhân viên liên hệ để xác nhận và xử lý đơn hàng. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
    //             $this->domainClient.'/' . 'don-hang', // http://14.321321.241342/don-hang/id
    //             // $this->domainClient.'/' . 'don-hang/' . $donhang->id, // http://14.321321.241342/don-hang/id
    //             "Đơn hàng",
    //             $user->id
    //         ); // trả về bool $check true/false

    //         /// Lưu IP vào bảng IP redis chỉ để check điều kiện người dùng mới cho bảng magiamgia

    //        $magiamgiaId = $id_magiamgia; // $magiamgiaId = $request->input('magiamgia_id'); // mã giảm giá user chọn
    //         $ip = $request->getClientIp();
    //         if ($magiamgiaId == 2) { // 2 là vì trong database mô tả của magiamgia đầy là mã kiểm tra người dùng mới, nền suy ra dùng IP để check
    //             $redisIpKey = "used_voucher_ip:$ip";

    //             // Lưu IP 1 năm
    //             Redis::setex($redisIpKey, 86400 * 365, true);
    //         }
    //         /// Lưu IP vào bảng IP redis chỉ để check điều kiện người dùng mới cho bảng magiamgia


    //         DB::commit();

    //         $donhang->created_at = $donhang->created_at ? $donhang->created_at->toIso8601String() : null;
    //         return response()->json([
    //             'status'  => true,
    //             'message' => 'Tạo đơn hàng thành công!',
    //             'data'    => $donhang->makeVisible(['created_at'])->load('chitietdonhang.bienthe.sanpham'),
    //         ], Response::HTTP_CREATED);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage(),
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }


    /**
     * @OA\Put(
     *     path="/api/tai-khoan/donhangs/{id}",
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
     *     tags={"Đơn hàng (Tài khoản)"},
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


    /**
     * @OA\Patch(
     *     path="/api/tai-khoan/donhangs/{id}/huy",
     *     summary="Hủy đơn hàng của người dùng (đồng bộ kho tự động)",
     *     description="
     *     ❌ Hủy đơn hàng khi đơn vẫn còn trong trạng thái 'Chờ xử lý'.
     *     🔁 Khi đơn bị hủy, **Observer DonhangObserver** sẽ tự hoàn lại số lượng sản phẩm trong kho (`bienthe.soluong += chitietdonhang.soluong`).
     *     ",
     *     tags={"Đơn hàng (Tài khoản)"},
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


    /**
     * @OA\Post(
     *     path="/api/tai-khoan/donhangs/{id}/create-payment-url",
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
        $vnp_Returnurl = route('api.tai-khoan.donhangs.payment-callback');

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
    }


    /**
     * @OA\Get(
     *     path="/api/tai-khoan/donhangs/payment-callback",
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
     *     path="/api/tai-khoan/donhangs/{id}/payment-status",
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

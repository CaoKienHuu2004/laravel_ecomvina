<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Http\Requests\AuthOrderCodeRequesty;
use App\Http\Requests\AuthUsernameOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Traits\ApiResponse;
use App\Http\Resources\Toi\TheoDoiDonHang\TheoDoiDonHangResource;
use App\Traits\SentMessToAdmin;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Tag(
 *     name="Tra cứu đơn hàng (web)",
 *     description="API cho phép người dùng xem và cập nhật trạng thái đơn hàng của họ"
 * )
 */
class TheoDoiDonHangWebApi extends Controller
{
    use ApiResponse;
    use SentMessToAdmin;

    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }


    /**
     * @OA\Get(
     *     path="/web/tracuu-donhang",
     *     summary="Tra cứu danh sách theo dõi đơn hàng (Web)",
     *     description="
     *     API cho phép người dùng tra cứu và theo dõi đơn hàng theo mã đơn.
     *     Kết quả trả về là danh sách các nhóm trạng thái hiển thị:
     *     - Chờ xác nhận
     *     - Đang xử lý (Đã xác nhận + Đang chuẩn bị hàng)
     *     - Đang vận chuyển
     *     - Đã giao
     *     - Đã hoàn thành
     *     - Đã hủy
     *     ",
     *     tags={"Tra cứu đơn hàng (web)"},
     *     security={{"ApiKeyAuth": {}, "ApiKeyOrder": {}}},
     *
     *     @OA\Parameter(
     *         name="madon",
     *         in="query",
     *         required=true,
     *         description="Mã đơn hàng dùng để xác thực và tra cứu (VD: VNA1218073)",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="trangthai",
     *         in="query",
     *         required=false,
     *         description="(Tùy chọn) Lọc đơn hàng theo trạng thái thực tế trong DB",
     *         @OA\Schema(
     *             type="string",
     *             enum={
     *                 "Chờ xử lý",
     *                 "Đã xác nhận",
     *                 "Đang chuẩn bị hàng",
     *                 "Đang giao hàng",
     *                 "Đã giao hàng",
     *                 "Thành công",
     *                 "Đã hủy"
     *             }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách theo dõi đơn hàng được nhóm theo trạng thái",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Danh sách theo dõi đơn hàng"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="label",
     *                         type="string",
     *                         example="Chờ xác nhận",
     *                         description="Tên nhóm trạng thái hiển thị"
     *                     ),
     *                     @OA\Property(
     *                         property="trangthai",
     *                         type="array",
     *                         description="Danh sách trạng thái thực tế trong DB thuộc nhóm này",
     *                         @OA\Items(type="string"),
     *                         example={"Chờ xử lý"}
     *                     ),
     *                     @OA\Property(
     *                         property="soluong",
     *                         type="integer",
     *                         example=1,
     *                         description="Số lượng đơn hàng trong nhóm trạng thái"
     *                     ),
     *                     @OA\Property(
     *                         property="donhang",
     *                         type="array",
     *                         description="Danh sách đơn hàng thuộc nhóm trạng thái",
     *                         @OA\Items(ref="#/components/schemas/TheoDoiDonHangResource")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Thiếu hoặc không xác thực được người dùng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không xác thực được user.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền truy cập đơn hàng (madon không thuộc user)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không có quyền truy cập đơn hàng này.")
     *         )
     *     )
     * )
     */
    public function index(AuthOrderCodeRequesty  $request)
    // public function index(AuthUsernameOrderRequest  $request)
    // public function index(Request $request)
    {
        $user = $request->get('auth_user');
        $donhang = $request->get('auth_donhang');


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
            'Thành công'
        ];

        // Label hiển thị tương ứng
        $labels = [
            'Chờ thanh toán' => 'Chờ thanh toán',
            'Chờ xử lý' => 'Đang xử lý',
            'Đã xác nhận' => 'Đang xử lý',
            'Đang chuẩn bị hàng' => 'Đang xử lý',
            'Đang giao hàng' => 'Đang vận chuyển',
            'Đã giao hàng' => 'Đã giao',
            'Đã hủy' => 'Đã hủy',
            'Thành công' => 'Đã hoàn thành',
        ];

        $query = DonhangModel::with([
            'chitietdonhang.bienthe.sanpham',
            'chitietdonhang.bienthe.loaibienthe',
            'chitietdonhang.bienthe.sanpham.hinhanhsanpham'
        ])->where('id_nguoidung', $user->id);

        if ($request->filled('trangthai') && in_array($request->trangthai, $validTrangThai)) {
            $query->where('trangthai', $request->trangthai);
        }

        if ($request->filled('madon')) {
            $query->where('madon', $request->madon);
        }

        $donhangs = $query->latest()->get();

        // Gom nhóm theo trạng thái + đếm số lượng
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

        return response()->json($grouped, Response::HTTP_OK);
    }

    // public function index(AuthOrderCodeRequesty $request)
    // {
    //     $user = $request->get('auth_user');
    //     $donhangAuth = $request->get('auth_donhang');

    //     // ❌ Chưa xác thực user
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
    //     | Query đơn hàng
    //     |--------------------------------------------------------------------------
    //     */
    //     $query = DonhangModel::with([
    //         'chitietdonhang.bienthe.sanpham',
    //         'chitietdonhang.bienthe.loaibienthe',
    //         'chitietdonhang.bienthe.sanpham.hinhanhsanpham'
    //     ])->where('id_nguoidung', $user->id);

    //     // Lọc theo mã đơn (từ AuthOrderCodeRequesty hoặc request)
    //     if ($donhangAuth) {
    //         $query->where('madon', $donhangAuth->madon);
    //     } elseif ($request->filled('madon')) {
    //         $query->where('madon', $request->madon);
    //     }

    //     // Lọc theo trạng thái DB (nếu FE truyền)
    //     if ($request->filled('trangthai')) {
    //         $query->where('trangthai', $request->trangthai);
    //     }

    //     $donhangs = $query->latest()->get();

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Gom nhóm theo trạng thái hiển thị
    //     |--------------------------------------------------------------------------
    //     */
    //     $grouped = [];

    //     foreach ($statusGroups as $group) {
    //         $donTheoTrangThai = $donhangs->filter(function ($don) use ($group) {
    //             return in_array($don->trangthai, $group['trangthai']);
    //         });

    //         $grouped[] = [
    //             'label' => $group['label'],
    //             'trangthai' => $group['trangthai'], // FE có thể dùng để filter
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
    //         'message' => 'Danh sách theo dõi đơn hàng',
    //         'data' => $grouped
    //     ], Response::HTTP_OK);
    // }




    public function update(AuthOrderCodeRequesty $request, $id)
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

        TheoDoiDonHangResource::withoutWrapping();
        return response()->json(new TheoDoiDonHangResource($donhang), Response::HTTP_OK);
    }
}

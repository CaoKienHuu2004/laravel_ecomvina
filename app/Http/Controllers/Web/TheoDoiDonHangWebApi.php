<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Http\Requests\AuthOrderCodeRequesty;
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
     *     summary="Lấy danh sách đơn hàng của người dùng (theo trạng thái)",
     *     description="API cho phép người dùng xem tất cả các đơn hàng của họ, được phân loại theo từng trạng thái. Mỗi trạng thái bao gồm nhãn hiển thị, mã trạng thái, tổng số đơn và danh sách chi tiết các đơn hàng tương ứng.",
     *     security={
     *         {"ApiKeyAuth": {},"ApiKeyOrder": {}}
     *     },
     *     tags={"Tra cứu đơn hàng (web)"},
     *
     *     @OA\Parameter(
     *         name="madon",
     *         in="query",
     *         description="Mã đơn hàng của người dùng, bắt buộc để xác thực quyền truy cập",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="trangthai",
     *         in="query",
     *         description="(Tùy chọn) Lọc đơn hàng theo trạng thái. Giá trị hợp lệ: Chờ xử lý, Đã xác nhận, Đang chuẩn bị hàng, Đang giao hàng, Đã giao hàng, Đã hủy",
     *         required=false,
     *         @OA\Schema(type="string", enum={"Chờ xử lý","Đã xác nhận","Đang chuẩn bị hàng","Đang giao hàng","Đã giao hàng","Đã hủy"})
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách đơn hàng được nhóm theo trạng thái",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="label", type="string", example="Đang xác nhận", description="Tên hiển thị của trạng thái"),
     *                 @OA\Property(property="trangthai", type="string", example="Đã xác nhận", description="Tên trạng thái thực tế trong cơ sở dữ liệu"),
     *                 @OA\Property(property="soluong", type="integer", example=3, description="Tổng số đơn hàng thuộc trạng thái này"),
     *                 @OA\Property(
     *                     property="donhang",
     *                     type="array",
     *                     description="Danh sách đơn hàng thuộc trạng thái này",
     *                     @OA\Items(ref="#/components/schemas/TheoDoiDonHangResource")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Thiếu hoặc không xác thực được người dùng (username hoặc madon sai hoặc thiếu)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không xác thực được user.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền truy cập đơn hàng (madon không thuộc user này)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không có quyền truy cập đơn hàng này.")
     *         )
     *     )
     * )
     */
    public function index(AuthOrderCodeRequesty  $request)
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
            'Thành công' => 'Đã giao',
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
            'Chờ xử lý' => 1,
            'Đã xác nhận' => 2,
            'Đang chuẩn bị hàng' => 3,
            'Đang giao hàng' => 4,
            'Đã giao hàng' => 5,
            'Đã hủy' => 6,
            'Thành công' => 7,
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

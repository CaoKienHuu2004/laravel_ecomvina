<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Traits\ApiResponse;
use App\Http\Resources\Toi\TheoDoiDonHang\TheoDoiDonHangResource;
use Illuminate\Support\Facades\DB;

use App\Traits\SentMessToAdmin;

class TheoDoiDonHangFrontendAPI extends Controller
{
    use ApiResponse;

    use SentMessToAdmin;

    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }


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

        // $chiTietTrangThai = ($newStatus === 'Đã hủy') ? 'Đã hủy' : 'Đã đặt';

        DB::transaction(function () use ($donhang, $newStatus) {
            $donhang->trangthai = $newStatus;
            $donhang->save();

        });

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

        return $this->jsonResponse([
            'status' => true,
            'message' => "Cập Nhật Trang Thái Đơn Hàng Thành Công Của Danh Sách Đơn Hàng Theo Trạng Thái Đơn Hàng Của Khách Hàng ".$user->id.":".$user->hoten,
            'data' => new TheoDoiDonHangResource($donhang)
        ], Response::HTTP_OK);
    }
}




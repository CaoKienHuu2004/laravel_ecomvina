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
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class DonHangWebApi extends BaseFrontendController
{
    use ApiResponse;


    //--------------- method của Nguyên : begin ------------------ //
    private function generateUniqueMadon()
    {
        do {
            $letters = strtoupper(Str::random(2));
            $numbers = rand(10000, 99999);
            $madon = $letters . $numbers;

        } while (DB::table('donhang')->where('ma_donhang', $madon)->exists());

        return $madon;
    }
    //--------------- method của Nguyên : end ------------------ //


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
            // 🧩 Bước 3: Tạo đơn hàng
            $donhang = DonhangModel::create([
                'id_phuongthuc'     => $validated['id_phuongthuc'],
                'id_nguoidung'      => $user->id,
                'id_phivanchuyen'   => $validated['id_phivanchuyen'],
                'id_diachigiaohang' => $validated['id_diachigiaohang'],
                'id_magiamgia'      => $validated['id_magiamgia'] ?? null,
                'madon'             => DonhangModel::generateOrderCode(),
                'tongsoluong'       => $giohang->sum('soluong'),
                'tamtinh'           => $validated['tamtinh'],
                'thanhtien'         => $validated['thanhtien'],
                'trangthaithanhtoan'=> 'Chưa thanh toán',
                'trangthai'         => 'Chờ xử lý',
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
                'data'    => $donhang->load('chitietdonhang.bienthe.sanpham'),
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
}



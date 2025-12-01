<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Traits\ApiResponse;
use App\Http\Resources\Toi\TheoDoiDonHang\TheoDoiDonHangResource;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Tag(
 *     name="Theo dõi đơn hàng (tôi) (web)",
 *     description="API cho phép người dùng xem và cập nhật trạng thái đơn hàng của họ"
 * )
 */
class TheoDoiDonHangWebApi extends Controller
{
    use ApiResponse;



    /**
     * @OA\Get(
     *     path="/toi/theodoi-donhang",
     *     summary="Lấy danh sách đơn hàng của người dùng",
     *     description="API cho phép người dùng xem danh sách các đơn hàng của họ, có thể lọc theo trạng thái hoặc mã đơn hàng.Yêu cầu phải có `username` và `madon` để xác thực.",
    *       security={
    *         {"ApiKeyAuth": {},"ApiKeyOrder": {}}
    *     },
     *     tags={"Theo dõi đơn hàng (tôi) (web)"},
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Tên tài khoản người dùng, bắt buộc để xác thực",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="madon",
     *         in="query",
     *         description="Mã đơn hàng, bắt buộc để xác thực",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="trangthai",
     *         in="query",
     *         description="Lọc đơn hàng theo trạng thái. Giá trị hợp lệ: Chờ xử lý, Đã xác nhận, Đang chuẩn bị hàng, Đang giao hàng, Đã giao hàng, Đã hủy",
     *         required=false,
     *         @OA\Schema(type="string", enum={"Chờ xử lý","Đã xác nhận","Đang chuẩn bị hàng","Đang giao hàng","Đã giao hàng","Đã hủy"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách đơn hàng của người dùng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh Sách Đơn Hàng Theo Trạng Thái..."),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/TheoDoiDonHangResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Thiếu hoặc không xác thực được người dùng (username hoặc madon sai hoặc thiếu)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền truy cập đơn hàng (madon không thuộc user này)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->get('auth_user');
        // $donhang = $request->get('auth_donhang'); // ko cần lấy về từ middleware
        if (!$user) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không xác thực được user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $query = DonhangModel::with(['chitietdonhang.bienthe.sanpham', 'chitietdonhang.bienthe.loaibienthe','chitietdonhang.bienthe.sanpham.hinhanhsanpham'])
            ->where('id_nguoidung', $user->id);

        $validTrangThai = [
            'Chờ xử lý',
            'Đã xác nhận',
            'Đang chuẩn bị hàng',
            'Đang giao hàng',
            'Đã giao hàng',
            'Đã hủy',
        ];

        if ($request->filled('trangthai') && in_array($request->trangthai, $validTrangThai)) {
            $query->where('trangthai', $request->trangthai);
        }

        if ($request->filled('madon')) {
            $query->where('madon', $request->madon);
        }

        $donhangs = $query->latest()->get();

        TheoDoiDonHangResource::withoutWrapping();
        return response()->json(TheoDoiDonHangResource::collection($donhangs), Response::HTTP_OK);
    }


    /**
     * @OA\Put(
     *     path="/toi/theodoi-donhang/{id}",
     *     summary="Cập nhật trạng thái đơn hàng",
     *     description="API cho phép người dùng cập nhật trạng thái đơn hàng của họ theo ID đơn hàng. Yêu cầu phải có `username` và `madon` để xác thực.",
     *      security={
        *         {"ApiKeyAuth": {},"ApiKeyOrder": {}}
        *     },
     *     tags={"Theo dõi đơn hàng (tôi) (web)"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID đơn hàng cần cập nhật",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Tên tài khoản người dùng, bắt buộc để xác thực",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="madon",
     *         in="query",
     *         description="Mã đơn hàng, bắt buộc để xác thực",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trangthai"},
     *             @OA\Property(property="trangthai", type="string", description="Trạng thái mới của đơn hàng", enum={"Chờ xử lý","Đã xác nhận","Đang chuẩn bị hàng","Đang giao hàng","Đã giao hàng","Đã hủy"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đơn hàng đã được cập nhật trạng thái thành công",
     *         @OA\JsonContent(ref="#/components/schemas/TheoDoiDonHangResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Thiếu hoặc không xác thực được người dùng (username hoặc madon sai hoặc thiếu)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền truy cập đơn hàng (madon không thuộc user này)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng hoặc không có quyền cập nhật",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không tìm thấy đơn hàng hoặc bạn không có quyền.")
     *         )
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

        TheoDoiDonHangResource::withoutWrapping();
        return response()->json(new TheoDoiDonHangResource($donhang), Response::HTTP_OK);
    }
}

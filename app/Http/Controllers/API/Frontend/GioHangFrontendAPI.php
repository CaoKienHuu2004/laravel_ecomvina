<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toi\GioHangResource;
use Illuminate\Http\Request;
use App\Models\GiohangModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Giỏ hàng (tôi)",
 *     description="Các API thao tác với giỏ hàng của người dùng frontend"
 * )
 */
class GioHangFrontendAPI extends BaseFrontendController
{
    /**
     * @OA\Get(
     *     path="/api/toi/giohang",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="Lấy toàn bộ giỏ hàng của người dùng hiện tại",
     *     description="Trả về danh sách sản phẩm trong giỏ hàng của người dùng đang đăng nhập. Nếu giỏ hàng trống sẽ trả về thông báo.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm trong giỏ hàng hoặc thông báo giỏ hàng trống",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách sản phẩm trong giỏ hàng"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/GioHangResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không có quyền truy cập hoặc thiếu token",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->get('auth_user');
        $userId = $user->id;

        $giohang = GiohangModel::with([
                'bienthe.sanpham',
                'bienthe',
                'bienthe.sanpham.hinhanhsanpham',
                'bienthe.loaibienthe'
            ])
            ->where('id_nguoidung', $userId)
            ->where('trangthai', 'Hiển thị')
            ->get();

        // Lọc bỏ các biến thể có soluong = 0
        $giohang = $giohang->filter(fn($item) => $item->soluong > 0)->values();

        if ($giohang->isEmpty()) {
            return $this->jsonResponse([
                'status' => true,
                'message' => 'Giỏ hàng trống',
                'data' => [],
            ], Response::HTTP_OK);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách sản phẩm trong giỏ hàng',
            'data' => GioHangResource::collection($giohang),
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/toi/giohang",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="Thêm sản phẩm vào giỏ hàng (có xử lý ưu đãi và quà tặng)",
     *     description="
     *      - API này dùng để thêm sản phẩm vào giỏ hàng của người dùng hiện tại.
     *      - Hệ thống sẽ tự động kiểm tra xem sản phẩm có nằm trong chương trình quà tặng (`quatang_sukien`) hay không.
     *      - Nếu số lượng mua thỏa mãn điều kiện `dieukien` của sự kiện và nằm trong thời gian hợp lệ (`ngaybatdau` - `ngayketthuc`),
     *        hệ thống sẽ cộng thêm số lượng quà tặng miễn phí (với `thanhtien = 0`).
     *      - Trường `luottang` trong bảng `bienthe` sẽ được cập nhật giảm tương ứng với số lượng quà đã tặng.
     *     ",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Thông tin sản phẩm cần thêm vào giỏ hàng",
     *         @OA\JsonContent(
     *             required={"id_bienthe","soluong"},
     *             @OA\Property(property="id_bienthe", type="integer", example=21, description="ID biến thể sản phẩm"),
     *             @OA\Property(property="soluong", type="integer", example=2, description="Số lượng sản phẩm muốn thêm vào giỏ")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm sản phẩm vào giỏ hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thêm sản phẩm vào giỏ hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Dữ liệu sản phẩm trong giỏ hàng sau khi thêm",
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="id_nguoidung", type="integer", example=2),
     *                 @OA\Property(property="id_bienthe", type="integer", example=21),
     *                 @OA\Property(property="soluong", type="integer", example=2),
     *                 @OA\Property(property="thanhtien", type="number", example=138000),
     *                 @OA\Property(property="trangthai", type="string", example="Hiển thị"),
     *                 @OA\Property(
     *                     property="bienthe",
     *                     type="object",
     *                     description="Thông tin biến thể sản phẩm",
     *                     @OA\Property(property="id", type="integer", example=21),
     *                     @OA\Property(property="giagoc", type="number", example=69000),
     *                     @OA\Property(property="luottang", type="integer", example=1),
     *                     @OA\Property(
     *                         property="sanpham",
     *                         type="object",
     *                         description="Thông tin sản phẩm gốc của biến thể"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dữ liệu không hợp lệ hoặc thiếu trường bắt buộc",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Dữ liệu không hợp lệ hoặc thiếu")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi xử lý thêm sản phẩm vào giỏ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Lỗi khi thêm sản phẩm vào giỏ hàng"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi từ server")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_bienthe' => 'required|exists:bienthe,id',
            'soluong' => 'required|integer|min:1',
        ]);

        $user = $request->get('auth_user');
        $userId = $user->id;

        DB::beginTransaction();
        try {
            // 🔹 Lấy biến thể sản phẩm
            $bienthe = DB::table('bienthe')
                ->where('id', $validated['id_bienthe'])
                ->lockForUpdate()
                ->first();

            if (!$bienthe) {
                throw new \Exception('Biến thể không tồn tại');
            }

            $price_unit = $bienthe->giagoc;
            $soluong = $validated['soluong'];
            $id_bienthe = $validated['id_bienthe'];

            // 🔹 Tìm ưu đãi áp dụng (nếu có)
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                ->where('bt.luottang', '>', 0)
                ->where('qs.dieukien', '<=', $soluong)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select('qs.dieukien as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                ->first();

            $thanhtien = 0;

            // 🔹 Nếu có ưu đãi
            if ($promotion) {
                $promotion_count = floor($soluong / $promotion->discount_multiplier);
                $num_free = min($promotion_count, $promotion->current_luottang);
                $num_to_pay = $soluong - $num_free;

                $thanhtien = $num_to_pay * $promotion->giagoc;

                // 🔹 Cập nhật lại lượt tặng
                DB::table('bienthe')
                    ->where('id', $id_bienthe)
                    ->update([
                        'luottang' => DB::raw("GREATEST(luottang - {$num_free}, 0)")
                    ]);

                // 🔹 Nếu có sản phẩm tặng, thêm trực tiếp vào giỏ hàng (thanhtien = 0)
                if ($num_free > 0) {
                    $giftItem = GiohangModel::where('id_nguoidung', $userId)
                        ->where('id_bienthe', $id_bienthe)
                        ->where('thanhtien', 0)
                        ->lockForUpdate()
                        ->first();

                    if ($giftItem) {
                        $giftItem->soluong += $num_free;
                        $giftItem->save();
                    } else {
                        GiohangModel::create([
                            'id_nguoidung' => $userId,
                            'id_bienthe' => $id_bienthe,
                            'soluong' => $num_free,
                            'thanhtien' => 0,
                            'trangthai' => 'Hiển thị',
                        ]);
                    }
                }
            } else {
                // 🔹 Không có ưu đãi
                $thanhtien = $soluong * $price_unit;
            }

            // 🔹 Thêm hoặc cập nhật sản phẩm chính trong giỏ hàng
            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $id_bienthe)
                ->where('thanhtien', '>', 0)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->soluong += $soluong;
                $item->thanhtien += $thanhtien;
                $item->save();
            } else {
                $item = GiohangModel::create([
                    'id_nguoidung' => $userId,
                    'id_bienthe' => $id_bienthe,
                    'soluong' => $soluong,
                    'thanhtien' => $thanhtien,
                    'trangthai' => 'Hiển thị',
                ]);
            }

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Thêm sản phẩm vào giỏ hàng thành công',
                'data' => $item->load('bienthe.sanpham'),
            ], Response::HTTP_CREATED);

        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi khi thêm sản phẩm vào giỏ hàng',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/toi/giohang/{id}",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="Cập nhật số lượng sản phẩm trong giỏ hàng (tự động áp dụng khuyến mãi/quà tặng nếu có)",
     *     description="
     *     - Cập nhật số lượng của sản phẩm trong giỏ hàng hiện tại.
     *     - Nếu số lượng mới bằng **0** → sản phẩm sẽ bị xóa khỏi giỏ hàng.
     *     - Nếu tồn tại chương trình **quà tặng/sự kiện** thỏa điều kiện (`dieukien <= soluong` và trong thời gian hiệu lực):
     *         - Tự động tính toán số lượng sản phẩm được tặng miễn phí.
     *         - Tự động trừ lượt tặng (`luottang`) trong bảng `bienthe`.
     *         - Cập nhật hoặc thêm dòng sản phẩm quà tặng (`thanhtien = 0`) vào giỏ hàng.
     *     - Nếu không còn ưu đãi → tính tiền bình thường và xóa hàng quà tặng (nếu có).
     *     ",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID bản ghi trong giỏ hàng cần cập nhật (không phải ID biến thể)",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"soluong"},
     *             @OA\Property(
     *                 property="soluong",
     *                 type="integer",
     *                 example=5,
     *                 description="Số lượng mới của sản phẩm. Nếu = 0 sẽ xóa sản phẩm khỏi giỏ hàng."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật số lượng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cập nhật số lượng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Chi tiết sản phẩm sau khi cập nhật",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="id_nguoidung", type="integer", example=2),
     *                 @OA\Property(property="id_bienthe", type="integer", example=21),
     *                 @OA\Property(property="soluong", type="integer", example=5),
     *                 @OA\Property(property="thanhtien", type="integer", example=400000)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm trong giỏ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm trong giỏ hàng")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi cập nhật giỏ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Lỗi khi cập nhật giỏ hàng"),
     *             @OA\Property(property="error", type="string", example="Biến thể không tồn tại")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soluong' => 'required|integer|min:0',
        ]);

        $user = $request->get('auth_user');
        $userId = $user->id;

        DB::beginTransaction();
        try {
            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            // Nếu số lượng mới = 0 => xóa luôn
            if ($validated['soluong'] == 0) {
                $item->delete();

                // Nếu giỏ hàng trống hoàn toàn
                $remaining = GiohangModel::where('id_nguoidung', $userId)->count();
                DB::commit();

                return $this->jsonResponse([
                    'status' => true,
                    'message' => $remaining === 0
                        ? 'Giỏ hàng hiện đang trống'
                        : 'Đã xóa sản phẩm khỏi giỏ hàng',
                ], Response::HTTP_OK);
            }

            $id_bienthe = $item->id_bienthe;
            $soluong = $validated['soluong'];

            // 🔹 Lấy giá gốc sản phẩm
            $bienthe = DB::table('bienthe')->where('id', $id_bienthe)->lockForUpdate()->first();
            if (!$bienthe) {
                throw new \Exception('Biến thể không tồn tại');
            }

            $price_unit = $bienthe->giagoc;

            // 🔹 Tìm ưu đãi còn hiệu lực
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                ->where('bt.luottang', '>', 0)
                ->where('qs.dieukien', '<=', $soluong)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select('qs.dieukien as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                ->first();

            $thanhtien = 0;

            // 🔹 Nếu có ưu đãi
            if ($promotion) {
                $promotion_count = floor($soluong / $promotion->discount_multiplier);
                $num_free = min($promotion_count, $promotion->current_luottang);
                $num_to_pay = $soluong - $num_free;

                $thanhtien = $num_to_pay * $promotion->giagoc;

                // 🔹 Cập nhật lượt tặng còn lại
                DB::table('bienthe')
                    ->where('id', $id_bienthe)
                    ->update([
                        'luottang' => DB::raw("GREATEST(luottang - {$num_free}, 0)")
                    ]);

                // 🔹 Cập nhật hoặc thêm sản phẩm tặng (thanhtien = 0)
                $giftItem = GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->where('thanhtien', 0)
                    ->lockForUpdate()
                    ->first();

                if ($num_free > 0) {
                    if ($giftItem) {
                        $giftItem->update(['soluong' => $num_free]);
                    } else {
                        GiohangModel::create([
                            'id_nguoidung' => $userId,
                            'id_bienthe' => $id_bienthe,
                            'soluong' => $num_free,
                            'thanhtien' => 0,
                            'trangthai' => 'Hiển thị',
                        ]);
                    }
                } elseif ($giftItem) {
                    // Nếu không còn ưu đãi => xóa hàng quà tặng cũ
                    $giftItem->delete();
                }
            } else {
                // 🔹 Không có ưu đãi
                $thanhtien = $soluong * $price_unit;

                // Nếu trước đó có hàng tặng, xóa luôn
                GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->where('thanhtien', 0)
                    ->delete();
            }

            // 🔹 Cập nhật sản phẩm chính
            $item->update([
                'soluong' => $soluong,
                'thanhtien' => $thanhtien,
            ]);

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Cập nhật số lượng thành công',
                'data' => $item->load('bienthe.sanpham'),
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi khi cập nhật giỏ hàng',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/toi/giohang/{id}",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="ID bản ghi giỏ hàng cần xóa",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id_bienthesp",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm cần xóa",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa sản phẩm khỏi giỏ hàng thành công"
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy sản phẩm trong giỏ hàng")
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->get('auth_user');
        $userId = $user->id;

        $item = GiohangModel::where('id_nguoidung', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công',
            'data' => [],
        ], Response::HTTP_OK);
    }
}

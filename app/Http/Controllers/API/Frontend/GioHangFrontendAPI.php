<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toi\GioHangResource;
use App\Models\BientheModel;
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
     *     summary="Thêm sản phẩm vào giỏ hàng (tự động áp dụng khuyến mãi/quà tặng nếu có)",
     *     description="
     *      - Thêm sản phẩm vào giỏ hàng của người dùng hiện tại.
     *      - Tự động kiểm tra và áp dụng chương trình quà tặng/sự kiện nếu thỏa điều kiện (`dieukien <= tổng số lượng sản phẩm`) và trong thời gian hiệu lực.
     *      - Tự động thêm hoặc xóa quà tặng miễn phí tương ứng (`thanhtien = 0`).
     *      - Không còn quản lý số lượng lượt tặng (`luottang`) trong bảng biến thể.
     *     ",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Thông tin sản phẩm cần thêm vào giỏ hàng",
     *         @OA\JsonContent(
     *             required={"id_bienthe","soluong"},
     *             @OA\Property(property="id_bienthe", type="integer", example=21, description="ID biến thể sản phẩm"),
     *             @OA\Property(property="soluong", type="integer", example=2, description="Số lượng sản phẩm muốn thêm (phải >= 1)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm sản phẩm vào giỏ hàng thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thêm sản phẩm vào giỏ hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Danh sách sản phẩm trong giỏ hàng sau khi thêm, bao gồm cả quà tặng miễn phí",
     *                 @OA\Items(ref="#/components/schemas/GioHangResource")
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
        $id_bienthe = $validated['id_bienthe'];
        $soluongNew = $validated['soluong'];

        DB::beginTransaction();
        try {
            // Khóa biến thể để tránh race condition
            $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
            $priceUnit = $variant->giagoc;

            // Lấy sản phẩm chính hiện tại trong giỏ (nếu có)
            $existingItem = GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $id_bienthe)
                ->where('thanhtien', '>', 0)
                ->lockForUpdate()
                ->first();

            $totalQuantity = $soluongNew + ($existingItem ? $existingItem->soluong : 0);

            // Kiểm tra khuyến mãi
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                // ->where('bt.luottang', '>', 0)
                ->where('qs.dieukien', '<=', $totalQuantity)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select('qs.dieukien as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                ->first();

            $numFree = 0;
            $thanhtien = $totalQuantity * $priceUnit;

            if ($promotion) {
                $promotionCount = floor($totalQuantity / $promotion->discount_multiplier);
                $numFree = min($promotionCount, $promotion->current_luottang);
                $numToPay = $totalQuantity - $numFree;
                $thanhtien = $numToPay * $promotion->giagoc;

                // Lấy quà tặng hiện có (nếu có)
                $existingFreeItem = GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->where('thanhtien', 0)
                    ->lockForUpdate()
                    ->first();

                // $currentFreeQty = $existingFreeItem ? $existingFreeItem->soluong : 0;
                // $deltaFree = $numFree - $currentFreeQty;

                // Chỉ trừ hoặc cộng lại phần chênh lệch quà tặng, bỏ vì thay đổi logic luottang là Tăng theo table Donhang chứ ko phải là quản lý số quà tặng
                // Bỏ vì luottang sẽ liên quan đế thông kế chưa ko còn là quản lý số quà tặng
                // if ($deltaFree > 0) {
                //     DB::table('bienthe')
                //         ->where('id', $id_bienthe)
                //         ->update(['luottang' => DB::raw("GREATEST(luottang - {$deltaFree}, 0)")]);
                // } elseif ($deltaFree < 0) {
                //     $restore = abs($deltaFree);
                //     DB::table('bienthe')
                //         ->where('id', $id_bienthe)
                //         ->update(['luottang' => DB::raw("luottang + {$restore}")]);
                // }

                // Cập nhật hoặc tạo dòng quà tặng
                if ($numFree > 0) {
                    if ($existingFreeItem) {
                        $existingFreeItem->update(['soluong' => $numFree, 'trangthai' => 'Hiển thị']);
                    } else {
                        GiohangModel::create([
                            'id_nguoidung' => $userId,
                            'id_bienthe' => $id_bienthe,
                            'soluong' => $numFree,
                            'thanhtien' => 0,
                            'trangthai' => 'Hiển thị',
                        ]);
                    }
                } else {
                    // Nếu không còn quà tặng thì xóa dòng quà
                    GiohangModel::where('id_nguoidung', $userId)
                        ->where('id_bienthe', $id_bienthe)
                        ->where('thanhtien', 0)
                        ->delete();
                }
            }

            // Cập nhật hoặc thêm sản phẩm chính
            if ($existingItem) {
                $existingItem->update([
                    'soluong' => $totalQuantity,
                    'thanhtien' => $thanhtien,
                    'trangthai' => 'Hiển thị',
                ]);
                $item = $existingItem;
            } else {
                $item = GiohangModel::create([
                    'id_nguoidung' => $userId,
                    'id_bienthe' => $id_bienthe,
                    'soluong' => $totalQuantity,
                    'thanhtien' => $thanhtien,
                    'trangthai' => 'Hiển thị',
                ]);
            }

            DB::commit();

            GioHangResource::withoutWrapping();
            $cartItems = GiohangModel::with(['bienthe.sanpham.thuonghieu', 'bienthe.loaibienthe', 'bienthe.sanpham.hinhanhsanpham'])
                ->where('id_nguoidung', $userId)
                ->where('trangthai', 'Hiển thị')
                ->get();
            return response()->json(GioHangResource::collection($cartItems), Response::HTTP_CREATED);

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
     *     - Cập nhật số lượng sản phẩm trong giỏ hàng.
     *     - Nếu số lượng mới bằng 0, sản phẩm sẽ bị xóa khỏi giỏ hàng cùng quà tặng liên quan.
     *     - Áp dụng tự động chương trình quà tặng/sự kiện nếu thỏa điều kiện (`dieukien <= soluong`) và trong thời gian hiệu lực.
     *     - Tự động thêm hoặc xóa quà tặng miễn phí tương ứng với số lượng sản phẩm.
     *     - Không còn quản lý số lượng lượt tặng (`luottang`) trong bảng biến thể.
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
     *                 description="Số lượng mới của sản phẩm. Nếu = 0 sẽ xóa sản phẩm khỏi giỏ hàng cùng quà tặng liên quan."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật số lượng thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cập nhật số lượng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Danh sách sản phẩm trong giỏ hàng sau khi cập nhật, bao gồm cả quà tặng miễn phí",
     *                 @OA\Items(ref="#/components/schemas/GioHangResource")
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
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi từ server")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soluong' => 'required|integer|min:0'
        ]);

        $user = $request->get('auth_user');
        $userId = $user->id;

        DB::beginTransaction();
        try {
            // ✅ Khóa dòng giỏ hàng cần cập nhật để tránh xung đột
            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $id_bienthe = $item->id_bienthe;
            $soluongNew = $validated['soluong'];

            // ✅ Nếu giảm về 0 → xóa sản phẩm và quà tặng liên quan
            if ($soluongNew == 0) {
                // Lấy quà tặng hiện tại để hoàn lại luottang nếu có
                $freeItem = GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->where('thanhtien', 0)
                    ->first();

                // if ($freeItem) {
                //     $restoreQty = $freeItem->soluong;
                //     DB::table('bienthe')->where('id', $id_bienthe)
                //         ->update(['luottang' => DB::raw("luottang + {$restoreQty}")]);
                // }

                GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->delete();

                DB::commit();
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Đã xóa sản phẩm và quà tặng khỏi giỏ hàng',
                ]);
            }

            // ✅ Lấy biến thể sản phẩm và khóa để cập nhật an toàn
            $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
            $priceUnit = $variant->giagoc;

            // ✅ Kiểm tra khuyến mãi/quà tặng áp dụng
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                // ->where('bt.luottang', '>', 0)
                ->where('qs.dieukien', '<=', $soluongNew)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select(
                    'qs.id',
                    'qs.dieukien as discount_multiplier',
                    'bt.luottang as current_luottang',
                    'bt.giagoc'
                )
                ->first();

            // ✅ Tính toán số lượng & thành tiền
            $numFreeNew = 0;
            $thanhtien = $soluongNew * $priceUnit;

            if ($promotion) {
                $promotionCount = floor($soluongNew / $promotion->discount_multiplier);
                $numFreeNew = min($promotionCount, $promotion->current_luottang);
                $numToPay = $soluongNew - $numFreeNew;
                $thanhtien = $numToPay * $promotion->giagoc;
            }

            // ✅ Lấy số quà tặng cũ (nếu có)
            $freeItem = GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $id_bienthe)
                ->where('thanhtien', 0)
                ->lockForUpdate()
                ->first();

            // $numFreeOld = $freeItem ? $freeItem->soluong : 0;
            // $delta = $numFreeNew - $numFreeOld;

            // ✅ Cập nhật lại luottang theo chênh lệch
            // Bỏ vì luottang sẽ liên quan đế thông kế chưa ko còn là quản lý số quà tặng
            // if ($delta > 0) {
            //     // Giảm thêm
            //     DB::table('bienthe')
            //         ->where('id', $id_bienthe)
            //         ->update(['luottang' => DB::raw("GREATEST(luottang - {$delta}, 0)")]);
            // } elseif ($delta < 0) {
            //     // Hoàn lại phần giảm
            //     $restore = abs($delta);
            //     DB::table('bienthe')
            //         ->where('id', $id_bienthe)
            //         ->update(['luottang' => DB::raw("luottang + {$restore}")]);
            // }

            // ✅ Cập nhật sản phẩm chính
            $item->update([
                'soluong' => $soluongNew,
                'thanhtien' => $thanhtien,
                'trangthai' => 'Hiển thị',
            ]);

            // ✅ Cập nhật hoặc xóa/tạo quà tặng
            if ($numFreeNew > 0) {
                if ($freeItem) {
                    $freeItem->update([
                        'soluong' => $numFreeNew,
                        'trangthai' => 'Hiển thị'
                    ]);
                } else {
                    GiohangModel::create([
                        'id_nguoidung' => $userId,
                        'id_bienthe' => $id_bienthe,
                        'soluong' => $numFreeNew,
                        'thanhtien' => 0,
                        'trangthai' => 'Hiển thị',
                    ]);
                }
            } else {
                if ($freeItem) {
                    $freeItem->delete();
                }
            }

            DB::commit();

            GioHangResource::withoutWrapping(); // Bỏ "data" bọc ngoài
            $cartItems = GiohangModel::with(['bienthe.sanpham.thuonghieu', 'bienthe.loaibienthe', 'bienthe.sanpham.hinhanhsanpham'])
                ->where('id_nguoidung', $userId)
                ->where('trangthai', 'Hiển thị')
                ->get();
            return response()->json(GioHangResource::collection($cartItems), Response::HTTP_OK);

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
     *     summary="Xóa sản phẩm khỏi giỏ hàng",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID bản ghi giỏ hàng cần xóa",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa sản phẩm khỏi giỏ hàng thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Xóa sản phẩm khỏi giỏ hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items()
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm trong giỏ hàng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm trong giỏ hàng")
     *         )
     *     )
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

<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toi\GioHangResource;
use App\Models\BientheModel;
use Illuminate\Http\Request;
use App\Models\GiohangModel;
use App\Models\QuatangsukienModel;
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
     *      Chức năng:
     *      - Thêm sản phẩm vào giỏ hàng của người dùng hiện tại.
     *      - Nếu có chương trình khuyến mãi/quà tặng (bảng quatang_sukien):
     *          + Tự động kiểm tra điều kiện số lượng (dieukiensoluong).
     *          + Tự động kiểm tra điều kiện giá trị (dieukiengiatri) tính theo tổng số tiền trong giỏ có đủ điều kiện của chiến lược này không(soluong và giatri).
     *          + Áp dụng đúng mô hình 'Mua X tặng 1'.
     *          + Thêm dòng quà tặng vào giỏ (thanhtien = 0) hoặc tự động cập nhật/xóa nếu không còn thỏa điều kiện.
     *      - Tính toán lại thành tiền theo số lượng phải trả.
     *      - Trả về toàn bộ giỏ hàng sau khi cập nhật.
     *
     *      Lưu ý:
     *      - Không còn sử dụng trường luottang trong bảng biến thể để quản lý số lượng quà.
     *      - Toàn bộ xử lý được khóa transaction + lockForUpdate() để chống race condition.
     *     ",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dữ liệu để thêm hoặc cập nhật sản phẩm trong giỏ hàng",
     *         @OA\JsonContent(
     *             required={"id_bienthe","soluong"},
     *             @OA\Property(
     *                 property="id_bienthe",
     *                 type="integer",
     *                 example=21,
     *                 description="ID biến thể sản phẩm cần thêm"
     *             ),
     *             @OA\Property(
     *                 property="soluong",
     *                 type="integer",
     *                 example=2,
     *                 description="Số lượng muốn thêm vào giỏ (phải >= 1)"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Thêm vào giỏ hàng thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             description="Danh sách giỏ hàng sau khi thêm, gồm cả sản phẩm và quà tặng",
     *             @OA\Items(ref="#/components/schemas/GioHangResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Dữ liệu không hợp lệ hoặc thiếu")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi xử lý thêm sản phẩm vào giỏ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Lỗi khi thêm sản phẩm vào giỏ hàng"),
     *             @OA\Property(property="error", type="string", example="Thông báo lỗi chi tiết từ server")
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
            // =======================================
            // 1. XỬ LÝ THÊM SẢN PHẨM VÀ KHUYẾN MÃI BUY X GET Y
            // =======================================

            $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
            $priceUnit = $variant->giagoc;

            $existingItem = GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $id_bienthe)
                ->where('thanhtien', '>', 0)
                ->lockForUpdate()
                ->first();

            $totalQuantity = $soluongNew + ($existingItem ? $existingItem->soluong : 0);

            // Kiểm tra khuyến mãi theo số lượng
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                ->where('qs.dieukiensoluong', '<=', $totalQuantity)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select('qs.dieukiensoluong as discount_multiplier', 'bt.giagoc')
                ->first();

            $numFree = 0;
            $thanhtien = $totalQuantity * $priceUnit;

            if ($promotion) {
                $promotionCount = floor($totalQuantity / $promotion->discount_multiplier);
                $numFree = $promotionCount;

                $numToPay = $totalQuantity - $numFree;
                $thanhtien = $numToPay * $promotion->giagoc;

                // Lấy dòng quà tặng hiện có
                $existingFreeItem = GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->where('thanhtien', 0)
                    ->lockForUpdate()
                    ->first();

                if ($numFree > 0) {
                    if ($existingFreeItem) {
                        $existingFreeItem->update([
                            'soluong' => $numFree,
                            'trangthai' => 'Hiển thị'
                        ]);
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
                    // Không còn "quà tặng theo số lượng" → xóa dòng free
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

            // ============================================================
            // 2. CHECK QUÀ TẶNG DỰA TRÊN TỔNG GIÁ TRỊ GIỎ HÀNG (RULE 2)
            // ============================================================

            DB::beginTransaction();

            // Lấy giỏ hàng chính
            $cartItems = GiohangModel::with('bienthe.sanpham.thuonghieu')
                ->where('id_nguoidung', $userId)
                ->where('thanhtien', '>', 0)
                ->where('trangthai', 'Hiển thị')
                ->lockForUpdate()
                ->get();

            $cartTotalValue = $cartItems->sum('thanhtien');

            // Lấy rule quà tặng active
            $activeGifts = QuatangsukienModel::where('trangthai', 'Hiển thị')
                ->whereNull('deleted_at')
                ->whereRaw('NOW() BETWEEN ngaybatdau AND ngayketthuc')
                ->get();

            foreach ($activeGifts as $rule) {
                $giftBientheId = $rule->id_bienthe;
                $requiredValue = $rule->dieukiengiatri;
                $requiredQty   = $rule->dieukiensoluong;

                // Check tổng giá trị
                if ($cartTotalValue < $requiredValue) {
                    GiohangModel::where('id_nguoidung', $userId)
                        ->where('id_bienthe', $giftBientheId)
                        ->where('thanhtien', 0)
                        ->delete();
                    continue;
                }

                // Lấy thương hiệu của quà
                $giftVariant = BientheModel::with('sanpham.thuonghieu')->find($giftBientheId);
                if (!$giftVariant) continue;

                $brandId = $giftVariant->sanpham->id_thuonghieu ?? null;
                if (!$brandId) continue;

                // Đếm số biến thể khác nhau trong giỏ thuộc thương hiệu này
                $distinctVariantCount = $cartItems
                    ->filter(fn($item) => $item->bienthe->sanpham->id_thuonghieu == $brandId)
                    ->unique('id_bienthe')
                    ->count();

                if ($distinctVariantCount < $requiredQty) {
                    GiohangModel::where('id_nguoidung', $userId)
                        ->where('id_bienthe', $giftBientheId)
                        ->where('thanhtien', 0)
                        ->delete();
                    continue;
                }

                // ĐỦ ĐIỀU KIỆN → cấp quà
                $existingGift = GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $giftBientheId)
                    ->where('thanhtien', 0)
                    ->lockForUpdate()
                    ->first();

                if ($existingGift) {
                    $existingGift->update([
                        'soluong' => 1,
                        'trangthai' => 'Hiển thị',
                    ]);
                } else {
                    GiohangModel::create([
                        'id_nguoidung' => $userId,
                        'id_bienthe'   => $giftBientheId,
                        'soluong'      => 1,
                        'thanhtien'    => 0,
                        'trangthai'    => 'Hiển thị',
                    ]);
                }
            }

            DB::commit();

            // ============================================================
            // TRẢ KẾT QUẢ CUỐI
            // ============================================================

            GioHangResource::withoutWrapping();
            $cartItems = GiohangModel::with(['bienthe.sanpham.thuonghieu','bienthe.loaibienthe','bienthe.sanpham.hinhanhsanpham'])
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
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'id_bienthe' => 'required|exists:bienthe,id',
    //         'soluong' => 'required|integer|min:1',
    //     ]);

    //     $user = $request->get('auth_user');
    //     $userId = $user->id;
    //     $id_bienthe = $validated['id_bienthe'];
    //     $soluongNew = $validated['soluong'];

    //     DB::beginTransaction();
    //     try {
    //         // Khóa biến thể để tránh race condition
    //         $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
    //         $priceUnit = $variant->giagoc;

    //         // Lấy sản phẩm chính hiện tại trong giỏ (nếu có)
    //         $existingItem = GiohangModel::where('id_nguoidung', $userId)
    //             ->where('id_bienthe', $id_bienthe)
    //             ->where('thanhtien', '>', 0)
    //             ->lockForUpdate()
    //             ->first();

    //         $totalQuantity = $soluongNew + ($existingItem ? $existingItem->soluong : 0);

    //         // Kiểm tra khuyến mãi
    //         $promotion = DB::table('quatang_sukien as qs')
    //             ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
    //             ->where('qs.id_bienthe', $id_bienthe)
    //             // ->where('bt.luottang', '>', 0)
    //             ->where('qs.dieukiensoluong', '<=', $totalQuantity)
    //             ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
    //             ->select('qs.dieukiensoluong as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
    //             ->first();

    //         $numFree = 0;
    //         $thanhtien = $totalQuantity * $priceUnit;

    //         if ($promotion) {
    //             $promotionCount = floor($totalQuantity / $promotion->discount_multiplier);
    //             // $numFree = min($promotionCount, $promotion->current_luottang);
    //             $numFree = $promotionCount;
    //             $numToPay = $totalQuantity - $numFree;
    //             $thanhtien = $numToPay * $promotion->giagoc;

    //             // Lấy quà tặng hiện có (nếu có)
    //             $existingFreeItem = GiohangModel::where('id_nguoidung', $userId)
    //                 ->where('id_bienthe', $id_bienthe)
    //                 ->where('thanhtien', 0)
    //                 ->lockForUpdate()
    //                 ->first();

    //             // $currentFreeQty = $existingFreeItem ? $existingFreeItem->soluong : 0;
    //             // $deltaFree = $numFree - $currentFreeQty;

    //             // // Chỉ trừ hoặc cộng lại phần chênh lệch quà tặng, bỏ vì thay đổi logic luottang là Tăng theo table Donhang chứ ko phải là quản lý số quà tặng
    //             // // Bỏ vì luottang sẽ liên quan đế thông kế chưa ko còn là quản lý số quà tặng
    //             // if ($deltaFree > 0) {
    //             //     DB::table('bienthe')
    //             //         ->where('id', $id_bienthe)
    //             //         ->update(['luottang' => DB::raw("GREATEST(luottang - {$deltaFree}, 0)")]);
    //             // } elseif ($deltaFree < 0) {
    //             //     $restore = abs($deltaFree);
    //             //     DB::table('bienthe')
    //             //         ->where('id', $id_bienthe)
    //             //         ->update(['luottang' => DB::raw("luottang + {$restore}")]);
    //             // }

    //             // Cập nhật hoặc tạo dòng quà tặng
    //             if ($numFree > 0) {
    //                 if ($existingFreeItem) {
    //                     $existingFreeItem->update(['soluong' => $numFree, 'trangthai' => 'Hiển thị']);
    //                 } else {
    //                     GiohangModel::create([
    //                         'id_nguoidung' => $userId,
    //                         'id_bienthe' => $id_bienthe,
    //                         'soluong' => $numFree,
    //                         'thanhtien' => 0,
    //                         'trangthai' => 'Hiển thị',
    //                     ]);
    //                 }
    //             } else {
    //                 // Nếu không còn quà tặng thì xóa dòng quà
    //                 GiohangModel::where('id_nguoidung', $userId)
    //                     ->where('id_bienthe', $id_bienthe)
    //                     ->where('thanhtien', 0)
    //                     ->delete();
    //             }
    //         }

    //         // Cập nhật hoặc thêm sản phẩm chính
    //         if ($existingItem) {
    //             $existingItem->update([
    //                 'soluong' => $totalQuantity,
    //                 'thanhtien' => $thanhtien,
    //                 'trangthai' => 'Hiển thị',
    //             ]);
    //             $item = $existingItem;
    //         } else {
    //             $item = GiohangModel::create([
    //                 'id_nguoidung' => $userId,
    //                 'id_bienthe' => $id_bienthe,
    //                 'soluong' => $totalQuantity,
    //                 'thanhtien' => $thanhtien,
    //                 'trangthai' => 'Hiển thị',
    //             ]);
    //         }

    //         DB::commit();

    //         GioHangResource::withoutWrapping();
    //         $cartItems = GiohangModel::with(['bienthe.sanpham.thuonghieu', 'bienthe.loaibienthe', 'bienthe.sanpham.hinhanhsanpham'])
    //             ->where('id_nguoidung', $userId)
    //             ->where('trangthai', 'Hiển thị')
    //             ->get();
    //         return response()->json(GioHangResource::collection($cartItems), Response::HTTP_CREATED);

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return $this->jsonResponse([
    //             'status' => false,
    //             'message' => 'Lỗi khi thêm sản phẩm vào giỏ hàng',
    //             'error' => $e->getMessage(),
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    /**
     * @OA\Put(
     *     path="/api/toi/giohang/{id}",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="Cập nhật số lượng sản phẩm trong giỏ hàng (tự động áp dụng khuyến mãi/quà tặng nếu có)",
     *     description="
     *     - Cập nhật số lượng sản phẩm trong giỏ hàng.
     *     - Nếu số lượng mới bằng 0, sản phẩm sẽ bị xóa khỏi giỏ hàng cùng các quà tặng liên quan.
     *     - Tự động áp dụng chương trình quà tặng/sự kiện nếu thỏa điều kiện (số lượng sản phẩm >= điều kiện số lượng) và trong thời gian hiệu lực.
     *     -Tự động kiểm tra điều kiện giá trị (dieukiengiatri) tính theo tổng số tiền trong giỏ có đủ điều kiện của chiến lược này không(soluong và giatri).
     *     - Tự động thêm hoặc xóa quà tặng miễn phí tương ứng với số lượng sản phẩm trong giỏ.
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
     *                 description="Số lượng mới của sản phẩm. Nếu bằng 0 sẽ xóa sản phẩm và các quà tặng liên quan khỏi giỏ hàng."
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
     *                 description="Danh sách sản phẩm trong giỏ hàng sau khi cập nhật, bao gồm cả quà tặng miễn phí nếu có",
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
     *         response=400,
     *         description="Yêu cầu không hợp lệ hoặc số lượng vượt quá điều kiện cho phép",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Số lượng yêu cầu vượt quá số lượng tồn kho hiện có.")
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
            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $id_bienthe = $item->id_bienthe;
            $soluongNew = $validated['soluong'];

            // Lấy biến thể để kiểm tra điều kiện
            $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
            $priceUnit = $variant->giagoc;

            // === Logic kiểm tra điều kiện giá trị ===
            // Ví dụ: kiểm tra số lượng yêu cầu không vượt quá số lượng tồn kho (ví dụ biến thể có trường 'stock')
            if (isset($variant->stock) && $soluongNew > $variant->stock) {
                DB::rollBack();
                return $this->jsonResponse([
                    'status' => false,
                    'message' => "Số lượng yêu cầu vượt quá số lượng tồn kho hiện có ({$variant->stock}).",
                ], Response::HTTP_BAD_REQUEST);
            }

            // Hoặc thêm các điều kiện khác nếu cần, ví dụ:
            // if (!$variant->is_active) {
            //     DB::rollBack();
            //     return $this->jsonResponse([
            //         'status' => false,
            //         'message' => 'Sản phẩm đã ngừng kinh doanh, không thể cập nhật số lượng.',
            //     ], Response::HTTP_BAD_REQUEST);
            // }

            // Nếu số lượng là 0 thì xóa sản phẩm và quà tặng
            if ($soluongNew == 0) {
                GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->delete();

                DB::commit();
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Đã xóa sản phẩm và quà tặng khỏi giỏ hàng',
                ]);
            }

            // Kiểm tra khuyến mãi/quà tặng
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                ->where('qs.dieukiensoluong', '<=', $soluongNew)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select(
                    'qs.id',
                    'qs.dieukiensoluong as discount_multiplier',
                    'bt.luottang as current_luottang',
                    'bt.giagoc'
                )
                ->first();

            $numFreeNew = 0;
            $thanhtien = $soluongNew * $priceUnit;

            if ($promotion) {
                $promotionCount = floor($soluongNew / $promotion->discount_multiplier);
                $numFreeNew = $promotionCount;
                $numToPay = $soluongNew - $numFreeNew;
                $thanhtien = $numToPay * $promotion->giagoc;
            }

            // Lấy quà tặng cũ nếu có
            $freeItem = GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $id_bienthe)
                ->where('thanhtien', 0)
                ->lockForUpdate()
                ->first();

            // Cập nhật sản phẩm chính
            $item->update([
                'soluong' => $soluongNew,
                'thanhtien' => $thanhtien,
                'trangthai' => 'Hiển thị',
            ]);

            // Cập nhật hoặc xóa/tạo quà tặng
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

            GioHangResource::withoutWrapping();
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
    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'soluong' => 'required|integer|min:0'
    //     ]);

    //     $user = $request->get('auth_user');
    //     $userId = $user->id;

    //     DB::beginTransaction();
    //     try {
    //         // ✅ Khóa dòng giỏ hàng cần cập nhật để tránh xung đột
    //         $item = GiohangModel::where('id_nguoidung', $userId)
    //             ->where('id', $id)
    //             ->lockForUpdate()
    //             ->firstOrFail();

    //         $id_bienthe = $item->id_bienthe;
    //         $soluongNew = $validated['soluong'];

    //         // ✅ Nếu giảm về 0 → xóa sản phẩm và quà tặng liên quan
    //         if ($soluongNew == 0) {
    //             // Lấy quà tặng hiện tại để hoàn lại luottang nếu có
    //             $freeItem = GiohangModel::where('id_nguoidung', $userId)
    //                 ->where('id_bienthe', $id_bienthe)
    //                 ->where('thanhtien', 0)
    //                 ->first();

    //             // if ($freeItem) {
    //             //     $restoreQty = $freeItem->soluong;
    //             //     DB::table('bienthe')->where('id', $id_bienthe)
    //             //         ->update(['luottang' => DB::raw("luottang + {$restoreQty}")]);
    //             // }

    //             GiohangModel::where('id_nguoidung', $userId)
    //                 ->where('id_bienthe', $id_bienthe)
    //                 ->delete();

    //             DB::commit();
    //             return $this->jsonResponse([
    //                 'status' => true,
    //                 'message' => 'Đã xóa sản phẩm và quà tặng khỏi giỏ hàng',
    //             ]);
    //         }

    //         // ✅ Lấy biến thể sản phẩm và khóa để cập nhật an toàn
    //         $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
    //         $priceUnit = $variant->giagoc;

    //         // ✅ Kiểm tra khuyến mãi/quà tặng áp dụng
    //         $promotion = DB::table('quatang_sukien as qs')
    //             ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
    //             ->where('qs.id_bienthe', $id_bienthe)
    //             // ->where('bt.luottang', '>', 0)
    //             ->where('qs.dieukiensoluong', '<=', $soluongNew)
    //             ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
    //             ->select(
    //                 'qs.id',
    //                 'qs.dieukiensoluong as discount_multiplier',
    //                 'bt.luottang as current_luottang',
    //                 'bt.giagoc'
    //             )
    //             ->first();

    //         // ✅ Tính toán số lượng & thành tiền
    //         $numFreeNew = 0;
    //         $thanhtien = $soluongNew * $priceUnit;

    //         if ($promotion) {
    //             $promotionCount = floor($soluongNew / $promotion->discount_multiplier);
    //             // $numFreeNew = min($promotionCount, $promotion->current_luottang);
    //             $numFreeNew = $promotionCount;
    //             $numToPay = $soluongNew - $numFreeNew;
    //             $thanhtien = $numToPay * $promotion->giagoc;
    //         }

    //         // ✅ Lấy số quà tặng cũ (nếu có)
    //         $freeItem = GiohangModel::where('id_nguoidung', $userId)
    //             ->where('id_bienthe', $id_bienthe)
    //             ->where('thanhtien', 0)
    //             ->lockForUpdate()
    //             ->first();

    //         // $numFreeOld = $freeItem ? $freeItem->soluong : 0;
    //         // $delta = $numFreeNew - $numFreeOld;

    //         // ✅ Cập nhật lại luottang theo chênh lệch
    //         // Bỏ vì luottang sẽ liên quan đế thông kế chưa ko còn là quản lý số quà tặng
    //         // if ($delta > 0) {
    //         //     // Giảm thêm
    //         //     DB::table('bienthe')
    //         //         ->where('id', $id_bienthe)
    //         //         ->update(['luottang' => DB::raw("GREATEST(luottang - {$delta}, 0)")]);
    //         // } elseif ($delta < 0) {
    //         //     // Hoàn lại phần giảm
    //         //     $restore = abs($delta);
    //         //     DB::table('bienthe')
    //         //         ->where('id', $id_bienthe)
    //         //         ->update(['luottang' => DB::raw("luottang + {$restore}")]);
    //         // }

    //         // ✅ Cập nhật sản phẩm chính
    //         $item->update([
    //             'soluong' => $soluongNew,
    //             'thanhtien' => $thanhtien,
    //             'trangthai' => 'Hiển thị',
    //         ]);

    //         // ✅ Cập nhật hoặc xóa/tạo quà tặng
    //         if ($numFreeNew > 0) {
    //             if ($freeItem) {
    //                 $freeItem->update([
    //                     'soluong' => $numFreeNew,
    //                     'trangthai' => 'Hiển thị'
    //                 ]);
    //             } else {
    //                 GiohangModel::create([
    //                     'id_nguoidung' => $userId,
    //                     'id_bienthe' => $id_bienthe,
    //                     'soluong' => $numFreeNew,
    //                     'thanhtien' => 0,
    //                     'trangthai' => 'Hiển thị',
    //                 ]);
    //             }
    //         } else {
    //             if ($freeItem) {
    //                 $freeItem->delete();
    //             }
    //         }

    //         DB::commit();

    //         GioHangResource::withoutWrapping(); // Bỏ "data" bọc ngoài
    //         $cartItems = GiohangModel::with(['bienthe.sanpham.thuonghieu', 'bienthe.loaibienthe', 'bienthe.sanpham.hinhanhsanpham'])
    //             ->where('id_nguoidung', $userId)
    //             ->where('trangthai', 'Hiển thị')
    //             ->get();
    //         return response()->json(GioHangResource::collection($cartItems), Response::HTTP_OK);

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return $this->jsonResponse([
    //             'status' => false,
    //             'message' => 'Lỗi khi cập nhật giỏ hàng',
    //             'error' => $e->getMessage(),
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }


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

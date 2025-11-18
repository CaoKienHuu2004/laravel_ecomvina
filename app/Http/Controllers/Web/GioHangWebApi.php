<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiohangModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Http\Resources\Toi\GioHangResource;
use App\Models\BientheModel;
use Illuminate\Support\Facades\Redis;

// trả về json Object
class GioHangWebApi extends Controller
{
    use \App\Traits\ApiResponse;

    /**
     * 🔹 Lấy hoặc tạo user_id số nguyên ánh xạ từ session token
     */
    protected function getMappedUserIdFromToken(string $token): int
    {
        $redisKey = "user_session_map:{$token}";

        // Kiểm tra Redis đã có mapping chưa
        $userId = Redis::get($redisKey);
        if ($userId) {
            return (int) $userId;
        }

        // Tạo user_id mới bằng Redis INCR
        $newUserId = Redis::incr('user_session_map:counter');

        // Lưu mapping
        Redis::set($redisKey, $newUserId);

        return $newUserId;
    }

    /**
     * 🔹 Xác định ID người dùng hiện tại (luôn là số nguyên)
     *  - Nếu đăng nhập → dùng user_id (int)
     *  - Nếu chưa → tạo token khách, ánh xạ sang ID số nguyên bằng Redis
     */
    protected function getCurrentUserId(Request $request): int
    {
        // Nếu đã đăng nhập
        if ($request->user()) {
            return (int) $request->user()->id;
        }

        // Nếu có token khách (guest_token)
        $guestToken = $request->cookie('guest_token');
        if (!$guestToken) {
            // Tạo token mới nếu chưa có
            $guestToken = bin2hex(random_bytes(16));
            cookie()->queue(cookie('guest_token', $guestToken, 60 * 24 * 30)); // lưu 30 ngày
        }

        // Lấy hoặc tạo user_id số nguyên ánh xạ trong Redis
        return $this->getMappedUserIdFromToken($guestToken);
    }

    /**
     * 🛒 Lấy danh sách sản phẩm trong giỏ hàng
     */
    public function index(Request $request)
    {
        $userId = $this->getCurrentUserId($request);

        $giohang = GiohangModel::with([
            'bienthe.sanpham',
            'bienthe.sanpham.hinhanhsanpham',
            'bienthe.loaibienthe'
        ])
            ->where('id_nguoidung', $userId)
            ->where('trangthai', 'Hiển thị')
            ->get()
            ->filter(fn($item) => $item->soluong > 0)
            ->values();

        // return $this->jsonResponse([
        //     'status' => true,
        //     'message' => $giohang->isEmpty() ? 'Giỏ hàng trống' : 'Danh sách sản phẩm trong giỏ hàng',
        //     'data' => GioHangResource::collection($giohang),
        // ], Response::HTTP_OK);
        GioHangResource::withoutWrapping();
        return response()->json(GioHangResource::collection($giohang), Response::HTTP_OK);
    }

    /**
     * ➕ Thêm sản phẩm vào giỏ hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_bienthe' => 'required|exists:bienthe,id',
            'soluong' => 'required|integer|min:1',
        ]);

        $userId = $this->getCurrentUserId($request);
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

                // // Chỉ trừ hoặc cộng lại phần chênh lệch quà tặng
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

            // Chuẩn bị dữ liệu biến thể quà để trả về
            // $freeVariant = null;
            // if ($numFree > 0) {
            //     $freeVariantModel = BientheModel::with('sanpham')->find($id_bienthe);
            //     if ($freeVariantModel) {
            //         $freeItem = GiohangModel::where('id_nguoidung', $userId)
            //             ->where('id_bienthe', $id_bienthe)
            //             ->where('thanhtien', 0)
            //             ->first();

            //         $freeVariant = $freeVariantModel->toArray();
            //         $freeVariant['soluong'] = $freeItem ? $freeItem->soluong : 0;
            //         $freeVariant['thanhtien'] = 0;
            //     }
            // }

            DB::commit();

            // return $this->jsonResponse([
            //     'status' => true,
            //     'message' => 'Thêm sản phẩm vào giỏ hàng thành công',
            //     'data' => $item->load('bienthe.sanpham'),
            //     'bienthe_tang' => $freeVariant,
            // ], Response::HTTP_CREATED);
            GioHangResource::withoutWrapping(); // Bỏ "data" bọc ngoài
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
     * ✏️ Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soluong' => 'required|integer|min:0'
        ]);

        $userId = $this->getCurrentUserId($request);

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

                if ($freeItem) {
                    $restoreQty = $freeItem->soluong;
                    DB::table('bienthe')->where('id', $id_bienthe)
                        ->update(['luottang' => DB::raw("luottang + {$restoreQty}")]);
                }

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

            // // ✅ Cập nhật lại luottang theo chênh lệch
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

            // ✅ Lấy thông tin biến thể quà để trả về
            // $freeVariant = null;
            // if ($numFreeNew > 0) {
            //     $freeVariantModel = BientheModel::with('sanpham')->find($id_bienthe);
            //     if ($freeVariantModel) {
            //         $freeVariant = $freeVariantModel->toArray();
            //         $freeVariant['soluong'] = $numFreeNew;
            //         $freeVariant['thanhtien'] = 0;
            //     }
            // }

            DB::commit();

            // return $this->jsonResponse([
            //     'status' => true,
            //     'message' => 'Cập nhật số lượng thành công',
            //     'data' => $item->load('bienthe.sanpham'),
            //     'bienthe_tang' => $freeVariant,
            // ]);
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
     * ❌ Xóa sản phẩm khỏi giỏ hàng
     */
    public function destroy(Request $request, $id)
    {
        $userId = $this->getCurrentUserId($request);

        $item = GiohangModel::where('id_nguoidung', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công',
        ]);
    }
}

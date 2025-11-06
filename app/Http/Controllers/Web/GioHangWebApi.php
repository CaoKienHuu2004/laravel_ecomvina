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

        return $this->jsonResponse([
            'status' => true,
            'message' => $giohang->isEmpty() ? 'Giỏ hàng trống' : 'Danh sách sản phẩm trong giỏ hàng',
            'data' => GioHangResource::collection($giohang),
        ], Response::HTTP_OK);
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

        DB::beginTransaction();
        try {
            $variant = BientheModel::lockForUpdate()->findOrFail($validated['id_bienthe']);

            $soluong = $validated['soluong'];
            $priceUnit = $variant->giagoc;
            $id_bienthe = $validated['id_bienthe'];

            // Kiểm tra ưu đãi (nếu có)
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                ->where('bt.luottang', '>', 0)
                ->where('qs.dieukien', '<=', $soluong)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select('qs.dieukien as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                ->first();

            $thanhtien = $soluong * $priceUnit;

            if ($promotion) {
                $promotionCount = floor($soluong / $promotion->discount_multiplier);
                $numFree = min($promotionCount, $promotion->current_luottang);
                $numToPay = $soluong - $numFree;
                $thanhtien = $numToPay * $promotion->giagoc;

                // Cập nhật lượt tặng
                DB::table('bienthe')
                    ->where('id', $id_bienthe)
                    ->update(['luottang' => DB::raw("GREATEST(luottang - {$numFree}, 0)")]);

                // Thêm dòng miễn phí (thanhtien = 0)
                if ($numFree > 0) {
                    GiohangModel::updateOrCreate(
                        ['id_nguoidung' => $userId, 'id_bienthe' => $id_bienthe, 'thanhtien' => 0],
                        ['soluong' => DB::raw("soluong + {$numFree}"), 'trangthai' => 'Hiển thị']
                    );
                }
            }

            // Thêm hoặc cập nhật giỏ hàng có tính tiền
            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $id_bienthe)
                ->where('thanhtien', '>', 0)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->increment('soluong', $soluong);
                $item->increment('thanhtien', $thanhtien);
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
     * ✏️ Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate(['soluong' => 'required|integer|min:0']);
        $userId = $this->getCurrentUserId($request);

        DB::beginTransaction();
        try {
            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            // Nếu người dùng giảm về 0 → xóa luôn
            if ($validated['soluong'] == 0) {
                $item->delete();
                DB::commit();
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                ]);
            }

            $bienthe = DB::table('bienthe')
                ->where('id', $item->id_bienthe)
                ->lockForUpdate()
                ->firstOrFail();

            $priceUnit = $bienthe->giagoc;
            $soluong = $validated['soluong'];
            $thanhtien = $soluong * $priceUnit;

            $item->update(['soluong' => $soluong, 'thanhtien' => $thanhtien]);
            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Cập nhật số lượng thành công',
                'data' => $item->load('bienthe.sanpham'),
            ]);
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

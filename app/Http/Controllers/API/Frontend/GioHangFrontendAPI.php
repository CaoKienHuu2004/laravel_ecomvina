<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\TOI\GioHangResource;
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
     *     path="/api/toi/giohangs",
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
     *     path="/api/toi/giohangs",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="Thêm sản phẩm vào giỏ hàng",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_bienthe","soluong"},
     *             @OA\Property(property="id_bienthe", type="integer", example=5),
     *             @OA\Property(property="soluong", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm sản phẩm vào giỏ hàng thành công"
     *     ),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ hoặc thiếu")
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
            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id_bienthe', $validated['id_bienthe'])
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->soluong += $validated['soluong'];
                $item->save();
            } else {
                $item = GiohangModel::create([
                    'id_nguoidung' => $userId,
                    'id_bienthe' => $validated['id_bienthe'],
                    'soluong' => $validated['soluong'],
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
     *     path="/api/toi/giohangs/{id_bienthesp}",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="Cập nhật số lượng sản phẩm trong giỏ hàng",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id_bienthesp",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm trong giỏ hàng",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"soluong"},
     *             @OA\Property(property="soluong", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật số lượng thành công"
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy sản phẩm trong giỏ hàng")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soluong' => 'required|integer|min:0',
        ]);

        $user = $request->get('auth_user');
        $userId = $user->id;

        $item = GiohangModel::where('id_nguoidung', $userId)
            ->where('id', $id)
            ->firstOrFail();

        if ($validated['soluong'] == 0) {
            $item->delete();

            $remaining = GiohangModel::where('id_nguoidung', $userId)->count();
            if ($remaining === 0) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Giỏ hàng hiện đang trống',
                    'data' => [],
                ], Response::HTTP_OK);
            }

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            ], Response::HTTP_OK);
        }

        $gia = DB::table('bienthe')->where('id', $item->id_bienthe)->value('giagoc');

        $item->update([
            'soluong' => $validated['soluong'],
            'thanhtien' => $gia * $validated['soluong'],
        ]);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật số lượng thành công',
            'data' => $item->load('bienthe.sanpham'),
        ], Response::HTTP_OK);
    }


    /**
     * @OA\Delete(
     *     path="/api/toi/giohangs/{id_bienthesp}",
     *     tags={"Giỏ hàng (tôi)"},
     *     summary="Xóa sản phẩm khỏi giỏ hàng",
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

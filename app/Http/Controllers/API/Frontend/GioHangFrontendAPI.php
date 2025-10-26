<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
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
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm trong giỏ hàng hoặc thông báo giỏ hàng trống"
     *     ),
     *     @OA\Response(response=401, description="Không có quyền truy cập hoặc thiếu token")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->get('auth_user');
        $userId = $user->id;

        $giohang = GiohangModel::with(['bienthe.sanpham'])
            ->where('id_nguoidung', $userId)
            ->where('trangthai', 'Hiển thị')
            ->get();

        return $this->jsonResponse([
            'status' => true,
            'message' => $giohang->isEmpty() ? 'Giỏ hàng trống' : 'Danh sách sản phẩm trong giỏ hàng',
            'data' => $giohang,
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
        $gia = DB::table('bienthe')->where('id', $validated['id_bienthe'])->value('gia');

        $item = GiohangModel::where('id_nguoidung', $userId)
            ->where('id_bienthe', $validated['id_bienthe'])
            ->first();

        if ($item) {
            $item->soluong += $validated['soluong'];
            $item->thanhtien = $gia * $item->soluong;
            $item->save();
        } else {
            $item = GiohangModel::create([
                'id_bienthe' => $validated['id_bienthe'],
                'id_nguoidung'=> $userId,
                'soluong' => $validated['soluong'],
                'thanhtien' => $gia * $validated['soluong'],
                'trangthai' => 'Hiển thị',
            ]);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công',
            'data' => $item->load('bienthe.sanpham'),
        ], Response::HTTP_CREATED);
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
            'soluong' => 'required|integer|min:1',
        ]);

        $user = $request->get('auth_user');
        $userId = $user->id;
        $item = GiohangModel::where('id_nguoidung', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $gia = DB::table('bienthe')->where('id', $item->id_bienthe)->value('gia');

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

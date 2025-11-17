<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toi\YeuThichResource;
use App\Models\YeuthichModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Schema(
 *     schema="YeuThich",
 *     type="object",
 *     title="Yêu thích",
 *     description="Thông tin sản phẩm yêu thích của người dùng",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_nguoidung", type="integer", example=5),
 *     @OA\Property(property="id_sanpham", type="integer", example=12),
 *     @OA\Property(property="trangthai", type="string", example="Hiển thị"),
 *     @OA\Property(
 *         property="sanpham",
 *         type="object",
 *         description="Thông tin sản phẩm",
 *         @OA\Property(property="id", type="integer", example=12),
 *         @OA\Property(property="tensanpham", type="string", example="Áo thun nam"),
 *         @OA\Property(property="gia", type="number", format="float", example=250000),
 *         // ... thêm các thuộc tính cần thiết của sanpham
 *         @OA\Property(
 *             property="hinhanhsanpham",
 *             type="array",
 *             description="Danh sách hình ảnh sản phẩm",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=3),
 *                 @OA\Property(property="url", type="string", example="https://example.com/images/sp1.jpg"),
 *                 // ... các thuộc tính khác của hình ảnh
 *             )
 *         ),
 *         @OA\Property(
 *             property="danhmuc",
 *             type="object",
 *             description="Danh mục sản phẩm",
 *             @OA\Property(property="id", type="integer", example=2),
 *             @OA\Property(property="tendanhmuc", type="string", example="Thời trang nam"),
 *             // ... các thuộc tính khác
 *         ),
 *         @OA\Property(
 *             property="thuonghieu",
 *             type="object",
 *             description="Thương hiệu sản phẩm",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="tenthuonghieu", type="string", example="Nike"),
 *             // ... các thuộc tính khác
 *         ),
 *         @OA\Property(
 *             property="bienthe",
 *             type="object",
 *             description="Biến thể sản phẩm",
 *             @OA\Property(property="id", type="integer", example=10),
 *             @OA\Property(property="tenbienthe", type="string", example="Size M"),
 *             // ... các thuộc tính khác
 *             @OA\Property(
 *                 property="loaibienthe",
 *                 type="object",
 *                 description="Loại biến thể",
 *                 @OA\Property(property="id", type="integer", example=100),
 *                 @OA\Property(property="tenloaibienthe", type="string", example="Size"),
 *                 // ... các thuộc tính khác
 *             )
 *         )
 *     )
 * )
 */
class YeuThichFrontendAPI extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/toi/yeuthichs",
     *     tags={"Yêu thích (tôi)"},
     *     summary="Lấy danh sách sản phẩm yêu thích của người dùng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm yêu thích",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách sản phẩm yêu thích"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/YeuThich")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa đăng nhập hoặc token không hợp lệ")
     * )
     */
    public function index(Request $request)
    {
       $user = $request->get('auth_user');
        $userId = $user->id;

        $yeuThichs = YeuthichModel::with(
                'sanpham',
                'sanpham.hinhanhsanpham',
                'sanpham.danhmuc',
                'sanpham.thuonghieu',
                'sanpham.bienthe',
                'sanpham.bienthe.loaibienthe',
            )
            ->where('id_nguoidung', $userId)
            ->where('trangthai', 'Hiển thị')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Danh sách sản phẩm yêu thích',
            'data' => YeuThichResource::collection($yeuThichs)
        ], Response::HTTP_OK);
    }



    /**
     * @OA\Post(
     *     path="/api/toi/yeuthichs",
     *     tags={"Yêu thích (tôi)"},
     *     summary="Thêm sản phẩm vào danh sách yêu thích",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_sanpham"},
     *             @OA\Property(property="id_sanpham", type="integer", example=12)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đã thêm sản phẩm vào danh sách yêu thích",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Đã thêm sản phẩm vào danh sách yêu thích"),
     *             @OA\Property(property="data", ref="#/components/schemas/YeuThich")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa đăng nhập hoặc token không hợp lệ"),
     *     @OA\Response(response=409, description="Sản phẩm đã có trong danh sách yêu thích")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sanpham' => 'required|integer|exists:sanphams,id',
        ]);

        $user = $request->get('auth_user');
        $userId = $user->id;
        $idSanpham = $request->id_sanpham;

        $yeuThich = YeuThichModel::where('id_nguoidung', $userId)
            ->where('id_sanpham', $idSanpham)
            ->first();

        if ($yeuThich) {
            if ($yeuThich->trangthai === 'Hiển thị') {
                return response()->json([
                    'status' => false,
                    'message' => 'Sản phẩm đã có trong danh sách yêu thích',
                ], Response::HTTP_CONFLICT);
            }

            $yeuThich->update(['trangthai' => 'Hiển thị']);
        } else {
            $yeuThich = YeuThichModel::create([
                'id_nguoidung' => $userId,
                'id_sanpham' => $idSanpham,
                'trangthai' => 'Hiển thị',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Đã thêm sản phẩm vào danh sách yêu thích',
            'data' => $yeuThich
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/toi/yeuthichs/{id_sanpham}",
     *     tags={"Yêu thích (tôi)"},
     *     summary="Cập nhật trạng thái yêu thích (bỏ hoặc yêu thích lại sản phẩm)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id_sanpham",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đã cập nhật trạng thái yêu thích",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Đã bỏ yêu thích sản phẩm"),
     *             @OA\Property(property="data", ref="#/components/schemas/YeuThich")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy sản phẩm trong danh sách yêu thích"),
     *     @OA\Response(response=401, description="Chưa đăng nhập hoặc token không hợp lệ")
     * )
     */
    public function update(Request $request, $id_sanpham)
    {
        $user = $request->get('auth_user');
        $userId = $user->id;

        $yeuThich = YeuThichModel::where('id_nguoidung', $userId)
            ->where('id_sanpham', $id_sanpham)
            ->first();

        if (!$yeuThich) {
            return response()->json([
                'status' => false,
                'message' => 'Sản phẩm này không có trong danh sách yêu thích',
            ], Response::HTTP_NOT_FOUND);
        }

        $newStatus = $yeuThich->trangthai === 'Hiển thị' ? 'Tạm ẩn' : 'Hiển thị';
        $yeuThich->update(['trangthai' => $newStatus]);

        return response()->json([
            'status' => true,
            'message' => $newStatus === 'Hiển thị'
                ? 'Đã yêu thích lại sản phẩm'
                : 'Đã bỏ yêu thích sản phẩm',
            'data' => $yeuThich
        ], Response::HTTP_OK);
    }
}

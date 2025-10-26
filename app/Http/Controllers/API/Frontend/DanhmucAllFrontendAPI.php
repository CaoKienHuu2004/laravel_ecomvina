<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DanhmucModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DanhmucAllFrontendAPI extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/danhmucs-all",
     *     tags={"Tất Cả Danh mục theo cây đa cấp (nằm ở trang contact ...)"},
     *     summary="Lấy danh sách danh mục đa cấp có đếm số lượng con",
     *     description="API này trả về danh sách tất cả danh mục theo cấu trúc đa cấp (cha - con - cháu...) kèm theo số lượng danh mục con trực tiếp của mỗi danh mục.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh mục thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lấy danh mục thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Danh sách danh mục cha và các danh mục con",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="ten_danhmuc", type="string", example="Điện thoại"),
     *                     @OA\Property(property="slug", type="string", example="dien-thoai"),
     *                     @OA\Property(property="parent", type="integer", nullable=true, example=null),
     *                     @OA\Property(property="so_luong_con", type="integer", example=3, description="Số lượng danh mục con trực tiếp"),
     *                     @OA\Property(
     *                         property="danhmuccon",
     *                         type="array",
     *                         description="Danh mục con của danh mục hiện tại",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="ten_danhmuc", type="string", example="Điện thoại Samsung"),
     *                             @OA\Property(property="slug", type="string", example="dien-thoai-samsung"),
     *                             @OA\Property(property="parent", type="integer", example=1),
     *                             @OA\Property(property="so_luong_con", type="integer", example=2),
     *                             @OA\Property(
     *                                 property="danhmuccon",
     *                                 type="array",
     *                                 description="Danh mục cháu (cấp con của cấp con)",
     *                                 @OA\Items(
     *                                     @OA\Property(property="id", type="integer", example=3),
     *                                     @OA\Property(property="ten_danhmuc", type="string", example="Samsung Galaxy A"),
     *                                     @OA\Property(property="slug", type="string", example="samsung-galaxy-a"),
     *                                     @OA\Property(property="parent", type="integer", example=2),
     *                                     @OA\Property(property="so_luong_con", type="integer", example=0)
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi server hoặc không lấy được dữ liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Lỗi hệ thống")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Lấy danh mục cha và con cấp 3
        $danhmucs = DanhmucModel::whereNull('parent')
            ->with('danhmuccon.danhmuccon.danhmuccon')
            ->get();

        // Đệ quy để thêm số lượng con
        $addCountRecursive = function ($categories) use (&$addCountRecursive) {
            return $categories->map(function ($item) use ($addCountRecursive) {
                $item->so_luong_con = $item->danhmuccon ? $item->danhmuccon->count() : 0;
                if ($item->danhmuccon && $item->danhmuccon->isNotEmpty()) {
                    $item->danhmuccon = $addCountRecursive($item->danhmuccon);
                }
                return $item;
            });
        };

        $danhmucs = $addCountRecursive($danhmucs);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh mục thành công',
            'data' => $danhmucs
        ], Response::HTTP_OK);
    }
}

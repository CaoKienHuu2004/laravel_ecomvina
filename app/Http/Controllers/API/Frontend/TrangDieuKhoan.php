<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TrangNoiDungModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TrangDieuKhoan extends BaseFrontendController
{
    //

    /**
     * @OA\Get(
     *     path="/api/trang-dieu-khoan",
     *     summary="Danh sách các selection và HTML của trang điều khoản",
     *     description="Trả về danh sách các mục điều khoản: tiêu đề, slug, mô tả HTML, hình ảnh và trạng thái hiển thị.",
     *     tags={"Trang Điều Khoản"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách trang điều khoản",
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách các selection và html của trang điều khoản"),
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/TrangDieuKhoanItem")
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="TrangDieuKhoanItem",
     *     type="object",
     *     title="Trang Điều Khoản",
     *
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="tieude", type="string", example="Trang điều khoản sử dụng"),
     *     @OA\Property(property="slug", type="string", example="http://148.230.100.215:3000/dieu-khoan"),
     *
     *     @OA\Property(
     *         property="mota",
     *         type="string",
     *         description="Nội dung HTML đầy đủ của trang điều khoản",
     *         example="<div class='page'>...</div>"
     *     ),
     *
     *     @OA\Property(property="hinhanh", type="string", nullable=true, example="http://148.230.100.215/assets/client/images/page/trang_dieukhoan.jpg"),
     *     @OA\Property(property="trangthai", type="string", example="Hiển thị"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-02T23:41:42.000000Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-02T23:41:42.000000Z")
     * )
     */
    public function index(Request $request)
    {
        $data = TrangNoiDungModel::where('tieude', 'Trang điều khoản sử dụng')
                    ->orderBy('id', 'desc')
                    ->get();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách các selection và html của trang điều khoản',
            'data'    => $data,
        ], Response::HTTP_OK);
    }
}

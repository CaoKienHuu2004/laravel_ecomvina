<?php

namespace App\Http\Controllers\API\Frontend;


use App\Http\Controllers\Controller;
use App\Models\TrangNoiDungModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TrangGioiThieu extends Controller
{
    use ApiResponse;
    /**
     * @OA\Get(
     *     path="/api/trang-gioi-thieu",
     *     summary="Lấy thông tin trang giới thiệu Siêu Thị Vina",
     *     description="Trả về nội dung HTML và các thông tin liên quan của trang giới thiệu Siêu Thị Vina",
     *     tags={"Trang Giới Thiệu"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin trang giới thiệu",
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thông tin trang giới thiệu Siêu Thị Vina"),
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/TrangGioiThieuItem"
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="TrangGioiThieuItem",
     *     type="object",
     *     title="Trang Giới Thiệu Siêu Thị Vina",
     *
     *     @OA\Property(property="id", type="integer", example=2),
     *     @OA\Property(property="tieude", type="string", example="Giới thiệu về Siêu Thị Vina"),
     *     @OA\Property(property="slug", type="string", example="http://148.230.100.215:3000/gioi-thieu"),
     *
     *     @OA\Property(
     *         property="mota",
     *         type="string",
     *         description="Nội dung HTML đầy đủ của trang giới thiệu",
     *         example="<div class='page'>...</div>"
     *     ),
     *
     *     @OA\Property(property="hinhanh", type="string", nullable=true, example="http://148.230.100.215/assets/client/images/page/trang_gioithieu.jpg"),
     *     @OA\Property(property="trangthai", type="string", example="Hiển thị")
     * )
     */
    public function index(Request $request)
    {
        $data = TrangNoiDungModel::where('tieude', 'Trang giới thiệu sieuthivina')
                    ->orderBy('id', 'desc')
                    ->get();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách các selection và html của trang giới thiệu',
            'data'    => $data,
        ], Response::HTTP_OK);
    }
}

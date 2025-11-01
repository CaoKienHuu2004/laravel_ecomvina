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
     *     tags={"Danh mục"},
     *     summary="Lấy danh sách danh mục cha và con (2 cấp, không có parent_id, FK tới chính nó)",
     *     description="Trả về danh sách danh mục có parent='Cha' và các danh mục con (parent='Con') tương ứng theo nhóm tên hoặc slug.",
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh mục thành công"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            // Lấy danh mục cha và con đang "Hiển thị"
            $cha = DanhmucModel::where('parent', 'Cha')
                ->where('trangthai', 'Hiển thị')
                ->get();

            $con = DanhmucModel::where('parent', 'Con')
                ->where('trangthai', 'Hiển thị')
                ->get();

            // Tạo cấu trúc cây đơn giản (cha - con)
            $result = $cha->map(function ($dmCha) use ($con) {
                // Gắn con theo cách "tên cha xuất hiện trong tên con"
                $danhmucCon = $con->filter(function ($dmCon) use ($dmCha) {
                    return str_contains(
                        strtolower($dmCon->ten),
                        strtolower($dmCha->ten)
                    );
                })->values();

                return [
                    'id' => $dmCha->id,
                    'ten' => $dmCha->ten,
                    'slug' => $dmCha->slug,
                    'logo' => $dmCha->logo,
                    'parent' => $dmCha->parent,
                    'trangthai' => $dmCha->trangthai,
                    'so_luong_con' => $danhmucCon->count(),
                    'danhmuccon' => $danhmucCon,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh mục thành công',
                'data' => $result
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

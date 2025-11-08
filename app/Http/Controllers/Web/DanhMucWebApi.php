<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\DanhMucResource;
use App\Models\DanhmucModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DanhMucWebApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Lấy danh mục cha đang "Hiển thị"
            $cha = DanhmucModel::where('parent', 'Cha')
                ->where('trangthai', 'Hiển thị')
                ->get();

            // Lấy danh mục con đang "Hiển thị"
            $con = DanhmucModel::where('parent', 'Con')
                ->where('trangthai', 'Hiển thị')
                ->get();

            // Gắn danh mục con cho từng cha theo điều kiện tên con chứa tên cha
            $cha->each(function ($dmCha) use ($con) {
                $dmCha->danhmuccon = $con->filter(function ($dmCon) use ($dmCha) {
                    return str_contains(
                        strtolower($dmCon->ten),
                        strtolower($dmCha->ten)
                    );
                })->values();
            });

            DanhMucResource::withoutWrapping();

            return response()->json(DanhMucResource::collection($cha), Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

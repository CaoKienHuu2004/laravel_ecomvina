<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toi\YeuThichResource;
use App\Models\YeuthichModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class YeuThichWebApi extends Controller
{
    /**
     * Display a listing of the resource.
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

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Danh sách sản phẩm yêu thích',
        //     'data' => $yeuThichs
        // ], Response::HTTP_OK);
        YeuThichResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(YeuThichResource::collection($yeuThichs), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sanpham' => 'required|integer|exists:sanpham,id',
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
        // YeuThichResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        // return response()->json(new YeuThichResource($yeuThich), Response::HTTP_CREATED);
    }

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
        // YeuThichResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        // return response()->json(new YeuThichResource($yeuThich), Response::HTTP_OK);
    }
}

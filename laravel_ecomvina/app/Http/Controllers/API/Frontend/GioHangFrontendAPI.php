<?php

namespace App\Http\Controllers\API\Frontend;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use App\Http\Resources\Frontend\GioHangResource;
use App\Http\Resources\ChiTietGioHangResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GioHangFrontendAPI extends BaseFrontendController
{

    // public function __construct() // nếu muốn login mới xem được giỏ hàng
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Xem toàn bộ giỏ hàng của user hiện tại
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $giohang = GioHang::where('id_nguoidung', $userId)
            ->with('chitiet.bienTheSanPham.sanpham', 'nguoidung')
            ->first();

        if (!$giohang) {
            return $this->jsonResponse([
                'status'  => true,
                'message' => 'Giỏ hàng trống',
                'data'    => []
            ], Response::HTTP_OK);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách giỏ hàng',
            'data'    => new GioHangResource($giohang)
        ], Response::HTTP_OK);
    }

    /**
     * Thêm sản phẩm vào giỏ
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bienthe_sp_id' => 'required|exists:bienthe_sp,id',
            'soluong'       => 'required|integer|min:1',
        ]);

        $userId = $request->user()->id;

        // Lấy hoặc tạo giỏ hàng cho user
        $giohang = GioHang::firstOrCreate(
            ['id_nguoidung' => $userId],
            ['id_nguoidung' => $userId]
        );

        // Kiểm tra nếu sản phẩm đã có trong giỏ → update số lượng
        $item = ChiTietGioHang::where('gio_hang_id', $giohang->id)
            ->where('bienthe_sp_id', $validated['bienthe_sp_id'])
            ->first();

        $gia = DB::table('bienthe_sp')->where('id', $validated['bienthe_sp_id'])->value('gia');

        if ($item) {
            $item->soluong += $validated['soluong'];
            $item->tongtien = $gia * $item->soluong;
            $item->save();
        } else {
            $item = ChiTietGioHang::create([
                'gio_hang_id'   => $giohang->id,
                'bienthe_sp_id' => $validated['bienthe_sp_id'],
                'soluong'       => $validated['soluong'],
                'tongtien'      => $gia * $validated['soluong'],
            ]);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Thêm sản phẩm vào giỏ thành công',
            'data'    => new ChiTietGioHangResource($item->load('bienTheSanPham.sanpham'))
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'soluong' => 'required|integer|min:1',
        ]);

        $userId = $request->user()->id;

        $item = ChiTietGioHang::whereHas('gioHang', function ($q) use ($userId) {
                $q->where('id_nguoidung', $userId);
            })
            ->findOrFail($id);

        $gia = $item->bienTheSanPham->gia;
        $item->soluong  = $validated['soluong'];
        $item->tongtien = $gia * $validated['soluong'];
        $item->save();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật giỏ hàng thành công',
            'data'    => new ChiTietGioHangResource($item->load('bienTheSanPham.sanpham'))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa sản phẩm khỏi giỏ
     */
    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;

        $item = ChiTietGioHang::whereHas('gioHang', function ($q) use ($userId) {
                $q->where('id_nguoidung', $userId);
            })
            ->findOrFail($id);

        $item->delete();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Xóa sản phẩm khỏi giỏ thành công',
            'data'    => []
        ], Response::HTTP_NO_CONTENT);
    }
}

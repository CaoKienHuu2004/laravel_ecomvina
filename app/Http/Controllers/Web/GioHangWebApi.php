<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class GioHangWebApi extends Controller
{
    use \App\Traits\ApiResponse;
    /**
     * Lấy giỏ hàng user hiện tại (dùng session/cookie login).
     * Next.js khi gọi API phải thêm:

    *      fetch("http://localhost:8000/giohang", {
    *      method: "GET",
    *      credentials: "include", // quan trọng để gửi cookie kèm theo
    *     });
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $giohang = GioHang::where('id_nguoidung', $userId)
            ->with('chitiet.bienTheSanPham.sanpham')
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
            'data'    => $giohang
        ], Response::HTTP_OK);
    }

    /**
     * Thêm sản phẩm vào giỏ.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bienthe_sp_id' => 'required|exists:bienthe_sp,id',
            'soluong'       => 'required|integer|min:1',
        ]);

        $userId = $request->user()->id;

        // Lấy hoặc tạo giỏ hàng
        $giohang = GioHang::firstOrCreate(['id_nguoidung' => $userId]);

        // Kiểm tra item trong giỏ
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
            'message' => 'Thêm sản phẩm thành công',
            'data'    => $item
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật số lượng.
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
            'message' => 'Cập nhật thành công',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Xóa sản phẩm khỏi giỏ.
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
            'message' => 'Xóa thành công',
            'data'    => []
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ChiTietGioHang;
use App\Http\Resources\ChiTietGioHangResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChiTietGioHangController extends BaseController
{
    // Lấy danh sách chi tiết giỏ hàng
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        // Query với eager load quan hệ
        $query = ChiTietGioHang::with(['gioHang', 'bienTheSanPham'])
            ->when($request->gio_hang_id, fn($q) => $q->where('gio_hang_id', $request->gio_hang_id))
            ->when($request->bienthe_sp_id, fn($q) => $q->where('bienthe_sp_id', $request->bienthe_sp_id))
            ->latest('updated_at');

        // Phân trang
        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu page vượt quá tổng số trang
        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'data'    => ChiTietGioHangResource::collection($items),
                'meta'    => [
                    'current_page' => $currentPage,
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ]
            ], 404);
        }

        // Response chuẩn
        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách chi tiết giỏ hàng',
            'data'    => ChiTietGioHangResource::collection($items),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'next_page_url'=> $items->nextPageUrl(),
                'prev_page_url'=> $items->previousPageUrl(),
            ]
        ], 200);
    }


    // Tạo mới chi tiết giỏ hàng
    public function store(Request $request)
    {
        $data = $request->validate([
            'gio_hang_id'   => 'required|exists:gio_hang,id',
            'bienthe_sp_id' => 'required|exists:bienthe_sp,id',
            'soluong'       => 'required|integer|min:1',
        ]);

        // tự động tính tổng tiền
        $gia = DB::table('bienthe_sp')->where('id', $data['bienthe_sp_id'])->value('gia');
        $data['tongtien'] = $gia * $data['soluong'];

        $item = ChiTietGioHang::create($data);

        return new ChiTietGioHangResource($item);
    }

    // Xem chi tiết 1 item
    public function show(ChiTietGioHang $chitiet_giohang)
    {
        return new ChiTietGioHangResource($chitiet_giohang->load(['gioHang', 'bienTheSanPham']));
    }

    // Cập nhật chi tiết giỏ hàng
    public function update(Request $request, ChiTietGioHang $chitiet_giohang)
    {
        $data = $request->validate([
            'soluong' => 'sometimes|integer|min:1',
        ]);

        if (isset($data['soluong'])) {
            $gia = $chitiet_giohang->bienTheSanPham->gia;
            $data['tongtien'] = $gia * $data['soluong'];
        }

        $chitiet_giohang->update($data);

        return new ChiTietGioHangResource($chitiet_giohang);
    }

    // Xóa item khỏi giỏ
    public function destroy(ChiTietGioHang $chitiet_giohang)
    {
        $chitiet_giohang->delete();
        return $this->jsonResponse(['message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
    }
}

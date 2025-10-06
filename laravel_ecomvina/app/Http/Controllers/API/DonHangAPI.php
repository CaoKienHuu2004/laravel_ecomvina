<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\DonHang;
use App\Http\Resources\DonHangResource;
use Illuminate\Http\Response;

class DonHangAPI extends BaseController
{
    /**
     * Lấy danh sách đơn hàng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = DonHang::with(['nguoidung', 'magiamgia'])
            ->latest('updated_at')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ma_donhang', 'like', "%$q%")
                        ->orWhere('ghichu', 'like', "%$q%")
                        ->orWhere('trangthai', 'like', "%$q%")
                        ->orWhereHas('nguoidung', function ($u) use ($q) {
                            $u->where('hoten', 'like', "%$q%");
                        });
                });
            });

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $items->lastPage(),
                    'per_page' => $perPage,
                    'total' => $items->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách đơn hàng',
            'data' => DonHangResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết 1 đơn hàng
     */
    public function show(string $id)
    {
        $item = DonHang::with(['nguoidung', 'magiamgia'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết đơn hàng',
            'data' => new DonHangResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới đơn hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_donhang'     => 'nullable|string|max:255',
            'tongtien'       => 'required|numeric',
            'tongsoluong'    => 'required|integer',
            'ghichu'         => 'nullable|string',
            'ngaytao'        => 'required|date',
            'trangthai'      => 'nullable|in:cho_xac_nhan,da_xac_nhan,dang_giao,da_giao,da_huy',
            'id_nguoidung'   => 'required|exists:nguoi_dung,id',
            'id_magiamgia'   => 'required|exists:ma_giamgia,id',
        ]);

        $item = DonHang::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo đơn hàng thành công',
            'data' => new DonHangResource($item)
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật đơn hàng
     */
    public function update(Request $request, string $id)
    {
        $item = DonHang::findOrFail($id);

        $validated = $request->validate([
            'ma_donhang'     => 'nullable|string|max:255',
            'tongtien'       => 'sometimes|numeric',
            'tongsoluong'    => 'sometimes|integer',
            'ghichu'         => 'nullable|string',
            'ngaytao'        => 'nullable|date',
            'trangthai'      => 'nullable|in:cho_xac_nhan,da_xac_nhan,dang_giao,da_giao,da_huy',
            'id_nguoidung'   => 'sometimes|exists:nguoi_dung,id',
            'id_magiamgia'   => 'sometimes|exists:ma_giamgia,id',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật đơn hàng thành công',
            'data' => new DonHangResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Xóa đơn hàng (soft delete)
     */
    public function destroy(string $id)
    {
        $item = DonHang::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa đơn hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

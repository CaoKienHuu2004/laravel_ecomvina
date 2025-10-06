<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ThanhToan;
use App\Http\Resources\ThanhToanResource;
use Illuminate\Http\Response;

class ThanhToanAPI extends BaseController
{
    /**
     * Lấy danh sách thanh toán (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = ThanhToan::with('donhang')
            ->latest('ngaythanhtoan')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nganhang', 'like', "%$q%")
                        ->orWhere('magiaodich', 'like', "%$q%")
                        ->orWhere('trangthai', 'like', "%$q%")
                        ->orWhereHas('donhang', function ($d) use ($q) {
                            $d->where('ma_donhang', 'like', "%$q%");
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
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách thanh toán',
            'data' => ThanhToanResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }


    /**
     * Xem chi tiết 1 thanh toán
     */
    public function show(string $id)
    {
        $item = ThanhToan::with('donhang')->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết thanh toán',
            'data' => new ThanhToanResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Tạo thanh toán mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nganhang'      => 'nullable|string|max:255',
            'gia'           => 'required|numeric',
            'noidung'       => 'nullable|string',
            'magiaodich'    => 'nullable|string|max:255',
            'ngaythanhtoan' => 'required|date',
            'trangthai'     => 'nullable|in:cho_xac_nhan,dang_xu_ly,thanh_cong,that_bai,da_huy,hoan_tien,tre_han,cho_xac_nhan_ngan_hang',
            'id_donhang'    => 'required|exists:don_hang,id',
        ]);

        $item = ThanhToan::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo thanh toán thành công',
            'data' => new ThanhToanResource($item)
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật thanh toán
     */
    public function update(Request $request, string $id)
    {
        $item = ThanhToan::findOrFail($id);

        $validated = $request->validate([
            'nganhang'      => 'sometimes|string|max:255',
            'gia'           => 'sometimes|numeric',
            'noidung'       => 'sometimes|string',
            'magiaodich'    => 'sometimes|string|max:255',
            'ngaythanhtoan' => 'sometimes|date',
            'trangthai'     => 'sometimes|in:cho_xac_nhan,dang_xu_ly,thanh_cong,that_bai,da_huy,hoan_tien,tre_han,cho_xac_nhan_ngan_hang',
            'id_donhang'    => 'sometimes|exists:don_hang,id',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật thanh toán thành công',
            'data' => new ThanhToanResource($item)
        ], Response::HTTP_OK);
    }

    /**
     * Xóa thanh toán (soft delete)
     */
    public function destroy(string $id)
    {
        $item = ThanhToan::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa thanh toán thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\DonhangModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DonHangAPI extends BaseController
{
    /**
     * Lấy danh sách đơn hàng (có phân trang, không dùng Resource)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $q       = $request->get('q');

        $query = DonhangModel::with(['nguoidung', 'phuongthuc', 'magiamgia'])
            ->latest('updated_at')
            ->when($q, function ($query) use ($q) {
                $query->where('madon', 'like', "%$q%")
                    ->orWhere('trangthai', 'like', "%$q%");
            });

        $items = $query->paginate($perPage);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách đơn hàng',
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        $item = DonhangModel::with(['nguoidung', 'phuongthuc', 'magiamgia'])->find($id);

        if (!$item) {
            return $this->jsonResponse(['status' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Chi tiết đơn hàng',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới đơn hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nguoidung' => 'required|exists:nguoidung,id',
            'id_phuongthuc' => 'required|exists:phuongthuc,id',
            'id_magiamgia' => 'nullable|exists:magiamgia,id',
            'madon' => 'required|string|max:10|unique:donhang,madon',
            'tongsoluong' => 'required|integer|min:1',
            'thanhtien' => 'required|integer|min:0',
            'trangthai' => 'nullable|in:Chờ xử lý,Đã chấp nhận,Đang giao hàng,Đã giao,Đã hủy',
        ]);

        $donhang = DonhangModel::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Tạo đơn hàng thành công',
            'data'    => $donhang
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật đơn hàng
     */
    public function update(Request $request, $id)
    {
        $donhang = DonhangModel::find($id);

        if (!$donhang) {
            return $this->jsonResponse(['status' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $validated = $request->validate([
            'id_nguoidung' => 'sometimes|exists:nguoidung,id',
            'id_phuongthuc' => 'sometimes|exists:phuongthuc,id',
            'id_magiamgia' => 'nullable|exists:magiamgia,id',
            'madon' => 'sometimes|string|max:10|unique:donhang,madon,' . $id,
            'tongsoluong' => 'sometimes|integer|min:1',
            'thanhtien' => 'sometimes|integer|min:0',
            'trangthai' => 'nullable|in:Chờ xử lý,Đã chấp nhận,Đang giao hàng,Đã giao,Đã hủy',
        ]);

        $donhang->update($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật đơn hàng thành công',
            'data'    => $donhang
        ], Response::HTTP_OK);
    }

    /**
     * Xóa mềm đơn hàng (Soft Delete)
     */
    public function destroy($id)
    {
        $donhang = DonhangModel::find($id);

        if (!$donhang) {
            return $this->jsonResponse(['status' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $donhang->delete(); // Soft delete — sẽ gán giá trị vào cột deleted_at

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Đơn hàng đã được xóa (soft delete)'
        ], Response::HTTP_OK);
    }

    /**
     * Xóa vĩnh viễn (Force Delete)
     */
    public function forceDelete($id)
    {
        $donhang = DonhangModel::withTrashed()->find($id);

        if (!$donhang) {
            return $this->jsonResponse(['status' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $donhang->forceDelete(); // Xóa thật khỏi DB

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Đã xóa vĩnh viễn đơn hàng'
        ], Response::HTTP_OK);
    }

    /**
     * Khôi phục đơn hàng bị xóa mềm
     */
    public function restore($id)
    {
        $donhang = DonhangModel::withTrashed()->find($id);

        if (!$donhang || !$donhang->trashed()) {
            return $this->jsonResponse(['status' => false, 'message' => 'Đơn hàng không tồn tại hoặc chưa bị xóa'], 404);
        }

        $donhang->restore();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Khôi phục đơn hàng thành công',
            'data'    => $donhang
        ], Response::HTTP_OK);
    }
}

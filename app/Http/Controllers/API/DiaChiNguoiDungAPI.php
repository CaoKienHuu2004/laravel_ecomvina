<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\DiaChiGiaoHangModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiaChiNguoiDungAPI extends BaseController
{
    /**
     * Lấy danh sách địa chỉ người dùng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $q       = $request->get('q'); // Từ khóa tìm kiếm

        $query = DiaChiGiaoHangModel::with('nguoidung')
            ->latest('updated_at')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('hoten', 'like', "%$q%")
                        ->orWhere('sodienthoai', 'like', "%$q%")
                        ->orWhere('diachi', 'like', "%$q%")
                        ->orWhereHas('nguoidung', function ($u) use ($q) {
                            $u->where('hoten', 'like', "%$q%");
                        });
                });
            });

        $items = $query->paginate($perPage);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách địa chỉ người dùng',
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
     * Xem chi tiết 1 địa chỉ người dùng
     */
    public function show($id)
    {
        $item = DiaChiGiaoHangModel::with('nguoidung')->find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ người dùng'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Chi tiết địa chỉ người dùng',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới địa chỉ người dùng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nguoidung' => 'required|exists:nguoidung,id',
            'hoten'        => 'required|string|max:255',
            'sodienthoai'  => 'required|string|max:10',
            'diachi'       => 'required|string',
            'trangthai'    => 'required|in:Mặc định,Khác,Tạm ẩn',
        ]);

        $item = DiaChiGiaoHangModel::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Tạo địa chỉ người dùng thành công',
            'data'    => $item
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật địa chỉ người dùng
     */
    public function update(Request $request, $id)
    {
        $item = DiaChiGiaoHangModel::find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ người dùng'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'hoten'        => 'sometimes|string|max:255',
            'sodienthoai'  => 'sometimes|string|max:10',
            'diachi'       => 'sometimes|string',
            'trangthai'    => 'sometimes|in:Mặc định,Khác,Tạm ẩn',
            'id_nguoidung' => 'sometimes|exists:nguoidung,id',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật địa chỉ người dùng thành công',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Xóa mềm địa chỉ người dùng
     */
    public function destroy($id)
    {
        $item = DiaChiGiaoHangModel::find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ người dùng'
            ], Response::HTTP_NOT_FOUND);
        }

        $item->delete(); // Soft delete — cột deleted_at sẽ được gán giá trị

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Địa chỉ người dùng đã được xóa (soft delete)'
        ], Response::HTTP_OK);
    }

    /**
     * Khôi phục địa chỉ người dùng bị xóa mềm
     */
    public function restore($id)
    {
        $item = DiaChiGiaoHangModel::withTrashed()->find($id);

        if (!$item || !$item->trashed()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Địa chỉ không tồn tại hoặc chưa bị xóa mềm'
            ], Response::HTTP_NOT_FOUND);
        }

        $item->restore();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Khôi phục địa chỉ thành công',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Xóa vĩnh viễn (hard delete)
     */
    public function forceDelete($id)
    {
        $item = DiaChiGiaoHangModel::withTrashed()->find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ người dùng'
            ], Response::HTTP_NOT_FOUND);
        }

        $item->forceDelete(); // Xóa thật khỏi DB

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Địa chỉ người dùng đã bị xóa vĩnh viễn'
        ], Response::HTTP_OK);
    }
}

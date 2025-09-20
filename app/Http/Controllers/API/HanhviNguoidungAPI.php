<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Resources\HanhviNguoiDungResource;
use App\Models\HanhviNguoidung;
use Illuminate\Http\Response;

class HanhviNguoidungAPI extends BaseController
{
    /**
     * Lưu hành vi người dùng
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_nguoidung' => 'nullable|exists:nguoi_dung,id',
            'id_sanpham'   => 'nullable|exists:san_pham,id',
            'id_bienthe'   => 'nullable|exists:bienthe_sp,id',
            'hanhdong'     => 'required|in:xem,click_bienthe,them_gio,mua,danh_gia',
            'ghichu'       => 'nullable|string',
        ]);

        $hanhvi = HanhviNguoidung::create($data);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Lưu hành vi người dùng thành công',
            'data' => new HanhviNguoiDungResource($hanhvi->load(['nguoidung', 'sanpham', 'bienthe']))
        ], Response::HTTP_CREATED);
    }

    /**
     * Danh sách hành vi (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $logs = HanhviNguoidung::with(['nguoidung', 'sanpham', 'bienthe'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $logs->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $logs->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $logs->lastPage(),
                    'per_page' => $perPage,
                    'total' => $logs->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách hành vi người dùng',
            'data' => HanhviNguoiDungResource::collection($logs),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page'    => $logs->lastPage(),
                'per_page'     => $logs->perPage(),
                'total'        => $logs->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết hành vi
     */
    public function show(string $id)
    {
        $hanhvi = HanhviNguoidung::with(['nguoidung', 'sanpham', 'bienthe'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết hành vi người dùng',
            'data' => new HanhviNguoiDungResource($hanhvi)
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật hành vi
     */
    public function update(Request $request, string $id)
    {
        $hanhvi = HanhviNguoidung::findOrFail($id);

        $data = $request->validate([
            'id_nguoidung' => 'nullable|exists:nguoi_dung,id',
            'id_sanpham'   => 'nullable|exists:san_pham,id',
            'id_bienthe'   => 'nullable|exists:bienthe_sp,id',
            'hanhdong'     => 'sometimes|required|in:xem,click_bienthe,them_gio,mua,danh_gia',
            'ghichu'       => 'nullable|string',
        ]);

        $hanhvi->update($data);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật hành vi thành công',
            'data' => new HanhviNguoiDungResource($hanhvi->load(['nguoidung', 'sanpham', 'bienthe']))
        ], Response::HTTP_OK);
    }

    /**
     * Xóa hành vi
     */
    public function destroy(string $id)
    {
        $hanhvi = HanhviNguoidung::findOrFail($id);
        $hanhvi->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa hành vi thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

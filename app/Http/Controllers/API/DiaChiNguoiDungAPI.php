<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\DiaChi;
use App\Http\Resources\DiaChiNguoiDungResources;
use Illuminate\Http\Response;

class DiaChiNguoiDungAPI extends BaseController
{
    /**
     * Danh sách địa chỉ người dùng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $diachis = DiaChi::latest('updated_at')->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $diachis->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $diachis->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $diachis->lastPage(),
                    'per_page' => $perPage,
                    'total' => $diachis->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách địa chỉ người dùng',
            'data' => DiaChiNguoiDungResources::collection($diachis),
            'meta' => [
                'current_page' => $diachis->currentPage(),
                'last_page'    => $diachis->lastPage(),
                'per_page'     => $diachis->perPage(),
                'total'        => $diachis->total(),
            ]
        ], 200);
    }

    /**
     * Tạo mới địa chỉ người dùng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten'         => 'required|string|max:255',
            'sodienthoai' => 'required|string|max:20',
            'thanhpho'    => 'nullable|string',
            'xaphuong'    => 'nullable|string',
            'sonha'       => 'nullable|string',
            'diachi'      => 'nullable|string',
            'trangthai'   => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
            'id_nguoidung'=> 'required|exists:nguoi_dung,id',
        ]);

        $diachi = DiaChi::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo địa chỉ người dùng thành công',
            'data' => new DiaChiNguoiDungResources($diachi)
        ], Response::HTTP_CREATED);
    }

    /**
     * Chi tiết địa chỉ người dùng
     */
    public function show(Request $request, $id)
    {
        $diachi = DiaChi::findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết địa chỉ người dùng',
            'data' => new DiaChiNguoiDungResources($diachi)
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật địa chỉ người dùng
     */
    public function update(Request $request, $id)
    {
        $diachi = DiaChi::findOrFail($id);

        $validated = $request->validate([
            'ten'         => 'sometimes|string|max:255',
            'sodienthoai' => 'sometimes|string|max:20',
            'thanhpho'    => 'nullable|string',
            'xaphuong'    => 'nullable|string',
            'sonha'       => 'nullable|string',
            'diachi'      => 'nullable|string',
            'trangthai'   => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
            'id_nguoidung'=> 'sometimes|exists:nguoi_dung,id',
        ]);

        $diachi->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật địa chỉ người dùng thành công',
            'data' => new DiaChiNguoiDungResources($diachi)
        ], Response::HTTP_OK);
    }

    /**
     * Xóa địa chỉ người dùng
     */
    public function destroy($id)
    {
        $diachi = DiaChi::findOrFail($id);
        $diachi->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa địa chỉ người dùng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

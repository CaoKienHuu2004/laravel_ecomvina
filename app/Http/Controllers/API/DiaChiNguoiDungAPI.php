<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiaChiNguoiDungResources;
use App\Models\DiaChi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiaChiNguoiDungAPI extends Controller
{
    /**
     * Danh sách địa chỉ người dùng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $diachis = DiaChi::latest('updated_at')->paginate($perPage);

        return DiaChiNguoiDungResources::collection($diachis)
            ->additional([
                'meta' => [
                    'current_page' => $diachis->currentPage(),
                    'last_page'    => $diachis->lastPage(),
                    'per_page'     => $diachis->perPage(),
                    'total'        => $diachis->total(),
                ]
            ]);
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

        return (new DiaChiNguoiDungResources($diachi))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Chi tiết địa chỉ người dùng
     */
    public function show(Request $request, $id)
    {
        $diachi = DiaChi::findOrFail($id);
        return new DiaChiNguoiDungResources($diachi);
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

        return new DiaChiNguoiDungResources($diachi);
    }

    /**
     * Xóa địa chỉ người dùng
     */
    public function destroy($id)
    {
        $diachi = DiaChi::findOrFail($id);
        $diachi->delete();

        return response()->json([
            'message' => 'Xóa địa chỉ người dùng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ThongbaoModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ThongBaoAPI extends Controller
{
    /**
     * Lấy danh sách tất cả thông báo
     */
    public function index()
    {
        $thongbaos = ThongbaoModel::with('nguoidung')->get();

        return response()->json([
            'success' => true,
            'data' => $thongbaos
        ]);
    }

    /**
     * Lấy thông báo theo id
     */
    public function show($id)
    {
        $thongbao = ThongbaoModel::with('nguoidung')->find($id);

        if (!$thongbao) {
            return response()->json([
                'success' => false,
                'message' => 'Thông báo không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $thongbao
        ]);
    }

    /**
     * Tạo mới một thông báo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nguoidung' => 'required|exists:nguoidung,id',
            'tieude'       => 'required|string|max:255',
            'noidung'      => 'required|string',
            'lienket'      => 'nullable|string',
            'trangthai'    => ['required', Rule::in(['Chưa đọc', 'Đã đọc', 'Tạm ẩn'])],
        ]);

        $thongbao = ThongbaoModel::create($validated);

        return response()->json([
            'success' => true,
            'data' => $thongbao
        ], 201);
    }

    /**
     * Cập nhật thông báo theo id
     */
    public function update(Request $request, $id)
    {
        $thongbao = ThongbaoModel::find($id);
        if (!$thongbao) {
            return response()->json([
                'success' => false,
                'message' => 'Thông báo không tồn tại'
            ], 404);
        }

        $validated = $request->validate([
            'id_nguoidung' => 'sometimes|exists:nguoidung,id',
            'tieude'       => 'sometimes|string|max:255',
            'noidung'      => 'sometimes|string',
            'lienket'      => 'nullable|string',
            'trangthai'    => ['sometimes', Rule::in(['Chưa đọc', 'Đã đọc', 'Tạm ẩn'])],
        ]);

        $thongbao->update($validated);

        return response()->json([
            'success' => true,
            'data' => $thongbao
        ]);
    }

    /**
     * Xóa thông báo theo id
     */
    public function destroy($id)
    {
        $thongbao = ThongbaoModel::find($id);
        if (!$thongbao) {
            return response()->json([
                'success' => false,
                'message' => 'Thông báo không tồn tại'
            ], 404);
        }

        $thongbao->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thông báo thành công'
        ]);
    }
}

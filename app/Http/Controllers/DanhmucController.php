<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use Illuminate\Http\Request;

class DanhmucController extends Controller
{
    // API lấy danh mục có phân trang
    public function index(Request $request)
    {
        // pageSize mặc định là 10, có thể truyền ?per_page=5
        $perPage = $request->get('per_page', 10);

        $danhmuc = Danhmuc::withCount('sanpham')->paginate($perPage);

        return response()->json($danhmuc);
    }

    // API tạo danh mục
    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'trangthai' => 'required|boolean',
        ]);

        $danhmuc = Danhmuc::create($data);

        return response()->json([
            'message' => 'Tạo danh mục thành công!',
            'data' => $danhmuc
        ], 201);
    }

    // API xem chi tiết
    public function show($id)
    {
        $danhmuc = Danhmuc::with('sanpham')->findOrFail($id);

        return response()->json($danhmuc);
    }

    // API cập nhật
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'trangthai' => 'required|boolean',
        ]);

        $danhmuc = Danhmuc::findOrFail($id);
        $danhmuc->update($data);

        return response()->json([
            'message' => 'Cập nhật thành công!',
            'data' => $danhmuc
        ]);
    }

    // API xóa
    public function destroy($id)
    {
        $danhmuc = Danhmuc::findOrFail($id);

        if ($danhmuc->sanpham()->count() > 0) {
            return response()->json([
                'message' => 'Không thể xóa! Danh mục này vẫn còn sản phẩm.'
            ], 400);
        }

        $danhmuc->delete();

        return response()->json([
            'message' => 'Xóa danh mục thành công!'
        ]);
    }
}

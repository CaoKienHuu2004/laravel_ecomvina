<?php

namespace App\Http\Controllers;

use App\Models\Danhmuc;
use Illuminate\Http\Request;

class DanhmucController extends Controller
{
    public function index()
    {
        $danhmuc = Danhmuc::withCount('sanpham')->get();
        return view('danhmuc', compact('danhmuc'));
    }

    public function create()
    {
        return view('taodanhmuc');
    }

    public function store(Request $request)
    {
        Danhmuc::create($request->only(['ten', 'trangthai']));
        return redirect()->route('danh-sach-danh-muc')->with('success', 'Tạo danh mục thành công!');
    }

    public function edit($id)
    {
        $danhmuc = Danhmuc::findOrFail($id);
        return view('suadanhmuc', compact('danhmuc'));
    }

    public function update(Request $request, $id)
    {
        $danhmuc = Danhmuc::findOrFail($id);
        $danhmuc->update($request->only(['ten', 'trangthai']));
        return redirect()->route('danh-sach-danh-muc')->with('success', 'Đã cập nhật thành công!');
    }

    public function destroy($id)
    {
        $danhmuc = Danhmuc::findOrFail($id);

        // Check nếu có sản phẩm thì không cho xóa
        if ($danhmuc->sanpham()->count() > 0) {
            return redirect()->route('danh-sach-danh-muc')
                ->with('error', 'Không thể xóa! Danh mục này vẫn còn sản phẩm.');
        }

        $danhmuc->delete();

        return redirect()->route('danh-sach-danh-muc')
            ->with('success', 'Xóa danh mục thành công!');
    }
}

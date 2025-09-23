<?php

namespace App\Http\Controllers;

use App\Models\Thuonghieu;
use Illuminate\Http\Request;

class ThuonghieuController extends Controller
{
    public function index()
    {
        $thuonghieu = Thuonghieu::withCount('sanpham')->get();
        return view('old-version/thuonghieu', compact('thuonghieu'));
    }

    public function create()
    {
        return view('old-version/taothuonghieu');
    }

    public function store(Request $request)
    {
        Thuonghieu::create($request->only(['ten', 'mota', 'trangthai']));
        return redirect()->route('danh-sach-thuong-hieu')->with('success', 'Tạo thương hiệu thành công!');
    }

    public function edit($id)
    {
        $thuonghieu = Thuonghieu::findOrFail($id);
        return view('old-version/suathuonghieu', compact('thuonghieu'));
    }

    public function update(Request $request, $id)
    {
        $thuonghieu = Thuonghieu::findOrFail($id);
        $thuonghieu->update($request->only(['ten', 'mota', 'trangthai']));
        return redirect()->route('danh-sach-thuong-hieu')->with('success', 'Đã cập nhật thành công!');
    }

    public function destroy($id)
    {
        $thuonghieu = Thuonghieu::findOrFail($id);

        // Check nếu có sản phẩm thì không cho xóa
        if ($thuonghieu->sanpham()->count() > 0) {
            return redirect()->route('danh-sach-thuong-hieu')
                ->with('error', 'Không thể xóa! thương hiệu này vẫn còn sản phẩm.');
        }

        $thuonghieu->delete();

        return redirect()->route('danh-sach-thuong-hieu')
            ->with('success', 'Xóa thương hiệu thành công!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Thuonghieu;
use App\Models\ThuongHieuModel;
use Illuminate\Http\Request;

class ThuonghieuController extends Controller
{
    public function index()
    {
        $thuonghieu = ThuongHieuModel::withCount('sanpham')->get();
        return view('thuonghieu.thuonghieu', compact('thuonghieu'));
    }

    public function create()
    {
        return view('thuonghieu.taothuonghieu');
    }

    public function store(Request $request)
    {
        ThuongHieuModel::create($request->only(['ten', 'mota', 'trangthai']));
        return redirect()->route('danh-sach-thuong-hieu')->with('success', 'Tạo thương hiệu thành công!');
    }

    public function edit($id)
    {
        $thuonghieu = ThuongHieuModel::findOrFail($id);
        return view('thuonghieu.suathuonghieu', compact('thuonghieu'));
    }

    public function update(Request $request, $id)
    {
        $thuonghieu = ThuongHieuModel::findOrFail($id);
        $thuonghieu->update($request->only(['ten', 'mota', 'trangthai']));
        return redirect()->route('danh-sach-thuong-hieu')->with('success', 'Đã cập nhật thành công!');
    }

    public function destroy($id)
    {
        $thuonghieu = ThuongHieuModel::findOrFail($id);

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donhang;

class DonhangController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Donhang::query();
        if ($request->filled('ma')) {
            $query->where('ma_don', 'like', '%' . $request->ma . '%');
        }
        if ($request->filled('sdt')) {
            $query->where('sdt', 'like', '%' . $request->sdt . '%');
        }
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        $donhangs = $query->orderByDesc('created_at')->paginate(15);
        return view('donhang.index', compact('donhangs'));
    }

    // Hiển thị form tạo đơn hàng
    public function create()
    {
        return view('donhang.create');
    }

    // Lưu đơn hàng mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_don' => 'required|string|max:50|unique:donhangs,ma_don',
            'ten_khach' => 'nullable|string|max:255',
            'sdt' => 'nullable|string|max:20',
            'tong_tien' => 'nullable|numeric|min:0',
            'trang_thai' => 'required|string',
            'thanh_toan' => 'required|string',
        ]);
        $donhang = Donhang::create($validated);
        return redirect()->route('danh-sach-don-hang')->with('success', 'Tạo đơn hàng thành công!');
    }

    // Hiển thị chi tiết đơn hàng
    public function show($id)
    {
        $donhang = Donhang::with('chitiets.sanpham')->findOrFail($id);
        return view('donhang.show', compact('donhang'));
    }

    // Hiển thị form chỉnh sửa đơn hàng
    public function edit($id)
    {
        $donhang = Donhang::findOrFail($id);
        return view('donhang.edit', compact('donhang'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id)
    {
        $donhang = Donhang::findOrFail($id);
        $validated = $request->validate([
            'ten_khach' => 'nullable|string|max:255',
            'sdt' => 'nullable|string|max:20',
            'tong_tien' => 'nullable|numeric|min:0',
            'trang_thai' => 'required|string',
            'thanh_toan' => 'required|string',
        ]);
        $donhang->update($validated);
        return redirect()->route('danh-sach-don-hang')->with('success', 'Cập nhật đơn hàng thành công!');
    }

    // Xóa đơn hàng (nếu cần)
    public function destroy($id)
    {
        $donhang = Donhang::findOrFail($id);
        $donhang->delete();
        return redirect()->route('danh-sach-don-hang')->with('success', 'Đã xóa đơn hàng!');
    }
}

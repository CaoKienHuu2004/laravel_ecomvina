<?php

namespace App\Http\Controllers;

use App\Models\PhuongthucModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PhuongThucController extends Controller
{
    /**
     * Hiển thị danh sách phương thức thanh toán
     */
    public function index()
    {
        // $phuongthucs = PhuongthucModel::orderBy('id', 'desc')->paginate(10); // phân trang 10 bản ghi serverside
        $phuongthucs = PhuongthucModel::all(); // dùng cliendetside vì nó có ít bản ghi

        return view('phuongthuc.index', compact('phuongthucs'));
    }

    /**
     * Hiển thị form tạo mới phương thức thanh toán
     */
    public function create()
    {
        // Lấy giá trị enum trạng thái để đưa vào select box trong form
        $trangthais = PhuongthucModel::getEnumValues('trangthai');

        return view('phuongthuc.create', compact('trangthais'));
    }

    /**
     * Lưu phương thức thanh toán mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'maphuongthuc' => 'nullable|string',
            'trangthai' => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        PhuongthucModel::create($request->only('ten', 'maphuongthuc', 'trangthai'));

        return redirect()->route('phuongthuc.index')->with('success', 'Thêm phương thức thanh toán thành công.');
    }

    /**
     * Hiển thị chi tiết 1 phương thức thanh toán
     */
    public function show($id)
    {
        $phuongthuc = PhuongthucModel::findOrFail($id);

        return view('phuongthuc.show', compact('phuongthuc'));
    }

    /**
     * Hiển thị form sửa phương thức thanh toán
     */
    public function edit($id)
    {
        $phuongthuc = PhuongthucModel::findOrFail($id);
        $trangthais = PhuongthucModel::getEnumValues('trangthai');

        return view('phuongthuc.edit', compact('phuongthuc', 'trangthais'));
    }

    /**
     * Cập nhật phương thức thanh toán
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'maphuongthuc' => 'nullable|string',
            'trangthai' => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        $phuongthuc = PhuongthucModel::findOrFail($id);
        $phuongthuc->update($request->only('ten', 'maphuongthuc', 'trangthai'));

        return redirect()->route('phuongthuc.index')->with('success', 'Cập nhật phương thức thanh toán thành công.');
    }

    /**
     * Xóa phương thức thanh toán
     */
    public function destroy($id)
    {
        $phuongthuc = PhuongthucModel::findOrFail($id);

        // Có thể kiểm tra ràng buộc (vd: có đơn hàng dùng phương thức này không)
        if ($phuongthuc->donhang()->count() > 0) {
            return redirect()->route('phuongthuc.index')->with('error', 'Không thể xóa phương thức đang được sử dụng trong đơn hàng.');
        }

        $phuongthuc->delete();

        return redirect()->route('phuongthuc.index')->with('success', 'Xóa phương thức thanh toán thành công.');
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\MagiamgiaModel;
use Illuminate\Http\Request;

class MagiamgiaController extends Controller
{
    // Hiển thị danh sách mã giảm giá
    public function index()
    {
        // Phân trang mã giảm giá (giới hạn 10 bản ghi mỗi trang)
        $magiamgia = MagiamgiaModel::paginate(10);
        return view('quanlygiamgia.index', compact('magiamgia'));
    }

    // Hiển thị form tạo mã giảm giá mới
    public function create()
    {
        return view('quanlygiamgia.create');
    }

    // Lưu mã giảm giá vào cơ sở dữ liệu
    public function store(Request $request)
    {
        // Validate dữ liệu từ form
        $request->validate([
            'magiamgia' => 'required|unique:magiamgia',
            'dieukien' => 'required|string',
            'mota' => 'required|string',
            'giatri' => 'required|numeric',
            'ngaybatdau' => 'required|date',
            'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau', // Ngày kết thúc không thể trước ngày bắt đầu
            'trangthai' => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        // Lưu vào cơ sở dữ liệu
        MagiamgiaModel::create($request->only([
            'magiamgia',
            'dieukien',
            'mota',
            'giatri',
            'ngaybatdau',
            'ngayketthuc',
            'trangthai',
        ])); // Chỉ lưu các trường đã được validate

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('danhsach.magiamgia')->with('success', 'Mã giảm giá đã được tạo thành công!');
    }


    // Hiển thị chi tiết mã giảm giá
    public function show($id)
    {
        $magiamgia = MagiamgiaModel::findOrFail($id);
        return view('quanlygiamgia.show', compact('magiamgia'));
    }

    // Cập nhật mã giảm giá
    public function edit($id)
    {
        $magiamgia = MagiamgiaModel::findOrFail($id);
        return view('quanlygiamgia.edit', compact('magiamgia'));
    }

    // Lưu cập nhật mã giảm giá
    public function update(Request $request, $id)
    {
        // Tìm mã giảm giá theo ID
        $magiamgia = MagiamgiaModel::findOrFail($id);

        // Cập nhật các trường thông qua dữ liệu từ request
        $magiamgia->update([
            'magiamgia' => $request->input('magiamgia'),
            'giatri' => $request->input('giatri'),
            'dieukien' => $request->input('dieukien'),
            'mota' => $request->input('mota'),
            'ngaybatdau' => $request->input('ngaybatdau'),
            'ngayketthuc' => $request->input('ngayketthuc'),
            'trangthai' => $request->input('trangthai'),
        ]);

        // Chuyển hướng hoặc trả về thông báo thành công
        return redirect()->route('danhsach.magiamgia')->with('success', 'Cập nhật mã giảm giá thành công!');
    }


    // Xóa mã giảm giá
    public function destroy($id)
    {
        $magiamgia = MagiamgiaModel::findOrFail($id);
        $magiamgia->delete();

        return redirect()->route('danhsach.magiamgia')->with('success', 'Mã giảm giá đã bị xóa!');
    }

    // Kiểm tra mã giảm giá còn hiệu lực hay không
    public function checkValid($id)
    {
        $magiamgia = MagiamgiaModel::findOrFail($id);
        return response()->json(['valid' => $magiamgia->isValid()]);
    }
}

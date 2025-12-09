<?php
namespace App\Http\Controllers;

use App\Models\MagiamgiaModel;
use Illuminate\Http\Request;

class MagiamgiaController extends Controller
{
    // Hiển thị danh sách mã giảm giá
    public function index()
    {
        $magiamgia = MagiamgiaModel::whereNotNull('dieukien')
            ->whereRaw('dieukien REGEXP "^[0-9]+$"')
            ->get();

        return view('quanlygiamgia.index', compact('magiamgia'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('quanlygiamgia.create');
    }

    // Lưu mới
    public function store(Request $request)
    {
        $request->validate([
            'magiamgia'      => 'required|unique:magiamgia,magiamgia',
            'dieukien'=> 'nullable|integer|min:0',
            'giatri'         => 'required|integer|min:1',
            'mota'           => 'nullable|string',
            'ngaybatdau'     => 'required|date',
            'ngayketthuc'    => 'required|date|after_or_equal:ngaybatdau',
            'trangthai'      => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        MagiamgiaModel::create([
            'magiamgia'        => $request->magiamgia,
            'dieukien'          =>      $request->dieukien ?? 0,
            'giatri'           => $request->giatri,
            'mota'             => $request->mota,
            'ngaybatdau'       => $request->ngaybatdau,
            'ngayketthuc'      => $request->ngayketthuc,
            'trangthai'        => $request->trangthai,
        ]);

        return redirect()->route('danhsach.magiamgia')->with('success', 'Tạo mã giảm giá thành công!');
    }

    // CHI TIẾT – ĐÃ SỬA ĐÚNG TÊN BIẾN TRUYỀN CHO BLADE
    public function show($id)
    {
        $magiamgia = MagiamgiaModel::where('id', $id)
            ->whereRaw('dieukien REGEXP "^[0-9]+$"')
            ->firstOrFail();

        return view('quanlygiamgia.show', compact('magiamgia'));
    }

    // Form sửa
    public function edit($id)
    {
        $magiamgia = MagiamgiaModel::where('id', $id)
            ->whereRaw('dieukien REGEXP "^[0-9]+$"')
            ->firstOrFail();

        return view('quanlygiamgia.edit', compact('magiamgia'));
    }

    // Lưu sửa
    public function update(Request $request, $id)
    {
        $magiamgia = MagiamgiaModel::findOrFail($id);

        $request->validate([
            'magiamgia'      => 'required|unique:magiamgia,magiamgia,'.$id,
            'dieukien'=> 'nullable|integer|min:0',
            'giatri'         => 'required|integer|min:1',
            'mota'           => 'nullable|string',
            'ngaybatdau'     => 'required|date',
            'ngayketthuc'    => 'required|date|after_or_equal:ngaybatdau',
            'trangthai'      => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        $magiamgia->update([
            'magiamgia'        => $request->magiamgia,
            'dieukien'  => $request->dieukien ?? 0,
            'giatri'           => $request->giatri,
            'mota'             => $request->mota,
            'ngaybatdau'       => $request->ngaybatdau,
            'ngayketthuc'      => $request->ngayketthuc,
            'trangthai'        => $request->trangthai,
        ]);

        return redirect()->route('danhsach.magiamgia')->with('success', 'Cập nhật thành công!');
    }

    // Xóa
    public function destroy($id)
    {
        $magiamgia = MagiamgiaModel::findOrFail($id);
        $magiamgia->delete();

        return redirect()->route('danhsach.magiamgia')->with('success', 'Xóa mã giảm giá thành công!');
    }

    // Kiểm tra mã có hiệu lực không (giữ nguyên của mày)
    public function checkValid($id)
    {
        $magiamgia = MagiamgiaModel::findOrFail($id);
        return response()->json(['valid' => $magiamgia->isValid()]);
    }
}

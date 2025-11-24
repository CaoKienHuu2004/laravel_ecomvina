<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThongbaoModel;
use App\Models\NguoidungModel;

class ThongBaoController extends Controller
{
    /**
     * Danh sách thông báo
     */
    public function index()
    {
        $thongbaos = ThongbaoModel::with('nguoidung')
            ->whereHas('nguoidung', function($query) {
                $query->where('vaitro', '!=', 'admin');
            })
            ->orderByDesc('id')
            ->get();

        $thongbaos_admin = ThongbaoModel::with('nguoidung')
            ->whereHas('nguoidung', function($query) {
                $query->where('vaitro', 'admin');
            })
            ->orderByDesc('id')
            ->get();

        return view('thongbao.index', compact('thongbaos','thongbaos_admin'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $nguoidungs = NguoidungModel::where('vaitro', '!=', 'admin')->get();
        $trangthais = ThongbaoModel::getEnumValues('trangthai');

        return view('thongbao.create', compact('nguoidungs', 'trangthais'));
    }

    /**
     * Lưu thông báo mới
     */
    public function store(Request $request)
    {
        $enumTrangthai = ThongbaoModel::getEnumValues('trangthai');

        $validated = $request->validate([
            'id_nguoidung' => ['required', 'exists:nguoidung,id'],
            'tieude'       => ['required', 'string'],
            'noidung'      => ['required', 'string'],
            'lienket'      => ['nullable', 'string'],
            'trangthai'    => ['required', 'in:' . implode(',', $enumTrangthai)],
        ]);

        ThongbaoModel::create($validated);

        return redirect()->route('thongbao.index')
            ->with('success', 'Tạo thông báo thành công');
    }

    /**
     * Xem chi tiết thông báo
     */
    public function show($id)
    {
        $thongbao = ThongbaoModel::with('nguoidung')->findOrFail($id);

        return view('thongbao.show', compact('thongbao'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $thongbao = ThongbaoModel::findOrFail($id);
        $nguoidungs = NguoidungModel::where('vaitro', '!=', 'admin')->get();
        $trangthais = ThongbaoModel::getEnumValues('trangthai');

        return view('thongbao.edit', compact('thongbao', 'nguoidungs', 'trangthais'));
    }

    /**
     * Cập nhật thông báo
     */
    public function update(Request $request, $id)
    {
        $thongbao = ThongbaoModel::findOrFail($id);
        $enumTrangthai = ThongbaoModel::getEnumValues('trangthai');

        $validated = $request->validate([
            'id_nguoidung' => ['required', 'exists:nguoidung,id'],
            'tieude'       => ['required', 'string'],
            'noidung'      => ['required', 'string'],
            'lienket'      => ['nullable', 'string'],
            'trangthai'    => ['required', 'in:' . implode(',', $enumTrangthai)],
        ]);

        $thongbao->update($validated);

        return redirect()->route('thongbao.index')
            ->with('success', 'Cập nhật thông báo thành công');
    }

    /**
     * Xóa thông báo
     */
    public function destroy($id)
    {
        $thongbao = ThongbaoModel::findOrFail($id);
        $thongbao->delete();

        return redirect()->route('thongbao.index')
            ->with('success', 'Xóa thông báo thành công');
    }
}

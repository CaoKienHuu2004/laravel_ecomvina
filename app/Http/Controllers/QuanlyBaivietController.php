<?php

namespace App\Http\Controllers;

use App\Models\QuanLyBaiVietModel;
use App\Models\NguoidungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QuanLyBaivietController extends Controller
{
    public function index(Request $request)
    {
        $query = QuanLyBaiVietModel::with('nguoidung');

        if ($request->filled('tieude')) {
            $query->where('tieude', 'like', '%' . $request->tieude . '%');
        }

        if ($request->filled('trangthai')) {
            $query->where('trangthai', $request->trangthai);
        }

        $baiviets = $query->orderByDesc('id')->paginate(10);

        return view('quanlybaiviet.index', compact('baiviets'));
    }

    public function create()
    {
        $nguoiDung = NguoidungModel::all();
        return view('quanlybaiviet.create', compact('nguoiDung'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieude'       => 'required|string|max:255',
            'noidung'      => 'required',
            'id_nguoidung' => 'required|exists:nguoidung,id',
            'hinhanh'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'trangthai'    => 'nullable|in:Hiển thị,Tạm ẩn',   // ĐÃ SỬA ĐÚNG VỚI DB
        ]);

        // Tạo slug duy nhất
        $slug = Str::slug($request->tieude);
        $originalSlug = $slug;
        $i = 1;
        while (QuanLyBaiVietModel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        // Upload ảnh
        $hinhanh = null;
        if ($request->hasFile('hinhanh')) {
            $hinhanh = $request->file('hinhanh')->store('baiviet', 'public');
        }

        QuanLyBaiVietModel::create([
            'id_nguoidung' => $request->id_nguoidung,
            'tieude'       => $request->tieude,
            'slug'         => $slug,
            'noidung'      => $request->noidung,
            'hinhanh'      => $hinhanh,
            'luotxem'      => 0,
            'trangthai'    => $request->trangthai ?? 'Hiển thị',   // ĐÃ SỬA ĐÚNG VỚI DB
        ]);

        return redirect()->route('baiviet.index')->with('success', 'Tạo bài viết thành công!');
    }

    public function show($id)
    {
        $baiviet = QuanLyBaiVietModel::findOrFail($id);
        return view('quanlybaiviet.show', compact('baiviet'));
    }

    public function edit($id)
    {
        $baiviet   = QuanLyBaiVietModel::findOrFail($id);
        $nguoiDung = NguoidungModel::all();
        return view('quanlybaiviet.edit', compact('baiviet', 'nguoiDung'));
    }

    public function update(Request $request, $id)
    {
        $baiviet = QuanLyBaiVietModel::findOrFail($id);

        $request->validate([
            'tieude'       => 'required|string|max:255',
            'noidung'      => 'required',
            'id_nguoidung' => 'required|exists:nguoidung,id',
            'trangthai'    => 'required|in:Hiển thị,Tạm ẩn',
            'hinhanh'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ]);

        $slug = Str::slug($request->tieude);
        $originalSlug = $slug;
        $i = 1;
        while (QuanLyBaiVietModel::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        $data = [
            'tieude'       => $request->tieude,
            'slug'         => $slug,
            'noidung'      => $request->noidung,
            'id_nguoidung' => $request->id_nguoidung,
            'trangthai'    => $request->trangthai,
        ];

        if ($request->hasFile('hinhanh')) {
            if ($baiviet->hinhanh && Storage::disk('public')->exists($baiviet->hinhanh)) {
                Storage::disk('public')->delete($baiviet->hinhanh);
            }
            $data['hinhanh'] = $request->file('hinhanh')->store('baiviet', 'public');
        }

        $baiviet->update($data);

        return redirect()->route('baiviet.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy($id)
    {
        $baiviet = QuanLyBaiVietModel::findOrFail($id);

        if ($baiviet->hinhanh && Storage::disk('public')->exists($baiviet->hinhanh)) {
            Storage::disk('public')->delete($baiviet->hinhanh);
        }

        $baiviet->delete();

        return redirect()->route('baiviet.index')->with('success', 'Xóa bài viết thành công!');
    }
}

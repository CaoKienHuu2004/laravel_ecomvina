<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nguoidung;
use App\Models\Diachi;

class NguoidungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $danhsach = Nguoidung::with('diachi')->where('vaitro', 'user')
            ->get();
        $diachi = Diachi::all();

        return view("khachhang.index", compact("danhsach","diachi"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('khachhang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // Nguoidung::create($request->only(['ten', 'mota', 'trangthai']));
        // return redirect()->route('danh-sach-thuong-hieu')->with('success', 'Tạo thương hiệu thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // $danhmuc = Nguoidung::findOrFail($id);
        // return view('suadanhmuc', compact('danhmuc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // $danhmuc = Nguoidung::findOrFail($id);
        // $danhmuc->update($request->only(['ten', 'trangthai']));
        // return redirect()->route('danh-sach-danh-muc')->with('success', 'Đã cập nhật thành công!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        //  $danhmuc = Nguoidung::findOrFail($id);
        // $danhmuc->update($request->only(['ten', 'trangthai']));
        // return redirect()->route('danh-sach-danh-muc')->with('success', 'Đã cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        // $danhmuc = Nguoidung::findOrFail($id);

        // // Check nếu có sản phẩm thì không cho xóa
        // if ($danhmuc->sanpham()->count() > 0) {
        //     return redirect()->route('danh-sach-danh-muc')
        //         ->with('error', 'Không thể xóa! Danh mục này vẫn còn sản phẩm.');
        // }

        // $danhmuc->delete();

        // return redirect()->route('danh-sach-danh-muc')
        //     ->with('success', 'Xóa danh mục thành công!');
    }
}

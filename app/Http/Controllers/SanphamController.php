<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sanpham;
use Illuminate\Http\Request;

class SanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $query = Sanpham::with(['bienThe.loaiBienThe', 'anhSanPham']);

        // // Filter theo tên
        // if ($request->filled('ten')) {
        //     $query->where('ten', 'like', '%' . $request->ten . '%');
        // }

        // // Filter theo giá (dùng bảng biến thể)
        // if ($request->filled('gia_min') || $request->filled('gia_max')) {
        //     $query->whereHas('bienThe', function ($q) use ($request) {
        //         if ($request->gia_min) {
        //             $q->where('gia', '>=', $request->gia_min);
        //         }
        //         if ($request->gia_max) {
        //             $q->where('gia', '<=', $request->gia_max);
        //         }
        //     });
        // }

        // // Filter theo thương hiệu
        // if ($request->filled('id_thuonghieu')) {
        //     $query->where('id_thuonghieu', $request->id_thuonghieu);
        // }

        // // Filter theo trạng thái
        // if ($request->filled('trangthai')) {
        //     $query->where('trangthai', $request->trangthai);
        // }

        // Lấy toàn bộ sản phẩm kèm quan hệ
        $sanpham = Sanpham::with(['bienThe.loaiBienThe', 'anhSanPham', 'danhmuc', 'thuonghieu'])->get();

        // Render view với dữ liệu
        return view('sanpham', compact('sanpham'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

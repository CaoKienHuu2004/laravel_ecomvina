<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sanpham;
use App\Models\Thuonghieu;
use App\Models\Danhmuc;
use App\Models\Bienthesp;
use App\Models\Loaibienthe;
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

    public function create()
    {
        $thuonghieus = Thuonghieu::all();
        $danhmucs = Danhmuc::all();
        $loaibienthes = Loaibienthe::all();

        return view('taosanpham', compact('thuonghieus', 'danhmucs', 'loaibienthes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'tensp' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'bienthe.*.id_tenloai' => 'required|string',
            'bienthe.*.gia' => 'required|numeric|min:0',
            'bienthe.*.soluong' => 'required|integer|min:1',
        ], [
            'tensp.required' => 'Tên sản phẩm không được bỏ trống',
            'bienthe.*.id_tenloai.required' => 'Loại biến thể không được bỏ trống',
            'bienthe.*.gia.required' => 'Giá biến thể bắt buộc nhập',
            'bienthe.*.soluong.required' => 'Số lượng biến thể bắt buộc nhập',
        ]);

        // Sau khi validate ok thì xử lý lưu
        // Ví dụ tạm thời chỉ in ra cho bạn thấy
        // dd($validated);

    // Tạo sản phẩm
    $sp = Sanpham::create([
        'ten' => $validated['ten'],
        'thuonghieu_id' => $validated['thuonghieu_id'],
        'trangthai' => 1,
    ]);

    // Gắn danh mục
    if (!empty($validated['danhmuc_ids'])) {
        $sp->danhmuc()->attach($validated['danhmuc_ids']);
    }

    // Upload ảnh
    if ($request->hasFile('anh')) {
        foreach ($request->file('anh') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/product'), $filename);

            $sp->anhSanPham()->create([
                'media' => $filename,
            ]);
        }
    }

    // Thêm biến thể
    foreach ($validated['bienthe'] as $bt) {
        $sp->bienThe()->create([
            'id_tenloai' => $bt['id_tenloai'],
            'gia' => $bt['gia'],
            'soluong' => $bt['soluong'],
            'trangthai' => 1,
            'uutien' => 0,
        ]);
        }

    return redirect()->route('sanpham')->with('success', 'Thêm sản phẩm thành công!');
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

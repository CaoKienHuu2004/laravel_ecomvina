<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sanpham;
use App\Models\Thuonghieu;
use App\Models\Danhmuc;
use App\Models\Bienthesp;
use App\Models\Loaibienthe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $request->validate([
            'tensp'        => 'required|string|max:255',
            'id_danhmuc'   => 'required|integer',
            'id_thuonghieu'=> 'required|integer',
            'xuatxu'   => 'nullable|string|max:255',
            'sanxuat'  => 'nullable|string|max:255',
            'trangthai'    => 'required|boolean',
            'mo_ta'        => 'nullable|string',
            'anhsanpham.*'=> 'image|mimes:jpg,jpeg,png|max:2048',
            'bienthe.*.gia'=> 'nullable|numeric|min:0',
            'bienthe.*.soluong' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Tạo sản phẩm
            $sanpham = Sanpham::create([
                'tensp'        => $request->tensp,
                'id_danhmuc'   => $request->id_danhmuc,
                'id_thuonghieu'=> $request->id_thuonghieu,
                'xuatxu'   => $request->noi_xuatxu,
                'sanxuat'  => $request->noi_sanxuat,
                'mediaurl'      => $request->mediaurl ?? 0,
                'trangthai'    => $request->trangthai,
                'mota'         => $request->mo_ta,
            ]);

            // Upload ảnh sản phẩm
            if($request->hasFile('anh_sanpham')){
                foreach($request->file('anh_sanpham') as $file){
                    $path = $file->store('uploads/sanpham', 'public');
                    anhSanPham::create([
                        'id_sanpham' => $sanpham->id,
                        'duong_dan'  => $path,
                    ]);
                }
            }

            // Lưu biến thể
            if($request->bienthe){
                foreach($request->bienthe as $bt){
                    if(!empty($bt['id_tenloai']) && !empty($bt['gia'])){
                        Bienthesp::create([
                            'id_sanpham' => $sanpham->id,
                            'id_tenloai' => $bt['id_tenloai'],
                            'gia'        => $bt['gia'],
                            'soluong'    => $bt['soluong'] ?? 0,
                            'trangthai'  => 1,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('sanpham.index')->with('success','Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi: '.$e->getMessage()]);
        }
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

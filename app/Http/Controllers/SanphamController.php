<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anhsp;
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
        $request->validate(
            [
                'tensp'        => 'required|string|max:255',
                'id_danhmuc'   => 'required|integer',
                'id_thuonghieu' => 'required|integer',
                'xuatxu'   => 'required|string|max:255',
                'sanxuat'  => 'nullable|string|max:255',
                'mo_ta'        => 'required|string',
                'anhsanpham.*' => 'image',
                'bienthe.*.gia' => 'required|numeric|min:0',
                'bienthe.*.soluong' => 'required|integer|min:0',
            ],
            [
                // Sản phẩm
                'tensp.required'         => 'Hãy nhập tên sản phẩm !',
                'tensp.string'           => 'Tên sản phẩm phải là chuỗi ký tự',
                'tensp.max'              => 'Tên sản phẩm không quá 255 ký tự',

                'id_danhmuc.required'    => 'Vui lòng chọn danh mục',
                'id_danhmuc.integer'     => 'Danh mục không hợp lệ',

                'id_thuonghieu.required' => 'Vui lòng chọn thương hiệu',
                'id_thuonghieu.integer'  => 'Thương hiệu không hợp lệ',

                'xuatxu.required'        => 'Vui lòng nhập xuất xứ',
                'xuatxu.string'          => 'Xuất xứ phải là chuỗi ký tự',
                'xuatxu.max'             => 'Xuất xứ không quá 255 ký tự',

                'sanxuat.string'         => 'Tên nơi sản xuất phải là chuỗi ký tự',
                'sanxuat.max'            => 'Tên nơi sản xuất không quá 255 ký tự',

                'mo_ta.required'         => 'Vui lòng nhập mô tả sản phẩm',
                'mo_ta.string'           => 'Mô tả sản phẩm không hợp lệ',

                // Ảnh
                'anhsanpham.*.image'     => 'Mỗi file tải lên phải là hình ảnh',

                // Biến thể
                'bienthe.*.gia.required' => 'Vui lòng nhập giá cho biến thể',
                'bienthe.*.gia.numeric'  => 'Giá biến thể phải là số',
                'bienthe.*.gia.min'      => 'Giá biến thể không được nhỏ hơn 0',

                'bienthe.*.soluong.required' => 'Vui lòng nhập số lượng cho biến thể',
                'bienthe.*.soluong.integer'  => 'Số lượng biến thể phải là số nguyên',
                'bienthe.*.soluong.min'      => 'Số lượng biến thể không được nhỏ hơn 0',
            ]
        );

        DB::beginTransaction();
        try {
            // Tạo sản phẩm
            $sanpham = Sanpham::create([
                'ten'        => $request->tensp,
                'id_thuonghieu' => $request->id_thuonghieu,
                'xuatxu'   => $request->xuatxu,
                'sanxuat'  => $request->sanxuat,
                'mediaurl'      => $request->mediaurl,
                'trangthai'    => $request->trangthai,
                'mota'         => $request->mo_ta,
            ]);

            $sanpham->danhmuc()->attach($request->id_danhmuc);

            // Upload ảnh sản phẩm
            if ($request->hasFile('anhsanpham')) {
                foreach ($request->file('anhsanpham') as $file) {
                    // Tạo tên file mới để tránh trùng
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    // Di chuyển file vào thư mục public/img/product
                    $file->move(public_path('img/product'), $fileName);

                    // Lưu thông tin vào DB
                    Anhsp::create([
                        'id_sanpham' => $sanpham->id,
                        'media'      => $fileName,
                    ]);
                }
            }


            // Lưu biến thể
            if ($request->bienthe) {
                foreach ($request->bienthe as $bt) {
                    if (!empty($bt['id_tenloai']) && !empty($bt['gia'])) {
                        Bienthesp::create([
                            'id_sanpham' => $sanpham->id,
                            'id_tenloai' => $bt['id_tenloai'],
                            'gia'        => $bt['gia'],
                            'soluong'    => $bt['soluong'] ?? 0,
                            'trangthai'  => 0,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('danh-sach')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi: ' . $e->getMessage()]);
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

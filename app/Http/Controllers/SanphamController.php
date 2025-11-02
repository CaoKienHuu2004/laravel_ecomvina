<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anhsp;
use App\Models\Sanpham;
use App\Models\Thuonghieu;
use App\Models\Danhmuc;
use App\Models\Bienthesp;
use App\Models\DanhmucModel;
use App\Models\Loaibienthe;
use App\Models\SanphamModel;
use App\Models\ThongTinNguoiBanHang;
use App\Models\ThuongHieuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SanphamModel::with('bienthe', 'danhmuc','anhsanpham','thuonghieu','bienthe.chitietdonhang');

        // Filter theo thương hiệu
        if ($request->filled('thuonghieu')) {
            $query->where('id_thuonghieu', $request->thuonghieu);
        }

        // Filter theo danh mục (many-to-many)
        if ($request->filled('danhmuc')) {
            $query->whereHas('danhmuc', function ($q) use ($request) {
                $q->where('id_danhmuc', $request->danhmuc);
            });
        }

        // Filter giá (dựa trên bảng bienThe_sp)
        if ($request->filled('gia_min') && $request->filled('gia_max')) {
            $query->whereHas('bienThe', function ($q) use ($request) {
                $q->whereBetween('gia', [$request->gia_min, $request->gia_max]);
            });
        } elseif ($request->filled('gia_min')) {
            $query->whereHas('bienThe', function ($q) use ($request) {
                $q->where('gia', '>=', $request->gia_min);
            });
        } elseif ($request->filled('gia_max')) {
            $query->whereHas('bienThe', function ($q) use ($request) {
                $q->where('gia', '<=', $request->gia_max);
            });
        }

        // Lấy kết quả
        $sanphams = $query->distinct()
                      ->orderBy('updated_at', 'desc')
                      ->get();

        // Lấy thêm list danh mục & thương hiệu để render filter
        $thuonghieus = ThuongHieuModel::all();
        $danhmucs = DanhmucModel::all();

        return view('sanpham/sanpham', compact('sanphams', 'thuonghieus', 'danhmucs'));

        // // Lấy toàn bộ sản phẩm kèm quan hệ
        // $sanpham = Sanpham::with(['bienThe.loaiBienThe', 'anhSanPham', 'danhmuc', 'thuonghieu'])->get();

        // // Render view với dữ liệu
        // return view('sanpham', compact('sanpham'));
    }

    public function create()
    {
        $cuaHang = ThongTinNguoiBanHang::all();
        $danhmucs = Danhmuc::all();
        $loaibienthes = Loaibienthe::all();

        return view('sanpham/taosanpham', compact('cuaHang', 'danhmucs', 'loaibienthes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'tensp'        => 'required|string|max:255',
                'id_danhmuc'   => 'required|array',
                'id_danhmuc.*' => 'integer|exists:danh_muc,id',
                'id_cuahang' => 'required|integer',
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

                'id_danhmuc.required'    => 'Vui lòng chọn ít nhất một danh mục',
                'id_danhmuc.array'       => 'Danh mục không hợp lệ',
                'id_danhmuc.*.integer'   => 'Danh mục không hợp lệ',
                'id_danhmuc.*.exists'    => 'Danh mục đã chọn không tồn tại',

                'id_cuahang.required' => 'Vui lòng chọn cửa hàng',
                'id_cuahang.integer'  => 'Cửa Hàng không hợp lệ',

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

            if(!empty($request->mediaurl)){
                $sanpham = Sanpham::create([
                    'ten'        => $request->tensp,
                    'id_cuahang' => $request->id_cuahang,
                    'xuatxu'   => $request->xuatxu,
                    'sanxuat'  => $request->sanxuat,
                    'mediaurl' => $request->mediaurl,
                    'trangthai'    => $request->trangthai,
                    'mota'         => $request->mo_ta,
                ]);
            }else{
                $sanpham = Sanpham::create([
                    'ten'        => $request->tensp,
                    'id_cuahang' => $request->id_cuahang,
                    'xuatxu'   => $request->xuatxu,
                    'sanxuat'  => $request->sanxuat,
                    'trangthai'    => $request->trangthai,
                    'mota'         => $request->mo_ta,
                ]);
            }

            // $sanpham->danhmuc()->attach($request->id_danhmuc);
            if ($request->id_danhmuc) {
                $sanpham->danhmuc()->attach($request->id_danhmuc);
            }

            if ($request->hasFile('anhsanpham')) {
                $slugName = Str::slug($request->tensp);
                $i = 1;

                foreach ($request->file('anhsanpham') as $file) {

                    $extension = $file->getClientOriginalExtension();

                    $filename = "{$slugName}-{$i}-" . time() . ".{$extension}";
                    $path = $file->storeAs('uploads/anh_sanpham/media', $filename, 'public');
                    $file->storeAs('images/anh_sanpham/media', $filename, 'nextjs_assets');
                    $url = asset('storage/' . $path);

                    // Ghi vào database
                    Anhsp::create([
                        'id_sanpham' => $sanpham->id,
                        'media'      => $path,
                    ]);

                    $i++;
                }
            }
            foreach ($request->bienthe as $bt) {
                if (!empty($bt['id_tenloai']) && !empty($bt['gia'])) {

                    // Nếu chọn từ select => id là số
                    if (is_numeric($bt['id_tenloai'])) {
                        $id_tenloai = $bt['id_tenloai'];
                    } else {
                        // Chuẩn hóa text (trim + strtolower cho chắc)
                        $tenLoai = trim($bt['id_tenloai']);

                        // Tìm trong DB xem đã có chưa
                        $existingLoai = Loaibienthe::whereRaw('LOWER(ten) = ?', [strtolower($tenLoai)])->first();

                        if ($existingLoai) {
                            // Nếu đã có -> lấy id cũ
                            $id_tenloai = $existingLoai->id;
                        } else {
                            // Nếu chưa có -> tạo mới
                            $newLoai = Loaibienthe::create(['ten' => $tenLoai]);
                            $id_tenloai = $newLoai->id;
                        }
                    }

                    // Tạo biến thể sản phẩm
                    Bienthesp::create([
                        'id_sanpham' => $sanpham->id,
                        'id_tenloai' => $id_tenloai,
                        'gia'        => $bt['gia'],
                        'soluong'    => $bt['soluong'] ?? 1,
                        'uutien'    => $bt['uutien'] ?? 1,
                    ]);
                }
            }


            // // Lưu biến thể
            // if ($request->bienthe) {
            //     foreach ($request->bienthe as $bt) {
            //         if (!empty($bt['id_tenloai']) && !empty($bt['gia'])) {
            //             Bienthesp::create([
            //                 'id_sanpham' => $sanpham->id,
            //                 'id_tenloai' => $bt['id_tenloai'],
            //                 'gia'        => $bt['gia'],
            //                 'soluong'    => $bt['soluong'] ?? 0,
            //                 'trangthai'  => 0,
            //             ]);
            //         }
            //     }
            // }

            DB::commit();
            return redirect()->route('san-pham.danh-sach')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi: ' . $e->getMessage()]);
        }
    }

    public function edit(Request $request, $id)
    {
        $sanpham = Sanpham::with(['bienthe', 'anhsanpham', 'danhmuc'])->findOrFail($id);
        $danhmucs = DanhMuc::all();
        $cuaHang = ThongTinNguoiBanHang::all();
        $loaibienthes = LoaiBienThe::all();

        return view('suasanpham', compact('sanpham', 'danhmucs', 'cuaHang', 'loaibienthes'));
    }

    public function update(Request $request, $id)
    {
        $sanpham = Sanpham::with(['bienthe', 'anhsanpham'])->findOrFail($id);

        // Update thông tin sản phẩm
        $sanpham->ten = $request->ten;
        $sanpham->id_thuonghieu = $request->id_thuonghieu;
        $sanpham->xuatxu = $request->xuatxu;
        $sanpham->sanxuat = $request->sanxuat;
        $sanpham->mediaurl = $request->mediaurl;
        $sanpham->trangthai = $request->trangthai;
        $sanpham->mota = $request->mo_ta;
        $sanpham->save();

        // Nếu sản phẩm có nhiều danh mục (many-to-many)
        if ($request->id_danhmuc) {
            $sanpham->danhmuc()->sync($request->id_danhmuc); // sync để giữ những danh mục mới và remove danh mục bị bỏ
        }

        // --- Xử lý biến thể ---
        $bienthesInput = $request->bienthe ?? [];

        // 1. Lấy tất cả biến thể hiện tại
        $existingIds = $sanpham->bienthe->pluck('id')->toArray();
        $newIds = [];

        foreach ($bienthesInput as $i => $bt) {
            // Xử lý id_tenloai: có thể là id (int) hoặc tên mới (string)
            $idTenLoai = null;
            if (is_numeric($bt['id_tenloai'])) {
                // chọn có sẵn
                $idTenLoai = $bt['id_tenloai'];
            } else {
                // nhập mới (string)
                $tenLoai = trim($bt['id_tenloai']);
                if ($tenLoai != '') {
                    $loai = LoaiBienThe::firstOrCreate(
                        ['ten' => $tenLoai],
                        ['ten' => $tenLoai] // nếu chưa có thì tạo mới
                    );
                    $idTenLoai = $loai->id;
                }
            }

            if (!$idTenLoai) continue; // bỏ qua nếu không hợp lệ

            if (isset($bt['id'])) {
                // Nếu biến thể đã có id -> update
                $b = Bienthesp::find($bt['id']);
                if ($b) {
                    $b->id_tenloai = $idTenLoai;
                    $b->gia = $bt['gia'];
                    $b->soluong = $bt['soluong'];
                    $b->save();
                    $newIds[] = $b->id;
                }
            } else {
                // Thêm biến thể mới
                $b = new Bienthesp();
                $b->id_sanpham = $sanpham->id;
                $b->id_tenloai = $idTenLoai;
                $b->gia = $bt['gia'];
                $b->soluong = $bt['soluong'];
                $b->save();
                $newIds[] = $b->id;
            }
        }

        // Xóa biến thể cũ mà bị remove
        $toDelete = array_diff($existingIds, $newIds);
        if (!empty($toDelete)) {
            Bienthesp::destroy($toDelete);
        }

        // --- Xử lý ảnh ---
        $deletedImages = $request->deleted_image_ids ?? []; // những ảnh user muốn xóa
        foreach ($sanpham->anhsanpham as $anh) {
            if (in_array($anh->id, $deletedImages)) {
                // kiểm tra file tồn tại trước khi xóa
                $filePath = public_path('img/product/' . $anh->media);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                // xóa bản ghi DB
                $anh->delete();
            }
        }

        if ($request->hasFile('anhsanpham')) {
            $slugName = Str::slug($request->ten);

            foreach ($request->file('anhsanpham') as $i => $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = $slugName . '-' . ($i + 1) . '.' . $extension;

                // Lưu file trực tiếp vào public/img/product
                $file->move(public_path('img/product'), $filename);

                // Lưu tên file vào DB
                $anh = new Anhsp();
                $anh->id_sanpham = $sanpham->id;
                $anh->media = $filename;
                $anh->save();
            }
        }


        return redirect()->route('danh-sach')->with('success', 'Cập nhật sản phẩm thành công!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sanpham = Sanpham::findOrFail($id);

        DB::beginTransaction();
        try {
            // Xoá ảnh sản phẩm
            if ($sanpham->anhSanPham && $sanpham->anhSanPham->count()) {
                foreach ($sanpham->anhSanPham as $anh) {
                    $filePath = public_path('img/product/' . $anh->media);
                    if (file_exists($filePath)) {
                        unlink($filePath); // xoá file thật
                    }
                    $anh->delete(); // xoá record DB
                }
            }

            // Xoá biến thể
            Bienthesp::where('id_sanpham', $sanpham->id)->delete();

            // Xoá liên kết danh mục (nếu có belongsToMany)
            $sanpham->danhmuc()->detach();

            // Xoá sản phẩm chính
            $sanpham->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Xoá sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi khi xoá: ' . $e->getMessage());
        }
    }

    public function show($slug,$id)
    {
        $sanpham = SanPham::with(['anhsanpham', 'danhmuc', 'bienthe.loaiBienThe'])->findOrFail($id);

        // kiểm tra slug có đúng không, nếu không thì redirect về slug đúng
        $correctSlug = \Illuminate\Support\Str::slug($sanpham->ten);
        if ($slug !== $correctSlug) {
            return redirect()->route('sanpham.show', [
                'id' => $sanpham->id,
                'slug' => $correctSlug
            ]);
        }

        return view('chitietsanpham', compact('sanpham'));
    }
}

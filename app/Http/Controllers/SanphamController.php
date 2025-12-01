<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anhsp;
use App\Models\BientheModel;
use App\Models\Sanpham;
use App\Models\Danhmuc;
use App\Models\Bienthesp;
use App\Models\DanhmucModel;
use App\Models\HinhanhsanphamModel;
use App\Models\Loaibienthe;
use App\Models\LoaibientheModel;
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
        $query = SanphamModel::with('bienthe', 'danhmuc','thuonghieu','bienthe.chitietdonhang');

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
        if ($request->filled('giagoc')){
            $query->whereHas('bienthe', function ($q) use ($request) {
                $q->whereBetween('giagoc', [$request->giagoc]);
            });
        }

        // Lấy kết quả
        $sanphams = $query->distinct()
                      ->orderBy('updated_at', 'desc')
                      ->get();

        // Lấy thêm list danh mục & thương hiệu để render filter
        $thuonghieus = ThuongHieuModel::all();
        $danhmucs = DanhmucModel::all();
        $bienthes = BientheModel::all();
        $hinhanhsanphams = HinhanhsanphamModel::all();

        return view('sanpham/sanpham', compact('bienthes', 'sanphams', 'thuonghieus', 'danhmucs'));

        // // Lấy toàn bộ sản phẩm kèm quan hệ
        // $sanpham = Sanpham::with(['bienThe.loaiBienThe', 'anhSanPham', 'danhmuc', 'thuonghieu'])->get();

        // // Render view với dữ liệu
        // return view('sanpham', compact('sanpham'));
    }

    public function create()
{   
    $sanpham = SanphamModel::all();
    $cuaHang = ThuongHieuModel::all();  
    $danhmucs = DanhmucModel::all();
    $loaibienthes = LoaibientheModel::all();
    $hinhanhsanphams = HinhanhsanphamModel::all();

    
    return view('sanpham.taosanpham', compact('cuaHang', 'danhmucs', 'loaibienthes', 'sanpham', 'hinhanhsanphams'));
}

    public function show($slug,$id)
    {
        $sanpham = SanphamModel::with(['bienthe', 'hinhanhsanpham', 'danhmuc', 'bienthe.loaibienthe'])->findOrFail($id);

        // kiểm tra slug có đúng không, nếu không thì redirect về slug đúng
        $correctSlug = \Illuminate\Support\Str::slug($sanpham->ten);
        if ($slug !== $correctSlug) {
            return redirect()->route('sanpham.show', [
                'id' => $sanpham->id,
                'slug' => $correctSlug
            ]);
        }
        return view('sanpham/show', compact('sanpham'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function save(Request $request)
    {
        // Validate dữ liệu
        $request->validate(
            [
                'tensp'        => 'required|string|max:255',
                'id_danhmuc'   => 'required|array',
                'id_danhmuc.*' => 'integer|exists:danhmuc,id',
                'xuatxu'   => 'required|string|max:255',
                'sanxuat'  => 'nullable|string|max:255',
                'mo_ta'        => 'required|string',
                'anhsanpham.*' => 'image',
                'bienthe.*.gia' => 'required|numeric|min:0',
                'bienthe.*.soluong' => 'required|integer|min:0',
                'id_thuonghieu' => 'required|integer|exists:thuonghieu,id',
                'trangthai' => 'required|string|max:50',
            ],
            [
                // Các thông báo lỗi cho sản phẩm và biến thể
                'tensp.required'         => 'Hãy nhập tên sản phẩm !',
                'tensp.string'           => 'Tên sản phẩm phải là chuỗi ký tự',
                'tensp.max'              => 'Tên sản phẩm không quá 255 ký tự',

                'id_danhmuc.required'    => 'Vui lòng chọn ít nhất một danh mục',
                'id_danhmuc.array'       => 'Danh mục không hợp lệ',
                'id_danhmuc.*.integer'   => 'Danh mục không hợp lệ',
                'id_danhmuc.*.exists'    => 'Danh mục đã chọn không tồn tại',

                'id_thuonghieu.required' => 'Vui lòng chọn cửa hàng',
                'id_thuonghieu.integer'  => 'Cửa Hàng không hợp lệ',

                'xuatxu.required'        => 'Vui lòng nhập xuất xứ',
                'xuatxu.string'          => 'Xuất xứ phải là chuỗi ký tự',
                'xuatxu.max'             => 'Xuất xứ không quá 255 ký tự',

                'sanxuat.string'         => 'Tên nơi sản xuất phải là chuỗi ký tự',
                'sanxuat.max'            => 'Tên nơi sản xuất không quá 255 ký tự',

                'mo_ta.required'         => 'Vui lòng nhập mô tả sản phẩm',
                'mo_ta.string'           => 'Mô tả sản phẩm không hợp lệ',

                'anhsanpham.*.image'     => 'Mỗi file tải lên phải là hình ảnh',

                'bienthe.*.gia.required' => 'Vui lòng nhập giá cho biến thể',
                'bienthe.*.gia.numeric'  => 'Giá biến thể phải là số',
                'bienthe.*.gia.min'      => 'Giá biến thể không được nhỏ hơn 0',

                'bienthe.*.soluong.required' => 'Vui lòng nhập số lượng cho biến thể',
                'bienthe.*.soluong.integer'  => 'Số lượng biến thể phải là số nguyên',
                'bienthe.*.soluong.min'      => 'Số lượng biến thể không được nhỏ hơn 0',

                'trangthai.required'     => 'Vui lòng chọn trạng thái cho sản phẩm',
                'trangthai.string'       => 'Trạng thái phải là chuỗi ký tự',
                'trangthai.max'          => 'Trạng thái không quá 50 ký tự',
            ]
        );
         $slug = Str::slug($request->tensp);
        DB::beginTransaction();
        try {
            // Tạo sản phẩm
            if(!empty($request->mediaurl)){
                $sanpham = SanphamModel::create([
                    'ten'        => $request->tensp,
                    'xuatxu'   => $request->xuatxu,
                    'sanxuat'  => $request->sanxuat,
                    'trangthai'    => $request->trangthai,
                    'mota'         => $request->mo_ta,
                    'id_thuonghieu' => $request->id_thuonghieu,
                    'slug'       => $slug,
                    'trangthai'    => $request->trangthai,
                ]);
            } else {
                $sanpham = SanphamModel::create([
                    'ten'        => $request->tensp,
                    'xuatxu'   => $request->xuatxu,
                    'sanxuat'  => $request->sanxuat,
                    'trangthai'    => $request->trangthai,
                    'mota'         => $request->mo_ta,
                    'id_thuonghieu' => $request->id_thuonghieu,
                    'slug'       => $slug,
                    'trangthai'    => $request->trangthai,
                ]);
            }

            // Gắn danh mục cho sản phẩm
            if ($request->id_danhmuc) {
                $sanpham->danhmuc()->attach($request->id_danhmuc);
            }

            // Xử lý ảnh sản phẩm
            if ($request->hasFile('hinhanhsanpham')) {
                $slugName = Str::slug($request->tensp);
                $i = 1;

                foreach ($request->file('hinhanhsanpham') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$slugName}-{$i}-" . time() . ".{$extension}";
                    $path = $file->storeAs('uploads/anh_sanpham/media', $filename, 'public');

                    // Ghi vào database
                    HinhanhsanphamModel::create([
                        'id_sanpham' => $sanpham->id,
                        'hinhanh'      => $path,
                    ]);
                    $i++;
                }
            }

            // Xử lý biến thể
            if (empty($request->bienthe)) {
                return response()->json(['message' => 'Không có biến thể sản phẩm'], 400);
            }

            foreach ($request->bienthe as $bt) {
                if (!empty($bt['id_tenloai']) && !empty($bt['gia'])) {
                    // Kiểm tra và tạo biến thể
                    if (is_numeric($bt['id_tenloai']) && intval($bt['id_tenloai']) == $bt['id_tenloai']) {
                        $id_tenloai = $bt['id_tenloai'];
                    } else {
                        // Chuẩn hóa tên loại
                        $tenLoai = trim($bt['id_tenloai']);
                        $existingLoai = LoaibientheModel::whereRaw('LOWER(ten) = ?', [strtolower($tenLoai)])->first();
                        if ($existingLoai) {
                            $id_tenloai = $existingLoai->id;
                        } else {
                            $newLoai = LoaibientheModel::create(['ten' => $tenLoai]);
                            $id_tenloai = $newLoai->id;
                        }
                    }

                    // Tạo biến thể sản phẩm
                    BientheModel::create([
                        'id_sanpham' => $sanpham->id,
                        'id_tenloai' => $id_tenloai,
                        'id_loaibienthe' => $id_tenloai,
                        'giagoc'        => $bt['gia'] ?? 0,
                        'soluong'    => $bt['soluong'] ?? 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sanpham.index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi: ' . $e->getMessage()]);
        }
    }


    public function edit(Request $request, $id)
    {
        $sanpham = SanphamModel::with(['bienthe', 'hinhanhsanpham', 'danhmuc'])->findOrFail($id);
        $danhmucs = DanhmucModel::all();
        $thuonghieus = ThuongHieuModel::all();
        $loaibienthes = LoaibientheModel::all();

        return view('sanpham.suasanpham', compact('sanpham', 'danhmucs', 'thuonghieus', 'loaibienthes'));
    }

    public function update(Request $request, $id)
    {
        $sanpham = SanphamModel::with(['bienthe', 'hinhanhsanpham'])->findOrFail($id);
        // Update thông tin sản phẩm
        $sanpham->ten = $request->ten;
        $sanpham->id_thuonghieu = $request->id_thuonghieu;
        $sanpham->xuatxu = $request->xuatxu;
        $sanpham->sanxuat = $request->sanxuat;
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
                    $loai = LoaibientheModel::firstOrCreate(
                        ['ten' => $tenLoai],
                        ['ten' => $tenLoai] // nếu chưa có thì tạo mới
                    );
                    $idTenLoai = $loai->id;
                }
            }

            if (!$idTenLoai) continue; // bỏ qua nếu không hợp lệ

            if (isset($bt['id'])) {
                // Nếu biến thể đã có id -> update
                $b = BientheModel::find($bt['id']);
                if ($b) {
                    $b->id_loaibienthe = $idTenLoai;
                    $b->giagoc = $bt['gia'];
                    $b->soluong = $bt['soluong'];
                    $b->save();
                    $newIds[] = $b->id;
                }
            } else {
                // Thêm biến thể mới
                $b = new BientheModel();
                $b->id_sanpham = $sanpham->id;
                $b->id_tenloai = $idTenLoai;
                $b->gia = $bt['gia'];
                $b->soluong = $bt['soluong'];
                $b->save();
                $newIds[] = $b->id;
            }
        }
        return redirect()->route('sanpham.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sanpham = SanphamModel::findOrFail($id);

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
            BientheModel::where('id_sanpham', $sanpham->id)->delete();

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
}
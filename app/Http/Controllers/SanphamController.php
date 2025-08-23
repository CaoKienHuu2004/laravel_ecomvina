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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SanPham::with('bienThe', 'danhmuc');

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
        $sanphams = $query->orderBy('updated_at', 'desc')->get();

        // Lấy thêm list danh mục & thương hiệu để render filter
        $thuonghieus = ThuongHieu::all();
        $danhmucs = DanhMuc::all();

        return view('sanpham', compact('sanphams', 'thuonghieus', 'danhmucs'));

        // // Lấy toàn bộ sản phẩm kèm quan hệ
        // $sanpham = Sanpham::with(['bienThe.loaiBienThe', 'anhSanPham', 'danhmuc', 'thuonghieu'])->get();

        // // Render view với dữ liệu
        // return view('sanpham', compact('sanpham'));
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

            if ($request->hasFile('anhsanpham')) {
                $i = 1;
                // Chuẩn hóa tên sản phẩm thành slug để đặt tên file
                $slugName = Str::slug($request->tensp);

                foreach ($request->file('anhsanpham') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $slugName . '-' . $i . '.' . $extension;

                    // Lưu vào thư mục public/storage/images
                    $file->move(public_path('img/product'), $filename);

                    // Lưu thông tin vào DB
                    Anhsp::create([
                        'id_sanpham' => $sanpham->id,
                        'media'      => $filename,
                    ]);

                    $i++;
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

    public function edit(Request $request, $id)
    {
        $sanpham = Sanpham::with(['bienthe', 'anhsanpham', 'danhmuc'])->findOrFail($id);
        $danhmucs = DanhMuc::all();
        $thuonghieus = ThuongHieu::all();
        $loaibienthes = LoaiBienThe::all();

        return view('suasanpham', compact('sanpham', 'danhmucs', 'thuonghieus', 'loaibienthes'));
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
            if (isset($bt['id'])) {
                // Nếu biến thể đã có id -> update
                $b = Bienthesp::find($bt['id']);
                if ($b) {
                    $b->id_tenloai = $bt['id_tenloai'];
                    $b->gia = $bt['gia'];
                    $b->soluong = $bt['soluong'];
                    $b->save();
                    $newIds[] = $b->id;
                }
            } else {
                // Thêm biến thể mới
                $b = new Bienthesp();
                $b->id_sanpham = $sanpham->id;
                $b->id_tenloai = $bt['id_tenloai'];
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
        $deletedImages = $request->deleted_image_ids ?? []; // chỉ những ảnh user muốn xóa
        foreach ($sanpham->anhsanpham as $anh) {
            if (in_array($anh->id, $deletedImages)) {
                // xóa file và bản ghi
                Storage::disk('public')->delete('img/product/' . $anh->media);
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
    public function destroy(string $id)
    {
        //
    }
}

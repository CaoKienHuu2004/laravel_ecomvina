<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BientheModel;
use App\Models\DanhmucModel;
use App\Models\HinhanhsanphamModel;
use App\Models\LoaibientheModel;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SanphamController extends Controller
{

    protected $uploadDir = "assets/client/images/thumbs";// thư mục lưu file, relative so với public
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SanphamModel::with('bienthe', 'danhmuc','hinhanhsanpham','thuonghieu','bienthe.chitietdonhang');

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
        // $sanphams = $query->distinct()
        //               ->orderBy('id', 'descs')
        //               ->get();

                // $sanphams = $query->select('id', 'ten', 'gia', ...) // các cột bạn cần
                //   ->distinct()
                //   ->orderBy('id', 'desc')
                //   ->get();
        $sanphams = $query->orderBy('id', 'desc')->get();

        // Lấy thêm list danh mục & thương hiệu để render filter
        $thuonghieus = ThuongHieuModel::all();
        $danhmucs = DanhmucModel::all();

        return view('sanpham/index', compact('sanphams', 'thuonghieus', 'danhmucs'));

        // // Lấy toàn bộ sản phẩm kèm quan hệ
        // $sanpham = Sanpham::with(['bienThe.loaiBienThe', 'anhSanPham', 'danhmuc', 'thuonghieu'])->get();

        // // Render view với dữ liệu
        // return view('sanpham', compact('sanpham'));
    }

    public function create()
    {
        $thuonghieus = ThuongHieuModel::all();
        $danhmucs = DanhmucModel::all();
        $loaibienthes = LoaibientheModel::all();
        $selectbox_sanpham_trangthais = SanphamModel::getEnumValues('trangthai');

        return view('sanpham/create', compact('thuonghieus', 'danhmucs', 'loaibienthes','selectbox_sanpham_trangthais'));
    }

    public function show($id)
    {
        $sanpham = SanphamModel::with(['hinhanhsanpham', 'danhmuc', 'bienthe.loaibienthe'])->findOrFail($id);

        // kiểm tra slug có đúng không, nếu không thì redirect về slug đúng
        // $correctSlug = \Illuminate\Support\Str::slug($sanpham->ten);
        // if ($slug !== $correctSlug) {
        //     return redirect()->route('sanpham.show', [
        //         'id' => $sanpham->id,
        //         'slug' => $correctSlug
        //     ]);
        // }

        return view('sanpham/show', compact('sanpham'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valueTrangThai = SanphamModel::getEnumValues('trangthai');
        $request->validate(
            [
                'tensp'        => 'required|string|max:255',
                'id_danhmuc'   => 'required|array',
                'id_danhmuc.*' => 'integer|exists:danhmuc,id',
                'id_thuonghieu' => 'required|integer', //edit
                'xuatxu'   => 'required|string|max:255',
                'sanxuat'  => 'nullable|string|max:255',
                'mo_ta'        => 'required|string',
                'giamgia'        => 'required|numeric|min:0|max:100', //new
                'anhsanpham.*' => 'image',
                'bienthe.*.gia' => 'required|numeric|min:0',
                'bienthe.*.soluong' => 'required|integer|min:0',
                'trangthai'    => 'required|in:' . implode(',', $valueTrangThai), //new
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

                'id_thuonghieu.required' => 'Vui lòng chọn thương hiệu', // edit
                'id_thuonghieu.integer'  => 'thương hiệu không hợp lệ',

                'xuatxu.required'        => 'Vui lòng nhập xuất xứ', // null có thể bỏ, để nó trống
                'xuatxu.string'          => 'Xuất xứ phải là chuỗi ký tự',
                'xuatxu.max'             => 'Xuất xứ không quá 255 ký tự',

                'sanxuat.string'         => 'Tên nơi sản xuất phải là chuỗi ký tự', // null có thể bỏ, để nó trống
                'sanxuat.max'            => 'Tên nơi sản xuất không quá 255 ký tự',

                'mo_ta.required'         => 'Vui lòng nhập mô tả sản phẩm',
                'mo_ta.string'           => 'Mô tả sản phẩm không hợp lệ',

                'giamgia.required' => 'Vui lòng nhập giá cho biến thể', // new
                'giamgia.numeric'  => 'Giá biến thể phải là số',
                'giamgia.min'      => 'Giá biến thể không được nhỏ hơn 0',
                'giamgia.max'      => 'Giá biến thể không được lớn hơn 100',

                // Ảnh
                // 'anhsanpham.*.image'     => 'Mỗi file tải lên phải là hình ảnh',
                'anhsanpham.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

                // Biến thể
                'bienthe.*.gia.required' => 'Vui lòng nhập giá cho biến thể',
                'bienthe.*.gia.numeric'  => 'Giá biến thể phải là số',
                'bienthe.*.gia.min'      => 'Giá biến thể không được nhỏ hơn 0',

                'bienthe.*.soluong.required' => 'Vui lòng nhập số lượng cho biến thể',
                'bienthe.*.soluong.integer'  => 'Số lượng biến thể phải là số nguyên',
                'bienthe.*.soluong.min'      => 'Số lượng biến thể không được nhỏ hơn 0',

                'trangthai.required'         => 'Hãy nhập trang thái !', //new
                'trangthai.in'           => 'Trang thái phải thuộc một trong các giá trị sau: ' . implode(', ', $valueTrangThai),
            ]
        );

        DB::beginTransaction();
        try {
            // Tạo sản phẩm

            $sanpham = SanphamModel::create([
                'id_thuonghieu' => $request->id_thuonghieu,
                'ten'        => $request->tensp,
                'slug'        => Str::slug($request->tensp), // auto
                'mota'         => $request->mo_ta,

                'xuatxu'   => $request->xuatxu, // null có thể bỏ, để nó trống
                'sanxuat'  => $request->sanxuat, // null có thể bỏ, để nó trống

                'trangthai'    => $request->trangthai,

                'giamgia'    => $request->giamgia, //new
                // 'luotxem'    => $request->luotxem, //auto

            ]);
            // $sanpham->danhmuc()->attach($request->id_danhmuc);
            if ($request->id_danhmuc) {
                $sanpham->danhmuc()->attach($request->id_danhmuc); // tự động thêm insert vào bảng trung gian danhmuc_sanpham
            }
            // if ($request->filled('id_danhmuc')) {
            //     // Dùng sync() để tránh duplicate hoặc dùng attach() nếu muốn thêm nhiều lần
            //     $sanpham->danhmuc()->sync($request->id_danhmuc);
            // }

            if ($request->hasFile('anhsanpham')) {
                $slugName = Str::slug($request->tensp);
                $i = 1;
                $dir_path = public_path($this->uploadDir);

                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0755, true);
                }
                foreach ($request->file('anhsanpham') as $file) {
                    // Lấy extension file gốc
                    $extension = $file->getClientOriginalExtension();
                    // Tạo tên file mới để tránh trùng (vd: ten-san-pham-1.jpg)
                    $fileName = "{$slugName}-{$i}" .  ".{$extension}"; //time() .
                    // Di chuyển file vào thư mục lưu trữ
                    $file->move($dir_path, $fileName);

                    // Tạo đường dẫn đầy đủ tới file (domain + đường dẫn upload)
                    $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $fileName;

                    // Ghi vào database
                    HinhanhsanphamModel::create([
                        'id_sanpham' => $sanpham->id, // lấy sanpham->id mới tạo //$request->id_sanpham,
                        'hinhanh'    => $link_hinh_anh,
                        // 'trangthai'  => 'Hiển thị', // nếu bạn có trạng thái mặc định thì có thể bỏ qua
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
                        $existingLoai = LoaibientheModel::whereRaw('LOWER(ten) = ?', [strtolower($tenLoai)])->first();

                        if ($existingLoai) {
                            // Nếu đã có -> lấy id cũ
                            $id_tenloai = $existingLoai->id;
                        } else {
                            // Nếu chưa có -> tạo mới
                            $newLoai = LoaibientheModel::create(['ten' => $tenLoai]);
                            $id_tenloai = $newLoai->id;
                        }
                    }

                    // Tạo biến thể sản phẩm
                    BientheModel::create([
                        'id_loaibienthe' => $id_tenloai,
                        'id_sanpham' => $sanpham->id,

                        'giagoc'        => $bt['gia'],
                        'soluong'    => $bt['soluong'] ?? 1,
                        'luottang'    => 0,
                        'luotban'    => 0,
                        'trangthai'  => $bt['soluong'] > 5 ? 'Còn hàng' : 'Sắp hết hàng', //BientheModel::getTinhTrangKhoAttribute();
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

        $selectbox_sanpham_trangthais = SanphamModel::getEnumValues('trangthai');

        return view('sanpham/edit', compact('sanpham', 'danhmucs', 'thuonghieus', 'loaibienthes','selectbox_sanpham_trangthais'));
    }

    public function update(Request $request, $id)
    {
        $sanpham = SanphamModel::with(['bienthe', 'hinhanhsanpham'])->findOrFail($id);
        // chưa validate
        // $valueTrangThai = SanphamModel::getEnumValues('trangthai');
        // Update thông tin sản phẩm
        $sanpham->id_thuonghieu = $request->id_thuonghieu; //edit
        $sanpham->ten = $request->ten;
        $sanpham->slug = $request->slug ?? Str::slug($request->ten); // nếu không có slug thì tự tạo

        $sanpham->mota = $request->mo_ta;

        $sanpham->xuatxu = $request->xuatxu;
        $sanpham->sanxuat = $request->sanxuat;

        $sanpham->trangthai = $request->trangthai;

        $sanpham->giamgia = $request->giamgia; //new
        $sanpham->luotxem = $request->luotxem; //new


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
                    $b->id_loaibienthe  = $idTenLoai;
                    $b->giagoc = $bt['gia'];
                    $b->soluong = $bt['soluong'];
                    $b->luottang = $bt['luottang'];
                    $b->luotban = $bt['luotban'];
                    $b->trangthai = $bt['soluong'] > 5 ? 'Còn hàng' : ( $bt['soluong'] == 0 ? 'Hết hàng' : 'Sắp hết hàng'); //BientheModel::getTinhTrangKhoAttribute();
                    $b->save();
                    $newIds[] = $b->id;
                }
            } else {
                // Thêm biến thể mới
                $b = new BientheModel();
                $b->id_sanpham = $sanpham->id;
                $b->id_loaibienthe = $idTenLoai;
                $b->giagoc = $bt['gia'];
                $b->soluong = $bt['soluong'];
                $b->luottang = 0;
                $b->luotban = 0;
                $b->trangthai = $bt['soluong'] > 5 ? 'Còn hàng' : ( $bt['soluong'] == 0 ? 'Hết hàng' : 'Sắp hết hàng'); //BientheModel::getTinhTrangKhoAttribute();
                $b->save();
                $newIds[] = $b->id;
            }
        }

        // Xóa biến thể cũ mà bị remove
        $toDelete = array_diff($existingIds, $newIds);
        if (!empty($toDelete)) {
            BientheModel::destroy($toDelete);
        }

        // --- Xử lý ảnh ---
        $dir_path = public_path($this->uploadDir);
        $deletedImages = $request->deleted_image_ids ?? []; // những ảnh user muốn xóa
        foreach ($sanpham->hinhanhsanpham as $anh) {
            if (in_array($anh->id, $deletedImages)) {
                // kiểm tra file tồn tại trước khi xóa
                $filePath = str_replace($this->domain, '', $anh->hinhanh);
                // $filePath = $dir_path.$anh->hinhanh;
                // $filePath = public_path('img/product/' . $anh->media);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                // xóa bản ghi DB
                $anh->delete();
            }
        }

        if ($request->hasFile('anhsanpham')) {
                $slugName = Str::slug($request->ten);
                $i = 1;
                $dir_path = public_path($this->uploadDir);

                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0755, true);
                }
                foreach ($request->file('anhsanpham') as $file) {
                    // Lấy extension file gốc
                    $extension = $file->getClientOriginalExtension();
                    // Tạo tên file mới để tránh trùng (vd: ten-san-pham-1.jpg)
                    $fileName = "{$slugName}-{$i}" .  ".{$extension}"; //time() .
                    // Di chuyển file vào thư mục lưu trữ
                    $file->move($dir_path, $fileName);

                    // Tạo đường dẫn đầy đủ tới file (domain + đường dẫn upload)
                    $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $fileName;

                    // Ghi vào database
                    HinhanhsanphamModel::create([
                        'id_sanpham' => $id, //$sanpham->id, // lấy sanpham->id mới tạo //$request->id_sanpham,
                        'hinhanh'    => $link_hinh_anh,
                        // 'trangthai'  => 'Hiển thị', // nếu bạn có trạng thái mặc định thì có thể bỏ qua
                    ]);

                    $i++;
                }
            }

        // if ($request->hasFile('anhsanpham')) {
        //     $slugName = Str::slug($request->ten);

        //     foreach ($request->file('anhsanpham') as $i => $file) {
        //         $extension = $file->getClientOriginalExtension();
        //         $filename = $slugName . '-' . ($i + 1) . '.' . $extension;

        //         // Lưu file trực tiếp vào public/img/product
        //         $file->move(public_path('img/product'), $filename);

        //         // Lưu tên file vào DB
        //         $anh = new HinhanhsanphamModel();
        //         $anh->id_sanpham = $sanpham->id;
        //         $anh->media = $filename;
        //         $anh->save();
        //     }
        // }


        return redirect()->route('sanpham.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }
    /**
     * Xóa mềm (soft delete)
     */
    public function destroy($id)
    {
        $sanpham = SanphamModel::findOrFail($id);

        DB::beginTransaction();
        try {
            // Xoá ảnh sản phẩm
            // if ($sanpham->anhSanPham && $sanpham->anhSanPham->count()) {
            //     foreach ($sanpham->anhSanPham as $anh) {
            //         $filePath = public_path('img/product/' . $anh->media);
            //         if (file_exists($filePath)) {
            //             unlink($filePath); // xoá file thật
            //         }
            //         $anh->delete(); // xoá record DB
            //     }
            // }

            // Xoá biến thể
            BientheModel::where('id_sanpham', $sanpham->id)->delete();
            HinhanhsanphamModel::where('id_sanpham', $sanpham->id)->delete();

            // Xoá liên kết danh mục (nếu có belongsToMany)
            // $sanpham->danhmuc()->detach(); // đang là short delete nên ko cần detach cũng đc

            // Xoá sản phẩm chính
            $sanpham->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Xoá mềm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi khi xoá mềm: ' . $e->getMessage());
        }
    }
    // public function destroy($id)
    // {
    //     $hinhanh = HinhanhsanphamModel::findOrFail($id);
    //     $hinhanh->delete();

    //     return redirect()->route('hinhanhsanpham.index')->with('success', 'Đã chuyển hình ảnh vào thùng rác!');
    // }

    /**
     * Hiển thị danh sách hình ảnh đã xóa
     */
    public function trash()
    {
        // $sanphams = SanphamModel::onlyTrashed()->orderByDesc('deleted_at')->get();
        $sanphams = SanphamModel::onlyTrashed()
            ->with([
                'bienthe' => function($query) {
                    $query->withTrashed();
                },
                'hinhanhsanpham' => function($query) {
                    $query->withTrashed();
                }
            ])
            ->orderByDesc('deleted_at')
            ->get();
        return view('sanpham.trash', compact('sanphams'));
    }



    /**
     * Khôi phục hình ảnh đã xóa mềm
     */
    public function restore($id)
    {
        $sanpham = SanphamModel::onlyTrashed()->findOrFail($id);

        // Lấy biến thể bị xóa mềm theo sản phẩm
        $bienthes = BientheModel::onlyTrashed()->where('id_sanpham', $sanpham->id)->get();

        $hinhanhsanphams = HinhanhsanphamModel::onlyTrashed()->where('id_sanpham', $sanpham->id)->get();

        // Khôi phục biến thể
        foreach ($bienthes as $bienthe) {
            $bienthe->restore();
        }
        foreach ($hinhanhsanphams as $hinhanh) {
            $hinhanh->restore();
        }

        // Khôi phục sản phẩm chính
        $sanpham->restore();

        return redirect()->route('sanpham.trash')->with('success', 'Khôi phục sản phẩm thành công!');
    }

    /**
     * Xóa vĩnh viễn hình ảnh khỏi DB và xóa file vật lý
     */
    public function forceDelete($id)
    {
        $sanpham = SanphamModel::onlyTrashed()->with(['hinhanhsanpham' => function($q) {
            $q->onlyTrashed();
        }, 'bienthe' => function($q) {
            $q->onlyTrashed();
        }])->findOrFail($id);

        // Xóa file ảnh vật lý và bản ghi ảnh vĩnh viễn
        foreach ($sanpham->hinhanhsanpham as $anh) {
            $filePath = public_path(parse_url($anh->hinhanh, PHP_URL_PATH));
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $anh->forceDelete();
        }
        // Xóa biến thể vĩnh viễn
        foreach ($sanpham->bienthe as $bienthe) {
            $bienthe->forceDelete();
        }

        // Xóa sản phẩm vĩnh viễn
        $sanpham->forceDelete();

        return redirect()->route('sanpham.trash')->with('success', 'Đã xóa vĩnh viễn sản phẩm!');
    }


}

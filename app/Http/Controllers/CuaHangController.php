<?php

namespace App\Http\Controllers;

use App\Models\Diachi;
use App\Models\Loaibienthe;
use App\Models\Nguoidung;
use App\Models\ThongTinNguoiBanHang;
use Illuminate\Http\Request;

class CuaHangController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $danhsach = Nguoidung::with('diachi')
        //     ->where('vaitro', 'assistant')
        //     ->get();
        // // $danhsach = Nguoidung::with('diachi')->vaitro('assistant')->get();
        // $diachi = Diachi::all();
        // $thongTinNguoiBan = ThongTinNguoiBanHang::all();
        // return view("cuahang.index", compact("danhsach","diachi","thongTinNguoiBan"));

        $danhsach = Nguoidung::with([
                'diachi',
                'thongTinNguoiBanHang',
                'thongTinNguoiBanHang.sanpham',
            ])
            ->where('vaitro', 'seller')
            ->get();

        return view('cuahang.index', compact('danhsach'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cuahang.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_cuahang' => 'required|string|max:255|unique:thongtin_nguoibanhang,ten_cuahang',
            'giayphep_kinhdoanh' => 'required|string|max:255',
            'sodienthoai' => 'required|string|max:20|unique:thongtin_nguoibanhang,sodienthoai',
            'email' => 'required|email|unique:thongtin_nguoibanhang,email',
            'diachi' => 'required|string|max:255',
            'mota' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bianen' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('images/cuahang/logo', 'nextjs_assets'); // laravel thì được còn frontend-dùng images, cấu hình trong file config/filesystems.php
                $logoPath = $request->file('logo')->store('uploads/cuahang/logo', 'public');
            }

            $bannerPath = null;
            if ($request->hasFile('bianen')) {
                $bannerPath = $request->file('bianen')->store('images/cuahang/bianen', 'nextjs_assets'); // laravel thì được còn frontend-dùng images , cấu hình trong file config/filesystems.php
                $bannerPath = $request->file('bianen')->store('uploads/cuahang/bianen', 'public');
            }
            $nguoidung = Nguoidung::create([
                'email' => $request->email,
                'sodienthoai' => $request->sodienthoai,
                'vaitro' => 'seller',
                'password' => bcrypt('123456789'), // database mới có cái này là defaul value rồi ko cần cũng được
            ]);

            ThongTinNguoiBanHang::create([
                'id_nguoidung' => $nguoidung->id,
                'ten_cuahang' => $request->ten_cuahang,
                'giayphep_kinhdoanh' => $request->giayphep_kinhdoanh,
                'diachi' => $request->diachi,
                'sodienthoai' => $request->sodienthoai,
                'email' => $request->email,
                'logo' => $logoPath,
                'bianen' => $bannerPath,
                'mota' => $request->mota,
                'theodoi' => 0,
                'luotban' => 0,
                'trangthai' => 'hoat_dong',
            ]);

            return redirect()->route('danh-sach-cua-hang')->with('success', 'Thêm cửa hàng mới thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi thêm cửa hàng: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $nguoiDung = Nguoidung::with([
            'diachi',
            'thongTinNguoiBanHang',
            'thongTinNguoiBanHang.sanpham',
            'thongTinNguoiBanHang.sanpham.bienThe',
            'thongTinNguoiBanHang.sanpham.bienThe.loaibienthe',
        ])
        ->where('vaitro', 'seller')
        ->find($id);

        if (!$nguoiDung) {
            return back()->with('error', 'Không tìm thấy thông tin người dùng hoặc cửa hàng!');
        }
        // dd($nguoiDung);
        return view('cuahang.show', compact('nguoiDung'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $nguoiDung = Nguoidung::with([
            'diachi',
            'thongTinNguoiBanHang',
            'thongTinNguoiBanHang.sanpham',
            'thongTinNguoiBanHang.sanpham.bienThe',
            'thongTinNguoiBanHang.sanpham.bienThe.loaibienthe',
            'thongTinNguoiBanHang.sanpham.anhSanPham',
        ])

        ->where('vaitro', 'seller')
        ->find($id);
        $loaiBienTheSelection = Loaibienthe::all();
        if (!$nguoiDung) {
            return back()->with('error', 'Không tìm thấy thông tin người dùng hoặc cửa hàng!');
        }
        // dd($nguoiDung);
        return view('cuahang.edit', compact('nguoiDung','loaiBienTheSelection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cuaHang = Nguoidung::findOrFail($id);

        // Nếu chỉ cập nhật trạng thái (route: cap-nhat-trang-thai)
        if ($request->route()->getName() === 'cap-nhat-trang-thai') {
            $request->validate([
                'trangthai' => 'required|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
            ]);

            $cuaHang->update(['trangthai' => $request->trangthai]);

            if ($request->ajax()) {
                return response()->json(['message' => 'Cập nhật trạng thái thành công!']);
            }

            return back()->with('success', 'Cập nhật trạng thái thành công!');
        }
        // Nếu chỉ cập nhật trạng thái (route: cap-nhat-trang-thai-cua-hang)
        if ($request->route()->getName() === 'cap-nhat-trang-thai-cua-hang') {
            // echo 'debug';
            // exit;
            $ck = $this->capNhatTrangThaiCuaHang($request,$id);
            if($ck['status'] === true) {
                return back()->with('success', $ck['message']);
            }else{
                return back()->with('error', $ck['message']);
            }
        }
        // Nếu chỉ cập nhật trạng thái (route: cap-nhat-tai-khoan)
        if ($request->route()->getName() === 'cap-nhat-cua-hang-tai-khoan') {

            $ck = $this->capNhatTaiKhoan($request,$id);
            if($ck['status'] === true) {
                return back()->with('success', 'Cập nhật thông tin tài khoản thành công!');
            }else{
                return back()->with('error', 'Cập nhật thông tin tài khoản thất bại!');
            }

        }
        // Nếu chỉ cập nhật của hàng (route: cap-nhat-cua-hang-duyet)
        if ($request->route()->getName() === 'cap-nhat-cua-hang-duyet') {
            $ck = $this->capNhatCuaHangDuyet($request, $id);
            if (isset($ck['status']) && $ck['status'] === true) {
                return back()->with('success', $ck['message'] ?? 'Cập nhật thông tin cửa hàng thành công!');
            } else {
                return back()->with('error', $ck['message'] ?? 'Cập nhật thông tin cửa hàng thất bại!');
            }
        }

        $nguoiDung = Nguoidung::with('thongTinNguoiBanHang')
            ->where('vaitro', 'seller')
            ->findOrFail($id);

        $cuaHang = $nguoiDung->thongTinNguoiBanHang;

        if (!$cuaHang) {
            return back()->with('error', 'Không tìm thấy thông tin cửa hàng của người dùng này.');
        }

        // Validate
        $request->validate([
            'ten_cuahang' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'sodienthoai' => 'nullable|string|max:20',
            'giayphep_kinhdoanh' => 'nullable|string|max:255',
            'mota' => 'nullable|string',
            'diachi' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bianen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'trangthai' => 'required|string|in:hoat_dong,ngung_hoa_dong,bi_khoa,cho_duyet',
        ]);

        // Upload ảnh nếu có
        $logoPath = $cuaHang->logo;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('uploads/cuahang/logo', 'public');
            $request->file('logo')->store('images/cuahang/logo', 'nextjs_assets');
        }

        $bannerPath = $cuaHang->bianen;
        if ($request->hasFile('bianen')) {
            $bannerPath = $request->file('bianen')->store('uploads/cuahang/bianen', 'public');
            $request->file('bianen')->store('images/cuahang/bianen', 'nextjs_assets');
        }

        $cuaHang->update([
            'ten_cuahang' => $request->ten_cuahang,
            'email' => $request->email,
            'sodienthoai' => $request->sodienthoai,
            'giayphep_kinhdoanh' => $request->giayphep_kinhdoanh,
            'mota' => $request->mota,
            'diachi' => $request->diachi,
            'logo' => $logoPath,
            'bianen' => $bannerPath,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('danh-sach-cua-hang')->with('success', 'Cập nhật cửa hàng thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function capNhatTaiKhoan(Request $request, $id)
    {
        // Lấy thông tin người dùng
        $nguoiDung = Nguoidung::with('diachi')->findOrFail($id);

        // ✅ 1. Validate dữ liệu
        $validated = $request->validate([
            'hoten' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:nguoi_dung,email,' . $id,
            'sodienthoai' => 'nullable|string|max:15',
            'trangthai' => 'required|in:hoat_dong,ngung_hoa_dong,bi_khoa,cho_duyet',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'diachi_nguoidung' => 'nullable|array',
            'diachi_nguoidung.*.id' => 'nullable|integer|exists:diachi_nguoidung,id',
            'diachi_nguoidung.*.diachi' => 'nullable|string|max:255',
            // 'diachi_nguoidung.*.ten' => 'nullable|string|max:255',
        ]);

        // ✅ 2. Cập nhật thông tin cơ bản
        $dataUpdate = [
            'hoten' => $request->hoten,
            'email' => $request->email,
            'sodienthoai' => $request->sodienthoai,
            'trangthai' => $request->trangthai,
        ];

        // ✅ 3. Xử lý avatar nếu có upload
        if ($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('uploads/nguoidung/avatar', 'public');
            $request->file('avatar')->store('images/nguoidung/avatar', 'nextjs_assets');
            $dataUpdate['avatar'] = $path;
        }

        $nguoiDung->update($dataUpdate);

        // ✅ 4. Cập nhật hoặc thêm mới địa chỉ
        if ($request->filled('diachi_nguoidung')) {
            foreach ($request->diachi_nguoidung as $item) {
                // Có id → cập nhật
                if (!empty($item['id'])) {
                    $nguoiDung->diachi()
                        ->where('id', $item['id'])
                        ->update([
                            'diachi' => $item['diachi'],
                        ]);
                }
                // Không có id → tạo mới
                elseif (!empty($item['diachi'])) {
                    $nguoiDung->diachi()->create([
                        'diachi' => $item['diachi'],
                        'ten' => $item['ten'] ?? $nguoiDung->hoten,
                        'sodienthoai' => $item['sodienthoai'] ?? $nguoiDung->sodienthoai,
                    ]);
                }
            }
        }

        // ✅ 5. Trả kết quả về view
        return true;
    }

  public function capNhatTrangThaiCuaHang(Request $request, $id)
    {
        $cuaHang = Nguoidung::with('thongTinNguoiBanHang')->findOrFail($id);

        $request->validate([
            'trangthai' => 'required|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        if (!$cuaHang->thongTinNguoiBanHang) {
                return [
                'status' => false,
                'message' => 'Cập nhật trạng thái cửa hàng thấp bại!'
            ];
        }

        $updated = $cuaHang->thongTinNguoiBanHang->update([
            'trangthai' => $request->trangthai
        ]);

        return [
            'status' => true,
            'message' => 'Cập nhật trạng thái cửa hàng thành công!'
        ];
    }

    public function capNhatCuaHangDuyet(Request $request, $id)
    {
        try {
            $nguoiDung = Nguoidung::with('thongTinNguoiBanHang')->findOrFail($id);

            $validated = $request->validate([
                'ten_cuahang' => 'required|string|max:255',
                'giayphep_kinhdoanh' => 'nullable|string|max:255',
                'mota' => 'nullable|string',
                'diachi' => 'nullable|string|max:255',
                'sodienthoai' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'bianen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            ]);

            $dataCuaHang = [
                'id_nguoidung' => $nguoiDung->id,
                'ten_cuahang' => $request->ten_cuahang,
                'giayphep_kinhdoanh' => $request->giayphep_kinhdoanh,
                'mota' => $request->mota,
                'diachi' => $request->diachi,
                'sodienthoai' => $request->sodienthoai ?? $nguoiDung->sodienthoai,
                'email' => $request->email ?? $nguoiDung->email,
            ];

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                if ($logo->isValid()) {
                    $dataCuaHang['logo'] = $logo->store('uploads/cuahang/logo', 'public');
                    $logo->store('images/cuahang/logo', 'nextjs_assets');
                } else {
                    return ['status' => false, 'message' => 'Upload logo thất bại'];
                }
            }

            if ($request->hasFile('bianen')) {
                $bianen = $request->file('bianen');
                if ($bianen->isValid()) {
                    $dataCuaHang['bianen'] = $bianen->store('uploads/cuahang/bianen', 'public');
                    $bianen->store('images/cuahang/bianen', 'nextjs_assets');
                } else {
                    return ['status' => false, 'message' => 'Upload bìa nền thất bại'];
                }
            }

            $flag = 0;
            if ($nguoiDung->thongTinNguoiBanHang) {
                $nguoiDung->thongTinNguoiBanHang->update($dataCuaHang);
            } else {
                $flag = 1;
                $nguoiDung->thongTinNguoiBanHang()->create($dataCuaHang);
            }

            return [
                'status' => true,
                'type' => $flag,
                'message' => $flag ? 'Tạo mới cửa hàng thành công!' : 'Cập nhật cửa hàng thành công!',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ];
        }
    }
}

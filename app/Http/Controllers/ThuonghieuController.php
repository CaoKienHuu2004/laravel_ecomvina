<?php

namespace App\Http\Controllers;

use App\Models\ThuongHieuModel;
use Illuminate\Http\Request;

class ThuonghieuController extends Controller
{
    protected $uploadDir = "assets/client/images/brands"; // thư mục lưu file, relative so với public
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }

    /**
     * Hiển thị danh sách thương hiệu
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = ThuongHieuModel::orderByDesc('id');

        if ($search) {
            $query->where('ten', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('trangthai', 'like', "%{$search}%");
        }

        $thuonghieus = $query->paginate(10)->withQueryString();

        return view('thuonghieu.index', compact('thuonghieus', 'search'));
    }

    /**
     * Hiển thị form tạo mới
     */
    public function create()
    {
        return view('thuonghieu.create');
    }

    /**
     * Lưu thương hiệu mới vào CSDL
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten'       => 'required|string',
            'slug'      => 'required|string|unique:thuonghieu,slug',
            'logo'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'mota'      => 'nullable|string',
            'trangthai' => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        $dir_path = public_path($this->uploadDir);

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0755, true);
        }

        $fileName = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName(); // giữ nguyên tên gốc
            $file->move($dir_path, $fileName);
        }

        $link_hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;

        ThuongHieuModel::create([
            'ten'       => $request->ten,
            'slug'      => $request->slug,
            'logo'      => $link_hinhanh,
            'mota'      => $request->mota,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('thuonghieu.index')->with('success', 'Thêm thương hiệu thành công!');
    }

    /**
     * Hiển thị chi tiết thương hiệu
     */
    public function show($id)
    {
        $thuonghieu = ThuongHieuModel::findOrFail($id);
        return view('thuonghieu.show', compact('thuonghieu'));
    }

    /**
     * Hiển thị form chỉnh sửa thương hiệu
     */
    public function edit($id)
    {
        $thuonghieu = ThuongHieuModel::findOrFail($id);
        return view('thuonghieu.edit', compact('thuonghieu'));
    }

    /**
     * Cập nhật thương hiệu
     */
    public function update(Request $request, $id)
    {
        $thuonghieu = ThuongHieuModel::findOrFail($id);

        $request->validate([
            'ten'       => 'required|string',
            'slug'      => 'required|string|unique:thuonghieu,slug,' . $id,
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'mota'      => 'nullable|string',
            'trangthai' => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        // Xử lý upload logo nếu có
        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu tồn tại
            if ($thuonghieu->logo) {
                $oldPath = public_path(parse_url($thuonghieu->logo, PHP_URL_PATH));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $dir_path = public_path($this->uploadDir);
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0755, true);
            }

            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName(); // giữ nguyên tên gốc
            $file->move($dir_path, $fileName);

            $link_hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;
            $thuonghieu->logo = $link_hinhanh;
        }

        $thuonghieu->ten = $request->ten;
        $thuonghieu->slug = $request->slug;
        $thuonghieu->mota = $request->mota;
        $thuonghieu->trangthai = $request->trangthai;

        $thuonghieu->save();

        return redirect()->route('thuonghieu.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    /**
     * Xóa thương hiệu
     */
    public function destroy($id)
    {
        $thuonghieu = ThuongHieuModel::findOrFail($id);

        // Xóa file logo nếu tồn tại
        if ($thuonghieu->logo) {
            $filePath = public_path(parse_url($thuonghieu->logo, PHP_URL_PATH));
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $thuonghieu->delete();

        return redirect()->route('thuonghieu.index')->with('success', 'Đã xóa thương hiệu!');
    }
}

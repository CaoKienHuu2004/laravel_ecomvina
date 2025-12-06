<?php

namespace App\Http\Controllers;

use App\Models\DanhmucModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DanhmucController extends Controller
{
    protected $uploadDir = "assets/client/images/categories"; // thư mục lưu file, relative so với public
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }

    public function index(Request $request)
    {
        $query = DanhmucModel::query()->withCount('sanpham');

        // // ✅ Tìm kiếm theo tên
        // if ($request->filled('keyword')) {
        //     $query->where('ten', 'like', '%' . $request->keyword . '%');
        // }

        // ✅ Phân trang (5 danh mục mỗi trang)
        // $danhmucs = $query->orderBy('id', 'desc')->paginate(10);
        $danhmucs = $query->orderBy('id', 'desc')->get(); //clientside paginate

        return view('danhmuc.index', compact('danhmucs'));
    }

    public function create()
    {
        return view('danhmuc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten'       => 'required|string|max:255|unique:danhmuc,ten',
            'logo'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'parent'    => 'required|in:Cha,Con',
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ]);

        $fileName = 'danhmuc.jpg'; // default logo

        if ($request->hasFile('logo')) {
            $dir_path = public_path($this->uploadDir);
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0755, true);
            }

            $file = $request->file('logo');
            // 👉 Lấy tên gốc của file (nguyên bản)
            $fileName = $file->getClientOriginalName();
            $file->move($dir_path, $fileName);
        }
        $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $fileName;
        DanhmucModel::create([
            'ten'       => $request->ten,
            'slug'      => Str::slug(str_replace('/', '-', $request->ten)),
            'logo'      => $link_hinh_anh,
            'parent'    => $request->parent,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('danhmuc.index')->with('success', 'Tạo danh mục thành công!');
    }

    public function show($id)
    {
        $danhmuc = DanhmucModel::findOrFail($id);
        return view('danhmuc.show', compact('danhmuc'));
    }

    public function edit($id)
    {
        $danhmuc = DanhmucModel::findOrFail($id);
        return view('danhmuc.edit', compact('danhmuc'));
    }

    public function update(Request $request, $id)
    {
        $danhmuc = DanhmucModel::findOrFail($id);

        $request->validate([
            'ten'       => 'required|string|max:255|unique:danhmuc,ten',
            'logo'      => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'parent'    => 'required|in:Cha,Con',
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ]);

        // Nếu có upload logo mới thì xóa logo cũ (trừ mặc định)
        if ($request->hasFile('logo')) {
            if ($danhmuc->logo && !str_contains($danhmuc->logo, 'danhmuc.jpg')) {
                $oldPath = public_path(parse_url($danhmuc->logo, PHP_URL_PATH));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $dir_path = public_path($this->uploadDir);
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0755, true);
            }

            $file = $request->file('logo');
            // 👉 Giữ nguyên tên file gốc
            $fileName = $file->getClientOriginalName();
            $file->move($dir_path, $fileName);
            $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $fileName;
            $danhmuc->logo = $link_hinh_anh;
        }

        $danhmuc->ten = $request->ten;
        $danhmuc->slug = Str::slug(str_replace('/', '-', $request->ten));
        $danhmuc->parent = $request->parent;
        $danhmuc->trangthai = $request->trangthai;

        $danhmuc->save();

        return redirect()->route('danhmuc.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $danhmuc = DanhmucModel::findOrFail($id);

        // Nếu danh mục còn sản phẩm thì không xóa
        if ($danhmuc->sanpham()->count() > 0) {
            return redirect()->route('danhmuc.index')->with('error', 'Không thể xóa! Danh mục này vẫn còn sản phẩm.');
        }

        // Xóa logo nếu không phải mặc định
        if ($danhmuc->logo && !str_contains($danhmuc->logo, 'danhmuc.jpg')) {
            $oldPath = public_path(parse_url($danhmuc->logo, PHP_URL_PATH));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $danhmuc->delete();

        return redirect()->route('danhmuc.index')->with('success', 'Xóa danh mục thành công!');
    }
}

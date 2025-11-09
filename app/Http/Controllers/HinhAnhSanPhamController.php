<?php

namespace App\Http\Controllers;

use App\Models\HinhanhsanphamModel;
use App\Models\SanphamModel;
use Illuminate\Http\Request;

class HinhAnhSanphamController extends Controller
{
    protected $uploadDir = "assets/client/images/thumbs";// thư mục lưu file, relative so với public
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }
    /**
     * Hiển thị danh sách hình ảnh (chưa xóa)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = HinhanhsanphamModel::with('sanpham')->orderByDesc('id');

        if ($search) {
            $query->where('id', $search) // tìm theo id của hình ảnh
                ->orWhereHas('sanpham', function ($q) use ($search) {
                    $q->where('ten', 'like', "%{$search}%"); // tìm theo tên sản phẩm liên kết
                });
        }

        $hinhanhs = $query->paginate(10)->withQueryString(); // phân trang 10 bản ghi

        return view('hinhanhsanpham.index', compact('hinhanhs', 'search'));
    }

    /**
     * Form thêm mới hình ảnh
     */
    public function create()
    {
        $sanphams = SanphamModel::all();
        return view('hinhanhsanpham.create', compact('sanphams'));
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id)
    {
        $hinhanh = HinhanhsanphamModel::findOrFail($id);
        $sanphams = SanphamModel::all();
        return view('hinhanhsanpham.edit', compact('hinhanh', 'sanphams'));
    }

    /**
     * Hiển thị chi tiết hình ảnh
     */
    public function show($id)
    {
        $hinhanh = HinhanhsanphamModel::with('sanpham')->findOrFail($id);
        return view('hinhanhsanpham.show', compact('hinhanh'));
    }

    /**
     * Hiển thị danh sách hình ảnh đã xóa
     */
    public function trash()
    {
        $hinhanhs = HinhanhsanphamModel::onlyTrashed()->orderByDesc('deleted_at')->get();
        return view('hinhanhsanpham.trash', compact('hinhanhs'));
    }

    /**
     * Lưu hình ảnh mới vào CSDL
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sanpham' => 'required|integer|exists:sanpham,id',
            'hinhanh'    => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'trangthai'  => 'required|string|in:Hiển thị,Tạm ẩn',
        ]);

        $dir_path = public_path($this->uploadDir);

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0755, true);
        }

        $fileName = null;
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            $fileName = $file->getClientOriginalName(); // giữ nguyên tên gốc (cẩn thận trùng tên)
            $file->move($dir_path, $fileName);
        }

        // Lưu đường dẫn URL đầy đủ (domain + relative path)
        $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $fileName;

        HinhanhsanphamModel::create([
            'id_sanpham' => $request->id_sanpham,
            'hinhanh'    => $link_hinh_anh,
            'trangthai'  => $request->trangthai,
        ]);

        return redirect()->route('hinhanhsanpham.index')->with('success', 'Thêm hình ảnh thành công!');
    }

    /**
     * Cập nhật thông tin hình ảnh
     */
    public function update(Request $request, $id)
    {
        $hinhanh = HinhanhsanphamModel::findOrFail($id);

        $request->validate([
            'id_sanpham' => 'required|integer|exists:sanpham,id',
            'trangthai'  => 'required|string|in:Hiển thị,Tạm ẩn',
            'hinhanh'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Nếu có upload hình mới thì xóa file cũ
        if ($request->hasFile('hinhanh')) {
            if ($hinhanh->hinhanh) {
                // Lấy đường dẫn file thực trên server
                $oldPath = public_path(parse_url($hinhanh->hinhanh, PHP_URL_PATH));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $dir_path = public_path($this->uploadDir);
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0755, true);
            }

            $file = $request->file('hinhanh');
            $fileName = $file->getClientOriginalName(); // giữ nguyên tên gốc
            $file->move($dir_path, $fileName);
            $hinhanh->hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;
        }

        $hinhanh->id_sanpham = $request->id_sanpham;
        $hinhanh->trangthai = $request->trangthai;
        $hinhanh->save();

        return redirect()->route('hinhanhsanpham.index')->with('success', 'Cập nhật hình ảnh thành công!');
    }

    /**
     * Xóa mềm (soft delete)
     */
    public function destroy($id)
    {
        $hinhanh = HinhanhsanphamModel::findOrFail($id);
        $hinhanh->delete();

        return redirect()->route('hinhanhsanpham.index')->with('success', 'Đã chuyển hình ảnh vào thùng rác!');
    }

    /**
     * Khôi phục hình ảnh đã xóa mềm
     */
    public function restore($id)
    {
        $hinhanh = HinhanhsanphamModel::onlyTrashed()->findOrFail($id);
        $hinhanh->restore();

        return redirect()->route('hinhanhsanpham.trash')->with('success', 'Khôi phục hình ảnh thành công!');
    }

    /**
     * Xóa vĩnh viễn hình ảnh khỏi DB và xóa file vật lý
     */
    public function forceDelete($id)
    {
        $hinhanh = HinhanhsanphamModel::onlyTrashed()->findOrFail($id);

        if ($hinhanh->hinhanh) {
            $filePath = public_path(parse_url($hinhanh->hinhanh, PHP_URL_PATH));
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $hinhanh->forceDelete();

        return redirect()->route('hinhanhsanpham.trash')->with('success', 'Đã xóa vĩnh viễn hình ảnh!');
    }
}

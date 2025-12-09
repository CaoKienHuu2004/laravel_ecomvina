<?php

namespace App\Http\Controllers;

use App\Models\TrangNoiDungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrangNoiDungController extends Controller
{
    protected $uploadDir = "assets/client/images/page"; // thư mục lưu file, relative so với public
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
        // $search = $request->input('search');

        $query = TrangNoiDungModel::orderBy('id','desc');

        // if ($search) {
        //     $query->where('ten', 'like', "%{$search}%")
        //           ->orWhere('slug', 'like', "%{$search}%")
        //           ->orWhere('trangthai', 'like', "%{$search}%");
        // }

        // $thuonghieus = $query->paginate(10)->withQueryString();
        $trangnoidungs = $query->get(); // clientside paginate

        return view('trangnoidung.index', compact('trangnoidungs'));
        // return view('thuonghieu.index', compact('thuonghieus', 'search'));
    }

    /**
     * Hiển thị form tạo mới
     */
    public function create()
    {
        $selectbox_trangthai = TrangNoiDungModel::getEnumValues('trangthai');
        return view('trangnoidung.create',compact('selectbox_trangthai'));
    }

    /**
     * Lưu thương hiệu mới vào CSDL
     */
    public function store(Request $request)
    {
        $request->validate([
            'tieude'    => 'required|string|unique:trang_noidung,tieude',
            'slug'    => 'required|string|unique:trang_noidung,slug',
            'hinhanh'   => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'mota'      => 'trequirede|string',
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ]);

        // Tạo thư mục upload nếu chưa có
        $dir_path = public_path($this->uploadDir);
        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0755, true);
        }

        // Upload hình
        $fileName = null;
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');

            // Đổi tên file an toàn
            $fileName = time() . '_' .
                Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();

            $file->move($dir_path, $fileName);
        }

        // Tạo link đầy đủ
        $link_hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;

        // Chống XSS cho mô tả
        $mota = $request->mota
            ? strip_tags($request->mota, '<p><br><b><i><u><strong><em><ul><ol><li>')
            : null;

        // Lưu dữ liệu
        TrangNoiDungModel::create([
            'tieude'    => e($request->tieude),
            'slug'      => $request->slug,
            'mota'      => $mota,
            'hinhanh'   => $link_hinhanh,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('trangnoidung.index')
            ->with('success', 'Thêm trang nội dung thành công!');
    }

    /**
     * Hiển thị chi tiết thương hiệu
     */
    public function show($id)
    {
        $trangnoidung = TrangNoiDungModel::findOrFail($id);
        $selectbox_trangthai = TrangNoiDungModel::getEnumValues('trangthai');
        return view('trangnoidung.show', compact('trangnoidung','selectbox_trangthai'));
    }

    /**
     * Hiển thị form chỉnh sửa thương hiệu
     */
    public function edit($id)
    {
        $trangnoidung = TrangNoiDungModel::findOrFail($id);
        $selectbox_trangthai = TrangNoiDungModel::getEnumValues('trangthai');
        return view('trangnoidung.edit', compact('trangnoidung','selectbox_trangthai'));
    }

    /**
     * Cập nhật thương hiệu
     */
    public function update(Request $request, $id)
    {
        $trang = TrangNoiDungModel::findOrFail($id);

        // Validate (đúng tên bảng + chống XSS cơ bản)
        $request->validate([
            'tieude'    => 'required|string|unique:trang_noidung,tieude,' . $id,
            'slug'    => 'required|string|unique:trang_noidung,slug,' . $id,
            'hinhanh'   => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'mota'      => 'sometimes|string',
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ]);

        // ==============================
        // XỬ LÝ UPLOAD FILE
        // ==============================
        if ($request->hasFile('hinhanh')) {

            $oldFile = basename(parse_url($trang->hinhanh, PHP_URL_PATH));

            // Không xoá ảnh mặc định page.jpg
            if ($oldFile !== 'page.jpg') {
                $oldPath = public_path($this->uploadDir . '/' . $oldFile);
                if (file_exists($oldPath)) unlink($oldPath);
            }

            // Tạo thư mục nếu chưa có
            $dir_path = public_path($this->uploadDir);
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0755, true);
            }

            $file = $request->file('hinhanh');

            // Tên file an toàn
            $fileName = time() . '_' . Str::slug(
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
            ) . '.' . $file->getClientOriginalExtension();

            $file->move($dir_path, $fileName);

            // Lưu URL đầy đủ vào DB
            $trang->hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;
        }

        // ==============================
        // UPDATE NỘI DUNG AN TOÀN (CHỐNG XSS)
        // ==============================
        $trang->tieude = e($request->tieude);

        $trang->slug = $request->slug;

        // Chỉ cho phép các thẻ an toàn (<p>, <b>, <i>, <strong>, ...)
        $trang->mota = $request->mota
            ? strip_tags($request->mota, '<p><br><b><i><u><strong><em><ul><ol><li>')
            : NULL;

        $trang->trangthai = $request->trangthai;

        $trang->save();

        return redirect()
            ->route('trangnoidung.index')
            ->with('success', 'Cập nhật trang nội dung thành công!');
    }

    /**
     * Xóa thương hiệu
     */
    public function destroy($id)
    {
        $trangnoidung = TrangNoiDungModel::findOrFail($id);

        // Xóa file hình ảnh nếu tồn tại và không phải file mặc định
        if ($trangnoidung->hinhanh) {

            // Lấy tên file từ URL
            $fileName = basename(parse_url($trangnoidung->hinhanh, PHP_URL_PATH));

            // Bỏ qua file mặc định
            if ($fileName !== 'page.jpg') {

                // Đường dẫn đúng đến file trong public
                $filePath = public_path($this->uploadDir . '/' . $fileName);

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Xóa database record
        $trangnoidung->delete();

        return redirect()
            ->route('trangnoidung.index')
            ->with('success', 'Đã xóa trang nội dung thành công!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BientheModel;
use App\Models\ChuongTrinhModel;
use App\Models\QuatangsukienModel;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class QuatangSukienController extends Controller
{
    protected $uploadDir = 'assets/client/images/thumbs';
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }

    /**
     * ============================
     * 📌 DANH SÁCH QUÀ TẶNG
     * ============================
     */
    public function index(Request $request)
    {
        $trangthais = QuatangsukienModel::getEnumValues('trangthai');

        $query = QuatangsukienModel::with(['bienthe', 'chuongtrinh'])
            ->orderBy('id', 'desc');

        if ($request->filled('trangthai') && in_array($request->trangthai, $trangthais)) {
            $query->where('trangthai', $request->trangthai);
        }

        if ($request->filled('tieude')) {
            $query->where('tieude', 'like', '%' . trim($request->tieude) . '%');
        }

        $quatangs = $query->paginate($request->get('per_page', 10))->appends($request->query());

        return view('quatangsukien.index', compact('quatangs', 'trangthais'));
    }

    /**
     * ============================
     * 📌 FORM THÊM QUÀ TẶNG
     * ============================
     */
    public function create()
    {
        $trangthais = QuatangsukienModel::getEnumValues('trangthai');
        // $bienthes = BientheModel::with('sanpham', 'loaibienthe', 'sanpham.hinhanhsanpham')
        // ->orderBy('id', 'desc')
        // ->paginate(10); //server side pagination
        $bienthes = BientheModel::with('sanpham', 'loaibienthe', 'sanpham.hinhanhsanpham')->get(); // đang dùng client side pagination
        $chuongtrinhs = ChuongTrinhModel::orderBy('id','desc')->get();

        return view('quatangsukien.create', compact(
            'trangthais',
            'bienthes',
            'chuongtrinhs',
        ));
    }

    /**
     * ============================
     * 📌 LƯU QUÀ TẶNG
     * ============================
     */
    public function store(Request $request)
    {
        $enumTrangThai = QuatangsukienModel::getEnumValues('trangthai');

        $request->validate([
            'id_bienthe' => 'required|integer|exists:bienthe,id',
            'id_chuongtrinh' => 'required|integer|exists:chuongtrinh,id',
            'tieude' => 'required|string|max:255',
            'dieukien' => 'nullable|string|max:255',
            'thongtin' => 'nullable|string',
            'ngaybatdau' => 'nullable|date',
            'ngayketthuc' => 'nullable|date',
            'hinhanh' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
            'trangthai' => 'required|in:' . implode(',', $enumTrangThai),
        ]);

        if ($request->ngaybatdau && $request->ngayketthuc) {
            if ($request->ngayketthuc < $request->ngaybatdau) {
                return back()->withErrors([
                    'ngayketthuc' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'
                ])->withInput();
            }
        }

        $quatang = new QuatangsukienModel();
        $quatang->fill($request->only([
            'id_bienthe', 'id_chuongtrinh', 'tieude',
            'thongtin', 'dieukien', 'trangthai',
            'ngaybatdau', 'ngayketthuc'
        ]));

        // Upload ảnh
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            $fileName = Str::slug($request->tieude) . '.' . $file->getClientOriginalExtension();
            $path = public_path($this->uploadDir);

            if (!file_exists($path)) mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $link_hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;
            $quatang->hinhanh = $link_hinhanh;
        }

        $quatang->save();

        return redirect()->route('quatangsukien.index')->with('success', 'Thêm quà tặng thành công!');
    }

    /**
     * ============================
     * 📌 CHI TIẾT
     * ============================
     */
    public function show($id)
    {
        $quatang = QuatangsukienModel::with(['bienthe', 'chuongtrinh'])->findOrFail($id);
        return view('quatangsukien.show', compact('quatang'));
    }

    /**
     * ============================
     * 📌 FORM CHỈNH SỬA
     * ============================
     */
    public function edit($id)
    {
        $quatang = QuatangsukienModel::findOrFail($id);
        $trangthais = QuatangsukienModel::getEnumValues('trangthai');
        $bienthes = BientheModel::with('sanpham')->get();
        $chuongtrinhs = ChuongTrinhModel::orderBy('tieude')->get();

        return view('quatangsukien.edit', compact(
            'quatang',
            'trangthais',
            'bienthes',
            'chuongtrinhs'
        ));
    }

    /**
     * ============================
     * 📌 CẬP NHẬT
     * ============================
     */
    public function update(Request $request, $id)
    {
        $enumTrangThai = QuatangsukienModel::getEnumValues('trangthai');

        $request->validate([
            'id_bienthe' => 'required|integer|exists:bienthe,id',
            'id_chuongtrinh' => 'required|integer|exists:chuongtrinh,id',
            'tieude' => 'required|string|max:255',
            'dieukien' => 'nullable|string|max:255',
            'thongtin' => 'nullable|string',
            'ngaybatdau' => 'nullable|date',
            'ngayketthuc' => 'nullable|date',
            'hinhanh' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
            'trangthai' => 'required|in:' . implode(',', $enumTrangThai),
        ]);

        if ($request->ngaybatdau && $request->ngayketthuc) {
            if ($request->ngayketthuc < $request->ngaybatdau) {
                return back()->withErrors([
                    'ngayketthuc' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'
                ]);
            }
        }

        $quatang = QuatangsukienModel::findOrFail($id);
        $quatang->fill($request->only([
            'id_bienthe', 'id_chuongtrinh', 'tieude',
            'thongtin', 'dieukien', 'trangthai',
            'ngaybatdau', 'ngayketthuc'
        ]));

        // Upload ảnh
        if ($request->hasFile('hinhanh')) {
            if ($quatang->hinhanh) {
                $oldPath = public_path(str_replace($this->domain, '', $quatang->hinhanh));
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $file = $request->file('hinhanh');
            $fileName = Str::slug($request->tieude) . '.' . $file->getClientOriginalExtension();

            $path = public_path($this->uploadDir);
            if (!file_exists($path)) mkdir($path, 0755, true);
            $link_hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;
            $file->move($path, $fileName);
            $quatang->hinhanh = $link_hinhanh;
        }

        $quatang->save();

        return redirect()->route('quatangsukien.index')->with('success', 'Cập nhật thành công!');
    }

    /**
     * ============================
     * 🗑️ XÓA (Soft Delete)
     * ============================
     */
    public function destroy($id)
    {
        $qt = QuatangsukienModel::findOrFail($id);
        $qt->delete();

        return redirect()->route('quatangsukien.index')->with('success', 'Đã đưa vào thùng rác!');
    }

    /**
     * ============================
     * 🗑️ DANH SÁCH THÙNG RÁC
     * ============================
     */
    public function trash()
    {
        $quatangs = QuatangsukienModel::onlyTrashed()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('quatangsukien.trash', compact('quatangs'));
    }

    /**
     * ============================
     * 🔄 KHÔI PHỤC
     * ============================
     */
    public function restore($id)
    {
        $qt = QuatangsukienModel::onlyTrashed()->findOrFail($id);
        $qt->restore();

        return redirect()->route('quatangsukien.trash')->with('success', 'Khôi phục thành công!');
    }

    /**
     * ============================
     * ❌ XÓA VĨNH VIỄN
     * ============================
     */
    public function forceDelete($id)
    {
        $qt = QuatangsukienModel::onlyTrashed()->findOrFail($id);

        if ($qt->hinhanh) {
            $oldPath = public_path(str_replace($this->domain, '', $qt->hinhanh));
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $qt->forceDelete();

        return redirect()->route('quatangsukien.trash')->with('success', 'Xóa vĩnh viễn thành công!');
    }
}

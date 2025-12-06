<?php

namespace App\Http\Controllers;

use App\Models\QuangcaoModel; // Model tương ứng bạn cần tạo hoặc đã có
use Illuminate\Http\Request;

class QuangCaoController extends Controller
{
    protected $uploadDir = "assets/client/images/bg"; // thư mục lưu file, relative so với public
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }

    /**
     * Hiển thị danh sách quảng cáo
     */
    public function index(Request $request)
    {
        // $search = $request->input('search');

        $query = QuangcaoModel::orderBy('id','desc');

        // if ($search) {
        //     $query->where('vitri', 'like', "%{$search}%")
        //         ->orWhere('mota', 'like', "%{$search}%")
        //         ->orWhere('trangthai', 'like', "%{$search}%");
        // }

        $quangcaos = $query->get(); // clientside paginate
        // $quangcaos = $query->paginate(10)->withQueryString();

        return view('quangcao.index', compact('quangcaos'));
        // return view('quangcao.index', compact('quangcaos', 'search'));
    }

    /**
     * Hiển thị form tạo mới quảng cáo
     */
    public function create()
    {
        return view('quangcao.create');
    }

    /**
     * Lưu quảng cáo mới vào CSDL
     */
    public function store(Request $request)
    {
        $request->validate([
            'vitri' => 'required|in:home_banner_slider,home_banner_event_1,home_banner_event_2,home_banner_event_3,home_banner_event_4,home_banner_promotion_1,home_banner_promotion_2,home_banner_promotion_3,home_banner_ads,home_banner_product', // cập nhật vị trí hợp lệ
            'hinhanh' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'lienket' => 'required|string',
            'mota' => 'required|string',
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ]);

        $dir_path = public_path($this->uploadDir);

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0755, true);
        }

        $fileName = null;
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            // $fileName = time() . '_' . $file->getClientOriginalName(); // tránh trùng tên
            // 👉 Lấy tên gốc của file (nguyên bản)
            $fileName = $file->getClientOriginalName();
            $file->move($dir_path, $fileName);
        }

        $link_hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;

        QuangcaoModel::create([
            'vitri' => $request->vitri,
            'hinhanh' => $link_hinhanh,
            'lienket' => $request->lienket,
            'mota' => $request->mota,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('quangcao.index')->with('success', 'Thêm quảng cáo thành công!');
    }

    /**
     * Hiển thị chi tiết quảng cáo
     */
    public function show($id)
    {
        $quangcao = QuangcaoModel::findOrFail($id);
        return view('quangcao.show', compact('quangcao'));
    }

    /**
     * Hiển thị form chỉnh sửa quảng cáo
     */
    public function edit($id)
    {
        $quangcao = QuangcaoModel::findOrFail($id);
        return view('quangcao.edit', compact('quangcao'));
    }

    /**
     * Cập nhật quảng cáo
     */
    public function update(Request $request, $id)
    {
        $quangcao = QuangcaoModel::findOrFail($id);

        $request->validate([
            'vitri' => 'required|in:home_banner_slider,home_banner_event_1,home_banner_event_2,home_banner_event_3,home_banner_event_4,home_banner_promotion_1,home_banner_promotion_2,home_banner_promotion_3,home_banner_ads,home_banner_product', // cập nhật vị trí hợp lệ
            'lienket' => 'required|string',
            'mota' => 'required|string',
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('hinhanh')) {
            // Xóa file cũ nếu tồn tại
            if ($quangcao->hinhanh) {
                $oldPath = public_path(parse_url($quangcao->hinhanh, PHP_URL_PATH));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $dir_path = public_path($this->uploadDir);
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0755, true);
            }

            $file = $request->file('hinhanh');
            // 👉 Lấy tên gốc của file (nguyên bản)
            $fileName = $file->getClientOriginalName();
            $file->move($dir_path, $fileName);
            $quangcao->hinhanh = $this->domain . $this->uploadDir . '/' . $fileName;
        }

        $quangcao->vitri = $request->vitri;
        $quangcao->lienket = $request->lienket;
        $quangcao->mota = $request->mota;
        $quangcao->trangthai = $request->trangthai;
        $quangcao->save();

        return redirect()->route('quangcao.index')->with('success', 'Cập nhật quảng cáo thành công!');
    }

    /**
     * Xóa quảng cáo
     */
    public function destroy($id)
    {
        $quangcao = QuangcaoModel::findOrFail($id);

        // Xóa file hình ảnh nếu có
        if ($quangcao->hinhanh) {
            $filePath = public_path(parse_url($quangcao->hinhanh, PHP_URL_PATH));
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $quangcao->delete();

        return redirect()->route('quangcao.index')->with('success', 'Đã xóa quảng cáo!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BientheModel;
use Illuminate\Http\Request;
use App\Models\ChuongTrinhModel;
use App\Models\QuatangsukienModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ChuongtrinhController extends Controller
{
    protected $uploadDir = 'assets/client/images/thumbs';
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }

    /**
     * Danh sách chương trình
     */
    public function index(Request $request)
    {
        $trangthais = ChuongTrinhModel::getEnumValues('trangthai');

        // $query = ChuongTrinhModel::with(['quatangsukien'])
        //     ->withCount('quatangsukien')
        //     ->orderBy('id', 'desc');

        // if ($request->filled('trangthai') && in_array($request->trangthai, $trangthais)) {
        //     $query->where('trangthai', $request->trangthai);
        // }

        // if ($request->filled('tieude')) {
        //     $query->where('tieude', 'like', '%' . trim($request->tieude) . '%');
        // }

        // $chuongtrinhs = $query->paginate($request->get('per_page', 10))->appends($request->query());

        $query = ChuongTrinhModel::with(['quatangsukien'])
            ->withCount('quatangsukien')
            ->orderBy('id', 'desc');
        $chuongtrinhs = $query->get(); // client paginate

        return view('chuongtrinh.index', compact('chuongtrinhs', 'trangthais'));
    }

    /**
     * Form thêm mới chương trình
     */
    public function create()
    {
        $trangthais_chuongtrinh = ChuongTrinhModel::getEnumValues('trangthai');
        $trangthais_quatang = QuatangsukienModel::getEnumValues('trangthai');
        $bienthes_combobox = BientheModel::with('sanpham','loaibienthe','sanpham.hinhanhsanpham')->get();
        return view('chuongtrinh.create', compact('bienthes_combobox','trangthais_chuongtrinh', 'trangthais_quatang'));
    }

    /**
     * Lưu chương trình mới + quà tặng
     */
    public function store(Request $request)
    {
        $enumTrangThai = ChuongTrinhModel::getEnumValues('trangthai');
        $enumTrangThaiQuaTang = QuatangsukienModel::getEnumValues('trangthai');
        $request->validate([
            'tieude' => 'required|string|max:255',
            'noidung' => 'string', // 'noidung' => 'nullable|string', // bỏi vì database ko cho null được

            // 'hinhanh' => 'nullable|image|max:2048',  // 2MB
            'hinhanh'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'trangthai' => 'required|in:' . implode(',', $enumTrangThai),

            'quatangsukien' => 'required|array|min:1',
            'quatangsukien.*.id_bienthe' => 'required|integer|exists:bienthe,id',
            'quatangsukien.*.tieude' => 'required|string|max:255',
            'quatangsukien.*.dieukiensoluong' => 'required|integer|max:999',
            'quatangsukien.*.dieukiengiatri' => 'nullable|integer|max:99999999999',
            'quatangsukien.*.ngaybatdau' => 'nullable|date',
            'quatangsukien.*.ngayketthuc' => 'nullable|date', //after_or_equal:quatangsukien.*.ngaybatdau' nếu có viết rules riêng cho nó
            'quatangsukien.*.trangthai' => 'nullable|in:' . implode(',', $enumTrangThaiQuaTang),

            // 'quatangsukien.*.hinhanh' => 'nullable|array', // nhầm 1 quatangsukien có 1 ảnh thôi
            'quatangsukien.*.hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'tieude.required' => 'Vui lòng nhập tiêu đề chương trình.',
            'quatangsukien.required' => 'Phải có ít nhất một quà tặng sự kiện.',
            'quatangsukien.*.tieude.required' => 'Vui lòng nhập tiêu đề quà tặng.',
        ]);
        // Kiểm tra ngày thủ công
        foreach ($request->input('quatangsukien', []) as $index => $qt) {
            if (!empty($qt['ngaybatdau']) && !empty($qt['ngayketthuc'])) {
                if (strtotime($qt['ngayketthuc']) < strtotime($qt['ngaybatdau'])) {
                    return redirect()->back()
                        ->withErrors(["quatangsukien.$index.ngayketthuc" => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'])
                        ->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            // === Lưu chương trình ===
            $chuongtrinh = new ChuongTrinhModel();
            $chuongtrinh->tieude = $request->tieude;
            $chuongtrinh->slug = Str::slug(str_replace('/', '-', $request->tieude)); // $chuongtrinh->slug = Str::slug($request->tieude);
            $chuongtrinh->noidung = $request->noidung;
            $chuongtrinh->trangthai = $request->trangthai;

            // Xử lý upload hình ảnh chương trình
            if ($request->hasFile('hinhanh')) {
                $file = $request->file('hinhanh');
                $fileName = Str::slug(str_replace('/', '-', $request->tieude)) . '.' . $file->getClientOriginalExtension();
                // $fileName = Str::slug($request->tieude) . '.' . $file->getClientOriginalExtension();

                $path = public_path($this->uploadDir);
                if (!file_exists($path)) mkdir($path, 0755, true);
                $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $fileName;
                $file->move($path, $fileName);
                $chuongtrinh->hinhanh = $link_hinh_anh;
            }

            $chuongtrinh->save();

            // === Lưu danh sách quà tặng ===
            foreach ($request->quatangsukien as $item) {
                $quatang = new QuatangsukienModel();
                $quatang->id_chuongtrinh = $chuongtrinh->id;
                $quatang->id_bienthe = $item['id_bienthe'] ?? 1; // hoặc bỏ nếu chưa có biến thể
                $quatang->tieude = $item['tieude'];
                $quatang->thongtin = $item['thongtin'] ?? '';
                $quatang->dieukiensoluong = $item['dieukiensoluong'] ?? '';
                $quatang->dieukiengiatri = $item['dieukiengiatri'] ?? '';
                $quatang->trangthai = $item['trangthai'] ?? 'Hiển thị';
                $quatang->ngaybatdau = $item['ngaybatdau'] ?? null;
                $quatang->ngayketthuc = $item['ngayketthuc'] ?? null;

                // Nếu có upload ảnh riêng cho quà tặng
                if (isset($item['hinhanh']) && $item['hinhanh'] instanceof \Illuminate\Http\UploadedFile) {
                    $giftFile = $item['hinhanh'];
                    $giftName = Str::slug(str_replace('/', '-', $item['tieude'])) . '.' . $giftFile->getClientOriginalExtension();
                    // $giftName = Str::slug($item['tieude']) . '.' . $giftFile->getClientOriginalExtension();
                    $giftPath = public_path($this->uploadDir);
                    if (!file_exists($giftPath)) mkdir($giftPath, 0755, true);
                    $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $giftName;
                    $giftFile->move($giftPath, $giftName);
                    $quatang->hinhanh = $link_hinh_anh;
                }

                $quatang->save();
            }

            DB::commit();
            return redirect()->route('chuongtrinh.index')->with('success', 'Thêm chương trình và quà tặng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Hiển thị chi tiết
     */
    public function show($id)
    {
        $chuongtrinh = ChuongTrinhModel::with('quatangsukien')->findOrFail($id);
        return view('chuongtrinh.show', compact('chuongtrinh'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $chuongtrinh = ChuongTrinhModel::with('quatangsukien')->findOrFail($id);
        $trangthais_chuongtrinh = ChuongTrinhModel::getEnumValues('trangthai');
        $trangthais_quatang = QuatangsukienModel::getEnumValues('trangthai');
        $bienthes_combobox = BientheModel::with('sanpham','loaibienthe','sanpham.hinhanhsanpham')->get();

        return view('chuongtrinh.edit', compact(
            'chuongtrinh',
            'bienthes_combobox',
            'trangthais_chuongtrinh',
            'trangthais_quatang'
        ));
    }

    /**
     * Cập nhật chương trình + quà tặng
     */
    public function update(Request $request, $id)
    {
        $chuongtrinh = ChuongTrinhModel::with('quatangsukien')->findOrFail($id);

        $enumTrangThai = ChuongTrinhModel::getEnumValues('trangthai');
        $enumTrangThaiQuaTang = QuatangsukienModel::getEnumValues('trangthai');

        // Validate dữ liệu tương tự như store
        $request->validate([
            'tieude' => 'required|string|max:255',
            'noidung' => 'nullable|string',
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'trangthai' => 'required|in:' . implode(',', $enumTrangThai),

            'quatangsukien' => 'required|array|min:1',
            'quatangsukien.*.id_bienthe' => 'required|integer|exists:bienthe,id',
            'quatangsukien.*.tieude' => 'required|string|max:255',
            'quatangsukien.*.dieukiensoluong' => 'required|integer|max:999|min:0',
            'quatangsukien.*.dieukiengiatri' => 'nullable|integer|max:99999999999|min:0',
            'quatangsukien.*.ngaybatdau' => 'nullable|date',
            'quatangsukien.*.ngayketthuc' => 'nullable|date',
            'quatangsukien.*.trangthai' => 'nullable|in:' . implode(',', $enumTrangThaiQuaTang),
            'quatangsukien.*.hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quatangsukien.*.id' => 'nullable|integer|exists:quatangsukien,id',
        ], [
            'tieude.required' => 'Vui lòng nhập tiêu đề chương trình.',
            'quatangsukien.required' => 'Phải có ít nhất một quà tặng sự kiện.',
            'quatangsukien.*.tieude.required' => 'Vui lòng nhập tiêu đề quà tặng.',
        ]);

        // Kiểm tra ngày bắt đầu và kết thúc quà tặng
        foreach ($request->input('quatangsukien', []) as $index => $qt) {
            if (!empty($qt['ngaybatdau']) && !empty($qt['ngayketthuc'])) {
                if (strtotime($qt['ngayketthuc']) < strtotime($qt['ngaybatdau'])) {
                    return redirect()->back()
                        ->withErrors(["quatangsukien.$index.ngayketthuc" => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'])
                        ->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            // Cập nhật chương trình
            $chuongtrinh->tieude = $request->tieude;
            $chuongtrinh->slug = Str::slug(str_replace('/', '-', $request->tieude));
            $chuongtrinh->noidung = $request->noidung;
            $chuongtrinh->trangthai = $request->trangthai;

            // Xử lý upload ảnh chương trình mới (nếu có)
            if ($request->hasFile('hinhanh')) {
                $file = $request->file('hinhanh');
                $fileName = Str::slug(str_replace('/', '-', $request->tieude)) . '.' . $file->getClientOriginalExtension();
                $path = public_path($this->uploadDir);

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                // Xóa ảnh cũ nếu có
                if ($chuongtrinh->hinhanh) {
                    $oldPath = public_path(str_replace($this->domain, '', $chuongtrinh->hinhanh));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $fileName;
                $file->move($path, $fileName);
                $chuongtrinh->hinhanh = $link_hinh_anh;
            }

            $chuongtrinh->save();

            // Lấy danh sách id quà tặng hiện có trong DB
            $existingIds = $chuongtrinh->quatangsukien->pluck('id')->toArray();

            $updatedIds = []; // Để lưu các quà tặng được update hoặc thêm mới

            // foreach ($request->quatangsukien as $item) {
            //     // Nếu có id => update, không có id => tạo mới
            //     if (!empty($item['id'])) {
            //         $quatang = QuatangsukienModel::find($item['id']);
            //         if ($quatang) {
            //             $quatang->id_chuongtrinh = $chuongtrinh->id;
            //             $quatang->id_bienthe = $item['id_bienthe'];
            //             $quatang->tieude = $item['tieude'];
            //             $quatang->thongtin = $item['thongtin'] ?? '';
            //             $quatang->dieukiensoluong = $item['dieukiensoluong'] ?? '';
            //             $quatang->dieukiengiatri = $item['dieukiengiatri'] ?? '';
            //             $quatang->trangthai = $item['trangthai'] ?? 'Hiển thị';
            //             $quatang->ngaybatdau = $item['ngaybatdau'] ?? null;
            //             $quatang->ngayketthuc = $item['ngayketthuc'] ?? null;

            //             // Xử lý upload ảnh quà tặng nếu có
            //             if (isset($item['hinhanh']) && $item['hinhanh'] instanceof \Illuminate\Http\UploadedFile) {
            //                 // Xóa ảnh cũ
            //                 if ($quatang->hinhanh) {
            //                     $oldGiftPath = public_path(str_replace($this->domain, '', $quatang->hinhanh));
            //                     if (file_exists($oldGiftPath)) {
            //                         unlink($oldGiftPath);
            //                     }
            //                 }

            //                 $giftFile = $item['hinhanh'];
            //                 $giftName = Str::slug(str_replace('/', '-', $item['tieude'])) . '.' . $giftFile->getClientOriginalExtension();
            //                 // $giftName = Str::slug($item['tieude']) . '.' . $giftFile->getClientOriginalExtension();
            //                 $giftPath = public_path($this->uploadDir);
            //                 if (!file_exists($giftPath)) mkdir($giftPath, 0755, true);
            //                 $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $giftName;
            //                 $giftFile->move($giftPath, $giftName);
            //                 $quatang->hinhanh = $link_hinh_anh;
            //             }

            //             $quatang->save();
            //             $updatedIds[] = $quatang->id;
            //         }
            //     } else {
            //         // Tạo mới quà tặng
            //         $quatang = new QuatangsukienModel();
            //         $quatang->id_chuongtrinh = $chuongtrinh->id;
            //         $quatang->id_bienthe = $item['id_bienthe'];
            //         $quatang->tieude = $item['tieude'];
            //         $quatang->thongtin = $item['thongtin'] ?? '';
            //         $quatang->dieukiensoluong = $item['dieukiensoluong'] ?? '';
            //         $quatang->dieukiengiatri = $item['dieukiengiatri'] ?? '';
            //         $quatang->trangthai = $item['trangthai'] ?? 'Hiển thị';
            //         $quatang->ngaybatdau = $item['ngaybatdau'] ?? null;
            //         $quatang->ngayketthuc = $item['ngayketthuc'] ?? null;

            //         if (isset($item['hinhanh']) && $item['hinhanh'] instanceof \Illuminate\Http\UploadedFile) {
            //             $giftFile = $item['hinhanh'];
            //             $giftName = Str::slug($item['tieude']) . '.' . $giftFile->getClientOriginalExtension();
            //             $giftPath = public_path($this->uploadDir);
            //             if (!file_exists($giftPath)) mkdir($giftPath, 0755, true);
            //             $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $giftName;
            //             $giftFile->move($giftPath, $giftName);
            //             $quatang->hinhanh = $link_hinh_anh;
            //         }

            //         $quatang->save();
            //         $updatedIds[] = $quatang->id;
            //     }
            // }
            foreach ($request->quatangsukien as $item) {

                // --- UPDATE ---
                if (!empty($item['id'])) {

                    $quatang = QuatangsukienModel::find($item['id']);

                    if ($quatang) {
                        $quatang->id_chuongtrinh = $chuongtrinh->id;
                        $quatang->id_bienthe = $item['id_bienthe'];
                        $quatang->tieude = $item['tieude'];
                        $quatang->thongtin = $item['thongtin'] ?? '';
                        $quatang->dieukiensoluong = $item['dieukiensoluong'] ?? '';
                        $quatang->dieukiengiatri = $item['dieukiengiatri'] ?? '';
                        $quatang->trangthai = $item['trangthai'] ?? 'Hiển thị';
                        $quatang->ngaybatdau = $item['ngaybatdau'] ?? null;
                        $quatang->ngayketthuc = $item['ngayketthuc'] ?? null;

                        // ✔ Nếu có upload ảnh → xử lý ảnh
                        if (isset($item['hinhanh']) && $item['hinhanh'] instanceof \Illuminate\Http\UploadedFile) {

                            // Xóa ảnh cũ
                            if ($quatang->hinhanh) {
                                $oldGiftPath = public_path(str_replace($this->domain, '', $quatang->hinhanh));
                                if (file_exists($oldGiftPath)) unlink($oldGiftPath);
                            }

                            $giftFile = $item['hinhanh'];
                            $giftName = Str::slug($item['tieude']) . '.' . $giftFile->getClientOriginalExtension();
                            $giftPath = public_path($this->uploadDir);

                            if (!file_exists($giftPath)) mkdir($giftPath, 0755, true);

                            $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $giftName;
                            $giftFile->move($giftPath, $giftName);

                            $quatang->hinhanh = $link_hinh_anh;

                        }
                        // ✔ Nếu KHÔNG upload ảnh → giữ ảnh cũ
                        // => Không làm gì cả

                        $quatang->save();
                        $updatedIds[] = $quatang->id;
                    }

                }
                // --- CREATE NEW ---
                else {

                    $quatang = new QuatangsukienModel();
                    $quatang->id_chuongtrinh = $chuongtrinh->id;
                    $quatang->id_bienthe = $item['id_bienthe'];
                    $quatang->tieude = $item['tieude'];
                    $quatang->thongtin = $item['thongtin'] ?? '';
                    $quatang->dieukiensoluong = $item['dieukiensoluong'] ?? '';
                    $quatang->dieukiengiatri = $item['dieukiengiatri'] ?? '';
                    $quatang->trangthai = $item['trangthai'] ?? 'Hiển thị';
                    $quatang->ngaybatdau = $item['ngaybatdau'] ?? null;
                    $quatang->ngayketthuc = $item['ngayketthuc'] ?? null;

                    // ✔ Nếu có upload ảnh → upload
                    if (isset($item['hinhanh']) && $item['hinhanh'] instanceof \Illuminate\Http\UploadedFile) {

                        $giftFile = $item['hinhanh'];
                        $giftName = Str::slug($item['tieude']) . '.' . $giftFile->getClientOriginalExtension();
                        $giftPath = public_path($this->uploadDir);

                        if (!file_exists($giftPath)) mkdir($giftPath, 0755, true);

                        $link_hinh_anh = $this->domain . $this->uploadDir . '/' . $giftName;
                        $giftFile->move($giftPath, $giftName);

                        $quatang->hinhanh = $link_hinh_anh;

                    } else {
                        // ✔ Tạo mới mà không có ảnh → set rỗng
                        $quatang->hinhanh = '';
                    }

                    $quatang->save();
                    $updatedIds[] = $quatang->id;
                }
            }

            // Xóa các quà tặng đã bị loại bỏ (không còn trong request)
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                foreach ($toDelete as $deleteId) {
                    $quatangDel = QuatangsukienModel::find($deleteId);
                    if ($quatangDel) {
                        // Xóa ảnh quà tặng cũ nếu có
                        if ($quatangDel->hinhanh) {
                            $oldGiftPath = public_path(str_replace($this->domain, '', $quatangDel->hinhanh));
                            if (file_exists($oldGiftPath)) {
                                unlink($oldGiftPath);
                            }
                        }
                        $quatangDel->delete();
                        // $quatangDel->forceDelete(); // maybe
                    }
                }
            }

            DB::commit();

            return redirect()->route('chuongtrinh.index')->with('success', 'Cập nhật chương trình và quà tặng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Lỗi: ' . $e->getMessage()])->withInput();
        }
    }


    /**
     * Xóa chương trình & quà tặng liên quan
     */
    public function destroy($id)
    {
        $chuongtrinh = ChuongTrinhModel::with('quatangsukien')->findOrFail($id);

        if ($chuongtrinh->hinhanh) {
            $filePath = public_path(str_replace($this->domain, '', $chuongtrinh->hinhanh));
            if (file_exists($filePath)) unlink($filePath);
        }

        foreach ($chuongtrinh->quatangsukien as $quatang) {
            if ($quatang->hinhanh) {
                $giftPath = public_path(str_replace($this->domain, '', $quatang->hinhanh));
                if (file_exists($giftPath)) unlink($giftPath);
            }
            $quatang->forceDelete();
        }

        $chuongtrinh->forceDelete();

        return redirect()->route('chuongtrinh.index')
            ->with('success', 'Đã xóa chương trình và toàn bộ quà tặng liên quan!');
    }
}

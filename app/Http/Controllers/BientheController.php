<?php

namespace App\Http\Controllers;

use App\Models\BientheModel;
use Illuminate\Http\Request;

use App\Models\DanhmucModel;
use App\Models\LoaibientheModel;
use App\Models\SanphamModel;

use App\Models\ThuongHieuModel;

class BientheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SanphamModel::with('bienthe', 'danhmuc');
        $bienthe = BientheModel::with('sanpham', 'loaibienthe');

        // Lấy kết quả
        $sanphams = $query->orderBydesc('id')->get();

        // Lấy thêm list danh mục & thương hiệu để render filter
        $cuaHang = ThuongHieuModel::all();
        $danhmucs = DanhmucModel::all();

        return view('khohang.khohang', compact('sanphams', 'bienthe', 'cuaHang', 'danhmucs'));
    }

    public function edit(Request $request, $id)
    {
        // $bienthe = Bienthesp::findOrFail($id);
        $bienthe = BientheModel::with(['loaibienthe', 'sanpham'])->findOrFail($id);
        $loaibienthes = LoaibientheModel::all();

        return view('khohang.suahangtonkho', compact('bienthe',  'loaibienthes'));
    }

    public function update(Request $request, $id)
    {
        // TỰ TÌM KIẾM: Tìm biến thể cần cập nhật
        $bienthe = BientheModel::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'gia' => 'required|numeric|min:0',
            'soluong' => 'required|integer|min:0',
            'id_loaibienthe' => 'required',
            'id_sanpham' => 'required',
        ]);

        $tenLoaiInput = $request->input('id_loaibienthe');
        $idTenLoai = null;

        if (is_numeric($tenLoaiInput)) {
            $idTenLoai = $tenLoaiInput;
        } else {
            $newLoai = LoaibientheModel::firstOrCreate(['ten' => $tenLoaiInput]);
            $idTenLoai = $newLoai->id;
        }

        // Cập nhật trực tiếp trên đối tượng đã tìm thấy
        $bienthe->giagoc = $request->input('gia');
        $bienthe->soluong = $request->input('soluong');
        $bienthe->id_sanpham = $request->input('id_sanpham');
        $bienthe->id_loaibienthe = $idTenLoai;

        // Lưu thay đổi
        $bienthe->save();

        // Chuyển hướng
        return redirect()->route('danh-sach-kho-hang')->with('success', 'Cập nhật hàng tồn thành công!');
    }

    public function destroy($id)
    {
        // 1. Tìm biến thể cần xóa, nếu không có sẽ báo lỗi 404
        $bienthe = BientheModel::findOrFail($id);

        // 2. Lấy sản phẩm cha của biến thể này
        $sanpham = $bienthe->sanpham;

        // 3. Kiểm tra điều kiện: Nếu sản phẩm cha chỉ có 1 biến thể (chính là cái sắp xóa)
        // thì không cho phép xóa.
        if ($sanpham && $sanpham->bienthe()->count() <= 1) {
            return redirect()->route('danh-sach-kho-hang') // Chuyển hướng về trang danh sách kho hàng/biến thể
                ->with('error', 'Không thể xóa! Sản phẩm phải có ít nhất một mặt hàng biến thể.');
        }

        // 4. Nếu điều kiện không bị vi phạm, tiến hành xóa
        $bienthe->delete();

        // 5. Trả về thông báo thành công
        return redirect()->route('danh-sach-kho-hang')
            ->with('success', 'Xóa mặt hàng tồn thành công!');
    }
}

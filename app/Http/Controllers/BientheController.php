<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sanpham;
use App\Models\Thuonghieu;
use App\Models\Danhmuc;
use App\Models\Bienthesp;
use App\Models\Loaibienthe;
use App\Models\ThongTinNguoiBanHang;

class BientheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SanPham::with('bienThe', 'danhmuc');
        $bienthe = Bienthesp::with('sanpham', 'loaiBienThe');

        // Lấy kết quả
        $sanphams = $query->orderBydesc('updated_at')->get();

        // Lấy thêm list danh mục & thương hiệu để render filter
        $cuaHang = ThongTinNguoiBanHang::all();
        $danhmucs = DanhMuc::all();

        return view('khohang', compact('sanphams', 'bienthe', 'cuaHang', 'danhmucs'));
    }

    public function edit(Request $request, $id)
    {
        // $bienthe = Bienthesp::findOrFail($id);
        $bienthe = Bienthesp::with(['loaiBienThe', 'sanpham'])->findOrFail($id);
        $loaibienthes = LoaiBienThe::all();

        return view('suahangtonkho', compact('bienthe',  'loaibienthes'));
    }

    public function update(Request $request, $id)
    {
        // TỰ TÌM KIẾM: Tìm biến thể cần cập nhật
        $bienthe = Bienthesp::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'gia' => 'required|numeric|min:0',
            'soluong' => 'required|integer|min:0',
            'id_tenloai' => 'required',
            'id_sanpham' => 'required',
        ]);

        $tenLoaiInput = $request->input('id_tenloai');
        $idTenLoai = null;

        if (is_numeric($tenLoaiInput)) {
            $idTenLoai = $tenLoaiInput;
        } else {
            $newLoai = Loaibienthe::firstOrCreate(['ten' => $tenLoaiInput]);
            $idTenLoai = $newLoai->id;
        }

        // Cập nhật trực tiếp trên đối tượng đã tìm thấy
        $bienthe->gia = $request->input('gia');
        $bienthe->soluong = $request->input('soluong');
        $bienthe->id_sanpham = $request->input('id_sanpham');
        $bienthe->id_tenloai = $idTenLoai;

        // Lưu thay đổi
        $bienthe->save();

        // Chuyển hướng
        return redirect()->route('danh-sach-kho-hang')->with('success', 'Cập nhật hàng tồn thành công!');
    }

    public function destroy($id)
    {
        // 1. Tìm biến thể cần xóa, nếu không có sẽ báo lỗi 404
        $bienthe = Bienthesp::findOrFail($id);

        // 2. Lấy sản phẩm cha của biến thể này
        $sanpham = $bienthe->sanpham;

        // 3. Kiểm tra điều kiện: Nếu sản phẩm cha chỉ có 1 biến thể (chính là cái sắp xóa)
        // thì không cho phép xóa.
        if ($sanpham && $sanpham->bienThe()->count() <= 1) {
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

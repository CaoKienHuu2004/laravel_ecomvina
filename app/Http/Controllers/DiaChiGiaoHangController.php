<?php

namespace App\Http\Controllers;

use App\Models\DiaChiGiaoHangModel;
use App\Models\NguoidungModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiaChiGiaoHangController extends Controller
{
    protected $provinces;

    public function __construct()
    {
        $this->provinces = config('tinhthanh');
    }

    /**
     * Hiển thị danh sách địa chỉ giao hàng (chưa xóa)
     */
    public function index(Request $request)
    {
        // $search = $request->input('search');

        // $query = DiaChiGiaoHangModel::orderByDesc('id');

        // if ($search) {
        //     $query->where(function($q) use ($search) {
        //         $q->where('hoten', 'like', "%{$search}%")
        //           ->orWhere('sodienthoai', 'like', "%{$search}%")
        //           ->orWhere('diachi', 'like', "%{$search}%")
        //           ->orWhere('tinhthanh', 'like', "%{$search}%");
        //     });
        // }

        // $diachis = $query->paginate(10)->withQueryString();

        // return view('diachigiaohang.index', compact('diachis', 'search'));
        $diachis = DiaChiGiaoHangModel::orderByDesc('id')->get(); // clientside paginate
        return view('diachigiaohang.index', compact('diachis'));
    }

    /**
     * Hiển thị form tạo mới địa chỉ giao hàng
     */
    public function create()
    {
        $tinhthanhs = collect($this->provinces)->pluck('ten')->toArray();
        $nguoidungs = NguoidungModel::where('vaitro', '!=', 'admin')
            ->get(['id', 'hoten', 'username']);
            $nguoidungs->transform(function ($nguoidung) {
                $parts = explode(',', $nguoidung->username);
                $nguoidung->username = $parts[0] ?? $nguoidung->username;
                return $nguoidung;
            });
        return view('diachigiaohang.create', compact('tinhthanhs', 'nguoidungs'));
    }

    /**
     * Lưu địa chỉ giao hàng mới
     */
    public function store(Request $request)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();

        $request->validate([
            'id_nguoidung'      => 'required|exists:nguoidung,id',
            'hoten'             => 'required|string|max:255',
            'sodienthoai'       => 'nullable|string|max:10',
            'diachi'            => 'required|string',
            'tinhthanh'         => ['required', 'string', Rule::in($provinceNames)],
            'trangthai'         => ['required', Rule::in(['Mặc định', 'Khác', 'Tạm ẩn'])],
        ]);

        // Nếu chọn 'Mặc định', cần đảm bảo chỉ có 1 địa chỉ mặc định cho người dùng này
        if ($request->trangthai === 'Mặc định') {
            DiaChiGiaoHangModel::where('id_nguoidung', $request->id_nguoidung)
                ->where('trangthai', 'Mặc định')
                ->update(['trangthai' => 'Khác']);
        }

        DiaChiGiaoHangModel::create([
            'id_nguoidung'  => $request->id_nguoidung,
            'hoten'         => $request->hoten,
            'sodienthoai'   => $request->sodienthoai,
            'diachi'        => $request->diachi,
            'tinhthanh'     => $request->tinhthanh,
            'trangthai'     => $request->trangthai,
        ]);

        return redirect()->route('diachigiaohang.index')->with('success', 'Thêm địa chỉ giao hàng thành công!');
    }

    /**
     * Hiển thị chi tiết địa chỉ giao hàng
     */
    public function show($id)
    {
        $diachi = DiaChiGiaoHangModel::findOrFail($id);
         // Lấy số lượng địa chỉ giao hàng của người dùng đó
        $countDiaChiNguoiDung = DiaChiGiaoHangModel::where('id_nguoidung', $diachi->id_nguoidung)->count();
        return view('diachigiaohang.show', compact('diachi','countDiaChiNguoiDung'));
    }

    /**
     * Hiển thị form chỉnh sửa địa chỉ giao hàng
     */
    public function edit($id)
    {
        $diachi = DiaChiGiaoHangModel::findOrFail($id);
        $tinhthanhs = collect($this->provinces)->pluck('ten')->toArray();
        $nguoidungs = NguoidungModel::where('vaitro', '!=', 'admin')
            ->get(['id', 'hoten', 'username']);
            $nguoidungs->transform(function ($nguoidung) {
                $parts = explode(',', $nguoidung->username);
                $nguoidung->username = $parts[0] ?? $nguoidung->username;
                return $nguoidung;
            });

        return view('diachigiaohang.edit', compact('diachi', 'tinhthanhs', 'nguoidungs'));
    }

    /**
     * Cập nhật địa chỉ giao hàng
     */
    public function update(Request $request, $id)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        $diachi = DiaChiGiaoHangModel::findOrFail($id);

        $request->validate([
            'id_nguoidung'      => 'required|exists:nguoidung,id',
            'hoten'             => 'required|string|max:255',
            'sodienthoai'       => 'nullable|string|max:10',
            'diachi'            => 'required|string',
            'tinhthanh'         => ['required', 'string', Rule::in($provinceNames)],
            'trangthai'         => ['required', Rule::in(['Mặc định', 'Khác', 'Tạm ẩn'])],
        ]);

        // Nếu chọn 'Mặc định', cần đảm bảo chỉ có 1 địa chỉ mặc định cho người dùng này
        if ($request->trangthai === 'Mặc định') {
            DiaChiGiaoHangModel::where('id_nguoidung', $request->id_nguoidung)
                ->where('trangthai', 'Mặc định')
                ->where('id', '!=', $id)
                ->update(['trangthai' => 'Khác']);
        }

        $diachi->update([
            'id_nguoidung'  => $request->id_nguoidung,
            'hoten'         => $request->hoten,
            'sodienthoai'   => $request->sodienthoai,
            'diachi'        => $request->diachi,
            'tinhthanh'     => $request->tinhthanh,
            'trangthai'     => $request->trangthai,
        ]);

        return redirect()->route('diachigiaohang.index')->with('success', 'Cập nhật địa chỉ giao hàng thành công!');
    }

    /**
     * Xóa mềm địa chỉ giao hàng
     */
    public function destroy($id)
    {
        $diachi = DiaChiGiaoHangModel::findOrFail($id);
        $diachi->delete();

        return redirect()->route('diachigiaohang.index')->with('success', 'Đã chuyển địa chỉ vào thùng rác!');
    }

    /**
     * Hiển thị địa chỉ đã xóa mềm (thùng rác)
     */
    public function trash()
    {
        $diachis = DiaChiGiaoHangModel::onlyTrashed()->orderByDesc('deleted_at')->get();  // clientside paginate
        // $diachis = DiaChiGiaoHangModel::onlyTrashed()->orderByDesc('deleted_at')->paginate(10);
        return view('diachigiaohang.trash', compact('diachis'));
    }

    /**
     * Khôi phục địa chỉ đã xóa mềm
     */
    public function restore($id)
    {
        $diachi = DiaChiGiaoHangModel::onlyTrashed()->findOrFail($id);
        $diachi->restore();

        return redirect()->route('diachigiaohang.trash')->with('success', 'Khôi phục địa chỉ thành công!');
    }

    /**
     * Xóa vĩnh viễn địa chỉ và dữ liệu liên quan (nếu có)
     */
    public function forceDelete($id)
    {
        $diachi = DiaChiGiaoHangModel::onlyTrashed()->findOrFail($id);
        $diachi->forceDelete();

        return redirect()->route('diachigiaohang.trash')->with('success', 'Đã xóa vĩnh viễn địa chỉ!');
    }
}

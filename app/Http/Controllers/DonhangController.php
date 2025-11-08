<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Donhang;
use App\Models\ChitietDonhang;
use App\Models\Nguoidung;
use App\Models\Sanpham;
use Illuminate\Http\Request;
use App\Models\Bienthe;
use App\Models\DonhangModel;
use App\Models\NguoidungModel;
use App\Models\SanphamModel;

class DonhangController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $query = DonhangModel::with(['khachhang', 'chitiet.bienthe.sanpham']);
        // ->where('is_deleted', 0); // nếu bạn muốn lọc đơn chưa xóa thì bỏ comment

        // Filter theo mã đơn
        if ($request->filled('ma_donhang')) {
            $query->where('ma_donhang', 'like', '%' . $request->ma_donhang . '%');
        }

        // Filter theo trạng thái
        if ($request->filled('trangthai')) {
            $query->where('trangthai', $request->trangthai);
        }

        // Filter theo khách hàng
        if ($request->filled('id_nguoidung')) {
            $query->where('id_nguoidung', $request->id_nguoidung);
        }

        // Filter theo ngày tạo
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter theo giá
        if ($request->filled('min_price')) {
            $query->where('tongtien', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('tongtien', '<=', $request->max_price);
        }

        $donhangs = $query->orderByDesc('created_at')->paginate(10);

        // Format dữ liệu
        $donhangs->getCollection()->transform(function ($donhang) {
            $donhang->tongsoluong   = $donhang->chitiet->sum('soluong');
            $donhang->tong_tien     = $donhang->chitiet->sum('tongtien');
            $donhang->ngay_tao      = $donhang->created_at
                ? $donhang->created_at->format('d/m/Y H:i')
                : null;
            $donhang->ten_khachhang = $donhang->khachhang->name ?? 'Khách lạ';
            $donhang->trangthai_text = match ($donhang->trangthai) {
                0 => 'Chờ thanh toán',
                1 => 'Đang giao',
                2 => 'Đã giao',
                3 => 'Đã hủy',
                default => 'Không rõ',
            };
            //TrangThai DonHang 'Chờ xử lý','Đã xác nhận','Đang chuẩn bị hàng','Đang giao hàng','Đã giao hàng','Đã hủy'
            //TrangThai ThanhToan 'Chưa thanh toán','Đã thanh toán','Thanh toán thất bại','Đã hoàn tiền'
            return $donhang;
        });

        return view('donhang.index', compact('donhangs'));
    }

    // Hiển thị form tạo đơn hàng
    public function create()
    {
        $customers = NguoidungModel::all();
        $products  = SanphamModel::with('bienthe')->get();
        return view('donhang.create', compact('customers', 'products'));
    }

    // Lưu đơn hàng mới
    public function store(Request $request)
    {
        $ma_donhang = $this->generateUniqueOrderCode();

        $validated = $request->validate([
            'ghichu'       => 'nullable|string',
            'trangthai'    => 'required|integer|in:0,1,2,3',
            'id_nguoidung' => 'nullable|integer',
            'id_magiamgia' => 'nullable|integer',
        ]);

        $validated['ma_donhang'] = $ma_donhang;

        DonhangModel::create($validated);

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Tạo đơn hàng thành công!');
    }

    // Hàm tạo mã đơn hàng ngẫu nhiên và duy nhất
    private function generateUniqueOrderCode()
    {
        do {
            $code = Str::upper(Str::random(5));
        } while (DonhangModel::where('ma_donhang', $code)->exists());

        return $code;
    }

    // Chi tiết đơn hàng
    public function show($id)
    {
        $donhang = DonhangModel::with(['khachhang', 'chitiet.sanpham'])
            ->findOrFail($id);

        return view('donhang.show', compact('donhang'));
    }

    // API chi tiết đơn hàng
    public function showApi($id)
    {
        $order = DonhangModel::with('chitiet')
            ->where('is_deleted', 0)
            ->findOrFail($id);

        return response()->json([
            'status'       => 'success',
            'order'        => $order,
            'total_price'  => $order->chitiets->sum('tongtien'),
            'total_amount' => $order->chitiets->sum('soluong'),
        ]);
    }

    // Form sửa đơn hàng
    public function edit($id)
    {
        $donhang = DonhangModel::with('khachhang')->findOrFail($id);
        $products = SanphamModel::all();

        return view('donhang.edit', compact('donhang', 'products'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id)
    {
        $donhang = DonhangModel::findOrFail($id);

        $validated = $request->validate([
            'ghichu'       => 'nullable|string',
            'trangthai'    => 'required|integer|in:0,1,2,3',
            'id_nguoidung' => 'nullable|integer',
            'id_magiamgia' => 'nullable|integer',
        ]);

        $donhang->update($validated);

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }

    // Xóa đơn hàng (ẩn dữ liệu)
    public function destroy($id)
    {
        $donhang = DonhangModel::findOrFail($id);
        $donhang->forceDelete();

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Đơn hàng đã được xóa!');
    }

    // API cập nhật số lượng sản phẩm trong đơn hàng
    public function updateItemQuantity(Request $request, $orderId, $itemId)
    {
        $validated = $request->validate([
            'soluong' => 'required|integer|min:1',
        ]);

        $order = DonhangModel::with('chitiet')->where('is_deleted', 0)->findOrFail($orderId);
        $item = $order->chitiets()->where('id', $itemId)->first();

        if (!$item) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sản phẩm không tồn tại trong đơn hàng',
            ], 404);
        }

        $item->soluong  = $validated['soluong'];
        $item->tongtien = $item->gia * $item->soluong;
        $item->save();

        return response()->json([
            'status'       => 'success',
            'message'      => 'Cập nhật số lượng thành công',
            'total_price'  => $order->chitiets->sum('tongtien'),
            'total_amount' => $order->chitiets->sum('soluong'),
        ]);
    }
}

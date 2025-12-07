<?php

// namespace App\Http\Controllers;

// use Illuminate\Support\Str;

// use Illuminate\Http\Request;

// use App\Models\DonhangModel;
// use App\Models\NguoidungModel;
// use App\Models\SanphamModel;

// class DonhangController extends Controller
// {
//     // Danh sách đơn hàng
//     public function index(Request $request)
//     {
//         $query = DonhangModel::with(['khachhang', 'chitiet.bienthe.sanpham']);
//         // ->where('is_deleted', 0); // nếu bạn muốn lọc đơn chưa xóa thì bỏ comment

//         // Filter theo mã đơn
//         if ($request->filled('ma_donhang')) {
//             $query->where('ma_donhang', 'like', '%' . $request->ma_donhang . '%');
//         }

//         // Filter theo trạng thái
//         if ($request->filled('trangthai')) {
//             $query->where('trangthai', $request->trangthai);
//         }

//         // Filter theo khách hàng
//         if ($request->filled('id_nguoidung')) {
//             $query->where('id_nguoidung', $request->id_nguoidung);
//         }

//         // Filter theo ngày tạo
//         if ($request->filled('from_date')) {
//             $query->whereDate('created_at', '>=', $request->from_date);
//         }
//         if ($request->filled('to_date')) {
//             $query->whereDate('created_at', '<=', $request->to_date);
//         }

//         // Filter theo giá
//         if ($request->filled('min_price')) {
//             $query->where('tongtien', '>=', $request->min_price);
//         }
//         if ($request->filled('max_price')) {
//             $query->where('tongtien', '<=', $request->max_price);
//         }

//         $donhangs = $query->orderByDesc('created_at')->paginate(10);

//         // Format dữ liệu
//         $donhangs->getCollection()->transform(function ($donhang) {
//             $donhang->tongsoluong   = $donhang->chitiet->sum('soluong');
//             $donhang->tong_tien     = $donhang->chitiet->sum('tongtien');
//             $donhang->ngay_tao      = $donhang->created_at
//                 ? $donhang->created_at->format('d/m/Y H:i')
//                 : null;
//             $donhang->ten_khachhang = $donhang->khachhang->name ?? 'Khách lạ';
//             $donhang->trangthai_text = match ($donhang->trangthai) {
//                 0 => 'Chờ thanh toán',
//                 1 => 'Đang giao',
//                 2 => 'Đã giao',
//                 3 => 'Đã hủy',
//                 default => 'Không rõ',
//             };
//             //TrangThai DonHang 'Chờ xử lý','Đã xác nhận','Đang chuẩn bị hàng','Đang giao hàng','Đã giao hàng','Đã hủy'
//             //TrangThai ThanhToan 'Chưa thanh toán','Đã thanh toán','Thanh toán thất bại','Đã hoàn tiền'
//             return $donhang;
//         });

//         return view('donhang.index', compact('donhangs'));
//     }

//     // Hiển thị form tạo đơn hàng
//     public function create()
//     {
//         $customers = NguoidungModel::all();
//         $products  = SanphamModel::with('bienthe')->get();
//         return view('donhang.create', compact('customers', 'products'));
//     }

//     // Lưu đơn hàng mới
//     public function store(Request $request)
//     {
//         $ma_donhang = $this->generateUniqueOrderCode();

//         $validated = $request->validate([
//             'ghichu'       => 'nullable|string',
//             'trangthai'    => 'required|integer|in:0,1,2,3',
//             'id_nguoidung' => 'nullable|integer',
//             'id_magiamgia' => 'nullable|integer',
//         ]);

//         $validated['ma_donhang'] = $ma_donhang;

//         DonhangModel::create($validated);

//         return redirect()->route('danh-sach-don-hang')
//             ->with('success', 'Tạo đơn hàng thành công!');
//     }

//     // Hàm tạo mã đơn hàng ngẫu nhiên và duy nhất
//     private function generateUniqueOrderCode()
//     {
//         do {
//             $code = Str::upper(Str::random(5));
//         } while (DonhangModel::where('ma_donhang', $code)->exists());

//         return $code;
//     }

//     // Chi tiết đơn hàng
//     public function show($id)
//     {
//         $donhang = DonhangModel::with(['khachhang', 'chitiet.sanpham'])
//             ->findOrFail($id);

//         return view('donhang.show', compact('donhang'));
//     }

//     // API chi tiết đơn hàng
//     public function showApi($id)
//     {
//         $order = DonhangModel::with('chitiet')
//             ->where('is_deleted', 0)
//             ->findOrFail($id);

//         return response()->json([
//             'status'       => 'success',
//             'order'        => $order,
//             'total_price'  => $order->chitiets->sum('tongtien'),
//             'total_amount' => $order->chitiets->sum('soluong'),
//         ]);
//     }

//     // Form sửa đơn hàng
//     public function edit($id)
//     {
//         $donhang = DonhangModel::with('khachhang')->findOrFail($id);
//         $products = SanphamModel::all();

//         return view('donhang.edit', compact('donhang', 'products'));
//     }

//     // Cập nhật đơn hàng
//     public function update(Request $request, $id)
//     {
//         $donhang = DonhangModel::findOrFail($id);

//         $validated = $request->validate([
//             'ghichu'       => 'nullable|string',
//             'trangthai'    => 'required|integer|in:0,1,2,3',
//             'id_nguoidung' => 'nullable|integer',
//             'id_magiamgia' => 'nullable|integer',
//         ]);

//         $donhang->update($validated);

//         return redirect()->route('danh-sach-don-hang')
//             ->with('success', 'Cập nhật đơn hàng thành công!');
//     }

//     // Xóa đơn hàng (ẩn dữ liệu)
//     public function destroy($id)
//     {
//         $donhang = DonhangModel::findOrFail($id);
//         $donhang->forceDelete();

//         return redirect()->route('danh-sach-don-hang')
//             ->with('success', 'Đơn hàng đã được xóa!');
//     }

//     // API cập nhật số lượng sản phẩm trong đơn hàng
//     public function updateItemQuantity(Request $request, $orderId, $itemId)
//     {
//         $validated = $request->validate([
//             'soluong' => 'required|integer|min:1',
//         ]);

//         $order = DonhangModel::with('chitiet')->where('is_deleted', 0)->findOrFail($orderId);
//         $item = $order->chitiets()->where('id', $itemId)->first();

//         if (!$item) {
//             return response()->json([
//                 'status'  => 'error',
//                 'message' => 'Sản phẩm không tồn tại trong đơn hàng',
//             ], 404);
//         }

//         $item->soluong  = $validated['soluong'];
//         $item->tongtien = $item->gia * $item->soluong;
//         $item->save();

//         return response()->json([
//             'status'       => 'success',
//             'message'      => 'Cập nhật số lượng thành công',
//             'total_price'  => $order->chitiets->sum('tongtien'),
//             'total_amount' => $order->chitiets->sum('soluong'),
//         ]);
//     }
// }


namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\DonhangModel;
use App\Models\NguoidungModel;
use App\Models\SanphamModel;
use App\Models\PhuongthucModel;
use App\Models\ChitietdonhangModel;
use App\Models\PhiVanChuyenModel;
use App\Models\DiaChiGiaoHangModel;
use App\Models\MagiamgiaModel;

class DonhangController extends Controller
{
    /* ===========================
        DANH SÁCH ĐƠN HÀNG
    ==============================*/
    public function index(Request $request)
    {
        $query = DonhangModel::with(['nguoidung', 'chitiet.bienthe.sanpham']);

        // Lọc mã đơn
        if ($request->filled('ma_donhang')) {
            $query->where('ma_donhang', 'like', '%' . $request->ma_donhang . '%');
        }

        // Lọc trạng thái
        if ($request->filled('trangthai')) {
            $query->where('trangthai', $request->trangthai);
        }

        // Lọc khách hàng
        if ($request->filled('id_nguoidung')) {
            $query->where('id_nguoidung', $request->id_nguoidung);
        }

        // Lọc theo ngày
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Lọc giá
        if ($request->filled('min_price')) {
            $query->where('thanhtien', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('thanhtien', '<=', $request->max_price);
        }

        $donhangs = $query->orderByDesc('created_at')->paginate(10);

        // Format
        $donhangs->transform(function ($donhang) {
            $donhang->tongsoluong   = $donhang->chitiet->sum('soluong');
            $donhang->tong_tien     = $donhang->chitiet->sum('tongtien');

            // $item->created_at ? $item->created_at->toIso8601String() : null,
            $donhang->ngay_tao      = $donhang->created_at
                ? $donhang->created_at->format('d/m/Y H:i')
                : null;

            $donhang->username      = $donhang->nguoidung->name ?? 'Khách lạ';

            return $donhang;
        });

        return view('donhang.index', compact('donhangs'));
    }

    /* ===========================
        FORM TẠO ĐƠN
    ==============================*/
    public function create()
    {
        return view('donhang.create', [
            'customers'      => NguoidungModel::all(),
            'products'       => SanphamModel::with('bienthe')->get(),
            'phuongthuc'     => PhuongthucModel::all(),
            'phivanchuyen'   => PhiVanChuyenModel::all(),
            'diachigiaohang' => DiaChiGiaoHangModel::all(),
            'magiamgia'      => MagiamgiaModel::all(),
        ]);
    }

    /* ===========================
        TẠO MÃ ĐƠN
    ==============================*/
    private function generateUniqueMadon()
    {
        do {
            $letters = strtoupper(Str::random(2));
            $numbers = rand(10000, 99999);
            $madon = $letters . $numbers;

        } while (DB::table('donhang')->where('ma_donhang', $madon)->exists());

        return $madon;
    }

    /* ===========================
        LƯU ĐƠN HÀNG
    ==============================*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trangthai'        => 'required|string',
            'id_nguoidung'     => 'nullable|integer',
            'id_phuongthuc'    => 'nullable|integer',
            'id_magiamgia'     => 'nullable|integer',
            'id_phivanchuyen'  => 'nullable|integer',
            'id_diachigiaohang'=> 'nullable|integer',
            'tongtien'         => 'required|numeric'
        ]);

        // Tính tổng số lượng
        $products = $request->input('products', []);
        $tongsoluong = array_sum(array_column($products, 'qty'));

        // Tạo đơn hàng
        $order = DonhangModel::create([
            'ma_donhang'       => $this->generateUniqueMadon(),
            'trangthai'        => $validated['trangthai'],
            'id_nguoidung'     => $validated['id_nguoidung'] ?? null,
            'id_phuongthuc'    => $validated['id_phuongthuc'] ?? null,
            'id_magiamgia'     => $validated['id_magiamgia'] ?? null,
            'id_phivanchuyen'  => $validated['id_phivanchuyen'] ?? null,
            'id_diachigiaohang'=> $validated['id_diachigiaohang'] ?? null,
            'tongsoluong'      => $tongsoluong,
            'tamtinh'          => $validated['tongtien'],
            'thanhtien'        => $validated['tongtien'],
        ]);

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    /* ===========================
        CHI TIẾT ĐƠN
    ==============================*/
    public function show($id)
    {
        $donhang = DonhangModel::with([
            'nguoidung',
            'chitiet.bienthe.sanpham'
        ])->findOrFail($id);

        return view('donhang.show', compact('donhang'));
    }

    /* ===========================
        FORM SỬA ĐƠN
    ==============================*/
    public function edit($id)
    {
        return view('donhang.edit', [
            'donhang'        => DonhangModel::with(['nguoidung','chitiet'])->findOrFail($id),
            'nguoidung'      => NguoidungModel::all(),
            'products'       => SanphamModel::with('bienthe')->get(),
            'phuongthuc'     => PhuongthucModel::all(),
            'phivanchuyen'   => PhiVanChuyenModel::all(),
            'diachigiaohang' => DiaChiGiaoHangModel::all(),
            'magiamgia'      => MagiamgiaModel::all(),
        ]);
    }

    /* ===========================
        CẬP NHẬT ĐƠN HÀNG
    ==============================*/
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'trangthai'       => 'required|string',
            'id_nguoidung'    => 'nullable|integer',
            'id_phuongthuc'   => 'nullable|integer',
            'id_magiamgia'    => 'nullable|integer',
            'id_phivanchuyen' => 'nullable|integer',
            'id_diachigiaohang'=> 'nullable|integer',
            'tongtien'        => 'required|numeric',
        ]);

        $donhang = DonhangModel::findOrFail($id);

        $donhang->update([
            'trangthai'    => $validated['trangthai'],
            'id_nguoidung' => $validated['id_nguoidung'] ?? null,
            'id_phuongthuc'=> $validated['id_phuongthuc'] ?? null,
            'id_magiamgia' => $validated['id_magiamgia'] ?? null,
            'id_phivanchuyen' => $validated['id_phivanchuyen'] ?? null,
            'id_diachigiaohang'=> $validated['id_diachigiaohang'] ?? null,
            'thanhtien'       => $validated['tongtien'],
            'tamtinh'         => $validated['tongtien'],
        ]);

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }

    /* ===========================
        XÓA ĐƠN HÀNG
    ==============================*/
    public function destroy($id)
    {
        DonhangModel::findOrFail($id)->forceDelete();

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Đơn hàng đã được xóa!');
    }




}

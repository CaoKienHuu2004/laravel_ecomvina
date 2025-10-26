<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Donhang;
use App\Models\ChitietDonhang;
use App\Models\Nguoidung;
use App\Models\Sanpham;
use Illuminate\Http\Request;
use App\Models\Bienthe;
use App\Models\Bienthesp;
use App\Models\Phuongthucthanhtoan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonhangNguyenController extends Controller
{
    // ========================
    // ---DANH SÁCH ĐƠN HÀNG---
    // ========================
    public function index(Request $request)
    {
        $query = Donhang::with(['khachhang', 'chitiet.sanpham']);
        // ==============
        // --- BỘ LỌC ---
        // ==============
        if ($request->filled('ma_donhang')) {
            $query->where('ma_donhang', 'like', '%' . $request->ma_donhang . '%');
        }

        if ($request->filled('trangthai')) {
            $query->where('trangthai', $request->trangthai);
        }

        if ($request->filled('id_nguoidung')) {
            $query->where('id_nguoidung', $request->id_nguoidung);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('min_price')) {
            $query->where('tongtien', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('tongtien', '<=', $request->max_price);
        }
        $donhangs = $query->orderByDesc('created_at')->paginate(10);
        // ======================
        // --- FORMAT DỮ LIỆU ---
        // ======================
        $donhangs->getCollection()->transform(function ($donhang) {
            $donhang->tongsoluong = $donhang->chitiet->sum('soluong');
            $donhang->tong_tien   = $donhang->chitiet->sum('tongtien');
            $donhang->ngay_tao    = $donhang->created_at
                ? \Carbon\Carbon::parse($donhang->created_at)->format('d/m/Y H:i')
                : null;
            $donhang->ten_khachhang = $donhang->khachhang->hoten ?? $donhang->khachhang->name ?? 'Khách lạ';
            $trangthai = trim(strtolower($donhang->trangthai));

            $donhang->trangthai_text = match ($trangthai) {
                'cho_xac_nhan' => 'Chờ xác nhận',
                'da_xac_nhan'  => 'Đã xác nhận',
                'dang_giao'    => 'Đang giao',
                'da_giao'      => 'Đã giao',
                'da_huy'       => 'Đã hủy',
                default        => 'Không rõ',
            };

            return $donhang;
        });

        return view('donhang.index', compact('donhangs'));
    }

    // ===========================
    // ---HIỂN THỊ FORM TẠO ĐƠN---
    // ===========================
    public function create()
    {
        $phuongthuc_thanhtoan = DB::table('phuongthuc_thanhtoan')
            ->where('trangthai', 'hoat_dong')
            ->orderBy('id')
            ->get();
        $customers = Nguoidung::all();
        $products  = Sanpham::with('bienthe')->get();
        return view('donhang.create', compact('customers', 'products','phuongthuc_thanhtoan'));
    }

    // ======================
    // ---LƯU ĐƠN HÀNG MỚI---
    // ======================
    public function store(Request $request)
    {
        $ma_donhang = $this->generateUniqueOrderCode();

        $validated = $request->validate([
            'ghichu'       => 'nullable|string',
            'trangthai'    => 'required|string|in:cho_xac_nhan,da_giao,da_huy,da_xac_nhan,dang_giao',
            'id_nguoidung' => 'nullable|integer',
            'id_magiamgia' => 'nullable|integer',
            'id_phuongthuc_thanhtoan' => 'nullable|integer|exists:phuongthuc_thanhtoan,id',
        ]);

        $validated['ma_donhang']  = $ma_donhang;
        $validated['tongtien']    = $request->input('tongtien', 0);
        $validated['tongsoluong'] = 0;
        $validated['ngaytao']     = now();
        $validated['created_at']  = now();
        $validated['updated_at']  = now();

        $donhang = Donhang::create($validated);

        if ($request->has('products')) {
            $tongsoluong = 0;

            foreach ($request->products as $p) {
                if (!empty($p['id']) && !empty($p['qty'])) {

                    // 🟢 Vì $p['id'] là ID biến thể, nên lấy từ bảng Bienthe
                    $bienthe = Bienthesp::with('sanpham')->find($p['id']);

                    if ($bienthe) {
                        $gia = $bienthe->gia ?? 0;

                        ChitietDonhang::create([
                            'id_donhang' => $donhang->id,
                            'id_sanpham' => $bienthe->sanpham->id,
                            'id_bienthe' => $bienthe->id,
                            'soluong'    => $p['qty'],
                            'gia'        => $gia,
                            'tongtien'   => $gia * $p['qty'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $tongsoluong += $p['qty'];
                    } else {
                        Log::warning('Không tìm thấy biến thể ID: '.$p['id']);
                    }
                }
            }

            $donhang->update(['tongsoluong' => $tongsoluong]);
        }

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Tạo đơn hàng thành công!');
    }

    // ================================================================
    // ---ĐẶT MÃ ĐƠN HÀNG THÀNH 5 KÍ TỰ BAO GỒM CHỮ VÀ SỐ NGẪU NHIÊN---
    // ================================================================
    private function generateUniqueOrderCode()
    {
        do {
            $code = Str::upper(Str::random(5));
        } while (Donhang::where('ma_donhang', $code)->exists());
        return $code;
    }

    // =====================================
    // ---HIỂN THỊ FORM CHI TIẾT ĐƠN HÀNG---
    // =====================================
    public function show($id)
    {
        $donhang = Donhang::with(['khachhang', 'chitiet.sanpham'])->findOrFail($id);
        return view('donhang.show', compact('donhang'));
    }

    // =====================================
    // ---HIỂN THỊ FORM CHỈNH SỬA ĐƠN HÀNG---
    // =====================================
    public function edit($id)
    {
        $donhang = Donhang::findOrFail($id);
        $khachhangs = Nguoidung::all();
        $phuongthucs = PhuongthucThanhtoan::all();

        return view('donhang.edit', compact('donhang', 'khachhangs', 'phuongthucs'));
    }

    // =======================
    // ---CẬP NHẬT ĐƠN HÀNG---
    // =======================
    public function update(Request $request, $id)
    {
        $donhang = Donhang::findOrFail($id);

        $validated = $request->validate([
            'ghichu'       => 'nullable|string',
            'trangthai'    => 'required|string|in:cho_xac_nhan,da_xac_nhan,dang_giao,da_giao,da_huy',
            'id_nguoidung' => 'nullable|integer',
            'id_magiamgia' => 'nullable|integer',
        ]);

        $donhang->update($validated);

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }

    // ============================
    // ---XÓA VĨNH VIỄN ĐƠN HÀNG---
    // ============================
    public function destroy($id)
    {
        $donhang = Donhang::findOrFail($id);
        $donhang->forceDelete();
        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Đơn hàng đã được xóa!');
    }

    public function updateItemQuantity(Request $request, $orderId, $itemId)
    {
        $validated = $request->validate([
            'soluong' => 'required|integer|min:1',
        ]);

        $order = Donhang::with('chitiet')->findOrFail($orderId);
        $item = $order->chitiet()->where('id', $itemId)->first();

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
            'total_price'  => $order->chitiet->sum('tongtien'),
            'total_amount' => $order->chitiet->sum('soluong'),
        ]);
    }


}

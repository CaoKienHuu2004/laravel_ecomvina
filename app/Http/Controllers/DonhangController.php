<?php

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
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $query = DonhangModel::with(['nguoidung', 'chitiet.bienthe.sanpham']);
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
            $donhang->username = $donhang->nguoidung->name ?? 'Khách lạ';
            $donhang->trangthai = match ($donhang->trangthai) {
                'Chờ xử lý' => 'Chờ xử lý',
                'Đã xác nhận' => 'Đã xác nhận',
                'Đang chuẩn bị hàng' => 'Đang chuẩn bị hàng',
                'Đang giao hàng' => 'Đang giao hàng',
                'Đã giao hàng' => 'Đã giao hàng',
                'Đã hủy' => 'Đã hủy',
                default => 'Không rõ',
            };
            $donhang->trangthaithanhtoan = match ($donhang->trangthaithanhtoan) {
                'Chưa thanh toán' => 'Chưa thanh toán',
                'Đã thanh toán' => 'Đã thanh toán',
                'Thanh toán thất bại' => 'Thanh toán thất bại',
                'Đã hoàn tiền' => 'Đã hoàn tiền',
                default => 'Không rõ',
            };
            return $donhang;
        });

        return view('donhang.index', compact('donhangs'));
    }

    // Hiển thị form tạo đơn hàng
    public function create()
    {
        $customers = NguoidungModel::all();
        $products  = SanphamModel::with('bienthe')->get();
        $phuongthuc = PhuongthucModel::all();
        $phivanchuyen = PhiVanChuyenModel::all();
        $diachigiaohang = DiaChiGiaoHangModel::all();
        $magiamgia = MagiamgiaModel::all();
        
        return view('donhang.create', compact('customers', 'products', 'phuongthuc', 'phivanchuyen', 'diachigiaohang', 'magiamgia'));
        
    }
    
    //-----Radom 7 kí tự gồm 2 chữ và 5 số-----
    function generateUniqueMadon($length = 5) {
        do {
            $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));// Tạo 2 ký tự chữ ngẫu nhiên
            $numbers = rand(10000, 99999); // Tạo 5 ký tự số ngẫu nhiên
            $madon = $letters . $numbers;// Kết hợp lại thành mã đơn hàng
        } while (DB::table('donhang')->where('madon', $madon)->exists());  // Kiểm tra xem mã đã tồn tại chưa
        return $madon;
    }
    //-----Lưu đơn hàng mới-----
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validated = $request->validate([
            'trangthai'    => 'required|string',
            'id_nguoidung' => 'nullable|integer',
            'id_phuongthuc'=> 'nullable|integer',
            'id_magiamgia'=> 'nullable|integer',
            'id_phivanchuyen'=> 'nullable|integer',
            'id_diachigiaohang'=> 'nullable|integer',
            'madon'        => 'nullable|string',
            'tongsoluong'  => 'nullable|integer',
            'tongtien'     => 'required|numeric',  // Validate tổng tiền
        ]); 
        $products = $request->input('products', []);   
        $tongsoluong = 0;
        foreach ($products as $product) {
            $qty = $product['qty'] ?? 0;
            $tongsoluong += $qty;
        }
        $validated['tongsoluong'] = $tongsoluong;
        // Lưu vào cơ sở dữ liệu
        $order = DonhangModel::create([
            'trangthai' => $validated['trangthai'],
            'id_nguoidung' => isset($validated['id_nguoidung']) ? $validated['id_nguoidung'] : null,
            'id_phuongthuc' => isset($validated['id_phuongthuc']) ? $validated['id_phuongthuc'] : null,
            'id_magiamgia' => isset($validated['id_magiamgia']) ? $validated['id_magiamgia'] : null,
            'id_phivanchuyen' => isset($validated['id_phivanchuyen']) ? $validated['id_phivanchuyen'] : null,
            'id_diachigiaohang' => isset($validated['id_diachigiaohang']) ? $validated['id_diachigiaohang'] : null,
            'madon' => $validated['madon'] ?? $this->generateUniqueMadon(),
            'tongsoluong' => isset($validated['tongsoluong']) ? $validated['tongsoluong'] : null,
            'tamtinh' => $validated['tongtien'],  // Lưu tổng tiền vào database
            'thanhtien' => $validated['tongtien'],  // Lưu tổng tiền vào database
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Trả về thông báo thành công
        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Đơn hàng đã được lưu thành công!');
    }

    

    // Chi tiết đơn hàng
    public function show($id)
    {
        $donhang = DonhangModel::with(['nguoidung', 'chitiet.donhang'])
            ->findOrFail($id);

        return view('donhang.show', compact('donhang'));
    }

    // Form sửa đơn hàng
   public function edit($id)
    {
         $donhang = DonhangModel::with(['nguoidung', 'chitiet.donhang'])
            ->findOrFail($id);
        $nguoidung = NguoidungModel::all();
        $products  = SanphamModel::with('bienthe')->get();
        $phuongthuc = PhuongthucModel::all();
        $phivanchuyen = PhiVanChuyenModel::all();
        $diachigiaohang = DiaChiGiaoHangModel::all();
        $magiamgia = MagiamgiaModel::all();

        
        return view('donhang.edit', compact('donhang','nguoidung', 'products', 'phuongthuc', 'phivanchuyen', 'diachigiaohang', 'magiamgia'));
    }


    // Cập nhật đơn hàng
    public function update(Request $request, $id)
    {
        // Tìm đơn hàng theo ID
        $donhang = DonhangModel::findOrFail($id);

        // Xác thực dữ liệu
        $validated = $request->validate([
            'trangthai'    => 'required|string',
            'id_nguoidung' => 'nullable|integer',
            'id_phuongthuc'=> 'nullable|integer',
            'id_magiamgia'=> 'nullable|integer',
            'id_phivanchuyen'=> 'nullable|integer',
            'id_diachigiaohang'=> 'nullable|integer',
            'madon'        => 'nullable|string',
            'tongsoluong'  => 'nullable|integer',
            'tongtien'     => 'required|numeric',
        ]);

        // Cập nhật đơn hàng
        $donhang->update($validated);

        // Quay lại danh sách đơn hàng với thông báo thành công
        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }


    // Xóa đơn hàng (xóa vĩnh viễn)
    public function destroy($id)
    {
        $donhang = DonhangModel::findOrFail($id);
        $donhang->forceDelete();

        return redirect()->route('danh-sach-don-hang')
            ->with('success', 'Đơn hàng đã được xóa!');
    }

    
}

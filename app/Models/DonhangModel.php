<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonhangModel extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'donhang';


    protected $primaryKey = 'id';


    public $timestamps = true;


    protected $fillable = [
        'id_phuongthuc',
        'id_magiamgia',
        'id_nguoidung',
        'id_phivanchuyen',
        'id_diachigiaohang',
        'madon',
        'tongsoluong',
        'tamtinh',
        'thanhtien',
        'trangthaithanhtoan',
        'trangthai',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'tongsoluong' => 'integer',
        'tamtinh' => 'integer',
        'thanhtien' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    // Cách lấy dữ liệu bao gồm cả bản ghi đã xóa mềm chỉ riêng admin mới dùng để xem
    // $donhang = DonhangModel::withTrashed()->find($id);
    // $donhang->makeVisible(['created_at', 'updated_at', 'deleted_at']);
    // return response()->json($donhang);

    /**
     * 🔗 Quan hệ: Một đơn hàng thuộc về một người dùng
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
    }
        public function khachhang()
        {
            return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
        }

    /**
     * 🔗 Quan hệ: Một đơn hàng có thể có một mã giảm giá
     */
    public function magiamgia()
    {
        return $this->belongsTo(MagiamgiaModel::class, 'id_magiamgia');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có một phương thức thanh toán/vận chuyển
     */
    public function phuongthuc()
    {
        return $this->belongsTo(PhuongthucModel::class, 'id_phuongthuc');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có một phí vận chuyển
     */
    public function phivanchuyen()
    {
        return $this->belongsTo(PhiVanChuyenModel::class, 'id_phivanchuyen');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có một địa chỉ giao hàng
     */
    public function diachigiaohang()
    {
        return $this->belongsTo(DiaChiGiaoHangModel::class, 'id_diachigiaohang');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
     */
    public function chitietdonhang()
    {
        return $this->hasMany(ChitietdonhangModel::class, 'id_donhang');
    }
        public function chitiet()
        {
            return $this->hasMany(ChitietdonhangModel::class, 'id_donhang');
        }

    /**
     * 🧭 Scope lọc theo trạng thái xử lý
     */
    public function scopeTrangThai($query, $status)
    {
        return $query->where('trangthai', $status);
    }

    /**
     * 🧭 Scope lọc theo trạng thái thanh toán
     */
    public function scopeThanhToan($query, $status)
    {
        return $query->where('trangthaithanhtoan', $status);
    }

    /**
     * 🧮 Hàm tính tổng tiền đã thanh toán (có thể dùng khi thống kê)
     */
    public static function tongTienDaThanhToan()
    {
        return self::where('trangthaithanhtoan', 'Đã thanh toán')->sum('thanhtien');
    }
    public static function generateOrderCode()
    {
        // $prefix = 'VNA'; // hoặc "SIEUVINA" nếu tránh unicode
        // $time   = now()->format('YmdHis'); // 20251117, hoặc 20251117173245 (giây)
        // $rand   = strtoupper(Str::random(6)); // độ dài tùy chỉnh
        // return "{$prefix}{$time}{$rand}";
        // $prefix = 'VNA'; // 3 ký tự // VNA
        // $time = now()->format('Hi'); // GiờPhútGiây, ví dụ: 214016
        // // $rand   = strtoupper(Str::random(1)); // 1 ký tự -> Tổng = 10

        // return "{$prefix}{$time}";
        // $prefix = 'VNA';                 // 3 ký tự
        // $time   = now()->format('Hi');   // 4 ký tự: Hour + Minute
        // $month  = substr(now()->format('m'), -1); // Lấy số cuối của tháng

        // return "{$prefix}{$month}{$time}"; // Tổng = 3 + 4 + 1 = 8 ký tự
        $prefix = 'VNA';                   // 3 ký tự
        $time   = now()->format('Hi');     // 4 ký tự (giờ + phút)
        $month  = now()->format('m');      // 2 ký tự
        $ramd = rand(0, 9); // 1 ký tự ngẫu nhiên
        // $ramd = strtoupper(Str::random(1)); // 1 ký tự ngẫu nhiên

        return "{$prefix}{$month}{$time}{$ramd}"; // Tổng: 3 + 2 + 4 = 9 ký tự + 1 = 10 ký tự
    }
    /**
     * //Model động lấy field enum động
     */
    public static function getEnumValues($column)
    {
        $table = (new static)->getTable();

        $result = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'");

        preg_match_all("/'([^']+)'/", $result[0]->Type, $matches);

        return $matches[1];
    }


    //--------------- method của Nguyên : begin ------------------ //
    public function capNhatSoLuongVaLuotBan()
    {
        // Lặp qua tất cả các chi tiết đơn hàng
        foreach ($this->chitietdonhang as $chitiet) {
            // Giả sử bảng SanphamModel có cột luotban và soluong
            $sanpham = $chitiet->sanpham;

            if ($this->trangthai == 'Đã hoàn tất') {
                // Cập nhật số lượng sản phẩm
                $sanpham->soluong -= $chitiet->soluong;
                $sanpham->luotban += $chitiet->soluong;
            } elseif ($this->trangthai == 'Đã hủy') {
                // Cập nhật số lượng khi hủy đơn hàng (thêm lại số lượng)
                $sanpham->soluong += $chitiet->soluong;
                $sanpham->luotban -= $chitiet->soluong;
            }

            $sanpham->save();
        }
    }
    public function capNhatTrangThai($newStatus)
    {
        $this->trangthai = $newStatus;
        $this->save();

        // Sau khi thay đổi trạng thái, cập nhật số lượng và lượt bán
        $this->capNhatSoLuongVaLuotBan();
    }

    /**
     * 🧭 Hàm tạo mã đơn hàng tự động
     */
    public static function generateOrderNumber()
    {
        // Tạo mã đơn hàng như đã hướng dẫn trước đó

    }

    //--------------- method của Nguyên : end ------------------ //

}

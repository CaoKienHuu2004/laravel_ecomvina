<?php

use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\DanhmucController;
use App\Http\Controllers\ThuonghieuController;
use App\Http\Controllers\BientheController;
use App\Http\Controllers\ChuongtrinhController;
use App\Http\Controllers\DiaChiGiaoHangController;
use App\Http\Controllers\DonhangController;
use App\Http\Controllers\NguoidungController;
use App\Http\Controllers\Web\DanhMucWebApi;
use App\Http\Controllers\Web\DonHangWebApi;
use App\Http\Controllers\Web\GioHangWebApi;
use App\Http\Controllers\Web\SanphamAllWebAPI;
use App\Http\Controllers\Web\TheoDoiDonHangWebApi;
use App\Http\Controllers\Web\TrangChuWebAPI;
use App\Http\Controllers\Web\YeuThichWebApi;

use App\Http\Controllers\HinhAnhSanphamController;
use App\Http\Controllers\LoaiBientheController;
use App\Http\Controllers\MagiamgiaController;
use App\Http\Controllers\PhuongThucController;
use App\Http\Controllers\QuangCaoController;

use App\Http\Controllers\ThongBaoController;
use App\Http\Controllers\Web\TrangDieuKhoanWebAPI;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\BaivietWebApi;
use App\Http\Controllers\Web\DiaChiWebApi;
use App\Http\Controllers\Web\GuiThongBaoWebApi;
use App\Http\Controllers\Web\MaGiamGiaWebApi;
use App\Http\Controllers\Web\QuatangAllWebAPI;
use App\Http\Controllers\Web\TimKiemWebApi;
use App\Http\Controllers\Web\TinhThanhVietNamWebApi;
use App\Http\Controllers\Web\TukhoaWebApi;

use App\Http\Controllers\QuanlyBaivietController;
use App\Http\Controllers\QuatangSukienController;
use App\Http\Controllers\Admin\TestUploadController;
use App\Http\Controllers\TrangNoiDungController;

// use App\Http\Controllers\SanphamController;
// use App\Http\Controllers\DanhmucController;
// use App\Http\Controllers\ThuonghieuController;
// use App\Http\Controllers\BientheController;
// use App\Http\Controllers\NguoidungController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// ----------------- TRANG LOGIN -----------------
Route::get('/dang-nhap', [AdminController::class, 'showLoginForm'])->name('dang-nhap');
Route::post('/dang-nhap', [AdminController::class, 'login'])->name('xu-ly-dang-nhap');

Route::prefix('auth')->group(function () {
        Route::post('dang-nhap', [AuthWebController::class, 'login']);
        Route::post('dang-ky', [AuthWebController::class, 'register']);
        Route::middleware('auth.api')->group(function () {
            Route::get('thong-tin-nguoi-dung', [AuthWebController::class, 'profile']);
            Route::post('cap-nhat-thong-tin', [AuthWebController::class, 'updateProfile']);
            Route::patch('cap-nhat-mat-khau', [AuthWebController::class, 'updatePassword']);
            Route::post('dang-xuat', [AuthWebController::class, 'logout']);
        });
    });
// ----------------- test '/' -----------------
// Route::get('/test-upload', [TestUploadController::class, 'index']);
// Route::post('/test-upload', [TestUploadController::class, 'upload'])->name('test.upload');
// ----------------- test '/' -----------------

// ----------------- REDIRECT '/' -----------------
Route::get('/', function () {
    return redirect()->route('dang-nhap');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/thong-tin-tai-khoan', [AdminController::class, 'profile'])->name('thong-tin-tai-khoan');
    Route::post('/cap-nhat-thong-tin-tai-khoan', [AdminController::class, 'updateProfile'])->name('cap-nhat-thong-tin-tai-khoan');
    Route::post('/cap-nhat-anh-dai-dien-tai-khoan', [AdminController::class, 'updateAvatar'])->name('cap-nhat-anh-dai-dien-tai-khoan');
    Route::post('/dang-xuat', [AdminController::class, 'logout'])->name('dang-xuat');

    // origin
    // Route::get('/trang-chu', function () {
    //     return view('trangchu');
    // })->name('trang-chu');
    // origin

    // Nguyên làm phần thông kê
    Route::get('/trang-chu', [AdminController::class, 'trangchu'])->name('trang-chu');
    Route::get('/thong-ke-doanh-thu', [AdminController::class, 'getThongKeDoanhThu'])->name('getThongKeDoanhThu');
    // Nguyên làm phần thông kê


    /* ===================== SẢN PHẨM ===================== */
    // Route::prefix('san-pham')->group(function () {
    //     Route::get('/danh-sach', [SanphamController::class, 'index'])->name('danh-sach');
    //     Route::get('/', [SanphamController::class, 'index']);

    //     Route::get('/tao-san-pham', [SanphamController::class, 'create'])->name('tao-san-pham');
    //     Route::post('/luu', [SanphamController::class, 'store'])->name('luu-san-pham');

    //     Route::get('/{slug}-{id}', [SanphamController::class, 'show'])
    //         ->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])
    //         ->name('chi-tiet-san-pham');

    //     Route::get('/{id}/chinh-sua', [SanphamController::class, 'edit'])->name('chinh-sua-san-pham');
    //     Route::post('/{id}/cap-nhat', [SanphamController::class, 'update'])->name('cap-nhat-san-pham'); // giữ POST theo dự án
    //     Route::get('/{id}/xoa', [SanphamController::class, 'destroy'])->name('xoa-san-pham');           // giữ GET theo dự án
    // });



    /* ===================== THƯƠNG HIỆU ===================== */
    // Route::prefix('thuong-hieu')->group(function () {
    //     Route::get('/danh-sach', [ThuonghieuController::class, 'index'])->name('danh-sach-thuong-hieu');
    //     Route::get('/', [ThuonghieuController::class, 'index']);

    //     Route::get('/tao-thuong-hieu', [ThuonghieuController::class, 'create'])->name('tao-thuong-hieu');
    //     Route::post('/luu', [ThuonghieuController::class, 'store'])->name('luu-thuong-hieu');

    //     Route::get('/{id}/chinh-sua', [ThuonghieuController::class, 'edit'])->name('chinh-sua-thuong-hieu');
    //     Route::post('/{id}/cap-nhat', [ThuonghieuController::class, 'update'])->name('cap-nhat-thuong-hieu');

    //     Route::delete('/{id}/xoa', [ThuonghieuController::class, 'destroy'])->name('xoa-thuong-hieu');
    // });

    /* ===================== KHO HÀNG (BIẾN THỂ) ===================== */
    Route::prefix('kho-hang')->group(function () {
        Route::get('/', [BientheController::class, 'index'])->name('danh-sach-kho-hang');
        Route::get('/danh-sach', [BientheController::class, 'index']);

        Route::get('/{id}/chinh-sua', [BientheController::class, 'edit'])->name('chinh-sua-hang-ton-kho');
        Route::post('/{id}/cap-nhat', [BientheController::class, 'update'])->name('cap-nhat-hang-ton-kho');
        Route::get('/{id}/xoa', [BientheController::class, 'destroy'])->name('xoa-hang-ton-kho');
    });

    /* ===================== KHÁCH HÀNG ===================== */
    // Route::prefix('khach-hang')->group(function () {
    //     Route::get('/', [NguoidungController::class, 'index'])->name('danh-sach-khach-hang');
    //     Route::get('/danh-sach', [NguoidungController::class, 'index']);

    //     // Tạo mới
    //     Route::get('/tao-khach-hang', [NguoidungController::class, 'create'])->name('tao-khach-hang');
    //     Route::post('/luu', [NguoidungController::class, 'store'])->name('luu-khach-hang');

    //     // Chỉnh sửa
    //     Route::get('/{id}/chinh-sua', [NguoidungController::class, 'edit'])->name('chinh-sua-khach-hang');
    //     Route::put('/{id}/cap-nhat', [NguoidungController::class, 'update'])->name('cap-nhat-khach-hang');

    //     // Xem chi tiết (View)
    //     Route::get('/{id}', [NguoidungController::class, 'show'])->name('chi-tiet-khach-hang');

    //     // Xóa
    //     Route::delete('/{id}/xoa', [NguoidungController::class, 'destroy'])->name('xoa-khach-hang');


    // });


    /* ===================== ĐƠN HÀNG ===================== */
    Route::prefix('don-hang')->group(function () {
        // Danh sách
        Route::get('/danh-sach', [DonhangController::class, 'index'])->name('danh-sach-don-hang');
        Route::get('/', [DonhangController::class, 'index']);

        // Tạo mới
        Route::get('/tao-don-hang', [DonhangController::class, 'create'])->name('tao-don-hang');
        Route::post('/luu', [DonhangController::class, 'store'])->name('luu-don-hang');

        // Chỉnh sửa
        Route::get('/{id}/chinh-sua', [DonhangController::class, 'edit'])->name('chinh-sua-don-hang');
        Route::put('/{id}/cap-nhat', [DonhangController::class, 'update'])->name('cap-nhat-don-hang');

        // Xem chi tiết (View)
        Route::get('/{id}', [DonhangController::class, 'show'])->name('chi-tiet-don-hang');

        // Xóa
        Route::delete('/{id}/xoa', [DonhangController::class, 'destroy'])->name('xoa-don-hang');

        /* ----------- API phụ để làm chức năng nâng cao ----------- */

        // Lấy chi tiết đơn hàng kèm tổng giá (JSON)
        Route::get('/api/{id}', [DonhangController::class, 'showApi']);

        // Cập nhật số lượng sản phẩm trong đơn hàng
        Route::post('/api/{orderId}/items/{itemId}/quantity', [DonhangController::class, 'updateItemQuantity']);

        // Tìm kiếm sản phẩm autocomplete
        Route::get('/api/search-products', [DonhangController::class, 'searchProducts']);
    });


    /* ===================== Hình Ảnh Sản Phẩm ===================== */
    Route::prefix('hinhanhsanpham')->group(function () {
        Route::get('/', [HinhAnhSanphamController::class, 'index'])->name('hinhanhsanpham.index');
        Route::get('/create', [HinhAnhSanphamController::class, 'create'])->name('hinhanhsanpham.create');
        Route::post('/store', [HinhAnhSanphamController::class, 'store'])->name('hinhanhsanpham.store');
        Route::get('/show/{id}', [HinhAnhSanphamController::class, 'show'])->name('hinhanhsanpham.show');
        Route::get('/edit/{id}', [HinhAnhSanphamController::class, 'edit'])->name('hinhanhsanpham.edit');
        Route::put('/update/{id}', [HinhAnhSanphamController::class, 'update'])->name('hinhanhsanpham.update');
        Route::delete('/delete/{id}', [HinhAnhSanphamController::class, 'destroy'])->name('hinhanhsanpham.destroy');

        // 🗑️ Thùng rác
        Route::get('/trash', [HinhAnhSanphamController::class, 'trash'])->name('hinhanhsanpham.trash');
        Route::post('/restore/{id}', [HinhAnhSanphamController::class, 'restore'])->name('hinhanhsanpham.restore');
        Route::delete('/force-delete/{id}', [HinhAnhSanphamController::class, 'forceDelete'])->name('hinhanhsanpham.forceDelete');
    });
    /* ===================== DANH MỤC ===================== */
    Route::prefix('danhmuc')->group(function () {
        Route::get('/', [DanhmucController::class, 'index'])->name('danhmuc.index');
        Route::get('/create', [DanhmucController::class, 'create'])->name('danhmuc.create');
        Route::post('/store', [DanhmucController::class, 'store'])->name('danhmuc.store');
        Route::get('/show/{id}', [DanhmucController::class, 'show'])->name('danhmuc.show');
        Route::get('/edit/{id}', [DanhmucController::class, 'edit'])->name('danhmuc.edit');
        Route::put('/update/{id}', [DanhmucController::class, 'update'])->name('danhmuc.update');
        Route::delete('/delete/{id}', [DanhmucController::class, 'destroy'])->name('danhmuc.destroy');
    });
    /* ===================== SẢN PHẨM ===================== */
    Route::prefix('sanpham')->group(function () {
        Route::get('/', [SanphamController::class, 'index'])->name('sanpham.index');
        Route::get('/create', [SanphamController::class, 'create'])->name('sanpham.create');
        Route::post('/store', [SanphamController::class, 'store'])->name('sanpham.store');
        Route::get('/show/{id}', [SanphamController::class, 'show'])->name('sanpham.show');
        Route::get('/edit/{id}', [SanphamController::class, 'edit'])->name('sanpham.edit');
        Route::put('/update/{id}', [SanphamController::class, 'update'])->name('sanpham.update');
        Route::delete('/delete/{id}', [SanphamController::class, 'destroy'])->name('sanpham.destroy');

        // 🗑️ Thùng rác
        Route::get('/trash', [SanphamController::class, 'trash'])->name('sanpham.trash');
        Route::post('/restore/{id}', [SanphamController::class, 'restore'])->name('sanpham.restore');
        Route::delete('/force-delete/{id}', [SanphamController::class, 'forceDelete'])->name('sanpham.forceDelete');
    });
        /* ===================== LOẠI BIẾN THỂ ===================== */
        Route::prefix('loaibienthe')->group(function () {
            Route::get('/', [LoaiBientheController::class, 'index'])->name('loaibienthe.index');
            Route::get('/create', [LoaiBientheController::class, 'create'])->name('loaibienthe.create');
            Route::post('/store', [LoaiBientheController::class, 'store'])->name('loaibienthe.store');
            Route::get('/show/{id}', [LoaiBientheController::class, 'show'])->name('loaibienthe.show');
            Route::get('/edit/{id}', [LoaiBientheController::class, 'edit'])->name('loaibienthe.edit');
            Route::put('/update/{id}', [LoaiBientheController::class, 'update'])->name('loaibienthe.update');
            Route::delete('/delete/{id}', [LoaiBientheController::class, 'destroy'])->name('loaibienthe.destroy');
        });
    /* ===================== QUẢNG CÁO ===================== */
    Route::prefix('quangcao')->group(function () {
        Route::get('/', [QuangCaoController::class, 'index'])->name('quangcao.index');
        Route::get('/create', [QuangCaoController::class, 'create'])->name('quangcao.create');
        Route::post('/store', [QuangCaoController::class, 'store'])->name('quangcao.store');
        Route::get('/show/{id}', [QuangCaoController::class, 'show'])->name('quangcao.show');
        Route::get('/edit/{id}', [QuangCaoController::class, 'edit'])->name('quangcao.edit');
        Route::put('/update/{id}', [QuangCaoController::class, 'update'])->name('quangcao.update');
        Route::delete('/delete/{id}', [QuangCaoController::class, 'destroy'])->name('quangcao.destroy');


    });
    /* ===================== THƯƠNG HIỆU ===================== */
    Route::prefix('thuonghieu')->group(function () {
        Route::get('/', [ThuonghieuController::class, 'index'])->name('thuonghieu.index');
        Route::get('/create', [ThuonghieuController::class, 'create'])->name('thuonghieu.create');
        Route::post('/store', [ThuonghieuController::class, 'store'])->name('thuonghieu.store');
        Route::get('/show/{id}', [ThuonghieuController::class, 'show'])->name('thuonghieu.show');
        Route::get('/edit/{id}', [ThuonghieuController::class, 'edit'])->name('thuonghieu.edit');
        Route::put('/update/{id}', [ThuonghieuController::class, 'update'])->name('thuonghieu.update');
        Route::delete('/delete/{id}', [ThuonghieuController::class, 'destroy'])->name('thuonghieu.destroy');
    });
    /* ===================== NGƯỜI DÙNG ===================== */
    Route::prefix('nguoidung')->group(function () {
        Route::get('/', [NguoidungController::class, 'index'])->name('nguoidung.index');
        Route::get('/create', [NguoidungController::class, 'create'])->name('nguoidung.create');
        Route::post('/store', [NguoidungController::class, 'store'])->name('nguoidung.store');
        Route::get('/show/{id}', [NguoidungController::class, 'show'])->name('nguoidung.show');
        Route::get('/edit/{id}', [NguoidungController::class, 'edit'])->name('nguoidung.edit');
        Route::put('/update/{id}', [NguoidungController::class, 'update'])->name('nguoidung.update');
        Route::delete('/delete/{id}', [NguoidungController::class, 'destroy'])->name('nguoidung.destroy');

        // 🗑️ Thùng rác
        Route::get('/trash', [NguoidungController::class, 'trash'])->name('nguoidung.trash');
        Route::post('/restore/{id}', [NguoidungController::class, 'restore'])->name('nguoidung.restore');
        Route::delete('/force-delete/{id}', [NguoidungController::class, 'forceDelete'])->name('nguoidung.forceDelete');
    });
    /* ===================== ĐỊA CHỈ GIAO HÀNG ===================== */
    Route::prefix('diachigiaohang')->group(function () {
        Route::get('/', [DiaChiGiaoHangController::class, 'index'])->name('diachigiaohang.index');
        Route::get('/create', [DiaChiGiaoHangController::class, 'create'])->name('diachigiaohang.create');
        Route::post('/store', [DiaChiGiaoHangController::class, 'store'])->name('diachigiaohang.store');
        Route::get('/show/{id}', [DiaChiGiaoHangController::class, 'show'])->name('diachigiaohang.show');
        Route::get('/edit/{id}', [DiaChiGiaoHangController::class, 'edit'])->name('diachigiaohang.edit');
        Route::put('/update/{id}', [DiaChiGiaoHangController::class, 'update'])->name('diachigiaohang.update');
        Route::delete('/delete/{id}', [DiaChiGiaoHangController::class, 'destroy'])->name('diachigiaohang.destroy');

        // 🗑️ Thùng rác
        Route::get('/trash', [DiaChiGiaoHangController::class, 'trash'])->name('diachigiaohang.trash');
        Route::post('/restore/{id}', [DiaChiGiaoHangController::class, 'restore'])->name('diachigiaohang.restore');
        Route::delete('/force-delete/{id}', [DiaChiGiaoHangController::class, 'forceDelete'])->name('diachigiaohang.forceDelete');
    });
    /* ===================== PHƯƠNG THỨC THANH TOÁN ===================== */
    Route::prefix('phuongthuc')->group(function () {
        Route::get('/', [PhuongThucController::class, 'index'])->name('phuongthuc.index');
        Route::get('/create', [PhuongThucController::class, 'create'])->name('phuongthuc.create');
        Route::post('/store', [PhuongThucController::class, 'store'])->name('phuongthuc.store');
        Route::get('/show/{id}', [PhuongThucController::class, 'show'])->name('phuongthuc.show');
        Route::get('/edit/{id}', [PhuongThucController::class, 'edit'])->name('phuongthuc.edit');
        Route::put('/update/{id}', [PhuongThucController::class, 'update'])->name('phuongthuc.update');
        Route::delete('/delete/{id}', [PhuongThucController::class, 'destroy'])->name('phuongthuc.destroy');
    });
    /* ===================== CHƯƠNG TRÌNH SỰ KIỆN ===================== */
    Route::prefix('chuongtrinh')->group(function () {
        Route::get('/', [ChuongtrinhController::class, 'index'])->name('chuongtrinh.index');
        Route::get('/create', [ChuongtrinhController::class, 'create'])->name('chuongtrinh.create');
        Route::post('/store', [ChuongtrinhController::class, 'store'])->name('chuongtrinh.store');
        Route::get('/show/{id}', [ChuongtrinhController::class, 'show'])->name('chuongtrinh.show');
        Route::get('/edit/{id}', [ChuongtrinhController::class, 'edit'])->name('chuongtrinh.edit');
        Route::put('/update/{id}', [ChuongtrinhController::class, 'update'])->name('chuongtrinh.update');
        Route::delete('/delete/{id}', [ChuongtrinhController::class, 'destroy'])->name('chuongtrinh.destroy');
    });
    /* ===================== QUÀ TẶNG SỰ KIỆN ===================== */
    Route::prefix('quatangsukien')->group(function () {
        Route::get('/', [QuatangSukienController::class, 'index'])->name('quatangsukien.index');
        Route::get('/create', [QuatangSukienController::class, 'create'])->name('quatangsukien.create');
        Route::post('/store', [QuatangSukienController::class, 'store'])->name('quatangsukien.store');
        Route::get('/show/{id}', [QuatangSukienController::class, 'show'])->name('quatangsukien.show');
        Route::get('/edit/{id}', [QuatangSukienController::class, 'edit'])->name('quatangsukien.edit');
        Route::put('/update/{id}', [QuatangSukienController::class, 'update'])->name('quatangsukien.update');
        Route::delete('/delete/{id}', [QuatangSukienController::class, 'destroy'])->name('quatangsukien.destroy');

        // 🗑️ Thùng rác
        Route::get('/trash', [QuatangSukienController::class, 'trash'])->name('quatangsukien.trash');
        Route::post('/restore/{id}', [QuatangSukienController::class, 'restore'])->name('quatangsukien.restore');
        Route::delete('/force-delete/{id}', [QuatangSukienController::class, 'forceDelete'])->name('quatangsukien.forceDelete');
    });
    /* ===================== THÔNG BÁO ===================== */
    Route::prefix('thongbao')->group(function () {
        Route::get('/', [ThongBaoController::class, 'index'])->name('thongbao.index');
        Route::get('/create', [ThongBaoController::class, 'create'])->name('thongbao.create');
        Route::post('/store', [ThongBaoController::class, 'store'])->name('thongbao.store');
        Route::get('/show/{id}', [ThongBaoController::class, 'show'])->name('thongbao.show');
        Route::get('/edit/{id}', [ThongBaoController::class, 'edit'])->name('thongbao.edit');
        Route::put('/update/{id}', [ThongBaoController::class, 'update'])->name('thongbao.update');
        Route::delete('/delete/{id}', [ThongBaoController::class, 'destroy'])->name('thongbao.destroy');
        //câp nhât trạng thái đã đọc
        Route::patch('/update-status/{id}', [ThongBaoController::class, 'updateStatus'])->name('thongbao.update-status');
    });

    /* ===================== QUẢN LÝ BÀI VIẾT ===================== */
    Route::prefix('baiviet')->group(function () {
        Route::get('/', [QuanlyBaivietController::class, 'index'])->name('baiviet.index');
        Route::get('/create', [QuanlyBaivietController::class, 'create'])->name('baiviet.create');
        Route::post('/store', [QuanlyBaivietController::class, 'store'])->name('baiviet.store');
        Route::get('/show/{id}', [QuanlyBaivietController::class, 'show'])->name('baiviet.show');
        Route::get('/edit/{id}', [QuanlyBaivietController::class, 'edit'])->name('baiviet.edit');
        Route::put('/update/{id}', [QuanlyBaivietController::class, 'update'])->name('baiviet.update');
        Route::delete('/delete/{id}', [QuanlyBaivietController::class, 'destroy'])->name('baiviet.destroy');
    });
    /* ===================== QUẢN LÝ MÃ GIẢM GIÁ ===================== */
    Route::prefix('magiamgia')->group(function () {
        Route::get('/', [MagiamgiaController::class, 'index'])->name('danhsach.magiamgia');
        Route::get('/create', [MagiamgiaController::class,'create'])->name('create.magiamgia');
        Route::post('/store', [MagiamgiaController::class,'store'])->name('store.magiamgia');
        Route::get('/show/{id}', [MagiamgiaController::class, 'show'])->name('magiamgia.show');
        Route::get('/edit/{id}', [MagiamgiaController::class,'edit'])->name('edit.magiamgia');
        Route::put('/update/{id}', [MagiamgiaController::class,'update'])->name('magiamgia.update');
        Route::delete('/delete/{id}', [MagiamgiaController::class,'destroy'])->name('delete.magiamgia');

        // 🗑️ Thùng rác
        Route::get('/trash', [MagiamgiaController::class, 'trash'])->name('magiamgia.trash');
        Route::post('/restore/{id}', [MagiamgiaController::class, 'restore'])->name('magiamgia.restore');
        Route::delete('/force-delete/{id}', [MagiamgiaController::class, 'forceDelete'])->name('magiamgia.forceDelete');
    });
    /* ===================== TRANG NỘI DUNG ===================== */
    Route::prefix('trangnoidung')->group(function () {
        Route::get('/', [TrangNoiDungController::class, 'index'])->name('trangnoidung.index');
        Route::get('/create', [TrangNoiDungController::class, 'create'])->name('trangnoidung.create');
        Route::post('/store', [TrangNoiDungController::class, 'store'])->name('trangnoidung.store');
        Route::get('/show/{id}', [TrangNoiDungController::class, 'show'])->name('trangnoidung.show');
        Route::get('/edit/{id}', [TrangNoiDungController::class, 'edit'])->name('trangnoidung.edit');
        Route::put('/update/{id}', [TrangNoiDungController::class, 'update'])->name('trangnoidung.update');
        Route::delete('/delete/{id}', [TrangNoiDungController::class, 'destroy'])->name('trangnoidung.destroy');

    });
});
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



// Route::get('/', function () {
//     // return Inertia::render('Welcome', [
//     //     'canLogin' => Route::has('login'),
//     //     'canRegister' => Route::has('register'),
//     //     'laravelVersion' => Application::VERSION,
//     //     'phpVersion' => PHP_VERSION,
//     // ]);
//     return redirect('login');
// });
// Route::get('/home', function () {
//     return redirect('login');
// });

// Route::get('/test-guest', function () {
//     return view('guest/test-guest');
// });
// //-------------------------------------------------- Guest User --------------------------------//
// // Nếu guest (chưa đăng nhập) → vẫn có giỏ hàng, nhưng dựa trên session_id (Laravel session) hoặc cookie.
// // Route::get('/giohang/guest', [GioHangFrontendAPI::class, 'guestCart']);

Route::get('/web/giohang/init', [GioHangWebApi::class, 'init']); // giống session init của php ở đâu file index.php
Route::get('/web/giohang', [GioHangWebApi::class, 'index']);
Route::post('/web/giohang', [GioHangWebApi::class, 'store']);
Route::put('/web/giohang/{id}', [GioHangWebApi::class, 'update']);
Route::delete('/web/giohang/{id}', [GioHangWebApi::class, 'destroy']);

Route::get('/api-san-pham', [SanphamAllWebAPI::class, 'index']);
Route::get('/api-san-pham/{id}', [SanphamAllWebAPI::class, 'show']);

Route::get('/api-qua-tang', [QuatangAllWebAPI::class, 'index']);
Route::get('/api-qua-tang/{id}', [QuatangAllWebAPI::class, 'show']);

Route::get('/api-bai-viet', [BaivietWebApi::class, 'index']);
Route::get('/api-bai-viet/{id}', [BaivietWebApi::class, 'show']);

Route::get('/api-trang-chu', [TrangChuWebAPI::class, 'index']);
    Route::apiResource('api-tim-kiem', TimKiemWebApi::class)->only(['index']);
    Route::apiResource('api-tu-khoa', TukhoaWebApi::class)->only(['index','store','update']);
    Route::get('/api-danh-muc', [DanhMucWebApi::class, 'index']);
    //pulic api
    Route::post('/gui-lien-he', [GuiThongBaoWebApi::class, 'guiLienHe']);
    //pulic api
//page tỉnh
Route::apiResource('api-trang-dieu-khoan', TrangDieuKhoanWebAPI::class)->only(['index']);
//page tỉnh

// gọi kèm các routes WebApi khác
Route::get('/api-tinh-thanh', [TinhThanhVietNamWebApi::class, 'index']);
Route::apiResource('api-ma-giam-gia', MaGiamGiaWebApi::class)->only(['index']);
// gọi kèm các routes WebApi khác

//-------------------------------------------------- Guest User authetication --------------------------------//

Route::middleware(['auth.api'])->group(function () {
    Route::get('/tai-khoan/donhang', [DonHangWebApi::class, 'index']);
    Route::post('/tai-khoan/donhang', [DonHangWebApi::class, 'store']);
    Route::get('/tai-khoan/donhang/{id}', [DonHangWebApi::class, 'show']);
    Route::put('/tai-khoan/donhang/{id}', [DonHangWebApi::class, 'update']);
    Route::patch('/tai-khoan/donhang/{id}/huy', [DonHangWebApi::class, 'cancel']);
    // // Tích hợp vietqr
    //     Route::post('/tai-khoan/donhang/{id}/vietqr-url', [DonHangWebApi::class, 'createVietqrtUrl']);

    // Thanh Toán Lại Đơn Hàng và Mua Lại (Trạng Thái Thành Công)
    Route::patch('/tai-khoan/donhang/{id}/thanh-toan-lai-don-hang', [DonHangWebApi::class, 'thanhToanLaiDonHang']);
    Route::patch('/tai-khoan/donhang/{id}/mua-lai-don-hang', [DonHangWebApi::class, 'muaLaiDonHang']);

    // Tích hợp thanh toán VNPAY, cần thêm 3 route
    Route::post('/tai-khoan/donhang/{id}/payment-url', [DonHangWebApi::class, 'createPaymentUrl']);
    Route::get('/tai-khoan/donhang/{id}/status', [DonHangWebApi::class, 'getPaymentStatus']);
});
    // Tích hợp thanh toán VNPAY, cần thêm 3 route
    Route::get('/tai-khoan/donhang/payment-callback', [DonHangWebApi::class, 'handlePaymentCallback'])
    ->name('tai-khoan.donhang.payment-callback');;
    // ko cần auth vì là hook từ VNPAY gửi về, nếu auth có thể dẫn đến lỗi 401 Unauthorized

    Route::middleware(['auth.order_code'])->group(function () {
        Route::get('/web/tracuu-donhang', [TheoDoiDonHangWebApi::class, 'index']);
        // Route::put('/web/tracuu-donhang/{id}', [TheoDoiDonHangWebApi::class, 'update']);
    });
Route::middleware(['auth.api'])->group(function () {
    Route::get('/tai-khoan/yeuthich', [YeuThichWebApi::class, 'index']); // Xem danh sách yêu thích
    Route::post('/tai-khoan/yeuthich', [YeuThichWebApi::class, 'store']); // Thêm sản phẩm vào yêu thích
    Route::patch('/tai-khoan/yeuthich/{id_sanpham}', [YeuThichWebApi::class, 'update']); // Bỏ yêu thích (chuyển trạng thái)
});

Route::middleware(['auth.api'])->prefix('tai-khoan')->group(function () {
    Route::get('/diachi', [DiaChiWebApi::class, 'index']);
    Route::post('/diachi', [DiaChiWebApi::class, 'store']);
    Route::put('/diachi/{id}', [DiaChiWebApi::class, 'update']);
    Route::delete('/diachi/{id}', [DiaChiWebApi::class, 'destroy']);
    Route::patch('/diachi/{id}/macdinh', [DiaChiWebApi::class, 'setDefault']);
    Route::patch('/diachi/{id}/trangthai', [DiaChiWebApi::class, 'toggleStatus']);
});

//-------------------------------------------------- Admin --------------------------------//
// Route::get('admin/category/trash', [CategoryController::class, 'trash'])->middleware('auth','role:admin');
// Route::post('admin/category/delete', [CategoryController::class, 'delete'])->middleware('auth','role:admin');
// Route::post('admin/category/restore', [CategoryController::class, 'restore'])->middleware('auth','role:admin');
// Route::get('admin/category/trash', [CategoryController::class, 'trash']);
// Route::middleware(['auth','role:admin'])->group(function () {
//     Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//     Route::resource('/admin/category', CategoryController::class)->names('category');
//     Route::resource('/admin/user', UserController::class)->names('user');
//     Route::post('/admin/category/destroy', [CategoryController::class, 'destroy']);
// });

//-------------------------------------------------- Admin --------------------------------//

////////jetstream
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
//     // 'role:admin',
//     'role:assistant',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
////////////////////// jetstream




// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
//     // 'role:assistant',
//     'role:admin',
// ])->group(function () {
//     Route::get('/trang-chu', function () {
//         return view('trangchu');
//     })->name('trang-chu');
//     /* ===================== SẢN PHẨM ===================== */
//     Route::prefix('san-pham')->group(function () {
//         Route::get('/danh-sach', [SanphamController::class, 'index'])->name('danh-sach');
//         Route::get('/', [SanphamController::class, 'index']);

//         Route::get('/tao-san-pham', [SanphamController::class, 'create'])->name('tao-san-pham');
//         Route::post('/luu', [SanphamController::class, 'store'])->name('luu-san-pham');

//         Route::get('/{slug}-{id}', [SanphamController::class, 'show'])
//             ->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])
//             ->name('chi-tiet-san-pham');

//         Route::get('/{id}/chinh-sua', [SanphamController::class, 'edit'])->name('chinh-sua-san-pham');
//         Route::post('/{id}/cap-nhat', [SanphamController::class, 'update'])->name('cap-nhat-san-pham'); // giữ POST theo dự án
//         Route::get('/{id}/xoa', [SanphamController::class, 'destroy'])->name('xoa-san-pham');           // giữ GET theo dự án
//     });

//     /* ===================== DANH MỤC ===================== */
//     Route::prefix('danh-muc')->group(function () {
//         Route::get('/danh-sach', [DanhmucController::class, 'index'])->name('danh-sach-danh-muc');
//         Route::get('/', [DanhmucController::class, 'index']);

//         Route::get('/tao-danh-muc', [DanhmucController::class, 'create'])->name('tao-danh-muc');
//         Route::post('/luu', [DanhmucController::class, 'store'])->name('luu-danh-muc');

//         Route::get('/{id}/chinh-sua', [DanhmucController::class, 'edit'])->name('chinh-sua-danh-muc');
//         Route::post('/{id}/cap-nhat', [DanhmucController::class, 'update'])->name('cap-nhat-danh-muc');

//         Route::delete('/{id}/xoa', [DanhmucController::class, 'destroy'])->name('xoa-danh-muc');
//     });

//     // /* ===================== THƯƠNG HIỆU ===================== */
//     // Route::prefix('thuong-hieu')->group(function () {
//     //     Route::get('/danh-sach', [ThuonghieuController::class, 'index'])->name('danh-sach-thuong-hieu');
//     //     Route::get('/', [ThuonghieuController::class, 'index']);

//     //     Route::get('/tao-thuong-hieu', [ThuonghieuController::class, 'create'])->name('tao-thuong-hieu');
//     //     Route::post('/luu', [ThuonghieuController::class, 'store'])->name('luu-thuong-hieu');

//     //     Route::get('/{id}/chinh-sua', [ThuonghieuController::class, 'edit'])->name('chinh-sua-thuong-hieu');
//     //     Route::post('/{id}/cap-nhat', [ThuonghieuController::class, 'update'])->name('cap-nhat-thuong-hieu');

//     //     Route::delete('/{id}/xoa', [ThuonghieuController::class, 'destroy'])->name('xoa-thuong-hieu');
//     // });

//     /* ===================== KHO HÀNG (BIẾN THỂ) ===================== */
//     Route::prefix('kho-hang')->group(function () {
//         Route::get('/', [BientheController::class, 'index'])->name('danh-sach-kho-hang');
//         Route::get('/danh-sach', [BientheController::class, 'index']);

//         Route::get('/{id}/chinh-sua', [BientheController::class, 'edit'])->name('chinh-sua-hang-ton-kho');
//         Route::post('/{id}/cap-nhat', [BientheController::class, 'update'])->name('cap-nhat-hang-ton-kho');
//         Route::get('/{id}/xoa', [BientheController::class, 'destroy'])->name('xoa-hang-ton-kho');
//     });

//     /* ===================== KHÁCH HÀNG ===================== */
//     Route::prefix('khach-hang')->group(function () {
//         Route::get('/', [NguoidungController::class, 'index'])->name('danh-sach-khach-hang');
//         Route::get('/danh-sach', [NguoidungController::class, 'index']);

//         // Tạo mới
//         Route::get('/tao-khach-hang', [NguoidungController::class, 'create'])->name('tao-khach-hang');
//         Route::post('/luu', [NguoidungController::class, 'store'])->name('luu-khach-hang');

//         // Chỉnh sửa
//         Route::get('/{id}/chinh-sua', [NguoidungController::class, 'edit'])->name('chinh-sua-khach-hang');
//         Route::put('/{id}/cap-nhat', [NguoidungController::class, 'update'])->name('cap-nhat-khach-hang');

//         // Xem chi tiết (View)
//         Route::get('/{id}', [NguoidungController::class, 'show'])->name('chi-tiet-khach-hang');

//         // Xóa
//         Route::delete('/{id}/xoa', [NguoidungController::class, 'destroy'])->name('xoa-khach-hang');


//     });


//     /* ===================== ĐƠN HÀNG ===================== */
//     Route::prefix('don-hang')->group(function () {
//         // Danh sách
//         Route::get('/danh-sach', [DonhangController::class, 'index'])->name('danh-sach-don-hang');
//         Route::get('/', [DonhangController::class, 'index']);

//         // Tạo mới
//         Route::get('/tao-don-hang', [DonhangController::class, 'create'])->name('tao-don-hang');
//         Route::post('/luu', [DonhangController::class, 'store'])->name('luu-don-hang');

//         // Chỉnh sửa
//         Route::get('/{id}/chinh-sua', [DonhangController::class, 'edit'])->name('chinh-sua-don-hang');
//         Route::put('/{id}/cap-nhat', [DonhangController::class, 'update'])->name('cap-nhat-don-hang');

//         // Xem chi tiết (View)
//         Route::get('/{id}', [DonhangController::class, 'show'])->name('chi-tiet-don-hang');

//         // Xóa
//         Route::delete('/{id}/xoa', [DonhangController::class, 'destroy'])->name('xoa-don-hang');

//         /* ----------- API phụ để làm chức năng nâng cao ----------- */

//         // Lấy chi tiết đơn hàng kèm tổng giá (JSON)
//         Route::get('/api/{id}', [DonhangController::class, 'showApi']);

//         // Cập nhật số lượng sản phẩm trong đơn hàng
//         Route::post('/api/{orderId}/items/{itemId}/quantity', [DonhangController::class, 'updateItemQuantity']);

//         // Tìm kiếm sản phẩm autocomplete
//         Route::get('/api/search-products', [DonhangController::class, 'searchProducts']);
//     });


// });


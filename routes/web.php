<?php

use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\DanhmucController;
use App\Http\Controllers\ThuonghieuController;
use App\Http\Controllers\BientheController;
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
use App\Http\Controllers\QuangCaoController;
use App\Http\Controllers\Web\TimKiemWebApi;
use App\Http\Controllers\Web\TinhThanhVietNamWebApi;
use App\Http\Controllers\Web\TukhoaWebApi;

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




// ----------------- REDIRECT '/' -----------------
Route::get('/', function () {
    return redirect()->route('dang-nhap');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/thong-tin-tai-khoan', [AdminController::class, 'profile'])->name('thong-tin-tai-khoan');
    Route::post('/cap-nhat-thong-tin-tai-khoan', [AdminController::class, 'updateProfile'])->name('cap-nhat-thong-tin-tai-khoan');
    Route::post('/cap-nhat-anh-dai-dien-tai-khoan', [AdminController::class, 'updateAvatar'])->name('cap-nhat-anh-dai-dien-tai-khoan');
    Route::post('/dang-xuat', [AdminController::class, 'logout'])->name('dang-xuat');

    Route::get('/trang-chu', function () {
        return view('trangchu');
    })->name('trang-chu');
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
Route::get('/toi/giohang', [GioHangWebApi::class, 'index']);
Route::post('/toi/giohang', [GioHangWebApi::class, 'store']);
Route::put('/toi/giohang/{id}', [GioHangWebApi::class, 'update']);
Route::delete('/toi/giohang/{id}', [GioHangWebApi::class, 'destroy']);
Route::get('/api-san-pham', [SanphamAllWebAPI::class, 'index']);
Route::get('/api-san-pham/{id}', [SanphamAllWebAPI::class, 'show']);

Route::get('/api-trang-chu', [TrangChuWebAPI::class, 'index']);

Route::get('/api-danh-muc', [DanhMucWebApi::class, 'index']);

Route::get('/api-tinh-thanh', [TinhThanhVietNamWebApi::class, 'index']);

Route::apiResource('api-tim-kiem', TimKiemWebApi::class)->only(['index']);

Route::apiResource('api-tu-khoa', TukhoaWebApi::class)->only(['index','store','update']);

//-------------------------------------------------- Guest User authetication --------------------------------//

Route::middleware(['auth.api'])->group(function () {
    Route::get('/toi/donhang', [DonHangWebApi::class, 'index']);
    Route::post('/toi/donhang', [DonHangWebApi::class, 'store']);
    Route::put('/toi/donhang/{id}', [DonHangWebApi::class, 'update']);
    Route::patch('/toi/donhang/{id}/huy', [DonHangWebApi::class, 'cancel']);
});

Route::middleware(['auth.username_order'])->group(function () {
    Route::get('/toi/theodoi-donhang', [TheoDoiDonHangWebApi::class, 'index']);
    Route::put('/toi/theodoi-donhang/{id}', [TheoDoiDonHangWebApi::class, 'update']);
});
Route::middleware(['auth.api'])->group(function () {
    Route::get('/toi/yeuthich', [YeuThichWebApi::class, 'index']); // Xem danh sách yêu thích
    Route::post('/toi/yeuthich', [YeuThichWebApi::class, 'store']); // Thêm sản phẩm vào yêu thích
    Route::put('/toi/yeuthich/{id_sanpham}', [YeuThichWebApi::class, 'update']); // Bỏ yêu thích (chuyển trạng thái)
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


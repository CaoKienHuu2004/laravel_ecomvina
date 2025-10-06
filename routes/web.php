<?php

use App\Http\Controllers\API\Frontend\GioHangFrontendAPI;

use App\Http\Controllers\Me\ProfileController;

use App\Http\Controllers\Web\GioHangWebApi;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

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


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return redirect('login');
});
Route::get('/home', function () {
    return redirect('login');
});

Route::get('/test-guest', function () {
    return view('guest/test-guest');
});
//-------------------------------------------------- Guest User --------------------------------//
// Nếu guest (chưa đăng nhập) → vẫn có giỏ hàng, nhưng dựa trên session_id (Laravel session) hoặc cookie.
// Route::get('/giohang/guest', [GioHangFrontendAPI::class, 'guestCart']);
Route::middleware(['auth'])->group(function () {
    Route::get('/giohang', [GioHangWebApi::class, 'index']);
    Route::post('/giohang', [GioHangWebApi::class, 'store']);
    Route::put('/giohang/{id_bienthesp}', [GioHangWebApi::class, 'update']);
    Route::delete('/giohang/{id_bienthesp}', [GioHangWebApi::class, 'destroy']);
});


//-------------------------------------------------- Guest User --------------------------------//

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
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    // 'role:admin',
    'role:assistant',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


////////////////////// jetstream


use App\Http\Controllers\SanphamController;
use App\Http\Controllers\DanhmucController;
use App\Http\Controllers\ThuonghieuController;
use App\Http\Controllers\BientheController;
use App\Http\Controllers\CuaHangController;
use App\Http\Controllers\DoiNguQuanTriController;
use App\Http\Controllers\DonhangController;
use App\Http\Controllers\NguoidungController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    // 'role:assistant',
    'role:admin',
])->group(function () {
    Route::get('/trang-chu', function () {
        return view('trangchu');
    })->name('trang-chu');
    /* ===================== SẢN PHẨM ===================== */
    Route::prefix('san-pham')->group(function () {
        Route::get('/danh-sach', [SanphamController::class, 'index'])->name('danh-sach');
        Route::get('/', [SanphamController::class, 'index']);

        Route::get('/tao-san-pham', [SanphamController::class, 'create'])->name('tao-san-pham');
        Route::post('/luu', [SanphamController::class, 'store'])->name('luu-san-pham');

        Route::get('/{slug}-{id}', [SanphamController::class, 'show'])
            ->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])
            ->name('chi-tiet-san-pham');

        Route::get('/{id}/chinh-sua', [SanphamController::class, 'edit'])->name('chinh-sua-san-pham');
        Route::post('/{id}/cap-nhat', [SanphamController::class, 'update'])->name('cap-nhat-san-pham'); // giữ POST theo dự án
        Route::get('/{id}/xoa', [SanphamController::class, 'destroy'])->name('xoa-san-pham');           // giữ GET theo dự án
    });

    /* ===================== DANH MỤC ===================== */
    Route::prefix('danh-muc')->group(function () {
        Route::get('/danh-sach', [DanhmucController::class, 'index'])->name('danh-sach-danh-muc');
        Route::get('/', [DanhmucController::class, 'index']);

        Route::get('/tao-danh-muc', [DanhmucController::class, 'create'])->name('tao-danh-muc');
        Route::post('/luu', [DanhmucController::class, 'store'])->name('luu-danh-muc');

        Route::get('/{id}/chinh-sua', [DanhmucController::class, 'edit'])->name('chinh-sua-danh-muc');
        Route::post('/{id}/cap-nhat', [DanhmucController::class, 'update'])->name('cap-nhat-danh-muc');

        Route::delete('/{id}/xoa', [DanhmucController::class, 'destroy'])->name('xoa-danh-muc');
    });

    /* ===================== THƯƠNG HIỆU ===================== */
    Route::prefix('thuong-hieu')->group(function () {
        Route::get('/danh-sach', [ThuonghieuController::class, 'index'])->name('danh-sach-thuong-hieu');
        Route::get('/', [ThuonghieuController::class, 'index']);

        Route::get('/tao-thuong-hieu', [ThuonghieuController::class, 'create'])->name('tao-thuong-hieu');
        Route::post('/luu', [ThuonghieuController::class, 'store'])->name('luu-thuong-hieu');

        Route::get('/{id}/chinh-sua', [ThuonghieuController::class, 'edit'])->name('chinh-sua-thuong-hieu');
        Route::post('/{id}/cap-nhat', [ThuonghieuController::class, 'update'])->name('cap-nhat-thuong-hieu');

        Route::delete('/{id}/xoa', [ThuonghieuController::class, 'destroy'])->name('xoa-thuong-hieu');
    });

    /* ===================== KHO HÀNG (BIẾN THỂ) ===================== */
    Route::prefix('kho-hang')->group(function () {
        Route::get('/', [BientheController::class, 'index'])->name('danh-sach-kho-hang');
        Route::get('/danh-sach', [BientheController::class, 'index']);

        Route::get('/{id}/chinh-sua', [BientheController::class, 'edit'])->name('chinh-sua-hang-ton-kho');
        Route::post('/{id}/cap-nhat', [BientheController::class, 'update'])->name('cap-nhat-hang-ton-kho');
        Route::get('/{id}/xoa', [BientheController::class, 'destroy'])->name('xoa-hang-ton-kho');
    });

    /* ===================== KHÁCH HÀNG ===================== */
    Route::prefix('khach-hang')->group(function () {
        Route::get('/', [NguoidungController::class, 'index'])->name('danh-sach-khach-hang');
        Route::get('/danh-sach', [NguoidungController::class, 'index']);

        // Tạo mới
        Route::get('/tao-khach-hang', [NguoidungController::class, 'create'])->name('tao-khach-hang');
        Route::post('/luu', [NguoidungController::class, 'store'])->name('luu-khach-hang');

        // Chỉnh sửa
        Route::get('/{id}/chinh-sua', [NguoidungController::class, 'edit'])->name('chinh-sua-khach-hang');
        Route::put('/{id}/cap-nhat', [NguoidungController::class, 'update'])->name('cap-nhat-khach-hang');

        // Xem chi tiết (View)
        Route::get('/{id}', [NguoidungController::class, 'show'])->name('chi-tiet-khach-hang');

        // Xóa
        Route::delete('/{id}/xoa', [NguoidungController::class, 'destroy'])->name('xoa-khach-hang');


    });
    /* ===================== CỬA HÀNG ===================== */
    Route::prefix('cua-hang')->group(function () {
        Route::get('/', [CuaHangController::class, 'index'])->name('danh-sach-cua-hang');
        Route::get('/danh-sach', [CuaHangController::class, 'index']);
        // danh-sach-cua-hang chi-tiet-cua-hang tao-cua-hang chinh-sua-cua-hang

        // Tạo mới
        Route::get('/tao-cua-hang', [CuaHangController::class, 'create'])->name('tao-cua-hang');
        Route::post('/luu', [CuaHangController::class, 'store'])->name('luu-cua-hang');

        // Chỉnh sửa
        Route::get('/{id}/chinh-sua', [CuaHangController::class, 'edit'])->name('chinh-sua-cua-hang');
        Route::put('/{id}/cap-nhat', [CuaHangController::class, 'update'])->name('cap-nhat-cua-hang');


        // Xem chi tiết (View)
        Route::get('/{id}', [CuaHangController::class, 'show'])->name('chi-tiet-cua-hang');

        // Xóa
        Route::delete('/{id}/xoa', [CuaHangController::class, 'destroy'])->name('xoa-cua-hang');



    });
    /* ===================== ĐỘI NGŨ QUẢN TRỊ ===================== */
    Route::prefix('doi-ngu-quan-tri')->group(function () {
        Route::get('/', [DoiNguQuanTriController::class, 'index'])->name('danh-sach-doi-ngu-quan-tri');
        Route::get('/danh-sach', [DoiNguQuanTriController::class, 'index']);

        // Tạo mới
        // không thể thêm admin

        // Chỉnh sửa
        Route::get('/{id}/chinh-sua', [DoiNguQuanTriController::class, 'edit'])->name('chinh-sua-doi-ngu-quan-tri');
        Route::put('/{id}/cap-nhat', [DoiNguQuanTriController::class, 'update'])->name('cap-nhat-doi-ngu-quan-tri');

        // Xem chi tiết (View)
        Route::get('/{id}', [DoiNguQuanTriController::class, 'show'])->name('chi-tiet-doi-ngu-quan-tri');

        // Xóa
        Route::delete('/{id}/xoa', [DoiNguQuanTriController::class, 'destroy'])->name('xoa-doi-ngu-quan-tri');

    });

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


});


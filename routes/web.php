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
    'role:admin',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
////////////////////// jetstream


// Route::prefix('san-pham')->group(function () {
//     Route::get('/danh-sach', [SanphamController::class, 'index'])->name('danh-sach');
//     Route::get('/', [SanphamController::class, 'index']);
//     Route::get('/tao-san-pham', [SanphamController::class, 'create'])->name('tao-san-pham');
//     Route::post('/luu', [SanphamController::class, 'store'])->name('luu-san-pham');
//     Route::get('/{slug}-{id}', [SanphamController::class, 'show'])
//     ->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])
//     ->name('chi-tiet-san-pham');
//     Route::get('/{id}/chinh-sua', [SanphamController::class, 'edit'])->name('chinh-sua-san-pham');
//     Route::post('/{id}/cap-nhat', [SanphamController::class, 'update'])->name('cap-nhat-san-pham');
//     Route::get('/{id}/xoa', [SanphamController::class, 'destroy'])->name('xoa-san-pham');
// });

// Route::prefix('danh-muc')->group(function () {
//     // Danh sách danh mục
//     Route::get('/danh-sach', [DanhmucController::class, 'index'])->name('danh-sach-danh-muc');
//     Route::get('/', [DanhmucController::class, 'index']);

//     // Form thêm mới
//     Route::get('/tao-danh-muc', [DanhmucController::class, 'create'])->name('tao-danh-muc');
//     Route::post('/luu', [DanhmucController::class, 'store'])->name('luu-danh-muc');

//     // Form sửa
//     Route::get('/{id}/chinh-sua', [DanhmucController::class, 'edit'])->name('chinh-sua-danh-muc');
//     Route::post('/{id}/cap-nhat', [DanhmucController::class, 'update'])->name('cap-nhat-danh-muc');

//     // Xóa
//     Route::delete('/{id}/xoa', [DanhmucController::class, 'destroy'])->name('xoa-danh-muc');
// });

// Route::prefix('thuong-hieu')->group(function () {
//     // Danh sách danh mục
//     Route::get('/danh-sach', [ThuonghieuController::class, 'index'])->name('danh-sach-thuong-hieu');
//     Route::get('/', [ThuonghieuController::class, 'index']);

//     // Form thêm mới
//     Route::get('/tao-thuong-hieu', [ThuonghieuController::class, 'create'])->name('tao-thuong-hieu');
//     Route::post('/luu', [ThuonghieuController::class, 'store'])->name('luu-thuong-hieu');

//     // Form sửa
//     Route::get('/{id}/chinh-sua', [ThuonghieuController::class, 'edit'])->name('chinh-sua-thuong-hieu');
//     Route::post('/{id}/cap-nhat', [ThuonghieuController::class, 'update'])->name('cap-nhat-thuong-hieu');

//     // Xóa
//     Route::delete('/{id}/xoa', [ThuonghieuController::class, 'destroy'])->name('xoa-thuong-hieu');
// });

// Route::prefix('kho-hang')->group(function () {
//     Route::get('/', [BientheController::class, 'index'])->name('danh-sach-kho-hang');
//     Route::get('/danh-sach', [BientheController::class, 'index']);
//     Route::get('/{id}/chinh-sua', [BientheController::class, 'edit'])->name('chinh-sua-hang-ton-kho');
//     Route::post('/{id}/cap-nhat', [BientheController::class, 'update'])->name('cap-nhat-hang-ton-kho');
//     Route::get('/{id}/xoa', [BientheController::class, 'destroy'])->name('xoa-hang-ton-kho');
// });

// Route::prefix('khach-hang')->group(function () {
//     Route::get('/', [NguoidungController::class,'index'])->name('danh-sach-khach-hang');
//     Route::get('/danh-sach', [NguoidungController::class,'index']);
// });


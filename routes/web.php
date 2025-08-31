<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\DanhmucController;
use App\Http\Controllers\ThuonghieuController;
use App\Http\Controllers\BientheController;
use App\Models\Sanpham;

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

Route::get('/', function () {
    return view('trangchu');
})->name('trang-chu');
Route::get('/trang-chu', function () {
    return view('trangchu');
});

Route::prefix('san-pham')->group(function () {
    Route::get('/danh-sach', [SanphamController::class, 'index'])->name('danh-sach');
    Route::get('/', [SanphamController::class, 'index']);
    Route::get('/tao-san-pham', [SanphamController::class, 'create'])->name('tao-san-pham');
    Route::post('/luu', [SanphamController::class, 'store'])->name('luu-san-pham');
    Route::get('/{slug}-{id}', [SanPhamController::class, 'show'])
    ->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-]+'])
    ->name('chi-tiet-san-pham');
    Route::get('/{id}/chinh-sua', [SanphamController::class, 'edit'])->name('chinh-sua-san-pham');
    Route::post('/{id}/cap-nhat', [SanphamController::class, 'update'])->name('cap-nhat-san-pham');
    Route::get('/{id}/xoa', [SanphamController::class, 'destroy'])->name('xoa-san-pham');
});

Route::prefix('danh-muc')->group(function () {
    // Danh sách danh mục
    Route::get('/danh-sach', [DanhmucController::class, 'index'])->name('danh-sach-danh-muc');
    Route::get('/', [DanhmucController::class, 'index']);

    // Form thêm mới
    Route::get('/tao-danh-muc', [DanhmucController::class, 'create'])->name('tao-danh-muc');
    Route::post('/luu', [DanhmucController::class, 'store'])->name('luu-danh-muc');

    // Form sửa
    Route::get('/{id}/chinh-sua', [DanhmucController::class, 'edit'])->name('chinh-sua-danh-muc');
    Route::post('/{id}/cap-nhat', [DanhmucController::class, 'update'])->name('cap-nhat-danh-muc');

    // Xóa
    Route::delete('/{id}/xoa', [DanhmucController::class, 'destroy'])->name('xoa-danh-muc');
});

Route::prefix('thuong-hieu')->group(function () {
    // Danh sách danh mục
    Route::get('/danh-sach', [ThuonghieuController::class, 'index'])->name('danh-sach-thuong-hieu');
    Route::get('/', [ThuonghieuController::class, 'index']);

    // Form thêm mới
    Route::get('/tao-thuong-hieu', [ThuonghieuController::class, 'create'])->name('tao-thuong-hieu');
    Route::post('/luu', [ThuonghieuController::class, 'store'])->name('luu-thuong-hieu');

    // Form sửa
    Route::get('/{id}/chinh-sua', [ThuonghieuController::class, 'edit'])->name('chinh-sua-thuong-hieu');
    Route::post('/{id}/cap-nhat', [ThuonghieuController::class, 'update'])->name('cap-nhat-thuong-hieu');

    // Xóa
    Route::delete('/{id}/xoa', [ThuonghieuController::class, 'destroy'])->name('xoa-thuong-hieu');
});

Route::prefix('kho-hang')->group(function () {
    Route::get('/', [BientheController::class, 'index'])->name('danh-sach-kho-hang');
    Route::get('/danh-sach', [BientheController::class, 'index']);
    Route::get('/{id}/chinh-sua', [BientheController::class, 'edit'])->name('chinh-sua-hang-ton-kho');
    Route::post('/{id}/cap-nhat', [BientheController::class, 'update'])->name('cap-nhat-hang-ton-kho');
    Route::get('/{id}/xoa', [BientheController::class, 'destroy'])->name('xoa-hang-ton-kho');
});


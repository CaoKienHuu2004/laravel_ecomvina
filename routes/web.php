<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\DanhmucController;
use App\Http\Controllers\ThuonghieuController;

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
});
Route::get('/trang-chu', function () {
    return view('trangchu');
});

Route::prefix('san-pham')->group(function () {
    Route::get('/danh-sach', [SanphamController::class, 'index'])->name('danh-sach');
    Route::get('/tao-san-pham', [SanphamController::class, 'create'])->name('tao-san-pham');
    Route::post('/store', [SanphamController::class, 'store'])->name('luu-san-pham');
});

Route::prefix('danh-muc')->group(function () {
    // Danh sách danh mục
    Route::get('/danh-sach', [DanhmucController::class, 'index'])->name('danh-sach-danh-muc');

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

    // Form thêm mới
    Route::get('/tao-thuong-hieu', [ThuonghieuController::class, 'create'])->name('tao-thuong-hieu');
    Route::post('/luu', [ThuonghieuController::class, 'store'])->name('luu-thuong-hieu');

    // Form sửa
    Route::get('/{id}/chinh-sua', [ThuonghieuController::class, 'edit'])->name('chinh-sua-thuong-hieu');
    Route::post('/{id}/cap-nhat', [ThuonghieuController::class, 'update'])->name('cap-nhat-thuong-hieu');

    // Xóa
    Route::delete('/{id}/xoa', [ThuonghieuController::class, 'destroy'])->name('xoa-thuong-hieu');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\DanhmucController;

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
    // Export PDF
    Route::get('/xuat-pdf', [DanhmucController::class, 'exportPdf'])->name('xuat-danh-muc-pdf');
    // Export Excel
    Route::get('/xuat-excel', [DanhmucController::class, 'exportExcel'])->name('xuat-danh-muc-excel');
    // Print
    Route::get('/in', [DanhmucController::class, 'print'])->name('in-danh-muc');

    // Form thêm mới
    Route::get('/create', [DanhmucController::class, 'create'])->name('tao-danh-muc');
    Route::post('/store', [DanhmucController::class, 'store'])->name('danhmuc.store');

    // Form sửa
    Route::get('/{id}/edit', [DanhmucController::class, 'edit'])->name('danhmuc.edit');
    Route::post('/{id}/update', [DanhmucController::class, 'update'])->name('danhmuc.update');

    // Xóa
    Route::get('/{id}/delete', [DanhmucController::class, 'destroy'])->name('danhmuc.destroy');
});

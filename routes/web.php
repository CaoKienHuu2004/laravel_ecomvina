<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\DanhmucController;
use App\Http\Controllers\ThuonghieuController;
use App\Http\Controllers\BientheController;
use App\Http\Controllers\NguoidungController;
use App\Http\Controllers\DonhangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes cho trang chủ + các module: Sản phẩm, Danh mục, Thương hiệu,
| Kho hàng, Khách hàng, Đơn hàng.
*/

Route::get('/', function () {
    return view('trangchu');
})->name('trang-chu');

Route::get('/trang-chu', function () {
    return view('trangchu');
});

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
    Route::put('/{id}/cap-nhat', [DonhangController::class, 'update'])->name('cap-nhat-don-hang'); // khớp @method('PUT')

    // Xem chi tiết
    Route::get('/{id}', [DonhangController::class, 'show'])->name('chi-tiet-don-hang');

    // Xóa
    Route::delete('/{id}/xoa', [DonhangController::class, 'destroy'])->name('xoa-don-hang');
});

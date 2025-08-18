<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;

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

Route::prefix('sanpham')->group(function () {
    Route::get('/', [SanphamController::class, 'index'])->name('danh-sach-san-pham');
    Route::get('/tao-san-pham', [SanphamController::class, 'create'])->name('tao-san-pham');
    Route::post('/store', [SanphamController::class, 'store'])->name('luu-san-pham');
});

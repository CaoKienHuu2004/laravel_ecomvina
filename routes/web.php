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
Route::get('/danh-sach-san-pham', [SanphamController::class, 'index']);
Route::get('/tao-san-pham', function () {
    return view('taosanpham');
});

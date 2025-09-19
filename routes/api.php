<?php

use App\Http\Controllers\API\DanhGiaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SanphamAPI;
use App\Http\Controllers\API\DanhmucAPI;
use App\Http\Controllers\API\DiaChiNguoiDungAPI;
use App\Http\Controllers\API\GioHangAPI;
use App\Http\Controllers\API\NguoidungAPI;
use App\Http\Controllers\Api\PhienDangNhapAPI;
use App\Http\Controllers\API\HanhviNguoidungAPI;
use App\Http\Controllers\API\MaGiamGiaAPI;
use App\Http\Controllers\API\QuatangKhuyenMaiAPI;
use App\Http\Controllers\API\SukienKhuyenMaiAPI;
use App\Http\Controllers\API\ThanhToanAPI;
use App\Http\Controllers\API\YeuThichAPI;
use App\Http\Controllers\API\ChuongTrinhSuKienAPI;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('sanphams', SanphamAPI::class)->only(['index','show']);
Route::apiResource('danhmucs', DanhmucAPI::class)->only(['index','show']);
Route::apiResource('nguoidungs', NguoidungAPI::class)->only(['index','show']);

Route::apiResource('diachis', DiaChiNguoiDungAPI::class)->only(['index','show']);
Route::apiResource('thanhtoans', ThanhToanAPI::class)->only(['index','show']);
Route::apiResource('chuongtrinhsukiens', ChuongTrinhSuKienAPI::class)->only(['index','show']);
Route::apiResource('quatangkhuyenmais', QuatangKhuyenMaiAPI::class)->only(['index','show']);
Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['index','show']);
Route::apiResource('danhgias', DanhGiaAPI::class)->only(['index','show']);

Route::apiResource('yeuthichs', YeuThichAPI::class)->only(['index','show']);

Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::apiResource('sanphams', SanphamAPI::class)->only(['store','update','destroy']);
    Route::apiResource('danhmucs', DanhmucAPI::class)->only(['store','update','destroy']);
    Route::apiResource('nguoidungs', NguoidungAPI::class)->only(['store','update','destroy']);

    Route::apiResource('diachis', DiaChiNguoiDungAPI::class)->only(['store','update','destroy']);
    Route::apiResource('thanhtoans', ThanhToanAPI::class)->only(['store','update','destroy']);
    Route::apiResource('chuongtrinhsukiens', ChuongTrinhSuKienAPI::class)->only(['store','update','destroy']);
    Route::apiResource('quatangkhuyenmais', QuatangKhuyenMaiAPI::class)->only(['store','update','destroy']);
    Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['store','update','destroy']);
    Route::apiResource('danhgias', DanhGiaAPI::class)->only(['store','update','destroy']);

    Route::apiResource('yeuthichs', YeuThichAPI::class)->only(['store','update','destroy']);
});

// Route::middleware(['auth:sanctum','role:admin,assistant'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index']);
// });

Route::middleware('apikey')->group(function () {
    // Route::get('/articles', [ArticleController::class, 'index']);
});

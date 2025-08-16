<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanphamController;
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

Route::prefix('sanpham')->group(function () {
    Route::get('/', [SanphamController::class, 'index']);        // Lấy danh sách + filter
    Route::post('/', [SanphamController::class, 'store']);       // Thêm mới
    Route::get('/{id}', [SanphamController::class, 'show']);     // Xem chi tiết
    Route::put('/{id}', [SanphamController::class, 'update']);   // Cập nhật
    Route::delete('/{id}', [SanphamController::class, 'destroy']);// Xoá
});

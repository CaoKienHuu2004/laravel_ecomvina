<?php

use App\Http\Controllers\Admin\AIController;
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

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\Frontend\GioHangFrontendAPI;
use App\Http\Controllers\API\LoaiBienTheAPI;


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


//////////////// begin:auth
Route::prefix('auth')->group(function () {
    Route::post('dang-nhap', [AuthController::class, 'login']);
    Route::post('dang-ky', [AuthController::class, 'register']);
    Route::post('quen-mat-khau', [AuthController::class, 'forgotPassword']);
    Route::post('dat-lai-mat-khau', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('thong-tin-nguoi-dung', [AuthController::class, 'userInfo']);
        // Route::get('thong-tin-nguoi-dung', function () {
        //     return response()->json(auth()->user());
        // });
        Route::post('dang-xuat', [AuthController::class, 'logout']);
        Route::put('cap-nhat-ho-so', [AuthController::class, 'updateProfile']);
        Route::put('doi-mat-khau', [AuthController::class, 'changePassword']); // Đổi mật khẩu khi đã đăng nhập
    });
});

//////////////// end:auth

//////////////// begin:admin ai
// Admin routes - Web interface
Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/', [AIController::class, 'index'])->name('index');
        Route::get('/intents/create', [AIController::class, 'createIntent'])->name('intents.create');
        Route::post('/intents', [AIController::class, 'storeIntent'])->name('intents.store');
        Route::get('/intents/{id}', [AIController::class, 'showIntent'])->name('intents.show');
        Route::post('/intents/{id}/training-data', [AIController::class, 'addTrainingData'])->name('intents.add-training');
        Route::post('/intents/{id}/responses', [AIController::class, 'addResponse'])->name('intents.add-response');
        Route::get('/conversations', [AIController::class, 'conversations'])->name('conversations');
        Route::get('/analytics', [AIController::class, 'analytics'])->name('analytics');
    });
});
// API routes
Route::post('/chat', [ChatController::class, 'processChat']);

    // Admin-only AI management endpoints
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/chat/train', [ChatController::class, 'addTrainingData']);
    Route::post('/chat/retrain', [ChatController::class, 'retrainModel']);
    Route::get('/chat/model-info', [ChatController::class, 'getModelInfo']);
    Route::get('/chat/debug', [ChatController::class, 'debugModel']);
});
//////////////// end:admin ai


// guest
Route::apiResource('sanphams', SanphamAPI::class)->only(['index','show']);
Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['index','show']); // làm menu khi hover list products da cấp
Route::apiResource('danhmucs', DanhmucAPI::class)->only(['index','show']);
Route::apiResource('chuongtrinhsukiens', ChuongTrinhSuKienAPI::class)->only(['index','show']);
Route::apiResource('quatangkhuyenmais', QuatangKhuyenMaiAPI::class)->only(['index','show']);
Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['index','show']);
Route::apiResource('danhgias', DanhGiaAPI::class)->only(['index','show']);
// guest

//begin: Api frontend // User + anonymous + admin

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me/giohang', [GioHangFrontendAPI::class, 'index']); // ở đây auth:sanctum ko giải quyết được nguoidungandanh guest phải dùng cách new Object(guest_id,session_id) hoặc Sử dụng cookies. 3. Khi người dùng đăng nhập  thì gộp giỏ hàng guest_id với user_id vào . Khi người dùng chưa đăng nhập, tạo một mã định danh tạm (guest_id) cho giỏ hàng..Lưu giỏ hàng trong database với cột guest_id
    Route::post('/me/giohang', [GioHangFrontendAPI::class, 'store']);
    Route::put('/me/giohang/{id_bienthesp}', [GioHangFrontendAPI::class, 'update']);
    Route::delete('/me/giohang/{id_bienthesp}', [GioHangFrontendAPI::class, 'destroy']);
});
// Route::middleware(['auth:sanctum','role:user,anonymous,admin'])->group(function () {
//     Route::get('giohang', [GioHangAPI::class, 'index']);
//     Route::get('diachi', [DiaChiNguoiDungAPI::class, 'index']); // chưa
//     Route::get('thanhtoan', [ThanhToanAPI::class, 'index']); // chưa
//     Route::get('yeuthich', [YeuThichAPI::class, 'index']); // chưa
// });
//end:Api frontend


//begin: Api back-end

// Admin only + have api-key
Route::middleware(['auth:sanctum','role:admin'])->group(function () {

    Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['store','update','destroy']);
    Route::apiResource('danhgias', DanhGiaAPI::class)->only(['store','update','destroy']);
    Route::apiResource('sanphams', SanphamAPI::class)->only(['store','update','destroy']);
    Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['store','update','destroy']); // làm menu khi hover list products da cấp
    Route::apiResource('danhmucs', DanhmucAPI::class)->only(['store','update','destroy']);
    Route::apiResource('chuongtrinhsukiens', ChuongTrinhSuKienAPI::class)->only(['store','update','destroy']);
    Route::apiResource('quatangkhuyenmais', QuatangKhuyenMaiAPI::class)->only(['store','update','destroy']);

    Route::apiResource('giohangs', GioHangAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('diachis', DiaChiNguoiDungAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('thanhtoans', ThanhToanAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('yeuthichs', YeuThichAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('nguoidungs', NguoidungAPI::class)->only(['index','show','store','update','destroy']);
});
//end:Api back-end

// cổng thành toán vnpay, momo
use App\Http\Controllers\API\CheckOutController;
use App\Http\Controllers\API\PaymentCallbackController;
// POST /api/checkout/vnpay → tạo link thanh toán VNPay
Route::post('/checkout/vnpay', [CheckOutController::class, 'vnpayCheckout']);
Route::post('/checkout/momo', [CheckOutController::class, 'momoCheckout']);
// Callback từ VNPay
Route::get('/payment/vnpay-return', [PaymentCallbackController::class, 'vnpayReturn']);
// Callback từ MoMo
Route::get('/payment/momo-return', [PaymentCallbackController::class, 'momoReturn']);
Route::post('/payment/momo-notify', [PaymentCallbackController::class, 'momoNotify']);
// cổng thành toán vnpay


// Route::middleware(['auth:sanctum','role:admin,assistant'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index']);
// });

// Route::middleware('apikey')->group(function () {
//     // Route::get('/articles', [ArticleController::class, 'index']);
// });

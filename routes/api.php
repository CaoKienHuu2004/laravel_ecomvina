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

use App\Http\Controllers\API\HanhviNguoidungAPI;
use App\Http\Controllers\API\MaGiamGiaAPI;
use App\Http\Controllers\API\QuatangKhuyenMaiAPI;
use App\Http\Controllers\API\SukienKhuyenMaiAPI;
use App\Http\Controllers\API\ThanhToanAPI;
use App\Http\Controllers\API\YeuThichAPI;
use App\Http\Controllers\API\ChuongTrinhSuKienAPI;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ChiTietDonHangAPI;
use App\Http\Controllers\Api\ChiTietGioHangController;

use App\Http\Controllers\API\Frontend\AuthFrontendController;
use App\Http\Controllers\API\Frontend\BannerQuangCaoFrontendAPI;
use App\Http\Controllers\API\Frontend\DanhmucAllFrontendAPI;
use App\Http\Controllers\API\Frontend\DanhmucFrontendAPI;
use App\Http\Controllers\API\Frontend\GioHangFrontendAPI;
use App\Http\Controllers\API\Frontend\SanPhamAllFrontendAPI;
use App\Http\Controllers\API\Frontend\SanPhamFrontendAPI;
use App\Http\Controllers\API\Frontend\TukhoaFrontendAPI;
use App\Http\Controllers\API\LoaiBienTheAPI;
use App\Http\Controllers\API\ThuongHieuAPI;

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


//------------------------ begin:auth sanctum Authentication API token, auth:sanctum bảo vệ routes me/profile
// Dùng cho SPA (cookie) có
Route::prefix('auth')->group(function () {
    Route::post('dang-nhap', [AuthController::class, 'login']);
    Route::post('dang-ky', [AuthController::class, 'register']);
    Route::post('quen-mat-khau', [AuthController::class, 'forgotPassword']);
    Route::post('dat-lai-mat-khau', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('thong-tin-nguoi-dung', [AuthController::class, 'userInfo']);
        Route::post('dang-xuat', [AuthController::class, 'logout']);
        Route::put('cap-nhat-ho-so', [AuthController::class, 'updateProfile']);
        Route::put('doi-mat-khau', [AuthController::class, 'changePassword']); // Đổi mật khẩu khi đã đăng nhập
    });
});

//------------------------ end:auth sanctum Authentication API token, auth:sanctum bảo vệ routes me/profile

//------------------------ begin:auth sanctum Authentication API token, auth:api bảo vệ routes me/profile,config/auth phải đê api :, token HTTP-only cookie
// Dùng cho SPA (cookie) không
Route::prefix('auth')->group(function () {
    Route::post('login',[AuthFrontendController::class,'login']);
    Route::post('register',[AuthFrontendController::class,'register']);
    Route::middleware('auth:api')->get('profile',[AuthFrontendController::class,'profile']);
    Route::middleware('auth:api')->get('logout',[AuthFrontendController::class,'logout']);
});
//------------------------ end:auth  auth:api bảo vệ routes me/profile,config/auth phải đê api : ,token HTTP-only cookie

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


// table tu_khoa srearch từ khóa nhiều nhất cho placehoder
// limit 5 Lấy danh sách tất cả từ khóa (sắp xếp theo số lượt giảm dần)
Route::apiResource('tukhoas', TukhoaFrontendAPI::class)->only(['index','store','update']);
    // Route riêng cho tăng lượt
    // Route::post('tukhoas/{id}/increment', [TukhoaFrontendAPI::class, 'increment']);
//bảng banner_quangcao
Route::apiResource('bannerquangcaos', BannerQuangCaoFrontendAPI::class)->only(['index']);
//bảng banner_quangcao

// có slug: sanpham, danhmuc,
Route::apiResource('sanphams', SanphamAPI::class)->only(['index','show']);
    Route::apiResource('sanphams-selection', SanPhamFrontendAPI::class)->only(['index','show']);
    Route::apiResource('sanphams-all', SanPhamAllFrontendAPI::class)->only(['index','show']);
    // GET /api/sanphams-selection?selection=hot_sales // limit 10 //  giả cả rẻ + giảm giá + nhiều đơn hàng của sản phẩm nhất
    // GET /api/sanphams-selection?selection=top_categories // limit 4:danhmuc limit 8:sanpham // tồng bộ danh mục luôn + querry có lượt mua nhiều nhất
    // GET /api/sanphams-selection?selection=top_brands // limit 10 // nhiều đơn hàng của sản phẩm nhất // list danh sách thuong hieu ko phải sản phẩm
    // GET /api/sanphams-selection?selection=best_products // limit 8 // nhiều đơn hàng của sản phẩm nhất và đánh giá // list danh sách sản phẩm
    // GET /api/sanphams-selection?selection=recommend&danhmuc_id=3 hoặc // limit 8 //Recommend (theo danh mục): // tùy theo lược xem + giả cả rẻ + giảm giá
    // GET /api/sanphams-selection?per_page=20&page=1&thuonghieu=2&gia_min=100000&gia_max=500000 //Default (phân trang + filter): //

Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['index','show']); // làm menu khi hover list products da cấp
Route::apiResource('danhmucs', DanhmucAPI::class)->only(['index','show']);
    Route::apiResource('danhmucs-selection', DanhmucFrontendAPI::class)->only(['index','show']); // selection: ở home // limit 10 // orderby theo danh mục có tổng lượt xem và có lượt mua nhiều nhất
    Route::apiResource('danhmucs-all', DanhmucAllFrontendAPI::class)->only(['index','show']); // thanh menu aside lọc sản phẩm

Route::apiResource('chuongtrinhsukiens', ChuongTrinhSuKienAPI::class)->only(['index','show']);
Route::apiResource('quatangkhuyenmais', QuatangKhuyenMaiAPI::class)->only(['index','show']);
Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['index','show']);
Route::apiResource('danhgias', DanhGiaAPI::class)->only(['index','show']);
// guest

//begin: Api frontend // User + anonymous + admin



// Guest (chưa đăng nhập) - giỏ hàng tạm (session / local storage) // nếu muốn nextjs client tạo seesion server sau khi đã sanctum:cookie-xxxx, và trong controller GioHang::where('guest_id', $sessionId)->get() còn đã auth GioHang::where('user_id', $request->user()->id)->get()
// Route::get('/guest/giohang', [GuestCartAPI::class, 'index']);
// Route::post('/guest/giohang', [GuestCartAPI::class, 'store']);
// Route::put('/guest/giohang/{id_bienthesp}', [GuestCartAPI::class, 'update']);
// Route::delete('/guest/giohang/{id_bienthesp}', [GuestCartAPI::class, 'destroy']);

// Route::middleware(['auth:sanctum'])->group(function () {
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me/giohang', [GioHangFrontendAPI::class, 'index']);
    Route::post('/me/giohang', [GioHangFrontendAPI::class, 'store']);
    Route::put('/me/giohang/{id_bienthesp}', [GioHangFrontendAPI::class, 'update']);
    Route::delete('/me/giohang/{id_bienthesp}', [GioHangFrontendAPI::class, 'destroy']);
});
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/me/giohang', [GioHangAPI::class, 'index']);
//     Route::get('/me/diachi', [DiaChiNguoiFrontendDungAPI::class, 'index']); // chưa
//     Route::get('/me/thanhtoan', [ThanhToanFrontendAPI::class, 'index']); // chưa
//     Route::get('/me/yeuthich', [YeuThichFrontendAPI::class, 'index']); // chưa
//     Route::get('/me/donhang', [DonHangFrontendAPI::class, 'index']); // chưa
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
    Route::apiResource('chitietgiohangs', ChiTietGioHangController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('diachis', DiaChiNguoiDungAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('thanhtoans', ThanhToanAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('yeuthichs', YeuThichAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('nguoidungs', NguoidungAPI::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('thuonghieus', ThuongHieuAPI::class)->only(['index','show','store','update','destroy']);
});
//end:Api back-end

// cổng thành toán vnpay, momo
use App\Http\Controllers\API\CheckOutController;
use App\Http\Controllers\API\PaymentCallbackController;
// POST /api/checkout/vnpay → tạo link thanh toán VNPay
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/checkout/vnpay', [CheckOutController::class, 'vnpayCheckout']);
    Route::post('/checkout/momo', [CheckOutController::class, 'momoCheckout']);
    // Callback từ VNPay
    Route::get('/payment/vnpay-return', [PaymentCallbackController::class, 'vnpayReturn']);
    // Callback từ MoMo
    Route::get('/payment/momo-return', [PaymentCallbackController::class, 'momoReturn']);
    Route::post('/payment/momo-notify', [PaymentCallbackController::class, 'momoNotify']);
});
// cổng thành toán vnpay


// Route::middleware(['auth:sanctum','role:admin,assistant'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index']);
// });

// Route::middleware('apikey')->group(function () {
//     // Route::get('/articles', [ArticleController::class, 'index']);
// });

<?php

// use App\Http\Controllers\Admin\AIController;
// use App\Http\Controllers\API\DanhGiaAPI;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\SanphamAPI;
// use App\Http\Controllers\API\DanhmucAPI;
// use App\Http\Controllers\API\DiaChiNguoiDungAPI;
// use App\Http\Controllers\API\GioHangAPI;
// use App\Http\Controllers\API\NguoidungAPI;

// use App\Http\Controllers\API\HanhviNguoidungAPI;
// use App\Http\Controllers\API\MaGiamGiaAPI;
// use App\Http\Controllers\API\QuatangKhuyenMaiAPI;
// use App\Http\Controllers\API\SukienKhuyenMaiAPI;
// use App\Http\Controllers\API\ThanhToanAPI;
// use App\Http\Controllers\API\YeuThichAPI;
// use App\Http\Controllers\API\ChuongTrinhSuKienAPI;

// use App\Http\Controllers\API\AuthController;
// use App\Http\Controllers\API\ChatController;
// use App\Http\Controllers\API\ChiTietDonHangAPI;
// use App\Http\Controllers\Api\ChiTietGioHangController;

// use App\Http\Controllers\API\Frontend\AuthFrontendController;
// use App\Http\Controllers\API\Frontend\BannerQuangCaoFrontendAPI;
// use App\Http\Controllers\API\Frontend\DanhmucAllFrontendAPI;
// use App\Http\Controllers\API\Frontend\DanhmucFrontendAPI;
// use App\Http\Controllers\API\Frontend\GioHangFrontendAPI;
// use App\Http\Controllers\API\Frontend\SanPhamAllFrontendAPI;
// use App\Http\Controllers\API\Frontend\SanPhamFrontendAPI;
// use App\Http\Controllers\API\Frontend\TukhoaFrontendAPI;
// use App\Http\Controllers\API\LoaiBienTheAPI;
// use App\Http\Controllers\API\ThuongHieuAPI;

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


// //------------------------ begin:auth sanctum Authentication API token, auth:sanctum bảo vệ routes me/profile
// //
// Route::prefix('auth')->group(function () {
//     Route::post('dang-nhap', [AuthController::class, 'login']);
//     Route::post('dang-ky', [AuthController::class, 'register']);
//     Route::post('quen-mat-khau', [AuthController::class, 'forgotPassword']);
//     Route::post('dat-lai-mat-khau', [AuthController::class, 'resetPassword']);

//     Route::middleware('auth:sanctum')->group(function () {
//         Route::get('thong-tin-nguoi-dung', [AuthController::class, 'userInfo']);
//         Route::post('dang-xuat', [AuthController::class, 'logout']);
//         Route::put('cap-nhat-ho-so', [AuthController::class, 'updateProfile']);
//         Route::put('doi-mat-khau', [AuthController::class, 'changePassword']); // Đổi mật khẩu khi đã đăng nhập
//     });
// });



// // API routes




// // guest


// // table tu_khoa srearch từ khóa nhiều nhất cho placehoder
// // limit 5 Lấy danh sách tất cả từ khóa (sắp xếp theo số lượt giảm dần)
// Route::apiResource('tukhoas', TukhoaFrontendAPI::class)->only(['index','store','update']);
//     // Route riêng cho tăng lượt
//     // Route::post('tukhoas/{id}/increment', [TukhoaFrontendAPI::class, 'increment']);
// //bảng banner_quangcao
// Route::apiResource('bannerquangcaos', BannerQuangCaoFrontendAPI::class)->only(['index']);
// //bảng banner_quangcao

// // có slug: sanpham, danhmuc,
// Route::apiResource('sanphams', SanphamAPI::class)->only(['index','show']);
//     Route::apiResource('sanphams-selection', SanPhamFrontendAPI::class)->only(['index','show']);
//     Route::apiResource('sanphams-all', SanPhamAllFrontendAPI::class)->only(['index','show']);
//     // GET /api/sanphams-selection?selection=hot_sales // limit 10 //  giả cả rẻ + giảm giá + nhiều đơn hàng của sản phẩm nhất
//     // GET /api/sanphams-selection?selection=top_categories // limit 4:danhmuc limit 8:sanpham // tồng bộ danh mục luôn + querry có lượt mua nhiều nhất
//     // GET /api/sanphams-selection?selection=top_brands // limit 10 // nhiều đơn hàng của sản phẩm nhất // list danh sách thuong hieu ko phải sản phẩm
//     // GET /api/sanphams-selection?selection=best_products // limit 8 // nhiều đơn hàng của sản phẩm nhất và đánh giá // list danh sách sản phẩm
//     // GET /api/sanphams-selection?selection=recommend&danhmuc_id=3 hoặc // limit 8 //Recommend (theo danh mục): // tùy theo lược xem + giả cả rẻ + giảm giá
//     // GET /api/sanphams-selection?per_page=20&page=1&thuonghieu=2&gia_min=100000&gia_max=500000 //Default (phân trang + filter): //

// Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['index','show']); // làm menu khi hover list products da cấp
// Route::apiResource('danhmucs', DanhmucAPI::class)->only(['index','show']);
//     Route::apiResource('danhmucs-selection', DanhmucFrontendAPI::class)->only(['index','show']); // selection: ở home // limit 10 // orderby theo danh mục có tổng lượt xem và có lượt mua nhiều nhất
//     Route::apiResource('danhmucs-all', DanhmucAllFrontendAPI::class)->only(['index','show']); // thanh menu aside lọc sản phẩm

// Route::apiResource('chuongtrinhsukiens', ChuongTrinhSuKienAPI::class)->only(['index','show']);
// Route::apiResource('quatangkhuyenmais', QuatangKhuyenMaiAPI::class)->only(['index','show']);
// Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['index','show']);
// Route::apiResource('danhgias', DanhGiaAPI::class)->only(['index','show']);
// // guest

// //begin: Api frontend // User + anonymous + admin


// //begin: Api back-end

// // Admin only + have api-key
// Route::middleware(['auth:sanctum','role:admin'])->group(function () {

//     Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['store','update','destroy']);
//     Route::apiResource('danhgias', DanhGiaAPI::class)->only(['store','update','destroy']);
//     Route::apiResource('sanphams', SanphamAPI::class)->only(['store','update','destroy']);
//     Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['store','update','destroy']); // làm menu khi hover list products da cấp
//     Route::apiResource('danhmucs', DanhmucAPI::class)->only(['store','update','destroy']);
//     Route::apiResource('chuongtrinhsukiens', ChuongTrinhSuKienAPI::class)->only(['store','update','destroy']);
//     Route::apiResource('quatangkhuyenmais', QuatangKhuyenMaiAPI::class)->only(['store','update','destroy']);

//     Route::apiResource('giohangs', GioHangAPI::class)->only(['index','show','store','update','destroy']);
//     Route::apiResource('chitietgiohangs', ChiTietGioHangController::class)->only(['index','show','store','update','destroy']);
//     Route::apiResource('diachis', DiaChiNguoiDungAPI::class)->only(['index','show','store','update','destroy']);
//     Route::apiResource('thanhtoans', ThanhToanAPI::class)->only(['index','show','store','update','destroy']);
//     Route::apiResource('yeuthichs', YeuThichAPI::class)->only(['index','show','store','update','destroy']);
//     Route::apiResource('nguoidungs', NguoidungAPI::class)->only(['index','show','store','update','destroy']);
//     Route::apiResource('thuonghieus', ThuongHieuAPI::class)->only(['index','show','store','update','destroy']);
// });
// //end:Api back-end


// // Route::middleware(['auth:sanctum','role:admin,assistant'])->group(function () {
// //     Route::get('/dashboard', [DashboardController::class, 'index']);
// // });

// // Route::middleware('apikey')->group(function () {
// //     // Route::get('/articles', [ArticleController::class, 'index']);
// // });

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


use App\Http\Controllers\API\Frontend\AuthFrontendController;
use App\Http\Controllers\API\Frontend\BannerQuangCaoFrontendAPI;
use App\Http\Controllers\API\Frontend\DanhGiaFrontendAPI;
use App\Http\Controllers\API\Frontend\DanhmucAllFrontendAPI;
use App\Http\Controllers\API\Frontend\DanhmucFrontendAPI;
use App\Http\Controllers\API\Frontend\DiaChiFrontendAPI;
use App\Http\Controllers\API\Frontend\DonHangFrontendAPI;
use App\Http\Controllers\API\Frontend\GioHangFrontendAPI;
use App\Http\Controllers\API\Frontend\SanPhamAllFrontendAPI;
use App\Http\Controllers\API\Frontend\SanPhamFrontendAPI;
use App\Http\Controllers\API\Frontend\TheoDoiDonHangFrontendAPI;
use App\Http\Controllers\API\Frontend\ThongBaoFrontendAPI;
use App\Http\Controllers\API\Frontend\TimKiemAPI;
use App\Http\Controllers\API\Frontend\TinhThanhVietNamFrontendAPI;
use App\Http\Controllers\API\Frontend\TrangChuAPI;
use App\Http\Controllers\API\Frontend\TukhoaFrontendAPI;
use App\Http\Controllers\API\Frontend\YeuThichFrontendAPI;
use App\Http\Controllers\API\LoaiBienTheAPI;
use App\Http\Controllers\API\QuaTangSuKienAPI;
use App\Http\Controllers\API\SuKienAPI;
use App\Http\Controllers\API\ThongBaoAPI;

//------------------------
// Route::middleware(['cors'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('dang-nhap', [AuthFrontendController::class, 'login']);
        Route::post('dang-ky', [AuthFrontendController::class, 'register']);

        Route::middleware('auth.api')->group(function () {
            Route::get('thong-tin-nguoi-dung', [AuthFrontendController::class, 'profile']);
            Route::post('cap-nhat-thong-tin', [AuthFrontendController::class, 'updateProfile']);
            Route::post('dang-xuat', [AuthFrontendController::class, 'logout']);
        });
    });

    // guest

    Route::apiResource('trang-chu', TrangChuAPI::class)->only(['index']);
        Route::apiResource('tim-kiem', TimKiemAPI::class)->only(['index']);
    // table tu_khoa srearch từ khóa nhiều nhất cho placehoder
    // limit 5 Lấy danh sách tất cả từ khóa (sắp xếp theo số lượt giảm dần)
    Route::apiResource('tukhoas', TukhoaFrontendAPI::class)->only(['index','store','update']);
        // Route riêng cho tăng lượt
        // Route::post('tukhoas/{id}/increment', [TukhoaFrontendAPI::class, 'increment']);
    //bảng banner_quangcao
    Route::apiResource('bannerquangcaos', BannerQuangCaoFrontendAPI::class)->only(['index']);
    //bảng banner_quangcao

    Route::get('/tinh-thanh', [TinhThanhVietNamFrontendAPI::class, 'index']);

    // có slug: sanpham, danhmuc,
    Route::apiResource('sanphams', SanphamAPI::class)->only(['index','show']);

        Route::apiResource('sanphams-all', SanPhamAllFrontendAPI::class)->only(['index','show']);
        // GET /api/sanphams-selection?selection=hot_sales // limit 10 //  giả cả rẻ + giảm giá + nhiều đơn hàng của sản phẩm nhất
        // GET /api/sanphams-selection?selection=top_categories // limit 4:danhmuc limit 8:sanpham // tồng bộ danh mục luôn + querry có lượt mua nhiều nhất
        // GET /api/sanphams-selection?selection=top_brands // limit 10 // nhiều đơn hàng của sản phẩm nhất // list danh sách thuong hieu ko phải sản phẩm
        // GET /api/sanphams-selection?selection=best_products // limit 8 // nhiều đơn hàng của sản phẩm nhất và đánh giá // list danh sách sản phẩm
        // GET /api/sanphams-selection?selection=recommend&danhmuc_id=3 hoặc // limit 8 //Recommend (theo danh mục): // tùy theo lược xem + giả cả rẻ + giảm giá
        // GET /api/sanphams-selection?per_page=20&page=1&thuonghieu=2&gia_min=100000&gia_max=500000 //Default (phân trang + filter): //

    Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['index','show']); // làm menu khi hover list products da cấp
    Route::apiResource('danhmucs', DanhmucAPI::class)->only(['index','show']);
        // Route::apiResource('danhmucs-selection', DanhmucFrontendAPI::class)->only(['index','show']); // tổng hợp vào sanpham-seletion rồi nên nó thành dữ
        Route::apiResource('danhmucs-all', DanhmucAllFrontendAPI::class)->only(['index']); // thanh menu aside lọc sản phẩm củ, UI mới thì ko chưa biết chưa có cứ làm tạm vậy

    Route::apiResource('chuongtrinhsukiens', SuKienAPI::class)->only(['index','show']);
    Route::apiResource('quatangkhuyenmais', QuaTangSuKienAPI::class)->only(['index','show']);
    Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['index','show']);
    Route::apiResource('danhgias', DanhGiaAPI::class)->only(['index','show']);
    // guest

    //begin: Api frontend // User + anonymous + admin



    // Guest (chưa đăng nhập) - giỏ hàng tạm (session / local storage) // nếu muốn nextjs client tạo seesion server sau khi đã sanctum:cookie-xxxx, và trong controller GioHang::where('guest_id', $sessionId)->get() còn đã auth GioHang::where('user_id', $request->user()->id)->get()
    // Route::get('/guest/giohang', [GuestCartAPI::class, 'index']);
    // Route::post('/guest/giohang', [GuestCartAPI::class, 'store']);
    // Route::put('/guest/giohang/{id_bienthesp}', [GuestCartAPI::class, 'update']);
    // Route::delete('/guest/giohang/{id_bienthesp}', [GuestCartAPI::class, 'destroy']);

    Route::middleware(['auth.api'])->group(function () {
        Route::get('/toi/giohang', [GioHangFrontendAPI::class, 'index']);
        Route::post('/toi/giohang', [GioHangFrontendAPI::class, 'store']);
        Route::put('/toi/giohang/{id}', [GioHangFrontendAPI::class, 'update']);
        Route::delete('/toi/giohang/{id}', [GioHangFrontendAPI::class, 'destroy']);
    });
    Route::middleware(['auth.api'])->group(function () {
        Route::get('/toi/donhangs', [DonHangFrontendAPI::class, 'index']);
        Route::post('/toi/donhangs', [DonHangFrontendAPI::class, 'store']);
        Route::put('/toi/donhangs/{id}', [DonHangFrontendAPI::class, 'update']);
        Route::patch('/toi/donhangs/{id}/huy', [DonHangFrontendAPI::class, 'cancel']);
    });
        Route::middleware(['auth.api'])->group(function () {
            Route::get('/toi/theodoi-donhang', [TheoDoiDonHangFrontendAPI::class, 'index']);
            Route::put('/toi/theodoi-donhang/{id}', [TheoDoiDonHangFrontendAPI::class, 'update']);
        });
    Route::middleware(['auth.api'])->group(function () {
        Route::get('/toi/danhgias', [DanhGiaFrontendAPI::class, 'index']);
        Route::post('/toi/danhgias', [DanhGiaFrontendAPI::class, 'store']);
        Route::put('/toi/danhgias/{id}', [DanhGiaFrontendAPI::class, 'update']);
        Route::delete('/toi/danhgias/{id}', [DanhGiaFrontendAPI::class, 'destroy']);
    });
    Route::middleware(['auth.api'])->prefix('toi')->group(function () {
        Route::get('/yeuthichs', [YeuThichFrontendAPI::class, 'index']); // Xem danh sách yêu thích
        Route::post('/yeuthichs', [YeuThichFrontendAPI::class, 'store']); // Thêm sản phẩm vào yêu thích
        Route::put('/yeuthichs/{id_sanpham}', [YeuThichFrontendAPI::class, 'update']); // Bỏ yêu thích (chuyển trạng thái)
    });
    Route::middleware(['auth.api'])->prefix('toi')->group(function () {
        Route::get('/diachis', [DiaChiFrontendAPI::class, 'index']);
        Route::post('/diachis', [DiaChiFrontendAPI::class, 'store']);
        Route::put('/diachis/{id}', [DiaChiFrontendAPI::class, 'update']);
        Route::delete('/diachis/{id}', [DiaChiFrontendAPI::class, 'destroy']);
        Route::patch('/diachis/{id}/macdinh', [DiaChiFrontendAPI::class, 'setDefault']);
        Route::patch('/diachis/{id}/trangthai', [DiaChiFrontendAPI::class, 'toggleStatus']);
    });
    Route::middleware(['auth.api'])->prefix('toi')->group(function () {
        Route::get('/thongbaos', [ThongBaoFrontendAPI::class, 'index']);       // Lấy danh sách
        Route::delete('/thongbaos/{id}', [ThongBaoFrontendAPI::class, 'destroy']); // Xóa soft
        Route::patch('/thongbaos/{id}/daxem', [ThongBaoFrontendAPI::class, 'markAsRead']); // Optional
        Route::patch('/thongbaos/{id}/tam-an', [ThongBaoFrontendAPI::class, 'toggleStatus']); // Optional
    });
    //end:Api frontend


    //begin: Api back-end

    // Admin only + have api-key
    Route::middleware(['auth.api','vaitro:admin'])->group(function () {

        Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['store','update','destroy']);
        Route::apiResource('danhgias', DanhGiaAPI::class)->only(['store','update','destroy']);
        Route::apiResource('sanphams', SanphamAPI::class)->only(['store','update','destroy']);
        Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['store','update','destroy']); // làm menu khi hover list products da cấp
        Route::apiResource('danhmucs', DanhmucAPI::class)->only(['store','update','destroy']);
        Route::apiResource('chuongtrinhsukiens', SuKienAPI::class)->only(['store','update','destroy']);
        Route::apiResource('quatangkhuyenmais', QuaTangSuKienAPI::class)->only(['store','update','destroy']);

        Route::apiResource('giohangs', GioHangAPI::class)->only(['index','show','store','update','destroy']);

        Route::apiResource('diachis', DiaChiNguoiDungAPI::class)->only(['index','show','store','update','destroy']);

        Route::apiResource('yeuthichs', YeuThichAPI::class)->only(['index','show','store','update','destroy']);
        Route::apiResource('nguoidungs', NguoidungAPI::class)->only(['index','show','store','update','destroy']);


        Route::apiResource('thongbaos', ThongBaoAPI::class)->only(['index','show','store','update','destroy']);

    });

    Route::middleware(['auth.api','vaitro:seller'])->group(function () {
        Route::apiResource('magiamgias', MaGiamGiaAPI::class)->only(['store','update','destroy']);
        Route::apiResource('danhgias', DanhGiaAPI::class)->only(['store','update','destroy']);
        Route::apiResource('sanphams', SanphamAPI::class)->only(['store','update','destroy']);
        Route::apiResource('loaibienthes', LoaiBienTheAPI::class)->only(['store','update','destroy']); // làm menu khi hover list products da cấp
        Route::apiResource('danhmucs', DanhmucAPI::class)->only(['store','update','destroy']);
        Route::apiResource('chuongtrinhsukiens', SuKienAPI::class)->only(['store','update','destroy']);
        Route::apiResource('quatangkhuyenmais', QuaTangSuKienAPI::class)->only(['store','update','destroy']);

        Route::apiResource('giohangs', GioHangAPI::class)->only(['index','show','store','update','destroy']);

        Route::apiResource('diachis', DiaChiNguoiDungAPI::class)->only(['index','show','store','update','destroy']);

        Route::apiResource('yeuthichs', YeuThichAPI::class)->only(['index','show','store','update','destroy']);
        Route::apiResource('nguoidungs', NguoidungAPI::class)->only(['index','show','store','update','destroy']);

    });
// });


//end:Api back-end

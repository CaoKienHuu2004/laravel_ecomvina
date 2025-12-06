<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\RateLimiter; // chống spam

class GuiThongBaoFrontendAPI extends Controller
{
    //
    use ApiResponse;
    /**
    * @OA\Post(
    *     path="/api/gui-lien-he",
    *     summary="Gửi liên hệ từ trang liên hệ",
    *       tags={"Liên hệ"},
    *     description="
    * API dùng để khách hàng gửi thông tin liên hệ tới hệ thống.
    *
    * Bao gồm cơ chế chống spam:
    * - Rate Limit: tối đa 3 yêu cầu / phút mỗi IP
    * - Honeypot: phát hiện bot tự động
    * - Chặn gửi nội dung trùng lặp trong 30 giây
    * - Chặn từ khóa spam: http, https, www, quảng cáo,...
    * - Chặn email rác (mailinator, yopmail,...)
    *
    * Khi gửi thành công, hệ thống sẽ tạo 1 bản ghi trong bảng 'thongbao'
    * và gửi đến tài khoản admin.",
    *
    *
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"hoten", "sodienthoai", "tieude", "noidung"},
    *             @OA\Property(property="hoten", type="string", example="Nguyễn Văn A"),
    *             @OA\Property(property="email", type="string", nullable=true, example="vana@gmail.com"),
    *             @OA\Property(property="sodienthoai", type="string", example="0987654321"),
    *             @OA\Property(property="tieude", type="string", example="Tôi muốn hỏi về sản phẩm"),
    *             @OA\Property(property="noidung", type="string", example="Cho tôi hỏi sản phẩm này còn hàng không?"),
    *             @OA\Property(property="website", type="string", nullable=true, example=null, description="Trường ẩn để chống bot (honeypot). Người dùng thật KHÔNG được nhập.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Gửi liên hệ thành công",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Gửi liên hệ thành công!")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Lỗi hệ thống hoặc bị phát hiện spam",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Phát hiện spam.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=422,
    *         description="Dữ liệu không hợp lệ (validate lỗi)",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="The hoten field is required."),
    *             @OA\Property(property="errors", type="object")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=429,
    *         description="Gửi quá nhanh (Rate Limit)",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Bạn gửi quá nhanh, vui lòng thử lại sau ít phút.")
    *         )
    *     )
    * )
    */
    public function guiLienHe(Request $request)
    {
        // CHỐNG SPAM: Rate Limit theo IP
        $key = 'contact:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Bạn gửi quá nhanh, vui lòng thử lại sau ít phút.',
            ], 429);
        }
        RateLimiter::hit($key, 60); // 60 giây

        // Honeypot: bắt bot
        if ($request->filled('website')) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Phát hiện spam.',
            ], 400);
        }

        // Chống gửi cùng nội dung liên tục
        $duplicate = ThongbaoModel::where('noidung', $request->noidung)
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();
        if ($duplicate) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Nội dung trùng lặp, nghi ngờ spam.',
            ], 400);
        }

        // $validated = $request->validate([
            //     'hoten' => 'required|string|max:255',
            //     'email' => 'required|email|max:255',
            //     'sodienthoai' => 'nullable|string|max:20',
            //     'tieude' => 'required|string',
            //     'noidung' => 'required|string',
            // ]);
        // Validate
        $validated = $request->validate([ //Injection attacks hoặc có thể là XSS
            'hoten' => [
                'required',
                'string',
                'max:30',
                'regex:/^[\p{L}\p{N}\s\.\,\-]+$/u'
            ],
            'email' => [ //Chặn email rác hoặc định dạng giả
                'nullable',
                'email',
                'max:50',
                'not_regex:/@(mailinator|tempmail|yopmail)\./i'
            ],
            'sodienthoai' => [
                'required',
                'string',
                'max:10',
                'regex:/^[0-9\+\-\s]+$/'
            ],
            'tieude' => [
                'required',
                'string',
                'regex:/^[\p{L}\p{N}\s\.\,\-]+$/u'
            ],
            'noidung' => [ //hặn từ khóa spam, quảng cáo, link (chống bot nhập link)
                'required',
                'string',
                'regex:/^(?!.*(http|https|www|link|quảng cáo|promo))[a-zA-Z0-9À-ỹ\s.,!?"\'()-]*$/iu'
            ],
        ]);


        $admin = NguoidungModel::where('vaitro', 'admin')->first(); // tìm admin mà cái này nếu chuyển database nguoidung(user) và khachhang(cumtomer), là nó sai liền- ý là tài khảon admin ko để chung với tài khoản người dùng=khách hàng
        if (!$admin) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Hệ thống có lỗi.',
            ], 400);
        }

        ThongbaoModel::create([
            'id_nguoidung' => $admin->id,
            'tieude' => $validated['tieude'],
            'noidung' => "Họ tên: {$validated['hoten']}, Email: {$validated['email']}, SĐT: {$validated['sodienthoai']}, Nội dung: {$validated['noidung']}",
            'lienket' => null,
            'loaithongbao' => 'Hệ thống',
            'trangthai' => 'Chưa đọc',
        ]);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Gửi liên hệ thành công!',
        ]);
    }
}

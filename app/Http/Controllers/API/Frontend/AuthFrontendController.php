<?php

namespace App\Http\Controllers\API\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Models\NguoidungModel;

/**
 * @OA\Schema(
 *     schema="NguoiDung",
 *     title="Người dùng",
 *     description="Thông tin chi tiết người dùng",
 *     @OA\Property(property="id", type="integer", example=1, description="ID tự tăng của người dùng"),
 *     @OA\Property(property="username", type="string", example="khachhang01", description="Tên đăng nhập"),
 *     @OA\Property(property="password", type="string", example="hashedpassword123", description="Mật khẩu đã mã hóa"),
 *     @OA\Property(property="sodienthoai", type="string", example="0987654321", description="Số điện thoại liên hệ"),
 *     @OA\Property(property="hoten", type="string", example="Nguyễn Văn A", description="Họ và tên đầy đủ"),
 *     @OA\Property(
 *         property="gioitinh",
 *         type="string",
 *         enum={"Nam","Nữ"},
 *         example="Nam",
 *         description="Giới tính của người dùng"
 *     ),
 *     @OA\Property(property="ngaysinh", type="string", format="date", example="1990-01-01", description="Ngày sinh"),
 *     @OA\Property(property="avatar", type="string", example="khachhang.jpg", description="Ảnh đại diện"),
 *     @OA\Property(
 *         property="vaitro",
 *         type="string",
 *         enum={"admin","seller","client"},
 *         example="client",
 *         description="Vai trò của người dùng"
 *     ),
 *     @OA\Property(
 *         property="trangthai",
 *         type="string",
 *         enum={"Hoạt động","Tạm khóa","Dừng hoạt động"},
 *         example="Hoạt động",
 *         description="Trạng thái tài khoản"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-15T10:00:00Z", description="Thời gian tạo bản ghi"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-15T10:05:00Z", description="Thời gian cập nhật bản ghi"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null, description="Thời gian xóa mềm (soft delete)")
 * )
 */
class AuthFrontendController extends BaseFrontendController
{
    /**
     * @OA\Post(
     *     path="/api/auth/dang-nhap",
     *     tags={"Xác thực người dùng (Auth)"},
     *     summary="Đăng nhập người dùng",
     *     description="Gửi username và password để đăng nhập, trả về token hợp lệ.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="khacduy"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Đăng nhập thành công"),
     *     @OA\Response(response=401, description="Tên đăng nhập hoặc mật khẩu không chính xác")
     * )
     */
    public function login(Request $req)
    {
        $req->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = NguoidungModel::where('username', $req->username)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Tên đăng nhập hoặc mật khẩu không chính xác 😓"
            ], 401);
        }

        $token = Str::random(60);
        $key = "api_token:$token";
        Redis::setex($key, 86400, $user->id);

        return $this->jsonResponse([
            'success' => true,
            'token' => $token,
            'message' => "Đăng Nhập Thành Công"
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/dang-ky",
     *     tags={"Xác thực người dùng (Auth)"},
     *     summary="Đăng ký tài khoản mới",
     *     description="Tạo tài khoản mới bằng tên, username và mật khẩu.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","username","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Nguyễn Văn Duy"),
     *             @OA\Property(property="username", type="string", example="duy123"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="password_confirmation", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Đăng ký thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ hoặc username đã tồn tại")
     * )
     */
    public function register(Request $req)
    {
        $req->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:nguoidung,username',
            'password' => 'required|string|confirmed',
        ]);

        $user = NguoidungModel::create([
            'name' => $req->name,
            'username' => $req->username,
            'password' => bcrypt($req->password),
        ]);

        $token = Str::random(60);
        Redis::setex("api_token:$token", 86400, $user->id);

        return $this->jsonResponse([
            'success' => true,
            'token' => $token,
            'message' => "Đăng Ký Thành Công"
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/thong-tin-nguoi-dung",
     *     tags={"Xác thực người dùng (Auth)"},
     *     summary="Lấy thông tin người dùng hiện tại",
     *     description="Yêu cầu header Authorization: Bearer {token}",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Trả về thông tin người dùng"),
     *     @OA\Response(response=401, description="Token không hợp lệ hoặc đã hết hạn")
     * )
     */
    public function profile(Request $req)
    {
        $token = $req->bearerToken();
        $key = "api_token:$token";
        $userId = Redis::get($key);

        if (!$userId) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Token không hợp lệ hoặc đã hết hạn!',
            ], 401);
        }

        $user = NguoidungModel::find($userId);

        return $this->jsonResponse([
            'success' => true,
            'user' => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/dang-xuat",
     *     tags={"Xác thực người dùng (Auth)"},
     *     summary="Đăng xuất người dùng",
     *     description="Xóa token khỏi Redis. Cần Authorization: Bearer {token}",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Đăng xuất thành công"),
     *     @OA\Response(response=401, description="Token không hợp lệ hoặc đã hết hạn")
     * )
     */
    public function logout(Request $req)
    {
        $token = $req->bearerToken();
        $key = "api_token:$token";
        Redis::del($key);

        return $this->jsonResponse([
            'success' => true,
            'message' => "Đăng Xuất Thành Công"
        ]);
    }
}

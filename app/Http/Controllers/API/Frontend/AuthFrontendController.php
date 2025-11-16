<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Resources\Toi\ThongTinNguoiDungResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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



    protected $uploadDir = "assets/client/images/thumbs";// thư mục lưu file, relative so với public
    protected $uploadDirBaoMat = "assets/client/images/profiles"; // thư mục lưu file, relative so với storage/app/public
    protected $domain;
    protected $provinces;

    public function __construct()
    {
        $this->provinces = config('tinhthanh');
    }
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
     *     description="Tạo tài khoản mới bằng tên, username, số điện thoại và mật khẩu. Và tự động gửi thông báo nhắc người dùng cập nhật thông tin người dùng",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hoten","username","password","password_confirmation"},
     *             @OA\Property(property="hoten", type="string", example="Nguyễn Văn Duy"),
     *             @OA\Property(property="username", type="string", example="duy123"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="password_confirmation", type="string", example="123456"),
     *             @OA\Property(property="sodienthoai", type="string",description="chỉ gồm 10 chữ số" , example="1234567890" ),
     *             @OA\Property(property="ngaysinh", type="date", description="mặc định 2000-01-01" , example="null" ),
     *             @OA\Property(property="vaitro", type="string", description="mặc định client" , example="null" ),
     *             @OA\Property(property="gioitinh", type="string", description="mặc định Nam" , example="null" ),
     *             @OA\Property(property="avatar", type="string", description="mặc định domain-ip/assets/client/images/thumbs/khachhang.jpg" , example="null" ),
     *             @OA\Property(property="trangthai", type="string", description="mặc định Hoạt động" , example="null" ),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Đăng ký thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ hoặc username đã tồn tại")
     * )
     */
    public function register(Request $req)
    {
        $req->validate([
            'hoten' => 'required|string',
            'username' => 'required|string|unique:nguoidung,username',
            'password' => 'required|string|confirmed',
            'sodienthoai' => 'nullable|string|unique:nguoidung,sodienthoai|max:10',
        ]);
        $link_hinh_anh = $this->domain . $this->uploadDir . '/';
        $user = NguoidungModel::create([
            'hoten' => $req->hoten,
            'username' => $req->username,
            'password' => bcrypt($req->password),
            'sodienthoai'  => $req->sodienthoai,
            'ngaysinh' => '2000-01-01', // mặc định ngày sinh cho người dùng mới
            'vaitro' => 'client',
            'gioitinh' => 'Nam',
            'avatar' => $link_hinh_anh.'khachhang.jpg',
            'trangthai' => 'Hoạt động',
        ]);

            // Tạo thông báo cho user mới
            ThongbaoModel::create([
                'id_nguoidung' => $user->id,
                'tieude' => 'Cập nhật thông tin cá nhân',
                'noidung' => 'Bạn vui lòng cập nhật thông tin cá nhân để hoàn thiện hồ sơ.',
                'lienket' => null, // hoặc link đến trang cập nhật profile nếu có
                'trangthai' => 'Chưa đọc',
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
     *     summary="Lấy thông tin người dùng hiện tại + địa chỉ người dùng, lưu ý người dùng mới đăng ký nhắc người dùng cập nhật địa chỉ và 1 số thông tin đang set mặc định ",
     *     description="Yêu cầu header Authorization: Bearer {token}",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Trả về thông tin người dùng và địa chỉ người dùng (địa chỉ giao hàng)"),
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

        $user = NguoidungModel::with('diachi')->find($userId);

        return $this->jsonResponse([
            'success' => true,
            'user' => new ThongTinNguoiDungResource($user),
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/auth/cap-nhat-thong-tin",
     *     tags={"Xác thực người dùng (Auth)"},
     *     summary="Cập nhật thông tin người dùng hiện tại",
     *     description="Cập nhật thông tin cá nhân, avatar và địa chỉ giao hàng mặc định. Yêu cầu header Authorization: Bearer {token}",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="hoten", type="string", example="Nguyễn Văn A", description="Họ và tên"),
     *                 @OA\Property(property="sodienthoai", type="string", example="0987654321", description="Số điện thoại liên hệ"),
     *                 @OA\Property(property="ngaysinh", type="string", format="date", example="1990-01-01", description="Ngày sinh"),
     *                 @OA\Property(property="gioitinh", type="string", enum={"Nam","Nữ"}, example="Nam", description="Giới tính"),
     *                 @OA\Property(property="avatar", type="string", format="binary", description="Ảnh đại diện (file hình ảnh)"),
     *                 @OA\Property(property="diachi", type="string", example="123 Đường ABC, Quận XYZ", description="Địa chỉ giao hàng"),
     *                 @OA\Property(property="tinhthanh", type="string", example="Hà Nội", description="Tỉnh thành"),
     *                 @OA\Property(property="trangthai_diachi", type="string", enum={"Mặc định","Khác","Tạm ẩn"}, example="Mặc định", description="Trạng thái địa chỉ giao hàng")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thông tin thành công"),
     *     @OA\Response(response=401, description="Token không hợp lệ hoặc đã hết hạn"),
     *     @OA\Response(response=404, description="Người dùng không tồn tại"),
     *     @OA\Response(response=422, description="Dữ liệu đầu vào không hợp lệ")
     * )
     */
    public function updateProfile(Request $req)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
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
        if (!$user) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Người dùng không tồn tại!',
            ], 404);
        }

        // Validate input
        $req->validate([
            'hoten' => 'required|string',
            'sodienthoai' => 'required|string|max:10|unique:nguoidung,sodienthoai,' . $user->id,
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required|in:Nam,Nữ',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'diachi' => 'required|string',
            'tinhthanh' => ['required', 'string', Rule::in($provinceNames)],
            'trangthai_diachi' => 'required|in:Mặc định,Khác,Tạm ẩn',
        ]);

        DB::beginTransaction(); // ================= BEGIN TRANSACTION =================

        try {

            $link_hinh_anh = $this->domain . 'storage/' . $this->uploadDir . '/';

            $userData = $req->only(['hoten', 'sodienthoai', 'ngaysinh', 'gioitinh']);

            // Avatar
            if ($req->hasFile('avatar')) {
                $file = $req->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs($this->uploadDir, $filename, 'public');
                $userData['avatar'] = $link_hinh_anh . $filename;
            } else {
                $userData['avatar'] = $link_hinh_anh . 'khachhang.jpg';
            }

            // Update user info
            $user->update($userData);

            // === Địa chỉ giao hàng ===
            $diachiGiaohang = $user->diachi()->where('trangthai', 'Mặc định')->first();

            $diachiData = [
                'hoten' => $req->hoten,
                'sodienthoai' => $req->sodienthoai,
                'diachi' => $req->diachi,
                'tinhthanh' => $req->tinhthanh,
                'trangthai' => $req->trangthai_diachi,
            ];

            if ($diachiGiaohang) {
                $diachiGiaohang->update($diachiData);
            } else {
                $diachiData['id_nguoidung'] = $user->id;
                $newAddress = $user->diachi()->create($diachiData);

                // Nếu tạo mới mà được đánh dấu mặc định -> đặt $diachiGiaohang là record mới
                if ($req->trangthai_diachi === 'Mặc định') {
                    $diachiGiaohang = $newAddress;
                }
            }

            // Reset địa chỉ khác
            if ($req->trangthai_diachi === 'Mặc định' && $diachiGiaohang) {
                $user->diachi()
                    ->where('id', '!=', $diachiGiaohang->id)
                    ->update(['trangthai' => 'Khác']);
            }

            DB::commit(); // ================= COMMIT =================

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công',
                'user' => new ThongTinNguoiDungResource($user->fresh()->load('diachi')),
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // ================ ROLLBACK =================

            return $this->jsonResponse([
                'success' => false,
                'message' => 'Lỗi khi cập nhật dữ liệu!',
                'error' => $e->getMessage(), // Tạm bật debug cho frontend xem
            ], 500);
        }
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

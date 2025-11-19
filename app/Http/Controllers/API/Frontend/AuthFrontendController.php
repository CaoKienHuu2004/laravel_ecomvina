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
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
        $this->provinces = config('tinhthanh');
    }
    /**
     * @OA\Post(
     *     path="/api/auth/dang-nhap",
     *     tags={"Xác thực người dùng (Auth)"},
     *     summary="Đăng nhập người dùng",
     *     description="Đăng nhập bằng username hoặc email cùng mật khẩu, trả về token phiên làm việc hợp lệ.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     required={"email","password"},
     *                     @OA\Property(property="email", type="string", format="email", example="user@example.com", description="Email đăng nhập"),
     *                     @OA\Property(property="password", type="string", example="123456", description="Mật khẩu (chỉ chữ, số, dấu _ tối đa 15 ký tự)")
     *                 ),
     *                 @OA\Schema(
     *                     required={"username","password"},
     *                     @OA\Property(property="username", type="string", example="khacduy", description="Tên đăng nhập (chỉ chữ, số, dấu _ tối đa 15 ký tự)"),
     *                     @OA\Property(property="password", type="string", example="123456", description="Mật khẩu (chỉ chữ, số, dấu _ tối đa 15 ký tự)")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đăng nhập thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="random_generated_token_string"),
     *             @OA\Property(property="message", type="string", example="Đăng Nhập Thành Công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tên đăng nhập hoặc mật khẩu không chính xác",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tên đăng nhập hoặc mật khẩu không chính xác 😓")
     *         )
     *     )
     * )
     */
    public function login(Request $req)
    {
        // Nếu gửi email → validate theo email
        if ($req->has('email')) {
            $req->validate([
                'email' => 'required|email',
                'password'    => 'required|string|max:15|min:6|confirmed|regex:/^[A-Za-z0-9_]+$/',
            ]);

            $input = $req->email;

            // tìm theo email (phần sau dấu phẩy)
            $user = NguoidungModel::whereRaw(
                "SUBSTRING_INDEX(username, ',', -1) = ?",
                [$input]
            )->first();

        }
        // Nếu gửi username → validate theo username
        else {
            $req->validate([
                'username' => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^[A-Za-z0-9_]+$/',   // chỉ cho chữ, số và dấu _
                ],
                'password'    => 'required|string|max:15|min:6|confirmed|regex:/^[A-Za-z0-9_]+$/',
            ]);

            $input = $req->username;

            // tìm theo username (phần trước dấu phẩy)
            $user = NguoidungModel::whereRaw(
                "SUBSTRING_INDEX(username, ',', 1) = ?",
                [$input]
            )->first();
        }

        // Kiểm tra user + mật khẩu
        if (!$user || !Hash::check($req->password, $user->password)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Tên đăng nhập hoặc mật khẩu không chính xác 😓"
            ], 401);
        }

        // Tạo token
        $token = Str::random(60);
        Redis::setex("api_token:$token", 86400, $user->id);

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
     *     description="Tạo tài khoản mới với họ tên, username, email, số điện thoại, mật khẩu. Tự động tạo thông báo nhắc người dùng cập nhật thông tin cá nhân.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"hoten","username","email","sodienthoai","password","password_confirmation"},
     *             @OA\Property(property="hoten", type="string", example="Nguyễn Văn Duy", description="Họ và tên đầy đủ, 30 ký tự, chỉ gồm chữ và dấu cách"),
     *             @OA\Property(property="username", type="string", example="duy123", description="Tên đăng nhập, chỉ gồm chữ, số và dấu gạch dưới, tối đa 15 ký tự"),
     *             @OA\Property(property="email", type="string", format="email", example="duy123@gmail.com", description="Địa chỉ email hợp lệ"),
     *             @OA\Property(property="password", type="string", format="password", example="123456", description="Mật khẩu, chỉ gồm chữ, số và dấu gạch dưới, tối đa 15 ký tự"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="123456", description="Xác nhận mật khẩu phải giống trường password"),
     *             @OA\Property(property="sodienthoai", type="string", maxLength=10, example="1234567890", description="Số điện thoại, tối đa 10 chữ số, có thể bỏ trống")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đăng ký thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="random_generated_token_string"),
     *             @OA\Property(property="message", type="string", example="Đăng Ký Thành Công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ hoặc username/email đã tồn tại",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Username đã tồn tại")
     *         )
     *     )
     * )
     */
    public function register(Request $req)
    {
        // Validate trước
        $req->validate([
            'hoten' => 'required|string|max:30|regex:/^[\pL\s]+$/u',
            'username' => 'required|string|max:15|regex:/^[A-Za-z0-9_]+$/',
            'email' => 'required|string|email',
            'password'    => 'required|string|max:15|min:6|confirmed|regex:/^[A-Za-z0-9_]+$/',
            'sodienthoai' => 'nullable|string|unique:nguoidung,sodienthoai|max:10',
        ]);

        $onlyUsername = $req->username;
        $onlyEmail    = $req->email;

        $existsUsername = DB::table('nguoidung')
            ->whereRaw("SUBSTRING_INDEX(username, ',', 1) = ?", [$onlyUsername])
            ->exists();

        if ($existsUsername) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Username đã tồn tại',
            ], 422);
        }

        $existsEmail = DB::table('nguoidung')
            ->whereRaw("SUBSTRING_INDEX(username, ',', -1) = ?", [$onlyEmail])
            ->exists();

        if ($existsEmail) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Email đã tồn tại',
            ], 422);
        }

        // Lưu username dạng username,email
        $fullUsername = $onlyUsername . ',' . $onlyEmail;

        $link_hinh_anh = $this->domain . $this->uploadDir . '/';

        $user = NguoidungModel::create([
            'hoten' => $req->hoten,
            'username' => $fullUsername,
            'password' => bcrypt($req->password),
            'sodienthoai' => $req->sodienthoai,
            'avatar' => $link_hinh_anh . 'khachhang.jpg',
            'ngaysinh' => '2000-01-01',
            'vaitro' => 'client',
            'gioitinh' => 'Nam',
            'trangthai' => 'Hoạt động',
        ]);

        // Tạo thông báo
        ThongbaoModel::create([
            'id_nguoidung' => $user->id,
            'tieude' => 'Cập nhật thông tin cá nhân',
            'noidung' => 'Bạn vui lòng cập nhật thông tin cá nhân để hoàn thiện hồ sơ.',
            'lienket' => null,
            'trangthai' => 'Chưa đọc',
        ]);

        // Token
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
     *     summary="Lấy thông tin người dùng hiện tại và địa chỉ giao hàng của người dùng",
     *     description="Trả về thông tin chi tiết người dùng bao gồm username, email (được tách ra từ trường username theo định dạng 'username,email'), số điện thoại, họ tên, giới tính, ngày sinh, avatar, vai trò, trạng thái tài khoản, cùng danh sách các địa chỉ giao hàng.
     *                  Yêu cầu header Authorization: Bearer {token}",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin người dùng trả về thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="username", type="string", example="username123"),
     *                 @OA\Property(property="email", type="string", example="email@example.com"),
     *                 @OA\Property(property="sodienthoai", type="string", example="0987654321"),
     *                 @OA\Property(property="hoten", type="string", example="Nguyễn Văn A"),
     *                 @OA\Property(property="gioitinh", type="string", enum={"Nam","Nữ"}, example="Nam"),
     *                 @OA\Property(property="ngaysinh", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="avatar", type="string", example="https://domain.com/storage/path/avatar.jpg"),
     *                 @OA\Property(property="vaitro", type="string", example="admin"),
     *                 @OA\Property(property="trangthai", type="string", example="active"),
     *                 @OA\Property(
     *                     property="diachi",
     *                     type="array",
     *                     description="Danh sách địa chỉ giao hàng",
     *                     @OA\Items(ref="#/components/schemas/DiachiGiaohangResource")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token không hợp lệ hoặc đã hết hạn",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token không hợp lệ hoặc đã hết hạn!")
     *         )
     *     )
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
     *     description="Cập nhật thông tin cá nhân, avatar và (nếu cung cấp đầy đủ 3 trường) cập nhật hoặc thêm địa chỉ giao hàng mặc định.
     *                  Email được cập nhật nằm trong trường 'username' theo định dạng 'username,email' (phần email nằm sau dấu phẩy).
     *                  Yêu cầu header Authorization: Bearer {token}",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="hoten",
     *                     type="string",
     *                     example="Nguyễn Văn A",
     *                     description="Họ và tên"
     *                 ),
     *                 @OA\Property(
     *                     property="sodienthoai",
     *                     type="string",
     *                     example="0987654321",
     *                     description="Số điện thoại liên hệ"
     *                 ),
     *                 @OA\Property(
     *                     property="ngaysinh",
     *                     type="string",
     *                     format="date",
     *                     example="1990-01-01",
     *                     description="Ngày sinh"
     *                 ),
     *                 @OA\Property(
     *                     property="gioitinh",
     *                     type="string",
     *                     enum={"Nam","Nữ"},
     *                     example="Nam",
     *                     description="Giới tính"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string",
     *                     format="binary",
     *                     description="Ảnh đại diện (file hình ảnh)"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     nullable=true,
     *                     example="email@example.com",
     *                     description="Email sẽ được cập nhật bên trong trường 'username' theo định dạng 'username,email', và ko bắt buộc phải gửi"
     *                 ),
     *                 @OA\Property(
     *                     property="diachi",
     *                     type="string",
     *                     nullable=true,
     *                     example="123 Đường ABC, Quận XYZ",
     *                     description="Địa chỉ giao hàng (không bắt buộc — chỉ xử lý nếu cung cấp đầy đủ 3 trường địa chỉ)"
     *                 ),
     *                 @OA\Property(
     *                     property="tinhthanh",
     *                     type="string",
     *                     nullable=true,
     *                     example="Thành Phố Hà Nội",
     *                     description="Tỉnh thành (không bắt buộc — phải hợp lệ nếu được gửi)"
     *                 ),
     *                 @OA\Property(
     *                     property="trangthai_diachi",
     *                     type="string",
     *                     nullable=true,
     *                     enum={"Mặc định","Khác","Tạm ẩn"},
     *                     example="Mặc định",
     *                     description="Trạng thái địa chỉ (không bắt buộc — chỉ áp dụng khi đủ 3 trường địa chỉ)"
     *                 )
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
            'email' => 'sometimes|email', // là 1 phần tử[1] của username khi bị explode
            'hoten' => 'required|string|max:30|regex:/^[\pL\s]+$/u',
            'sodienthoai' => 'required|string|max:10',
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required|in:Nam,Nữ',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Chuyển lại logic edit profile không bắt buộc nhập trường của địa chỉ
            // 'diachi' => 'required|string',
            // 'tinhthanh' => ['required', 'string', Rule::in($provinceNames)],
            // 'trangthai_diachi' => 'required|in:Mặc định,Khác,Tạm ẩn',
            'diachi' => 'nullable|string',
            'tinhthanh' => ['nullable', 'string', Rule::in($provinceNames)],
            'trangthai_diachi' => 'nullable|in:Mặc định,Khác,Tạm ẩn',
        ]);

        DB::beginTransaction(); // ================= BEGIN TRANSACTION =================

        try {

            $link_hinh_anh = $this->domain . 'storage/' . $this->uploadDirBaoMat . '/';

            $userData = $req->only(['hoten', 'sodienthoai', 'ngaysinh', 'gioitinh']);
            if ($req->has('email')) {
                $oldUsernameEmail = $user->username; // dạng: "username,email"
                $arrayFiledUsername = explode(',', $oldUsernameEmail);
                $oldUsername = $arrayFiledUsername[0];
                if (!isset($arrayFiledUsername[1])) {
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => 'Tài khoản chưa có email, không thể cập nhật email mới',
                    ], 422);
                }
                $newEmail = $req->input('email', $arrayFiledUsername[1]);
                $userData['username'] = $oldUsername . ',' . $newEmail;
            }

            // var_dump($userData);
            // exit;


            // Avatar
            if ($req->hasFile('avatar')) {
                $file = $req->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs($this->uploadDirBaoMat, $filename, 'public');
                $userData['avatar'] = $link_hinh_anh . $filename;
            }


            // Update user info
            // $result = $user->update($userData); // để debug
            $user->update($userData);

            // === XỬ LÝ ĐỊA CHỈ GIAO HÀNG ===
            // Begin===CHỈ xử lý khi người dùng gửi đầy đủ cả 3 trường, logic bây h` có thì insert diachi_giaohang ko thì bỏ qua===
            $hasAddressInput = $req->filled('diachi')
                                && $req->filled('tinhthanh')
                                && $req->filled('trangthai_diachi');

            if ($hasAddressInput) {
                $diachiGiaohang = $user->diachi()->where('trangthai', 'Mặc định')->first();

                $diachiData = [
                    'hoten'       => $req->hoten,
                    'sodienthoai' => $req->sodienthoai,
                    'diachi'      => $req->diachi,
                    'tinhthanh'   => $req->tinhthanh,
                    'trangthai'   => $req->trangthai_diachi,
                ];

                if ($diachiGiaohang) {
                    $diachiGiaohang->update($diachiData);
                } else {
                    // Tạo mới
                    $diachiData['id_nguoidung'] = $user->id;
                    $newAddress = $user->diachi()->create($diachiData);

                    if ($req->trangthai_diachi === 'Mặc định') {
                        $diachiGiaohang = $newAddress;
                    }
                }

                if ($req->trangthai_diachi === 'Mặc định' && $diachiGiaohang) {
                    $user->diachi()
                        ->where('id', '!=', $diachiGiaohang->id)
                        ->update(['trangthai' => 'Khác']);
                }
            }
            // End===CHỈ xử lý khi người dùng gửi đầy đủ cả 3 trường, logic bây h` có thì insert diachi_giaohang ko thì bỏ qua===


            // $result4 = DB::commit(); // để debug
            DB::commit(); // ================= COMMIT =================

            //tới đây đúng hết rồi chỉ là cái trả res nó sida á ^^
            // return $this->jsonResponse([
            //     'success' => false,
            //     'message' => 'Lỗi khi cập nhật dữ liệu!',
            //     'error' => [$userData,$filename,$result, $diachiGiaohang,$diachiData,$result2,$result3,$result4], // Tạm bật debug cho frontend xem
            // ], 500); //để debug

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

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toi\ThongTinNguoiDungResource;
use App\Models\GiohangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AuthWebController extends Controller
{
    use ApiResponse;

    protected $uploadDir = "assets/client/images/thumbs";// thư mục lưu file, relative so với public
    protected $uploadDirBaoMat = "assets/client/images/profiles"; // thư mục lưu file, relative so với storage/app/public
    protected $domain;
    protected $provinces;

    protected $cart_session;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
        $this->provinces = config('tinhthanh');

        $this->cart_session = config('cart_session.session_key_cart', 'cart_session');
    }

    public function login(Request $req)
    {
        if (!$req->has('email') && !$req->has('username')) {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Bạn phải nhập email hoặc username!"
            ], 422);
        }
        // Nếu gửi email → validate theo email
        if ($req->has('email')) {
            $req->validate([
                'email' => [
                    'required',
                    'string',
                    'email:rfc,dns,filter',   // kiểm tra format + DNS MX
                    'max:50',
                    'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',   // không khoảng trắng + phải có domain
                ],
                'password'    => 'required|string|max:20|min:6|regex:/^[A-Za-z0-9_]+$/',
            ]);

            $input = $req->email;
            $user = NguoidungModel::where('email', $input)->first();

        }
        // Nếu gửi username → validate theo username
        else {
            $usernameInput = $req->username;
            $isEmail = filter_var($usernameInput, FILTER_VALIDATE_EMAIL);
            if ($isEmail) {
                $req->validate([
                    'username' => [
                        'required',
                        'string',
                        'email:rfc,dns,filter',
                        'max:50',
                        'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
                    ],
                    'password' => 'required|string|max:20|min:6|regex:/^[A-Za-z0-9_]+$/',
                ]);
                $user = NguoidungModel::where('email', $usernameInput)->first();
            }
            else {
                $req->validate([
                    'username' => [
                        'required',
                        'string',
                        'min:6',
                        'max:20',
                        'regex:/^[A-Za-z0-9_]+$/',
                    ],
                    'password' => 'required|string|max:20|min:6|regex:/^[A-Za-z0-9_]+$/',
                ]);
                $user = NguoidungModel::where('username', $usernameInput)->first();
            }
        }

        // Kiểm tra user + mật khẩu
        if (!$user || !Hash::check($req->password, $user->password)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Tên đăng nhập hoặc mật khẩu không chính xác 😓"
            ], 401);
        }

        // ko khi xác thực đăng nhập thành công nếu cart_session có sp thì merge vào giỏ hàng của user
        // var_dump($sessionCart = session($this->cart_session, [])); // do lên https có cả www và ko www mới bị
        // exit;
        $this->merge_cart_from_session_after_login($user->id);
        // trả về void á nên khá khó debug

        // Tạo token
        $token = Str::random(60);
        Redis::setex("api_token:$token", 86400, $user->id);


        return $this->jsonResponse([
            'success' => true,
            'token' => $token,
            'message' => "Đăng Nhập Thành Công"
        ]);
    }



    public function register(Request $req)
    {

        // Validate trước
        try {
            $req->validate([
                'hoten' => 'required|string|min:1|max:30|regex:/^[\pL\s]+$/u',
                'username' => 'required|string|min:6|max:20|regex:/^[A-Za-z0-9_]+$/|unique:nguoidung,username',
                'email' => [
                    'required',
                    'string',
                    'email:rfc,dns,filter',   // kiểm tra format + DNS MX
                    'max:50',
                    'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',   // không khoảng trắng + phải có domain
                    'unique:nguoidung,email'
                ],
                'password' => 'required|string|max:20|min:6|confirmed|regex:/^[A-Za-z0-9_]+$/',
                'sodienthoai' => 'required|string|regex:/^[0-9]+$/|max:10|unique:nguoidung,sodienthoai',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return $this->jsonResponse([
                'error' => true,
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        }

        $link_hinh_anh = $this->domain . $this->uploadDir . '/';

        $user = NguoidungModel::create([
            'hoten' => $req->hoten,
            'username' => $req->username,
            'email' => $req->email,
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




    public function profile(Request $req)
    {
        $token = $req->bearerToken();
        $key = "api_token:$token";
        $userId = Redis::get($key);
        // midleware auth đã check token rồi, nhưng vẫn check lại cho chắc

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



    public function updateProfile(Request $req)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        $token = $req->bearerToken();
        $key = "api_token:$token";
        $userId = Redis::get($key);
        // midleware auth đã check token rồi, nhưng vẫn check lại cho chắc

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
        try {
            $req->validate([
                'email' => [
                    'sometimes',
                    'string',
                    'email:rfc,dns,filter',   // kiểm tra format + DNS MX
                    'max:50',
                    'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',   // không khoảng trắng + phải có domain
                    'unique:nguoidung,email,' . $userId,
                ],
                'hoten' => 'required|string|min:1|max:30|regex:/^[\pL\s]+$/u',
                'sodienthoai' => [
                    'required',
                    'string',
                    'max:10',
                    'unique:nguoidung,sodienthoai,' . $userId,
                    'regex:/^[0-9]+$/',
                ],
                'ngaysinh' => 'required|date',
                'gioitinh' => 'required|in:Nam,Nữ',
                'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                // Chuyển lại logic edit profile không bắt buộc nhập trường của địa chỉ
                // 'diachi' => 'required|string',
                // 'tinhthanh' => ['required', 'string', Rule::in($provinceNames)],
                // 'trangthai_diachi' => 'required|in:Mặc định,Khác,Tạm ẩn',
                'diachi' => 'nullable|string',
                'tinhthanh' => ['nullable', 'string', Rule::in($provinceNames)],
                'trangthai_diachi' => 'nullable|in:Mặc định,Khác,Tạm ẩn',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return $this->jsonResponse([
                'error' => true,
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        }



        DB::beginTransaction(); // ================= BEGIN TRANSACTION =================

        try {

            $link_hinh_anh = $this->domain . 'storage/' . $this->uploadDirBaoMat . '/';

            $userData = $req->only(['hoten', 'sodienthoai', 'ngaysinh', 'gioitinh']);
            if ($req->has('email')) {
                $userData['email'] = $req->email;
            }

            // var_dump($userData);
            // exit;


            // Avatar
            if ($req->hasFile('avatar')) {
                // ---- Xóa ảnh cũ nếu không phải ảnh mặc định ----
                if($user->avatar)
                {
                    $partAvatarOriginUser = parse_url($user->avatar, PHP_URL_PATH);
                    $defaultAvatars = [
                        '/' . $this->uploadDir . '/khachhang.jpg',
                        '/' . $this->uploadDir . '/khachhang.png'
                    ];
                    if(!in_array($partAvatarOriginUser, $defaultAvatars))
                    {
                        $relativePath = ltrim(str_replace('/storage/', '', $partAvatarOriginUser), '/');
                        $filePath = storage_path('app/public/' . $relativePath);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
                // ---- updaload file ----
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

    public function updatePassword(Request $req)
    {
        $token = $req->bearerToken();
        $key = "api_token:$token";
        $userId = Redis::get($key);
        //midleware check rồi check lại cho chắc

        if (!$userId) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Token không hợp lệ hoặc đã hết hạn!',
            ], 401);
        }

        // Validate dữ liệu đầu vào
        try {
            $req->validate([
                'current_password' => ['required', 'string', 'min:6', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
                'new_password' => ['required', 'string', 'min:6', 'max:20', 'confirmed', 'regex:/^[A-Za-z0-9_]+$/'],
                // new_password_confirmation sẽ được tự động validate bởi 'confirmed'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = NguoidungModel::find($userId);
        if (!$user) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Người dùng không tồn tại!',
            ], 404);
        }

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($req->current_password, $user->password)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Mật khẩu cũ không đúng!',
            ], 400);
        }

        // Cập nhật mật khẩu mới
        $user->password = bcrypt($req->new_password);
        $user->save();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Cập nhật mật khẩu thành công',
        ]);
    }


    public function logout(Request $req)
    {
        $token = $req->bearerToken();
        $key = "api_token:$token";
        Redis::del($key);
        // midleware auth đã check token rồi, nhưng vẫn check lại cho chắc

        //
        //4. Xử lý khi đăng xuất Thường bạn không cần xóa giỏ hàng trên DB khi đăng xuất. Nhưng nếu bạn muốn, có thể xóa session giỏ hàng để tránh nhầm lẫn. Giỏ hàng user lưu trên DB nên giữ nguyên để lần đăng nhập sau lấy lại.
        session()->forget($this->cart_session);

        return $this->jsonResponse([
            'success' => true,
            'message' => "Đăng Xuất Thành Công"
        ]);
    }


    /**
     * Gộp giỏ hàng từ session vào giỏ hàng trong cơ sở dữ liệu sau khi người dùng đăng nhập.
     *
     * Hàm này thực hiện việc chuyển các sản phẩm từ giỏ hàng của khách (lưu trong session)
     * vào giỏ hàng của người dùng đã đăng nhập (lưu trong cơ sở dữ liệu).
     * - Nếu sản phẩm trong session đã tồn tại trong giỏ hàng của người dùng, số lượng sẽ được cộng dồn.
     * - Nếu sản phẩm chưa có, nó sẽ được thêm mới vào giỏ hàng của người dùng.
     * Sau khi gộp thành công, giỏ hàng trong session sẽ bị xóa.
     *
     * @param int $userId ID của người dùng vừa đăng nhập.
     * @return void
     */
    private function merge_cart_from_session_after_login($userId)
    {
        // Lấy session cart (giỏ hàng chưa đăng nhập)
        $sessionCart = session($this->cart_session, []);
        // ko tự động lấy được laravel_session và XSRF-TOKEN phải gửi kièm trong header


        if (empty($sessionCart)) {
            return; // Không có gì để merge
        }


        // Lấy giỏ hàng DB hiện tại của user
        $dbCartItems = GiohangModel::where('id_nguoidung', $userId)
            ->where('trangthai', 'Hiển thị')
            ->get()
            ->keyBy('id_bienthe');

        // Duyệt session cart, add/update vào DB
        foreach ($sessionCart as $sessionItem) {
            $id_bienthe = $sessionItem['id_bienthe'];
            $soluong = $sessionItem['soluong'];
            $thanhtien = $sessionItem['thanhtien'];

            if (isset($dbCartItems[$id_bienthe])) {
                // Cộng dồn số lượng sản phẩm
                $dbItem = $dbCartItems[$id_bienthe];
                $dbItem->soluong += $soluong;
                $dbItem->thanhtien += $thanhtien; // Hoặc tính lại nếu cần
                $dbItem->save();
            } else {
                // Tạo mới bản ghi giỏ hàng
                GiohangModel::create([
                    'id_nguoidung' => $userId,
                    'id_bienthe' => $id_bienthe,
                    'soluong' => $soluong,
                    'thanhtien' => $thanhtien,
                    'trangthai' => 'Hiển thị',
                ]);
            }
        }

        // Xóa session giỏ hàng đi (đã nhập vào DB rồi)
        session()->forget($this->cart_session);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\NguoiDungAuthResource;
use App\Models\Nguoidung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Config;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    protected $createNewUser;
    protected $updateProfile;
    protected $updatePassword;
    protected $resetPassword;

    public function __construct(
        CreateNewUser $createNewUser,
        UpdateUserProfileInformation $updateProfile,
        UpdateUserPassword $updatePassword,
        ResetUserPassword $resetPassword
    ) {
        $this->createNewUser   = $createNewUser;
        $this->updateProfile   = $updateProfile;
        $this->updatePassword  = $updatePassword;
        $this->resetPassword   = $resetPassword;
    }

    /**
     * Hàm helper để set mailer theo request
     * POST /api/auth/quen-mat-khau?mailer=smtp
     * POST /api/auth/doi-mat-khau?mailer=mailgun
     */
    private function setMailer(Request $request)
    {
        $mailer = $request->query('mailer', config('mail.default'));
        Config::set('mail.default', $mailer);
    }

    public function register(Request $request)
    {
        $user = $this->createNewUser->create($request->all());
        $token = $user->createToken('auth_token')->plainTextToken;

        // Nếu bạn muốn gửi email chào mừng
        // $this->setMailer($request);
        // Mail::to($user->email)->send(new WelcomeMail($user));

        return $this->jsonResponse([
            'message'      => 'Chào mừng ' . $user->hoten . '! Bạn đã đăng ký thành công.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new NguoiDungAuthResource($user),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Tìm user theo email
        $user = Nguoidung::where('email', $request->email)->first();

        // Nếu không tồn tại user hoặc password sai


        //--------------------- nếu muốn dùng dạng session + cookie của Sanctum, mà bạn đang dùng Sanctum Personal Access Token (tức kiểu API token).
        // $credentials = $request->only('email', 'password');
        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();
        //     return response()->json(['message' => 'Đăng nhập thành công']);
        // }
        // return response()->json(['message' => 'Sai email hoặc mật khẩu'], 401);
         //---------------------


        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->jsonResponse(['message' => 'Unauthorized'], 401);
        }

        // Tạo token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Trả JSON
        return $this->jsonResponse([
            'message'      => 'Chào ' . $user->hoten . '! Chúc bạn an lành!',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new NguoiDungAuthResource($user),
        ]);
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     $user  = Nguoidung::where('email', $request->email)->firstOrFail();
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return $this->jsonResponse([
    //         'message'      => 'Chào ' . $user->hoten . '! Chúc bạn an lành!',
    //         'access_token' => $token,
    //         'token_type'   => 'Bearer',
    //         'user'         => new NguoiDungAuthResource($user),
    //     ]);
    // }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->jsonResponse([
            'message' => 'Bạn đã thoát ứng dụng và token đã xóa'
        ]);
    }

    public function userInfo(Request $req)
    {
        $name = $req->user()->hoten;
        return $this->jsonResponse([
            'success' => true,

            'message' => 'Thông tin Của '.$name.'🤩',
            'user' => $req->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $this->updateProfile->update($user, $request->all());

        return $this->jsonResponse([
            'message' => 'Thông tin đã được cập nhật',
            'user'    => new NguoiDungAuthResource($user),
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        $this->updatePassword->update($user, $request->all());

        return $this->jsonResponse([
            'message' => 'Mật khẩu đã được thay đổi',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // chọn mailer động
        $this->setMailer($request);

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link khôi phục mật khẩu đã được gửi vào email!'
            ]);
        }

        return $this->jsonResponse([
            'message' => __($status)
        ], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // chọn mailer động
        $this->setMailer($request);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->resetPassword->reset($user, ['password' => $password]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->jsonResponse([
                'message' => 'Mật khẩu đã được đặt lại thành công'
            ]);
        }

        return $this->jsonResponse([
            'message' => __($status)
        ], 400);
    }
}

// namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
// use App\Http\Resources\NguoiDungAuthResource;
// use App\Models\Nguoidung;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Password;
// use App\Actions\Fortify\CreateNewUser;
// use App\Actions\Fortify\UpdateUserProfileInformation;
// use App\Actions\Fortify\UpdateUserPassword;
// use App\Actions\Fortify\ResetUserPassword;

// class AuthController extends Controller
// {
//     protected $createNewUser;
//     protected $updateProfile;
//     protected $updatePassword;
//     protected $resetPassword;

//     public function __construct(
//         CreateNewUser $createNewUser,
//         UpdateUserProfileInformation $updateProfile,
//         UpdateUserPassword $updatePassword,
//         ResetUserPassword $resetPassword
//     ) {
//         $this->createNewUser   = $createNewUser;
//         $this->updateProfile   = $updateProfile;
//         $this->updatePassword  = $updatePassword;
//         $this->resetPassword   = $resetPassword;
//     }

//     public function register(Request $request)
//     {
//         $user = $this->createNewUser->create($request->all());
//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'message'      => 'Chào mừng ' . $user->hoten . '! Bạn đã đăng ký thành công.',
//             'access_token' => $token,
//             'token_type'   => 'Bearer',
//             'user'         => new NguoiDungAuthResource($user),
//         ]);
//     }

//     public function login(Request $request)
//     {
//         $request->validate([
//             'email'    => 'required|email',
//             'password' => 'required'
//         ]);

//         if (!Auth::attempt($request->only('email', 'password'))) {
//             return response()->json(['message' => 'Unauthorized'], 401);
//         }

//         $user  = Nguoidung::where('email', $request->email)->firstOrFail();
//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'message'      => 'Chào ' . $user->hoten . '! Chúc bạn an lành!',
//             'access_token' => $token,
//             'token_type'   => 'Bearer',
//             'user'         => new NguoiDungAuthResource($user),
//         ]);
//     }

//     public function logout(Request $request)
//     {
//         $request->user()->tokens()->delete();

//         return response()->json([
//             'message' => 'Bạn đã thoát ứng dụng và token đã xóa'
//         ]);
//     }

//     public function updateProfile(Request $request)
//     {
//         $user = $request->user();
//         $this->updateProfile->update($user, $request->all());

//         return response()->json([
//             'message' => 'Thông tin đã được cập nhật',
//             'user'    => new NguoiDungAuthResource($user),
//         ]);
//     }

//     public function changePassword(Request $request)
//     {
//         $user = $request->user();
//         $this->updatePassword->update($user, $request->all());

//         return response()->json([
//             'message' => 'Mật khẩu đã được thay đổi',
//         ]);
//     }

//     public function forgotPassword(Request $request)
//     {
//         $request->validate(['email' => 'required|email']);

//         $status = Password::broker()->sendResetLink(
//             $request->only('email')
//         );

//         if ($status === Password::RESET_LINK_SENT) {
//             return response()->json([
//                 'message' => 'Link khôi phục mật khẩu đã được gửi vào email!'
//             ]);
//         }

//         return response()->json([
//             'message' => __($status)
//         ], 400);
//     }

//     public function resetPassword(Request $request)
//     {
//         $request->validate([
//             'email'    => 'required|email',
//             'token'    => 'required|string',
//             'password' => 'required|string|min:8|confirmed',
//         ]);

//         $status = Password::broker()->reset(
//             $request->only('email', 'password', 'password_confirmation', 'token'),
//             function ($user, $password) {

//                 $this->resetPassword->reset($user, ['password' => $password]); // Dùng Fortify Action để reset password
//             }
//         );

//         if ($status === Password::PASSWORD_RESET) {
//             return response()->json([
//                 'message' => 'Mật khẩu đã được đặt lại thành công'
//             ]);
//         }

//         return response()->json([
//             'message' => __($status)
//         ], 400);
//     }
// }

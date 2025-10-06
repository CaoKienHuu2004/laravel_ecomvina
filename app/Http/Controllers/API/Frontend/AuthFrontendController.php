<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthFrontendController extends BaseFrontendController
{
    //
    //
    public function login(Request $req)
    {

        $email = $req->email;
        $password = $req->password;
        if(!$email || !$password)
        {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Vui Lòng Nhập Đầy Đủ Email Và Mật Khẩu🤩",
            ], 400);
        }
        $status = Auth::attempt(['email' => $email, 'password' => $password]);
        if($status){
            $token = $req->user()->createToken('auth');
            // $token = $req->user()->createToken($req->token_name);

            return $this->jsonResponse([
                'success' =>true,
                'token' => $token->plainTextToken,
                'message' => "Đăng Nhập Thành Công"
            ]);
        }
        return $this->jsonResponse([
            'success' =>false,
            'message' => "Email hoặc mật khẩu không chính xác😓",
        ]);
    }

    public function profile(Request $req)
    {
        return $this->jsonResponse([
            'success' => true,
            'user' => $req->user()
        ]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return $this->jsonResponse([
            'success' => true,
            'message' => "Đăng Xuất Thành Công"
        ]);
    }

    public function register(Request $req)
    {
        $name = $req->name;
        $email = $req->email;
        $password = $req->password;
        $password_confirmation = $req->password_confirmation;

        if(!$name || !$email || !$password || !$password_confirmation)
        {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Vui Lòng Nhập Đầy Đủ Thông Tin🤩",
            ], 400);
        }
        if($password != $password_confirmation)
        {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Mật Khẩu Xác Nhận Không Khớp🤩",
            ], 400);
        }
        $checkEmail = \App\Models\Nguoidung::where('email', $email)->first();
        if($checkEmail)
        {
            return $this->jsonResponse([
                'success' => false,
                'message' => "Email Này Đã Được Sử Dụng🤩",
            ], 400);
        }

        $user = new \App\Models\Nguoidung();
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        //tự động đăng nhập sau khi đăng ký thành công
        Auth::attempt(['email' => $email, 'password' => $password]);
        $token = $req->user()->createToken('auth');

        return $this->jsonResponse([
            'success' => true,
            'token' => $token->plainTextToken,
            'message' => "Đăng Ký Thành Công"
        ]);
    }
}

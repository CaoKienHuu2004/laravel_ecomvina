<?php

namespace App\Http\Controllers;

use App\Models\NguoidungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\AssignOp\Mod;

class AdminController extends Controller
{

    // Trang chính admin
    public function dashboard()
    {
        $user = Auth::user(); // lấy thông tin user đang login

        // Kiểm tra role admin
        if ($user->vaitro !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập');
        }

        return view('trangchu', compact('user'));
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->vaitro === 'admin') {
                return redirect()->route('trang-chu');
            } else {
                Auth::logout();
                return redirect()->route('dang-nhap')->withErrors(['email' => 'Bạn không có quyền truy cập trang admin.']);
            }
        }

        return redirect()->route('dang-nhap')->withErrors(['email' => 'Thông tin đăng nhập không hợp lệ.']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dang-nhap');
    }
    public function profile()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }
    // Cập nhật thông tin tài khoản
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\NguoidungModel $user */
        $user = Auth::user();

        // hoten,username,sodienthoai,gioitinh,pawwword
        $request->validate([
            'hoten' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:nguoidung,username,' . $user->id,
            'sodienthoai' => 'nullable|string|max:20',
            'gioitinh' => 'nullable|in:Nam,Nữ',
            'password' => 'nullable|string|min:6|confirmed',
            'ngaysinh' => 'nullable|date',
        ]);

        $user->hoten = $request->input('hoten');
        $user->username = $request->input('username');
        $user->sodienthoai = $request->input('sodienthoai');
        $user->gioitinh = $request->input('gioitinh');
        $user->ngaysinh = $request->input('ngaysinh');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect()->route('thong-tin-tai-khoan')
                         ->with('success', 'Cập nhật thông tin thành công.');
    }

    // Cập nhật ảnh đại diện
    public function updateAvatar(Request $request)
    {
        /** @var \App\Models\NguoidungModel $user */
        $user = Auth::user();

        // chỉ id, avatar
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // // Xoá avatar cũ nếu có, cái này hơi mệt kia kết hợp với nextjs
            // if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            //     Storage::disk('public')->delete($user->avatar);
            // }
            // php artisan storage:link nhớ chạy lệnh này để tạo liên kết lưu trữ
            $avatarPath = $request->file('avatar')->store('nguoidung/avatar', 'uploads');
            $imgName = basename($avatarPath);
            // Cập nhật đường dẫn avatar mới
            $user->avatar = $imgName;
            $user->save();
        }

        return redirect()->route('thong-tin-tai-khoan')
                         ->with('success', 'Cập nhật ảnh đại diện thành công.');
    }
}


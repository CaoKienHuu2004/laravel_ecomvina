<?php

namespace App\Http\Controllers;

use App\Models\NguoidungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\AssignOp\Mod;

class AdminController extends Controller
{
    // php artisan storage:link nhớ dùng khi lưu ảnh vào storage/app/public
    protected $uploadDir = "assets/client/images/profiles"; // thư mục lưu file, relative so với public
    protected $domain;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
    }

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
    public function login(Request $req)
    {
        $req->validate([
            'password' => 'required|string|max:15|min:6|regex:/^[A-Za-z0-9_]+$/',
        ]);
        $query = null;
        if ($req->has('email')) {
            $req->validate([
                'email' => 'required|email',
            ]);

            $query = NguoidungModel::whereRaw(
                "SUBSTRING_INDEX(username, ',', -1) = ?",
                [$req->email]
            );
        }
        else {
            $req->validate([
                'username' => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^[A-Za-z0-9_]+$/',
                ]
            ]);
            $query = NguoidungModel::whereRaw(
                "SUBSTRING_INDEX(username, ',', 1) = ?",
                [$req->username]
            );
        }
        $user = $query->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
            ])->withInput();
        }

        if ($user->vaitro !== 'admin') {
            return back()->withErrors([
                'login' => 'Tài khoản này không có quyền truy cập admin.',
            ]);
        }
        Auth::login($user); //Đăng nhập session bình thường

        return redirect()->route('trang-chu')->with('success', 'Đăng nhập thành công!');
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
            'hoten' => 'required|string|max:30|regex:/^[\pL\s]+$/u',
            'email' => 'required|email',
            'username' => 'required|string|min:6|max:15|regex:/^[A-Za-z0-9_]+$/',
            'sodienthoai' => 'nullable|string|max:20',
            'gioitinh' => 'nullable|in:Nam,Nữ',
            'password' => 'nullable|string|max:15|min:6|confirmed|regex:/^[A-Za-z0-9_]+$/',
            'ngaysinh' => 'nullable|date',
        ]);

        $onlyUsername = $request->username;
        $onlyEmail    = $request->email;

        $existsUsername = DB::table('nguoidung')
            ->whereRaw("SUBSTRING_INDEX(username, ',', 1) = ?", [$onlyUsername])
            ->where('id', '!=', $user->id) // tránh check trùng chính user hiện tại
            ->exists();

        if ($existsUsername) {
            return back()->withErrors(['username' => 'Username đã tồn tại'])
                        ->withInput();
        }

        $existsEmail = DB::table('nguoidung')
            ->whereRaw("SUBSTRING_INDEX(username, ',', -1) = ?", [$onlyEmail])
            ->where('id', '!=', $user->id) // tránh check trùng chính user hiện tại
            ->exists();

        if ($existsEmail) {
            return back()->withErrors(['email' => 'Email đã tồn tại'])
                        ->withInput();
        }

        $fullUsername = $onlyUsername . ',' . $onlyEmail;

        $user->hoten = $request->input('hoten');
        $user->username = $fullUsername;
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

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $filename = $request->file('avatar')->getClientOriginalName();

            $avatarPath = $request->file('avatar')->storeAs($this->uploadDir, $filename, 'public');

            $imgName = $this->domain . 'storage/' . $this->uploadDir . '/' . $filename;

            $user->avatar = $imgName;
            $user->save();
        }

        return redirect()->route('thong-tin-tai-khoan')
                        ->with('success', 'Cập nhật ảnh đại diện thành công.');
    }
}


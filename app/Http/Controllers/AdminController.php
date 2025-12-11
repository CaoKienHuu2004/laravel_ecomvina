<?php

namespace App\Http\Controllers;

use App\Models\NguoidungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\AssignOp\Mod;

 // begin: Alias của Nguyên
use App\Models\DonhangModel;
use App\Models\ChitietdonhangModel;
use App\Models\BientheModel;
use Carbon\Carbon;
 // begin: Alias của Nguyên

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


    // begin: method của Nguyên
    public function trangchu()
    {
        // Tính tổng doanh thu tất cả thời gian
        $tongDoanhThu = ChitietdonhangModel::sum('dongia');
        // Tính tổng doanh thu hôm nay
        $tongDoanhThuNgay = ChitietdonhangModel::whereHas('donhang', function($query) {
            $query->whereDate('created_at', Carbon::today());
        })->sum('dongia');
        // Tính tổng doanh thu trong tuần
        $startOfWeek = Carbon::now()->startOfWeek(); // Ngày bắt đầu của tuần
        $endOfWeek = Carbon::now()->endOfWeek(); // Ngày kết thúc của tuần
        $tongDoanhThuTuan = ChitietdonhangModel::whereHas('donhang', function($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        })->sum('dongia');
        // Tính tổng doanh thu trong tháng
        $tongDoanhThuThang = ChitietdonhangModel::whereHas('donhang', function($query) {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        })->sum('dongia');
        $donHangsMoi = DonhangModel::orderBy('created_at', 'desc')->take(3)->get();
        $sanPhamHetHang = BientheModel::with('sanpham','sanpham.hinhanhsanpham')->where('soluong', '<=', 5)->take(2)->get();
        $sanPhamTonKho = BientheModel::with('sanpham','sanpham.hinhanhsanpham')->where('soluong', '>',100)->take(2) ->get();
        return view('trangchu', compact('tongDoanhThu', 'tongDoanhThuNgay', 'tongDoanhThuTuan', 'tongDoanhThuThang', 'donHangsMoi', 'sanPhamHetHang', 'sanPhamTonKho')); // Truyền tổng doanh thu vào view
    }
    // end: method của Nguyên

    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $req)
    {
        // Validate password dùng chung
        $req->validate([
            'password' => 'required|string|max:20|min:6|regex:/^[A-Za-z0-9_]+$/',
        ]);
        $user = null;
        // Trường hợp gửi field email
        if ($req->has('email')) {

            $req->validate([
                'email' => [
                    'required',
                    'string',
                    'email:rfc,dns,filter',
                    'max:50',
                    'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
                ],
            ]);

            $user = NguoidungModel::where('email', $req->email)->first();
        }

        // Trường hợp gửi username (có thể là email hoặc username thật)
        elseif ($req->has('username')) {
            $usernameInput = $req->username;
            $isEmail = filter_var($usernameInput, FILTER_VALIDATE_EMAIL);

            // Username là email
            if ($isEmail) {

                $req->validate([
                    'username' => [
                        'required',
                        'string',
                        'email:rfc,dns,filter',
                        'max:50',
                        'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
                    ],
                ]);

                $user = NguoidungModel::where('email', $usernameInput)->first();
            }
            // Username là username thực
            else {

                $req->validate([
                    'username' => [
                        'required',
                        'string',
                        'min:6',
                        'max:20',
                        'regex:/^[A-Za-z0-9_]+$/',
                    ],
                ]);
                $user = NguoidungModel::where('username', $usernameInput)->first();
            }
        }else {
            return back()->withErrors([
                'login' => 'Bạn phải nhập email hoặc username!',
            ])->withInput();
        }
        // Check user + password
        if (!$user || !Hash::check($req->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
            ])->withInput();
        }

        // Check role
        if ($user->vaitro !== 'admin') {
            return back()->withErrors([
                'login' => 'Tài khoản này không có quyền truy cập admin.',
            ]);
        }

        // Login session Laravel
        Auth::login($user);

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

        try {
            $request->validate([
                'hoten' => 'required|string|min:1|max:30|regex:/^[\pL\s]+$/u',
                'email' => [
                        'required',
                        'string',
                        'email:rfc,dns,filter',   // kiểm tra format + DNS MX
                        'max:50',
                        'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',   // không khoảng trắng + phải có domain
                        'unique:nguoidung,email,' . $user->id,
                    ],
                'username' => 'required|string|min:6|max:20|regex:/^[A-Za-z0-9_]+$/|unique:nguoidung,username,' . $user->id,
                'sodienthoai' => 'required|string|max:10|unique:nguoidung,sodienthoai,' . $user->id. '|regex:/^[0-9]+$/',
                'gioitinh' => 'nullable|in:Nam,Nữ',
                'password' => 'nullable|string|max:20|min:6|confirmed|regex:/^[A-Za-z0-9_]+$/',
                'ngaysinh' => 'nullable|date',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()->withErrors([
                'error' => "Lỗi validate form".$e->getMessage(),
            ])->withInput();
        }





        $user->hoten = $request->input('hoten');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
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


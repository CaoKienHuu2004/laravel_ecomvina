<?php

namespace App\Http\Controllers;

use App\Models\DiaChiGiaoHangModel;
use App\Models\NguoidungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NguoidungController extends Controller
{
    protected $uploadDir = "assets/client/images/profiles"; // thư mục lưu file, relative so với storage/app/public
    protected $domain;
    protected $provinces;

    public function __construct()
    {
        $this->domain = env('DOMAIN', 'http://148.230.100.215/');
        $this->provinces = config('tinhthanh');
    }

    /**
     * Hiển thị danh sách người dùng (chưa xóa)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = NguoidungModel::orderByDesc('id');

        if ($search) {
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('hoten', 'like', "%{$search}%")
                  ->orWhere('sodienthoai', 'like', "%{$search}%");
        }

        $nguoidungs = $query->paginate(10)->withQueryString();

        return view('nguoidung.index', compact('nguoidungs', 'search'));
    }

    /**
     * Form thêm mới người dùng
     */
    public function create()
    {
        $tinhthanhs = collect($this->provinces)->pluck('ten')->toArray();
        return view('nguoidung.create', compact('tinhthanhs'));
    }

    /**
     * Lưu người dùng mới vào CSDL
     */
    /**
     * Lưu người dùng mới và địa chỉ giao hàng
     */
    public function store(Request $request)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        // Validate dữ liệu người dùng + địa chỉ giao hàng
        $request->validate([
            'username'    => 'required|string|max:255|unique:nguoidung,username',
            'password'    => 'required|string|min:6|confirmed',
            'hoten'       => 'required|string|max:255',
            'sodienthoai' => 'nullable|string|max:10',
            'gioitinh'    => 'nullable|in:Nam,Nữ',
            'ngaysinh'    => 'nullable|date',
            'vaitro'      => 'required|in:admin,seller,client',
            'trangthai'   => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Validate địa chỉ giao hàng
            'diachi_diachi'    => 'required|string',
            'diachi_tinhthanh' => ['required', 'string', Rule::in($provinceNames)], // list tỉnh thành bạn định nghĩa
            'diachi_trangthai' => 'nullable|in:Mặc định,Khác,Tạm ẩn',
        ]);

        // Tạo người dùng
        $nguoidung = new NguoidungModel();
        $nguoidung->username = $request->username;
        $nguoidung->password = Hash::make($request->password);
        $nguoidung->hoten = $request->hoten;
        $nguoidung->sodienthoai = $request->sodienthoai;
        $nguoidung->gioitinh = $request->gioitinh;
        $nguoidung->ngaysinh = $request->ngaysinh;
        $nguoidung->vaitro = $request->vaitro;
        $nguoidung->trangthai = $request->trangthai;

        $link_hinh_anh = $this->domain . 'storage/' . $this->uploadDir . '/';
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // $filename = $file->getClientOriginalName(); // time() . '_' .
            $filename = time() . '_' .$file->getClientOriginalName(); // thêm này đi cho chắc mắc công nhằm người dùng là toang
            $file->storeAs($this->uploadDir, $filename, 'public');

            $nguoidung->avatar = $link_hinh_anh.$filename;
        } else {
            $nguoidung->avatar = $link_hinh_anh.'khachhang.jpg';
        }

        $nguoidung->save();

        // Tạo địa chỉ giao hàng liên kết với người dùng vừa tạo
        // sử dụng lại hoten và sodienthoai từ nguoidung, bắt buộc form phải thêm 3 trường diachi tinhthanh và trangthai
        $diachi = new DiaChiGiaoHangModel();
        $diachi->id_nguoidung = $nguoidung->id;
        $diachi->hoten = $request->hoten;
        $diachi->sodienthoai = $request->sodienthoai;
        $diachi->diachi = $request->diachi_diachi;
        $diachi->tinhthanh = $request->diachi_tinhthanh;
        $diachi->trangthai = $request->diachi_trangthai ?? 'Khác'; // mặc định nếu không có
        $diachi->save();

        return redirect()->route('nguoidung.index')->with('success', 'Thêm người dùng và địa chỉ giao hàng thành công!');
    }

    /**
     * Hiển thị chi tiết người dùng
     */
    public function show($id)
    {
        $nguoidung = NguoidungModel::findOrFail($id);
        return view('nguoidung.show', compact('nguoidung'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit($id)
    {
        $nguoidung = NguoidungModel::findOrFail($id);
        $tinhthanhs = collect($this->provinces)->pluck('ten')->toArray();

        return view('nguoidung.edit', compact('nguoidung','tinhthanhs'));
    }

    /**
     * Cập nhật người dùng và địa chỉ giao hàng mặc định
     */
    public function update(Request $request, $id)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        $nguoidung = NguoidungModel::findOrFail($id);

        $request->validate([
            'username'    => 'required|string|max:255|unique:nguoidung,username,' . $nguoidung->id,
            'password'    => 'nullable|string|min:6|confirmed',
            'hoten'       => 'required|string|max:255',
            'sodienthoai' => 'nullable|string|max:10',
            'gioitinh'    => 'nullable|in:Nam,Nữ',
            'ngaysinh'    => 'nullable|date',
            'vaitro'      => 'required|in:admin,seller,client',
            'trangthai'   => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Validate địa chỉ giao hàng

            'diachi_diachi'    => 'required|string',
            'diachi_tinhthanh' => ['required', 'string', Rule::in($provinceNames)], // list tỉnh thành bạn định nghĩa
            'diachi_trangthai' => 'nullable|in:Mặc định,Khác,Tạm ẩn',
        ]);

        // Cập nhật người dùng
        $nguoidung->username = $request->username;
        $nguoidung->hoten = $request->hoten;
        $nguoidung->sodienthoai = $request->sodienthoai;
        $nguoidung->gioitinh = $request->gioitinh;
        $nguoidung->ngaysinh = $request->ngaysinh;
        $nguoidung->vaitro = $request->vaitro;
        $nguoidung->trangthai = $request->diachi_trangthai ?? 'Khác'; // mặc định nếu không có

        if ($request->filled('password')) {
            $nguoidung->password = Hash::make($request->password);
        }


        if ($request->hasFile('avatar')) {
            // if ($nguoidung->avatar) {
            //     $oldPath = public_path(parse_url($nguoidung->avatar, PHP_URL_PATH));
            //     if (file_exists($oldPath) && $nguoidung->avatar != 'khachhang.jpg') {
            //         unlink($oldPath);
            //     }
            // }
            $file = $request->file('avatar');
            $filename = time() . '_' .$file->getClientOriginalName(); // thêm này đi cho chắc mắc công nhằm người dùng là toang
            // $filename = $file->getClientOriginalName(); //time() . '_' .
            $file->storeAs($this->uploadDir, $filename, 'public');
            $link_hinh_anh = $this->domain . 'storage/' . $this->uploadDir . '/';
            $nguoidung->avatar = $link_hinh_anh.$filename;
        }

        $nguoidung->save();

        // Cập nhật hoặc tạo mới địa chỉ giao hàng mặc định cho người dùng này
        $diachi = $nguoidung->diachi()->where('trangthai', 'Mặc định')->first();

        if (!$diachi) {
            $diachi = new DiaChiGiaoHangModel();
            $diachi->id_nguoidung = $nguoidung->id;
        }

        // sử dụng lại hoten và sodienthoai từ nguoidung, bắt buộc form phải thêm 3 trường diachi tinhthanh và trangthai
        $diachi->hoten = $request->hoten;
        $diachi->sodienthoai = $request->sodienthoai;
        $diachi->diachi = $request->diachi_diachi;
        $diachi->tinhthanh = $request->diachi_tinhthanh;
        $diachi->trangthai = $request->diachi_trangthai;
        $diachi->save();

        return redirect()->route('nguoidung.index')->with('success', 'Cập nhật người dùng và địa chỉ giao hàng thành công!');
    }

    /**
     * Xóa mềm người dùng (soft delete)
     */
    public function destroy($id)
    {
        $nguoidung = NguoidungModel::findOrFail($id);
        $nguoidung->delete();

        return redirect()->route('nguoidung.index')->with('success', 'Đã chuyển người dùng vào thùng rác!');
    }

    /**
     * Hiển thị danh sách người dùng đã xóa mềm
     */
    public function trash()
    {
        $nguoidungs = NguoidungModel::onlyTrashed()->orderByDesc('deleted_at')->paginate(10);
        return view('nguoidung.trash', compact('nguoidungs'));
    }

    /**
     * Khôi phục người dùng đã xóa mềm
     */
    public function restore($id)
    {
        $nguoidung = NguoidungModel::onlyTrashed()->findOrFail($id);
        $nguoidung->restore();

        return redirect()->route('nguoidung.trash')->with('success', 'Khôi phục người dùng thành công!');
    }

    /**
     * Xóa vĩnh viễn người dùng khỏi DB và xóa avatar vật lý nếu có
     */
    public function forceDelete($id)
    {
        $nguoidung = NguoidungModel::onlyTrashed()->findOrFail($id);

        // if ($nguoidung->avatar && $nguoidung->avatar != 'khachhang.jpg') {
        //     $filePath = public_path(parse_url($nguoidung->avatar, PHP_URL_PATH));
        //     if (file_exists($filePath)) {
        //         unlink($filePath);
        //     }
        // }

        $nguoidung->forceDelete();

        return redirect()->route('nguoidung.trash')->with('success', 'Đã xóa vĩnh viễn người dùng!');
    }
}

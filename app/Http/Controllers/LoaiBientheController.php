<?php

namespace App\Http\Controllers;

use App\Models\BientheModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\LoaibientheModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoaiBientheController extends Controller
{
    /**
     * Hiển thị danh sách loại biến thể
     */
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm
        // $keyword = $request->query('keyword');

        // // Tạo query builder
        // $query = LoaibientheModel::query()->orderBy('id', 'desc');

        // // Nếu có từ khóa thì lọc theo tên
        // if (!empty($keyword)) {
        //     $query->where('ten', 'LIKE', "%{$keyword}%");
        // }

        // // Phân trang (10 bản ghi mỗi trang)
        // $loaibienthes = $query->paginate(10);

        // // Giữ lại tham số tìm kiếm khi chuyển trang
        // $loaibienthes->appends(['keyword' => $keyword]);

        // return view('loaibienthe.index', compact('loaibienthes', 'keyword'));
        $loaibienthes = LoaibientheModel::orderBy('id', 'desc')->get();
        return view('loaibienthe.index', compact('loaibienthes'));
    }


    /**
     * Hiển thị form thêm mới loại biến thể
     */
    public function create()
    {
        // Lấy danh sách giá trị enum của cột trangthai
        $trangthais = LoaibientheModel::getEnumValues('trangthai');

        return view('loaibienthe.create', compact('trangthais'));
    }

    /**
     * Xử lý thêm mới loại biến thể
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255|unique:loaibienthe,ten',
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ], [
            'ten.required' => 'Tên loại biến thể không được để trống.',
            'ten.unique' => 'Tên loại biến thể đã tồn tại.',
            'trangthai.required' => 'Vui lòng chọn trạng thái.',
        ]);

        // Tạo mới
        LoaibientheModel::create([
            'ten' => $request->ten,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('loaibienthe.index')
            ->with('success', 'Thêm loại biến thể thành công!');
    }

    /**
     * Hiển thị chi tiết loại biến thể
     */
    public function show($id)
    {
        $loaibienthe = LoaibientheModel::findOrFail($id);
        return view('loaibienthe.show', compact('loaibienthe'));
    }

    /**
     * Hiển thị form chỉnh sửa loại biến thể
     */
    public function edit($id)
    {
        $loaibienthe = LoaibientheModel::findOrFail($id);
        $trangthais = LoaibientheModel::getEnumValues('trangthai');

        return view('loaibienthe.edit', compact('loaibienthe', 'trangthais'));
    }

    /**
     * Cập nhật loại biến thể
     */
    public function update(Request $request, $id)
    {
        $loaibienthe = LoaibientheModel::findOrFail($id);

        // Validate
        $request->validate([
            'ten' => 'required|string|max:255|unique:loaibienthe,ten,' . $id,
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ]);

        $loaibienthe->update([
            'ten' => $request->ten,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('loaibienthe.index')
            ->with('success', 'Cập nhật loại biến thể thành công!');
    }

    /**
     * Xóa loại biến thể cứng
     */
    public function destroy(Request $request,$id)
    {
        // Xác thực mật khẩu trước khi xóa
        $request->validate([
            'password_confirm' => ['required'],
        ]);
        $user = Auth::user();
        // Kiểm tra mật khẩu nhập lại
        if (!Hash::check($request->password_confirm, $user->password)) {
            return redirect()->back()->withErrors(['password_confirm' => 'Mật khẩu không chính xác']);
        }
        // Xác thực mật khẩu trước khi xóa

        $loaibienthe = LoaibientheModel::findOrFail($id);

        try {
            // Chỉ cần gọi delete, model tự lo việc xóa cứng liên quan
            $loaibienthe->forceDelete();

            return redirect()->route('loaibienthe.index')
                ->with('success', 'Đã xóa cứng loại biến thể cùng các sản phẩm & biến thể liên quan!');
        } catch (\Exception $e) {
            return redirect()->route('loaibienthe.index')
                ->with('error', 'Không thể xóa loại biến thể này!');
        }
    }
}

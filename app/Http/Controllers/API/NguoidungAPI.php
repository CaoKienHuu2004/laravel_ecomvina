<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoidungModel;
use Illuminate\Http\Response;

class NguoidungAPI extends BaseController
{
    /**
     * Lấy danh sách người dùng có phân trang + tìm kiếm
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->query('q');

        $query = NguoidungModel::with(['diachi', 'cuahang', 'baiviet'])
            ->whereNull('deleted_at');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('hoten', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhere('sodienthoai', 'like', "%{$q}%");
            });
        }

        $users = $query->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        // Nếu người dùng yêu cầu trang vượt quá tổng số trang
        if ($currentPage > $users->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $users->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page'    => $users->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $users->total(),
                ]
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Danh sách người dùng',
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ]
        ], 200);
    }

    /**
     * Tạo mới người dùng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'     => 'required|string|max:255|unique:nguoidung,username',
            'password'     => 'required|string|min:6',
            'sodienthoai'  => 'required|string|max:10|unique:nguoidung,sodienthoai',
            'hoten'        => 'required|string|max:255',
            'gioitinh'     => 'required|in:Nam,Nữ',
            'ngaysinh'     => 'required|date',
            'avatar'       => 'nullable|string|max:255',
            'vaitro'       => 'required|in:admin,seller,client',
            'trangthai'    => 'required|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = NguoidungModel::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Tạo người dùng thành công',
            'data' => $user,
        ], Response::HTTP_CREATED);
    }

    /**
     * Hiển thị chi tiết người dùng
     */
    public function show(string $id)
    {
        $user = NguoidungModel::with(['diachi', 'cuahang', 'baiviet'])
            ->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Thông tin chi tiết người dùng',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật người dùng
     */
    public function update(Request $request, string $id)
    {
        $user = NguoidungModel::findOrFail($id);

        $validated = $request->validate([
            'username'     => 'sometimes|required|string|max:255|unique:nguoidung,username,' . $user->id,
            'password'     => 'nullable|string|min:6',
            'sodienthoai'  => 'sometimes|required|string|max:10|unique:nguoidung,sodienthoai,' . $user->id,
            'hoten'        => 'sometimes|required|string|max:255',
            'gioitinh'     => 'nullable|in:Nam,Nữ',
            'ngaysinh'     => 'nullable|date',
            'avatar'       => 'nullable|string|max:255',
            'vaitro'       => 'nullable|in:admin,seller,client',
            'trangthai'    => 'nullable|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật người dùng thành công',
            'data' => $user->fresh(['diachi', 'cuahang', 'baiviet']),
        ], Response::HTTP_OK);
    }

    /**
     * Xóa mềm người dùng
     */
    public function destroy(string $id)
    {
        $user = NguoidungModel::findOrFail($id);

        // Kiểm tra nếu người dùng có cửa hàng hoặc bài viết
        if ($user->cuahang || $user->baiviet()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể xóa người dùng vì vẫn còn dữ liệu liên quan (cửa hàng hoặc bài viết).',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->delete(); // Xóa mềm (vì dùng SoftDeletes)

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa người dùng thành công (đã chuyển vào thùng rác)',
        ], Response::HTTP_OK);
    }

    /**
     * Khôi phục người dùng đã xóa mềm
     */
    public function restore(string $id)
    {
        $user = NguoidungModel::onlyTrashed()->findOrFail($id);
        $user->restore();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Khôi phục người dùng thành công',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    /**
     * Xóa vĩnh viễn (force delete)
     */
    public function forceDelete(string $id)
    {
        $user = NguoidungModel::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đã xóa vĩnh viễn người dùng',
        ], Response::HTTP_OK);
    }
}

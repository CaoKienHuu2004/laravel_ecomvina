<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Nguoidung;
use App\Http\Resources\NguoidungResources;
use Illuminate\Http\Response;

class NguoidungAPI extends BaseController
{
    /**
     * Lấy danh sách người dùng (có phân trang).
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->query('q');

        // Khởi tạo query
        // $query = Nguoidung::with(['diachi', 'session']);
        $query = Nguoidung::with(['diachi' => function ($q) {
            $q->whereNull('deleted_at'); // lọc những địa chỉ chưa bị xóa
        }, 'session']);

        // Nếu có từ khóa tìm kiếm
        if ($q) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('hoten', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        // Thực hiện phân trang
        $items = $query->orderBy('id', 'asc')
                    ->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'data' => NguoidungResources::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], 200);
    }

    /**
     * Tạo mới người dùng.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usename'   => 'required|string|max:255|unique:nguoi_dung,usename',
            'email'     => 'required|email|unique:nguoi_dung,email',
            'password'  => 'required|string|min:6',
            'avatar'    => 'nullable|string',
            'hoten'     => 'required|string|max:255',
            'giotinh'   => 'nullable|in:nam,nữ',
            'ngaysinh'  => 'nullable|date',
            'sodienthoai' => 'nullable|string|max:15|unique:nguoi_dung,sodienthoai',
            'vaitro'    => 'nullable|in:user,admin,assistant,anonymous',
            'trangthai' => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = Nguoidung::create($validated);

        // Load quan hệ nếu cần
        $user->load(['diachi', 'session']);

        return $this->jsonResponse(new NguoidungResources($user), Response::HTTP_CREATED);
    }

    /**
     * Xem chi tiết 1 người dùng.
     */
    public function show(string $id)
    {
        $user = Nguoidung::with(['diachi', 'phiendangnhap'])->findOrFail($id);

        return $this->jsonResponse(new NguoidungResources($user), Response::HTTP_OK);
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function update(Request $request, string $id)
    {
        $user = Nguoidung::findOrFail($id);

        $validated = $request->validate([
            'usename'   => 'sometimes|required|string|max:255|unique:nguoi_dung,usename,' . $user->id,
            'email'     => 'sometimes|required|email|unique:nguoi_dung,email,' . $user->id,
            'password'  => 'nullable|string|min:6',
            'avatar'    => 'nullable|string',
            'hoten'     => 'sometimes|required|string|max:255',
            'giotinh'   => 'nullable|in:nam,nữ',
            'ngaysinh'  => 'nullable|date',
            'sodienthoai' => 'nullable|string|max:15|unique:nguoi_dung,sodienthoai,' . $user->id,
            'vaitro'    => 'nullable|in:user,admin,assistant,anonymous',
            'trangthai' => 'nullable|in:hoat_dong,ngung_hoat_dong,bi_khoa,cho_duyet',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $user->load(['diachi', 'session']);

        return $this->jsonResponse(new NguoidungResources($user), Response::HTTP_OK);
    }

    /**
     * Xóa người dùng.
     */
    public function destroy(string $id)
    {
        $user = Nguoidung::findOrFail($id);

        if ($user->diachi()->count() > 0) {
            return $this->jsonResponse([
                'message' => 'Không thể xóa! Người dùng này vẫn còn địa chỉ.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->delete();

        return $this->jsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

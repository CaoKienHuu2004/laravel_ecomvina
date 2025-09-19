<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nguoidung;
use App\Http\Resources\NguoidungResources;
use Illuminate\Http\Response;

class NguoidungAPI extends Controller
{
    /**
     * Lấy danh sách người dùng (có phân trang).
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $items = Nguoidung::latest('updated_at')->paginate($perPage);

        // Load quan hệ luôn
        $items->load(['diachi', 'phiendangnhap']);

        return response()->json([
            'data' => NguoidungResources::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
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
        $user->load(['diachi', 'phiendangnhap']);

        return response()->json(new NguoidungResources($user), Response::HTTP_CREATED);
    }

    /**
     * Xem chi tiết 1 người dùng.
     */
    public function show(string $id)
    {
        $user = Nguoidung::with(['diachi', 'phiendangnhap'])->findOrFail($id);

        return response()->json(new NguoidungResources($user), Response::HTTP_OK);
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

        $user->load(['diachi', 'phiendangnhap']);

        return response()->json(new NguoidungResources($user), Response::HTTP_OK);
    }

    /**
     * Xóa người dùng.
     */
    public function destroy(string $id)
    {
        $user = Nguoidung::findOrFail($id);

        if ($user->diachi()->count() > 0) {
            return response()->json([
                'message' => 'Không thể xóa! Người dùng này vẫn còn địa chỉ.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

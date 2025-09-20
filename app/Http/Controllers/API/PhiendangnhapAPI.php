<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PhienDangNhap;
use App\Http\Resources\PhienDangNhapResource;
use Illuminate\Http\Response;

class PhienDangNhapAPI extends BaseController
{
    // Lấy danh sách phiên đăng nhập (có phân trang)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        $phienDangNhaps = PhienDangNhap::with('nguoidung')
            ->latest('created_at')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $phienDangNhaps->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $phienDangNhaps->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $phienDangNhaps->lastPage(),
                    'per_page' => $perPage,
                    'total' => $phienDangNhaps->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách phiên đăng nhập',
            'data' => PhienDangNhapResource::collection($phienDangNhaps),
            'meta' => [
                'current_page' => $phienDangNhaps->currentPage(),
                'last_page'    => $phienDangNhaps->lastPage(),
                'per_page'     => $phienDangNhaps->perPage(),
                'total'        => $phienDangNhaps->total(),
            ]
        ], Response::HTTP_OK);
    }

    // Lấy chi tiết 1 phiên
    public function show(Request $request, string $id)
    {
        $phien = PhienDangNhap::with('nguoidung')->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết phiên đăng nhập',
            'data' => new PhienDangNhapResource($phien)
        ], Response::HTTP_OK);
    }

    // Tạo mới phiên (ví dụ khi login)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'            => 'required|string',
            'nguoi_dung_id' => 'nullable|exists:nguoi_dung,id',
            'dia_chi_ip'    => 'nullable|string|max:45',
            'trinh_duyet'   => 'nullable|string',
            'du_lieu'       => 'required|string',
            'hoat_dong_cuoi'=> 'required|integer',
        ]);

        $phien = PhienDangNhap::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo phiên đăng nhập thành công',
            'data' => new PhienDangNhapResource($phien)
        ], Response::HTTP_CREATED);
    }

    // Cập nhật phiên (khi user hoạt động lại)
    public function update(Request $request, string $id)
    {
        $phien = PhienDangNhap::findOrFail($id);

        $validated = $request->validate([
            'du_lieu' => 'sometimes|required|string',
            'hoat_dong_cuoi' => 'sometimes|required|integer',
        ]);

        $phien->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật phiên đăng nhập thành công',
            'data' => new PhienDangNhapResource($phien)
        ], Response::HTTP_OK);
    }

    // Xóa phiên
    public function destroy(string $id)
    {
        $phien = PhienDangNhap::findOrFail($id);
        $phien->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa phiên đăng nhập thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

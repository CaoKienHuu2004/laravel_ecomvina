<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhienDangNhap;
use Illuminate\Http\Request;
use App\Http\Resources\PhienDangNhapResource;
use Illuminate\Http\Response;

class PhienDangNhapAPI extends Controller
{
    // Lấy danh sách phiên đăng nhập (có phân trang)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $phienDangNhaps = PhienDangNhap::with('nguoidung')
            ->latest('created_at')
            ->paginate($perPage);

        return PhienDangNhapResource::collection($phienDangNhaps)
            ->additional([
                'meta' => [
                    'current_page' => $phienDangNhaps->currentPage(),
                    'last_page'    => $phienDangNhaps->lastPage(),
                    'per_page'     => $phienDangNhaps->perPage(),
                    'total'        => $phienDangNhaps->total(),
                ]
            ]);
    }

    // Lấy chi tiết 1 phiên
    public function show(Request $request, string $id)
    {
        $phien = PhienDangNhap::with('nguoidung')->findOrFail($id);
        return new PhienDangNhapResource($phien, $request);
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

        return (new PhienDangNhapResource($phien))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // Cập nhật phiên (khi user hoạt động lại)
    public function update(Request $request, string $id)
    {
        $phien = PhienDangNhap::findOrFail($id);
        $phien->update($request->only(['du_lieu', 'hoat_dong_cuoi']));

        return new PhienDangNhapResource($phien);
    }

    // Xóa phiên
    public function destroy(string $id)
    {
        $phien = PhienDangNhap::findOrFail($id);
        $phien->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

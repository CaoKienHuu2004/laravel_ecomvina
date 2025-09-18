<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HanhviNguoiDungResource;
use App\Models\HanhviNguoidung;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HanhviNguoidungAPI extends Controller
{
    /**
     * Lưu hành vi người dùng
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_nguoidung' => 'nullable|exists:nguoi_dung,id',
            'id_sanpham'   => 'nullable|exists:san_pham,id',
            'id_bienthe'   => 'nullable|exists:bienthe_sp,id',
            'hanhdong'     => 'required|in:xem,click_bienthe,them_gio,mua,danh_gia',
            'ghichu'       => 'nullable|string',
        ]);

        $hanhvi = HanhviNguoidung::create($data);

        return (new HanhviNguoiDungResource(
            $hanhvi->load(['nguoidung', 'sanpham', 'bienthe'])
        ))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Danh sách hành vi (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $logs = HanhviNguoidung::with(['nguoidung', 'sanpham', 'bienthe'])
            ->latest()
            ->paginate($perPage);

        return HanhviNguoiDungResource::collection($logs)
            ->additional([
                'meta' => [
                    'current_page' => $logs->currentPage(),
                    'last_page'    => $logs->lastPage(),
                    'per_page'     => $logs->perPage(),
                    'total'        => $logs->total(),
                ],
            ]);
    }
}

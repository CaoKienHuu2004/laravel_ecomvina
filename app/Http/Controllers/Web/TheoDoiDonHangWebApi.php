<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DonhangModel;
use App\Traits\ApiResponse;
use App\Http\Resources\Toi\TheoDoiDonHang\TheoDoiDonHangResource;
use Illuminate\Support\Facades\DB;

class TheoDoiDonHangWebApi extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user = $request->get('auth_user');
        if (!$user) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không xác thực được user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $query = DonhangModel::with(['chitietdonhang.bienthe.sanpham', 'chitietdonhang.bienthe.loaibienthe','chitietdonhang.bienthe.sanpham.hinhanhsanpham'])
            ->where('id_nguoidung', $user->id);

        $validTrangThai = [
            'Chờ xử lý',
            'Đã xác nhận',
            'Đang chuẩn bị hàng',
            'Đang giao hàng',
            'Đã giao hàng',
            'Đã hủy',
        ];

        if ($request->filled('trangthai') && in_array($request->trangthai, $validTrangThai)) {
            $query->where('trangthai', $request->trangthai);
        }

        if ($request->filled('madon')) {
            $query->where('madon', $request->madon);
        }

        $donhangs = $query->latest()->get();

        TheoDoiDonHangResource::withoutWrapping();
        return response()->json(TheoDoiDonHangResource::collection($donhangs), Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $user = $request->get('auth_user');
        if (!$user) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không xác thực được user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $donhang = DonhangModel::with('chitietdonhang')->find($id);

        if (!$donhang || $donhang->id_nguoidung !== $user->id) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy đơn hàng hoặc bạn không có quyền.',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'trangthai' => 'required|string|in:Chờ xử lý,Đã xác nhận,Đang chuẩn bị hàng,Đang giao hàng,Đã giao hàng,Đã hủy',
        ]);

        $chiTietTrangThai = ($validated['trangthai'] === 'Đã hủy') ? 'Đã hủy' : 'Đã đặt';

        DB::transaction(function () use ($donhang, $validated, $chiTietTrangThai) {
            $donhang->trangthai = $validated['trangthai'];
            $donhang->save();

            foreach ($donhang->chitietdonhang as $chitiet) {
                $chitiet->trangthai = $chiTietTrangThai;
                $chitiet->save();
            }
        });

        $donhang->load(['chitietdonhang.bienthe.loaibienthe', 'chitietdonhang.bienthe.sanpham','chitietdonhang.bienthe.sanpham.hinhanhsanpham']);

        TheoDoiDonHangResource::withoutWrapping();
        return response()->json(new TheoDoiDonHangResource($donhang), Response::HTTP_OK);
    }
}

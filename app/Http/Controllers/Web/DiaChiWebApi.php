<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\DiaChiGiaoHangModel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Http\Resources\Toi\DiachiGiaohangResource;
use App\Traits\ApiResponse;

class DiaChiWebApi extends Controller
{
    use ApiResponse;
    protected $provinces;

    public function __construct()
    {
        $this->provinces = config('tinhthanh');
    }

    public function index(Request $request)
    {
        try {
            $user = $request->get('auth_user');

            $diachis = DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
                ->orderByRaw("FIELD(trangthai, 'Mặc định', 'Khác', 'Tạm ẩn')")
                ->get();

            DiachiGiaohangResource::withoutWrapping();

            return response()->json(DiachiGiaohangResource::collection($diachis), Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        $trangthaiEnum = DiaChiGiaoHangModel::getEnumValues('trangthai');
        $user = $request->get('auth_user');

        $validated = $request->validate([
            'hoten' => 'required|string|max:255',
            'sodienthoai' => 'required|string|size:10',
            'diachi' => 'required|string',
            'diachi' => 'required|string',
            'tinhthanh' => ['required', 'string', Rule::in($provinceNames)],
            'trangthai' => 'required|in:' . implode(',', $trangthaiEnum),
        ]);

        DB::beginTransaction();
        try {
            // Nếu thêm địa chỉ mặc định, các địa chỉ khác sẽ thành "Khác"
            if (($validated['trangthai'] ?? null) === 'Mặc định') {
                DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
                    ->update(['trangthai' => 'Khác']);
            }

            $diachi = DiaChiGiaoHangModel::create(array_merge($validated, [
                'id_nguoidung' => $user->id,
                'trangthai' => $validated['trangthai'] ?? 'Khác',
            ]));

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Thêm địa chỉ thành công',
                'data' => $diachi,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi thêm địa chỉ: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $provinceNames = collect($this->provinces)->pluck('ten')->toArray();
        $trangthaiEnum = DiaChiGiaoHangModel::getEnumValues('trangthai');
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'hoten' => 'sometimes|string|max:255',
            'sodienthoai' => 'sometimes|string|size:10',
            'diachi' => 'sometimes|string',
            'tinhthanh' => ['required', 'string', Rule::in($provinceNames)],
            'trangthai' => ['required', Rule::in($trangthaiEnum)],
        ]);

        DB::beginTransaction();
        try {
            // Nếu cập nhật thành mặc định
            if (($validated['trangthai'] ?? null) === 'Mặc định') {
                DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
                    ->update(['trangthai' => 'Khác']);
            }

            $diachi->update($validated);

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Cập nhật địa chỉ thành công',
                'data' => $diachi,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi cập nhật địa chỉ: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        $diachi->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa địa chỉ thành công',
        ], Response::HTTP_OK);
    }

    public function setDefault(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            // Các địa chỉ khác thành "Khác"
            DiaChiGiaoHangModel::where('id_nguoidung', $user->id)
                ->update(['trangthai' => 'Khác']);

            $diachi->update(['trangthai' => 'Mặc định']);

            DB::commit();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Đặt địa chỉ mặc định thành công',
                'data' => $diachi,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Lỗi đặt mặc định: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $diachi = DiaChiGiaoHangModel::where('id', $id)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$diachi) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Không tìm thấy địa chỉ!',
            ], Response::HTTP_NOT_FOUND);
        }

        // Nếu địa chỉ đang mặc định thì không cho tạm ẩn
        if ($diachi->trangthai === 'Mặc định') {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Địa chỉ mặc định không thể tạm ẩn!',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Toggle giữa Tạm ẩn và Khác
        $diachi->update([
            'trangthai' => $diachi->trangthai === 'Tạm ẩn' ? 'Khác' : 'Tạm ẩn',
        ]);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật trạng thái địa chỉ thành công',
            'data' => $diachi,
        ], Response::HTTP_OK);
    }
}

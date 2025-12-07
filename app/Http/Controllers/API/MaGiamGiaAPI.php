<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\MagiamgiaModel;
use Illuminate\Http\Response;



class MaGiamGiaAPI extends BaseController
{

    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = MagiamgiaModel::orderBy('id', 'desc')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('magiamgia', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%")
                        ->orWhere('trangthai', 'like', "%$q%");
                });
            });

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status'  => false,
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
            'status'  => true,
            'message' => 'Danh sách mã giảm giá',
            'data'    => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }


    public function show($id)
    {
        $item = MagiamgiaModel::with('donhang')->find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy mã giảm giá'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Chi tiết mã giảm giá',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới mã giảm giá
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'magiamgia'   => 'required|string|unique:magiamgia,magiamgia',
            'dieukien'    => 'required|string|max:255',
            'mota'        => 'nullable|string',
            'giatri'      => 'required|integer|min:0',
            'ngaybatdau'  => 'required|date',
            'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau',
            'trangthai'   => 'nullable|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        $item = MagiamgiaModel::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Tạo mã giảm giá thành công',
            'data'    => $item
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function update(Request $request, $id)
    {
        $item = MagiamgiaModel::find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy mã giảm giá'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'magiamgia'   => 'nullable|string|unique:magiamgia,magiamgia,' . $id,
            'dieukien'    => 'nullable|string|max:255',
            'mota'        => 'nullable|string',
            'giatri'      => 'nullable|integer|min:0',
            'ngaybatdau'  => 'nullable|date',
            'ngayketthuc' => 'nullable|date|after_or_equal:ngaybatdau',
            'trangthai'   => 'nullable|in:Hoạt động,Tạm khóa,Dừng hoạt động',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Cập nhật mã giảm giá thành công',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Xóa mềm mã giảm giá
     */
    public function destroy($id)
    {
        $item = MagiamgiaModel::find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy mã giảm giá để xóa'
            ], Response::HTTP_NOT_FOUND);
        }

        $item->delete(); // Soft delete

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Đã xóa mã giảm giá thành công (soft delete)'
        ], Response::HTTP_OK);
    }

    /**
     * Khôi phục mã giảm giá bị xóa mềm
     */
    public function restore($id)
    {
        $item = MagiamgiaModel::withTrashed()->find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy mã giảm giá để khôi phục'
            ], Response::HTTP_NOT_FOUND);
        }

        $item->restore();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Khôi phục mã giảm giá thành công',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Xóa vĩnh viễn mã giảm giá (force delete)
     */
    public function forceDelete($id)
    {
        $item = MagiamgiaModel::withTrashed()->find($id);

        if (!$item) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Không tìm thấy mã giảm giá để xóa vĩnh viễn'
            ], Response::HTTP_NOT_FOUND);
        }

        $item->forceDelete();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Đã xóa vĩnh viễn mã giảm giá'
        ], Response::HTTP_OK);
    }
}

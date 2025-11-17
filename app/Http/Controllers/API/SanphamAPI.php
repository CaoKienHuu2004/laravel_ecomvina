<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SanphamModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class SanphamAPI extends BaseController
{

    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $thuonghieu  = $request->get('thuonghieu');
        $trangthai   = $request->get('trangthai');
        $q           = $request->get('q');

        $query = SanphamModel::with(['thuonghieu', 'danhmuc', 'hinhanhsanpham', 'bienthe','loaibienthe','danhgia','chitietdonhang','danhgia.nguoidung'])
        // $query = SanphamModel::with(['yeuthich','thuonghieu', 'danhmuc', 'hinhanhsanpham', 'bienthe','loaibienthe','danhgia','chitietdonhang','danhgia.nguoidung'])
            ->orderBy('id', 'desc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%")
                    ->orWhere('mota', 'like', "%$q%");
            });
        }

        if ($thuonghieu) {
            $query->whereHas('thuonghieu', function ($sub) use ($thuonghieu) {
                $sub->where('ten', 'like', "%$thuonghieu%");
            });
        }

        if ($trangthai) {
            $query->where('trangthai', $trangthai);
        }

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Danh sách sản phẩm',
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten'           => 'required|string|max:255',
            'slug'          => 'required|string|max:255|unique:sanpham,slug',
            'mota'          => 'nullable|string',
            'xuatxu'        => 'nullable|string|max:255',
            'sanxuat'       => 'nullable|string|max:255',
            'trangthai'     => 'required|in:Công khai,Chờ duyệt,Tạm ẩn,Tạm khóa',
            'giamgia'       => 'nullable|integer|min:0',
            'luotxem'       => 'nullable|integer|min:0',
            'luotban'       => 'nullable|integer|min:0',
            'id_thuonghieu' => 'nullable|integer|exists:thuonghieu,id',
            'id_danhmuc'    => 'nullable|array',
            'id_danhmuc.*'  => 'integer|exists:danhmuc,id',
        ]);

        $product = SanphamModel::create($validated);

        if ($request->has('id_danhmuc')) {
            $product->danhmuc()->sync($request->id_danhmuc);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Thêm sản phẩm thành công',
            'data'    => $product->load(['thuonghieu', 'danhmuc']),
        ], Response::HTTP_CREATED);
    }


    public function show(string $id)
    {
        $product = SanphamModel::with(['thuonghieu', 'danhmuc', 'hinhanhsanpham', 'bienthe','loaibienthe','danhgia','chitietdonhang','danhgia.nguoidung',])
        // $product = SanphamModel::with(['yeuthich','thuonghieu', 'danhmuc', 'hinhanhsanpham', 'bienthe','loaibienthe','danhgia','chitietdonhang','danhgia.nguoidung'])
            ->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Chi tiết sản phẩm',
            'data'    => $product,
        ], Response::HTTP_OK);
    }

    public function update(Request $request, string $id)
    {
        $product = SanphamModel::findOrFail($id);

        $validated = $request->validate([
            'ten'           => 'sometimes|string|max:255',
            'slug'          => 'sometimes|string|max:255|unique:sanpham,slug,' . $product->id,
            'mota'          => 'nullable|string',
            'xuatxu'        => 'nullable|string|max:255',
            'sanxuat'       => 'nullable|string|max:255',
            'trangthai'     => 'nullable|in:Công khai,Chờ duyệt,Tạm ẩn,Tạm khóa',
            'giamgia'       => 'nullable|integer|min:0',
            'id_thuonghieu' => 'nullable|integer|exists:thuonghieu,id',
        ]);

        $product->update($validated);

        if ($request->has('id_danhmuc')) {
            $product->danhmuc()->sync($request->id_danhmuc);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật sản phẩm thành công',
            'data'    => $product->refresh()->load(['thuonghieu', 'danhmuc']),
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $product = SanphamModel::findOrFail($id);
        $product->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Đã xóa sản phẩm (xóa mềm)',
        ], Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $product = SanphamModel::onlyTrashed()->findOrFail($id);
        $product->restore();

        return response()->json([
            'status'  => true,
            'message' => 'Khôi phục sản phẩm thành công',
            'data'    => $product,
        ], Response::HTTP_OK);
    }

    public function forceDestroy(string $id)
    {
        $product = SanphamModel::onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return response()->json([
            'status'  => true,
            'message' => 'Đã xóa vĩnh viễn sản phẩm',
        ], Response::HTTP_OK);
    }
}

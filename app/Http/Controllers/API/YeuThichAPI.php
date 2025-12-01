<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YeuthichModel;
use Illuminate\Http\Response;

class YeuThichAPI extends Controller
{
    /**
     * Lấy danh sách yêu thích
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $keyword     = $request->get('q');

        $query = YeuthichModel::with(['sanpham', 'nguoidung'])
            ->latest('created_at')
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('trangthai', 'like', "%$keyword%")
                    ->orWhereHas('sanpham', function ($sp) use ($keyword) {
                        $sp->where('ten', 'like', "%$keyword%");
                    })
                    ->orWhereHas('nguoidung', function ($nd) use ($keyword) {
                        $nd->where('ten', 'like', "%$keyword%");
                    });
            });

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
            ], 404);
        }
        // dd($query);
        // exit();

        return response()->json([
            'status'  => true,
            'message' => 'Danh sách yêu thích',
            'data'    => $items->items(), // không dùng Resource
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Xem chi tiết
     */
    public function show(string $id)
    {
        $item = YeuthichModel::with(['sanpham', 'nguoidung'])->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Chi tiết yêu thích',
            'data'    => $item
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới yêu thích
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sanpham'   => 'required|exists:sanpham,id',
            'id_nguoidung' => 'required|exists:nguoidung,id',
            'trangthai'    => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $item = YeuthichModel::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Thêm vào danh sách yêu thích thành công',
            'data'    => $item->load(['sanpham', 'nguoidung'])
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật yêu thích
     */
    public function update(Request $request, string $id)
    {
        $item = YeuthichModel::findOrFail($id);

        $validated = $request->validate([
            'trangthai' => 'required|in:Hiển thị,Tạm ẩn',
        ]);

        $item->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật yêu thích thành công',
            'data'    => $item->load(['sanpham', 'nguoidung'])
        ], Response::HTTP_OK);
    }

    /**
     * Xóa (xóa mềm)
     */
    public function destroy(string $id)
    {
        $item = YeuthichModel::findOrFail($id);
        $item->delete(); // nhờ use SoftDeletes => xóa mềm

        return response()->json([
            'status'  => true,
            'message' => 'Đã xóa yêu thích thành công (xóa mềm)'
        ], Response::HTTP_OK);
    }
}

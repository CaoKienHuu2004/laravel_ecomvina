<?php

// namespace App\Http\Controllers\API;

// use App\Http\Resources\GioHangCollectionResource;
// use Illuminate\Http\Request;
// use App\Models\GioHang;
// use App\Http\Resources\GioHangResource;
// use Illuminate\Http\Response;

// class GioHangAPI extends BaseController
// {
//     /**
//      * Danh sách giỏ hàng (có phân trang)
//      */
//     public function index(Request $request)
//     {
//         $perPage = $request->get('per_page', 10);
//         $currentPage = $request->get('page', 1);

//         $giohangs = GioHang::with(['nguoidung', 'bienthesp.sanpham'])
//             ->latest('updated_at')
//             ->paginate($perPage, ['*'], 'page', $currentPage);

//         // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
//         if ($currentPage > $giohangs->lastPage() && $currentPage > 1) {
//             return $this->jsonResponse([
//                 'status' => false,
//                 'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $giohangs->lastPage(),
//                 'data'    => GioHangResource::collection($giohangs),
//                 'meta' => [
//                     'current_page' => $currentPage,
//                     'last_page'    => $giohangs->lastPage(),
//                     'per_page'     => $perPage,
//                     'total'        => $giohangs->total(),
//                 ]
//             ], 404);
//         }

//         return $this->jsonResponse([
//             'status'  => true,
//             'message' => 'Danh sách giỏ hàng',
//             'data'    => GioHangResource::collection($giohangs),
//             'meta'    => [
//                 'current_page' => $giohangs->currentPage(),
//                 'last_page'    => $giohangs->lastPage(),
//                 'per_page'     => $giohangs->perPage(),
//                 'total'        => $giohangs->total(),
//             ]
//         ], 200);
//     }

//     /**
//      * Tạo mới giỏ hàng
//      */
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'soluong'      => 'required|integer|min:1',
//             'tongtien'     => 'required|numeric|min:0',
//             'id_bienthesp' => 'required|exists:bienthe_sp,id',
//             'id_nguoidung' => 'required|exists:nguoi_dung,id',
//         ]);

//         $giohang = GioHang::create($validated);

//         return $this->jsonResponse([
//             'status'  => true,
//             'message' => 'Tạo giỏ hàng thành công',
//             'data'    => new GioHangResource($giohang->load(['nguoidung', 'bienthesp']))
//         ], Response::HTTP_CREATED);
//     }

//     /**
//      * Chi tiết giỏ hàng
//      */
//     public function show(string $id)
//     {
//         $giohang = GioHang::with(['nguoidung', 'bienthesp.sanpham'])->findOrFail($id);

//         return $this->jsonResponse([
//             'status'  => true,
//             'message' => 'Chi tiết giỏ hàng',
//             'data'    => new GioHangResource($giohang) // đang sai login quan Hệ N-N phải sửa database them chi tiet gio hang mới giải quyết được GioHangResource::collection mới đúng
//         ], Response::HTTP_OK);
//     }

//     /**
//      * Cập nhật giỏ hàng
//      */
//     public function update(Request $request, string $id)
//     {
//         $giohang = GioHang::findOrFail($id);

//         $validated = $request->validate([
//             'soluong'      => 'sometimes|integer|min:1',
//             'tongtien'     => 'sometimes|numeric|min:0',
//             'id_bienthesp' => 'sometimes|exists:bienthe_sp,id',
//             'id_nguoidung' => 'sometimes|exists:nguoi_dung,id',
//         ]);

//         $giohang->update($validated);

//         return $this->jsonResponse([
//             'status'  => true,
//             'message' => 'Cập nhật giỏ hàng thành công',
//             'data'    => new GioHangResource($giohang->refresh()->load(['nguoidung', 'bienthesp']))
//         ], Response::HTTP_OK);
//     }

//     /**
//      * Xóa giỏ hàng
//      */
//     public function destroy(string $id)
//     {
//         $giohang = GioHang::findOrFail($id);
//         $giohang->delete();

//         return $this->jsonResponse([
//             'status'  => true,
//             'message' => 'Xóa giỏ hàng thành công'
//         ], Response::HTTP_NO_CONTENT);
//     }
// }


namespace App\Http\Controllers\API;

use App\Models\GioHang;
use App\Http\Resources\GioHangResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GioHangAPI extends BaseController
{
    // Danh sách giỏ hàng
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q              = $request->get('q', '');

        $giohangs = GioHang::with(['nguoidung', 'chitiet.bienTheSanPham.sanpham'])
            ->when($q, function ($query) use ($q) {
                // lọc qua mối quan hệ để tìm theo tên sản phẩm
                $query->whereHas('chitiet.bienTheSanPham.sanpham', function ($subQuery) use ($q) {
                    $subQuery->where('ten', 'LIKE', '%' . $q . '%');
                });
            })
            ->latest('updated_at')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách giỏ hàng',
            'data'    => GioHangResource::collection($giohangs),
            'meta'    => [
                'current_page' => $giohangs->currentPage(),
                'last_page'    => $giohangs->lastPage(),
                'per_page'     => $giohangs->perPage(),
                'total'        => $giohangs->total(),
            ]
        ], 200);
    }

    // Tạo giỏ hàng (chỉ cần user_id, sản phẩm thêm vào chiTiet)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nguoidung' => 'required|exists:nguoi_dung,id',
        ]);

        $giohang = GioHang::create($validated);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Tạo giỏ hàng thành công',
            'data'    => new GioHangResource($giohang->load(['nguoidung', 'chitiet']))
        ], Response::HTTP_CREATED);
    }

    // Xem chi tiết giỏ hàng
    public function show(string $id)
    {
        $giohang = GioHang::with(['nguoidung', 'chitiet.bienTheSanPham.sanpham'])->findOrFail($id);

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Chi tiết giỏ hàng',
            'data'    => new GioHangResource($giohang)
        ], Response::HTTP_OK);
    }

    // Xóa giỏ hàng
    public function destroy(string $id)
    {
        $giohang = GioHang::findOrFail($id);
        $giohang->delete();

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Xóa giỏ hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

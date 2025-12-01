<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\MagiamgiaModel;
use Illuminate\Http\Response;


/**
 * @OA\Tag(
 *     name="Mã giảm giá",
 *     description="API quản lý mã giảm giá (tìm kiếm, chi tiết, CRUD)"
 * )
 */
class MaGiamGiaAPI extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/magiamgias",
     *     tags={"Mã giảm giá"},
     *     summary="Lấy danh sách mã giảm giá (có tìm kiếm + phân trang)",
     *     description="Trả về danh sách các mã giảm giá, có hỗ trợ tìm kiếm theo mã, mô tả hoặc trạng thái.",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Từ khóa tìm kiếm (theo mã, mô tả, trạng thái)",
     *         @OA\Schema(type="string", example="SALE50")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Trang hiện tại",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Số lượng phần tử mỗi trang",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách mã giảm giá",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Danh sách mã giảm giá"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = MagiamgiaModel::latest('updated_at')
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

    /**
     * @OA\Get(
     *     path="/api/magiamgias/{id}",
     *     tags={"Mã giảm giá"},
     *     summary="Xem chi tiết 1 mã giảm giá",
     *     description="Trả về thông tin chi tiết của một mã giảm giá theo ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của mã giảm giá",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết mã giảm giá",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chi tiết mã giảm giá"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy mã giảm giá")
     * )
     */
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
            'magiamgia'   => 'required|integer|unique:magiamgia,magiamgia',
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
            'magiamgia'   => 'nullable|integer|unique:magiamgia,magiamgia,' . $id,
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

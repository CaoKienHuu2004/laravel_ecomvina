<?php
namespace App\Http\Controllers\API;



use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\ChitietdonhangModel;
use Illuminate\Http\Response;

class ChiTietDonHangAPI extends BaseController
{
    /**
     * Lấy danh sách chi tiết đơn hàng (có phân trang)
     */
    public function index(Request $request)
    {
        $perPage     = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q', null);

        $query = ChitietdonhangModel::with(['donhang', 'bienthe'])->latest('updated_at');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->whereHas('donhang', fn($q1) =>
                        $q1->where('id', $q))
                    ->orWhereHas('bienthe', fn($q2) =>
                        $q2->where('ten', 'like', "%$q%")
                           ->orWhere('ma', 'like', "%$q%"));
            });
        }

        $items = $query->paginate($perPage, ['*'], 'page', $currentPage);

        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'meta'    => [
                    'current_page' => $currentPage,
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status'  => true,
            'message' => 'Danh sách chi tiết đơn hàng',
            'data'    => $items->map(fn($item) => [
                'id' => $item->id,
                'id_bienthe' => $item->id_bienthe,
                'id_donhang' => $item->id_donhang,
                'soluong' => $item->soluong,
                'dongia' => $item->dongia,
                'donhang' => $item->donhang,
                'bienthe' => $item->bienthe,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'next_page_url'=> $items->nextPageUrl(),
                'prev_page_url'=> $items->previousPageUrl(),
            ]
        ], 200);
    }

    /**
     * Xem chi tiết 1 bản ghi
     */
    public function show(string $id)
    {
        $item = ChitietdonhangModel::with(['donhang', 'bienthe'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết đơn hàng',
            'data' => [
                'id' => $item->id,
                'id_bienthe' => $item->id_bienthe,
                'id_donhang' => $item->id_donhang,
                'soluong' => $item->soluong,
                'dongia' => $item->dongia,
                'donhang' => $item->donhang,
                'bienthe' => $item->bienthe,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Tạo mới chi tiết đơn hàng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_donhang' => 'required|exists:donhang,id',
            'id_bienthe' => 'required|exists:bienthe,id',
            'soluong'    => 'required|integer|min:1',
            'dongia'     => 'required|numeric|min:0',
        ]);

        $item = ChitietdonhangModel::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo chi tiết đơn hàng thành công',
            'data' => $item
        ], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật chi tiết đơn hàng
     */
    public function update(Request $request, string $id)
    {
        $item = ChitietdonhangModel::findOrFail($id);

        $validated = $request->validate([
            'id_donhang' => 'sometimes|exists:donhang,id',
            'id_bienthe' => 'sometimes|exists:bienthe,id',
            'soluong'    => 'sometimes|integer|min:1',
            'dongia'     => 'sometimes|numeric|min:0',
        ]);

        $item->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật chi tiết đơn hàng thành công',
            'data' => $item
        ], Response::HTTP_OK);
    }

    /**
     * Xóa chi tiết đơn hàng
     */
    public function destroy(string $id)
    {
        $item = ChitietdonhangModel::findOrFail($id);
        $item->delete(); // xóa thật vì không có soft delete

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa chi tiết đơn hàng thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

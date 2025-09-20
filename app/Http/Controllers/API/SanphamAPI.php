<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Resources\SanphamResources;
use App\Models\Sanpham;
use Illuminate\Http\Response;

class SanphamAPI extends BaseController
{
    /**
     * Display a listing of the resource with filters + pagination.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);

        $query = Sanpham::query()->with([
            'bienThe.loaiBienThe',
            'anhSanPham',
            'danhmuc',
            'thuonghieu',
        ]);

        // Filter thương hiệu
        if ($request->filled('thuonghieu')) {
            $query->where('id_thuonghieu', (int) $request->thuonghieu);
        }

        // Filter danh mục
        if ($request->filled('danhmuc')) {
            $query->whereHas('danhmuc', fn($q) => $q->where('id_danhmuc', (int) $request->danhmuc));
        }

        // Filter giá min
        if ($request->filled('gia_min')) {
            $query->whereHas('bienThe', fn($q) => $q->where('gia', '>=', (int) $request->gia_min));
        }

        // Filter giá max
        if ($request->filled('gia_max')) {
            $query->whereHas('bienThe', fn($q) => $q->where('gia', '<=', (int) $request->gia_max));
        }

        $sanphams = $query->latest('updated_at')->paginate($perPage, ['*'], 'page', $currentPage);

        // Kiểm tra nếu trang yêu cầu vượt quá tổng số trang
        if ($currentPage > $sanphams->lastPage() && $currentPage > 1) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $sanphams->lastPage(),
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $sanphams->lastPage(),
                    'per_page' => $perPage,
                    'total' => $sanphams->total(),
                ]
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách sản phẩm',
            'data' => SanphamResources::collection($sanphams),
            'meta' => [
                'current_page' => $sanphams->currentPage(),
                'last_page'    => $sanphams->lastPage(),
                'per_page'     => $sanphams->perPage(),
                'total'        => $sanphams->total(),
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource (admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten'          => 'required|string|max:255',
            'id_thuonghieu'=> 'required|integer|exists:thuong_hieu,id',
            'xuatxu'       => 'required|string|max:255',
            'sanxuat'      => 'nullable|string|max:255',
            'mota'         => 'nullable|string',
        ]);

        $product = Sanpham::create($validated);

        if ($request->has('id_danhmuc')) {
            $product->danhmuc()->sync($request->id_danhmuc);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo sản phẩm thành công',
            'data' => new SanphamResources($product->load(['thuonghieu', 'danhmuc']))
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Sanpham::with([
            'bienThe.loaiBienThe',
            'anhSanPham',
            'danhmuc',
            'thuonghieu',
        ])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết sản phẩm',
            'data' => new SanphamResources($product)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource (admin only)
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'ten'          => 'sometimes|required|string|max:255',
            'id_thuonghieu'=> 'sometimes|required|integer|exists:thuong_hieu,id',
            'xuatxu'       => 'sometimes|required|string|max:255',
            'sanxuat'      => 'nullable|string|max:255',
            'mota'         => 'nullable|string',
        ]);

        $product = Sanpham::findOrFail($id);
        $product->update($validated);

        if ($request->has('id_danhmuc')) {
            $product->danhmuc()->sync($request->id_danhmuc);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật sản phẩm thành công',
            'data' => new SanphamResources($product->refresh()->load(['thuonghieu', 'danhmuc']))
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource (admin only)
     */
    public function destroy(Request $request, string $id)
    {
        $product = Sanpham::findOrFail($id);
        $product->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa sản phẩm thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}

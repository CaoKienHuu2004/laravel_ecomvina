<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SanphamResources;
use App\Models\Sanpham;
use Illuminate\Http\Response;

class SanphamAPI extends Controller
{
    /**
     * Display a listing of the resource with filters + pagination.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);

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

        $sanphams = $query->latest('updated_at')->paginate($perPage);

        return SanphamResources::collection($sanphams)
            ->additional([
                'meta' => [
                    'current_page' => $sanphams->currentPage(),
                    'last_page'    => $sanphams->lastPage(),
                    'per_page'     => $sanphams->perPage(),
                    'total'        => $sanphams->total(),
                ]
            ]);
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

        return (new SanphamResources($product->load(['thuonghieu', 'danhmuc'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
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

        return new SanphamResources($product);
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

        return new SanphamResources($product->refresh()->load(['thuonghieu', 'danhmuc']));
    }

    /**
     * Remove the specified resource (admin only)
     */
    public function destroy(Request $request, string $id)
    {
        $product = Sanpham::findOrFail($id);
        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

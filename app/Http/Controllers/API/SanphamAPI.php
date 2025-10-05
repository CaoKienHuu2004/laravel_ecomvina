<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SanphamResources;
use App\Models\Sanpham;

class SanphamAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sanphams = Sanpham::with([
            "bienThe",
            "bienThe.loaiBienThe",
            "anhSanPham",
            "danhmuc",
            "thuonghieu",
        ])->paginate(20);

        // Trả về dữ liệu qua API Resource Collection
        return SanphamResources::collection($sanphams);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

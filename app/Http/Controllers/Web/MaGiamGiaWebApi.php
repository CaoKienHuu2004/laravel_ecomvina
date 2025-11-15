<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\MaGiamGiaResource;
use App\Models\MagiamgiaModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaGiamGiaWebApi extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q       = $request->get('q');

        $items = MagiamgiaModel::orderBy('id', 'desc')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('magiamgia', 'like', "%{$q}%")
                        ->orWhere('mota', 'like', "%{$q}%")
                        ->orWhere('trangthai', 'like', "%{$q}%");
                });
            })
            ->simplePaginate($perPage);

        MaGiamGiaResource::withoutWrapping();

        return response()->json(
            MaGiamGiaResource::collection($items->items()),
            Response::HTTP_OK
        );
    }
    public function show($id)
    {
        $item = MagiamgiaModel::find($id);
        MaGiamGiaResource::withoutWrapping();
        return response()->json(
            new MaGiamGiaResource($item),
            Response::HTTP_OK
        );
    }
}

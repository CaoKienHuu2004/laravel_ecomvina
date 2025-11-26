<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\BaiVietAllResource;
use App\Http\Resources\Web\BaiVietResource;
use Illuminate\Http\Request;
use App\Models\BaivietModel;
use Illuminate\Http\Response;

class BaivietWebApi extends Controller
{
    //
    public function index(Request $request)
    {
        $filter = $request->get('filter', null);

        $baiviets = BaivietModel::query();
        if ($filter) {
            $baiviets->where(function ($query) use ($filter) {
                $query->where('tieude', 'like', "%{$filter}%")
                    ->orWhere('noidung', 'like', "%{$filter}%");
            });
        }
        $result = $baiviets->orderBy('id', 'desc')->paginate(10);

        BaiVietAllResource::withoutWrapping();

        return response()->json(BaiVietAllResource::collection($result), Response::HTTP_OK);
    }

    public function show(string $id)
    {
        if (is_numeric($id)) {
            $baiviet = BaivietModel::where('id', $id)->first(); // firstOrFail() 404 luôn
        } else {
            // Nếu $id không phải số → xem nó là slug
            $baiviet = BaivietModel::where('slug', $id)->first();  // firstOrFail() 404 luôn
        }

        // Không tìm thấy
        if (!$baiviet) {
            return $this->error('Không tìm thấy bài viết', [], 404);
        }

        $baiviet->increment('luotxem');

        // Lấy bài viết liên quan: cùng chuyên mục, khác id hiện tại, giới hạn 2 bài
        $keyword = $baiviet->tieude;
        $baitviets = BaivietModel::where('tieude', 'like', "%{$keyword}%")
                ->where('id', '!=', $baiviet->id)
                ->limit(2)
                ->get();

        BaiVietResource::withoutWrapping();
        BaiVietAllResource::withoutWrapping();

        return response()->json([
            new BaiVietResource($baiviet),
            BaiVietAllResource::collection($baitviets)
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\BaiVietAllResource;
use App\Http\Resources\Frontend\BaiVietResource;
use App\Models\BaivietModel;
use Illuminate\Http\Request;

class BaiVietAllFrontendAPI extends BaseFrontendController
{
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
        return $this->jsonResponse([
            'data' => BaiVietAllResource::collection($result->items()),
            'pagination' => [
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
            ],
        ]);
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

        return $this->jsonResponse([
            'data' => new BaiVietResource($baiviet),
            'baiviet_tuongtu' => BaiVietResource::collection($baitviets),
        ]);
    }


    // public function getPreviousAndNext(int $id)
    // {
    //     $current = BaivietModel::find($id);
    //     if (!$current) {
    //         return $this->error('Không tìm thấy bài viết hiện tại', [], 404);
    //     }

    //     // Bài viết trước (id < $id, lấy bản ghi lớn nhất nhỏ hơn $id)
    //     $previous = BaivietModel::where('id', '<', $id)
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     // Bài viết sau (id > $id, lấy bản ghi nhỏ nhất lớn hơn $id)
    //     $next = BaivietModel::where('id', '>', $id)
    //         ->orderBy('id', 'asc')
    //         ->first();

    //     return $this->jsonResponse([
    //         'previous' => $previous ? [
    //             'id' => $previous->id,
    //             'title' => $previous->title ?? $previous->ten ?? '', // tùy trường tên bài viết
    //             'slug' => $previous->slug,
    //         ] : null,
    //         'next' => $next ? [
    //             'id' => $next->id,
    //             'title' => $next->title ?? $next->ten ?? '',
    //             'slug' => $next->slug,
    //         ] : null,
    //     ]);
    // }
}

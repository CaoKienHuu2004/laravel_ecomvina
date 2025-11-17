<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BaivietModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;


class BaiVietAPI extends BaseController
{
    use ApiResponse;


    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page    = $request->get('page', 1);
        $q       = $request->get('q');

        $query = BaivietModel::where('trangthai', 'Hiển thị')->orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('tieude', 'like', "%$q%")
                    ->orWhere('noidung', 'like', "%$q%");
            });
        }

        $items = $query->paginate($perPage, ['*'], 'page', $page);

        if ($page > $items->lastPage() && $page > 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Danh sách bài viết',
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], Response::HTTP_OK);
    }


    public function show($id)
    {
        $baiviet = BaivietModel::where('id', $id)
            ->where('trangthai', 'Hiển thị')
            ->first();

        if (!$baiviet) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Bài viết không tồn tại hoặc đang tạm ẩn',
            ], Response::HTTP_NOT_FOUND);
        }

        // Tăng lượt xem
        $baiviet->increment('luotxem');

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết bài viết',
            'data' => $baiviet,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * Chỉ admin mới sử dụng, frontend không cần
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Chỉ admin mới sử dụng, frontend không cần
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * Chỉ admin mới sử dụng, frontend không cần
     */
    public function destroy(string $id)
    {
        //
    }
}

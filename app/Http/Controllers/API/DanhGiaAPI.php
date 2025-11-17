<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\DanhgiaModel;
use Illuminate\Http\Response;

class DanhGiaAPI extends BaseController
{

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $q = $request->get('q');

        $query = DanhgiaModel::with(['sanpham', 'nguoidung','chitietdonhang'])
            ->latest('updated_at');
        // dd( $query);
        // var_dump($query);
        // exit();
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('noidung', 'like', "%$q%")
                    ->orWhere('trangthai', 'like', "%$q%")
                    ->orWhereHas('sanpham', function ($sp) use ($q) {
                        $sp->where('ten', 'like', "%$q%");
                    })
                    ->orWhereHas('nguoidung', function ($u) use ($q) {
                        $u->where('hoten', 'like', "%$q%");
                    });
            });
        }


        $items = $query->with([
                'nguoidung:id,hoten',
                'sanpham',
                'chitietdonhang'
            ])->paginate($perPage);

        // dd($query);
        // var_dump($query);
        // exit();


        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách đánh giá',
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'next_page_url'=> $items->nextPageUrl(),
                'prev_page_url'=> $items->previousPageUrl(),
            ],
        ], Response::HTTP_OK);
    }


    public function show($id)
    {
        $item = DanhgiaModel::with(['sanpham', 'nguoidung:id,hoten','chitietdonhang'])->findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết đánh giá',
            'data' => $item,
        ], Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sanpham' => 'required|exists:sanpham,id',
            'id_nguoidung'=> 'required|exists:nguoidung,id',
            'id_chitietdonhang'=> 'required|exists:chitiet_donhang,id',
            'diem'       => 'required|integer|min:0|max:5',
            'noidung'    => 'nullable|string',
            'trangthai'  => 'nullable|in:Hiển thị,Tạm ẩn',
        ]);

        $item = DanhgiaModel::create($validated);
        $item->load(['sanpham', 'nguoidung','chitietdonhang']);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo đánh giá thành công',
            'data' => $item,
        ], Response::HTTP_CREATED);
    }


    public function update(Request $request, $id)
    {
        $item = DanhgiaModel::findOrFail($id);

        $validated = $request->validate([
            'id_sanpham' => 'sometimes|exists:sanpham,id',
            'id_nguoidung'=> 'sometimes|exists:nguoi_dung,id',
            'id_chitietdonhang'=> 'required|exists:chitiet_donhang,id',
            'diem'       => 'sometimes|integer|min:0|max:5',
            'noidung'    => 'sometimes|string',
            'trangthai'  => 'sometimes|in:Hiển thị,Tạm ẩn',
        ]);

        $item->update($validated);
        $item->load(['sanpham', 'nguoidung','chitietdonhang']);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật đánh giá thành công',
            'data' => $item,
        ], Response::HTTP_OK);
    }


    public function destroy($id)
    {
        $item = DanhgiaModel::findOrFail($id);
        $item->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa đánh giá thành công',
        ], Response::HTTP_NO_CONTENT);
    }



    public function restore($id)
    {
        $item = DanhgiaModel::withTrashed()->findOrFail($id);

        if (!$item->trashed()) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Đánh giá này chưa bị xóa.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $item->restore();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Phục hồi đánh giá thành công.',
            'data' => $item->load(['sanpham', 'nguoidung']),
        ], Response::HTTP_OK);
    }


    public function forceDelete($id)
    {
        $item = DanhgiaModel::withTrashed()->findOrFail($id);

        $item->forceDelete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Đánh giá đã bị xóa vĩnh viễn.'
        ], Response::HTTP_NO_CONTENT);
    }
}

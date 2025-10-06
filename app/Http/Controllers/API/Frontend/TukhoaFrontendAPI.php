<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Resources\Frontend\TukhoaResource;
use App\Models\TuKhoa;
use Illuminate\Http\Request;

class TukhoaFrontendAPI extends BaseFrontendController
{
    // Lấy danh sách tất cả từ khóa (sắp xếp theo số lượt giảm dần)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $q = $request->get('q');

        $query = TuKhoa::query();

        if ($q) {
            $query->whereRaw("MATCH(dulieu) AGAINST (? IN NATURAL LANGUAGE MODE)", [$q]);
        }
        $tuKhoa = $query->orderByDesc('soluot')->paginate($perPage);
        return TukhoaResource::collection($tuKhoa);
    }

    // Thêm mới một từ khóa
    public function store(Request $request)
    {
        $request->validate([
            'dulieu' => 'required|string|max:255',
            'soluot' => 'nullable|integer|min:0'
        ]);

        $tuKhoa = TuKhoa::create([
            'dulieu' => $request->dulieu,
            'soluot' => $request->soluot ?? 0
        ]);

        return (new TukhoaResource($tuKhoa))
                ->additional(['message' => 'Tạo từ khóa thành công'])
                ->response()
                ->setStatusCode(201);
    }

    // Hiển thị chi tiết một từ khóa
    public function show($id)
    {
        $tuKhoa = TuKhoa::findOrFail($id);

        return (new TukhoaResource($tuKhoa))
                    ->additional(['message' => 'Chi tiết từ khóa']);
    }

    // Cập nhật số lượt hoặc tự động tăng
    public function update(Request $request, $id)
    {
        $tuKhoa = TuKhoa::findOrFail($id);

        if ($request->has('soluot')) {
            $request->validate([
                'soluot' => 'required|integer|min:0'
            ]);
            $tuKhoa->update(['soluot' => $request->soluot]);
        } else {
            $tuKhoa->increment('soluot');
            $tuKhoa->refresh();
        }

        return (new TukhoaResource($tuKhoa))
                ->additional(['message' => 'Cập nhật số lượt thành công']);
    }

    // Xóa từ khóa
    public function destroy($id)
    {
        $tuKhoa = TuKhoa::findOrFail($id);
        $tuKhoa->delete();

        return (new TukhoaResource($tuKhoa))
                ->additional(['message' => 'Xóa thành công']);
    }
}

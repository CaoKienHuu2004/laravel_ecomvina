<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\TukhoaResource;
use App\Models\TukhoaModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TukhoaWebApi extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $q = $request->get('q');

        $query = TukhoaModel::query();

        if ($q) {
            $query->where('tukhoa', 'like', "%{$q}%");
        }

        $tuKhoa = $query->orderByDesc('luottruycap')->paginate($perPage);
        TukhoaResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(TukhoaResource::collection($tuKhoa), Response::HTTP_OK);
        // return $this->jsonResponse([
        //     'status' => true,
        //     'message' => 'Danh sách từ khóa',
        //     'data' => $tuKhoa->items(),
        //     'meta' => [
        //         'current_page' => $tuKhoa->currentPage(),
        //         'last_page' => $tuKhoa->lastPage(),
        //         'per_page' => $tuKhoa->perPage(),
        //         'total' => $tuKhoa->total(),
        //         'next_page_url' => $tuKhoa->nextPageUrl(),
        //         'prev_page_url' => $tuKhoa->previousPageUrl(),
        //     ]
        // ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tukhoa' => 'required|string|max:255',
            'luottruycap' => 'nullable|integer|min:0',
        ]);

        $tuKhoa = TukhoaModel::create([
            'tukhoa' => $validated['tukhoa'],
            'luottruycap' => $validated['luottruycap'] ?? 0,
        ]);

        // return $this->jsonResponse([
        //     'status' => true,
        //     'message' => '✅ Tạo từ khóa thành công',
        //     'data' => $tuKhoa,
        // ], Response::HTTP_CREATED);

        TukhoaResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(new TukhoaResource($tuKhoa), Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Tìm từ khóa, nếu không có thì báo lỗi 404
        $tuKhoa = TukhoaModel::findOrFail($id);

        // Tăng lượt truy cập lên 1
        $tuKhoa->increment('luottruycap');

        // Nếu bạn muốn đảm bảo lấy giá trị mới nhất sau khi tăng:
        $tuKhoa->refresh();

        // Trả về JSON response
        TukhoaResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(new TukhoaResource($tuKhoa), Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tuKhoa = TukhoaModel::findOrFail($id);

        // Nếu có dữ liệu cập nhật cụ thể
        if ($request->has('tukhoa') || $request->has('luottruycap')) {
            $validated = $request->validate([
                'tukhoa' => 'sometimes|string|max:255',
                'luottruycap' => 'sometimes|integer|min:0',
            ]);

            $tuKhoa->update($validated);
        } else {
            // Nếu không có dữ liệu cụ thể thì tăng lượt truy cập lên 1
            $tuKhoa->increment('luottruycap');
            $tuKhoa->refresh();
        }
        TukhoaResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(new TukhoaResource($tuKhoa), Response::HTTP_OK);
        // return $this->jsonResponse([
        //     'status' => true,
        //     'message' => '🔄 Cập nhật từ khóa thành công',
        //     'data' => $tuKhoa,
        // ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $tuKhoa = TukhoaModel::findOrFail($id);
        $tuKhoa->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => '🗑️ Xóa từ khóa thành công',
        ], Response::HTTP_OK);
    }
}

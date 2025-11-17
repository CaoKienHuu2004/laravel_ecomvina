<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\DanhmucModel;
use Illuminate\Http\Response;
use Illuminate\Support\Str;



class DanhmucAPI extends BaseController
{

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $q = $request->get('q');

        $query = DanhmucModel::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        // Laravel tự động lấy ?page= từ query string
        $items = $query->latest('id')->paginate($perPage);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Danh sách danh mục',
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

    /**
     * Tạo mới danh mục
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten' => 'required|string|max:255|unique:danhmuc,ten',
            'logo' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['ten']);
        $validated['logo'] = $validated['logo'] ?? 'danhmuc.jpg';

        $dm = DanhmucModel::create($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Tạo danh mục thành công',
            'data' => $dm,
        ], Response::HTTP_CREATED);
    }


    public function show($id)
    {
        $dm = DanhmucModel::findOrFail($id);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Chi tiết danh mục',
            'data' => $dm,
        ], Response::HTTP_OK);
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id)
    {
        $dm = DanhmucModel::findOrFail($id);

        $validated = $request->validate([
            'ten' => 'sometimes|required|string|max:255|unique:danhmuc,ten,' . $id,
            'logo' => 'nullable|string|max:255',
        ]);

        if (isset($validated['ten'])) {
            $validated['slug'] = Str::slug($validated['ten']);
        }

        $dm->update($validated);

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Cập nhật danh mục thành công',
            'data' => $dm,
        ], Response::HTTP_OK);
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id)
    {
        $dm = DanhmucModel::findOrFail($id);
        $dm->delete();

        return $this->jsonResponse([
            'status' => true,
            'message' => 'Xóa danh mục thành công',
        ], Response::HTTP_OK);
    }
    // thiếu xóa mềm rồi
}

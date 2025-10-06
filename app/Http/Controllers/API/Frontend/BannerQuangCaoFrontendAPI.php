<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\BannerQuangCaoResource;
use Illuminate\Http\Request;
use App\Models\BannerQuangCao;

class BannerQuangCaoFrontendAPI extends BaseFrontendController
{
    /**
     * Hiển thị danh sách banner (có phân trang).
     */
    public function index(Request $request)
    {
        $query = BannerQuangCao::query();
        // Nếu có tham số tìm kiếm q
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('hinhanh', 'like', "%{$q}%")
                ->orWhere('tieude', 'like', "%{$q}%");
        }

        if ($request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 5);
            $banners = $query->orderByDesc('created_at')->paginate($perPage);
        } else {
            $banners = $query->orderByDesc('created_at')->get();
        }
        return BannerQuangCaoResource::collection($banners);
    }

    /**
     * Tạo mới một banner.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vitri'     => 'required|string|max:255',
            'hinhanh'   => 'required|string|max:255',
            'duongdan'  => 'required|string|max:255',
            'tieude'    => 'required|string',
            'trangthai' => 'in:hoat_dong,ngung_hoat_dong',
        ]);

        $banner = BannerQuangCao::create($request->all());

        return (new BannerQuangCaoResource($banner))
                ->additional(['message' => 'Tạo banner thành công'])
                ->response()
                ->setStatusCode(201);

    }

    /**
     * Xem chi tiết một banner.
     */
    public function show($id)
    {
        $banner = BannerQuangCao::findOrFail($id);
        return (new BannerQuangCaoResource($banner))->additional(['message' => 'Chi tiết banner']);
    }

    /**
     * Cập nhật banner.
     */
    public function update(Request $request, $id)
    {
        $banner = BannerQuangCao::findOrFail($id);

        $request->validate([
            'vitri'     => 'sometimes|string|max:255',
            'hinhanh'   => 'sometimes|string|max:255',
            'duongdan'  => 'sometimes|string|max:255',
            'tieude'    => 'sometimes|string',
            'trangthai' => 'sometimes|in:hoat_dong,ngung_hoat_dong',
        ]);

        $banner->update($request->all());

        return (new BannerQuangCaoResource($banner))->additional(['message' => 'Cập nhật banner thành công']);

    }

    /**
     * Xóa banner.
     */
    public function destroy($id)
    {
        $banner = BannerQuangCao::findOrFail($id);
        $banner->delete();

        return (new BannerQuangCaoResource($banner))
                ->additional(['message' => 'Xóa thành công']);
    }

}

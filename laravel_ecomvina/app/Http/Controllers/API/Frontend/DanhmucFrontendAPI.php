<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\API\DanhmucAPI;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\DanhMuc\DanhmucSelectionHomeHomeResource;
use App\Models\Danhmuc;
use Illuminate\Http\Request;

class DanhmucFrontendAPI extends DanhmucAPI
{
    /**
     * Display a listing of the resource.
     */
    // limit 10 orderby theo danh mục có tổng lượt xem và có lượt mua nhiều nhất
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = Danhmuc::select(
                'danh_muc.id',
                'danh_muc.ten',
                'danh_muc.media',
                'danh_muc.trangthai',
                'danh_muc.created_at',
                'danh_muc.updated_at',
                'danh_muc.deleted_at'
            )
            ->selectRaw('
                COALESCE(SUM(san_pham.luotxem), 0) as total_views,
                COALESCE(SUM(chitiet_donhang.soluong), 0) as total_purchases
            ')
            ->leftJoin('sanpham_danhmuc', 'sanpham_danhmuc.id_danhmuc', '=', 'danh_muc.id')
            ->leftJoin('san_pham', 'san_pham.id', '=', 'sanpham_danhmuc.id_sanpham')
            ->leftJoin('bienthe_sp', 'bienthe_sp.id_sanpham', '=', 'san_pham.id')
            ->leftJoin('chitiet_donhang', 'chitiet_donhang.id_bienthe', '=', 'bienthe_sp.id')
            ->when(!optional($request->user())->isAdmin(), function ($q) {
                $q->whereNull('danh_muc.deleted_at');
            })
            ->groupBy(
                'danh_muc.id',
                'danh_muc.ten',
                'danh_muc.media',
                'danh_muc.trangthai',
                'danh_muc.created_at',
                'danh_muc.updated_at',
                'danh_muc.deleted_at'
            )
            ->orderByDesc('total_views')
            ->orderByDesc('total_purchases');

        $items = $query->paginate($perPage);

        return DanhmucSelectionHomeHomeResource::collection($items);
    }



}

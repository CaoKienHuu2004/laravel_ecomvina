<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\Frontend\BaseFrontendController;
use App\Http\Controllers\Controller;


use App\Models\DanhmucModel;
use App\Models\SanPham;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// trả về json data
class SanphamAllWebAPI extends BaseFrontendController
{
    public function index(Request $request)
    {
        //
         $filtering = $request->get('filter');

        switch ($filtering) {
            //Dựa trên sự tương tác/lượt xem cao nhất trong một khoảng thời gian nhất định.
            case 'popular':
                $data = $this->getPopular($request);
                break;
            //Dựa trên thời gian tạo hoặc cập nhật gần nhất (Timestamp).
            case 'latest':
                $data = $this->getLatest($request);
                break;
            //Dựa trên tốc độ tăng trưởng tương tác/lượt xem gần đây.
            case 'trending':
                $data = $this->getTrending($request);
                break;
            //Dựa trên mức độ liên quan đến tìm kiếm hoặc sở thích cá nhân.
            case 'matches':
                $data = $this->getMatches($request);
                break;
            default:
                $data = $this->getDefaultProducts($request);
        }
        $filterMenu = $this->getMenuFilterAside();
        $array = $data->toArray();
        $array['data'][] = $filterMenu;
        return $array['data'];
    }
    protected function getPopular(Request $request)
    {
        //----------------  limit 20 //Dựa trên sự tương tác/lượt xem cao nhất trong một khoảng thời gian nhất định.
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng bán
            ->withSum('bienthe as total_sold', 'luotban')
            ->withSum('bienthe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem') // điểm
            ->withCount('danhgia as review_count') // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienthe' => function ($q) {
                $q->orderBy('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);
        // Sắp xếp:  rồi giagiam, rồi số lượng bán, rồi lượt xem
        // $query->orderByRaw('COALESCE((SELECT gia - giagiam FROM bienthe
        //                             WHERE id_sanpham = san_pham.id
        //                             ORDER BY uutien ASC LIMIT 1), 0) ASC')
            // $query->orderByRaw('COALESCE((SELECT giamgia FROM sanpham
            //                         WHERE id_sanpham = sanpham.id
            //                         ORDER BY uutien ASC LIMIT 1), 0) DESC')
            $query->orderByDesc('giamgia')
            ->orderByDesc('total_sold')
            ->orderByDesc('luotxem'); // thêm lượt xem để tính "phổ biến"

        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    //--------------------------------   limit 20 // Dựa trên thời gian tạo hoặc cập nhật gần nhất (Timestamp)(model đẫ ép kiểu datetime).
    protected function getLatest(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienthe as total_quantity', 'soluong')      // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình đánh giá
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienthe' => function ($q) {
                $q->orderBy('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);

        // 🔥 Sắp xếp theo thời gian mới nhất (updated_at trước, rồi created_at)
        // $query->orderByDesc('updated_at')
        //     ->orderByDesc('created_at');
        $query->latest('id'); // id tăng dần theo thời gian tạo và cập nhật gần nhất

        // Giới hạn 20 sản phẩm
        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    //--------------------------- limit 20 //Dựa trên tốc độ tăng trưởng tương tác/lượt xem gần đây.
    protected function getTrending(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        // khoảng thời gian để tính "gần đây" (vd: 7 ngày qua)
        // $days = $request->get('days', 7);
        // $fromDate = now()->subDays($days);

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienthe as total_quantity', 'soluong')      // tổng tồn kho
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('ten', 'like', "%$q%")
                        ->orWhere('mota', 'like', "%$q%");
                });
            })
            ->with(['bienthe' => function ($q) {
                $q->orderBy('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }])
            // chỉ lấy sản phẩm được cập nhật gần đây
            ->latest('id') //->where('updated_at', '>=', $fromDate)

            // 🔥 sắp xếp theo lượt xem giảm dần (gần đây)
            ->orderByDesc('luotxem');

        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    //--------------------------- limit 20 //Dựa trên mức độ liên quan đến tìm kiếm hoặc sở thích cá nhân.
    protected function getMatches(Request $request)
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm
        $userId      = $request->get('user_id'); // giả sử có user_id để gợi ý theo sở thích

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc', 'bienthe', 'loaibienthe','bienthe.loaibienthe','bienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong')   // tổng số lượng bán
            ->withSum('bienthe as total_quantity', 'soluong')      // tổng tồn kho
            ->withAvg('danhgia as avg_rating', 'diem')             // điểm trung bình
            ->withCount('danhgia as review_count')                 // số lượng đánh giá
            ->with(['bienthe' => function ($q) {
                $q->orderBy('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);

        // 🔎 Nếu có từ khóa tìm kiếm
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('mota', 'like', "%$q%");
            })
            // Thêm điểm relevance để ưu tiên tên hơn mô tả
            ->selectRaw("
                sanpham.*,
                (CASE
                    WHEN ten LIKE ? THEN 3
                    WHEN mota LIKE ? THEN 1
                    ELSE 0
                END) as relevance
            ", ["%$q%", "%$q%"])
            ->orderByDesc('relevance')
            ->orderByDesc('luotxem');
        }
        // ❤️ Nếu không có q mà có user_id → gợi ý theo sản phẩm yêu thích
        elseif ($userId) {
            $query->whereIn('id', function($sub) use ($userId) {
                $sub->select('id_sanpham')
                    ->from('yeuthich')
                    ->where('id_nguoidung', $userId);
            })
            ->latest('id');
        }
        // fallback: nếu không có cả q và user_id → lấy ngẫu nhiên
        else {
            $query->inRandomOrder();
        }

        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return $products;
    }

    protected function getDefaultProducts(Request $request)
    {
        /** Default: phân trang + filter + q + param lọc danhmuc,thuonghieu,locgia theo string covert về number */
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q'); // từ khóa tìm kiếm

        $query = SanphamModel::with([
            'hinhanhsanpham',
            'thuonghieu',
            'danhgia',
            'danhmuc',
            'bienthe',
            'loaibienthe',
            'bienthe.loaibienthe',
            'bienthe.sanpham'
            // ,'bienthe.quatangsukien.chuongtrinh:id,tieude'
        ])
        ->withAvg('danhgia as avg_rating', 'diem')       // điểm trung bình
        ->withCount('danhgia as review_count')           // tổng số đánh giá
        ->withSum('bienthe as total_quantity', 'soluong') // tổng tồn kho
        ->withSum('bienthe as total_sold', 'luotban')
        ->withExists([
            'bienthe as have_gift' => function ($query) {
                $query->whereHas('quatangsukien', function ($q) {
                    $q->where('trangthai', 'Hiển thị')
                    ->whereDate('ngaybatdau', '<=', now())
                    ->whereDate('ngayketthuc', '>=', now())
                    ->whereNull('deleted_at');
                });
            }
        ]);

        // --- Tìm kiếm theo tên hoặc mô tả ---
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('ten', 'like', "%$q%")
                    ->orWhere('mota', 'like', "%$q%");
            });
        }

        // --- Filter thương hiệu ---
        if ($request->filled('thuonghieu')) {
            $query->whereHas('thuonghieu', fn($q) => $q->where('slug', $request->thuonghieu));
        }

        // --- Filter danh mục ---
        if ($request->filled('danhmuc')) {
            $query->whereHas('danhmuc', fn($q) => $q->where('slug', $request->danhmuc));
        }

        // --- Filter giá ---
        if ($request->filled('locgia')) {
            $mapGia = [
                'to100'    => [null, 100000],
                'to200'    => [100000, 200000],
                'to300'    => [200000, 300000],
                'to500'    => [300000, 500000],
                'to700'    => [500000, 700000],
                'to1000'   => [700000, 1000000],
                'high1000' => [1000000, null],
            ];

            $giaMin = $mapGia[$request->locgia][0] ?? null;
            $giaMax = $mapGia[$request->locgia][1] ?? null;

            $query->whereHas('bienthe', function ($q) use ($giaMin, $giaMax) {
                if (!is_null($giaMin)) {
                    $q->where('giagoc', '>=', $giaMin);
                }
                if (!is_null($giaMax)) {
                    $q->where('giagoc', '<=', $giaMax);
                }
            });
        }
        if ($request->filled('sortby')) {
            switch ($request->sortby) {
                case 'topdeals':
                    // Sản phẩm có giảm giá cao nhất → giamgia giảm dần
                    $query->orderByDesc('giamgia')
                        ->orderByDesc('total_sold')
                        ->orderByDesc('avg_rating');
                    break;

                case 'top-bach-hoa':
                    // Giả sử đây là danh mục đặc biệt (bạn có thể thay slug cụ thể)
                    $query->whereHas('danhmuc', fn($q) => $q->where('slug', 'bach-hoa'))
                        ->orderByDesc('total_sold')
                        ->orderByDesc('avg_rating');
                    break;

                case 'latest':
                    // Sản phẩm mới nhất → sắp xếp theo ngày tạo giảm dần
                    $query->orderByDesc('id');
                    break;

                case 'quantamnhieunhat':
                    // Sản phẩm được xem nhiều nhất → luotxem giảm dần
                    $query->orderByDesc('luotxem')
                        ->orderByDesc('avg_rating');
                    break;

                default:
                    // Nếu sortby không hợp lệ thì dùng thứ tự mặc định
                    $query->orderByDesc('luotxem')
                        ->orderByRaw('COALESCE((SELECT MIN(giagoc) FROM bienthe WHERE id_sanpham = sanpham.id), 0) ASC')
                        ->orderByDesc('giamgia')
                        ->orderByDesc('total_sold')
                        ->orderByDesc('avg_rating');
                    break;
            }
        } else {
            // --- Sắp xếp mặc định ---
            $query->orderByDesc('luotxem')
                ->orderByRaw('COALESCE((SELECT MIN(giagoc) FROM bienthe WHERE id_sanpham = sanpham.id), 0) ASC')
                ->orderByDesc('giamgia')
                ->orderByDesc('total_sold')
                ->orderByDesc('avg_rating');
        }

        // --- Phân trang ---
        $sanphams = $query->paginate($perPage, ['*'], 'page', $currentPage);

        $sanphams->getCollection()->transform(function ($item) {
            $bienthe = optional($item->bienthe->where('giagoc', '>', 0)->sortBy('giagoc')->first());
            return [
                'id' => $item->id,
                'name' => $item->ten,
                'slug' => $item->slug,
                'have_gift' => (bool) $item->have_gift ?? false,
                // 'giftProgramId' => optional(
                //     $item->bienthe
                //         ->flatMap(fn($bt) => $bt->quatangsukien)
                //         ->first()
                //         ->chuongtrinh ?? null
                // )->id,
                'originalPrice' => (int)$bienthe->giagoc,
                'discount' => (int)$bienthe->giamgia,
                'sold' => (int)$item->total_sold,
                'rating' => round($item->avg_rating, 1),
                'brand' => $item->thuonghieu->ten ?? null,
                'categoies' => $item->danhmuc->pluck('ten')->toArray(),
                'image' => $item->hinhanhsanpham->first()->hinhanh ?? null,
            ];
        });

        return $sanphams;
    }

    public function show(string $id)
    {
        // $product = Sanpham::with([
        //     'bienThe.loaiBienThe',
        //     'anhSanPham',
        //     'danhmuc',
        //     'thuonghieu',
        // ])->findOrFail($id);

        $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc',
         'bienthe', 'loaibienthe','danhgia.nguoidung','bienthe.loaibienthe','bienthe.sanpham','bienthe.quatangsukien.chuongtrinh:id,tieude'])
        // $query = SanphamModel::with(['hinhanhsanpham', 'thuonghieu', 'danhgia', 'danhmuc',
        //  'bienthe', 'loaibienthe','danhgia.nguoidung','bienthe.loaibienthe','loaibienthe.sanpham'])
            // ->withSum('chitietdonhang as total_sold', 'soluong') // tổng số lượng bán
            // trong Detailreources đã tính tổng số lượng bán từ luotban ở bảng biến thể, nen ko cần subquery như sanphams-all method get
            ->withSum('bienthe as total_quantity', 'soluong') // tổng số biến thể (tồn kho)
            ->withAvg('danhgia as avg_rating', 'diem') // điểm
            ->withCount('danhgia as review_count') // số lượng đánh giá
            ->withSum('bienthe as total_sold', 'luotban')
            ->withExists(['bienthe as have_gift' => function ($query) {
                $query->whereHas('quatangsukien', function ($q) {
                    $q->where('trangthai', 'Hiển thị')
                    ->whereDate('ngaybatdau', '<=', now())
                    ->whereDate('ngayketthuc', '>=', now())
                    ->whereNull('deleted_at');
                });
            }])
            ->with(['bienthe' => function ($q) {
                $q->orderBy('giagoc');
                // $q->orderByDesc('giagoc')->limit(1);
            }]);
            if (is_numeric($id)) {
                $query = $query->where('id', $id)->firstOrFail();
            } else {
                $query = $query->where('slug', $id)->firstOrFail();
            }
            // }])->findOrFail($id);
        $query->increment('luotxem');

        // dd($query);
        // exit;
        $sanphamTuongtu = SanphamModel::with([
                'hinhanhsanpham',
                'thuonghieu',
                'danhgia',
                'danhmuc',
                'bienthe',
                'loaibienthe',
                'bienthe.loaibienthe',
                'bienthe.sanpham'
                // ,'bienthe.quatangsukien.chuongtrinh:id,tieude'
            ])
            ->withSum('bienthe as total_sold', 'luotban')
            ->withSum('bienthe as total_quantity', 'soluong')
            ->withAvg('danhgia as avg_rating', 'diem')
            ->withCount('danhgia as review_count')
            ->withExists(['bienthe as have_gift' => function ($query) {
                $query->whereHas('quatangsukien', function ($q) {
                    $q->where('trangthai', 'Hiển thị')
                    ->whereDate('ngaybatdau', '<=', now())
                    ->whereDate('ngayketthuc', '>=', now())
                    ->whereNull('deleted_at');
                });
            }])
            ->with(['bienthe' => function ($q) {
                $q->orderBy('giagoc');
            }])
            ->whereHas('danhmuc', function ($q) use ($query) {
                $q->whereIn('danhmuc.id', $query->danhmuc->pluck('id')->toArray());
            })
            ->where('sanpham.id', '!=', $query->id)
            ->limit(5)
            ->get();


        // $array = $query->toArray();
        $array = [
            'id' => $query->id,
            'variants' => $query->bienthe->map(function ($bt) {
                return [
                    'id' => $bt->id,
                    'id_variant_types' => $bt->id_loaibienthe,
                    'discount' => (int)$bt->giamgia,
                    'originalPrice' => (int)$bt->giagoc,
                    'sold' => (int)$bt->luotban,
                ];
            }),
            'variantTypes' => $query->bienthe->map(function ($bt) {
                return [
                    'id' => $bt->loaibienthe->id,
                    'name' => $bt->loaibienthe->ten,
                ];
            }),
            'name' => $query->ten,
            'slug' => $query->slug,
            'have_gift' => $query->have_gift ?? false,
            'giftProgramId' => optional(
                $query->bienthe
                    ->flatMap(fn($bt) => $bt->quatangsukien)
                    ->first()
                    ->chuongtrinh ?? null
            )->id,
            'sold' => (int)$query->total_sold,
            'rating' => round($query->avg_rating, 1),
            'brand' => $query->thuonghieu->ten ?? null,
            'categoies' => $query->danhmuc->pluck('ten')->toArray(),
            'image' => $query->hinhanhsanpham->first()->hinhanh ?? null,
            'description' => $query->mota,
            'avg_rating' => $query->avg_rating,
            '1_star' => (int)$query->danhgia->where('diem', 1)->count(),
            '2_star' => (int)$query->danhgia->where('diem', 2)->count(),
            '3_star' => (int)$query->danhgia->where('diem', 3)->count(),
            '4_star' => (int)$query->danhgia->where('diem', 4)->count(),
            '5_star' => (int)$query->danhgia->where('diem', 5)->count(),
            'reviews' => $query->danhgia->map(function ($dg) {
                return [
                    'id' => $dg->id,
                    'user' => [
                        'id' => $dg->nguoidung->id,
                        'name' => $dg->nguoidung->hoten,
                        'avatar' => $dg->nguoidung->avatar,
                    ],
                    'rating' => (int)$dg->diem,
                    'comment' => $dg->noidung,
                ];
            }),
        ];
        $sanphamTuongtuArray = $sanphamTuongtu->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->ten,
                'slug' => $item->slug,
                'have_gift' => $query->have_gift ?? false,
                // 'giftProgramId' => optional(
                //     $item->bienthe
                //         ->flatMap(fn($bt) => $bt->quatangsukien)
                //         ->first()
                //         ->chuongtrinh ?? null
                // )->id,
                'originalPrice' => (int)optional($item->bienthe->where('giagoc', '>', 0)->sortBy('giagoc')->first())->giagoc,
                'discount' => (int)$item->giamgia,
                'sold' => (int)$item->total_sold,
                'rating' => round($item->avg_rating, 1),
                'brand' => $item->thuonghieu->ten ?? null,
                'categories' => $item->danhmuc->pluck('ten')->toArray(),
                'image' => $item->hinhanhsanpham->first()->hinhanh ?? null,
            ];
        })->toArray();
        // $resource = [
        //     'chitiet' => $array,
        //     'ds_sanpham_tuongtu' => $sanphamTuongtuArray
        // ];
        // return $resource;
        return response()->json([
            $array,
            $sanphamTuongtuArray
        ], 200);
    }
}




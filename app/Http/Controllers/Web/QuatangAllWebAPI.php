<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Resources\Frontend\QuatangAllResource;
use App\Http\Resources\Frontend\QuatangResource;
use App\Http\Resources\Frontend\SanphamCoQuatangCoBientheDeThemVaoGioResource;
use App\Models\QuatangsukienModel;
use App\Models\SanphamModel;
use App\Models\ThuongHieuModel;

use App\Http\Resources\Web\QuatangAllGroupResource;
use App\Http\Resources\Web\QuatangGroupResource;

class QuatangAllWebAPI extends Controller
{

    public function index(Request $request)
    {
        $popular = $request->get('popular', null);
        $newest = $request->get('newest', null);
        $expiring = $request->get('expiring', null);
        $provider = $request->get('provider', null);
        $limit = 5; // đang theo Khải


        $quatangs = QuatangsukienModel::query()
                ->with([
                'bienthe',
                'bienthe.sanpham',
                'bienthe.sanpham.thuonghieu'
            ]);
        $hasFilter = false;
        if ($popular) {
            if($popular === "popular"){
                $fromDate = now()->subDays(4)->toDateString(); // 4 vì dưới nó là 2,3,4 suy ra đây phải là 4 // hên xui
                // $fromDate = now()->subDays(7)->toDateString();
                $toDate = now()->toDateString();
                $quatangs->whereDate('ngaybatdau', '<=', $toDate)
                        ->whereDate('ngayketthuc', '>=', $fromDate);
                $quatangs->orderBy('luotxem', 'desc');
            }
            $hasFilter = true;
        }
        if ($newest) {
            if($newest === "newest"){
                $quatangs->orderBy('id', 'desc');
            }
            $hasFilter = true;
        }
        if ($expiring) {
            if($expiring === "expiring"){
                $today = now()->toDateString(); // YYYY-MM-DD
                $soon = now()->addDays(4)->toDateString(); // 4 vì dưới nó là 2,3,4 suy ra đây phải là 4// hên xui
                $quatangs->where(function ($query) use ($today, $soon) {
                    $query->whereDate('ngayketthuc', '>=', $today)  // chưa hết hạn
                        ->whereDate('ngayketthuc', '<=', $soon); // sắp tới hạn
                });
                // 🔥 Sắp xếp theo ngày kết thúc gần nhất → xa nhất
                $quatangs->orderBy('ngayketthuc', 'desc'); // đang theo Khải 2 ngày 3 ngày 4 ngày
                // 🔥 Sắp xếp theo ngày kết thúc xa nhất → gần nhất
                // $quatangs->orderBy('ngayketthuc', 'asc');
            }
            $hasFilter = true;
        }
        if ($provider) {
            $quatangs->whereHas('bienthe.sanpham.thuonghieu', function ($query) use ($provider) {
                $query->where('id', $provider);
            });
            $quatangs->orderBy('id', 'desc');
            $hasFilter = true;
        }
        if (!$hasFilter) {
            // $today = now()->toDateString();
            // $soon = now()->addDays(4)->toDateString();
            // $quatangs->where(function ($query) use ($today, $soon) {
            //     $query->whereDate('ngayketthuc', '>=', $today)
            //         ->whereDate('ngayketthuc', '<=', $soon);
            // });
            // $quatangs->orderBy('ngayketthuc', 'asc'); // đang theo Khải 4 ngày 2 ngày 1 ngày

            $quatangs->orderBy('id', 'desc');
        }

        $result = $quatangs->paginate($limit);

        $filterMenu = $this->getMenuFilterAsideInQuaTang();

        // return $this->jsonResponse([
        //     'data' => QuatangAllResource::collection($result->items()),
        //     'filters' => $filterMenu,
        //     'pagination' => [
        //         'current_page' => $result->currentPage(),
        //         'last_page' => $result->lastPage(),
        //         'per_page' => $result->perPage(),
        //         'total' => $result->total(),
        //     ],
        // ]);

        QuatangAllResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json([
            [
                'items' => QuatangAllResource::collection($result->items()),
            ],
            [
                'filters' => $filterMenu,
                'pagination' => [
                    'current_page' => $result->currentPage(),
                    'last_page' => $result->lastPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                ],
            ]
        ], Response::HTTP_OK);
    }


    public function getMenuFilterAsideInQuaTang()
    {
        $now = now()->toDateString();

        $thuonghieus = ThuongHieuModel::whereHas('sanpham.bienthe.quatangsukien', function ($query) use ($now) {
            $query->where('trangthai', 'Hiển thị')
                ->whereDate('ngaybatdau', '<=', $now)
                ->whereDate('ngayketthuc', '>=', $now)
                ->whereNull('deleted_at');
        })
        ->get(['id', 'ten']);
        // ->get(['id', 'ten', 'slug']);

        $expiring = ['label' => 'Sắp hết hạn','param' => 'expiring','value' => 'expiring'];
        $newest = ['label' => 'Mới nhất','param' => 'newest','value' => 'newest'];
        $popular = ['label' => 'Phổ biến','param' => 'popular','value' => 'popular'];

        return ([
            'popular' => $popular,
            'newest' => $newest,
            'expiring' => $expiring,
            'thuonghieus' => $thuonghieus,
        ]);
    }


    public function show(string $id)
    {
        if (is_numeric($id)) {
            // $quatang = QuatangsukienModel::where('id', $id)->first(); // firstOrFail() 404 luôn
            $quatang = QuatangsukienModel::with([
                'bienthe',
                'bienthe.sanpham',
                'bienthe.loaibienthe',
                'bienthe.sanpham.hinhanhsanpham',
                'bienthe.sanpham.thuonghieu'
            ])->where('id', $id)->first();
        } else {
            // Nếu $id không phải số → xem nó là slug

            $slug = $id;
            // $quatang = QuatangsukienModel::get()
            // ->first(function ($item) use ($slug) {
            //     return Str::slug($item->tieude) === $slug;
            // });
            $quatang = QuatangsukienModel::with([
                'bienthe',
                'bienthe.sanpham',
                'bienthe.loaibienthe',
                'bienthe.sanpham.hinhanhsanpham',
                'bienthe.sanpham.thuonghieu'
            ])->get()->first(function ($item) use ($slug) {
                return $item->slug === $slug;
            });
        }

        if (!$quatang) {
            return $this->error('Không tìm thấy quà tặng', [], 404);
        }

        $quatang->increment('luotxem');


        $sanphamCoQua = SanphamModel::whereHas('bienthe.quatangsukien', function ($q) {
                $q->where('trangthai', 'Hiển thị')
                ->whereDate('ngaybatdau', '<=', now())
                ->whereDate('ngayketthuc', '>=', now())
                ->whereNull('deleted_at');
            })
            ->with([
                'hinhanhsanpham',
                'thuonghieu',
                'danhgia',
                'danhmuc',
                'bienthe',
                'loaibienthe',
                'bienthe.loaibienthe',
                'bienthe.sanpham'
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
            ->limit(5)
            ->get();

            QuatangResource::withoutWrapping();
            SanphamCoQuatangCoBientheDeThemVaoGioResource::withoutWrapping();

            return response()->json([
                'quatang' => new QuatangResource($quatang),
                'sanpham_coqua' => SanphamCoQuatangCoBientheDeThemVaoGioResource::collection($sanphamCoQua),
            ], Response::HTTP_OK);

        // return $this->jsonResponse([
        //     'data' => new QuatangResource($quatang),
        //     'sanpham_coqua' => SanphamCoQuatangCoBientheDeThemVaoGioResource::collection($sanphamCoQua),
        // ]);
    }
}

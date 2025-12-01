<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\TinhThanhVietNamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TinhThanhVietNamWebApi extends Controller
{

    protected $provinces;
    public function __construct()
    {
        $this->provinces = config('tinhthanh');
    }
    /**
     * Lấy danh sách tất cả các tỉnh/thành Việt Nam.
     */
    public function index(Request $request)
    {
        // Cho phép lọc theo vùng (khuvuc) nếu có query param ?khuvuc=Đông Nam Bộ
        $khuvuc = $request->query('khuvuc');

        $provinces = $this->provinces;

        if ($khuvuc) {
            $provinces = array_filter($provinces, function ($province) use ($khuvuc) {
                return stripos($province['khuvuc'], $khuvuc) !== false;
            });
        }
        $resoult =array_values($provinces);

        TinhThanhVietNamResource::withoutWrapping(); // Bỏ "data" bọc ngoài
        return response()->json(TinhThanhVietNamResource::collection($resoult), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

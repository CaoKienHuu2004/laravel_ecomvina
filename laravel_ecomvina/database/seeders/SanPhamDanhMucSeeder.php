<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SanPhamDanhMucSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $sanPhamIds = DB::table('san_pham')->pluck('id')->toArray();
        $danhMucIds = DB::table('danh_muc')->pluck('id')->toArray();

        $data = [];
        $pairs = [];

        while (count($data) < 10) {
            $sp = Arr::random($sanPhamIds);
            $dm = Arr::random($danhMucIds);
            $pairKey = $sp . '-' . $dm;

            if (!isset($pairs[$pairKey])) {
                $pairs[$pairKey] = true;
                $data[] = [
                    'id_sanpham' => $sp,
                    'id_danhmuc' => $dm,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('sanpham_danhmuc')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SanPhamDanhMucSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả ID sản phẩm và danh mục
        $sanPhamIds = DB::table('san_pham')->pluck('id')->toArray();
        $danhMucIds = DB::table('danh_muc')->pluck('id')->toArray();

        $data = [];

        // Tạo 10 quan hệ ngẫu nhiên
        for ($i = 0; $i < 10; $i++) {
            $sp = Arr::random($sanPhamIds);
            $dm = Arr::random($danhMucIds);

            // Kiểm tra để không duplicate
            if (!in_array(['id_sanpham' => $sp, 'id_danhmuc' => $dm], $data)) {
                $data[] = [
                    'id_sanpham' => $sp,
                    'id_danhmuc' => $dm,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('sanpham_danhmuc')->insert($data);
    }
}

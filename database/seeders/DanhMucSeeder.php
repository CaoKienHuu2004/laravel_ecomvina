<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DanhMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'ten' => 'Sức khỏe',
                'slug' => 'suc-khoe',
                'logo' => 'suc-khoe.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Thực phẩm chức năng',
                'slug' => 'thuc-pham-chuc-nang',
                'logo' => 'thuc-pham-chuc-nang.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Chăm sóc cá nhân',
                'slug' => 'cham-soc-ca-nhan',
                'logo' => 'cham-soc-ca-nhan.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Làm đẹp',
                'slug' => 'lam-dep',
                'logo' => 'lam-dep.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Điện máy',
                'slug' => 'dien-may',
                'logo' => 'dien-may.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Thiết bị y tế',
                'slug' => 'thiet-bi-y-te',
                'logo' => 'thiet-bi-y-te.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Bách hóa',
                'slug' => 'bach-hoa',
                'logo' => 'bach-hoa.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Nội thất - Trang trí',
                'slug' => 'noi-that-trang-tri',
                'logo' => 'noi-that-trang-tri.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Mẹ & bé',
                'slug' => 'me-va-be',
                'logo' => 'me-va-be.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Thời trang',
                'slug' => 'thoi-trang',
                'logo' => 'thoi-trang.svg',
                'parent' => 'Cha',
                'trangthai' => 'Hiển thị',
            ],
            [
                'ten' => 'Thực phẩm - đồ ăn',
                'slug' => 'thuc-pham-do-an',
                'logo' => 'thuc-pham-do-an.svg',
                'parent' => 'Con',
                'trangthai' => 'Hiển thị',
            ],
        ];

        DB::table('danhmuc')->insert($data);
    }
}

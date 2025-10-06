<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChuongTrinhSuKienDanhMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách id chương trình sự kiện
        $eventIds = DB::table('chuongtrinhsukien')->pluck('id')->toArray();

        // Lấy danh sách id danh mục
        $categoryIds = DB::table('danh_muc')->pluck('id')->toArray();

        $usedPairs = []; // lưu các cặp đã dùng để tránh duplicate
        $data = [];

        foreach ($eventIds as $eventId) {
            // Số danh mục gán cho mỗi sự kiện (1->5)
            $numCategories = rand(1, min(5, count($categoryIds)));

            // Lấy ngẫu nhiên danh mục mà sự kiện chưa có
            $selectedCategories = collect($categoryIds)
                ->shuffle()
                ->filter(fn($catId) => !in_array("$eventId-$catId", $usedPairs))
                ->take($numCategories);

            foreach ($selectedCategories as $catId) {
                $data[] = [
                    'id_chuongtrinhsukien' => $eventId,
                    'id_danhmuc' => $catId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $usedPairs[] = "$eventId-$catId";
            }
        }

        DB::table('chuongtrinhsukien_danhmuc')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChuongTrinhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programs = [];
        $baseContent = "Đây là nội dung chi tiết cho bài viết mẫu. Nội dung này được tạo ra để mô phỏng một bài viết đầy đủ trong bảng 'chuongtrinh' của hệ thống.";

        for ($i = 1; $i <= 10; $i++) {
            $title = "Chương Trình Khuyến Mãi Đặc Biệt Tháng " . ($i % 12 + 1);

            $programs[] = [
                'tieude' => $title,
                // Tạo slug từ tiêu đề
                'slug' => Str::slug($title),
                // Đường dẫn ảnh mẫu. Bạn nên thay thế bằng đường dẫn ảnh thực tế.
                'hinhanh' => 'chuongtrinh_sukien_' . $i . '.png',
                // Nội dung mẫu (LONGTEXT)
                'noidung' => $baseContent . "\n\nNgày công bố: " . Carbon::now()->subDays(rand(1, 30))->format('d/m/Y'),
                // Trạng thái: 8 bản ghi Hiển thị, 2 bản ghi Tạm ẩn
                'trangthai' => ($i <= 8) ? 'Hiển thị' : 'Tạm ẩn',
            ];
        }

        // Chèn 10 bản ghi vào bảng 'chuongtrinh'
        DB::table('chuongtrinh')->insert($programs);
    }
}

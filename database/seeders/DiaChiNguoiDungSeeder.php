<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiaChiNguoiDungSeeder extends Seeder
{
    public function run()
    {
        $diachi = [];
        $diachi[] = [
            'ten' => "Admin",
            // Lưu 2 số điện thoại cùng lúc
            'sodienthoai' => "0997654321,0998654321",
            'thanhpho' => 'Hồ Chí Minh',
            'xaphuong' => "Phường 1",
            'sonha' => "Số 1, Đường ABC",
            'diachi' => "Số 1, Đường ABC, Phường 1, Q.1, Thành phố Hồ Chí Minh",
            'trangthai' => 'hoat_dong',
            'id_nguoidung' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $diachi[] = [
            'ten' => "Assistant",
            // Lưu 2 số điện thoại cùng lúc
            'sodienthoai' => "0992654321,0993654321",
            'thanhpho' => 'Hồ Chí Minh',
            'xaphuong' => "Phường 1",
            'sonha' => "Số 1, Đường ABC",
            'diachi' => "Số 1, Đường ABC, Phường 1, Q.1, Thành phố Hồ Chí Minh",
            'trangthai' => 'hoat_dong',
            'id_nguoidung' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $diachi[] = [
            'ten' => "Anonymous",
            // Lưu 2 số điện thoại cùng lúc
            'sodienthoai' => "0997654321,0998654321",
            'thanhpho' => 'Hồ Chí Minh',
            'xaphuong' => "Phường 1",
            'sonha' => "Số 1, Đường ABC",
            'diachi' => "Số 1, Đường ABC, Phường 1, Q.1, Thành phố Hồ Chí Minh",
            'trangthai' => 'hoat_dong',
            'id_nguoidung' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        for ($i = 4; $i <= 70; $i++) {
            $diachi[] = [
                'ten' => "Nguyễn Văn A $i",
                // Lưu 2 số điện thoại cùng lúc
                'sodienthoai' => "098765432$i,098565432$i",
                'thanhpho' => $i % 2 == 0 ? 'Hà Nội' : 'Hồ Chí Minh',
                'xaphuong' => "Phường $i",
                'sonha' => "Số $i, Đường ABC",
                'diachi' => "Số $i, Đường ABC, Phường $i, Q.$i, Thành phố " . ($i % 2 == 0 ? 'Hà Nội' : 'Hồ Chí Minh'),
                'trangthai' => 'hoat_dong',
                'id_nguoidung' => $i, // giả sử bảng nguoi_dung đã có user với id từ 2–70
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('diachi_nguoidung')->insert($diachi);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuKienKhuyenMaiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách id từ 2 bảng liên quan
        $khuyenmaiIds = DB::table('quatang_khuyenmai')->pluck('id')->toArray();
        $sukienIds = DB::table('chuongtrinhsukien')->pluck('id')->toArray();

        $data = [];
        $usedPairs = []; // để tránh trùng (id_khuyenmai, id_sukien)

        for ($i = 0; $i < 15; $i++) { // tạo 15 bản ghi
            $id_khuyenmai = $khuyenmaiIds[array_rand($khuyenmaiIds)];
            $id_sukien = $sukienIds[array_rand($sukienIds)];

            $pair = $id_khuyenmai . '-' . $id_sukien;

            // nếu cặp này đã tồn tại thì bỏ qua, random lại
            if (in_array($pair, $usedPairs)) {
                $i--;
                continue;
            }

            $usedPairs[] = $pair;

            $data[] = [
                'id_khuyenmai' => $id_khuyenmai,
                'id_sukien'    => $id_sukien,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('sukien_khuyenmai')->insert($data);
    }
}

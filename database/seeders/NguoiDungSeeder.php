<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class NguoiDungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Thời gian hiện tại GMT+7
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $users = [
            [
                "email" => "admin@example.com",
                "password" => Hash::make("admin1234"),
                // "avatar" => "https://multiavatar.com/api/admin.png",
                "hoten" => "Admin",
                "gioitinh" => "nam",
                "ngaysinh" => "2000-12-13", // sửa hợp lệ
                "sodienthoai" => "0997654321",
                "vaitro" => "admin",
                "trangthai" => "hoat_dong",
                "created_at" => $now,
                "updated_at" => $now,
            ],
            [
                "email" => "seller@example.com",
                "password" => Hash::make("assistant1234"),
                // "avatar" => "https://multiavatar.com/api/assistant.png",
                "hoten" => "seller",
                "gioitinh" => "nam",
                "ngaysinh" => "2000-11-11", // sửa hợp lệ
                "sodienthoai" => "0991654321",
                "vaitro" => "seller",
                "trangthai" => "hoat_dong",
                "created_at" => $now,
                "updated_at" => $now,
            ],
        ];



        for ($i = 3; $i <= 20; $i++) {
            $month = ($i % 12) + 1; // 1 -> 12
            $day   = ($i % 28) + 1; // 1 -> 28 (tránh ngày sai)

            $users[] = [
                "email" => "user$i@example.com",
                "password" => Hash::make("password123"),
                // "avatar" => "https://i.pravatar.cc/150?img=$i",
                "hoten" => "User $i",
                "gioitinh" => $i % 2 == 0 ? "nam" : "nữ",
                "ngaysinh" => sprintf("2000-%02d-%02d", $month, $day),
                "sodienthoai" => "098765432$i",
                "vaitro" => "user",
                "trangthai" => "hoat_dong",
                "created_at" => $now,
                "updated_at" => $now,
            ];
        }

        for ($i = 21; $i <= 31; $i++) {
            $month = ($i % 12) + 1; // 1 -> 12
            $day   = ($i % 28) + 1; // 1 -> 28 (tránh ngày sai)

            $users[] = [
                "email" => "user$i@example.com",
                "password" => Hash::make("password123"),
                // "avatar" => "https://i.pravatar.cc/150?img=$i",
                "hoten" => "User $i",
                "gioitinh" => $i % 2 == 0 ? "nam" : "nữ",
                "ngaysinh" => sprintf("2000-%02d-%02d", $month, $day),
                "sodienthoai" => "098765432$i",
                "vaitro" => "seller",
                "trangthai" => "hoat_dong",
                "created_at" => $now,
                "updated_at" => $now,
            ];
        }

        // Insert vào DB
        DB::table('nguoi_dung')->insert($users);
    }
}

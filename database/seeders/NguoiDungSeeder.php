<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $users = [
            [
                "username" => "Admin",
                "email" => "admin@example.com",
                "password" => Hash::make("admin1234"),
                "avatar" => "https://multiavatar.com/api/admin.png",
                "hoten" => "Admin",
                "gioitinh" => "nam",
                "ngaysinh" => "1990-01-01",
                "sodienthoai" => "0997654321",
                "vaitro" => "admin",
                "trangthai" => "hoat_dong",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];

        $users = [
            [
                "username" => "Assistant",
                "email" => "assistant@example.com",
                "password" => Hash::make("assistant1234"),
                "avatar" => "https://multiavatar.com/api/assistant.png",
                "hoten" => "Assistant",
                "gioitinh" => "nam",
                "ngaysinh" => "1990-02-02",
                "sodienthoai" => "0991654321",
                "vaitro" => "assistant",
                "trangthai" => "hoat_dong",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];

        $users = [
            [
                "username" => "Anonymous",
                "email" => "anonymous@example.com",
                "password" => Hash::make("anonymous1234"),
                "avatar" => "https://multiavatar.com/api/anonymous.png",
                "hoten" => "Anonymous",
                "gioitinh" => "nam",
                "ngaysinh" => "1990-03-02",
                "sodienthoai" => "0992654321",
                "vaitro" => "anonymous",
                "trangthai" => "hoat_dong",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];

        //
        for ($i = 4; $i <= 70; $i++) {
            $users[] = [
                "username" => "User$i",
                "email" => "user$i@example.com",
                "password" => Hash::make("password123"),
                "avatar" => "https://i.pravatar.cc/150?img=$i",
                "hoten" => "User $i",
                "gioitinh" => $i % 2 == 0 ? "nam" : "Nữ",
                "ngaysinh" => "2000-0$i-0$i",
                "sodienthoai" => "098765432$i",
                "vaitro" => "user",
                "trangthai" => "hoat_dong",
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }



        DB::table('nguoi_dung')->insert($users);

        // for ($i = 1; $i <= 5; $i++) {
        //     User::create([
        //         'name' => "User$i",
        //         'email' => "user$i@example.com",
        //         'password' => Hash::make('password123'), // password mặc định
        //         'avatar' => "https://i.pravatar.cc/150?img=$i", // URL avatar mẫu
        //     ]);
        // }

        // // Tạo user admin riêng (tùy chọn)
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('admin123'),
        //     'avatar' => "https://i.pravatar.cc/150?img=99",
        // ]);
    }
}

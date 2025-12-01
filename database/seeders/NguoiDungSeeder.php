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
        // Xóa dữ liệu cũ (nếu có)
        DB::table('nguoidung')->delete();

        // 1️⃣ Tài khoản admin
        DB::table('nguoidung')->insert([
            'username' => 'lyhuu123',
            'password' => Hash::make('123@#'),
            'sodienthoai' => '0845381121',
            'hoten' => 'Cao Kiến Hựu',
            'gioitinh' => 'Nam',
            'ngaysinh' => '2004-10-13',
            'avatar' => 'khachhang.jpg',
            'vaitro' => 'seller',
            'trangthai' => 'Hoạt động',
        ]);

        // 2️⃣ Tài khoản seller
        DB::table('nguoidung')->insert([
            'username' => 'admindemo',
            'password' => Hash::make('admindemo'),
            'sodienthoai' => '0900000002',
            'hoten' => 'Người bán hàng',
            'gioitinh' => 'Nữ',
            'ngaysinh' => '1995-05-05',
            'avatar' => 'khachhang.jpg',
            'vaitro' => 'admin',
            'trangthai' => 'Hoạt động',
        ]);

        // 3️⃣ 8 người dùng (client)
        for ($i = 1; $i <= 8; $i++) {
            DB::table('nguoidung')->insert([
                'username' => 'user' . $i,
                'password' => Hash::make('123456'),
                'sodienthoai' => '09000000' . str_pad($i + 2, 2, '0', STR_PAD_LEFT),
                'hoten' => 'Khách hàng ' . $i,
                'gioitinh' => $i % 2 == 0 ? 'Nữ' : 'Nam',
                'ngaysinh' => '2000-01-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'avatar' => 'khachhang.jpg',
                'vaitro' => 'client',
                'trangthai' => 'Hoạt động',
            ]);
        }
    }
}

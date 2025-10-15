<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AITrainingData;
use App\Models\DanhGia;
use App\Models\SanPham;
use Illuminate\Database\Seeder;
// use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            NguoiDungSeeder::class,
            DiaChiNguoiDungSeeder::class,
            ThongTinNguoiBanHangSeeder::class,
            DanhMucSeeder::class,
            LoaiBientheSeeder::class,
            SanPhamSeeder::class,
            SanPhamDanhMucSeeder::class,
            AnhSanPhamSeeder::class,
            GioHangSeeder::class,
            BientheSpSeeder::class,
            ChuongTrinhSuKienSeeder::class,
            QuatangKhuyenMaiSeeder::class,

            MaGiamGiaSeeder::class,
            DanhGiaSeeder::class,
            // ChiTietGioHangSeeder::class,
            TuKhoaSeeder::class,
            BannerQuangCaoSeeder::class,
            YeuThichSeeder::class,
            PhuongThucThanhToanSeeder::class,
            DonHangSeeder::class,
            ChitietDonhangSeeder::class,
            ThanhToanSeeder::class,
            ThongBaoSeeder::class,
            BaiVietSeeder::class
            // ai training data
            // AITrainingData::class
        ]);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnhSanPhamSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        DB::table('anh_sanpham')->insert([
            [
                // 'media' => 'yensaonest100_70ml_2.jpg',
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'yensaonest100_70ml_3.jpg',
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-1.jpg',
                'trangthai' => 'ngung_hoat_dong',
                'id_sanpham' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-2.jpg',
                'trangthai' => 'ngung_hoat_dong',
                'id_sanpham' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'sua-tam-nuoc-hoa-duong-da-parisian-chic-for-her-265ml-3.jpg',
                'trangthai' => 'ngung_hoat_dong',
                'id_sanpham' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-1.jpg',
                'trangthai' => 'cho_duyet',
                'id_sanpham' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-2.jpg',
                'trangthai' => 'cho_duyet',
                'id_sanpham' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-3.jpg',
                'trangthai' => 'cho_duyet',
                'id_sanpham' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-4.jpg',
                'trangthai' => 'cho_duyet',
                'id_sanpham' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'ca-phe-dua-cappuccino-collagen-giup-tinh-tao-dep-da-20-goi-x-18g-5.jpg',
                'trangthai' => 'cho_duyet',
                'id_sanpham' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-1.jpg',
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-2.jpg',
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-3.jpg',
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-4.jpg',
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'media' => 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg-5.jpg',
                'trangthai' => 'hoat_dong',
                'id_sanpham' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

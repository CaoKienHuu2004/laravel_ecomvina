<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BienTheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_loaibienthe' => 1, 'id_sanpham' => 1, 'giagoc' => 270000, 'soluong' => 10, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 2, 'id_sanpham' => 2, 'giagoc' => 385000, 'soluong' => 10, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 3, 'giagoc' => 466560, 'soluong' => 10, 'luottang' => 0, 'luotban' => 23, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 4, 'giagoc' => 260000, 'soluong' => 10, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 2, 'id_sanpham' => 5, 'giagoc' => 512000, 'soluong' => 10, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 6, 'giagoc' => 270000, 'soluong' => 2, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Sắp hết hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 2, 'id_sanpham' => 9, 'giagoc' => 360000, 'soluong' => 253, 'luottang' => 0, 'luotban' => 28, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 9, 'giagoc' => 260000, 'soluong' => 5, 'luottang' => 0, 'luotban' => 2, 'trangthai' => 'Sắp hết hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 10, 'giagoc' => 795000, 'soluong' => 200, 'luottang' => 0, 'luotban' => 10, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 2, 'id_sanpham' => 11, 'giagoc' => 950000, 'soluong' => 27, 'luottang' => 0, 'luotban' => 124, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 11, 'giagoc' => 500000, 'soluong' => 5, 'luottang' => 0, 'luotban' => 10, 'trangthai' => 'Sắp hết hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 12, 'giagoc' => 330000, 'soluong' => 63, 'luottang' => 0, 'luotban' => 12, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 2, 'id_sanpham' => 12, 'giagoc' => 330000, 'soluong' => 92, 'luottang' => 0, 'luotban' => 72, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 3, 'id_sanpham' => 12, 'giagoc' => 512000, 'soluong' => 12, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 4, 'id_sanpham' => 13, 'giagoc' => 90000, 'soluong' => 240, 'luottang' => 7, 'luotban' => 142, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 2, 'id_sanpham' => 14, 'giagoc' => 369000, 'soluong' => 75, 'luottang' => 20, 'luotban' => 472, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 5, 'id_sanpham' => 15, 'giagoc' => 282000, 'soluong' => 25, 'luottang' => 0, 'luotban' => 782, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 6, 'id_sanpham' => 15, 'giagoc' => 282000, 'soluong' => 23, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 3, 'id_sanpham' => 16, 'giagoc' => 249000, 'soluong' => 2, 'luottang' => 0, 'luotban' => 187, 'trangthai' => 'Sắp hết hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 3, 'id_sanpham' => 17, 'giagoc' => 220800, 'soluong' => 12, 'luottang' => 0, 'luotban' => 17, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 8, 'id_sanpham' => 18, 'giagoc' => 69000, 'soluong' => 76, 'luottang' => 5, 'luotban' => 76, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 3, 'id_sanpham' => 19, 'giagoc' => 160000, 'soluong' => 1214, 'luottang' => 0, 'luotban' => 67, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 9, 'id_sanpham' => 20, 'giagoc' => 490000, 'soluong' => 107, 'luottang' => 0, 'luotban' => 193, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 9, 'id_sanpham' => 21, 'giagoc' => 799000, 'soluong' => 123, 'luottang' => 1, 'luotban' => 3, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 1, 'id_sanpham' => 22, 'giagoc' => 42000, 'soluong' => 7, 'luottang' => 0, 'luotban' => 3, 'trangthai' => 'Sắp hết hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 9, 'id_sanpham' => 23, 'giagoc' => 290000, 'soluong' => 100, 'luottang' => 0, 'luotban' => 74, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 8, 'id_sanpham' => 22, 'giagoc' => 89000, 'soluong' => 123, 'luottang' => 0, 'luotban' => 3, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 10, 'id_sanpham' => 24, 'giagoc' => 699000, 'soluong' => 20, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 11, 'id_sanpham' => 25, 'giagoc' => 690000, 'soluong' => 24, 'luottang' => 0, 'luotban' => 20, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 7, 'id_sanpham' => 26, 'giagoc' => 560000, 'soluong' => 123, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 9, 'id_sanpham' => 27, 'giagoc' => 89000, 'soluong' => 12, 'luottang' => 0, 'luotban' => 0, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
            ['id_loaibienthe' => 8, 'id_sanpham' => 26, 'giagoc' => 799000, 'soluong' => 87, 'luottang' => 25, 'luotban' => 123, 'trangthai' => 'Còn hàng', 'deleted_at' => null],
        ];

        DB::table('bienthe')->insert($data);
    }
}

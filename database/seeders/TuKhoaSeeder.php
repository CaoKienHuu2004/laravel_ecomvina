<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TuKhoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tukhoa')->insert([
            ['id' => 1,  'tukhoa' => 'Máy massage',          'luottruycap' => 5],
            ['id' => 2,  'tukhoa' => 'Điện gia dụng',        'luottruycap' => 1],
            ['id' => 3,  'tukhoa' => 'Đồ chơi minecraft',    'luottruycap' => 153],
            ['id' => 4,  'tukhoa' => 'Sách hán ngữ 3',       'luottruycap' => 597],
            ['id' => 5,  'tukhoa' => 'Huyndai decor',        'luottruycap' => 62],
            ['id' => 6,  'tukhoa' => 'Điện nội thất',        'luottruycap' => 125],
            ['id' => 7,  'tukhoa' => 'Móc khóa genshin',     'luottruycap' => 246],
            ['id' => 8,  'tukhoa' => 'Phiền Muộn Của Afratu','luottruycap' => 13],
            ['id' => 9,  'tukhoa' => 'Kẹo',                  'luottruycap' => 50],
            ['id' => 10, 'tukhoa' => 'Sâm Ngọc Linh',        'luottruycap' => 626],
            ['id' => 11, 'tukhoa' => 'Thầy Hộ',              'luottruycap' => 1],
            ['id' => 12, 'tukhoa' => 'y tế',                 'luottruycap' => 1],
            ['id' => 13, 'tukhoa' => 'abena',                'luottruycap' => 77],
            ['id' => 14, 'tukhoa' => 'a',                    'luottruycap' => 39],
            ['id' => 15, 'tukhoa' => 'abena pad',            'luottruycap' => 6],
            ['id' => 16, 'tukhoa' => 'thiết bị y tế',        'luottruycap' => 3],
            ['id' => 17, 'tukhoa' => 'cchoi',                'luottruycap' => 1],
            ['id' => 18, 'tukhoa' => "c'choi",               'luottruycap' => 2],
        ]);
    }
}

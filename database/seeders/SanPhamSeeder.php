<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SanPhamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại để tránh lỗi khi truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sanpham')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $sanphams = [
            [
                'id_thuonghieu' => 1,
                'ten' => 'Keo ong xanh Tracybee Propolis Mint & Honey – Giảm đau họng tự nhiên',
                'mota' => 'Giải pháp kháng khuẩn tự nhiên từ keo ong xanh Brazil kết hợp bạc hà và mật ong.',
                'xuatxu' => 'Brazil',
                'sanxuat' => 'Nhập khẩu chính ngạch bởi Siêu Thị Vina',
                'trangthai' => 'Công khai',
                'giamgia' => 10,
                'luotxem' => 0,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Mật ong Tây Bắc đông trùng hạ thảo X3 (Hũ 240g)',
                'mota' => 'Sản phẩm kết hợp tinh túy mật ong Tây Bắc cùng đông trùng hạ thảo tự nhiên.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 20,
                'luotxem' => 0,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Sâm Ngọc Linh trường sinh đỏ (Thùng 24 lon)',
                'mota' => 'Tinh hoa dược liệu Việt, tăng cường sức khỏe và sức đề kháng.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 1,
            ],
            [
                'id_thuonghieu' => 2,
                'ten' => "Dưỡng mi tế bào gốc C’Choi - Bio Placenta Lash Serum",
                'mota' => 'Sản phẩm dưỡng mi cao cấp chứa tinh chất Bio-Placenta giúp mi mọc dài và dày hơn.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 30,
                'luotxem' => 23,
            ],
            [
                'id_thuonghieu' => 3,
                'ten' => 'Collagen thủy phân hỗ trợ Da Móng Tóc Acaci Labs Max Colla',
                'mota' => 'Collagen thủy phân từ cá biển sâu giúp cải thiện da, móng, tóc và chống lão hóa.',
                'xuatxu' => 'Australia',
                'sanxuat' => 'Australia',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 68,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Tinh dầu tràm tự nhiên ECO - Hỗ trợ giảm ho, cảm cúm',
                'mota' => 'Chiết xuất 100% từ lá tràm thiên nhiên, an toàn cho trẻ nhỏ và phụ nữ mang thai.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 85,
                'luotxem' => 0,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Sữa non tổ yến Papamilk Height & Gain giúp tăng cân và chiều cao',
                'mota' => 'Sản phẩm hỗ trợ phát triển chiều cao và cân nặng cho trẻ em và thanh thiếu niên.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 20,
                'luotxem' => 0,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Hũ Hít Thảo Dược Nhị Thiên Đường - Hũ 5g',
                'mota' => 'Thương hiệu hơn 100 năm, giúp thông mũi, giảm đau đầu, chóng mặt hiệu quả.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 26,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Máy Xông Khí Dung Cầm Tay Kachi YS35: Giải Pháp Hô Hấp Hiệu Quả',
                'mota' => 'Thiết bị y tế tiện lợi hỗ trợ điều trị các bệnh về đường hô hấp.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 854,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Nước rửa chén sả chanh COME ON làm sạch bát đĩa, an toàn da tay',
                'mota' => 'Sản phẩm rửa chén chiết xuất thiên nhiên, bảo vệ da tay và làm sạch vượt trội.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 67,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Keo ong xanh Tracybee Propolis Mint & Honey – Giảm đau họng',
                'slug' => 'keo-ong-xanh-tracybee-propolis-mint-honey-giam-dau-hong',
                'mota' => 'Bạn đang tìm kiếm giải pháp kháng khuẩn tự nhiên và giảm đau họng hiệu quả? Keo ong xanh Tracybee là lựa chọn lý tưởng.',
                'xuatxu' => 'Brazil',
                'sanxuat' => 'Nhập khẩu chính ngạch bởi Siêu Thị Vina',
                'trangthai' => 'Công khai',
                'giamgia' => 10,
                'luotxem' => 0,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Mật ong Tây Bắc đông trùng hạ thảo X3 (Hũ 240g)',
                'slug' => 'mat-ong-tay-bac-dong-trung-ha-thao-x3-hu-240g',
                'mota' => 'Mật ong Tây Bắc Đông Trùng Hạ Thảo X3 là siêu phẩm kết hợp dưỡng chất quý hiếm.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 20,
                'luotxem' => 0,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Sâm Ngọc Linh trường sinh đỏ (Thùng 24 lon)',
                'slug' => 'sam-ngoc-linh-truong-sinh-do-thung-24lon',
                'mota' => 'Sâm Ngọc Linh Trường Sinh Đỏ là tinh hoa của dược liệu Việt Nam.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 1,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Tinh dầu tràm tự nhiên ECO - Hỗ trợ giảm ho, cảm cúm',
                'slug' => 'tinh-dau-tram-tu-nhien-eco-ho-tro-giam-ho-cam-cum',
                'mota' => 'Tinh Dầu Tràm Tự Nhiên ECO là sản phẩm chiết xuất từ lá tràm tự nhiên giúp giảm ho, cảm lạnh hiệu quả.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 85,
                'luotxem' => 0,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Sữa non tổ yến Papamilk Height & Gain giúp tăng cân, tăng chiều cao',
                'slug' => 'sua-non-to-yen-papamilk-height-gain-giup-tang-can-tang-chieu-cao',
                'mota' => 'Sữa Non Tổ Yến Papamilk Height & Gain là công thức dinh dưỡng giúp bé phát triển toàn diện.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 20,
                'luotxem' => 0,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'hahaha',
                'slug' => 'hahahaha',
                'mota' => 'ádasdasd',
                'xuatxu' => 'ss',
                'sanxuat' => 'ss',
                'trangthai' => 'Công khai',
                'giamgia' => 20,
                'luotxem' => 1,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Thực phẩm bảo vệ sức khỏe: Midu MenaQ7 180mcg',
                'slug' => 'thuc-pham-bao-ve-suc-khoe-midu-menaq7-180mcg',
                'mota' => 'Midu MenaQ7 bổ sung canxi, Vitamin D3, K2 hỗ trợ xương chắc khỏe.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 10,
                'luotxem' => 26,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 3,
                'ten' => 'Collagen thủy phân hỗ trợ Da Móng Tóc Acaci Labs Marine',
                'slug' => 'collagen-thuy-phan-ho-tro-da-mong-toc-acai-labs-marine',
                'mota' => 'Acaci Labs, thương hiệu từ Australia, mang đến dòng collagen thủy phân cao cấp.',
                'xuatxu' => 'Australia',
                'sanxuat' => 'Australia',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 68,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 2,
                'ten' => 'Dưỡng mi tế bào gốc C’Choi - Bio Placenta Lash Serum',
                'slug' => 'duong-mi-te-bao-goc-cchoi-bio-placenta-lash-serum',
                'mota' => 'Dưỡng mi tế bào gốc C’Choi giúp mi dài và dày hơn chỉ sau 2 tuần sử dụng.',
                'xuatxu' => 'Việt Nam',
                'sanxuat' => 'Việt Nam',
                'trangthai' => 'Công khai',
                'giamgia' => 30,
                'luotxem' => 23,
                'deleted_at' => null,
            ],
            [
                'id_thuonghieu' => 1,
                'ten' => 'Nước rửa bát Bio Formula - Bơ và Lô Hội (Túi 500ml)',
                'slug' => 'nuoc-rua-bat-bio-formula-bo-va-lo-hoi-tui-500ml',
                'mota' => 'Chiết xuất lô hội giúp làm dịu da tay, sạch bóng bát đĩa.',
                'xuatxu' => 'Ukraine',
                'sanxuat' => 'Ukraine',
                'trangthai' => 'Công khai',
                'giamgia' => 0,
                'luotxem' => 1200,
                'deleted_at' => null,
            ]
        ];

        // Chuẩn bị dữ liệu có slug
        $data = [];
        foreach ($sanphams as $sp) {
            $data[] = [
                'id_thuonghieu' => $sp['id_thuonghieu'],
                'ten' => $sp['ten'],
                'slug' => Str::slug($sp['ten']),
                'mota' => $sp['mota'],
                'xuatxu' => $sp['xuatxu'],
                'sanxuat' => $sp['sanxuat'],
                'trangthai' => $sp['trangthai'],
                'giamgia' => $sp['giamgia'],
                'luotxem' => $sp['luotxem'],
                'deleted_at' => null,
            ];
        }

        DB::table('sanpham')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ThuongHieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ (nếu có)
        DB::table('thuonghieu')->delete();

        $part = "uploads/thuonghieu/"; // lý ra phải $part.'hinhanh'; mà thôi vậy cũng được

        // Danh sách 20 thương hiệu phổ biến trên các sàn TMĐT
        $brands = [
            [
                'ten' => 'Trung Tâm Bán Hàng Siêu Thị Vina',
                'logo' => 'trung-tam-ban-hang-sieu-thi-vina.png',
                'mota' => 'Thương hiệu nội địa nổi tiếng cung cấp đa dạng mặt hàng tiêu dùng và điện tử.',
            ],
            [
                'ten' => "C'CHOI",
                'logo' => 'thuonghieu-logo.png',
                'mota' => 'Thương hiệu thời trang năng động, hướng đến giới trẻ hiện đại.',
            ],
            [
                'ten' => 'ACACI LABS',
                'logo' => 'thuonghieu-logo.png',
                'mota' => 'Chuyên về mỹ phẩm thiên nhiên và chăm sóc da an toàn, lành tính.',
            ],
            [
                'ten' => 'Samsung',
                'logo' => 'samsung.png',
                'mota' => 'Thương hiệu công nghệ hàng đầu thế giới đến từ Hàn Quốc.',
            ],
            [
                'ten' => 'Apple',
                'logo' => 'apple.png',
                'mota' => 'Nhà sản xuất thiết bị công nghệ cao cấp nổi tiếng toàn cầu.',
            ],
            [
                'ten' => 'Nike',
                'logo' => 'nike.png',
                'mota' => 'Thương hiệu thể thao hàng đầu với phong cách trẻ trung, năng động.',
            ],
            [
                'ten' => 'Adidas',
                'logo' => 'adidas.png',
                'mota' => 'Hãng thể thao nổi tiếng của Đức, được yêu thích trên toàn thế giới.',
            ],
            [
                'ten' => 'Xiaomi',
                'logo' => 'xiaomi.png',
                'mota' => 'Thương hiệu công nghệ giá rẻ chất lượng cao, chuyên về smartphone và thiết bị IoT.',
            ],
            [
                'ten' => 'LG',
                'logo' => 'lg.png',
                'mota' => 'Tập đoàn điện tử hàng đầu Hàn Quốc, nổi bật với thiết bị gia dụng.',
            ],
            [
                'ten' => 'Omo',
                'logo' => 'omo.png',
                'mota' => 'Thương hiệu bột giặt và sản phẩm giặt tẩy nổi tiếng thuộc tập đoàn Unilever.',
            ],
            [
                'ten' => 'Unilever',
                'logo' => 'unilever.png',
                'mota' => 'Tập đoàn đa quốc gia chuyên về sản phẩm tiêu dùng nhanh với hàng trăm nhãn hàng nổi tiếng.',
            ],
            [
                'ten' => 'Panasonic',
                'logo' => 'panasonic.png',
                'mota' => 'Hãng điện tử Nhật Bản nổi tiếng với các sản phẩm gia dụng và công nghệ hiện đại.',
            ],
            [
                'ten' => 'Sony',
                'logo' => 'sony.png',
                'mota' => 'Tập đoàn điện tử hàng đầu Nhật Bản chuyên về tivi, âm thanh và thiết bị giải trí.',
            ],
            [
                'ten' => 'Gucci',
                'logo' => 'gucci.png',
                'mota' => 'Thương hiệu thời trang xa xỉ đến từ Ý, nổi tiếng với phong cách đẳng cấp và tinh tế.',
            ],
            [
                'ten' => 'Zara',
                'logo' => 'zara.png',
                'mota' => 'Thương hiệu thời trang nhanh nổi tiếng của Tây Ban Nha, hướng đến phong cách trẻ trung.',
            ],
            [
                'ten' => 'Oppo',
                'logo' => 'oppo.png',
                'mota' => 'Hãng điện thoại thông minh đến từ Trung Quốc, nổi bật với camera selfie và thiết kế đẹp.',
            ],
            [
                'ten' => 'Puma',
                'logo' => 'puma.png',
                'mota' => 'Thương hiệu thể thao toàn cầu, cung cấp giày, quần áo và phụ kiện năng động.',
            ],
            [
                'ten' => 'Maybelline',
                'logo' => 'maybelline.png',
                'mota' => 'Thương hiệu mỹ phẩm nổi tiếng của Mỹ, chuyên về trang điểm và làm đẹp.',
            ],
            [
                'ten' => 'Vinamilk',
                'logo' => 'vinamilk.png',
                'mota' => 'Thương hiệu sữa hàng đầu Việt Nam với mạng lưới phân phối trên toàn quốc.',
            ],
            [
                'ten' => 'Highlands Coffee',
                'logo' => 'highlands.png',
                'mota' => 'Thương hiệu cà phê nổi tiếng của Việt Nam, mang phong cách hiện đại và đậm đà hương vị Việt.',
            ],
        ];

        // Chuẩn bị dữ liệu có slug, trạng thái mặc định
        $data = [];
        foreach ($brands as $brand) {
            $data[] = [
                'ten' => $brand['ten'],
                'slug' => Str::slug($brand['ten']),
                'logo' => $brand['logo'],
                'mota' => $brand['mota'],
                'trangthai' => 'Hoạt động',
            ];
        }

        // Chèn dữ liệu vào DB
        DB::table('thuonghieu')->insert($data);
    }
}

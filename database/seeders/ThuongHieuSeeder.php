<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThuongHieuSeeder extends Seeder
{
    public function run(): void
    {
        $thuongHieu = [
            // Sức Khỏe
            ['ten' => 'Vinamilk', 'mota' => 'Thương hiệu sữa và dinh dưỡng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Abbott', 'mota' => 'Dinh dưỡng và thực phẩm chức năng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Nestle', 'mota' => 'Sữa và sản phẩm dinh dưỡng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Mead Johnson', 'mota' => 'Dinh dưỡng cho trẻ em', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Procare', 'mota' => 'Thực phẩm chức năng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Ensure', 'mota' => 'Dinh dưỡng người lớn', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'VitaDairy', 'mota' => 'Sữa tươi và sản phẩm bổ sung', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Dielac', 'mota' => 'Sữa bột cho trẻ em', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'FrieslandCampina', 'mota' => 'Sữa và thực phẩm dinh dưỡng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Life Nutrition', 'mota' => 'Thực phẩm bổ sung sức khỏe', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Chăm sóc cá nhân
            ['ten' => 'P&G', 'mota' => 'Hàng tiêu dùng chăm sóc cá nhân', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Unilever', 'mota' => 'Sản phẩm vệ sinh và chăm sóc cá nhân', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'L’Oreal', 'mota' => 'Mỹ phẩm và chăm sóc tóc', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Colgate', 'mota' => 'Kem đánh răng và chăm sóc răng miệng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Dove', 'mota' => 'Xà phòng và chăm sóc cơ thể', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Nivea', 'mota' => 'Sản phẩm dưỡng da', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Garnier', 'mota' => 'Mỹ phẩm và dưỡng da', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Pantene', 'mota' => 'Chăm sóc tóc', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Olay', 'mota' => 'Dưỡng da và mỹ phẩm', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Shiseido', 'mota' => 'Mỹ phẩm cao cấp', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Điện máy
            ['ten' => 'Samsung', 'mota' => 'Điện tử và điện thoại', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'LG', 'mota' => 'Điện tử và điện gia dụng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Sony', 'mota' => 'Điện tử, TV, âm thanh', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Panasonic', 'mota' => 'Điện tử và gia dụng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Philips', 'mota' => 'Điện tử và chăm sóc sức khỏe', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Midea', 'mota' => 'Điện gia dụng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Electrolux', 'mota' => 'Gia dụng và thiết bị điện', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Toshiba', 'mota' => 'Điện tử, máy tính, gia dụng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Asanzo', 'mota' => 'Điện tử Việt Nam', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Casper', 'mota' => 'Điện tử và thiết bị gia dụng', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Thiết bị y tế
            ['ten' => 'Omron', 'mota'=>'Thiết bị y tế gia đình', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Microlife', 'mota'=>'Máy đo huyết áp, nhiệt kế', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Beurer', 'mota'=>'Thiết bị y tế và chăm sóc sức khỏe', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Medela', 'mota'=>'Thiết bị chăm sóc mẹ và bé', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Braun', 'mota'=>'Máy đo, nhiệt kế, máy cạo', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Philips Avent', 'mota'=>'Thiết bị chăm sóc trẻ em', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => '3M', 'mota'=>'Thiết bị y tế và bảo hộ', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Hartmann', 'mota'=>'Thiết bị y tế và băng gạc', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'B Braun', 'mota'=>'Thiết bị chăm sóc sức khỏe', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten' => 'Omnison', 'mota'=>'Thiết bị y tế gia đình', 'trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Bách Hóa
            ['ten'=>'Co.opMart','mota'=>'Siêu thị bách hóa tổng hợp','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'BigC','mota'=>'Siêu thị bách hóa tổng hợp','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'VinMart','mota'=>'Chuỗi siêu thị bách hóa','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Aeon','mota'=>'Trung tâm bách hóa tổng hợp','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Lotte Mart','mota'=>'Siêu thị đa dạng sản phẩm','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Satra','mota'=>'Chuỗi siêu thị Việt Nam','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Circle K','mota'=>'Cửa hàng tiện lợi','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'7-Eleven','mota'=>'Chuỗi cửa hàng tiện lợi','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'FamilyMart','mota'=>'Cửa hàng tiện lợi','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Mini Stop','mota'=>'Cửa hàng bách hóa nhỏ lẻ','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Nhà Cửa - Đời Sống
            ['ten'=>'IKEA','mota'=>'Đồ nội thất và trang trí nhà','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'JYSK','mota'=>'Nội thất và gia dụng','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Homecenter','mota'=>'Đồ gia dụng và nội thất','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Aeon Home','mota'=>'Gia dụng và trang trí nhà','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Tiki Home','mota'=>'Đồ gia dụng, trang trí','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Lazada Home','mota'=>'Nội thất và đồ gia dụng','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Phong Cách Việt','mota'=>'Nội thất và decor','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'DecoX','mota'=>'Đồ trang trí nhà cửa','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Casa','mota'=>'Trang trí và nội thất','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'MyHome','mota'=>'Đồ gia dụng và nội thất','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Mẹ Và Bé
            ['ten'=>'Pigeon','mota'=>'Sản phẩm mẹ và bé','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Munchkin','mota'=>'Đồ chơi và chăm sóc bé','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Chicco','mota'=>'Đồ dùng trẻ em','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Combi','mota'=>'Xe đẩy và sản phẩm trẻ em','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Fisher-Price','mota'=>'Đồ chơi giáo dục trẻ em','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Dr.Brown','mota'=>'Bình sữa và phụ kiện','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Babyhug','mota'=>'Đồ dùng và quần áo trẻ em','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Aprica','mota'=>'Xe đẩy và sản phẩm mẹ bé','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Pampers','mota'=>'Tã giấy và sản phẩm vệ sinh','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Huggies','mota'=>'Tã và chăm sóc bé','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Thời Trang
            ['ten'=>'Adidas','mota'=>'Thời trang thể thao','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Zara','mota'=>'Thời trang nhanh','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'H&M','mota'=>'Thời trang phổ thông','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Uniqlo','mota'=>'Thời trang cơ bản và casual','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Gucci','mota'=>'Thời trang cao cấp','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Louis Vuitton','mota'=>'Thời trang và phụ kiện cao cấp','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Puma','mota'=>'Thời trang và giày thể thao','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Levi\'s','mota'=>'Thời trang denim và casual','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Converse','mota'=>'Giày và thời trang casual','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],

            // Sản Phẩm Khác
            // ['ten'=>'Samsung','mota'=>'Điện tử, điện thoại và thiết bị gia dụng','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Apple','mota'=>'Điện thoại, máy tính và phụ kiện','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            // ['ten'=>'Sony','mota'=>'Điện tử, giải trí và âm thanh','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            // ['ten'=>'LG','mota'=>'Thiết bị gia dụng và điện tử','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Canon','mota'=>'Máy ảnh và thiết bị hình ảnh','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'HP','mota'=>'Máy tính, laptop và thiết bị văn phòng','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Dell','mota'=>'Laptop và thiết bị máy tính','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Asus','mota'=>'Laptop, máy tính và linh kiện','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            ['ten'=>'Xiaomi','mota'=>'Điện thoại, thiết bị thông minh và phụ kiện','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
            // ['ten'=>'Philips','mota'=>'Thiết bị điện tử và gia dụng','trangthai'=>'hoat_dong','created_at'=>now(),'updated_at'=>now()],
        ];

        DB::table('thuong_hieu')->insert($thuongHieu);
    }
}

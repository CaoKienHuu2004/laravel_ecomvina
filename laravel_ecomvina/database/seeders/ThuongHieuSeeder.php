<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThuongHieuSeeder extends Seeder
{
    public function run(): void
    {

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $thuongHieu = [
            // Sức Khỏe
            ['ten' => 'Vinamilk','id_cuahang' => 1, 'mota' => 'Thương hiệu sữa và dinh dưỡng', 'media'=>'vinamilk.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Abbott','id_cuahang' => 1, 'mota' => 'Dinh dưỡng và thực phẩm chức năng', 'media'=>'abbott.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Nestle','id_cuahang' => 1, 'mota' => 'Sữa và sản phẩm dinh dưỡng', 'media'=>'nestle.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Mead Johnson','id_cuahang' => 1, 'mota' => 'Dinh dưỡng cho trẻ em', 'media'=>'mead_johnson.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Procare','id_cuahang' => 1, 'mota' => 'Thực phẩm chức năng', 'media'=>'procare.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Ensure','id_cuahang' => 1, 'mota' => 'Dinh dưỡng người lớn', 'media'=>'ensure.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'VitaDairy','id_cuahang' => 1, 'mota' => 'Sữa tươi và sản phẩm bổ sung', 'media'=>'vitadairy.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Dielac','id_cuahang' => 1, 'mota' => 'Sữa bột cho trẻ em', 'media'=>'dielac.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'FrieslandCampina','id_cuahang' => 1, 'mota' => 'Sữa và thực phẩm dinh dưỡng', 'media'=>'frieslandcampina.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Life Nutrition','id_cuahang' => 1, 'mota' => 'Thực phẩm bổ sung sức khỏe', 'media'=>'life_nutrition.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Chăm sóc cá nhân
            ['ten' => 'P&G','id_cuahang' => 2, 'mota' => 'Hàng tiêu dùng chăm sóc cá nhân', 'media'=>'pg.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Unilever','id_cuahang' => 2, 'mota' => 'Sản phẩm vệ sinh và chăm sóc cá nhân', 'media'=>'unilever.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'L’Oreal','id_cuahang' => 2, 'mota' => 'Mỹ phẩm và chăm sóc tóc', 'media'=>'loreal.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Colgate','id_cuahang' => 2, 'mota' => 'Kem đánh răng và chăm sóc răng miệng', 'media'=>'colgate.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Dove','id_cuahang' => 2, 'mota' => 'Xà phòng và chăm sóc cơ thể', 'media'=>'dove.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Nivea','id_cuahang' => 2, 'mota' => 'Sản phẩm dưỡng da', 'media'=>'nivea.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Garnier','id_cuahang' => 2, 'mota' => 'Mỹ phẩm và dưỡng da', 'media'=>'garnier.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Pantene','id_cuahang' => 2, 'mota' => 'Chăm sóc tóc', 'media'=>'pantene.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Olay','id_cuahang' => 2, 'mota' => 'Dưỡng da và mỹ phẩm', 'media'=>'olay.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Shiseido','id_cuahang' => 2, 'mota' => 'Mỹ phẩm cao cấp', 'media'=>'shiseido.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Điện máy
            ['ten' => 'Samsung','id_cuahang' => 3, 'mota' => 'Điện tử và điện thoại', 'media'=>'samsung.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'LG','id_cuahang' => 3, 'mota' => 'Điện tử và điện gia dụng', 'media'=>'lg.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Sony','id_cuahang' => 3, 'mota' => 'Điện tử, TV, âm thanh', 'media'=>'sony.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Panasonic','id_cuahang' => 3, 'mota' => 'Điện tử và gia dụng', 'media'=>'panasonic.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Philips','id_cuahang' => 3, 'mota' => 'Điện tử và chăm sóc sức khỏe', 'media'=>'philips.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Midea','id_cuahang' => 3, 'mota' => 'Điện gia dụng', 'media'=>'midea.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Electrolux','id_cuahang' => 3, 'mota' => 'Gia dụng và thiết bị điện', 'media'=>'electrolux.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Toshiba','id_cuahang' => 3, 'mota' => 'Điện tử, máy tính, gia dụng', 'media'=>'toshiba.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Asanzo','id_cuahang' => 3, 'mota' => 'Điện tử Việt Nam', 'media'=>'asanzo.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Casper','id_cuahang' => 3, 'mota' => 'Điện tử và thiết bị gia dụng', 'media'=>'casper.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Thiết bị y tế
            ['ten' => 'Omron','id_cuahang' => 4, 'mota'=>'Thiết bị y tế gia đình', 'media'=>'omron.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Microlife','id_cuahang' => 4, 'mota'=>'Máy đo huyết áp, nhiệt kế', 'media'=>'microlife.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Beurer','id_cuahang' => 4, 'mota'=>'Thiết bị y tế và chăm sóc sức khỏe', 'media'=>'beurer.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Medela','id_cuahang' => 4, 'mota'=>'Thiết bị chăm sóc mẹ và bé', 'media'=>'medela.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Braun','id_cuahang' => 4, 'mota'=>'Máy đo, nhiệt kế, máy cạo', 'media'=>'braun.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Philips Avent','id_cuahang' => 4, 'mota'=>'Thiết bị chăm sóc trẻ em', 'media'=>'philips_avent.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => '3M','id_cuahang' => 4, 'mota'=>'Thiết bị y tế và bảo hộ', 'media'=>'3m.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Hartmann','id_cuahang' => 4, 'mota'=>'Thiết bị y tế và băng gạc', 'media'=>'hartmann.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'B Braun','id_cuahang' => 4, 'mota'=>'Thiết bị chăm sóc sức khỏe', 'media'=>'bbraun.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten' => 'Omnison','id_cuahang' => 4, 'mota'=>'Thiết bị y tế gia đình', 'media'=>'omnison.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Bách Hóa
            ['ten'=>'Co.opMart','id_cuahang' => 5,'mota'=>'Siêu thị bách hóa tổng hợp','media'=>'coopmart.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'BigC','id_cuahang' => 5,'mota'=>'Siêu thị bách hóa tổng hợp','media'=>'bigc.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'VinMart','id_cuahang' => 5,'mota'=>'Chuỗi siêu thị bách hóa','media'=>'vinmart.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Aeon','id_cuahang' => 5,'mota'=>'Trung tâm bách hóa tổng hợp','media'=>'aeon.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Lotte Mart','id_cuahang' => 5,'mota'=>'Siêu thị đa dạng sản phẩm','media'=>'lotte.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Satra','id_cuahang' => 5,'mota'=>'Chuỗi siêu thị Việt Nam','media'=>'satra.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Circle K','id_cuahang' => 5,'mota'=>'Cửa hàng tiện lợi','media'=>'circlek.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'7-Eleven','id_cuahang' => 5,'mota'=>'Chuỗi cửa hàng tiện lợi','media'=>'7eleven.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'FamilyMart','id_cuahang' => 5,'mota'=>'Cửa hàng tiện lợi','media'=>'familymart.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Mini Stop','id_cuahang' => 5,'mota'=>'Cửa hàng bách hóa nhỏ lẻ','media'=>'ministop.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Nhà Cửa - Đời Sống
            ['ten'=>'IKEA','id_cuahang' => 6,'mota'=>'Đồ nội thất và trang trí nhà','media'=>'ikea.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'JYSK','id_cuahang' => 6,'mota'=>'Nội thất và gia dụng','media'=>'jysk.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Homecenter','id_cuahang' => 6,'mota'=>'Đồ gia dụng và nội thất','media'=>'homecenter.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Aeon Home','id_cuahang' => 6,'mota'=>'Gia dụng và trang trí nhà','media'=>'aeon_home.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Tiki Home','id_cuahang' => 6,'mota'=>'Đồ gia dụng, trang trí','media'=>'tiki_home.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Lazada Home','id_cuahang' => 6,'mota'=>'Nội thất và đồ gia dụng','media'=>'lazada_home.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Phong Cách Việt','id_cuahang' => 6,'mota'=>'Nội thất và decor','media'=>'phongcachviet.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'DecoX','id_cuahang' => 6,'mota'=>'Đồ trang trí nhà cửa','media'=>'decox.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Casa','id_cuahang' => 6,'mota'=>'Trang trí và nội thất','media'=>'casa.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'MyHome','id_cuahang' => 6,'mota'=>'Đồ gia dụng và nội thất','media'=>'myhome.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Mẹ Và Bé

            ['ten'=>'Pigeon','id_cuahang' => 7,'mota'=>'Sản phẩm mẹ và bé','media'=>'pigeon.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Munchkin','id_cuahang' => 7,'mota'=>'Đồ chơi và chăm sóc bé','media'=>'munchkin.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Chicco','id_cuahang' => 7,'mota'=>'Đồ dùng trẻ em','media'=>'chicco.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Combi','id_cuahang' => 7,'mota'=>'Xe đẩy và sản phẩm trẻ em','media'=>'combi.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Fisher-Price','id_cuahang' => 7,'mota'=>'Đồ chơi giáo dục trẻ em','media'=>'fisherprice.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Dr.Brown','id_cuahang' => 7,'mota'=>'Bình sữa và phụ kiện','media'=>'drbrown.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Babyhug','id_cuahang' => 7,'mota'=>'Đồ dùng và quần áo trẻ em','media'=>'babyhug.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Aprica','id_cuahang' => 7,'mota'=>'Xe đẩy và sản phẩm mẹ bé','media'=>'aprica.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Pampers','id_cuahang' => 7,'mota'=>'Tã giấy và sản phẩm vệ sinh','media'=>'pampers.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Huggies','id_cuahang' => 7,'mota'=>'Tã giấy trẻ em','media'=>'huggies.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Thời trang
            ['ten'=>'Adidas','id_cuahang' => 8,'mota'=>'Thời trang thể thao','media'=>'adidas.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Zara','id_cuahang' => 8,'mota'=>'Thời trang nhanh','media'=>'zara.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'H&M','id_cuahang' => 8,'mota'=>'Thời trang phổ thông','media'=>'hm.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Uniqlo','id_cuahang' => 8,'mota'=>'Thời trang cơ bản và casual','media'=>'uniqlo.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Gucci','id_cuahang' => 8,'mota'=>'Thời trang cao cấp','media'=>'gucci.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Louis Vuitton','id_cuahang' => 8,'mota'=>'Thời trang và phụ kiện cao cấp','media'=>'louis_vuitton.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Puma','id_cuahang' => 8,'mota'=>'Thời trang và giày thể thao','media'=>'puma.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Levi\'s','id_cuahang' => 8,'mota'=>'Thời trang denim và casual','media'=>'levis.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Converse','id_cuahang' => 8,'mota'=>'Giày và thời trang casual','media'=>'converse.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],

            // Sản phẩm khác
            ['ten'=>'Apple','id_cuahang' => 9,'mota'=>'Điện thoại, máy tính và phụ kiện','media'=>'apple.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Canon','id_cuahang' => 9,'mota'=>'Máy ảnh và thiết bị hình ảnh','media'=>'canon.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'HP','id_cuahang' => 9,'mota'=>'Máy tính, laptop và thiết bị văn phòng','media'=>'hp.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Dell','id_cuahang' => 9,'mota'=>'Laptop và thiết bị máy tính','media'=>'dell.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Asus','id_cuahang' => 9,'mota'=>'Laptop, máy tính và linh kiện','media'=>'asus.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
            ['ten'=>'Xiaomi','id_cuahang' => 9,'mota'=>'Điện thoại, thiết bị thông minh và phụ kiện','media'=>'xiaomi.png','trangthai'=>'hoat_dong','created_at'=>$now,'updated_at'=>$now],
        ];

        DB::table('thuong_hieu')->insert($thuongHieu);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BaivietSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $bai_viet_1_cho_bai_viet_no_dai_hon_thoi = "<p>Siêu Thị Vina - Đối Tác Phân Phối Hàng Đầu Cho Mọi Nhà
        Siêu Thị Vina tự hào là đối tác phân phối đáng tin cậy, cung cấp đa dạng các mặt hàng thiết yếu từ Sức khỏe, Chăm sóc cá nhân, Điện máy đến Thiết bị y tế, Bách hóa và nhiều hơn nữa. Chúng tôi cam kết mang đến những sản phẩm chất lượng với giá cả cạnh tranh nhất.

        Tại Sao Nên Chọn Siêu Thị Vina?
        Với phương châm \"Khách hàng là trọng tâm\", Siêu Thị Vina không ngừng nỗ lực hoàn thiện để trở thành người bạn đồng hành tin cậy của mọi gia đình Việt.

        Chất lượng đảm bảo: Tất cả sản phẩm đều được tuyển chọn kỹ lưỡng, đảm bảo an toàn và có nguồn gốc xuất xứ rõ ràng.

        Giá cả cạnh tranh: Chính sách giá hợp lý nhờ chuỗi cung ứng được tối ưu hóa.

        Dịch vụ chuyên nghiệp: Đội ngũ nhân viên tận tâm, sẵn sàng tư vấn và hỗ trợ.

        Khám Phá Các Danh Mục Sản Phẩm Tại Siêu Thị Vina
        Siêu Thị Vina sở hữu một hệ sinh thái sản phẩm toàn diện, đáp ứng mọi nhu cầu từ cơ bản đến cao cấp của khách hàng.

        🏥 Sức Khỏe & Thiết Bị Y Tế
        Danh mục này cung cấp các sản phẩm chăm sóc sức khỏe chủ động và thiết yếu cho gia đình bạn. Từ thực phẩm chức năng, vitamin hỗ trợ nâng cao sức đề kháng, đến các thiết bị y tế như máy đo huyết áp, nhiệt kế điện tử, máy đo đường huyết, giúp bạn dễ dàng theo dõi tình trạng sức khỏe tại nhà. Chúng tôi hiểu rằng sức khỏe là vốn quý nhất, vì vậy mọi sản phẩm đều được lựa chọn kỹ càng.

        💄 Làm Đẹp & Chăm Sóc Cá Nhân
        Đây là thiên đường dành cho những ai yêu thích làm đẹp. Danh mục Làm đẹp và Chăm sóc cá nhân tại Siêu Thị Vina bao gồm đầy đủ các sản phẩm từ mỹ phẩm, dược phẩm làm đẹp đến dụng cụ chăm sóc da, body. Bên cạnh đó, bạn cũng có thể tìm thấy những vật dụng thiết yếu hàng ngày như bàn chải đánh răng, sữa tắm, dầu gội,... giúp bạn luôn tươi trẻ và tự tin trong cuộc sống.

        🏠 Nhà Cửa & Đời Sống
        Biến ngôi nhà thành tổ ấm thực sự với danh mục Nhà cửa - Đời sống. Chúng tôi cung cấp vô vàn các sản phẩm gia dụng, đồ dùng nhà bếp, vật dụng trang trí và dụng cụ cải tạo nhà cửa. Từ những chiếc bát đĩa xinh xắn đến các thiết bị vệ sinh, tất cả đều được thiết kế tiện nghi và hiện đại, mang đến sự tiện lợi và thoải mái cho không gian sống của bạn.

        👨‍👩‍👧‍👦 Mẹ Và Bé
        Đồng hành cùng các bậc cha mẹ trong hành trình chăm sóc thiên thần nhỏ, danh mục Mẹ và bé của Siêu Thị Vina là nơi bạn có thể tìm thấy mọi thứ từ sữa bột, tã lót, đồ dùng ăn dặm đến xe đẩy, đồ chơi an toàn. Các sản phẩm đều được kiểm định nghiêm ngặt về độ an toàn, đảm bảo cho sự phát triển toàn diện của bé yêu.

        ⚡ Điện Máy & Bách Hóa
        Đáp ứng nhu cầu thiết yếu và nâng cao chất lượng sống, danh mục Điện máy cung cấp các thiết bị như quạt, nồi cơm điện, bàn ủi... tiết kiệm điện năng. Trong khi đó, danh mục Bách hóa là nơi bạn có thể mua sắm mọi thứ từ thực phẩm khô, đồ gia vị đến văn phòng phẩm, đồ dùng học tập một cách nhanh chóng và tiện lợi.

        👗 Thời Trang
        Cập nhật những xu hướng thời trang mới nhất với danh mục Thời trang tại Siêu Thị Vina. Chúng tôi mang đến cho bạn những bộ trang phục đa dạng từ quần áo, giày dép đến phụ kiện thời trang phù hợp cho mọi lứa tuổi và dịp sử dụng, giúp bạn luôn nổi bật và cá tính.

        Trải Nghiệm Mua Sắm Khác Biệt Tại Siêu Thị Vina
        Khi đến với Siêu Thị Vina, bạn không chỉ đơn thuần là mua sắm mà còn là trải nghiệm một dịch vụ toàn diện. Chúng tôi sở hữu hệ thống siêu thị rộng khắp với không gian mua sắm thoáng đãng, sạch sẽ. Đội ngũ nhân viên tư vấn được đào tạo bài bản, luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn. Bên cạnh đó, chính sách hậu mãi, bảo hành và đổi trả rõ ràng, minh bạch sẽ mang đến cho bạn sự an tâm tuyệt đối.

        Kết Luận
        Siêu Thị Vina không ngừng phấn đấu để trở thành điểm đến mua sắm tin cậy, nơi mọi khách hàng đều có thể tìm thấy những sản phẩm chất lượng với mức giá hợp lý nhất. Hãy ghé thăm Siêu Thị Vina ngay hôm nay để khám phá trọn vẹn thế giới sản phẩm đa dạng và trải nghiệm dịch vụ khác biệt của chúng tôi!</p>";

        // Xóa dữ liệu cũ
        DB::table('baiviet')->delete();

        $adminIds = DB::table('nguoidung')->where('vaitro', 'admin')->value('id'); // $adminIds là con gpt con tào lao á tôi vô tội
        $part = "uploads/baiviet/"; // lý ra phải $part.'hinhanh'; mà thôi vậy cũng được
        // Dữ liệu 10 bài viết mẫu
        $data = [
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Chương trình Siêu Sale Tháng 11 - Mua Sắm Thả Ga, Giảm Giá Tận Tay!',
                'slug' => Str::slug('Chương trình Siêu Sale Tháng 11 - Mua Sắm Thả Ga, Giảm Giá Tận Tay!'),
                'noidung' => '<p>Siêu Thị Vina mang đến chương trình <strong>Siêu Sale Tháng 11</strong> với hàng ngàn sản phẩm giảm giá đến <strong>70%</strong>. Từ thực phẩm chức năng, mỹ phẩm, đồ gia dụng cho đến sản phẩm chăm sóc sức khỏe – tất cả đều có mặt!</p>
                <p>Thời gian: <strong>01/11/2025 - 15/11/2025</strong></p>
                <p>Hãy nhanh tay đặt hàng và nhận quà hấp dẫn ngay hôm nay!</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 120,
                'hinhanh' => 'sale-thang-11.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' => 1,
                'tieude' => 'Mẹo bảo quản mật ong đúng cách giúp giữ nguyên dinh dưỡng',
                'slug' => Str::slug('Mẹo bảo quản mật ong đúng cách giúp giữ nguyên dinh dưỡng'),
                'noidung' => '<p>Mật ong là món quà quý từ thiên nhiên, nhưng nếu bảo quản không đúng cách, hương vị và chất lượng sẽ giảm sút.</p>
                <ul>
                    <li>Bảo quản trong lọ thủy tinh, nơi khô ráo, tránh ánh nắng trực tiếp.</li>
                    <li>Không để mật ong trong tủ lạnh vì dễ kết tinh.</li>
                    <li>Đậy kín nắp sau khi sử dụng để tránh ẩm mốc.</li>
                </ul>
                <p>Áp dụng những mẹo nhỏ này để mật ong của bạn luôn thơm ngon nhé!</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 80,
                'hinhanh' => 'bao-quan-mat-ong.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Cách lựa chọn sữa non phù hợp cho bé yêu phát triển toàn diện',
                'slug' => Str::slug('Cách lựa chọn sữa non phù hợp cho bé yêu phát triển toàn diện'),
                'noidung' => '<p>Sữa non là nguồn dinh dưỡng quý giá giúp tăng cường sức đề kháng và phát triển chiều cao cho trẻ nhỏ.</p>
                <p>Khi chọn sữa non, hãy chú ý:</p>
                <ul>
                    <li>Xuất xứ rõ ràng, có chứng nhận an toàn.</li>
                    <li>Thành phần giàu canxi, protein, và DHA.</li>
                    <li>Phù hợp với độ tuổi của bé.</li>
                </ul>
                <p><strong>Papamilk Height & Gain</strong> là lựa chọn được nhiều mẹ tin dùng tại Siêu Thị Vina.</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 55,
                'hinhanh' => 'sua-non-cho-be.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Tinh dầu tràm thiên nhiên – Bí quyết chăm sóc sức khỏe gia đình',
                'slug' => Str::slug('Tinh dầu tràm thiên nhiên – Bí quyết chăm sóc sức khỏe gia đình'),
                'noidung' => '<p>Tinh dầu tràm có công dụng kháng khuẩn, giảm ho, và giữ ấm cơ thể – đặc biệt hữu ích cho mùa lạnh.</p>
                <p><strong>Siêu Thị Vina</strong> mang đến dòng tinh dầu tràm ECO 100% tự nhiên, an toàn cho trẻ nhỏ.</p>
                <p>Sản phẩm hiện đang được giảm giá 15% trong tháng này!</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 95,
                'hinhanh' => 'tinh-dau-tram-eco.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Bí quyết làm đẹp da với collagen thủy phân – Cập nhật xu hướng 2025',
                'slug' => Str::slug('Bí quyết làm đẹp da với collagen thủy phân – Cập nhật xu hướng 2025'),
                'noidung' => '<p>Collagen thủy phân giúp da sáng mịn, đàn hồi và trẻ hóa. Sản phẩm <strong>Acai Labs Marine Collagen</strong> nhập khẩu Australia hiện đang rất được ưa chuộng.</p>
                <p>Uống mỗi ngày 1 gói, sau 2 tuần bạn sẽ cảm nhận làn da căng mướt tự nhiên.</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 40,
                'hinhanh' => 'collagen-thuy-phan.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Khuyến mãi đặc biệt – Miễn phí vận chuyển cho đơn hàng trên 500K',
                'slug' => Str::slug('Khuyến mãi đặc biệt – Miễn phí vận chuyển cho đơn hàng trên 500K'),
                'noidung' => '<p>Nhằm tri ân khách hàng thân thiết, Siêu Thị Vina triển khai chương trình <strong>FreeShip toàn quốc</strong> cho mọi đơn hàng từ 500,000đ trở lên.</p>
                <p>Thời gian áp dụng: <strong>01/11 - 30/11/2025</strong></p>
                <p>Áp dụng cho tất cả sản phẩm thuộc danh mục thực phẩm, mỹ phẩm và hàng tiêu dùng.</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 300,
                'hinhanh' => 'freeship-500k.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Top 5 sản phẩm chăm sóc sức khỏe bán chạy nhất tại Siêu Thị Vina',
                'slug' => Str::slug('Top 5 sản phẩm chăm sóc sức khỏe bán chạy nhất tại Siêu Thị Vina'),
                'noidung' => '<ol>
                    <li>Midu MenaQ7 180mcg – Hỗ trợ xương khớp chắc khỏe.</li>
                    <li>Keo ong Tracybee – Tăng đề kháng, kháng khuẩn tự nhiên.</li>
                    <li>Sâm Ngọc Linh Trường Sinh Đỏ – Bồi bổ sức khỏe toàn thân.</li>
                    <li>Collagen Acai Labs – Làm đẹp da từ bên trong.</li>
                    <li>Tinh dầu tràm ECO – Giữ ấm cơ thể và thư giãn tinh thần.</li>
                </ol>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 200,
                'hinhanh' => 'top5-suc-khoe.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Sự kiện “Ngày hội sức khỏe 2025” – Cùng Siêu Thị Vina lan tỏa năng lượng tích cực',
                'slug' => Str::slug('Sự kiện “Ngày hội sức khỏe 2025” – Cùng Siêu Thị Vina lan tỏa năng lượng tích cực'),
                'noidung' => '<p>Tham gia “Ngày hội sức khỏe 2025” do Siêu Thị Vina tổ chức tại TP. Hồ Chí Minh với nhiều hoạt động bổ ích:</p>
                <ul>
                    <li>Khám sức khỏe miễn phí</li>
                    <li>Workshop hướng dẫn chăm sóc da và dinh dưỡng</li>
                    <li>Giảm giá 30% cho tất cả sản phẩm trong ngày</li>
                </ul>
                <p>Sự kiện diễn ra ngày <strong>10/12/2025</strong> tại Vincom Quận 9. Đăng ký ngay để nhận quà hấp dẫn!</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 60,
                'hinhanh' => 'ngay-hoi-suc-khoe.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Tổng hợp quà Tết 2026 – Ý nghĩa và sang trọng cùng Siêu Thị Vina',
                'slug' => Str::slug('Tổng hợp quà Tết 2026 – Ý nghĩa và sang trọng cùng Siêu Thị Vina'),
                'noidung' => '<p>Chuẩn bị Tết 2026, Siêu Thị Vina giới thiệu bộ sưu tập <strong>Giỏ quà Tết cao cấp</strong> với nhiều lựa chọn đa dạng:</p>
                <ul>
                    <li>Giỏ quà Sức Khỏe – Dành cho người thân yêu.</li>
                    <li>Giỏ quà Doanh Nghiệp – Sang trọng, tinh tế.</li>
                    <li>Giỏ quà Gia Đình – Ấm áp và tiết kiệm.</li>
                </ul>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 150,
                'hinhanh' => 'qua-tet-2026.jpg',
                'trangthai' => 'Hiển thị',
            ],
            [
                'id_nguoidung' =>         $adminIds,
                'tieude' => 'Chăm sóc gia đình an toàn với sản phẩm tẩy rửa sinh học từ thiên nhiên',
                'slug' => Str::slug('Chăm sóc gia đình an toàn với sản phẩm tẩy rửa sinh học từ thiên nhiên'),
                'noidung' => '<p>Các sản phẩm như <strong>Nước rửa chén sả chanh Come On</strong> và <strong>Nước rửa bát Bio Formula</strong> đang được ưa chuộng nhờ chiết xuất tự nhiên, an toàn cho da tay và môi trường.</p>
                <p>Bạn hoàn toàn có thể yên tâm sử dụng mỗi ngày cho cả gia đình!</p>'.$bai_viet_1_cho_bai_viet_no_dai_hon_thoi,
                'luotxem' => 110,
                'hinhanh' => 'tay-rua-sinh-hoc.jpg',
                'trangthai' => 'Hiển thị',
            ],
        ];

        DB::table('baiviet')->insert($data);
    }
}


// namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;
// use Illuminate\Support\Str;

// class BaiVietSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         $now = Carbon::now('Asia/Ho_Chi_Minh');
//         //
//         $bai_viet_1 = "<p>Siêu Thị Vina - Đối Tác Phân Phối Hàng Đầu Cho Mọi Nhà
//         Siêu Thị Vina tự hào là đối tác phân phối đáng tin cậy, cung cấp đa dạng các mặt hàng thiết yếu từ Sức khỏe, Chăm sóc cá nhân, Điện máy đến Thiết bị y tế, Bách hóa và nhiều hơn nữa. Chúng tôi cam kết mang đến những sản phẩm chất lượng với giá cả cạnh tranh nhất.

//         Tại Sao Nên Chọn Siêu Thị Vina?
//         Với phương châm \"Khách hàng là trọng tâm\", Siêu Thị Vina không ngừng nỗ lực hoàn thiện để trở thành người bạn đồng hành tin cậy của mọi gia đình Việt.

//         Chất lượng đảm bảo: Tất cả sản phẩm đều được tuyển chọn kỹ lưỡng, đảm bảo an toàn và có nguồn gốc xuất xứ rõ ràng.

//         Giá cả cạnh tranh: Chính sách giá hợp lý nhờ chuỗi cung ứng được tối ưu hóa.

//         Dịch vụ chuyên nghiệp: Đội ngũ nhân viên tận tâm, sẵn sàng tư vấn và hỗ trợ.

//         Khám Phá Các Danh Mục Sản Phẩm Tại Siêu Thị Vina
//         Siêu Thị Vina sở hữu một hệ sinh thái sản phẩm toàn diện, đáp ứng mọi nhu cầu từ cơ bản đến cao cấp của khách hàng.

//         🏥 Sức Khỏe & Thiết Bị Y Tế
//         Danh mục này cung cấp các sản phẩm chăm sóc sức khỏe chủ động và thiết yếu cho gia đình bạn. Từ thực phẩm chức năng, vitamin hỗ trợ nâng cao sức đề kháng, đến các thiết bị y tế như máy đo huyết áp, nhiệt kế điện tử, máy đo đường huyết, giúp bạn dễ dàng theo dõi tình trạng sức khỏe tại nhà. Chúng tôi hiểu rằng sức khỏe là vốn quý nhất, vì vậy mọi sản phẩm đều được lựa chọn kỹ càng.

//         💄 Làm Đẹp & Chăm Sóc Cá Nhân
//         Đây là thiên đường dành cho những ai yêu thích làm đẹp. Danh mục Làm đẹp và Chăm sóc cá nhân tại Siêu Thị Vina bao gồm đầy đủ các sản phẩm từ mỹ phẩm, dược phẩm làm đẹp đến dụng cụ chăm sóc da, body. Bên cạnh đó, bạn cũng có thể tìm thấy những vật dụng thiết yếu hàng ngày như bàn chải đánh răng, sữa tắm, dầu gội,... giúp bạn luôn tươi trẻ và tự tin trong cuộc sống.

//         🏠 Nhà Cửa & Đời Sống
//         Biến ngôi nhà thành tổ ấm thực sự với danh mục Nhà cửa - Đời sống. Chúng tôi cung cấp vô vàn các sản phẩm gia dụng, đồ dùng nhà bếp, vật dụng trang trí và dụng cụ cải tạo nhà cửa. Từ những chiếc bát đĩa xinh xắn đến các thiết bị vệ sinh, tất cả đều được thiết kế tiện nghi và hiện đại, mang đến sự tiện lợi và thoải mái cho không gian sống của bạn.

//         👨‍👩‍👧‍👦 Mẹ Và Bé
//         Đồng hành cùng các bậc cha mẹ trong hành trình chăm sóc thiên thần nhỏ, danh mục Mẹ và bé của Siêu Thị Vina là nơi bạn có thể tìm thấy mọi thứ từ sữa bột, tã lót, đồ dùng ăn dặm đến xe đẩy, đồ chơi an toàn. Các sản phẩm đều được kiểm định nghiêm ngặt về độ an toàn, đảm bảo cho sự phát triển toàn diện của bé yêu.

//         ⚡ Điện Máy & Bách Hóa
//         Đáp ứng nhu cầu thiết yếu và nâng cao chất lượng sống, danh mục Điện máy cung cấp các thiết bị như quạt, nồi cơm điện, bàn ủi... tiết kiệm điện năng. Trong khi đó, danh mục Bách hóa là nơi bạn có thể mua sắm mọi thứ từ thực phẩm khô, đồ gia vị đến văn phòng phẩm, đồ dùng học tập một cách nhanh chóng và tiện lợi.

//         👗 Thời Trang
//         Cập nhật những xu hướng thời trang mới nhất với danh mục Thời trang tại Siêu Thị Vina. Chúng tôi mang đến cho bạn những bộ trang phục đa dạng từ quần áo, giày dép đến phụ kiện thời trang phù hợp cho mọi lứa tuổi và dịp sử dụng, giúp bạn luôn nổi bật và cá tính.

//         Trải Nghiệm Mua Sắm Khác Biệt Tại Siêu Thị Vina
//         Khi đến với Siêu Thị Vina, bạn không chỉ đơn thuần là mua sắm mà còn là trải nghiệm một dịch vụ toàn diện. Chúng tôi sở hữu hệ thống siêu thị rộng khắp với không gian mua sắm thoáng đãng, sạch sẽ. Đội ngũ nhân viên tư vấn được đào tạo bài bản, luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn. Bên cạnh đó, chính sách hậu mãi, bảo hành và đổi trả rõ ràng, minh bạch sẽ mang đến cho bạn sự an tâm tuyệt đối.

//         Kết Luận
//         Siêu Thị Vina không ngừng phấn đấu để trở thành điểm đến mua sắm tin cậy, nơi mọi khách hàng đều có thể tìm thấy những sản phẩm chất lượng với mức giá hợp lý nhất. Hãy ghé thăm Siêu Thị Vina ngay hôm nay để khám phá trọn vẹn thế giới sản phẩm đa dạng và trải nghiệm dịch vụ khác biệt của chúng tôi!</p>";



//         $baiViets = [
//             [
//                 'tieude' => 'Siêu Thị Vina Khai Trương Chi Nhánh Mới Tại Quận 1',
//                 'mota' => 'Khám phá không gian mua sắm hiện đại với hàng ngàn sản phẩm chất lượng',
//                 'noidung' => $bai_viet_1,
//                 'luotxem' => 150,
//                 'trangthai' => 'đã xuất bản',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now,
//                 'updated_at' => $now
//             ],
//             [
//                 'tieude' => 'Cẩm Nang Chăm Sóc Sức Khỏe Mùa Hè',
//                 'mota' => 'Bí quyết bảo vệ sức khỏe cho cả gia đình trong những ngày nắng nóng',
//                 'noidung' => '<p>Mùa hè với thời tiết nắng nóng dễ khiến cơ thể mệt mỏi. Bài viết cung cấp những tips chăm sóc sức khỏe hiệu quả từ các chuyên gia.</p>',
//                 'luotxem' => 89,
//                 'trangthai' => 'đã xuất bản',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(1),
//                 'updated_at' => $now->copy()->subDays(1)
//             ],
//             [
//                 'tieude' => 'Thiết Bị Y Tế Gia Đình - Nên Có Những Gì?',
//                 'mota' => 'Danh sách các thiết bị y tế cần thiết cho mỗi gia đình',
//                 'noidung' => '<p>Từ nhiệt kế, máy đo huyết áp đến tủ thuốc gia đình, đâu là những thiết bị y tế không thể thiếu trong mỗi gia đình hiện đại?</p>',
//                 'luotxem' => 203,
//                 'trangthai' => 'đã xuất bản',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(2),
//                 'updated_at' => $now->copy()->subDays(2)
//             ],
//             [
//                 'tieude' => 'Xu Hướng Điện Máy Xanh 2024',
//                 'mota' => 'Các thiết bị điện máy tiết kiệm điện năng đang được ưa chuộng',
//                 'noidung' => '<p>Cùng điểm qua những xu hướng điện máy xanh đang thịnh hành trong năm 2024 và lợi ích mà chúng mang lại.</p>',
//                 'luotxem' => 167,
//                 'trangthai' => 'đã lưu trữ',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(3),
//                 'updated_at' => $now->copy()->subDays(3)
//             ],
//             [
//                 'tieude' => 'Bách Hóa Siêu Thị Vina - Đa Dạng Sản Phẩm',
//                 'mota' => 'Khám phá thế giới bách hóa đa dạng với hàng ngàn mặt hàng',
//                 'noidung' => '<p>Từ thực phẩm khô đến đồ gia dụng, bách hóa Siêu Thị Vina đáp ứng mọi nhu cầu thiết yếu của gia đình bạn.</p>',
//                 'luotxem' => 95,
//                 'trangthai' => 'đã xuất bản',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(4),
//                 'updated_at' => $now->copy()->subDays(4)
//             ],
//             [
//                 'tieude' => 'Nhà Cửa Đời Sống - Tổ Ấm Của Bạn',
//                 'mota' => 'Cải thiện không gian sống với các sản phẩm nhà cửa đời sống',
//                 'noidung' => '<p>Những gợi ý trang trí và cải tạo không gian sống từ các sản phẩm nhà cửa đời sống tại Siêu Thị Vina.</p>',
//                 'luotxem' => 78,
//                 'trangthai' => 'đang chờ duyệt',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(5),
//                 'updated_at' => $now->copy()->subDays(5)
//             ],
//             [
//                 'tieude' => 'Mẹ Và Bé - Hành Trình Làm Mẹ',
//                 'mota' => 'Đồng hành cùng mẹ trong hành trình chăm sóc bé yêu',
//                 'noidung' => '<p>Chuyên mục chia sẻ kinh nghiệm chăm sóc mẹ và bé với các sản phẩm chất lượng, an toàn.</p>',
//                 'luotxem' => 234,
//                 'trangthai' => 'đã xuất bản',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(6),
//                 'updated_at' => $now->copy()->subDays(6)
//             ],
//             [
//                 'tieude' => 'Thời Trang Công Sở - Phong Cách Mới',
//                 'mota' => 'Cập nhật xu hướng thời trang công sở 2024',
//                 'noidung' => '<p>Những items thời trang công sở không thể thiếu trong tủ đồ của bạn mùa này.</p>',
//                 'luotxem' => 145,
//                 'trangthai' => 'đã xuất bản',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(7),
//                 'updated_at' => $now->copy()->subDays(7)
//             ],
//             [
//                 'tieude' => 'Làm Đẹp Tự Nhiên - Bí Quyết Từ Chuyên Gia',
//                 'mota' => 'Bật mí bí quyết làm đẹp an toàn và hiệu quả',
//                 'noidung' => '<p>Khám phá những phương pháp làm đẹp tự nhiên cùng các sản phẩm chăm sóc da chất lượng.</p>',
//                 'luotxem' => 189,
//                 'trangthai' => 'nháp',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(8),
//                 'updated_at' => $now->copy()->subDays(8)
//             ],
//             [
//                 'tieude' => 'Chăm Sóc Cá Nhân - Nâng Tầm Cuộc Sống',
//                 'mota' => 'Sản phẩm chăm sóc cá nhân cao cấp cho cuộc sống hiện đại',
//                 'noidung' => '<p>Nâng cao chất lượng cuộc sống với các sản phẩm chăm sóc cá nhân đến từ thương hiệu uy tín.</p>',
//                 'luotxem' => 112,
//                 'trangthai' => 'đã xuất bản',
//                 'id_nguoidung' => 1,
//                 'created_at' => $now->copy()->subDays(9),
//                 'updated_at' => $now->copy()->subDays(9)
//             ]
//         ];

//         DB::table('bai_viet')->insert($baiViets);
//     }
// }

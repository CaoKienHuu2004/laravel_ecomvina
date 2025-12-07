<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BientheModel;
use App\Models\GiohangModel;
use App\Models\QuatangsukienModel;
use Illuminate\Http\Request;


class CartController extends Controller
{
    protected $giohang;
    public function __construct(GiohangModel $giohang, array $giohang_session)
    {
        if(!empty($giohang)) {
            $this->giohang = $giohang;
        } else {
            $this->giohang = $giohang_session;
        }
    }
    private function loadgiohang()
    {
        /* ============================
        1) USER ĐĂNG NHẬP – DÙNG DB
        ============================ */
        if ($this->giohang instanceof GiohangModel) {

            // Lấy user_id từ model
            $userId = $this->giohang->user_id ?? auth()->id();

            // Truy vấn lại toàn bộ giỏ hàng của user
            $items = GiohangModel::with(['bienthe.sanpham'])
                ->where('user_id', $userId)
                ->get()
                ->map(function ($row) {
                    return [
                        'id_bienthe' => $row->id_bienthe,
                        'soluong'    => $row->soluong,
                        'thanhtien'  => $row->thanhtien,
                        'bienthe'    => $row->bienthe
                    ];
                })
                ->toArray();

            $this->giohang = $items;
            return;
        }

        /* ============================
        2) USER KHÔNG ĐĂNG NHẬP – SESSION
        ============================ */
        if (is_array($this->giohang)) {

            foreach ($this->giohang as $index => $item) {

                // Load biến thể
                $bienthe = BientheModel::with('sanpham')
                    ->find($item['id_bienthe']);

                if ($bienthe) {
                    $this->giohang[$index]['bienthe'] = $bienthe;
                }

                // Tính lại thành tiền nếu chưa có
                if (!isset($this->giohang[$index]['thanhtien'])) {
                    $this->giohang[$index]['thanhtien'] =
                        $bienthe ? ($bienthe->gia * $item['soluong']) : 0;
                }
            }

            // Lưu lại vào session
            session(['giohang' => $this->giohang]);
            return;
        }
    }

    public function addToCart(Request $request)
    {
        $id_bienthe = $request->id_bienthe;
        $soluong = $request->soluong ?? 1;

        $this->addProductToCart($id_bienthe, $soluong);

        // Kiểm tra điều kiện quà tặng
        $this->xacnhandieukienquatang();

        return response()->json([
            'status' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ',
            'giohang' => $this->giohang
        ]);
    }
    public function updateQuantity(Request $request)
    {
        $id_bienthe = $request->id_bienthe;
        $soluong = $request->soluong;

        $this->updateProductQuantity($id_bienthe, $soluong);

        // Cập nhật lại quà
        $this->xacnhandieukienquatang();

        return response()->json([
            'status' => true,
            'giohang' => $this->giohang
        ]);
    }
    public function removeItem($id_bienthe)
    {
        $this->removeProductFromCart($id_bienthe);

        // Kiểm tra lại điều kiện quà tặng
        $this->xacnhandieukienquatang();

        return response()->json([
            'status' => true,
            'giohang' => $this->giohang
        ]);
    }
    public function getCart()
    {
        $this->loadgiohang();

        // Auto kiểm quà khi mở trang giỏ
        $this->xacnhandieukienquatang();

        return view('giohang.index', [
            'giohang' => $this->giohang
        ]);
    }
    private function addProductToCart($id_bienthe, $soluong)
    {
        // Nếu user đăng nhập => dùng model
        if ($this->giohang instanceof GiohangModel) {

            $item = $this->giohang->where('id_bienthe', $id_bienthe)->first();

            if ($item) {
                // Cộng dồn số lượng
                $item->soluong += $soluong;
                $item->thanhtien = $item->bienthe->gia * $item->soluong;
                $item->save();
            } else {
                // Tạo mới
                $this->giohang->create([
                    'id_bienthe' => $id_bienthe,
                    'soluong'    => $soluong,
                    'thanhtien'  => BientheModel::find($id_bienthe)->gia * $soluong,
                ]);
            }

            // Load lại giỏ
            $this->loadgiohang();
            return;
        }

        // Nếu dùng session
        if (is_array($this->giohang)) {

            // Tìm xem đã tồn tại trong giỏ chưa
            foreach ($this->giohang as $index => $item) {
                if ($item['id_bienthe'] == $id_bienthe && $item['thanhtien'] > 0) {
                    $this->giohang[$index]['soluong'] += $soluong;

                    $bienthe = BientheModel::find($id_bienthe);
                    $this->giohang[$index]['thanhtien'] = $bienthe->gia * $this->giohang[$index]['soluong'];

                    session(['giohang' => $this->giohang]);
                    return;
                }
            }

            // Nếu chưa có → thêm mới
            $bienthe = BientheModel::with('sanpham')->find($id_bienthe);

            $this->giohang[] = [
                'id_bienthe' => $id_bienthe,
                'soluong'    => $soluong,
                'thanhtien'  => $bienthe->gia * $soluong,
                'bienthe'    => $bienthe
            ];

            session(['giohang' => $this->giohang]);
        }
    }
    private function addGiftToCart($id_bienthe, $soluong)
    {
        // Nếu dùng database
        if ($this->giohang instanceof GiohangModel) {

            $item = $this->giohang->where('id_bienthe', $id_bienthe)->first();

            if ($item) {
                // Nếu đã có quà → không tăng số lượng thêm
                return;
            }

            $this->giohang->create([
                'id_bienthe' => $id_bienthe,
                'soluong'    => $soluong,
                'thanhtien'  => 0, // QUÀ = 0
            ]);

            return;
        }

        // Nếu dùng session
        if (is_array($this->giohang)) {

            foreach ($this->giohang as $item) {
                if ($item['id_bienthe'] == $id_bienthe && $item['thanhtien'] == 0) {
                    return; // đã có quà rồi
                }
            }

            $gift = BientheModel::with('sanpham')->find($id_bienthe);

            $this->giohang[] = [
                'id_bienthe' => $id_bienthe,
                'soluong'    => 1,
                'thanhtien'  => 0,
                'bienthe'    => $gift
            ];

            session(['giohang' => $this->giohang]);
        }
    }
    private function updateProductQuantity($id_bienthe, $soluong)
    {
        // Nếu dùng DB
        if ($this->giohang instanceof GiohangModel) {

            $item = $this->giohang->where('id_bienthe', $id_bienthe)->first();

            if ($item) {
                if ($item->thanhtien > 0) { // chỉ cập nhật SP chính, không update quà
                    $item->soluong = max(1, $soluong);
                    $item->thanhtien = $item->bienthe->gia * $item->soluong;
                    $item->save();
                }
            }

            $this->loadgiohang();
            return;
        }


        // Session
        if (is_array($this->giohang)) {

            foreach ($this->giohang as $index => $item) {
                if ($item['id_bienthe'] == $id_bienthe && $item['thanhtien'] > 0) {
                    $this->giohang[$index]['soluong'] = max(1, $soluong);

                    $bienthe = BientheModel::find($id_bienthe);
                    $this->giohang[$index]['thanhtien'] = $bienthe->gia * $this->giohang[$index]['soluong'];

                    break;
                }
            }

            session(['giohang' => $this->giohang]);
        }
    }
    private function removeProductFromCart($id_bienthe)
    {
        // DB
        if ($this->giohang instanceof GiohangModel) {

            $this->giohang->where('id_bienthe', $id_bienthe)->delete();
            $this->loadgiohang();
            return;
        }

        // Session
        if (is_array($this->giohang)) {

            $this->giohang = array_filter($this->giohang, function ($item) use ($id_bienthe) {
                return $item['id_bienthe'] != $id_bienthe;
            });

            session(['giohang' => array_values($this->giohang)]);
        }
    }




    //
    /* ----------------------------------- method Của Hựu :Beigin */
    private function xacnhandieukienquatang()
    {
        $uniqueItemsByBrand = [];
        $cartTotalValue = 0; // Biến tính tổng giá trị giỏ hàng thực tế (hàng mua)

        // 1. Duyệt qua giỏ hàng để lấy thông tin Brand và Tổng tiền
        foreach ($this->giohang as $item) {
            // CHỈ XÉT SẢN PHẨM CHÍNH (có thành tiền > 0)
            if ($item['thanhtien'] > 0) {
                // Cộng dồn tổng giá trị để xét điều kiện dieukiengiatri
                $cartTotalValue += $item['thanhtien'];

                $thuonghieuId = $item['bienthe']['sanpham']['id_thuonghieu'] ?? null;
                $bientheId = $item['id_bienthe'];

                if ($thuonghieuId) {
                    if (!isset($uniqueItemsByBrand[$thuonghieuId])) {
                        $uniqueItemsByBrand[$thuonghieuId] = [];
                    }
                    $uniqueItemsByBrand[$thuonghieuId][$bientheId] = true;
                }
            }
        }

        // Lấy danh sách quà tặng đang active
        $quatangsukiendb = QuatangsukienModel::where('trangthai', 'Hiển thị')->where('deleted_at', null)->get();
        $themquatang = [];

        foreach ($quatangsukiendb as $rule) {
            $bientheduoctang = $rule->id_bienthe;
            $dieukienSoluong = $rule->dieukiensoluong; // Điều kiện 1: Số lượng sản phẩm khác nhau
            $dieukienGiatri = $rule->dieukiengiatri ?? 0; // Điều kiện 2: Giá trị đơn hàng tối thiểu

            // --- KIỂM TRA ĐIỀU KIỆN GIÁ TRỊ (MỚI) ---
            // Nếu tổng tiền giỏ hàng chưa đủ điều kiện giá trị -> Bỏ qua quà này
            if ($cartTotalValue < $dieukienGiatri) {
                continue;
            }

            $giftBienthe = BientheModel::with('sanpham')->find($bientheduoctang);

            if (!$giftBienthe) continue;

            $requiredBrandId = $giftBienthe->sanpham->id_thuonghieu ?? null;

            if ($requiredBrandId) {
                // TÍNH TOÁN: Lấy số lượng biến thể DUY NHẤT đang có của Thương hiệu đó
                $uniqueItemsCount = 0;
                if (isset($uniqueItemsByBrand[$requiredBrandId])) {
                    $uniqueItemsCount = count($uniqueItemsByBrand[$requiredBrandId]);
                }

                // --- KIỂM TRA ĐIỀU KIỆN SỐ LƯỢNG BIẾN THỂ ---
                // Cả 2 điều kiện (Giá trị & Số lượng) đều phải thỏa mãn
                if ($uniqueItemsCount >= $dieukienSoluong) {
                    $soluongquatang = 1;

                    if (!isset($themquatang[$bientheduoctang])) {
                        $themquatang[$bientheduoctang] = 0;
                    }
                    $themquatang[$bientheduoctang] = max($themquatang[$bientheduoctang], $soluongquatang);
                }
            }
        }

        // Thêm quà vào giỏ
        foreach ($themquatang as $id_bienthe => $soluong) {
            if ($soluong > 0) {
                $this->addGiftToCart($id_bienthe, $soluong);
            }
        }

        $this->loadgiohang();
    }
    /* ----------------------------------- method Của Hựu :end */
}

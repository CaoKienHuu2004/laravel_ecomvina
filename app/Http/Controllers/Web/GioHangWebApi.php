<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiohangModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Http\Resources\Toi\GioHangResource;
use App\Models\BientheModel;
use App\Models\NguoidungModel;
use App\Models\QuangcaoModel;
use Illuminate\Support\Facades\Redis;


// trả về json Object
class GioHangWebApi extends Controller
{
    use \App\Traits\ApiResponse;


    private $cart_session;
    public function __construct()
    {
        // Middleware có thể được thêm vào đây nếu cần
        $this->cart_session = config('cart_session.session_key_cart', 'cart_session');
    }

    /**
     * Lấy ID định danh của người dùng hiện tại.
     *
     * Phương thức này trả về một định danh duy nhất đại diện cho người dùng đang truy cập.
     * - Nếu người dùng đã đăng nhập (được xác thực qua bearer token), trả về user ID lấy từ Redis dựa trên token.
     * - Nếu người dùng chưa đăng nhập (khách), trả về session ID của phiên hiện tại.
     *
     * Việc sử dụng token và Redis giúp tách biệt việc lấy user ID không phụ thuộc trực tiếp vào session Laravel.
     *
     * @param \Illuminate\Http\Request $request Đối tượng Request hiện tại.
     * @return int ID của người dùng đã đăng nhập hoặc session ID của khách.
     */
    protected function getCurrentUserId(Request $request): string
    {
        // Lấy bearer token nếu có
        $token = $request->bearerToken();
        if ($token) {
            $key = "api_token:$token";
            $userId = Redis::get($key);
            // Nếu Redis trả về null, fallback về session ID
            if ($userId !== null) {
                return (int) $userId;
            }
        }
        // Trường hợp không có token hoặc Redis không tìm thấy user ID → dùng session ID
        return $request->session()->getId();
    }







    /**
     * 🛒 FE Next.js phải gọi một API để tạo session
     * khởi tạo session giống như session init của PHP ở file index.php
     * và tạo ra 2 cookie:
     * laravel_session = eyJpdiI6IjZ4MWw3...
    *  XSRF-TOKEN = eyJpdiI6IjRsdGZ...
    * Nhầm để các request sau có thể sử dụng session này
    * để biết session giỏ hàng của ai
    *      Laravel sẽ tự gửi cookie:
     *      laravel_session=xxxx
     *      XSRF-TOKEN=xxxx
     *      Nếu bạn bật middleware CSRF + CORS đúng.
     *     res
    *       {
      *          "status": true,
     *           "session_id": "0fcf053ec78492acfb0bd07c39eb84785f77a1f2"
    *        }
    *      40 ký tự hex. đối với database redis
     */
    /**
     * @OA\Get(
     *     path="/web/giohang/init",
     *     summary="Khởi tạo giỏ hàng",
     *     description="WebApi này luôn được gọi fetchapi ở trang page.tsx(tương tự index.php của php)  dùng để khởi tạo session cho giỏ hàng. Khi gọi, nó sẽ tạo một session mới (nếu chưa có), đặt một biến cờ 'khoitao_giohang' vào session, và trả về session ID. Đồng thời, nó cũng gửi về một cookie XSRF-TOKEN để client sử dụng cho các request tiếp theo nhằm chống lại tấn công CSRF.",
     *     tags={"Giỏ Hàng (web)"},
     *     @OA\Response(
     *         response=200,
     *         description="Khởi tạo thành công. Trả về status và session_id.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true, description="Trạng thái khởi tạo, luôn là true nếu thành công."),
     *             @OA\Property(property="session_id", type="string", example="eyJpdiI6Im...", description="ID của session đã được khởi tạo.")
     *         ),
     *         @OA\Header(
     *             header="Set-Cookie",
     *             description="Cookie XSRF-TOKEN được gửi về để chống CSRF.",
     *             @OA\Schema(
     *                 type="string",
     *                 example="XSRF-TOKEN=eyJpdiI6...; expires=...; path=/; samesite=lax"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi máy chủ nội bộ."
     *     )
     * )
     */
    public function init(Request $request)
    {
        $sessionId = $request->session()->getId(); // Laravel tự tạo
        $request->session()->put('khoitao_giohang', true);
        return response()->json([
            'status' => true,
            'session_id' => $sessionId,
        ])->withCookie(
            cookie()->forever('XSRF-TOKEN', csrf_token())
        ); // Dòng này có nhiệm vụ gửi cookie XSRF-TOKEN về cho FE, dùng để chống CSRF cho các request POST/PUT/PATCH/DELETE.
    }
        /**
         * tạo session giỏ hàng cho FE Nextjs SPA
         * thủ công
         */
        public function manual_init(Request $request)
        {
            // 👉 Bước 1: ép Laravel tạo session nếu chưa tồn tại
            $sessionId = $request->session()->getId(); // tự sinh nếu chưa có

            // 👉 Bước 2: để chắc chắn session được lưu
            session()->put('cart_session_initialized', true);

            // 👉 Bước 3: tạo token XSRF (bắt buộc cho SPA FE như Nextjs)
            $token = csrf_token();

            // 👉 Bước 4: trả cookie chứa XSRF + session cho FE
            return response()->json([
                'status' => true,
                'message' => 'Khởi tạo session giỏ hàng thành công',
                'session_id' => $sessionId,
            ], Response::HTTP_OK)
            // Cookie XSRF
            ->cookie(
                'XSRF-TOKEN',
                $token,
                60 * 24, // 1 ngày
                '/',
                null,
                true,       // Secure: true (HTTPS)
                false,      // HttpOnly: false -> FE JS đọc được header
                false,
                'Lax'       // SameSite
            )
            // Cookie session của Laravel
            ->cookie(
                config('session.cookie'),
                $sessionId,
                60 * 24,
                '/',
                null,
                true,       // Secure
                true,       // HttpOnly: true -> bảo mật, FE không đọc được bằng JS
                false,
                'Lax'
            );
        }
    /**
     * Lấy thông tin người dùng từ bearer token trong request.
     *
     * Phương thức này trích xuất bearer token từ header của request,
     * sử dụng token đó để lấy ID người dùng đã được lưu trong Redis,
     * sau đó tìm và trả về đối tượng người dùng tương ứng từ cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request Đối tượng request HTTP chứa bearer token.
     * @return \App\Models\NguoidungModel|null Đối tượng người dùng nếu tìm thấy, ngược lại trả về null.
     */
    private function get_user_from_token(Request $request)
    {
       $token = $request->bearerToken();
        $key = "api_token:$token";
        $userId = Redis::get($key);
        $user = NguoidungModel::find($userId);
        return $user;
    }


    /**
     * @OA\Get(
     *     path="/web/giohang",
     *     summary="Lấy danh sách sản phẩm trong giỏ hàng (Web)",
     *     description="
     *         API lấy toàn bộ sản phẩm trong giỏ hàng.
     *         - Nếu người dùng **đã đăng nhập** (gửi kèm Bearer Token), giỏ hàng sẽ được lấy từ **database**.
     *         - Nếu người dùng **chưa đăng nhập**, giỏ hàng sẽ được lấy từ **session (cookie: laravel_session)**.
     *
     *         Bao gồm đầy đủ thông tin:
     *         - Số lượng
     *         - Thành tiền (tự tính — bao gồm khuyến mãi nếu có)
     *         - Thông tin biến thể
     *         - Sản phẩm
     *         - Hình ảnh
     *         - Loại biến thể
     *     ",
     *     tags={"Giỏ Hàng (web)"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lấy giỏ hàng thành công.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", nullable=true, example=12),
     *                 @OA\Property(property="id_nguoidung", type="integer", nullable=true, example=5),
     *                 @OA\Property(property="id_bienthe", type="integer", example=101),
     *                 @OA\Property(property="soluong", type="integer", example=3),
     *                 @OA\Property(property="thanhtien", type="number", example=150000),
     *                 @OA\Property(property="trangthai", type="string", example="Hiển thị"),
     *
     *                 @OA\Property(
     *                     property="bienthe",
     *                     type="object",
     *                     description="Thông tin biến thể sản phẩm",
     *                     @OA\Property(property="id", type="integer", example=101),
     *                     @OA\Property(property="giagoc", type="integer", example=50000),
     *
     *                     @OA\Property(
     *                         property="loaibienthe",
     *                         type="object",
     *                         description="Loại biến thể (màu sắc, kích thước...)",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="ten", type="string", example="Màu đỏ")
     *                     ),
     *
     *                     @OA\Property(
     *                         property="sanpham",
     *                         type="object",
     *                         description="Thông tin sản phẩm cha",
     *                         @OA\Property(property="id", type="integer", example=20),
     *                         @OA\Property(property="ten", type="string", example="Áo thun nam cotton"),
     *
     *                         @OA\Property(
     *                             property="hinhanhsanpham",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="url", type="string", example="https://example.com/image1.jpg")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Không hợp lệ hoặc thiếu Bearer Token (chỉ áp dụng khi lấy giỏ từ DB)."
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi máy chủ."
     *     )
     * )
     */
    public function index(Request $request)
    {

        $user = $this->get_user_from_token($request);

        if ($user) {
            // Đã đăng nhập, lấy giỏ hàng từ database
            $userId = $user->id;

            $giohang = GiohangModel::with([
                'bienthe.sanpham',
                'bienthe.sanpham.hinhanhsanpham',
                'bienthe.loaibienthe'
            ])
                ->where('id_nguoidung', $userId)
                ->where('trangthai', 'Hiển thị')
                ->get()
                ->filter(fn($item) => $item->soluong > 0)
                ->values();

            GioHangResource::withoutWrapping();
            return response()->json(GioHangResource::collection($giohang), Response::HTTP_OK);
        } else {
            // Chưa đăng nhập, lấy giỏ hàng từ session
            $sessionCart = $request->session()->get($this->cart_session, []);

            // Chuyển mảng session thành collection ảo để dùng Resource
            // Vì session không phải model, ta cần tạo 1 collection ảo giả lập

            // Cách đơn giản: map mảng session thành các object tương tự GiohangModel (hoặc stdClass)
            // Tuy nhiên, vì bạn dùng GioHangResource có thể phụ thuộc quan hệ (bienthe.sanpham),
            // nên ta cần lấy thêm dữ liệu biến thể từ DB dựa vào id_bienthe.

            // Lấy tất cả id_bienthe trong giỏ hàng session
            $variantIds = array_column($sessionCart, 'id_bienthe');

            // Lấy dữ liệu biến thể & quan hệ liên quan
            $variants = BientheModel::with(['sanpham', 'sanpham.hinhanhsanpham', 'loaibienthe'])
                ->whereIn('id', $variantIds)
                ->get()
                ->keyBy('id');

            // Ghép dữ liệu session với biến thể (giá trị) tạo object giả lập cho Resource
            $cartItems = collect($sessionCart)->map(function ($item) use ($variants) {
            $variant = $variants->get($item['id_bienthe']);

            $priceUnit = $variant ? $variant->giagoc : 0;
            $soluong = $item['soluong'] ?? 0;

            // Lấy khuyến mãi áp dụng cho biến thể này
            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $item['id_bienthe'])
                ->where('qs.dieukiensoluong', '<=', $soluong)
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select('qs.dieukiensoluong as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                ->first();

            $thanhtien = $soluong * $priceUnit; // mặc định không khuyến mãi
            if ($promotion) {
                $promotionCount = floor($soluong / $promotion->discount_multiplier);
                $numFree = min($promotionCount, $promotion->current_luottang);
                $numToPay = $soluong - $numFree;
                $thanhtien = $numToPay * $promotion->giagoc;
            }

            return (object) [
                    'id' => null,
                    'id_nguoidung' => null,
                    'id_bienthe' => $item['id_bienthe'],
                    'soluong' => $soluong,
                    'thanhtien' => $thanhtien,
                    'trangthai' => 'Hiển thị',
                    'bienthe' => $variant,
                ];
            });

            GioHangResource::withoutWrapping();
            return response()->json(GioHangResource::collection($cartItems), Response::HTTP_OK);
        }
    }


    /**
     * @OA\Post(
     *     path="/web/giohang",
     *     tags={"Giỏ Hàng (web)"},
     *     summary="Thêm sản phẩm vào giỏ hàng (Hỗ trợ cả user đăng nhập & khách).",
     *     description="
     *     API này dùng để thêm sản phẩm (biến thể) vào giỏ hàng.
     *     - Nếu người dùng **đăng nhập**, giỏ hàng sẽ lưu trong **database**.
     *     - Nếu **chưa đăng nhập**, giỏ hàng lưu trong **session**.
     *
     *     API tự động tính khuyến mãi theo 2 rule:
     *     **RULE 1: Khuyến mãi theo số lượng (quatang_sukien) → tặng FREE item.**
     *     **RULE 2: Quà theo giá trị giỏ hàng (quangcao) → tặng 1 biến thể.**
     *     ",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_bienthe", "soluong"},
     *             @OA\Property(
     *                 property="id_bienthe",
     *                 type="integer",
     *                 example=12,
     *                 description="ID của biến thể cần thêm vào giỏ hàng."
     *             ),
     *             @OA\Property(
     *                 property="soluong",
     *                 type="integer",
     *                 minimum=1,
     *                 example=3,
     *                 description="Số lượng sản phẩm muốn thêm."
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Thêm vào giỏ hàng thành công. Trả về danh sách giỏ hàng sau khi cập nhật.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                @OA\Property(property="id", type="integer", example=101),
     *                @OA\Property(property="id_nguoidung", type="integer", example=5),
     *                @OA\Property(property="id_bienthe", type="integer", example=12),
     *                @OA\Property(property="soluong", type="integer", example=4, description="Tổng số lượng đã cộng dồn."),
     *                @OA\Property(property="thanhtien", type="number", example=450000),
     *                @OA\Property(property="trangthai", type="string", example="Hiển thị"),
     *                @OA\Property(
     *                     property="bienthe",
     *                     type="object",
     *                     description="Thông tin biến thể.",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="giagoc", type="number", example=150000),
     *                     @OA\Property(
     *                         property="sanpham",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="tensanpham", type="string", example="Áo thun nam"),
     *                         @OA\Property(
     *                             property="hinhanhsanpham",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(property="url", type="string", example="https://.../image.jpg")
     *                             )
     *                         )
     *                     )
     *                )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Dữ liệu gửi lên không hợp lệ (thiếu id_bienthe hoặc soluong không đúng).",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The soluong field must be at least 1.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy biến thể hoặc biến thể đã bị xóa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Biến thể không tồn tại.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi server trong quá trình thêm giỏ hàng.",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_bienthe' => 'required|exists:bienthe,id',
            'soluong' => 'required|integer|min:1',
        ]);

        $id_bienthe = $validated['id_bienthe'];
        $soluongNew = $validated['soluong'];

        $user = $this->get_user_from_token($request);

        if ($user) {
            // Đã đăng nhập => xử lý giỏ hàng trong database
            $userId = $user->id;

            DB::beginTransaction();
            try {
                // Khóa biến thể để tránh race condition
                $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
                $priceUnit = $variant->giagoc;

                // Lấy sản phẩm chính hiện tại trong giỏ (nếu có)
                $existingItem = GiohangModel::where('id_nguoidung', $userId)
                    ->where('id_bienthe', $id_bienthe)
                    ->where('thanhtien', '>', 0)
                    ->lockForUpdate()
                    ->first();

                $totalQuantity = $soluongNew + ($existingItem ? $existingItem->soluong : 0);

                
                // Tổng giỏ hiện tại (chỉ tính hàng có thanhtien > 0) //edit
                $tongGiaHienTai = GiohangModel::where('id_nguoidung', $userId)
                    ->where('thanhtien', '>', 0)
                    ->sum('thanhtien');

                // Tổng giỏ mới sau khi thêm sản phẩm
                $tongGiaGioHang = $tongGiaHienTai + ($soluongNew * $priceUnit); //edit

                // Kiểm tra khuyến mãi (có thể giữ nguyên logic hiện tại)
                $promotion = DB::table('quatang_sukien as qs')
                    ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                    ->where('qs.id_bienthe', $id_bienthe)
                    ->where('qs.dieukiensoluong', '<=', $totalQuantity)
                    ->where('qs.dieukiengiatri', '<=', $tongGiaGioHang) //edit
                    ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                    ->select('qs.dieukiensoluong as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                    ->first();

                $numFree = 0;
                $thanhtien = $totalQuantity * $priceUnit;

                if ($promotion) {
                    $promotionCount = floor($totalQuantity / $promotion->discount_multiplier);
                    // $numFree = min($promotionCount, $promotion->current_luottang);
                    $numFree = $promotionCount;
                    $numToPay = $totalQuantity - $numFree;
                    $thanhtien = $numToPay * $promotion->giagoc;

                    $existingFreeItem = GiohangModel::where('id_nguoidung', $userId)
                        ->where('id_bienthe', $id_bienthe)
                        ->where('thanhtien', 0)
                        ->lockForUpdate()
                        ->first();

                    if ($numFree > 0) {
                        if ($existingFreeItem) {
                            $existingFreeItem->update(['soluong' => $numFree, 'trangthai' => 'Hiển thị']);
                        } else {
                            GiohangModel::create([
                                'id_nguoidung' => $userId,
                                'id_bienthe' => $id_bienthe,
                                'soluong' => $numFree,
                                'thanhtien' => 0,
                                'trangthai' => 'Hiển thị',
                            ]);
                        }
                    } else {
                        GiohangModel::where('id_nguoidung', $userId)
                            ->where('id_bienthe', $id_bienthe)
                            ->where('thanhtien', 0)
                            ->delete();
                    }
                }

                if ($existingItem) {
                    $existingItem->update([
                        'soluong' => $totalQuantity,
                        'thanhtien' => $thanhtien,
                        'trangthai' => 'Hiển thị',
                    ]);
                    $item = $existingItem;
                } else {
                    $item = GiohangModel::create([
                        'id_nguoidung' => $userId,
                        'id_bienthe' => $id_bienthe,
                        'soluong' => $totalQuantity,
                        'thanhtien' => $thanhtien,
                        'trangthai' => 'Hiển thị',
                    ]);
                }

                DB::commit();

                GioHangResource::withoutWrapping();
                $cartItems = GiohangModel::with(['bienthe.sanpham.thuonghieu', 'bienthe.loaibienthe', 'bienthe.sanpham.hinhanhsanpham'])
                    ->where('id_nguoidung', $userId)
                    ->where('trangthai', 'Hiển thị')
                    ->get();
                return response()->json(GioHangResource::collection($cartItems), Response::HTTP_CREATED);

            } catch (\Throwable $e) {
                DB::rollBack();
                return $this->jsonResponse([
                    'status' => false,
                    'message' => 'Lỗi khi thêm sản phẩm vào giỏ hàng',
                    'error' => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            // Chưa đăng nhập => xử lý session cart có quà tặng
            $sessionCart = $request->session()->get($this->cart_session, []);

            // Tìm sản phẩm đã có trong session
            $foundIndex = null;
            foreach ($sessionCart as $index => $cartItem) {
                if ($cartItem['id_bienthe'] == $id_bienthe && ($cartItem['thanhtien'] ?? null) !== 0) {
                    // tìm sản phẩm chính (thanhtien != 0)
                    $foundIndex = $index;
                    break;
                }
            }

            $existingQty = 0;
            if ($foundIndex !== null) {
                $existingQty = $sessionCart[$foundIndex]['soluong'];
            }

            $totalQty = $existingQty + $soluongNew;

            // Lấy biến thể và khuyến mãi
            $variant = BientheModel::find($id_bienthe);
            $priceUnit = $variant ? $variant->giagoc : 0;

            // 👉 Tính tổng giỏ hàng session hiện tại (chỉ tính sản phẩm có thanhtien > 0)
            // $sessionCart = $request->session()->get($this->cart_session, []);
            $tongGiaGioHangSession = 0;
            foreach ($sessionCart as $item) {
                if (($item['thanhtien'] ?? 0) > 0) {
                    $tongGiaGioHangSession += $item['thanhtien'];
                }
            }

            $promotion = DB::table('quatang_sukien as qs')
                ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                ->where('qs.id_bienthe', $id_bienthe)
                ->where('qs.dieukiensoluong', '<=', $totalQty)
                ->where('qs.dieukiengiatri', '<=', $tongGiaGioHangSession) //edit
                ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                ->select('qs.dieukiensoluong as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                ->first();

            $numFree = 0;
            $thanhtien = $totalQty * $priceUnit;

            if ($promotion) {
                $promotionCount = floor($totalQty / $promotion->discount_multiplier);
                // $numFree = min($promotionCount, $promotion->current_luottang);
                $numFree = $promotionCount;
                $numToPay = $totalQty - $numFree;
                $thanhtien = $numToPay * $promotion->giagoc;
            }

            // Cập nhật hoặc thêm sản phẩm chính (thanhtien > 0)
            if ($foundIndex !== null) {
                $sessionCart[$foundIndex]['soluong'] = $totalQty;
                $sessionCart[$foundIndex]['thanhtien'] = $thanhtien;
            } else {
                $sessionCart[] = [
                    'id_bienthe' => $id_bienthe,
                    'soluong' => $totalQty,
                    'thanhtien' => $thanhtien,
                ];
            }

            // Xử lý quà tặng trong session: tìm quà tặng đã có (thanhtien = 0)
            $freeIndex = null;
            foreach ($sessionCart as $index => $cartItem) {
                if ($cartItem['id_bienthe'] == $id_bienthe && ($cartItem['thanhtien'] ?? null) === 0) {
                    $freeIndex = $index;
                    break;
                }
            }

            if ($numFree > 0) {
                if ($freeIndex !== null) {
                    // Cập nhật số lượng quà tặng
                    $sessionCart[$freeIndex]['soluong'] = $numFree;
                } else {
                    // Thêm mới quà tặng
                    $sessionCart[] = [
                        'id_bienthe' => $id_bienthe,
                        'soluong' => $numFree,
                        'thanhtien' => 0,
                    ];
                }
            } else {
                // Nếu không còn quà tặng thì xóa nếu có
                if ($freeIndex !== null) {
                    unset($sessionCart[$freeIndex]);
                }
            }

            // Reset key mảng sau khi unset
            $sessionCart = array_values($sessionCart);

            // Lưu lại session
            $request->session()->put($this->cart_session, $sessionCart);

            return response()->json([
                'status' => true,
                'message' => 'Thêm sản phẩm vào giỏ hàng thành công (session)',
                'cart_session' => $sessionCart,
            ], Response::HTTP_CREATED);
        }
    }




        /**
         * @OA\Put(
         *     path="/web/giohang/{id}",
         *     tags={"Giỏ Hàng (web)"},
         *     summary="Cập nhật số lượng sản phẩm trong giỏ hàng",
         *     description="Cập nhật số lượng sản phẩm trong giỏ hàng Web API. Nếu số lượng = 0 thì xóa sản phẩm. Tự động xử lý quà tặng rule 1 và rule 2.",
         *
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         required=true,
         *         description="ID sản phẩm trong giỏ hàng (id bản ghi giỏ hàng, không phải id_bienthe)",
         *         example=10
         *     ),
         *
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"soluong"},
         *             @OA\Property(
         *                 property="soluong",
         *                 type="integer",
         *                 example=5,
         *                 description="Số lượng mới của sản phẩm"
         *             )
         *         )
         *     ),
         *
         *     @OA\Response(
         *         response=200,
         *         description="Cập nhật giỏ hàng thành công",
         *         @OA\JsonContent(
         *             @OA\Property(property="status", type="boolean", example=true),
         *             @OA\Property(property="message", type="string", example="Cập nhật giỏ hàng thành công"),
         *         )
         *     ),
         *
         *     @OA\Response(
         *         response=404,
         *         description="Không tìm thấy sản phẩm trong giỏ"
         *     ),
         *
         *     @OA\Response(
         *         response=500,
         *         description="Lỗi trong quá trình cập nhật giỏ hàng"
         *     )
         * )
         */
        public function update(Request $request, $id)
        {
            $validated = $request->validate([
                'soluong' => 'required|integer|min:0'
            ]);

            $soluongNew = $validated['soluong'];

            $user = $this->get_user_from_token($request);

            if ($user) {
                // Đã đăng nhập: cập nhật trong DB như cũ
                $userId = $user->id;

                DB::beginTransaction();
                try {
                    $item = GiohangModel::where('id_nguoidung', $userId)
                        ->where('id', $id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $id_bienthe = $item->id_bienthe;

                    if ($soluongNew == 0) {
                        // Xóa sản phẩm + quà tặng
                        $freeItem = GiohangModel::where('id_nguoidung', $userId)
                            ->where('id_bienthe', $id_bienthe)
                            ->where('thanhtien', 0)
                            ->first();

                        if ($freeItem) {
                            $restoreQty = $freeItem->soluong;
                            DB::table('bienthe')->where('id', $id_bienthe)
                                ->update(['luottang' => DB::raw("luottang + {$restoreQty}")]);
                        }

                        GiohangModel::where('id_nguoidung', $userId)
                            ->where('id_bienthe', $id_bienthe)
                            ->delete();

                        DB::commit();

                        return $this->jsonResponse([
                            'status' => true,
                            'message' => 'Đã xóa sản phẩm và quà tặng khỏi giỏ hàng',
                        ]);
                    }

                    // Cập nhật sản phẩm (giữ nguyên logic khuyến mãi như bạn đã có)
                    $variant = BientheModel::lockForUpdate()->findOrFail($id_bienthe);
                    $priceUnit = $variant->giagoc;

                    // Tổng giỏ hiện tại (chỉ tính hàng có thanhtien > 0) //edit
                    $tongGiaHienTai = GiohangModel::where('id_nguoidung', $userId)
                        ->where('thanhtien', '>', 0)
                        ->sum('thanhtien');

                    // Tổng giỏ mới sau khi thêm sản phẩm
                    $tongGiaGioHang = $tongGiaHienTai + ($soluongNew * $priceUnit); //edit

                    $promotion = DB::table('quatang_sukien as qs')
                        ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                        ->where('qs.id_bienthe', $id_bienthe)
                        ->where('qs.dieukiensoluong', '<=', $soluongNew)
                        ->where('qs.dieukiengiatri', '<=', $tongGiaGioHang) //edit
                        ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                        ->select('qs.id', 'qs.dieukiensoluong as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                        ->first();

                    $numFreeNew = 0;
                    $thanhtien = $soluongNew * $priceUnit;

                    if ($promotion) {
                        $promotionCount = floor($soluongNew / $promotion->discount_multiplier);
                        // $numFreeNew = min($promotionCount, $promotion->current_luottang);
                        $numFreeNew = $promotionCount;
                        $numToPay = $soluongNew - $numFreeNew;
                        $thanhtien = $numToPay * $promotion->giagoc;
                    }

                    $freeItem = GiohangModel::where('id_nguoidung', $userId)
                        ->where('id_bienthe', $id_bienthe)
                        ->where('thanhtien', 0)
                        ->lockForUpdate()
                        ->first();

                    // Cập nhật sản phẩm chính
                    $item->update([
                        'soluong' => $soluongNew,
                        'thanhtien' => $thanhtien,
                        'trangthai' => 'Hiển thị',
                    ]);

                    // Cập nhật hoặc tạo/xóa quà tặng
                    if ($numFreeNew > 0) {
                        if ($freeItem) {
                            $freeItem->update([
                                'soluong' => $numFreeNew,
                                'trangthai' => 'Hiển thị',
                            ]);
                        } else {
                            GiohangModel::create([
                                'id_nguoidung' => $userId,
                                'id_bienthe' => $id_bienthe,
                                'soluong' => $numFreeNew,
                                'thanhtien' => 0,
                                'trangthai' => 'Hiển thị',
                            ]);
                        }
                    } else {
                        if ($freeItem) {
                            $freeItem->delete();
                        }
                    }

                    DB::commit();

                    GioHangResource::withoutWrapping();
                    $cartItems = GiohangModel::with([
                        'bienthe.sanpham.thuonghieu',
                        'bienthe.loaibienthe',
                        'bienthe.sanpham.hinhanhsanpham'
                    ])
                        ->where('id_nguoidung', $userId)
                        ->where('trangthai', 'Hiển thị')
                        ->get();

                    return response()->json(GioHangResource::collection($cartItems), Response::HTTP_OK);

                } catch (\Throwable $e) {
                    DB::rollBack();
                    return $this->jsonResponse([
                        'status' => false,
                        'message' => 'Lỗi khi cập nhật giỏ hàng',
                        'error' => $e->getMessage(),
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                // Cập nhật trong session, có xử lý quà tặng

                $sessionCart = $request->session()->get($this->cart_session, []);

                // Tìm sản phẩm chính trong session (thanhtien != 0)
                $foundKey = null;
                foreach ($sessionCart as $key => $item) {
                    if ($item['id_bienthe'] == $id && ($item['thanhtien'] ?? null) !== 0) {
                        $foundKey = $key;
                        break;
                    }
                }

                if ($foundKey === null) {
                    return $this->jsonResponse([
                        'status' => false,
                        'message' => 'Sản phẩm không tồn tại trong giỏ hàng session',
                    ], Response::HTTP_NOT_FOUND);
                }

                if ($soluongNew == 0) {
                    // Xóa sản phẩm chính
                    unset($sessionCart[$foundKey]);

                    // Đồng thời xóa quà tặng liên quan (thanhtien = 0)
                    foreach ($sessionCart as $key => $item) {
                        if ($item['id_bienthe'] == $id && ($item['thanhtien'] ?? null) === 0) {
                            unset($sessionCart[$key]);
                        }
                    }
                } else {
                    // Lấy biến thể và khuyến mãi
                    $variant = BientheModel::find($id);
                    $priceUnit = $variant ? $variant->giagoc : 0;

                    // 👉 Tính tổng giỏ hàng session hiện tại (chỉ tính sản phẩm có thanhtien > 0)
                    // $sessionCart = $request->session()->get($this->cart_session, []);
                    $tongGiaGioHangSession = 0;
                    foreach ($sessionCart as $item) {
                        if (($item['thanhtien'] ?? 0) > 0) {
                            $tongGiaGioHangSession += $item['thanhtien'];
                        }
                    }

                    $promotion = DB::table('quatang_sukien as qs')
                        ->join('bienthe as bt', 'qs.id_bienthe', '=', 'bt.id')
                        ->where('qs.id_bienthe', $id)
                        ->where('qs.dieukiensoluong', '<=', $soluongNew)
                        ->where('qs.dieukiengiatri', '<=', $tongGiaGioHangSession) //edit
                        ->whereRaw('NOW() BETWEEN qs.ngaybatdau AND qs.ngayketthuc')
                        ->select('qs.dieukiensoluong as discount_multiplier', 'bt.luottang as current_luottang', 'bt.giagoc')
                        ->first();

                    $numFreeNew = 0;
                    $thanhtien = $soluongNew * $priceUnit;

                    if ($promotion) {
                        $promotionCount = floor($soluongNew / $promotion->discount_multiplier);
                        // $numFreeNew = min($promotionCount, $promotion->current_luottang);
                        $numFreeNew = $promotionCount;
                        $numToPay = $soluongNew - $numFreeNew;
                        $thanhtien = $numToPay * $promotion->giagoc;
                    }

                    // Cập nhật sản phẩm chính
                    $sessionCart[$foundKey]['soluong'] = $soluongNew;
                    $sessionCart[$foundKey]['thanhtien'] = $thanhtien;

                    // Tìm quà tặng trong session
                    $freeKey = null;
                    foreach ($sessionCart as $key => $item) {
                        if ($item['id_bienthe'] == $id && ($item['thanhtien'] ?? null) === 0) {
                            $freeKey = $key;
                            break;
                        }
                    }

                    if ($numFreeNew > 0) {
                        if ($freeKey !== null) {
                            $sessionCart[$freeKey]['soluong'] = $numFreeNew;
                        } else {
                            // Thêm quà tặng mới
                            $sessionCart[] = [
                                'id_bienthe' => $id,
                                'soluong' => $numFreeNew,
                                'thanhtien' => 0,
                            ];
                        }
                    } else {
                        if ($freeKey !== null) {
                            unset($sessionCart[$freeKey]);
                        }
                    }
                }

                // Reset lại key mảng
                $sessionCart = array_values($sessionCart);

                // Lưu lại session mới
                $request->session()->put($this->cart_session, $sessionCart);

                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Cập nhật giỏ hàng thành công (session)',
                    'data' => $sessionCart,
                ], Response::HTTP_OK);
            }
        }




    /**
     * @OA\Delete(
     *     path="/web/giohang/{id}",
     *     summary="Xóa sản phẩm khỏi giỏ hàng",
     *     description="Xóa sản phẩm khỏi giỏ hàng. Nếu người dùng đã đăng nhập thì xóa trong database. Nếu chưa đăng nhập thì xóa trong session.",
     *     tags={"Giỏ Hàng (web)"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID sản phẩm trong giỏ hàng (hoặc ID biến thể nếu chưa đăng nhập)",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Xóa sản phẩm khỏi giỏ hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Xóa sản phẩm khỏi giỏ hàng thành công")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm trong giỏ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Sản phẩm không tồn tại trong giỏ hàng session")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Token không hợp lệ hoặc hết hạn (nếu có token)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token không hợp lệ")
     *         )
     *     )
     * )
     */

    public function destroy(Request $request, $id)
    {
        $user = $this->get_user_from_token($request);

        if ($user) {
            // Đã đăng nhập: xóa trong DB
            $userId = $user->id;

            $item = GiohangModel::where('id_nguoidung', $userId)
                ->where('id', $id)
                ->firstOrFail();

            $item->delete();

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công',
            ]);
        } else {
            // Chưa đăng nhập: xóa trong session
            $sessionCart = $request->session()->get($this->cart_session, []);

            // Tìm sản phẩm trong session dựa theo id biến thể (giả định $id là id_bienthe)
            $foundKey = null;
            foreach ($sessionCart as $key => $item) {
                if ($item['id_bienthe'] == $id) {
                    $foundKey = $key;
                    break;
                }
            }

            if ($foundKey === null) {
                return $this->jsonResponse([
                    'status' => false,
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng session',
                ], 404);
            }

            // Xóa sản phẩm khỏi session
            unset($sessionCart[$foundKey]);

            // Cập nhật lại session (reset key mảng)
            $request->session()->put($this->cart_session, array_values($sessionCart));

            return $this->jsonResponse([
                'status' => true,
                'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công (phiên chưa đăng nhập)',
                'data' => $request->session()->get($this->cart_session),
            ]);
        }
    }
}

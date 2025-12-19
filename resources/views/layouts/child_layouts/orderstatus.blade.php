<li class="submenu">
    <a href="javascript:void(0);"><i data-feather="shopping-cart"></i>
    <span>Bán hàng <span class="bg-danger text-white text-center rounded-circle blinking-flash px-2 py-1" style="font-size: 13px;">!</span></span>
    <span class="menu-arrow"></span></a>
    <ul>
    <!-- <li><a href="saleslist.html">Xác nhận thanh toán<span class="bg-warning text-white px-2 py-1 rounded-circle">4</span></a></li> -->
    <li><a class="{{ request()->routeIs('donhang.*') ? 'active' : '' }}" href="{{ route('donhang.index') }}">Chờ thanh toán đơn <span
            class="bg-warning text-white px-2 py-1 rounded-circle">4</span></a></li>
    <li><a class="{{ request()->routeIs('donhang.*') ? 'active' : '' }}" class="active" href="{{ route('donhang.index') }}">Xác nhận đơn hàng <span
            class="bg-warning text-white px-2 py-1 rounded-circle">4</span></a></li>
    <li><a class="{{ request()->routeIs('donhang.*') ? 'active' : '' }}" href="{{ route('donhang.index') }}">Đóng gói đơn hàng</a></li>
    <li><a class="{{ request()->routeIs('donhang.*') ? 'active' : '' }}" href="{{ route('donhang.index') }}">Vận chuyển đơn hàng</a></li>
    <li><a class="{{ request()->routeIs('donhang.*') ? 'active' : '' }}" href="{{ route('donhang.index') }}">Danh sách đơn hàng</a></li>

    </ul>
</li>

{{-- <ul>
    <li><a href="saleslist.html">Voucher</a></li>
    <li><a href="pos.html">Ưu đãi sản phẩm</a></li>
    <li><a href="pos.html">Sự kiện khuyến mãi</a></li>
    <li><a href="/">Đánh giá sản phẩm</a></li>
    <li><a href="pos.html">Danh sách đơn hàng</a></li>

</ul> --}}

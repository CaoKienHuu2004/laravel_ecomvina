@extends('layouts.app')

@section('title', 'Order Details | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Order Details</h4>
        <h6>Thông tin chi tiết đơn hàng</h6>
      </div>
    </div>

    <div class="row">
      <!-- Thông tin đơn hàng -->
      <div class="col-lg-8 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="bar-code-view mb-4">
              <img src="{{ asset('assets/img/barcode1.png') }}" alt="barcode">
              <a class="printimg" href="javascript:void(0)">
                <img src="{{ asset('assets/img/icons/printer.svg') }}" alt="print">
              </a>
            </div>

            <div class="productdetails">
              <ul class="product-bar">
                <li>
                  <h4>Mã đơn hàng</h4>
                  <h6>#{{ $donhang->id }}</h6>
                </li>
                <li>
                  <h4>Ngày đặt</h4>
                  <h6>{{ \Carbon\Carbon::parse($donhang->created_at)->format('H:i - d/m/Y') }}</h6>
                </li>
                <li>
                  <h4>Ngày giao</h4>
                  <h6>{{ $donhang->ngaygiao ? \Carbon\Carbon::parse($donhang->ngaygiao)->format('H:i - d/m/Y') : 'Chưa giao' }}</h6>
                </li>
                <li>
                  <h4>Tổng tiền</h4>
                  <h6 class="text-success">{{ number_format($donhang->thanhtien, 0, ',', '.') }} VND</h6>
                </li>
                <li>
                  <h4>Trạng thái</h4>
                  <h6>{{ $donhang->trangthai}}</h6>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Danh sách sản phẩm -->
      <div class="col-lg-4 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="slider-product-details">
              <div class="owl-carousel owl-theme product-slide">
                  <div class="slider-product text-center">
                    <img src="{{ $hinhanh->hinhanh ?? 'assets/img/product/default.jpg' }}" alt="img" style="max-height:200px;object-fit:cover;">
                @foreach($donhang->chitietdonhang as $item)
                    <h4 class="mt-2">{{ $item->bienthe->sanpham->ten ?? 'Sản phẩm' }}</h4>
                    <h6>Số lượng: {{ $item->soluong }}</h6>
                    <h6>Giá: {{ number_format($item->bienthe->giagoc, 0, ',', '.') }} VND</h6>
                    <h6 class="text-success">Thành tiền: {{ number_format($item->soluong * $item->bienthe->giagoc, 0, ',', '.') }} VND</h6>
                    @if($item->bienthe)
                      <small class="text-muted">Trạng thái sản phẩm: {{ $item->bienthe->trangthai ?? 'N/A' }}</small>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- row -->
  </div>
</div>
@endsection

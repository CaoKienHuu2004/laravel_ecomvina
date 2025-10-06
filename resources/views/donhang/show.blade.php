@extends('layouts.app')

@section('title', 'Order Details | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Order Details</h4>
        <h6>Full details of the order</h6>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="order-details">
              <ul class="order-bar">
                <li>
                  <h4>Order ID</h4>
                  <h6>{{ $donhang->id }}</h6>
                </li>
                <li>
                  <h4>Customer</h4>
                  <h6>{{ $donhang->khachhang->name ?? 'Unknown' }}</h6> <!-- Display customer name -->
                </li>
                <li>
                  <h4>Order Date</h4>
                  <h6>{{ $donhang->created_at->format('d/m/Y') }}</h6>
                </li>
                <li>
                  <h4>Delivery Date</h4>
                  <h6>{{ $donhang->delivery_date ? $donhang->delivery_date->format('d/m/Y') : 'N/A' }}</h6>
                </li>
                <li>
                  <h4>Total Amount</h4>
                  <h6>{{ number_format($donhang->tongtien, 2) }} VND</h6> <!-- Display total amount -->
                </li>
                <li>
                  <h4>Status</h4>
                  <h6>{{ $donhang->getTrangthaiTextAttribute() }}</h6> <!-- Use status text method -->
                </li>
                <li>
                  <h4>Items</h4>
                  <ul>
                    @foreach($donhang->chitiet as $item)
                      <li>
                        {{ $item->sanpham->ten ?? 'Product Name' }} - 
                        {{ $item->soluong }} x 
                        {{ number_format($item->gia, 2) }} VND
                        @if($item->bienthe)
                          <br><strong>Bienthe:</strong> {{ $item->bienthe->description ?? 'No Description' }}
                        @endif
                      </li>
                    @endforeach
                  </ul>
                </li>
                <li>
                  <h4>Description</h4>
                  <h6>{{ $donhang->ghichu ?? 'N/A' }}</h6>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush

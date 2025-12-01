@extends('layouts.app')

@section('title','Tạo đơn hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">

        <h4>TẠO ĐƠN HÀNG</h4>
        <h6>Nhập thông tin đơn hàng mới</h6>
      </div>
      <div class="page-btn">
        <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-added">
          <img src="{{ asset('assets/img/icons/arrow-left.svg') }}" class="me-1" alt="">Quay lại danh sách
        </a>
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('luu-don-hang') }}">
          @csrf

          <!-- ========== KHÁCH HÀNG ========== -->
          <div class="row">
            <div class="col-lg-6 col-sm-12">
              <div class="form-group custom-select-container">
                <label>Khách hàng <span class="text-danger">*</span></label>
                <div class="custom-select-wrapper">
                  <div class="custom-select-display" data-type="customer">-- Chọn khách hàng --</div>
                  <div class="custom-select-dropdown">
                    <input type="text" class="custom-search-input" placeholder="Tìm khách hàng...">
                    <div class="custom-options-list">
                      @foreach($customers as $kh)
                        <div class="custom-option" data-value="{{ $kh->id }}">
                          {{ $kh->hoten ?? $kh->username }} - {{ $kh->sodienthoai }}
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                <input type="hidden" name="id_nguoidung" id="selected-customer">
              </div>
            </div>
          </div>
          <!-- ========== SẢN PHẨM ========== -->
          <h5 class="mt-4 mb-2">Danh sách sản phẩm</h5>
          <table class="table table-bordered" id="product-table">
            <thead>
              <tr>
                <th style="width:40%">Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="custom-select-wrapper">
                    <div class="custom-select-display" data-type="product">-- Chọn sản phẩm --</div>
                    <div class="custom-select-dropdown">
                      <input type="text" class="custom-search-input" placeholder="Tìm sản phẩm...">
                      <div class="custom-options-list">
                        @foreach($products as $p)
                          @if($p->bienthe->count() > 0)
                            @foreach($p->bienthe as $bt)
                              <div class="custom-option" data-id="{{ $bt->id }}" data-price="{{ $bt->giagoc }}">
                                {{ $p->ten }}
                              </div>
                            @endforeach
                          @else
                            <div class="custom-option" data-id="{{ $p->id }}" data-price="0">
                              {{ $p->ten }} - (Chưa gắn giá sản phẩm)
                            </div>
                          @endif
                        @endforeach
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="products[0][id]" class="selected-product-id">
                </td>
                <td class="product-price">0</td>
                <td>
                  <div class="input-group">
                    <button type="button" class="btn btn-sm btn-light btn-minus">-</button>
                    <input type="number" name="products[0][qty]" class="form-control text-center qty-input" value="1" min="1">
                    <button type="button" class="btn btn-sm btn-light btn-plus">+</button>
                  </div>
                </td>
                <td class="product-total">0</td>
                <td><button type="button" class="btn btn-danger btn-sm btn-remove">X</button></td>
              </tr>
            </tbody>
          </table>

          <button type="button" class="btn btn-primary btn-sm" id="add-product">+ Thêm sản phẩm</button>

          <div class="mt-3 text-end">
            <h5>Tạm tính:<span id="grand-total" name="tamtinh">0</span> VNĐ</h5>
          </div>
            <input type="hidden" name="tongtien" id="tong-tien-input" value="0">
          </div>
              <!-- ========== PHƯƠNG THỨC THANH TOÁN ========== -->
          <div class="mb-3">
              <label for="id_phuongthuc" class="form-label">Phương thức thanh toán</label>
              <select name="id_phuongthuc" id="id_phuongthuc" class="form-select" required>
                  <option value="">-- Chọn phương thức thanh toán --</option>
                  @foreach($phuongthuc as $pt)
                      <option value="{{ $pt->id }}">{{ $pt->ten }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-3">
              <label for="id_phivanchuyen" class="form-label">Phí vận chuyển</label>
              <select name="id_phivanchuyen" id="id_phivanchuyen" class="form-select" required>
                  <option value="">-- Chọn phí vận chuyển --</option>
                  @foreach($phivanchuyen as $pvc)
                      <option value="{{ $pvc->id }}">{{ $pvc->ten }}</option>
                  @endforeach
              </select>
          </div>
          <div class="mb-3">
              <label for="id_diachigiaohang" class="form-label">Địa chỉ giao hàng</label>
              <select name="id_diachigiaohang" id="id_diachigiaohang" class="form-select" required>
                  <option value="">-- Chọn địa chỉ giao hàng --</option>
                  @foreach($diachigiaohang as $dcgh)
                      <option value="{{ $dcgh->id }}">{{ $dcgh->diachi }}</option>
                  @endforeach
              </select>
          </div>
          <!-- ========== TRẠNG THÁI ========== -->
          <div class="row mt-4">
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select" name="trangthai" required>
                  <option value="Chờ xử lý">Chờ xử lý</option>
                  <option value="Đã xác nhận">Đã xác nhận</option>
                  <option value="Đang chuẩn bị hàng">Đang chuẩn bị hàng</option>
                  <option value="Đang giao hàng">Đang giao hàng</option>
                  <option value="Đã giao hàng">Đã giao hàng</option>
                  <option value="Đã hủy">Đã hủy</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label for="id_magiamgia" class="form-label">Địa chỉ mã giảm giá</label>
              <select name="id_magiamgia" id="id_magiamgia" class="form-select" required>
                  <option value="">-- Chọn Mã Giảm Giá --</option>
                  @foreach($magiamgia as $mgg)
                      <option value="{{ $mgg->id }}">{{ $mgg->mota }}</option>
                  @endforeach
              </select>
          </div>
          </div>
          <div class="mt-3 text-end">
            <h5>Tổng tiền:<span id="grand-total" name="tongsoluong">0</span> VNĐ</h5>
          </div>
          <div class="text-end mt-3"> 
            <button type="submit" class="btn btn-submit me-2">Lưu</button>
            <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-cancel">Hủy</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/donhang_create.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/donhang_create.js') }}"></script>
@endpush
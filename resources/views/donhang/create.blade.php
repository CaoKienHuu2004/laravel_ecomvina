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
                              <div class="custom-option"
                                   data-id="{{ $bt->id }}"
                                   data-price="{{ $bt->gia }}">
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


          <!-- ========== PHƯƠNG THỨC THANH TOÁN + GHI CHÚ ========== -->
          <div class="mt-3 text-end">
            <h5>Tổng tiền: <span id="grand-total">0</span> đ</h5>
            <input type="hidden" name="tongtien" id="tong-tien-input" value="0">
          </div>
          <div class="mb-3">
              <label for="id_phuongthuc_thanhtoan" class="form-label">Phương thức thanh toán</label>
              <select name="id_phuongthuc_thanhtoan" id="id_phuongthuc_thanhtoan" class="form-select" required>
                  <option value="">-- Chọn phương thức thanh toán --</option>
                  @foreach($phuongthuc_thanhtoan as $pt)
                      <option value="{{ $pt->id }}">{{ $pt->ten }}</option>
                  @endforeach
              </select>
          </div>
          <div class="mb-3">
              <label for="ghichu" class="form-label">Ghi chú đơn hàng</label>
              <textarea name="ghichu" id="ghichu" class="form-control" rows="3" placeholder="Nhập ghi chú cho đơn hàng (nếu có)..."></textarea>
          </div>
          <!-- ========== TRẠNG THÁI + THANH TOÁN ========== -->
          <div class="row mt-4">
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select" name="trangthai" required>
                  <option value="cho_xac_nhan">Chờ xác nhận</option>
                  <option value="da_xac_nhan">Đã xác nhận</option>
                  <option value="dang_giao">Đang giao</option>
                  <option value="da_giao">Đã giao</option>
                  <option value="da_huy">Đã hủy</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Thanh toán <span class="text-danger">*</span></label>
                <select class="form-select" name="thanh_toan" required>
                  <option value="0">Chưa</option>
                  <option value="1">Đã thanh toán</option>
                  <option value="2">Hoàn</option>
                </select>
              </div>
            </div>
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

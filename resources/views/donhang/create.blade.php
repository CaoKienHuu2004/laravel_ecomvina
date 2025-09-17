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
          <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">Quay lại danh sách
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
          <div class="row">
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Mã đơn <span class="text-danger">*</span></label>
                <input type="text" name="ma_don" class="form-control" value="{{ old('ma_don') }}" placeholder="VD: DH2025-0001" required>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Tên khách</label>
                <input type="text" name="ten_khach" class="form-control" value="{{ old('ten_khach') }}" placeholder="Nguyễn Văn A">
              </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt" class="form-control" value="{{ old('sdt') }}" placeholder="09xx xxx xxx">
              </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Tổng tiền (đ)</label>
                <input type="number" step="1" min="0" name="tong_tien" class="form-control" value="{{ old('tong_tien', 0) }}">
              </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Trạng thái <span class="text-danger">*</span></label>
                <select class="select" name="trang_thai" required>
                  @foreach(['moi'=>'Mới','dang_xu_ly'=>'Đang xử lý','hoan_thanh'=>'Hoàn thành','huy'=>'Hủy'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('trang_thai')===$k?'selected':'' }}>{{ $v }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Thanh toán <span class="text-danger">*</span></label>
                <select class="select" name="thanh_toan" required>
                  @foreach(['chua'=>'Chưa','da_tt'=>'Đã TT','hoan'=>'Hoàn'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('thanh_toan')===$k?'selected':'' }}>{{ $v }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-submit me-2">Lưu</button>
            <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-cancel">Hủy</a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

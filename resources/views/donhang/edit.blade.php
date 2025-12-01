@extends('layouts.app')

@section('title', 'Chỉnh sửa đơn hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Chỉnh sửa đơn hàng</h4>
        <h6>Cập nhật thông tin đơn hàng</h6>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form action="{{ route('cap-nhat-don-hang', $donhang->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="card">
        <div class="card-body">
          <div class="row">
            {{-- Mã đơn hàng --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Mã đơn hàng</label>
                <input type="text" name="ma_donhang" class="form-control" 
                  value="{{ old('ma_donhang', $donhang->madon) }}" required>
              </div>
            </div>

            {{-- Khách hàng --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Khách hàng</label>
                <select class="select form-control" name="id_nguoidung" required>
                  @foreach($nguoidung as $nguoidung_item)
                    <option value="{{ $nguoidung_item->id }}" 
                      {{ old('id_nguoidung', $donhang->id_nguoidung) == $nguoidung_item->id ? 'selected' : '' }}>{{ $nguoidung_item->hoten }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            {{-- Tổng tiền --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Tổng tiền -VNĐ</label>
                <input type="number" name="tongtien" class="form-control"
                  value="{{ old('tongtien', $donhang->thanhtien) }}" required>
              </div>
            </div>

            {{-- Phương thức thanh toán --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select class="select form-control" name="id_phuongthuc" required>
                  <option value="">-- Chọn phương thức --</option>
                  @foreach($phuongthuc as $pt)
                    <option value="{{ $pt->id }}" 
                      {{ old('id_phuongthuc', $donhang->id_phuongthuc) == $pt->id ? 'selected' : '' }}>{{ $pt->ten }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Trạng thái --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Trạng thái</label>
                <select class="select form-control" name="trangthai" required>
                  <option value="Chờ xử lý" {{ old('trangthai', $donhang->trangthai) == 'Chờ xử lý' ? 'selected' : '' }}>Chờ xác nhận</option>
                  <option value="Đã xác nhận" {{ old('trangthai', $donhang->trangthai) == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                  <option value="Đang chuẩn bị hàng" {{ old('trangthai', $donhang->trangthai) == 'Đang chuẩn bị hàng' ? 'selected' : '' }}>Đang chuẩn bị hàng</option>
                  <option value="Đã xác nhận" {{ old('trangthai', $donhang->trangthai) == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option> 
                  <option value="Đang chuẩn bị hàng" {{ old('trangthai', $donhang->trangthai) == 'Đang chuẩn bị hàng' ? 'selected' : '' }}>Đang chuẩn bị hàng</option>
                  <option value="Đã hủy" {{ old('trangthai', $donhang->trangthai) == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>        
                </select>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-secondary">Hủy</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

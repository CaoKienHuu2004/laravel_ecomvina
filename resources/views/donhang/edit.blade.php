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
                  value="{{ old('ma_donhang', $donhang->ma_donhang) }}" required>
              </div>
            </div>

            {{-- Khách hàng --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Khách hàng</label>
                <select class="select form-control" name="khachhang_id" required>
                  <option value="">-- Chọn khách hàng --</option>
                  @foreach($khachhangs as $kh)
                    <option value="{{ $kh->id }}"
                      {{ old('khachhang_id', $donhang->khachhang_id) == $kh->id ? 'selected' : '' }}>
                      {{ $kh->hoten }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Tổng tiền --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Tổng tiền</label>
                <input type="number" name="tongtien" class="form-control"
                  value="{{ old('tongtien', $donhang->tongtien) }}" required>
              </div>
            </div>

            {{-- Phương thức thanh toán --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select class="select form-control" name="phuongthuc_thanhtoan_id" required>
                  <option value="">-- Chọn phương thức --</option>
                  @foreach($phuongthucs as $pt)
                    <option value="{{ $pt->id }}"
                      {{ old('phuongthuc_thanhtoan_id', $donhang->phuongthuc_thanhtoan_id) == $pt->id ? 'selected' : '' }}>
                      {{ $pt->tenphuongthuc }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Trạng thái --}}
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Trạng thái</label>
                <select class="select form-control" name="trangthai" required>
                  <option value="cho_xac_nhan" {{ old('trangthai', $donhang->trangthai) == 'cho_xac_nhan' ? 'selected' : '' }}>Chờ xác nhận</option>
                  <option value="da_giao" {{ old('trangthai', $donhang->trangthai) == 'da_giao' ? 'selected' : '' }}>Đã giao</option>
                  <option value="da_huy" {{ old('trangthai', $donhang->trangthai) == 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                </select>
              </div>
            </div>

            {{-- Ghi chú --}}
            <div class="col-lg-12 col-sm-12 col-12">
              <div class="form-group">
                <label>Ghi chú</label>
                <textarea class="form-control" name="ghichu" rows="3">{{ old('ghichu', $donhang->ghichu) }}</textarea>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-submit me-2">Cập nhật</button>
            <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-cancel">Hủy</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

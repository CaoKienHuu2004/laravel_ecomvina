@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('title','Chi tiết đơn hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>CHI TIẾT ĐƠN HÀNG</h4>
        <h6>Mã đơn: {{ $donhang->ma_don }}</h6>
      </div>
      <div class="page-btn">
        <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-added">
          <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">Quay lại danh sách
        </a>
        <a href="{{ route('chinh-sua-don-hang',$donhang->id) }}" class="btn btn-added ms-2">
          <img src="{{ asset('img/icons/edit.svg') }}" class="me-1" alt="">Chỉnh sửa
        </a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">Mã đơn</label>
              <div class="fw-semibold">{{ $donhang->ma_don }}</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">Khách hàng</label>
              <div class="fw-semibold">{{ $donhang->ten_khach ?? '—' }}</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">SĐT</label>
              <div class="fw-semibold">{{ $donhang->sdt ?? '—' }}</div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">Tổng tiền</label>
              <div class="fw-semibold">{{ number_format($donhang->tong_tien,0,',','.') }} đ</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">Trạng thái</label>
              <div class="fw-semibold">{{ Str::of($donhang->trang_thai)->replace('_',' ')->title() }}</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">Thanh toán</label>
              <div class="fw-semibold">
                {{ $donhang->thanh_toan === 'da_tt' ? 'Đã TT' : ($donhang->thanh_toan === 'hoan' ? 'Hoàn' : 'Chưa') }}
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">Ngày tạo</label>
              <div class="fw-semibold">{{ optional($donhang->created_at)->format('H:i - d/m/Y') }}</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="text-muted">Ngày cập nhật</label>
              <div class="fw-semibold">{{ optional($donhang->updated_at)->format('H:i - d/m/Y') }}</div>
            </div>
          </div>
        </div>

        {{-- Nếu có chi tiết đơn hàng, render bảng bên dưới --}}
        @isset($donhang->chitiets)
        <div class="table-responsive mt-3">
          <table class="table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>SL</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              @forelse($donhang->chitiets as $ct)
              <tr>
                <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $ct->ten_san_pham ?? ($ct->sanpham->ten ?? '') }}">
                  {{ $ct->ten_san_pham ?? ($ct->sanpham->ten ?? '—') }}
                </td>
                <td>{{ $ct->so_luong }}</td>
                <td>{{ number_format($ct->don_gia,0,',','.') }} đ</td>
                <td>{{ number_format($ct->so_luong * $ct->don_gia,0,',','.') }} đ</td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center">Chưa có chi tiết</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @endisset

      </div>
    </div>
  </div>
</div>
@endsection

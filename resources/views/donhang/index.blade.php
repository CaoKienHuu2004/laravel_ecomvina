@extends('layouts.app')

@section('title','Danh sách đơn hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH ĐƠN HÀNG</h4>
        <h6>Quản lý {{ method_exists($donhangs,'total') ? $donhangs->total() : $donhangs->count() }} đơn hàng</h6>
      </div>
      <div class="page-btn">
        <a href="{{ route('tao-don-hang') }}" class="btn btn-added">
          <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1">Tạo đơn hàng
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card mb-0" id="filter_inputs">
      <div class="card-body pb-0">
        <label class="mb-2"><strong>Lọc đơn hàng</strong></label>
        <form id="filterForm" class="row" method="GET" action="{{ route('danh-sach-don-hang') }}">
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <input type="text" class="form-control" name="ma_donhang" value="{{ request('ma_donhang') }}" placeholder="Mã đơn">
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <select class="select" name="trangthai">
                <option value="">--Trạng thái--</option>
                @foreach(['cho_xac_nhan'=>'Chờ xác nhận','da_giao'=>'Đã giao','da_huy'=>'Đã hủy'] as $k=>$v)
                  <option value="{{ $k }}" {{ request('trangthai') == $k ? 'selected' : '' }}>{{ $v }}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group row">
              <a class="btn btn-outline-danger col-lg-3" href="{{ route('danh-sach-don-hang') }}">X</a>
              <button type="submit" class="btn btn-filters ms-2 col-lg-3">
                <img src="{{ asset('img/icons/search-whites.svg') }}" alt="img">
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table datanew">
        <thead>
          <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($donhangs as $dh)
          <tr>
            <td class="text-nowrap">
              <a href="{{ route('chi-tiet-don-hang', $dh->id) }}" title="Xem chi tiết">{{ $dh->ma_donhang }}</a>
            </td>
            <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $dh->khachhang->name ?? '—' }}">
              {{ $dh->khachhang->hoten ?? '—' }}
            </td>
            <td>{{ $dh->tongtien }} đ</td>
            <td>{{ $dh->trangthai_text }}</td>
            <td>
              {{ \Carbon\Carbon::parse($dh->ngaytao)->format('H:i - d/m/Y') }}
            </td>
            <td>
              <a class="me-3" href="{{ route('chi-tiet-don-hang', $dh->id) }}" title="Xem chi tiết">
                <img src="{{ asset('img/icons/eye.svg') }}" alt="img">
              </a>
              <a class="me-3" href="{{ route('chinh-sua-don-hang', $dh->id) }}" title="Chỉnh sửa">
                <img src="{{ asset('img/icons/edit.svg') }}" alt="img">
              </a>
              <form class="d-inline" method="POST" action="{{ route('xoa-don-hang', $dh->id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                @csrf @method('DELETE')
                <button class="btn p-0 border-0 bg-transparent" title="Xóa">
                  <img src="{{ asset('img/icons/delete.svg') }}" alt="img">
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center">Chưa có đơn hàng</td></tr>
          @endforelse
        </tbody>
      </table>
      @if(method_exists($donhangs,'links'))
        {{ $donhangs->withQueryString()->links() }}
      @endif
    </div>
  </div>
</div>
@endsection

@section('scripts')
<style>.dt-buttons{display:none!important;}</style>
@endsection

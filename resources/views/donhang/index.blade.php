@extends('layouts.app')

@section('title','Danh sách đơn hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Danh sách đơn hàng</h4>
            <h6>Danh sách những đơn hàng đang có trên hệ thống.</h6>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-sm-6 col-12">
            <a href="#" class="dash-widget dash1">
              <div class="dash-widgetimg">
                <span><i data-feather="package" style="color: #1fb163;"></i></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>
                  <span class="counters">100</span>
                </h5>
                <h6>Tổng số đơn hàng</h6>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <a href="#" class="dash-widget dash2">
              <div class="dash-widgetimg">
                <span><i data-feather="share" style="color: #0093e8;"></i></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>
                  <span class="counters">80</span>
                </h5>
                <h6>Đơn hoàn thành</h6>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <a href="#" class="dash-widget dash3">
              <div class="dash-widgetimg">
                <span><i data-feather="x-circle" style="color:#ea5454;"></i></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>
                  <span class="counters">20</span>
                </h5>
                <h6>Đã hủy đơn</h6>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <a href="#" class="dash-widget dash">
              <div class="dash-widgetimg">
                <span><i data-feather="alert-circle" style="color:#ff8f07;"></i></span>
              </div>
              <div class="dash-widgetcontent">
                <h5>
                  <span class="counters">76</span>
                </h5>
                <h6>Chờ xác nhận đơn</h6>
              </div>
            </a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-top">
              <div class="search-set">
                <div class="search-path">

                </div>
                <div class="search-input">
                  <a class="btn btn-searchset"><img src="{{asset('img/icons/search-white.svg')}}" alt="img" /></a>
                </div>
              </div>
              <div class="wordset">
                <ul>
                  <li>
                    <button id="export-pdf-button" class="bg-white border-0 p-0" data-bs-toggle="tooltip"
                      data-bs-placement="top" title="pdf"><img src="{{asset('img/icons/pdf.svg')}}" alt="img" /></button>
                      {{-- data-bs-placement="top" title="pdf"><img src="assets/img/icons/pdf.svg" alt="img" /></button> --}}
                  </li>
                  <li>
                    <button id="export-excel-button" class="bg-white border-0 p-0" data-bs-toggle="tooltip"
                      data-bs-placement="top" title="excel"><img src="{{asset('img/icons/excel.svg')}}" alt="img" /></button>
                      {{-- data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg" alt="img" /></button> --}}
                  </li>
                  <li>
                    <button id="print-button" class="bg-white border-0 p-0" data-bs-toggle="tooltip"
                      data-bs-placement="top" title="printer"><img src="{{asset('img/icons/printer.svg')}}"
                      {{-- data-bs-placement="top" title="printer"><img src="assets/img/icons/printer.svg" --}}
                        alt="img" /></button>
                  </li>
                </ul>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table datanew">
                <!-- có thể thêm datanew sau class table -->
                <thead>
                  <tr>
                    <th class="text-start">Mã đơn</th>
                    <th class="text-start" style="width: 20px !important;">Địa chỉ giao hàng</th>
                    <th class="text-start">Tổng cộng</th>
                    <th class="text-start">Ngày đặt hàng</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-start">
                      <a class="fw-bold" href="javascript:void(0);">#STV25120944</a>
                    </td>
                    <td class="text-start">
                      <p class="fw-bold text-black m-0" style="font-size: 14px;">Trần Bá Hộ</p>
                      <div class="text-black" style="font-size: 14px; width: 340px; white-space: break-spaces; overflow: hidden; text-overflow: ellipsis;">801/2A Phạm Thế Hiển, Phường 4, Quận 8, Thành phố Hồ Chí Minh</div>
                    </td>
                    <td class="text-start text-black fw-bold"><span class="text-danger">300.000 đ</span></td>
                    <td class="text-start text-black" style="width: 50px;">
                      09/12/2025 - 16:59
                    </td>
                    <td class="text-center"><span class="badges bg-lightyellow">Chờ xác nhận</span></td>
                    <td class="text-center">
                      <a class="me-3" href="sales-details.html">
                        <i data-feather="eye" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết đơn hàng"></i>
                      </a>
                      <!-- <a href="javascript: void(0);" id="alert-confirm"
                                    class="btn btn-primary waves-effect waves-light">Click me</a> -->
                      <a class="" href="javascript:void(0);">
                        <i data-feather="x-circle" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Xác nhận hủy đơn"></i>
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-start">
                      <a class="fw-bold" href="javascript:void(0);">#STV25120944</a>
                    </td>
                    <td class="text-start">
                      <p class="fw-bold text-black m-0" style="font-size: 14px;">Trần Bá Hộ</p>
                      <div class="text-black" style="font-size: 14px; width: 340px; white-space: break-spaces; overflow: hidden; text-overflow: ellipsis;">801/2A Phạm Thế Hiển, Phường 4, Quận 8, Thành phố Hồ Chí Minh</div>
                    </td>
                    <td class="text-start text-black fw-bold"><span class="text-danger">300.000 đ</span></td>
                    <td class="text-start text-black" style="width: 50px;">
                      09/12/2025 - 16:59
                    </td>
                    <td class="text-center"><span class="badges bg-lightpurple">Chờ thanh toán</span></td>
                    <td class="text-center">
                      <a class="me-3" href="sales-details.html">
                        <i data-feather="eye" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết đơn hàng"></i>
                      </a>
                      <a class="" href="javascript:void(0);">
                        <i data-feather="x-circle" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Xác nhận hủy đơn"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection

@section('scripts')

@endsection

{{-- <div class="page-wrapper">
    <div class="content">
    <div class="page-header">
        <div class="page-title">
        <h4>Xác nhận đơn hàng <span class="bg-danger text-white text-center rounded-circle blinking-flash px-2 py-1" style="font-size: 13px;">!</span></h4>
        <h6>Những đơn hàng đang chờ xác nhận từ bạn</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
        <div class="table-top">
            <div class="search-set">
            <div class="search-path">

            </div>
            <div class="search-input">
                <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img" /></a>
            </div>
            </div>
            <div class="wordset">
            <ul>
                <li>
                <button id="export-pdf-button" class="bg-white border-0 p-0" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="pdf"><img src="assets/img/icons/pdf.svg" alt="img" /></button>
                </li>
                <li>
                <button id="export-excel-button" class="bg-white border-0 p-0" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg" alt="img" /></button>
                </li>
                <li>
                <button id="print-button" class="bg-white border-0 p-0" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="printer"><img src="assets/img/icons/printer.svg"
                    alt="img" /></button>
                </li>
            </ul>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table datanew">
            <!-- có thể thêm datanew sau class table -->
            <thead>
                <tr>
                <th class="text-start">Mã đơn</th>
                <th class="text-start" style="width: 20px !important;">Địa chỉ giao hàng</th>
                <th class="text-start">Tổng cộng</th>
                <th class="text-start">Ngày đặt hàng</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td class="text-start">
                    <a class="fw-bold" href="javascript:void(0);">#STV25120944</a>
                </td>
                <td class="text-start">
                    <p class="fw-bold text-black m-0" style="font-size: 14px;">Trần Bá Hộ</p>
                    <div class="text-black" style="font-size: 14px; width: 340px; white-space: break-spaces; overflow: hidden; text-overflow: ellipsis;">801/2A Phạm Thế Hiển, Phường 4, Quận 8, Thành phố Hồ Chí Minh</div>
                </td>
                <td class="text-start text-black fw-bold"><span class="text-danger">300.000 đ</span></td>
                <td class="text-start text-black" style="width: 50px;">
                    09/12/2025 - 16:59
                </td>
                <td class="text-center"><span class="badges bg-lightyellow">Chờ xác nhận</span></td>
                <td class="text-center">
                    <a class="me-3" href="editproduct.html">
                    <i data-feather="check-circle" class="text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Xác nhận đơn hàng"></i>
                    </a>
                    <a class="confirm-text" href="javascript:void(0);">
                    <i data-feather="x-circle" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Từ chối và hủy đơn"></i>
                    </a>
                </td>
                </tr>
                <tr>
                <td class="text-start">
                    <a class="fw-bold" href="javascript:void(0);">#STV25120944</a>
                </td>
                <td class="text-start">
                    <p class="fw-bold text-black m-0" style="font-size: 14px;">Trần Bá Hộ</p>
                    <div class="text-black" style="font-size: 14px; width: 340px; white-space: break-spaces; overflow: hidden; text-overflow: ellipsis;">801/2A Phạm Thế Hiển, Phường 4, Quận 8, Thành phố Hồ Chí Minh</div>
                </td>
                <td class="text-start text-black fw-bold"><span class="text-danger">300.000 đ</span></td>
                <td class="text-start text-black" style="width: 50px;">
                    09/12/2025 - 16:59
                </td>
                <td class="text-center"><span class="badges bg-lightpurple">Chờ thanh toán</span></td>
                <td class="text-center">
                    <a class="me-3" href="editproduct.html">
                    <i data-feather="check-circle" class="text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Xác nhận đơn hàng"></i>
                    </a>
                    <a class="confirm-text" href="javascript:void(0);">
                    <i data-feather="x-circle" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Từ chối và hủy đơn"></i>
                    </a>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
</div> --}}
{{-- <div class="page-wrapper">
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
</div> --}}

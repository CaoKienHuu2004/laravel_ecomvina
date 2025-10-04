@extends('layouts.app')

@section('title','Danh sách khách hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')

<div class="page-wrapper">
        <div class="content">
          <div class="page-header">
            <div class="page-title">
              <h4>Danh sách khách hàng</h4>
              <h6>Quản lý thông tin khách hàng của bạn.</h6>
            </div>
            <div class="page-btn">
                <a href="{{route('tao-khach-hang')}}" class="btn btn-added">
                    <img src="{{asset('')}}img/icons/plus.svg" alt="img" />Thêm khách hàng
                </a>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="table-top">
                <div class="search-set">
                  <div class="search-input">
                    <a class="btn btn-searchset"
                      ><img src="{{asset('')}}img/icons/search-white.svg" alt="img"
                    /></a>
                  </div>
                </div>

              </div>



              <div class="table-responsive">
                <table class="table datanew">
                  <thead>
                    <tr>
                      <th>Tên khách hàng</th>
                      <th>Email</th>
                      <th>Số điện thoại</th>
                      <th>Địa chỉ (thành phố)</th>
                      <th>Trạng thái</th>
                      <th>Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($danhsach as $ds)
                      <tr>
                        <td class="productimgname">
                          <a href="javascript:void(0);" class="product-img">
                            <img
                              src="{{ asset('img/customer/' . $ds->avatar) }}"
                              alt="product"
                            />
                          </a>
                          <a href="javascript:void(0);">{{ $ds->hoten }}</a>
                        </td>
                        <td>
                          <a href="mailto:{{$ds->email}}" class="__cf_email__" data-cfemail="1165797e7c7062517469707c617d743f727e7c">
                              {{$ds->email}}
                          </a>
                        </td>
                        <td>{{$ds->sodienthoai}}</td>
                        <td>
                          Đài Trung
                        </td>
                        <td>
                          <span class="badges bg-lightyellow">Tạm khóa</span>
                        </td>
                        <td>
                          <a class="me-3" href="{{ route('chi-tiet-khach-hang', ['id' => $ds->id]) }}" title="xem chi tiết">
                              <img src="{{asset('img/icons/eye.svg')}}" alt="img" />
                          </a>
                          <a class="me-3" href="{{ route('chinh-sua-khach-hang', ['id' => $ds->id]) }}">
                            <img src="{{asset('')}}img/icons/edit.svg" alt="img" />
                          </a>
                          <a class="me-3 confirm-text" href="javascript:void(0);">
                            <img src="{{asset('')}}img/icons/delete.svg" alt="img" />
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
@section('scripts')
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
@endsection

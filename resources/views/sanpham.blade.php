@extends('layouts.app')

@section('title', 'Danh sách sản phẩm | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH SẢN PHẨM</h4>
        <h6>
          Quản lý {{ $sanphams->where('trangthai', 0)->count() }} sản phẩm của bạn
        </h6>
      </div>
      <div class="page-btn">
        <a href="{{route('tao-san-pham')}}" class="btn btn-added"><img
            src="{{asset('img/icons/plus.svg')}}"
            alt="img"
            class="me-1" />Tạo sản phẩm</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            <div class="search-path">
              <!-- id="filter_search" -->
              <a class="btn btn-filter" id="filter_search">
                <img src="{{ asset('img/icons/filter.svg') }}" alt="img">
                <span><img src="{{ asset('img/icons/closes.svg') }}" alt="img"></span>
              </a>
            </div>
            <div class="search-input">
              <a class="btn btn-searchset"><img src="{{asset('img/icons/search-white.svg')}}" alt="img" /></a>
            </div>
          </div>

        </div>

        <div class="card mb-0" id="filter_inputs"> <!-- id="filter_inputs" -->
          <div class="card-body pb-0">
            <label for="" class="mb-2"><strong>Lọc danh sách sản phẩm</strong></label>
            <div class="row">
              <form id="filterForm" class="col-lg-12 col-sm-12" method="GET" action="{{ route('danh-sach') }}">
                <div class="row">
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select" name="danhmuc">
                        <option value="">--Danh mục--</option>
                        @foreach($danhmucs as $dm)
                        <option value="{{ $dm->id }}" {{ request('danhmuc') == $dm->id ? 'selected' : '' }}>
                          {{ $dm->ten }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select" name="thuonghieu">
                        <option value="">--Thương hiệu--</option>
                        @foreach($thuonghieus as $th)
                        <option value="{{ $th->id }}" {{ request('thuonghieu') == $th->id ? 'selected' : '' }}>
                          {{ $th->ten }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <input type="number" class="form-control" name="gia_min" value="{{ request('gia_min') }}" placeholder="giá nhỏ nhất">
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <input type="number" class="form-control" name="gia_max" value="{{ request('gia_max') }}" placeholder="giá lớn nhất">
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group row">
                      <a class="btn btn-outline-danger col-lg-3" href="{{ route('danh-sach') }}">X</a>
                      <button type="submit" class="btn btn-filters ms-2 col-lg-3">
                        <img src="{{asset('img/icons/search-whites.svg')}}" alt="img" />
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table datanew">
            <thead>
              <tr>
                <!-- <th>
                        <label class="checkboxs">
                          <input type="checkbox" id="select-all" />
                          <span class="checkmarks"></span>
                        </label>
                      </th> -->
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Giá</th>
                <th>Loại</th>
                <th>Số lượng</th>
                <th>Lượt mua</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>

              @foreach($sanphams as $sp)
              <tr>
                <!-- <td>
                        <label class="checkboxs">
                          <input type="checkbox" />
                          <span class="checkmarks"></span>
                        </label>
                      </td> -->
                <td class="productimgname">
                  <a href="{{url('/')}}" class="product-img">
                    <img
                      src="{{ asset('img/product/' . $sp->anhSanPham->first()->media) }}"
                      alt="Not found" />
                  </a>
                  <a href="{{url('/')}}" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$sp->ten}}">{{$sp->ten}}</a>
                </td>
                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{!! $sp->danhmuc->pluck('ten')->implode(', ') ?: 'Chưa có danh mục' !!}">{!! $sp->danhmuc->pluck('ten')->implode(', ') ?: 'Chưa có danh mục' !!}</td>
                <td>{{ $sp->thuonghieu->ten ?? 'Không có' }}</td>
                <td>
                  @if($sp->bienthe->count())
                  @php
                  $giaMin = $sp->bienthe->min('gia');
                  $giaMax = $sp->bienthe->max('gia');
                  @endphp

                  {{ number_format($sp->bienthe->min('gia'), 0, ',', '.') }} đ

                  {{-- Chỉ hiển thị giá max nếu > giá min --}}
                  @if($giaMax > $giaMin)
                  ~ {{ number_format($giaMax, 0, ',', '.') }} đ
                  @endif
                  @else
                  Chưa có giá
                  @endif
                </td>
                <td>
                  @php
                  $tenbt = $sp->bienthe->pluck('loaiBienThe.ten')->implode(', ');
                  @endphp
                  {{$tenbt ?: 'Không có biến thể'}}
                </td>
                <td>{{ $sp->bienthe->sum('soluong') }}</td>
                <td>Không có</td>
                <td>{{ $sp->updated_at->format('H:j - d/m/Y') }}</td>
                <td>
                  <a class="me-3" href="{{url('/')}}">
                    <img src="{{asset('img/icons/eye.svg')}}" alt="img" />
                  </a>
                  <a class="me-3" href="{{url('/')}}">
                    <img src="{{asset('img/icons/edit.svg')}}" alt="img" />
                  </a>
                  <a class="confirm-text" href="{{url('/')}}">
                    <img src="{{asset('img/icons/delete.svg')}}" alt="img" />
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
<script>
  document.getElementById('filterForm').addEventListener('submit', function(e) {
    this.querySelectorAll('input, select').forEach(function(el) {
      if (!el.value) {
        el.removeAttribute('name'); // xoá name để nó không lên URL
      }
    });
  });
</script>
@endsection
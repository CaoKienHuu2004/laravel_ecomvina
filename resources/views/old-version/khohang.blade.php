@extends('layouts.app')

@section('title', 'Danh sách hàng tồn kho | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH HÀNG TỒN KHO</h4>
        <h6>
          Quản lý {{ $bienthe->count() }} hàng tồn kho của bạn
        </h6>
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
    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">

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
                <th>Loại hàng</th>
                <th>Giá mặt hàng</th>
                <th>Số lượng</th>
                <th>Tổng tồn</th>
                <th>Trạng thái</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>

              @foreach($sanphams as $sp)
              @foreach ($sp->bienthe as $bt)


              <tr>
                <!-- <td>
                        <label class="checkboxs">
                          <input type="checkbox" />
                          <span class="checkmarks"></span>
                        </label>
                      </td> -->
                <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  <a href="{{route('chi-tiet-san-pham',['id' => $sp->id, 'slug' => Str::slug($sp->ten)])}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$sp->ten}}">{{$sp->ten}}</a>
                </td>
                <td>
                  {{ $bt->loaiBienThe->ten }}
                </td>
                <td>

                  {{ number_format($bt->gia, 0, ',', '.')  }} đ
                </td>

                <td>{{ $bt->soluong ? $bt->soluong : 0 }}</td>
                <td>
                  @if ($bt->soluong > 0)
                    {{ number_format($bt->gia * $bt->soluong, 0, ',', '.')}} đ
                  @else
                      Không có
                  @endif
                </td>
                <td>
                  @if ($bt->soluong > 10)
                  <span class="badges bg-lightgreen">Còn hàng</span>
                  @elseif ($bt->soluong === 0)
                  <span class="badges bg-lightred">Hết hàng</span>
                  @else
                  <span class="badges bg-lightyellow">Sắp hết hàng</span>
                  @endif
                </td>
                <td>{{ $bt->updated_at->format('H:j - d/m/Y') }}</td>
                <td align="center">
                  <a class="me-3" href="{{ route('chinh-sua-hang-ton-kho',$bt->id) }}">
                    <img src="{{asset('img/icons/edit.svg')}}" alt="img" />
                  </a>

                  <a class="me-3" href="{{ route('xoa-hang-ton-kho',$bt->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa hàng tồn kho này?');">
                    <img src="{{asset('img/icons/delete.svg')}}" alt="img" />
                  </a>
                </td>
              </tr>
              @endforeach
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

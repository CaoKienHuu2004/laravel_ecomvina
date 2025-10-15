@extends('layouts.app')

@section('title', 'Danh sách sản phẩm | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH SẢN PHẨM</h4>
        <h6>
            {{-- Đếm sản phẩm có ít nhất 1 biến thể hết hàng --}}
            {{ $sanphams->filter(function ($sp) {
                return $sp->bienThe->where('soluong', 0)->count() > 0;
            })->count() }} sản phẩm có biến thể hết hàng
            <br>

            {{-- Đếm sản phẩm đang hoạt động --}}
            {{ $sanphams->where('trangthai', 'hoat_dong')->count() }} sản phẩm đang hoạt động
            <br>

            {{-- Đếm sản phẩm bị ngưng hoạt động --}}
            {{ $sanphams->where('trangthai', 'ngung_hoat_dong')->count() }} sản phẩm bị ngưng hoạt động
        </h6>
      </div>
      <div class="page-btn">
        <a href="{{route('tao-san-pham')}}" class="btn btn-added"><img
            src="{{asset('img/icons/plus.svg')}}"
            alt="img"
            class="me-1" />Tạo sản phẩm</a>
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
                        <option value="">--Cửa hàng--</option>
                        @foreach($cuaHang as $ch)
                        <option value="{{ $ch->id }}" {{ request('cuahang') == $ch->id ? 'selected' : '' }}>
                          {{ $ch->ten_cuahang }}
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
                <th>Cửa hàng</th>
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

                <style>
                    .center-cell {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    }
                    .center-content {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        text-align: center;
                        margin: 0;
                    }
                </style>
                <td class="productimgname center-cell" >
                    <a href="{{ url('/') }}" class="product-img">
                        <img
                            src="{{ asset('storage/' . ($sp->anhSanPham->first()->media ?? 'uploads/anh_sanpham/media/anh_sanpham.png')) }}"
                            alt="{{ $sp->ten ?? 'Sản phẩm' }}"
                        />
                    </a>
                  <a href="{{url('/')}}" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$sp->ten}}">{{$sp->ten}}</a>
                </td>
                <td title="{!! $sp->danhmuc->pluck('ten')->implode(', ') ?: 'Chưa có danh mục' !!}">
                    @php
                        $danhmucText = $sp->danhmuc->pluck('ten')->implode(', ');
                    @endphp

                    @if ($danhmucText)
                        <span class="text-success">
                            {!! wordwrap($danhmucText, 20, '<br>') !!}
                        </span>
                    @else
                        <span>Chưa có danh mục</span>
                    @endif
                </td>
                @php
                    $tenCuaHang = $sp->cuahang->ten_cuahang ?? 'Không có';
                @endphp
                <td>{!! nl2br(e(wordwrap($tenCuaHang, 12, "\n"))) !!}</td>
                <td>
                  @if($sp->bienthe->count())
                    @php
                    $giaMin = $sp->bienthe->min('gia');
                    $giaMax = $sp->bienthe->max('gia');
                    @endphp
                    <pre class="text-center"><span class="text-success">{!! wordwrap((number_format($sp->bienthe->min('gia'), 0, ',', '.').'đ'), 15, "\n") !!}</span></pre>
                    {{-- {{ number_format($sp->bienthe->min('gia'), 0, ',', '.') }} đ --}}
                    {{-- Chỉ hiển thị giá max nếu > giá min --}}
                    @if($giaMax > $giaMin)
                    {{-- ~ {{ number_format($giaMax, 0, ',', '.') }} đ --}}
                    <pre class="text-center"><span class="center-content">~</span><span class="text-success ">{!! wordwrap((number_format($giaMax, 0, ',', '.').'đ'), 15, "\n") !!}</span></pre>
                    @endif
                  @else
                  Chưa có giá
                  @endif
                </td>
                <td>
                  @php
                  $tenbt = $sp->bienthe->pluck('loaiBienThe.ten')->implode(', ');
                  @endphp
                  {{-- <pre>{{ wordwrap($tenbt ?: 'Không có biến thể', 12, "\n") }}</pre> --}}

                    @if (!empty($tenbt))
                        <pre><span class="text-success">{!! wordwrap($tenbt, 12, "\n") !!}</span></pre>
                    @else
                        <pre>{!! wordwrap('Không có biến thể', 12, "\n") !!}</pre>
                    @endif

                </td>
                {{-- <td>{{ $sp->bienthe->sum('soluong') }}</td> --}}
                <td>
                    @if ($sp->bienThe->isEmpty())
                        <pre><span class="text-muted">{!! wordwrap('Không có biến thể', 12, '<br>') !!}</span></pre>
                    @elseif ($sp->bienThe->sum('soluong') == 0)
                        <span class="text-danger">Hết hàng</span>
                    @else
                        <span class="text-success">{{ $sp->bienThe->sum('soluong') }}</span>

                    @endif
                </td>
                @php
                    $tongChiTietDonHang = $sp->bienThe->flatMap->chiTietDonHang->count();
                @endphp

                <td>{{ $tongChiTietDonHang }}</td>
                <td>
                    <pre>{!! wordwrap($sp->updated_at->format('d/m/Y H:j'), 10, "\n") !!}</pre>
                    {{-- {{ $sp->updated_at->format('H:j - d/m/Y') }} --}}

                </td>
                <td>
                  <a class="me-3" href="{{ route('chi-tiet-san-pham', ['id' => $sp->id, 'slug' => Str::slug($sp->ten)]) }}" title="xem chi tiết">
                    <img src="{{asset('img/icons/eye.svg')}}" alt="img" />
                  </a>
                  <a class="me-3" href="{{ route('chinh-sua-san-pham',$sp->id) }}">
                    <img src="{{asset('img/icons/edit.svg')}}" alt="img" />
                  </a>

                  <a class="me-3" href="{{route('xoa-san-pham', $sp->id)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
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

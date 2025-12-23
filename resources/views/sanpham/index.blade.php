@extends('layouts.app')

@section('title', 'Danh sách sản phẩm | Quản trị hệ thống Siêu Thị Vina')

{{-- // controller truyền xuống $sanphams,$thuonghieus danhmucs  --}}
{{-- // các route sư dụng sanpham.create sanpham.trash sanpham.index sanpham.index(?param) sanpham.show sanpham.edit sanpham.destroy   --}}
{{--  $sanphams->hinhanhsanpham->first()->hihanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}

{{-- bản củ // controller truyền xuống $sanphams,$thuonghieus danhmucs  --}}
{{-- {{-- dir_part asset storage/uploads/anh_sanpham/media/anh_sanpham.png --}}
{{--  tao-san-pham danh-sach xoa-san-pham chinh-sua-san-pham chi-tiet-san-pham   --}}



{{-- bỏ luottang , them trangthai, danh muc tren gia--}}
@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH SẢN PHẨM</h4>
        <h6>
            {{-- Đếm sản phẩm có ít nhất 1 biến thể hết hàng --}}
            {{ $sanphams->filter(function ($sp) {
                return $sp->bienthe->where('soluong', 0)->count() > 0;
            })->count() }} sản phẩm có biến thể hết hàng
            <br>
            {{ $sanphams->where('trangthai', 'Công khai')->count() }} Công khai sản phẩm
            <br>
            {{ $sanphams->where('trangthai', 'Chờ duyệt')->count() }} Chờ duyệt sản phẩm
            <br>

            {{ $sanphams->where('trangthai', 'Tạm ẩn')->count() }} Tạm ẩn sản phẩm
            <br>

            {{ $sanphams->where('trangthai', 'Tạm khóa')->count() }} Tạm khóa sản phẩm
        </h6>
      </div>
      <div class="d-flex">
        <div class="page-btn">
            <a href="{{route('sanpham.create')}}" class="btn btn-added"><img
                src="{{asset('img/icons/plus.svg')}}"
                alt="img"
                class="me-1" />Tạo sản phẩm</a>
        </div>
        {{-- <div class="page-btn ms-1">
            <a href="{{route('sanpham.trash')}}" class="btn btn-added"><img
                src="{{asset('img/icons/delete.svg')}}"
                alt="img"
                class="me-1" />Thùng Rác</a>
        </div> --}}
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
              <form id="filterForm" class="col-lg-12 col-sm-12" method="GET" action="{{ route('sanpham.index') }}">
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
                        <option value="">--Thương Hiệu--</option>
                        @foreach($thuonghieus as $th)
                        <option value="{{ $th->id }}" {{ request('cuahang') == $th->id ? 'selected' : '' }}>
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
                      <a class="btn btn-outline-danger col-lg-3" href="{{ route('sanpham.index') }}">X</a>
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
                <th class="text-center">Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Giá Gốc</th>
                <th>Loại</th>
                <th>Số lượng</th>
                <th>Lượt mua</th>
                <th>Lượt Tặng</th>
                <th>Giảm</th>
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
                        @if (!empty($sp->hinhanhsanpham) && !empty($sp->hinhanhsanpham->first()->hinhanh) )
                            <img
                                src="{{ $sp->hinhanhsanpham->first()->hinhanh }}"
                                alt="{{ $sp->ten ?? 'Sản phẩm' }}"
                            />
                        @else
                            <Sản phẩm không có hình ảnh>
                        @endif



                    </a>
                  <a href="{{url('/')}}" >{!! wordwrap($sp->ten, 25, '<br>') !!}</a>
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
                    $tenCuaHang = $sp->thuonghieu->ten ?? 'Không có';
                @endphp
                <td>{!! nl2br(e(wordwrap($tenCuaHang, 12, "\n"))) !!}</td>
                <td>
                  @if($sp->bienthe->count())
                    @php
                    $giaMin = $sp->bienthe->min('giagoc');
                    $giaMax = $sp->bienthe->max('giagoc');
                    @endphp
                    <pre class="text-center"><span class="text-success">{!! wordwrap((number_format($sp->bienthe->min('giagoc'), 0, ',', '.').'đ'), 15, "\n") !!}</span></pre>
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
                    $tongLuotBan = $sp->bienThe->sum('luotban')
                @endphp

                <td>{{ $tongLuotBan }}</td>
                @php
                    $tongLuotTang = $sp->bienThe->sum('luottang')
                @endphp

                <td>{{ $tongLuotTang }}</td>
                <td>
                    {{-- <pre>{!! wordwrap($sp->updated_at->format('d/m/Y H:j'), 10, "\n") !!}</pre> --}}
                    {{-- {{ $sp->updated_at->format('H:j - d/m/Y') }} --}}
                    {{ $sp->giamgia.'%' ?? '0%' }}
                </td>

                {{-- <td> --}}
                    {{-- <pre>{!! wordwrap($sp->updated_at->format('d/m/Y H:j'), 10, "\n") !!}</pre> --}}
                    {{-- {{ $sp->updated_at->format('H:j - d/m/Y') }} --}}

                {{--</td> --}}
                <td>
                  <div class="d-flex justify-content-center align-items-center">
                    <a class="me-3 d-flex justify-content-center align-items-center" href="{{ route('sanpham.show',$sp->id)}}" title="xem chi tiết">
                        <img src="{{asset('img/icons/eye.svg')}}" alt="img" />
                    </a>
                    <a class="me-3 d-flex justify-content-center align-items-center" href="{{ route('sanpham.edit',$sp->id) }}">
                        <img src="{{asset('img/icons/edit.svg')}}" alt="img" />
                    </a>
                  </div>
                    {{-- <a href="#"
                        class="me-3 d-flex justify-content-center align-items-center"
                        title="Xóa"
                        onclick="event.preventDefault();
                                    if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                                    document.getElementById('delete-form-{{ $sp->id }}').submit();
                                    }">
                        <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                    </a>
                    <form id="delete-form-{{ $sp->id }}" action="{{ route('sanpham.destroy', $sp->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                    </form> --}}


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

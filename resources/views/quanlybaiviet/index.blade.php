@extends('layouts.app')

@section('title', 'Danh sách bài viết | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH BÀI VIẾT</h4>
        <h6>
            {{-- Đếm bài viết đang hoạt động --}}
            {{ $baiviets->where('trangthai', 'Hiển thị')->count() }} bài viết đang hoạt động
            <br>

            {{-- Đếm bài viết bị ngưng hoạt động --}}
            {{ $baiviets->where('trangthai', 'Tạm ẩn')->count() }} bài viết bị ngưng hoạt động
        </h6>
      </div>
      <div class="page-btn">
        <a href="{{ route('baiviet.create') }}" class="btn btn-added">
          <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1" />Tạo bài viết
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
              <a class="btn btn-searchset"><img src="{{ asset('img/icons/search-white.svg') }}" alt="img" /></a>
            </div>
          </div>
        </div>

        <div class="card mb-0" id="filter_inputs">
          <div class="card-body pb-0">
            <label for="" class="mb-2"><strong>Lọc danh sách bài viết</strong></label>
            <div class="row">
              <form id="filterForm" class="col-lg-12 col-sm-12" method="GET" action="{{ route('baiviet.index') }}">
                <div class="row">
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
                      <a class="btn btn-outline-danger col-lg-3" href="{{ route('baiviet.index') }}">X</a>
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
                <th>Ảnh bài viết</th>
                <th>Tên bài viết</th>
                <th>Trạng thái</th>
                <th>Số lượng lượt xem</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              @foreach($baiviets as $bv)
              <tr>
                <td class="productimgname center-cell">
                    <a href="{{ url('/') }}" class="product-img">
                        <img src="{{ $bv->hinhanh ?? asset('img/icons/default-image.png') }}" />
                    </a>
                </td>
                <td>{{ $bv->tieude ?? 'Bài viết' }}</td>
                <td>
                    @if ($bv->trangthai == 'Hiển thị')
                        <span class="text-success">Hiển thị</span>
                    @else
                        <span class="text-danger">Tạm ẩn</span>
                    @endif
                </td>
                <td>{{ $bv->luotxem ?? 0 }}</td>
                <td>{{ \Carbon\Carbon::parse($bv->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($bv->updated_at)->format('d/m/Y H:i') }}</td>
                <td>
                  <a class="me-3" href="{{ route('baiviet.show', ['id' => $bv->id, 'slug' => Str::slug($bv->tieude)]) }}" title="xem chi tiết">
                    <img src="{{asset('img/icons/eye.svg')}}" alt="img" />
                  </a>
                  <a class="me-3" href="{{ route('baiviet.edit',$bv->id) }}">
                    <img src="{{asset('img/icons/edit.svg')}}" alt="img" />
                  </a>
                  <a class="me-3" href="{{route('baiviet.destroy', $bv->id)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                    <img src="{{asset('img/icons/delete.svg')}}" alt="img" />
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="pagination-wrap">
          {{ $baiviets->links() }}
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
        el.removeAttribute('name');
      }
    });
  });
</script>
@endsection

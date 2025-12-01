@extends('layouts.app')

@section('title', 'Danh sách Phương Thức Thanh Toán')
{{-- // controller truyền xuống $phuongthucs --}}
{{-- // các route sư dụng phuongthuc.create phuongthuc.show phuongthuc.edit phuongthuc.destroy --}}
@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH PHƯƠNG THỨC THANH TOÁN</h4>
        <h6>
          Tổng số: {{ $phuongthucs->count() }} phương thức
          <br>
          {{-- Bạn có thể thêm các thống kê khác nếu cần --}}
        </h6>
      </div>
      <div class="d-flex">
        <div class="page-btn">
          <a href="{{ route('phuongthuc.create') }}" class="btn btn-added">
            <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1" /> Thêm mới
          </a>
        </div>
        {{-- Nếu cần thêm nút khác thì thêm ở đây --}}
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

    {{-- Nếu bạn muốn thêm filter giống mẫu sản phẩm, có thể thêm ở đây --}}
    {{--
    <div class="card mb-0" id="filter_inputs">
      <div class="card-body pb-0">
        <label for="" class="mb-2"><strong>Lọc danh sách phương thức</strong></label>
        <form method="GET" action="{{ route('phuongthuc.index') }}">
          <div class="row">
            <div class="col-lg-4 col-sm-6 col-12">
              <input type="text" class="form-control" name="ten" placeholder="Tên phương thức" value="{{ request('ten') }}">
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <select class="form-select" name="trangthai">
                <option value="">-- Trạng thái --</option>
                <option value="Hoạt động" {{ request('trangthai') == 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                <option value="Tạm khóa" {{ request('trangthai') == 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
                <option value="Dừng hoạt động" {{ request('trangthai') == 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option>
              </select>
            </div>
            <div class="col-lg-4 col-sm-6 col-12 d-flex align-items-center">
              <button type="submit" class="btn btn-filters ms-2">Lọc</button>
              <a href="{{ route('phuongthuc.index') }}" class="btn btn-outline-danger ms-2">Xóa</a>
            </div>
          </div>
        </form>
      </div>
    </div>
    --}}

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table datanew">
            <thead>
              <tr>
                <th class="text-center">ID</th>
                <th>Tên phương thức</th>
                <th>Mã phương thức</th>
                <th>Trạng thái</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @foreach($phuongthucs as $pt)
              <tr>
                <td class="text-center">{{ $pt->id }}</td>
                <td>{{ $pt->ten }}</td>
                <td>{{ $pt->maphuongthuc ?? '-' }}</td>
                <td>
                  @if ($pt->trangthai == 'Hoạt động')
                    <span class="badge bg-success">{{ $pt->trangthai }}</span>
                  @elseif ($pt->trangthai == 'Tạm khóa')
                    <span class="badge bg-warning text-dark">{{ $pt->trangthai }}</span>
                  @else
                    <span class="badge bg-danger">{{ $pt->trangthai }}</span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="{{ route('phuongthuc.show', $pt->id) }}" class="me-3" title="Xem chi tiết">
                    <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                  </a>
                  <a href="{{ route('phuongthuc.edit', $pt->id) }}" class="me-3" title="Chỉnh sửa">
                    <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                  </a>
                  <a href="#"
                     onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa?')) { document.getElementById('delete-form-{{ $pt->id }}').submit(); }"
                     title="Xóa">
                    <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                  </a>
                  <form id="delete-form-{{ $pt->id }}" action="{{ route('phuongthuc.destroy', $pt->id) }}" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div> <!-- /.table-responsive -->
      </div> <!-- /.card-body -->
    </div> <!-- /.card -->

  </div> <!-- /.content -->
</div> <!-- /.page-wrapper -->
@endsection

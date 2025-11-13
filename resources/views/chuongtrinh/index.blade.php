@extends('layouts.app')

@section('title', 'Danh sách Chương Trình | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $chuongtrinhs $trangthais --}}
{{-- // các route sư dụng  chuongtrinh.create chuongtrinh.index?trangthai chuongtrinh.index?tieude  chuongtrinh.show chuongtrinh.edit chuongtrinh.destroy --}}
{{-- foreach($chuongtrinhs) $ct->hinhanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div class="page-title">
        <h4>DANH SÁCH CHƯƠNG TRÌNH</h4>
        <h6>
          {{-- Thống kê trạng thái --}}
          @php
            $countAll = $chuongtrinhs->total();
            $countTrangThai = [];
            foreach($trangthais as $tt) {
                $countTrangThai[$tt] = $chuongtrinhs->filter(fn($c) => $c->trangthai === $tt)->count();
            }
          @endphp
          Tổng: {{ $countAll }} chương trình <br>
          @foreach($trangthais as $tt)
            {{ $countTrangThai[$tt] ?? 0 }} {{ $tt }} <br>
          @endforeach
        </h6>
      </div>
      <div class="d-flex">
        <div class="page-btn">
          <a href="{{ route('chuongtrinh.create') }}" class="btn btn-added">
            <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1" /> Tạo chương trình
          </a>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        {{-- Filter --}}
        <form method="GET" action="{{ route('chuongtrinh.index') }}" class="row g-3 mb-3" id="filterForm">
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              name="tieude"
              value="{{ request('tieude') }}"
              placeholder="Tìm theo tiêu đề"
            />
          </div>
          <div class="col-md-3">
            <select class="form-select" name="trangthai">
              <option value="">-- Chọn trạng thái --</option>
              @foreach($trangthais as $tt)
                <option value="{{ $tt }}" {{ request('trangthai') == $tt ? 'selected' : '' }}>
                  {{ $tt }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
              <img src="{{ asset('img/icons/search-white.svg') }}" alt="Tìm" class="me-1" /> Tìm kiếm
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('chuongtrinh.index') }}" class="btn btn-outline-danger w-100">Xóa filter</a>
          </div>
        </form>

        {{-- Bảng danh sách --}}
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Quà Tặng Sự Kiện</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($chuongtrinhs as $ct)
                <tr>
                  <td>
                    <a href="{{ route('chuongtrinh.show', $ct->id) }}">
                      {!! wordwrap($ct->tieude, 30, '<br>') !!}
                    </a>
                  </td>
                  <td>{{ $ct->trangthai }}</td>
                  <td class="text-center">{{ $ct->quatangsukien_count }}</td>
                  <td class="text-center" style="width: 100px;">
                    @if($ct->hinhanh)
                      <img src="{{ $ct->hinhanh }}" alt="{{ $ct->tieude }}" style="max-width: 80px; max-height: 50px; object-fit: contain;" />
                    @else
                      <span class="text-muted">Không có</span>
                    @endif
                  </td>
                  <td class="text-center" style="width: 120px;">
                    <a href="{{ route('chuongtrinh.show', $ct->id) }}" title="Xem chi tiết" class="me-2">
                      <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                    </a>
                    <a href="{{ route('chuongtrinh.edit', $ct->id) }}" title="Chỉnh sửa" class="me-2">
                      <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                    </a>
                    <a href="#"
                      onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa chương trình này?')) { document.getElementById('delete-form-{{ $ct->id }}').submit(); }"
                      title="Xóa"
                    >
                      <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                    </a>
                    <form id="delete-form-{{ $ct->id }}" action="{{ route('chuongtrinh.destroy', $ct->id) }}" method="POST" style="display:none;">
                      @csrf
                      @method('DELETE')
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Chưa có chương trình nào.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Phân trang --}}
        <div class="d-flex justify-content-end mt-3">
          {{ $chuongtrinhs->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Nếu muốn loại bỏ input không có giá trị khi submit filter (tùy chọn)
  document.getElementById('filterForm').addEventListener('submit', function(e) {
    this.querySelectorAll('input, select').forEach(function(el) {
      if (!el.value) {
        el.removeAttribute('name');
      }
    });
  });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Danh sách Quà Tặng Sự Kiện | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div class="page-title">
        <h4>DANH SÁCH QUÀ TẶNG SỰ KIỆN</h4>
        <h6>
          @php
            $countAll = $quatangs->total();
            $countTrangThai = [];
            foreach($trangthais as $tt) {
                $countTrangThai[$tt] = $quatangs->filter(fn($q) => $q->trangthai === $tt)->count();
            }
          @endphp
          Tổng: {{ $countAll }} quà tặng <br>
          @foreach($trangthais as $tt)
            {{ $countTrangThai[$tt] ?? 0 }} {{ $tt }} <br>
          @endforeach
        </h6>
      </div>
      <div class="d-flex">
        <div class="page-btn">
          <a href="{{ route('quatangsukien.create') }}" class="btn btn-added">
            <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1" /> Tạo quà tặng
          </a>
        </div>
        <div class="page-btn ms-1">
          <a href="{{ route('quatangsukien.trash') }}" class="btn btn-added">
            <img src="{{ asset('img/icons/delete.svg') }}" alt="img" class="me-1" /> Thùng rác
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
        <form method="GET" action="{{ route('quatangsukien.index') }}" class="row g-3 mb-3" id="filterForm">
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
            <a href="{{ route('quatangsukien.index') }}" class="btn btn-outline-danger w-100">Xóa filter</a>
          </div>
        </form>

        {{-- Bảng danh sách --}}
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Thông tin biến thể</th>
                <th>Chương trình</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($quatangs as $qt)
                <tr>
                  <td>
                    <a href="{{ route('quatangsukien.show', $qt->id) }}">
                      {!! wordwrap($qt->tieude, 30, '<br>') !!}
                    </a>
                  </td>
                  <td>{{ $qt->trangthai }}</td>
                  <td>
                    {!! wordwrap($qt->bienthe ? $qt->bienthe->loaibienthe->ten . ' - ' . ($qt->bienthe->sanpham->ten ?? 'N/A') : 'N/A',30,'<br>') !!}

                    {{-- {{ $qt->id_bienthe }} --}}
                  </td>
                  <td>
                    {{ $qt->chuongtrinh ? $qt->chuongtrinh->tieude : 'N/A' }}
                  </td>
                  <td class="text-center" style="width: 100px;">
                    @if($qt->hinhanh)
                      <img src="{{ $qt->hinhanh }}" alt="{{ $qt->tieude }}" style="max-width: 80px; max-height: 50px; object-fit: contain;" />
                    @else
                      <span class="text-muted">Không có</span>
                    @endif
                  </td>
                  <td class="text-center" style="width: 120px;">
                    <a href="{{ route('quatangsukien.show', $qt->id) }}" title="Xem chi tiết" class="me-2">
                      <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                    </a>
                    <a href="{{ route('quatangsukien.edit', $qt->id) }}" title="Chỉnh sửa" class="me-2">
                      <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                    </a>
                    <a href="#"
                      onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa quà tặng này?')) { document.getElementById('delete-form-{{ $qt->id }}').submit(); }"
                      title="Xóa"
                    >
                      <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                    </a>
                    <form id="delete-form-{{ $qt->id }}" action="{{ route('quatangsukien.destroy', $qt->id) }}" method="POST" style="display:none;">
                      @csrf
                      @method('DELETE')
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">Chưa có quà tặng nào.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Phân trang --}}
        <div class="d-flex justify-content-end mt-3">
          {{ $quatangs->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Loại bỏ input không có giá trị khi submit filter (tùy chọn)
  document.getElementById('filterForm').addEventListener('submit', function(e) {
    this.querySelectorAll('input, select').forEach(function(el) {
      if (!el.value) {
        el.removeAttribute('name');
      }
    });
  });
</script>
@endsection

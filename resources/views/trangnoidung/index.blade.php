@extends('layouts.app')

@section('title', 'Danh sách trang nội dung | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">

    <!-- HEADER -->
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH TRANG NỘI DUNG</h4>
        <h6>
            Tổng: {{ $trangnoidungs->count() }} trang nội dung <br>
            Hoạt động: {{ $trangnoidungs->where('trangthai', 'Hoạt động')->count() }} <br>
            Tạm khóa: {{ $trangnoidungs->where('trangthai', 'Tạm khóa')->count() }}
        </h6>
      </div>
      <div class="page-btn">
        <a href="{{ route('trangnoidung.create') }}" class="btn btn-added">
          <img src="{{ asset('img/icons/plus.svg') }}" class="me-1" />
          Tạo trang
        </a>
      </div>
    </div>

    <!-- ALERT -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- TABLE -->
    <div class="card">
      <div class="card-body">

        <div class="table-responsive">
          <table class="table datanew">
            <thead>
              <tr>
                <th class="text-center">Hình ảnh</th>
                <th>Tiêu đề</th>
                <th>Slug</th>
                <th>Mô tả ngắn</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
              </tr>
            </thead>

            <tbody>
              @foreach($trangnoidungs as $tn)
              <tr>
                <!-- Hình ảnh -->
                <td class="text-center">
                  <img src="{{ $tn->hinhanh }}" width="60" height="60" style="object-fit:cover;border-radius:6px;">
                </td>

                <!-- Tiêu đề -->
                <td>
                  {!! wordwrap($tn->tieude, 30, '<br>') !!}
                </td>

                <!-- Slug -->
                <td>
                  <span class="text-primary">
                    {!! wordwrap($tn->slug, 30, '<br>') !!}
                  </span>
                </td>

                <!-- Mô tả -->
                <td>
                  {!! wordwrap(Str::limit(strip_tags($tn->mota), 80), 40, '<br>') !!}
                </td>

                <!-- Trạng thái -->
                <td>
                  @if($tn->trangthai == 'Hiển thị')
                      <span class="badge bg-success">Hiển thị</span>
                  @elseif($tn->trangthai == 'Tạm ẩn')
                      <span class="badge bg-warning text-dark">Tạm ẩn</span>
                  @else
                      <span class="badge bg-danger">Dừng hoạt động</span>
                  @endif
                </td>

                <!-- Hành động -->
                <td>
                  <div class="d-flex justify-content-center align-items-center">
                      <a class="me-3" href="{{ route('trangnoidung.show', $tn->id) }}" title="Xem chi tiết">
                          <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem">
                      </a>

                      <a class="me-3" href="{{ route('trangnoidung.edit', $tn->id) }}" title="Chỉnh sửa">
                          <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa">
                      </a>

                      <!-- Xóa -->
                      <a href="#" class="me-3" title="Xóa"
                        onclick="event.preventDefault();
                          if(confirm('Bạn có chắc chắn muốn xóa trang này?')) {
                              document.getElementById('delete-form-{{ $tn->id }}').submit();
                          }">
                          <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa">
                      </a>

                      <form id="delete-form-{{ $tn->id }}" action="{{ route('trangnoidung.destroy', $tn->id) }}" method="POST" style="display: none;">
                          @csrf
                          @method('DELETE')
                      </form>
                  </div>
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
<style> .dt-buttons { display: none !important; } </style>
@endsection

@extends('layouts.app')

@section('title', 'Danh sách Loại Biến Thể | Quản trị hệ thống Siêu Thị Vina')

{{-- // controller truyền xuống $loaibienthes $keyword --}}
{{-- // các route sư dụng loaibienthe.create loaibienthe.index loaibienthe.index(?keyword) loaibienthe.show loaibienthe.edit loaibienthe.destroy   --}}
{{--  $sanphams->hinhanhsanpham->first()->hihanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH LOẠI BIẾN THỂ</h4>
        <h6>
          Tổng cộng: {{ $loaibienthes->total() }} loại biến thể <br>
          Hiển thị: {{ $loaibienthes->filter(fn($lbt) => $lbt->trangthai === 'Hiển thị')->count() }} <br>
          Tạm ẩn: {{ $loaibienthes->filter(fn($lbt) => $lbt->trangthai === 'Tạm ẩn')->count() }}
        </h6>
      </div>
      <div class="page-btn">
        <a href="{{ route('loaibienthe.create') }}" class="btn btn-added">
          <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1" />
          Thêm loại biến thể
        </a>
      </div>
    </div>

    {{-- Thông báo --}}
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

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            <div class="search-input">
              <form method="GET" action="{{ route('loaibienthe.index') }}" class="d-flex">
                <input type="text" name="keyword" class="form-control"
                       placeholder="Tìm theo tên..." value="{{ request('keyword') }}">
                <button class="btn btn-filters ms-2" type="submit">
                  <img src="{{ asset('img/icons/search-whites.svg') }}" alt="Tìm kiếm" />
                </button>
                <a href="{{ route('loaibienthe.index') }}" class="btn btn-outline-danger ms-2">X</a>
              </form>
            </div>
          </div>
        </div>

        <div class="table-responsive mt-3">
          <table class="table datanew">
            <thead>
              <tr>
                <th class="text-center">ID</th>
                <th>Tên loại biến thể</th>
                <th class="text-center">Số lượng sản phẩm</th>
                <th class="text-center">Số lượng biến thể</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($loaibienthes as $lbt)
                <tr>
                  <td class="text-center">{{ $lbt->id }}</td>
                  <td><strong>{{ $lbt->ten }}</strong></td>
                  <td class="text-center">{{ $lbt->sanpham()->count() }}</td>
                  <td class="text-center">{{ $lbt->bienthe()->count() }}</td>
                  <td class="text-center">
                    @if($lbt->trangthai === 'Hiển thị')
                      <span class="badge bg-success">Hiển thị</span>
                    @else
                      <span class="badge bg-secondary">Tạm ẩn</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center">
                      <a class="me-3" href="{{ route('loaibienthe.show', $lbt->id) }}" title="Xem chi tiết">
                        <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                      </a>
                      <a class="me-3" href="{{ route('loaibienthe.edit', $lbt->id) }}" title="Chỉnh sửa">
                        <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                      </a>
                      <a href="#" onclick="event.preventDefault();
                        if(confirm('Bạn có chắc chắn muốn xóa loại biến thể này?'))
                        document.getElementById('delete-form-{{ $lbt->id }}').submit();" title="Xóa">
                        <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                      </a>
                      <form id="delete-form-{{ $lbt->id }}"
                            action="{{ route('loaibienthe.destroy', $lbt->id) }}"
                            method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">Không có loại biến thể nào.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Phân trang --}}
        <div class="mt-3 d-flex justify-content-center">
          {{ $loaibienthes->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<style>
  .dt-buttons { display: none !important; }
</style>
@endsection

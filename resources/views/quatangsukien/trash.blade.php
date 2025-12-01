@extends('layouts.app')

@section('title', 'Thùng rác Quà Tặng Sự Kiện | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">
    <div class="page-header">
        <x-header.breadcrumb
            title="THÙNG RÁC QUÀ TẶNG SỰ KIỆN"
            :links="[
                ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                ['label' => 'Danh sách Quà Tặng Sự Kiện', 'route' => 'quatangsukien.index']
            ]"
            active="Thùng rác Quà Tặng Sự Kiện"
        />
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        @if($quatangs->count() > 0)
          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead>
                <tr>
                  <th>Tiêu đề</th>
                  <th>Trạng thái</th>
                  <th>Ngày xóa</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                @foreach($quatangs as $qt)
                  <tr>
                    <td>{{ $qt->tieude }}</td>
                    <td>{{ $qt->trangthai }}</td>
                    <td>{{ $qt->deleted_at ? $qt->deleted_at->format('d/m/Y H:i') : '' }}</td>
                    <td class="text-center" style="width: 160px;">
                      <form action="{{ route('quatangsukien.restore', $qt->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm me-1" title="Khôi phục" onclick="return confirm('Bạn có chắc chắn muốn khôi phục quà tặng này?')">
                          <img src="{{ asset('img/icons/return1.svg') }}" alt="Khôi phục" style="width:16px; height:16px;">
                        </button>
                      </form>

                      <form action="{{ route('quatangsukien.forceDelete', $qt->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm" title="Xóa vĩnh viễn" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn quà tặng này?')">
                          <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" style="width:16px; height:16px;">
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- Phân trang --}}
          <div class="d-flex justify-content-end mt-3">
            {{ $quatangs->links() }}
          </div>
        @else
          <p class="text-center">Thùng rác hiện đang trống.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

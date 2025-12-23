@extends('layouts.app')

@section('title', 'Danh sách khách hàng | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $nguoidungs  --}}
{{-- // các route sư dụng  nguoidung.index, nguoidung.create, nguoidung.show, nguoidung.edit, nguoidung.destroy --}}
{{-- $nguoidungs->avatar: Link http://148.230.100.215/storage/assets/client/images/profiles/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div class="page-title">
        <h4>Danh sách khách hàng</h4>
        <h6>Quản lý thông tin khách hàng của bạn.</h6>
      </div>
      <div class="d-flex">
        <div class="page-btn">
            <a href="{{route('nguoidung.create')}}" class="btn btn-added"><img
                src="{{asset('img/icons/plus.svg')}}"
                alt="img"
                class="me-1" />Tạo mới người dùng</a>
        </div>
        {{-- <div class="page-btn ms-1">
            <a href="{{route('nguoidung.trash')}}" class="btn btn-added"><img
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
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table datanew">
            <thead>
              <tr>
                <th>Tên khách hàng</th>
                <th>Username</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ (tỉnh/thành phố)</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($nguoidungs as $nguoidung)
              <tr>
                <td class="productimgname">
                  <a href="javascript:void(0);" class="product-img">
                    <img src="{{ $nguoidung->avatar ?? asset('img/default-avatar.png') }}" alt="{{ $nguoidung->hoten }}" style="width:50px; height:50px; object-fit:cover; border-radius:50%;">
                  </a>
                  <a href="javascript:void(0);" style="white-space: normal; display: block;">
                    {!! wordwrap(($nguoidung->hoten), 10, '<br>') !!}
                  </a>
                </td>
                <td>
                  {!! wordwrap(($nguoidung->username), 10, '<br>') !!}
                </td>
                <td>
                  {!! wordwrap(($nguoidung->email), 10, '<br>') !!}
                </td>
                <td>{{ $nguoidung->sodienthoai ?? '-' }}</td>
                <td>
                  @php $diachi = $nguoidung->diachi->first(); @endphp
                  @if($diachi)
                    {!! wordwrap(($diachi->diachi . ' (' . $diachi->tinhthanh . ')'), 30, '<br>') !!}
                  @else
                    <i>Chưa có địa chỉ</i>
                  @endif
                </td>
                <td>
                  @php
                    $statusClass = match($nguoidung->trangthai) {
                      'Hoạt động' => 'bg-lightgreen',
                      'Tạm khóa' => 'bg-lightyellow',
                      'Dừng hoạt động' => 'bg-lightred',
                      default => 'bg-lightgray',
                    };
                  @endphp
                  <span class="badges {{ $statusClass }}">{{ $nguoidung->trangthai }}</span>
                </td>
                <td>
                  <a href="{{ route('nguoidung.show', $nguoidung->id) }}" title="Xem chi tiết" class="me-2">
                    <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                  </a>
                  <a href="{{ route('nguoidung.edit', $nguoidung->id) }}" title="Chỉnh sửa" class="me-2">
                    <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                  </a>
                  {{-- <form action="{{ route('nguoidung.destroy', $nguoidung->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này không?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                      <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                    </button>
                  </form> --}}
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center">Không có khách hàng nào.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Pagination nếu có --}}
        {{-- @if ($nguoidungs->hasPages())
          <div class="mt-3">
            {{ $nguoidungs->links() }}
          </div>
        @endif --}}
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
<style>
  .bg-lightgreen {
    background-color: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
  }
  .bg-lightyellow {
    background-color: #fff3cd;
    color: #856404;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
  }
  .bg-lightred {
    background-color: #f8d7da;
    color: #721c24;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
  }
  .bg-lightgray {
    background-color: #e2e3e5;
    color: #6c757d;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
  }
</style>
@endsection

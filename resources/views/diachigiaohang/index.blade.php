@extends('layouts.app')

@section('title', 'Danh sách địa chỉ giao hàng | Quản trị hệ thống Siêu Thị Vina')
{{-- // các route sư dụng  diachigiaohang.index, diachigiaohang.create, diachigiaohang.show, diachigiaohang.edit, diachigiaohang.trash  diachigiaohang.destroy --}}

{{-- // controller truyền xuống $diachis $search --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Danh sách địa chỉ giao hàng</h4>
                <h6>Quản lý các địa chỉ giao hàng của khách hàng.</h6>
            </div>
            <div class="page-btn">
                <div class="mb-3 d-flex justify-content-between">
                    <a href="{{ route('diachigiaohang.create') }}" class="btn btn-added">
                        ➕ Thêm địa chỉ giao hàng
                    </a>
                    <a href="{{ route('diachigiaohang.trash') }}" class="btn btn-danger ms-2">
                        🗑️ Thùng rác
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('diachigiaohang.index') }}" class="mb-3">
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Tìm kiếm theo họ tên, số điện thoại, địa chỉ, tỉnh/thành..."
                        />
                        <button type="submit" class="btn btn-primary">
                            <img src="{{ asset('img/icons/search-white.svg') }}" alt="Tìm kiếm" />
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Tỉnh/Thành phố</th>
                                <th>Trạng thái</th>
                                <th>Tài Khoản</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($diachis as $diachi)
                                <tr>
                                    <td>{{ $diachi->hoten }}</td>
                                    <td>{{ $diachi->sodienthoai ?? '-' }}</td>
                                    <td style="max-width: 200px; white-space: normal;">{{ $diachi->diachi }}</td>
                                    <td>{{ $diachi->tinhthanh }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($diachi->trangthai) {
                                                'Mặc định' => 'bg-lightgreen',
                                                'Khác' => 'bg-secondary',
                                                'Tạm ẩn' => 'bg-lightred',
                                                default => 'bg-lightgray',
                                            };
                                        @endphp
                                        <span class="badges {{ $statusClass }}">{{ $diachi->trangthai }}</span>
                                    </td>
                                    <td>
                                        {{-- Hiển thị tên người dùng liên kết nếu có --}}
                                        {{ $diachi->nguoidung->username ?? '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('diachigiaohang.show', $diachi->id) }}" title="Xem chi tiết" class="me-2">
                                            <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                                        </a>
                                        <a href="{{ route('diachigiaohang.edit', $diachi->id) }}" title="Chỉnh sửa" class="me-2">
                                            <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                                        </a>
                                        <form action="{{ route('diachigiaohang.destroy', $diachi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                                                <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có địa chỉ giao hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($diachis->hasPages())
                    <div class="mt-3">
                        {{ $diachis->links() }}
                    </div>
                @endif
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
    .badges {
        display: inline-block;
        min-width: 75px;
        text-align: center;
    }
</style>
@endsection

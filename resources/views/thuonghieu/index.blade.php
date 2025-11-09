@extends('layouts.app')

@section('title', 'Danh sách thương hiệu')
{{-- $thuonghieus->logo: Link http://148.230.100.215/assets/client/images/brands/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div class="page-title">
                <h4>Danh sách thương hiệu</h4>
                <h6>Quản lý và cập nhật thương hiệu sản phẩm</h6>
            </div>
            <a href="{{ route('thuonghieu.create') }}" class="btn btn-primary">
                ➕ Thêm thương hiệu
            </a>
        </div>

        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- Form tìm kiếm --}}
        <form action="{{ route('thuonghieu.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Tìm kiếm theo tên, slug hoặc trạng thái..."
                    value="{{ old('search', $search ?? '') }}"
                >
                <button class="btn btn-outline-secondary" type="submit">🔍 Tìm kiếm</button>
            </div>
        </form>

        {{-- Bảng danh sách thương hiệu --}}
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Tên thương hiệu</th>
                            <th>Logo</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th class="text-center" width="200px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($thuonghieus as $thuonghieu)
                            <tr>
                                <td>{{ $thuonghieu->id }}</td>
                                <td>{{ $thuonghieu->ten }}</td>
                                <td>
                                    @if($thuonghieu->logo)
                                        <img src="{{ $thuonghieu->logo }}" alt="{{ $thuonghieu->ten }}" width="80px">
                                    @else
                                        <span class="text-muted">Chưa có logo</span>
                                    @endif
                                </td>
                                <td style="max-width: 300px; white-space: normal; word-wrap: break-word;">
                                    {!! nl2br(e($thuonghieu->mota)) !!}
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($thuonghieu->trangthai) {
                                            'Hoạt động' => 'success',
                                            'Tạm khóa' => 'warning',
                                            'Dừng hoạt động' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">
                                        {{ $thuonghieu->trangthai }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('thuonghieu.show', $thuonghieu->id) }}" class="btn btn-sm btn-primary" title="Xem chi tiết">👁️</a>
                                    <a href="{{ route('thuonghieu.edit', $thuonghieu->id) }}" class="btn btn-sm btn-info" title="Sửa">✏️</a>
                                    <form action="{{ route('thuonghieu.destroy', $thuonghieu->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có thương hiệu nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $thuonghieus->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Danh sách hình ảnh sản phẩm')

{{-- $hinhanhs->hinhanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Danh sách hình ảnh sản phẩm</h4>
                <h6>Quản lý và cập nhật hình ảnh sản phẩm</h6>
            </div>
        </div>

        {{-- Hiển thị thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form tìm kiếm --}}
        <form action="{{ route('hinhanhsanpham.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên sản phẩm..." value="{{ $search ?? '' }}">
                <button class="btn btn-outline-secondary" type="submit">🔍 Tìm kiếm</button>
            </div>
        </form>

        {{-- Nút chức năng --}}
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('hinhanhsanpham.create') }}" class="btn btn-primary">
                ➕ Thêm hình ảnh
            </a>
            <a href="{{ route('hinhanhsanpham.trash') }}" class="btn btn-secondary">
                🗑️ Thùng rác
            </a>
        </div>

        <div class="card">
            <div class="card-body p-0">
                {{-- Bảng dữ liệu --}}
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>Hình ảnh</th>
                            <th>Trạng thái</th>
                            <th class="text-center" width="220px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hinhanhs as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td style="max-width: 250px; word-wrap: break-word; white-space: normal;">
                                    {{ $item->sanpham ? $item->sanpham->ten : 'Không xác định' }}
                                </td>
                                <td>
                                    @php
                                        $imagePath = $item->hinhanh;
                                    @endphp

                                    @if ($imagePath)
                                        <img src="{{ $imagePath }}" width="80px" alt="Hình ảnh">
                                    @else
                                        <span class="text-muted">Không có hình</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->trangthai == 'Hiển thị' ? 'success' : 'warning' }}">
                                        {{ $item->trangthai }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('hinhanhsanpham.show', $item->id) }}" class="btn btn-sm btn-primary" title="Xem chi tiết">
                                        👁️
                                    </a>
                                    <a href="{{ route('hinhanhsanpham.edit', $item->id) }}" class="btn btn-sm btn-info" title="Sửa">
                                        ✏️
                                    </a>
                                    <form action="{{ route('hinhanhsanpham.destroy', $item->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa hình này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Không có hình ảnh nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $hinhanhs->links() }}
        </div>
    </div>
</div>
@endsection

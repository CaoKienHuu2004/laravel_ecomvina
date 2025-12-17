@extends('layouts.app')

@section('title', 'Danh sách hình ảnh sản phẩm')

{{-- $hinhanhs->hinhanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4>Danh sách hình ảnh sản phẩm</h4>
                <h6>Quản lý và cập nhật hình ảnh sản phẩm</h6>
            </div>
            <div class="d-flex">
                <div class="page-btn">
                    <a href="{{route('hinhanhsanpham.create')}}" class="btn btn-added"><img
                        src="{{asset('img/icons/plus.svg')}}"
                        alt="img"
                        class="me-1" />Tạo mới hình ảnh sản phẩm</a>
                </div>
                <div class="page-btn ms-1">
                    <a href="{{route('hinhanhsanpham.trash')}}" class="btn btn-added"><img
                        src="{{asset('img/icons/delete.svg')}}"
                        alt="img"
                        class="me-1" />Thùng Rác</a>
                </div>
            </div>
        </div>

        {{-- Hiển thị thông báo thành công --}}
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



        {{-- Nút chức năng
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('hinhanhsanpham.create') }}" class="btn btn-primary">
                ➕ Thêm hình ảnh
            </a>
            <a href="{{ route('hinhanhsanpham.trash') }}" class="btn btn-secondary">
                🗑️ Thùng rác
            </a>
        </div> --}}

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
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
                                    {{-- id --}}
                                    <td>{{ $item->id }}</td>
                                    {{-- thông tin sản phẩm --}}
                                    <td style="max-width: 250px; word-wrap: break-word; white-space: normal;">
                                        @php
                                            $bientheList = [];
                                            if ($item->sanpham && $item->sanpham->bienthe) {
                                                foreach ($item->sanpham->bienthe as $bienthe) {
                                                    if ($bienthe->loaibienthe) {
                                                        $bientheList[] = $bienthe->loaibienthe->ten;
                                                    }
                                                }
                                            }
                                            $bientheString = implode(', ', $bientheList); // nối các tên loại biến thể bằng dấu phẩy
                                        @endphp

                                        {!! wordwrap(
                                            ($item->sanpham ? $item->sanpham->ten : 'Không xác định') .
                                            ($bientheString ? ' - ' . $bientheString : ''),
                                            36,
                                            "<br>",
                                            true
                                        ) !!}
                                    </td>
                                    {{-- hình ảnh sản phẩm --}}
                                    <td>
                                        {{-- @php
                                            $imagePath = $item->hinhanh;
                                        @endphp

                                        @if ($imagePath)
                                            <img src="{{ $imagePath }}" width="80px" alt="Hình ảnh">
                                        @else
                                            <span class="text-muted">Không có hình</span>
                                        @endif --}}
                                        @php
                                            $meta = $item->image_meta;
                                        @endphp

                                        @if ($item->hinhanh && $meta)
                                            <img
                                                src="{{ $item->hinhanh }}"
                                                width="80"
                                                height="{{ intval(80 * $meta['height'] / $meta['width']) }}"
                                                alt="Hình ảnh"
                                                loading="lazy"
                                            >

                                            <div class="text-muted" style="font-size:12px">
                                                {{ $meta['width'] }}x{{ $meta['height'] }} • {{ strtoupper($meta['type']) }}
                                            </div>
                                        @elseif($item->hinhanh)
                                            <img src="{{ $item->hinhanh }}" width="80" alt="Hình ảnh">
                                        @else
                                            <span class="text-muted">Không có hình</span>
                                        @endif
                                    </td>
                                    {{-- trạng thái hình ảnh sản phẩm --}}
                                    <td>
                                        <span class="badge bg-{{ $item->trangthai == 'Hiển thị' ? 'success' : 'warning' }}">
                                            {{ $item->trangthai }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="{{ route('hinhanhsanpham.show', $item->id) }}" title="Xem chi tiết" class="me-2">
                                                <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                                            </a>
                                            <a href="{{ route('hinhanhsanpham.edit', $item->id) }}" title="Chỉnh sửa" class="me-2">
                                                <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                                            </a>
                                            <form action="{{ route('hinhanhsanpham.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa hình ảnh này không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                                                <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    {{-- <td class="text-center">
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
                                    </td> --}}
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
        </div>

        {{-- Phân trang --}}
        {{-- <div class="d-flex justify-content-center mt-3">
            {{ $hinhanhs->links() }}
        </div> --}}
    </div>
</div>
@endsection

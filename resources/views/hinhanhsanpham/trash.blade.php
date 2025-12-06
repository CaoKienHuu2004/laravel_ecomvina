@extends('layouts.app')

@section('title', 'Thùng rác hình ảnh sản phẩm')
{{--
    // Controller truyền xuống $hinhanhs
    $hinhanh->hinhanh chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg
--}}
{{--
    // Controller truyền xuống không
    // các route sư dụng hinhanhsanpham.restore hinhanhsanpham.forceDelete --- của breadcrumb hinhanhsanpham.index trang-chu
--}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Breadcrumb --}}
        <div class="page-header">
            <x-header.breadcrumb
                title="Thùng rác hình ảnh sản phẩm"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách hình ảnh sản phẩm', 'route' => 'hinhanhsanpham.index']
                ]"
                active="Thùng rác"
            />
        </div>

        {{-- Thông báo success, error --}}
        <div class="error-log">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif
        </div>

        <div class="card">


            {{-- Bảng dữ liệu --}}
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>Hình ảnh</th>
                            <th>Ngày xóa</th>
                            <th class="text-center" style="width: 220px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hinhanhs as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->sanpham ? $item->sanpham->ten : 'Không xác định' }}</td>
                                <td>
                                    @if ($item->hinhanh)
                                        <img src="{{ $item->hinhanh }}" width="80" alt="Hình ảnh">
                                    @else
                                        <span class="text-muted fst-italic">Không có hình</span>
                                    @endif
                                </td>
                                <td>{{ $item->deleted_at ? $item->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="text-center">
                                    {{-- Khôi phục --}}
                                    <form action="{{ route('hinhanhsanpham.restore', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                    @method('POST')
                                        <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                            Khôi phục
                                        </button>
                                    </form>

                                    {{-- Xóa vĩnh viễn --}}
                                    <form action="{{ route('hinhanhsanpham.forceDelete', $item->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn hình ảnh này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa vĩnh viễn">
                                            Xóa vĩnh viễn
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted fst-italic">Thùng rác trống.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

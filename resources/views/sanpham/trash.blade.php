@extends('layouts.app')

@section('title', 'Thùng rác sản phẩm')
{{--$hinhanhs->hinhanh: Link  http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}

{{-- // controller truyền xuống $sanphams  --}}
{{-- // các route sư dụng sanpham.restore sanpham.forceDelete --- của breadcrumb sanpham.index trang-chu      --}}
{{--  $sanphams->hinhanhsanpham->first()->hihanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title="🗑️ Thùng rác sản phẩm đã bị xóa tạm thời"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách sản phẩm', 'route' => 'sanpham.index']
                ]"
                active="Thùng rác"
            />
        </div>

        <div class="card shadow-sm p-4">
            {{-- Hiển thị thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            {{-- Bảng dữ liệu --}}
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>Biến thể</th>
                            <th>Hình ảnh</th>
                            <th>Ngày xóa</th>
                            <th class="text-center" style="width: 220px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sanphams as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item ? $item->ten : 'Không xác định' }}</td>
                                <td>
                                    @foreach ($item->bienthe as $bt)
                                        {{ $bt->id }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @if ( !empty($sp->hinhanhsanpham) && !empty($sp->hinhanhsanpham->first()->hinhanh))
                                        <img src="{{ $item->hinhanhsanpham->first()->hinhanh }}" width="80" alt="Hình ảnh">
                                    @else
                                        <span class="text-muted fst-italic">Không có hình</span>
                                    @endif
                                </td>
                                <td>{{ $item->deleted_at ? $item->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="text-center">
                                    {{-- Khôi phục --}}
                                    <form action="{{ route('sanpham.restore', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                            🔄 Khôi phục
                                        </button>
                                    </form>

                                    {{-- Xóa vĩnh viễn --}}
                                    <form action="{{ route('sanpham.forceDelete', $item->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn hình ảnh này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa vĩnh viễn">
                                            ❌ Xóa vĩnh viễn
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted fst-italic">Thùng rác trống.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

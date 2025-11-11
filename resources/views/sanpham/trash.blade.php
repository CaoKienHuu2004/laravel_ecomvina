@extends('layouts.app')

@section('title', 'Thùng rác sản phẩm')
{{--$hinhanhs->hinhanh: Link  http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}

{{-- // controller truyền xuống $sanphams  --}}
{{-- // các route sư dụng sanpham.index sanpham.restore sanpham.forceDelete     --}}
{{--  $sanphams->hinhanhsanpham->first()->hihanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h2 class="text-center">🗑️ Thùng rác sản phẩm</h2>
                <h6 class="text-center text-muted">Quản lý các sản phẩm đã bị xóa tạm thời</h6>
            </div>
        </div>

        <div class="card shadow-sm p-4">
            {{-- Hiển thị thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Nút quay lại --}}
            <div class="mb-3 d-flex justify-content-start">
                <a href="{{ route('sanpham.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>

            {{-- Bảng dữ liệu --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-primary">
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
                                    @if ($item->hinhanhsanpham)
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

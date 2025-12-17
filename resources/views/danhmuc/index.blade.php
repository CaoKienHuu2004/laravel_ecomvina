@extends('layouts.app')

@section('title', 'Danh sách danh mục | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $danhmucs --}}
{{-- // các route sư dụng danhmuc.create  danhmuc.show danhmuc.edit danhmuc.destroy   --}}
{{--
    $danhmucs->logo chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/categories/tenfilehinhanh.web
--}}
{{-- bỏ id, show --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>DANH SÁCH DANNH MỤC</h4>
                <div class="d-flex align-items-center" style="gap: 2rem;">
                    {{-- 'Hiển thị','Tạm ẩn' --}}
                    <h6>
                    Tổng danh mục: {{ $danhmucs->count() }} <br>
                    Hiển thị: {{ $danhmucs->where('trangthai', 'Hiển thị')->count() }} <br>
                    Tạm ẩn: {{ $danhmucs->where('trangthai', 'Tạm ẩn')->count() }} <br>
                    </h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="{{ route('danhmuc.create') }}" class="btn btn-added">
                    <img src="{{ asset('img/icons/plus.svg') }}" class="me-1" alt="img">
                    Tạo Thông Báo
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Tên danh mục</th>
                                <th>Số sản phẩm</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($danhmucs as $dm)
                            <tr>
                                <td>{{ $dm->id }}</td>
                                <td>
                                    @if($dm->logo && file_exists(public_path(parse_url($dm->logo, PHP_URL_PATH))))
                                        <img src="{{ $dm->logo }}" alt="Logo {{ $dm->ten }}" width="50" height="50" class="rounded">
                                    @else
                                        <img src="{{ asset('assets/client/images/categories/danhmuc.jpg') }}" alt="Default" width="50" height="50" class="rounded">
                                    @endif
                                </td>
                                <td>{{ $dm->ten }}</td>
                                <td>{{ $dm->sanpham_count }}</td>
                                <td>
                                    <span class="badge {{ $dm->trangthai == 'Hiển thị' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $dm->trangthai }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{ route('danhmuc.show', $dm->id) }}" title="Xem chi tiết" class="me-2">
                                            <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                                        </a>
                                        <a href="{{ route('danhmuc.edit', $dm->id) }}" title="Chỉnh sửa" class="me-2">
                                            <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                                        </a>
                                        <form action="{{ route('danhmuc.destroy', $dm->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                                            <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="mt-3 d-flex justify-content-center">
                    {{ $danhmucs->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Nếu có class từ DataTables bạn muốn ẩn, có thể giữ đoạn này */
    .dt-buttons { display: none !important; }
</style>
@endsection

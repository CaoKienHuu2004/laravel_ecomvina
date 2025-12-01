@extends('layouts.app')

@section('title', 'Danh sách danh mục | Quản trị hệ thống Siêu Thị Vina')
{{--
    $danhmucs->logo chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/categories/tenfilehinhanh.web
--}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center mb-3">
            <div class="page-title">
                <h4>Danh mục sản phẩm</h4>
                <h6>Theo dõi {{ $danhmucs->total() }} danh mục sản phẩm</h6>
            </div>

            <div class="page-btn d-flex align-items-center">
                <form action="{{ route('danhmuc.index') }}" method="GET" class="me-2">
                    <div class="input-group">
                        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm kiếm danh mục...">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('danhmuc.create') }}" class="btn btn-primary d-flex align-items-center ms-2">
                    <img src="{{ asset('img/icons/plus.svg') }}" class="me-1" alt="img" />
                    Thêm danh mục
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-primary">
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
                                <td>
                                    <a href="{{ route('danhmuc.show', $dm->id) }}" class="btn btn-sm btn-outline-info me-2" title="Xem chi tiết">👁️</a>
                                    <a href="{{ route('danhmuc.edit', $dm->id) }}" class="btn btn-sm btn-outline-primary me-2" title="Chỉnh sửa">
                                        <img src="{{ asset('img/icons/edit.svg') }}" alt="Edit" />
                                    </a>
                                    <form action="{{ route('danhmuc.destroy', $dm->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <img src="{{ asset('img/icons/delete.svg') }}" alt="Delete" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $danhmucs->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
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

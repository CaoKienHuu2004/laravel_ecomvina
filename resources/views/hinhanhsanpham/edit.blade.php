@extends('layouts.app')

@section('title', 'Chỉnh sửa hình ảnh sản phẩm')

{{--
    // Controller truyền xuống $hinhanh $sanphams
    // các route sư dụng hinhanhsanpham.update --- của breadcrumb hinhanhsanpham.index trang-chu
--}}
{{--
    $hinhanh->hinhanh chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg
--}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        {{-- Breadcrumb --}}
        <div class="page-header">
            <x-header.breadcrumb
                title="Chỉnh sửa hình ảnh sản phẩm"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách hình ảnh sản phẩm', 'route' => 'hinhanhsanpham.index']
                ]"
                active="Chỉnh sửa"
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
            <div class="card-body">

                {{-- Thông báo lỗi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Lỗi!</strong> Vui lòng kiểm tra lại các trường nhập.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form chỉnh sửa hình ảnh sản phẩm --}}
                <form action="{{ route('hinhanhsanpham.update', $hinhanh->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Chọn sản phẩm liên kết --}}
                    <div class="mb-3">
                        <label for="id_sanpham" class="form-label fw-bold">Sản phẩm</label>
                        <select name="id_sanpham" id="id_sanpham" class="form-select" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach ($sanphams as $sp)
                                <option value="{{ $sp->id }}" {{ $hinhanh->id_sanpham == $sp->id ? 'selected' : '' }}>
                                    {{ $sp->ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(!empty($hinhanh->hinhanh))
                    <div class="mb-3">
                        <strong>Tên Hình Ảnh:</strong> {{ $hinhanh->hinhanh }}
                    </div>
                @endif

                    {{-- Ảnh hiện tại --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hình ảnh hiện tại:</label><br>
                        @if ($hinhanh->hinhanh)
                            <img src="{{ $hinhanh->hinhanh }}" alt="Ảnh sản phẩm" class="img-thumbnail" width="200">
                        @else
                            <p class="text-muted fst-italic">Chưa có hình ảnh.</p>
                        @endif
                    </div>

                    {{-- Upload ảnh mới --}}
                    <div class="mb-3">
                        <label for="hinhanh" class="form-label fw-bold">Chọn hình ảnh mới (nếu muốn thay đổi):</label>
                        <input type="file" name="hinhanh" id="hinhanh" class="form-control" accept="image/*">
                        <small class="text-muted">Định dạng: jpeg, png, jpg, gif, webp | Tối đa 2MB</small>
                    </div>

                    {{-- Trạng thái hiển thị --}}
                    <div class="mb-3">
                        <label for="trangthai" class="form-label fw-bold">Trạng thái hiển thị:</label>
                        <select name="trangthai" id="trangthai" class="form-select">
                            <option value="Hiển thị" {{ $hinhanh->trangthai == 'Hiển thị' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="Tạm ẩn" {{ $hinhanh->trangthai == 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                        </select>
                    </div>

                    {{-- Nút gửi form và quay lại --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                        {{-- <a href="{{ route('hinhanhsanpham.index') }}" class="btn btn-secondary ms-2">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a> --}}
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

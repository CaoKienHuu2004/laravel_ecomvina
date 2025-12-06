@extends('layouts.app')

@section('title', 'Cập nhật danh mục | Quản trị hệ thống Siêu Thị Vina')

{{-- // controller truyền xuống $danhmuc --}}
{{-- // các route sư dụng danhmuc.update --- của breadcrumb danhmuc.index trang-chu  --}}
{{--
    $danhmuc->logo chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/categories/tenfilehinhanh.jpg
--}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title="Sửa Danh Mục"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách danh mục', 'route' => 'danhmuc.index']
                ]"
                active="Chỉnh sửa"
            />
        </div>

        {{-- HIỆN THÔNG BÁO --}}
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

        <div class="card shadow-sm">
            <div class="card-body">
                <form class="row" action="{{ route('danhmuc.update', $danhmuc->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Tên danh mục -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="ten" class="form-control" value="{{ old('ten', $danhmuc->ten) }}">
                            @error('ten')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Slug (tự động sinh từ tên hoặc cho chỉnh sửa) -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $danhmuc->slug) }}" readonly>
                            <small class="text-muted success">Slug là duy nhất, được tạo tự động từ tên danh mục.</small>
                        </div>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select" name="trangthai">
                                <option value="Hiển thị" {{ old('trangthai', $danhmuc->trangthai) == 'Hiển thị' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="Tạm ẩn" {{ old('trangthai', $danhmuc->trangthai) == 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                            </select>
                            @error('trangthai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Loại danh mục -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Loại danh mục <span class="text-danger">*</span></label>
                            <select class="form-select" name="parent">
                                <option value="Cha" {{ old('parent', $danhmuc->parent) == 'Cha' ? 'selected' : '' }}>Danh mục cha</option>
                                <option value="Con" {{ old('parent', $danhmuc->parent) == 'Con' ? 'selected' : '' }}>Danh mục con</option>
                            </select>
                            @error('parent')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Logo danh mục</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            @if($danhmuc->logo)
                                <div class="mt-2">
                                    <img src="{{ $danhmuc->logo }}" alt="Logo hiện tại" style="width: 100px; height: auto; border-radius: 6px; border: 1px solid #ddd;">
                                </div>
                            @endif
                            @error('logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="col-lg-12 text-start mt-3">
                        <button type="submit" class="btn btn-primary">
                            Cập nhật danh mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Thêm danh mục | Quản trị hệ thống Siêu Thị Vina')
{{--
    // Controller truyền xuống không
    // các route sư dụng danhmuc.store --- của breadcrumb danhmuc.index trang-chu
--}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title="Tạo Mới Danh Mục"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách danh mục', 'route' => 'danhmuc.index']
                ]"
                active="Thêm mới"
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
                <form action="{{ route('danhmuc.store') }}" method="POST" enctype="multipart/form-data" class="row">
                    @csrf

                    <!-- Tên danh mục -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="ten" class="form-control" placeholder="Nhập tên danh mục" value="{{ old('ten') }}" required >
                        @error('ten')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Slug danh mục -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" readonly disabled>
                        <div class="form-text">Slug phải là duy nhất, tự động xin theo tên danh mục.</div>
                        @error('slug')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Logo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo danh mục <span class="text-danger">*</span></label>
                        <input type="file" name="logo" class="form-control" accept="image/*" required>
                        @error('logo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Loại danh mục -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại danh mục <span class="text-danger">*</span></label>
                        <select name="parent" class="form-select" required>
                            <option value="Cha" {{ old('parent') == 'Cha' ? 'selected' : '' }}>Danh mục cha</option>
                            <option value="Con" {{ old('parent') == 'Con' ? 'selected' : '' }}>Danh mục con</option>
                        </select>
                        @error('parent')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="trangthai" class="form-select" required>
                            <option value="Hiển thị" {{ old('trangthai') == 'Hiển thị' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="Tạm ẩn" {{ old('trangthai') == 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                        </select>
                        @error('trangthai')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nút hành động -->
                    <div class="col-12 mt-4 text-start">
                        <button type="submit" class="btn btn-primary">
                            Tạo danh mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

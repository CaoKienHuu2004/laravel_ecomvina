@extends('layouts.app')

@section('title', 'Thêm thương hiệu mới')
{{-- // controller truyền xuống không  --}}
{{-- // các route sư dụng thuonghieu.store --- của breadcrumb thuonghieu.index trang-chu  --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title="Tạo Mới Thương Hiệu"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách thương hiệu', 'route' => 'thuonghieu.index']
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

        <form action="{{ route('thuonghieu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="ten" class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" id="ten" name="ten" class="form-control" value="{{ old('ten') }}" required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" id="slug" name="slug" class="form-control" readonly disabled>
                <div class="form-text">Slug phải là duy nhất, tự động xin theo tên thương hiệu.</div>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo thương hiệu <span class="text-danger">*</span></label>
                <input type="file" id="logo" name="logo" class="form-control" accept="image/*" required>
                <div class="form-text">File ảnh định dạng jpeg, png, jpg, gif, webp, tối đa 2MB.</div>
            </div>

            <div class="mb-3">
                <label for="mota" class="form-label">Mô tả</label>
                <textarea id="mota" name="mota" rows="4" class="form-control">{{ old('mota') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select id="trangthai" name="trangthai" class="form-select" required>
                    <option value="Hoạt động" {{ old('trangthai') == 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="Tạm khóa" {{ old('trangthai') == 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
                    <option value="Dừng hoạt động" {{ old('trangthai') == 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                {{-- <a href="{{ route('thuonghieu.index') }}" class="btn btn-secondary">← Quay lại</a> --}}
                <button type="submit" class="btn btn-primary">Lưu thương hiệu</button>
            </div>
        </form>
    </div>
</div>
@endsection

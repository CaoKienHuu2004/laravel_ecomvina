@extends('layouts.app')

@section('title', 'Chỉnh sửa thương hiệu')
{{-- // controller truyền xuống $thuonghieu --}}
{{-- // các route sư dụng thuonghieu.update --- của breadcrumb thuonghieu.index trang-chu  --}}
{{-- $thuonghieu->logo: Link http://148.230.100.215/assets/client/images/brands/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title="Sửa Thương Hiệu"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách thương hiệu', 'route' => 'thuonghieu.index']
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

        <form action="{{ route('thuonghieu.update', $thuonghieu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="ten" class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" id="ten" name="ten" class="form-control" value="{{ old('ten', $thuonghieu->ten) }}" required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $thuonghieu->slug) }}" readonly disabled>
                <div class="form-text">Slug phải là duy nhất, tự động sinh theo tên thương hiệu.</div>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo thương hiệu</label>
                @if ($thuonghieu->logo)
                    <div class="mb-2">
                        <img src="{{ $thuonghieu->logo }}" alt="Logo hiện tại" width="120" style="border:1px solid #ddd; padding:3px; border-radius:5px;">
                    </div>
                @endif
                <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                <div class="form-text">Nếu muốn thay đổi logo, chọn file mới. Định dạng jpeg, png, jpg, gif, webp, tối đa 2MB.</div>
            </div>

            <div class="mb-3">
                <label for="mota" class="form-label">Mô tả</label>
                <textarea id="mota" name="mota" rows="4" class="form-control">{{ old('mota', $thuonghieu->mota) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select id="trangthai" name="trangthai" class="form-select" required>
                    <option value="Hoạt động" {{ old('trangthai', $thuonghieu->trangthai) == 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="Tạm khóa" {{ old('trangthai', $thuonghieu->trangthai) == 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
                    <option value="Dừng hoạt động" {{ old('trangthai', $thuonghieu->trangthai) == 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                {{-- <a href="{{ route('thuonghieu.index') }}" class="btn btn-secondary">← Quay lại</a> --}}
                <button type="submit" class="btn btn-primary">Cập nhật thương hiệu</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Thêm danh mục | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Thêm danh mục sản phẩm</h4>
                <h6>Tạo danh mục sản phẩm mới</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('danhmuc.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('danhmuc.store') }}" method="POST" enctype="multipart/form-data" class="row">
                    @csrf

                    <!-- Tên danh mục -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="ten" class="form-control" placeholder="Nhập tên danh mục" value="{{ old('ten') }}">
                        @error('ten')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Logo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo danh mục</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @error('logo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Loại danh mục -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại danh mục <span class="text-danger">*</span></label>
                        <select name="parent" class="form-select">
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
                        <select name="trangthai" class="form-select">
                            <option value="Hiển thị" {{ old('trangthai') == 'Hiển thị' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="Tạm ẩn" {{ old('trangthai') == 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                        </select>
                        @error('trangthai')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nút hành động -->
                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i> Tạo danh mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

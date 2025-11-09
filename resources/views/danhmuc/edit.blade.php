@extends('layouts.app')

@section('title', 'Cập nhật danh mục | Quản trị hệ thống Siêu Thị Vina')
{{--
    $danhmuc->logo chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/categories/tenfilehinhanh.jpg
--}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Cập nhật danh mục sản phẩm</h4>
                <h6>Chỉnh sửa thông tin danh mục</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('danhmuc.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
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
                            <small class="text-muted success">Slug được tạo tự động từ tên danh mục.</small>
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
                    <div class="col-lg-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            💾 Cập nhật danh mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

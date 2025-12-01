@extends('layouts.app')

@section('title', 'Chỉnh sửa thương hiệu')
{{-- $thuonghieu->logo: Link http://148.230.100.215/assets/client/images/brands/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header mb-4">
            <div class="page-title">
                <h4>Chỉnh sửa thương hiệu</h4>
                <h6>Sửa thông tin thương hiệu "{{ $thuonghieu->ten }}"</h6>
            </div>
        </div>

        {{-- Hiển thị lỗi validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('thuonghieu.update', $thuonghieu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="ten" class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" id="ten" name="ten" class="form-control" value="{{ old('ten', $thuonghieu->ten) }}" required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $thuonghieu->slug) }}" required>
                <div class="form-text">Slug phải là duy nhất, không dấu, viết liền.</div>
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
                <a href="{{ route('thuonghieu.index') }}" class="btn btn-secondary">← Quay lại</a>
                <button type="submit" class="btn btn-success">💾 Cập nhật thương hiệu</button>
            </div>
        </form>
    </div>
</div>
@endsection

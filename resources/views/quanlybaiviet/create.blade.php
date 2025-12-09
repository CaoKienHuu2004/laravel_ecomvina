{{-- resources/views/quanlybaiviet/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tạo bài viết mới | Quản trị Siêu Thị Vina')

@section('content')
<div class="page-wrapper">

    <!-- Thông báo -->
    <div class="error-log mb-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Tạo bài viết mới</h4>
                <h6>Thêm bài viết, tin tức, hướng dẫn...</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form class="row g-3" action="{{ route('baiviet.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Tiêu đề bài viết -->
                    <div class="col-lg-8 col-12">
                        <label class="form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input type="text" name="tieude" class="form-control" value="{{ old('tieude') }}" placeholder="Nhập tiêu đề bài viết..." required>
                        @error('tieude') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- TRẠNG THÁI – ĐÃ SỬA ĐÚNG VỚI DATABASE -->
                    <div class="col-lg-4 col-12">
                        <label class="form-label">Trạng thái</label>
                        <select name="trangthai" class="form-select">
                            <option value="Hiển thị" {{ old('trangthai', 'Hiển thị') == 'Hiển thị' ? 'selected' : '' }}>
                                Hiển thị
                            </option>
                            <option value="Tạm ẩn" {{ old('trangthai') == 'Tạm ẩn' ? 'selected' : '' }}>
                                Tạm ẩn
                            </option>
                        </select>
                    </div>

                    <!-- Mô tả ngắn (tóm tắt) – nếu bạn không dùng thì để trống cũng được -->
                    <div class="col-12">
                        <label class="form-label">Mô tả ngắn (hiển thị ở danh sách)</label>
                        <textarea name="mota" class="form-control" rows="3" placeholder="Tóm tắt ngắn gọn... (không bắt buộc)">{{ old('mota') }}</textarea>
                    </div>

                    <!-- Nội dung chi tiết -->
                    <div class="col-12">
                        <label class="form-label">Nội dung bài viết <span class="text-danger">*</span></label>
                        <textarea name="noidung" id="noidung" class="form-control">{{ old('noidung') }}</textarea>
                        @error('noidung') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Ảnh bài viết -->
                    <div class="col-lg-6 col-12">
                        <label class="form-label">Ảnh bài viết (thumbnail)</label>
                        <input type="file" name="hinhanh" class="form-control" accept="image/*">
                        <small class="text-muted">Để trống nếu chưa có ảnh</small>
                        @error('hinhanh') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Người viết -->
                    <div class="col-lg-6 col-12">
                        <label class="form-label">Người viết <span class="text-danger">*</span></label>
                        <select name="id_nguoidung" class="form-select" required>
                            <option value="">-- Chọn người viết --</option>
                            @foreach($nguoiDung as $nd)
                                <option value="{{ $nd->id }}" {{ old('id_nguoidung') == $nd->id ? 'selected' : '' }}>
                                    {{ $nd->username }} ({{ $nd->hoten ?? $nd->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_nguoidung') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Nút submit -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-submit me-2">Xuất bản bài viết</button>
                        <a href="{{ route('baiviet.index') }}" class="btn btn-cancel">Hủy bỏ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script>
    let editor;
    ClassicEditor
        .create(document.querySelector('#noidung'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
        })
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => console.error(error));

    // Đồng bộ dữ liệu CKEditor khi submit
    document.querySelector('form').addEventListener('submit', () => {
        if (editor) {
            document.querySelector('#noidung').value = editor.getData();
        }
    });
</script>
@endsection

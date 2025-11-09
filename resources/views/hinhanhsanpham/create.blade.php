@extends('layouts.app')

@section('title', 'Thêm hình ảnh sản phẩm')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h2 class="text-center">🖼️ Thêm hình ảnh sản phẩm</h2>
                <h6 class="text-center text-muted">Thêm mới hình ảnh cho sản phẩm</h6>
            </div>
        </div>

        <div class="card shadow-sm p-4">
            {{-- Hiển thị thông báo lỗi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Lỗi!</strong> Vui lòng kiểm tra lại các trường nhập.<br><br>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form thêm mới --}}
            <form action="{{ route('hinhanhsanpham.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf

                {{-- Chọn sản phẩm --}}
                <div class="mb-3">
                    <label for="id_sanpham" class="form-label fw-bold">Sản phẩm</label>
                    <select name="id_sanpham" id="id_sanpham" class="form-select" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach ($sanphams as $sp)
                            <option value="{{ $sp->id }}">{{ $sp->ten ?? 'Sản phẩm #' . $sp->id }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Upload hình ảnh --}}
                <div class="mb-3">
                    <label for="hinhanh" class="form-label fw-bold">Hình ảnh</label>
                    <input type="file" name="hinhanh" id="hinhanh" class="form-control" accept="image/*" required>
                    <small class="text-muted">Chỉ chấp nhận các định dạng: jpeg, png, jpg, gif, webp (tối đa 2MB).</small>
                </div>

                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label for="trangthai" class="form-label fw-bold">Trạng thái hiển thị</label>
                    <select name="trangthai" id="trangthai" class="form-select" required>
                        <option value="Hiển thị">Hiển thị</option>
                        <option value="Tạm ẩn">Tạm ẩn</option>
                    </select>
                </div>

                {{-- Nút hành động --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('hinhanhsanpham.index') }}" class="btn btn-secondary">
                        ← Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Lưu hình ảnh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

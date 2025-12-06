@extends('layouts.app')

@section('title', 'Thêm hình ảnh sản phẩm')

{{--
    // Controller truyền xuống không
    // các route sư dụng hinhanhsanpham.store --- của breadcrumb hinhanhsanpham.index trang-chu
--}}

@section('content')
<div class="page-wrapper">
    <div class="content">


        <div class="page-header">
            <x-header.breadcrumb
                title="Tạo Mới Hình Ảnh Sản Phẩm"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách hình ảnh sản phẩm', 'route' => 'hinhanhsanpham.index']
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

        <div class="card shadow-sm p-4">


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
                    {{-- <a href="{{ route('hinhanhsanpham.index') }}" class="btn btn-secondary">
                        ← Quay lại
                    </a> --}}
                    <button type="submit" class="btn btn-primary">
                        Lưu hình ảnh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

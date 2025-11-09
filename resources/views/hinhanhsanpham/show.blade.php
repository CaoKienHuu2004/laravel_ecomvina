@extends('layouts.app')

@section('title', 'Chi tiết hình ảnh sản phẩm')

{{--
    $hinhanh->hinhanh chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg
--}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3 class="text-primary">Chi tiết hình ảnh sản phẩm</h3>
                <h6>Thông tin chi tiết về hình ảnh sản phẩm</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="mb-3">
                    <strong>ID:</strong> {{ $hinhanh->id }}
                </div>

                <div class="mb-3">
                    <strong>Sản phẩm:</strong> {{ $hinhanh->sanpham->ten ?? 'Không xác định' }}
                </div>

                <div class="mb-3">
                    <strong>Hình ảnh:</strong><br>
                    @if ($hinhanh->hinhanh)
                        {{-- Hiển thị hình ảnh sử dụng URL từ DB --}}
                        <img src="{{ $hinhanh->hinhanh }}" class="img-thumbnail" width="250" alt="Hình ảnh sản phẩm">
                    @else
                        <p class="text-muted fst-italic">Không có hình ảnh</p>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Trạng thái:</strong>
                    <span class="{{ $hinhanh->trangthai == 'Hiển thị' ? 'text-success' : 'text-danger' }}">
                        {{ $hinhanh->trangthai }}
                    </span>
                </div>

                <div class="mt-3">
                    <a href="{{ route('hinhanhsanpham.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
